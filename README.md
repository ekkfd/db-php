MYSQL database functions [db.php] made by ekk v1.0

**functions list**
    dbConnect() [-] connect to database using config
    dbDisconnect() [-] disconnect from database
    dbQuery($query) [-] make query to database
    dbGetLines($table, $column, $value) [-] get lines array by column = value
    dbFilter($table, $filters) [-]  get lines array by filter (columns = values)
    dbGetLine($table, $column, $value) [-] get 1 line by column = value
    dbAddLine($table, $columns, $values) [-] create line
    dbDeleteLine($table, $column, $value) [-] delete line
    dbUpdateCells($table, $column, $value, $columns, $values) [-] update cells by column = value
    dbGetMaxID($table) [-] get max id in table
    dbJack($data, $splitter) [-] Jack the ripper :D (split the string and removes emptiness)


**{database} example**
    {table}
    `id` | `name` | `password`
    1	 |  Ivan  |  pass1
    2	 |  Egor  |  pass2
    3	 |  Dima  |  pass3
    4	 |  Dima  |  pass4
    5	 |  Dima  |  pass4
     ..  |  ....  |  ....


**dbQuery($query)**
    + require $query [SQL code]
	- return [MYSQL-object]
	* example: dbQuery('SELECT * FROM `database`');


**dbGetLines($table, $column, $value)**
    + require:
        ++ $table  [text/table name],
        ++ $column [text/column name where search],
        ++ $value  [text(int)/value wich search in column]
            +++ $column == '*' and $value == '*' to select all from table

    - return [Array(Array('field' => 'value'))] or `false` if 0 rows returned

    * example:
        *call* dbGetLines('table', 'name', 'Dima');
        *return* 
        [
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


**dbFilter($table, $filters)**
	+ require:
        ++ $table 	[text/table name],
        ++ $filters [Array('field' => 'value')]
    
    - return [Array(Array('field' => 'value'))] or `false` if 0 rows returned

    * example:
        *call* dbFilter('table', Array('name' => 'Dima', 'password' => 'pass4'));
        *return* 
        [
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


**dbGetLine($table, $column, $value)**
	+ require:
            ++ $table  [text/table name],
            ++ $column [text/column name where search],
            ++ $value  [text(int)/value wich search in column]
    - return [Array('field' => 'value')] or `false` if rows != 1 returned

    * example:
        *call* dbFilter('table', 'id', 4);
        *return*
        [
            'id'   		=> '4',
            'name' 		=> 'Dima',
            'password'  => 'pass4'
        ]


**dbAddLine($table, $columns, $values)**
	+ require:
        ++ $table	[text/table name],
        ++ $columns [Array('column1', 'column2',)],
        ++ $values  [Array('value1', 'value2',)]
		
    - return [~nut~]

    * example:
        *call* dbAddLine('table', ['name', 'password'], ['Artem', 'pass6']);
        *return* [~nut~]


**dbDeleteLine($table, $column, $value)**
	+ require:
        ++ $table	[text/table name],
        ++ $column  [text/column name where search],
        ++ $value   [text(int)/value wich search in column]
		
    - return [~nut~]

    * example:
        *call* dbDeleteLine('table', 'id', 6);
        *return* [~nut~]


**dbUpdateCells($table, $column, $value, $columns, $values)**
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


**dbGetMaxID($table)**
	+ require:
        $table [text/table name]
    
    - return [int]

    * example:
        *call* dbGetMaxID('table');
        *return* 5


**dbJack($data, $splitter)**
	+ require:
        $data 	  [text/original string],
        $splitter [text/splitter symb]
    - return Array('val1', 'val2',)

    * example:
        *call* dbJack('a,.,b,.,c,.,d', ',.,');
        *return* ['a', 'b', 'c', 'd']
