<?php
// MYSQL database functions [db.php] made by ekk v1.0

	// include  database config
	include 'modules/config.php';

	// creating database link
	$dbLink = Null;

	
	// -- [creating connection to database] -- //
	function dbConnect() {
		global $dbBaseName, $dbUserName, $dbPassword, $dbHostName, $dbLink;
		$dbLink = mysqli_connect($dbHostName, $dbUserName, $dbPassword, $dbBaseName);
		mysqli_query($dbLink, "SET NAMES 'utf8';");
		mysqli_query($dbLink, "SET CHARACTER SET 'utf8';");
		mysqli_query($dbLink, "SET SESSION collation_connection = 'utf8_general_ci';");
	}

	
	// -- [close connection to database] -- //
	function dbDisconnect() {
		global $dbLink;
		mysqli_close($dbLink);
	}

	
	// -- [make query] -- //
	function dbQuery($query) {
		global $dbLink;
		dbConnect();
		$ret =  mysqli_query($dbLink, $query);
		dbDisconnect();
		return $ret;
	}
	
	
	// -- [lines by column = value] -- //
	function dbGetLines($table, $column, $value) {
		if($column == '*' && $value == '*') $q = dbQuery("SELECT * FROM `".$table."`");
		else $q = dbQuery("SELECT * FROM `".$table."` WHERE `".$column."` = '".$value."'");
		if(mysqli_num_rows($q)) {
			$rows = array();
			while($row = mysqli_fetch_assoc($q)) array_push($rows, $row);
			$ret = $rows;
		} else $ret = false;
		return $ret;
	}
	

	// -- [get lines by filter] -- //
	function dbFilter($table, $filters) {
		$q = "SELECT * FROM `".$table.'` WHERE';
		$i = 1;
		foreach($filters as $key => $filter) {
			$q .= " `".$key."` = '".$filter."' ";
			if(count($filters) > $i) {
				$q .= " AND ";
			}
			$i++;
		}
		$ret = dbQuery($q);
		if(mysqli_num_rows($ret)) {
			$rows = array();
			while($row = mysqli_fetch_assoc($ret)) array_push($rows, $row);
			$ret = $rows;
		} else $ret = false;
		return $ret;
	}
	
	
	// -- [get 1 line by column = value] -- //
	function dbGetLine($table, $column, $value) {
		$q = dbQuery("SELECT * FROM `".$table."` WHERE `".$column."` = '".$value."'");
		if(mysqli_num_rows($q) == 1) $ret = mysqli_fetch_assoc($q);
		else $ret = false;
		return $ret;
	}
	
	
	// -- [create line] -- //
	function dbAddLine($table, $columns, $values) {
		$q = "INSERT INTO `".$table."` (";
		$c = 0;
		foreach($columns as $col) {
			$q .= "`".$col."`";
			$c++;
			if($c != (count($columns))) $q .= ", ";
		}
		$c = 0;
		$q .= ") VALUES (";
		foreach($values as $val) {
			$q .= "'".$val."'";
			$c++;
			if($c != count($values)) $q .= ", ";
		}
		$q .= ")";
		dbQuery($q);
	}
	

	// -- [delete line] -- //
	function dbDeleteLine($table, $column, $value) {
		$q = "DELETE FROM `".$table."` WHERE `".$column."` = '".$value."'";
		dbQuery($q);
	}

	
	// -- [update cells by column = value] -- //
	function dbUpdateCells($table, $column, $value, $columns, $values) {
		$q = "UPDATE `".$table."` SET ";
		for($i=0; $i < count($columns); $i++) {
			$q .= "`".$columns[$i]."` = '".$values[$i]."'";
			if($i != count($columns)-1) $q .= ", ";
		}
		$q .= " WHERE `".$column."` = '".$value."'";
		dbQuery($q);
	}
	
	
	// -- [get max id in table] -- //
	function dbGetMaxID($table) {
		return intval(mysqli_fetch_array(dbQuery('SELECT MAX(`id`) FROM `'.$table.'`'))[0]);
	}
	
	
	// -- [Jack the ripper :D (split the string and removes emptiness)] -- //
	function dbJack($data, $splitter) {
		$ret = explode($splitter, $data);
		for($i = count($ret); $i >= 0 ; $i--) {
			if($ret[$i] == '') array_splice($ret, $i, 1);
		}
		return $ret;
	}
