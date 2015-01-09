<?php

class MyDb {
  private $db;

  public function __construct($host, $username, $password, $dbName) {
  	$this->db = mysql_connect($host, $username, $password);
  	mysql_select_db($dbName);
  }

  public function __destruct() {
	mysql_close($this->db);
  }

  public function select($query) {
  	return $this->toArray(mysql_query($query));
  }
  
  private function toArray($res) {
  	$arr = array();

  	while ($row = mysql_fetch_array($res)) {
  		$cols = array();

  		foreach ($row as $key => $val) {
  			if (is_string($key))
  				$cols[$key] = $val
  		}

  		array_push($arr, $cols);

  	}

  	return $arr;
  }
}  


class MatchType {
  const BEGINS_WITH = 0;
  const ENDS_WITH   = 1;
  const CONTAINS    = 2;
}


function processRequest($query, $matchType) {
  $db = new MyDb("localhost", "root", "", "instant_search");
  $like = "'%{$query}%'";

  switch ($matchType) {
    case MatchType::BEGINS_WITH:
      $like = "'%{$query}%'";
    break;

    case MatchType::ENDS_WITH:
      $like = "'%{$query}%'";
    break;  
  }

  $selectQuery = "SELECT name FROM names WHERE name LIKE {$like} ORDER BY name ASC";
  $results = $db->select($selectQuery);

  echo json_encode(array("names" => $results));

}


$query = $_POST["query"];
$matchType = isset($_POST["matchType"]) ? $_POST["matchType] : MatchType::CONTAINS;

processRequest($query, $matchType);