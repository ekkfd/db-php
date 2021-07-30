<?php
// MYSQL database functions [db.php] made by ekk v1.0

	/*
		* {database} example *
		{table}
		`id` | `name` | `password`
		 1	 |  Ivan  |  pass1
		 2	 |  Egor  |  pass2
		 3	 |  Dima  |  pass3
		 4	 |  Dima  |  pass4
		 5	 |  Dima  |  pass4
		 ..  |  ....  |  ....
	*/ 

	// include  database config
	include 'modules/config.php';

	// creating database link
	$dbLink = Null;

	/*
		creating connection to database
	*/
	function dbConnect() {
		global $dbBaseName, $dbUserName, $dbPassword, $dbHostName, $dbLink;
		$dbLink = mysqli_connect($dbHostName, $dbUserName, $dbPassword, $dbBaseName);
		mysqli_query($dbLink, "SET NAMES 'utf8';");
		mysqli_query($dbLink, "SET CHARACTER SET 'utf8';");
		mysqli_query($dbLink, "SET SESSION collation_connection = 'utf8_general_ci';");
	}
	
	/*
		close connection to database
	*/
	function dbDisconnect() {
		global $dbLink;
		mysqli_close($dbLink);
	}
	
	/*
		make query
		+ require $query [SQL code]
		- return [MYSQL-object]
		* example: dbQuery('SELECT * FROM `database`');
	*/
	function dbQuery($query) {
		global $dbLink;
		dbConnect();
		$ret =  mysqli_query($dbLink, $query);
		dbDisconnect();
		return $ret;
	}
	
	/*
		get lines by column = value

		+ require:
				++ $table  [text/table name],
				++ $column [text/column name where search],
				++ $value  [text(int)/value wich search in column]
					+++ $column == '*' and $value == '*' to select all from table

		- return [Array(Array('field' => 'value'))] or `false` if 0 rows returned

		* example:
				*call* dbGetLines('table', 'name', 'Dima');
				*return* [
							[
								'id'   		=> '3',
								'name' 		=> 'Dima',
								'password'  => 'pass3'
							],
							[
								'id'   		=> '4',
								'name' 		=> 'Dima',
								'password'  => 'pass4'
							]
						 ]
	*/
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
	/*
		get lines by filter

		+ require:
			++ $table 	[text/table name],
			++ $filters [Array('field' => 'value')]
		
		- return [Array(Array('field' => 'value'))] or `false` if 0 rows returned

		* example:
			*call* dbFilter('table', Array('name' => 'Dima', 'password' => 'pass4'));
			*return* [
						[
							'id'   		=> '4',
							'name' 		=> 'Dima',
							'password'  => 'pass4'
						],
						[
							'id'   		=> '5',
							'name' 		=> 'Dima',
							'password'  => 'pass4'
						]
					 ]
	*/
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
	
	/*
		get 1 line by column = value

		+ require:
				++ $table  [text/table name],
				++ $column [text/column name where search],
				++ $value  [text(int)/value wich search in column]
		- return [Array('field' => 'value')] or `false` if rows != 1 returned

		* example:
			*call* dbFilter('table', 'id', 4);
			*return* [
						'id'   		=> '4',
						'name' 		=> 'Dima',
						'password'  => 'pass4'
					 ]
	*/
	function dbGetLine($table, $column, $value) {
		$q = dbQuery("SELECT * FROM `".$table."` WHERE `".$column."` = '".$value."'");
		if(mysqli_num_rows($q) == 1) $ret = mysqli_fetch_assoc($q);
		else $ret = false;
		return $ret;
	}
	
	/*
		create line

		+ require:
				++ $table	[text/table name],
				++ $columns [Array('column1', 'column2',)],
				++ $values  [Array('value1', 'value2',)]
		
		- return [~nut~]

		* example:
			*call* dbAddLine('table', ['name', 'password'], ['Artem', 'pass6']);
			*return* [~nut~]

	*/
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
	
	/*
		delete line

		+ require:
				++ $table	[text/table name],
				++ $column  [text/column name where search],
				++ $value   [text(int)/value wich search in column]
		
		- return [~nut~]

		* example:
			*call* dbDeleteLine('table', 'id', 6);
			*return* [~nut~]
	*/
	function dbDeleteLine($table, $column, $value) {
		$q = "DELETE FROM `".$table."` WHERE `".$column."` = '".$value."'";
		dbQuery($q);
	}

	/*
		update cells by column = value

		+ require:
				++ $table	[text/table name],
				++ $column  [text/column name where search],
				++ $value   [text(int)/value wich search in column],
				++ $columns [Array('column1', 'column2',)],
				++ $values  [Array('value1', 'value2',)]
		
		- return [~nut~]

		* example:
			*call* dbUpdateCells('table', 'id', '5', ['name', 'password'], ['Iva', 'pass123']);
			*return* [~nut~]
	*/
	function dbUpdateCells($table, $column, $value, $columns, $values) {
		$q = "UPDATE `".$table."` SET ";
		for($i=0; $i < count($columns); $i++) {
			$q .= "`".$columns[$i]."` = '".$values[$i]."'";
			if($i != count($columns)-1) $q .= ", ";
		}
		$q .= " WHERE `".$column."` = '".$value."'";
		dbQuery($q);
	}
	
	/*
		get max id in table

		+ require:
				$table [text/table name]
		
		- return [int]

		* example:
			*call* dbGetMaxID('table');
			*return* 5
	*/ 
	function dbGetMaxID($table) {
		return intval(mysqli_fetch_array(dbQuery('SELECT MAX(`id`) FROM `'.$table.'`'))[0]);
	}
	
	/*
		Jack the ripper :D (split the string and removes emptiness)

		+ require:
				$data 	  [text/original string],
				$splitter [text/splitter symb]
		- return Array('val1', 'val2',)

		* example:
			*call* dbJack('a,.,b,.,c,.,d', ',.,');
			*return* ['a', 'b', 'c', 'd']
	*/
	function dbJack($data, $splitter) {
		$ret = explode($splitter, $data);
		for($i = count($ret); $i >= 0 ; $i--) {
      if($ret[$i] == '') array_splice($ret, $i, 1);
		}
		return $ret;
	}
