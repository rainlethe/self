<?php 
class Mysql {
	function str_startsWith($haystack, $needle)
	{
		return $needle === "" || strpos($haystack, $needle) === 0;
	}

	// 문자열 끝
	function str_endsWith($haystack, $needle)
	{
		return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}

	// 문자열 포함
	function str_contains($haystack, $needle)
	{
		return strrpos($haystack, $needle) !== false;
	}

	var $_host = '';
	var $_dbname = '';
	var $_user = '';
	var $_pass = '';

	var $dbprefix = '';
	var $G_DEBUG = true;

	var $_dbh = null;

	public function __construct($host='',$dbname='', $user='',$pass='')
	{
		$this->_host = $host;
		$this->_dbname = $dbname;
		$this->_user = $user;
		$this->_pass = $pass;		
	}

	private function open_dbhandler()
	{
		try
		{

			$this->_dbh = new PDO("mysql:host=" . $this->_host . ";dbname=" . $this->_dbname, $this->_user, $this->_pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
			return true;

		}catch(PDOException $e)
		{
			//echo $e;
			return false;
		}
	}

	private function close_dbhandler()
	{
		try
		{
			$this->_dbh = null;
			return true;
		}
		catch(PDOException $e)
		{
			return false;
		}
	}

	public function query($query, $arrArgs, $isSelect=false)
	{
		if ($this->open_dbhandler() == false){return null;}

		$stmt = null;
		if ($isSelect)
		{
			$stmt = $this->_dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		}
		else
		{
			$stmt = $this->_dbh->prepare($query);
		}

		for ($i=0;$i<count( $arrArgs); $i++)
		{
		$stmt->bindParam($i+1, $arrArgs[$i]);
		}

		$stmt->execute();

		if ($isSelect)
		{
		$result = array();

		while($row = $stmt->fetch(PDO::FETCH_BOTH, PDO::FETCH_ORI_NEXT))
		{
		array_push($result, $row);
		}
		}
		else
		{
		$result = true;
		}

		$stmt = null;

			$this->close_dbhandler();

			return $result;
}

public function createtable($tablename, $columns)
{
$tablename = $this->dbprefix !== '' && $this->str_startsWith($tablename,$this->dbprefix) === false ? $this->dbprefix . $tablename : $tablename;

$primaryKey = $tablename . "_id";
$sql = "CREATE TABLE IF NOT EXISTS `$tablename` (";
		$sql .= "`$primaryKey` bigint(20) unsigned NOT NULL AUTO_INCREMENT,";
		foreach($columns as $col)
		{
		if ($this->str_endsWith($col, '_id'))
		{
		$sql .= " `$col` bigint(20) NULL,";
}
else
{
	$sql .= " `$col` text CHARACTER SET utf8 NULL,";
}

}

$sql .= "PRIMARY KEY (`$primaryKey`), ";
$sql .= "UNIQUE KEY `$primaryKey` (`$primaryKey`) ";
$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

//echo $sql;
return $this->query($sql, array());


	/*
	`test_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`test_value` text CHARACTER SET utf8 NULL,
	PRIMARY KEY (`test_id`),
	UNIQUE KEY `test_id` (`test_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8_general_ci AUTO_INCREMENT=1 ;
	*/
}

public function addcolumn($tablename, $columns)
    {
        $tablename = $this->dbprefix !== '' && $this->str_startsWith($tablename,$this->dbprefix) === false ? $this->dbprefix . $tablename : $tablename;
foreach($columns as $col)
{
if ($this->str_endsWith($col, '_id'))
{
$sql = "ALTER TABLE `$tablename` ADD `$col` bigint(20) NULL ;";
}
else
{
$sql = "ALTER TABLE `$tablename` ADD `$col` TEXT NULL ;";
}


$this->query($sql, array());
}

return true;
}

public function exist_table($tablename)
{
$tablename = $this->dbprefix !== '' && $this->str_startsWith($tablename,$this->dbprefix) === false ? $this->dbprefix . $tablename : $tablename;

$sql = "SHOW TABLES LIKE '$tablename'";
$result = $this->query($sql, array(), true);
if ($result == null){return false;}
if (count($result) === 0)
	{
		return false;
}
else
{
return true;
}
}

		public function createschema($tablename, $columns)
		{
		$tablename = $this->dbprefix !== '' && $this->str_startsWith($tablename,$this->dbprefix) === false ? $this->dbprefix . $tablename : $tablename;

		$is_exist_table =  $this->exist_table($tablename);
		if ($is_exist_table)
		{
		return $this->addcolumn($tablename, $columns);
}
else
{
return $this->createtable($tablename, $columns);
}
}

public function select($tablename, $condstr='', $condArr=array(), $orderby = '')
{
$tablename = $this->dbprefix !== '' && $this->str_startsWith($tablename,$this->dbprefix) === false ? $this->dbprefix . $tablename : $tablename;

$query = " select * from $tablename ";

if ($condstr != '')
	{
		$query = $query . " where $condstr";
}
if ($orderby != ''){
$query = $query . " order by " . $orderby;
}
return $this->query($query, $condArr, true);
}

public function simpleselect($tablename, $pk_value)
{
$tablename = $this->dbprefix !== '' && $this->str_startsWith($tablename,$this->dbprefix) === false ? $this->dbprefix . $tablename : $tablename;
return $this->select($tablename, $tablename . "_id = ?", array($pk_value));
}

public function simpleselect2($tablename, $pk_key, $pk_value){
$tablename = $this->dbprefix !== '' && $this->str_startsWith($tablename,$this->dbprefix) === false ? $this->dbprefix . $tablename : $tablename;
return $this->select($tablename, $pk_key . "= ?", array($pk_value));
}

private function getjustcolumns($arrkeyvalue)
{
$justcols = array();
foreach($arrkeyvalue as $key=>$value)
{
array_push($justcols, $key);
}
return $justcols;
}

function addnotcontaincol($tablename, $arrkeyvalue)
{
if ($this->G_DEBUG)
{
$tablename = $this->dbprefix !== '' && $this->str_startsWith($tablename,$this->dbprefix) === false ? $this->dbprefix . $tablename : $tablename;

$justcols = $this->getjustcolumns($arrkeyvalue);
$this->createschema($tablename, $justcols);
}
}

public function insert($tablename, $arrkeyvalue)
{
		$tablename = $this->dbprefix !== '' && $this->str_startsWith($tablename,$this->dbprefix) === false ? $this->dbprefix . $tablename : $tablename;

$this->addnotcontaincol($tablename, $arrkeyvalue);

$keys = '';
$valueq = '';
$values = array();
foreach($arrkeyvalue as $key=>$value)
{
if ($keys != '')
{
$keys = $keys . ",";
}
if ($valueq != '')
{
$valueq = $valueq . ",";
}

$keys = $keys . $key;
$valueq = $valueq . "?";

array_push($values, $value);
}

$query = "insert into $tablename ($keys) values ($valueq)";
return $this->query($query, $values);

}

public function update($tablename, $arrkeyvalue, $condstr, $condArr)
	{
	$tablename = $this->dbprefix !== '' && $this->str_startsWith($tablename,$this->dbprefix) === false ? $this->dbprefix . $tablename : $tablename;

		$this->addnotcontaincol($tablename, $arrkeyvalue);

		$query = "update $tablename set ";
		$updatebody = '';

		$arrQ = array();
		foreach($arrkeyvalue as $key=>$value)
		{
		if ($updatebody != '')
		{
		$updatebody = $updatebody . ",";
		}
		$updatebody = $updatebody . $key . ' = ?';
		array_push($arrQ, $value);
	}

	$query = $query . ' ' . $updatebody . ' where ' . $condstr;

	foreach($condArr as $ca)
	{
		array_push($arrQ, $ca);
	}


	return $this->query($query, $arrQ);
	}

	public function insert_or_update($tablename, $arrkeyvalue, $condstr, $condArr)
	{
	$tablename = $this->dbprefix !== '' && $this->str_startsWith($tablename,$this->dbprefix) === false ? $this->dbprefix . $tablename : $tablename;

	$this->addnotcontaincol($tablename, $arrkeyvalue);

	$select = $this->select($tablename, $condstr, $condArr);
	$result = '';

	if ($select != null && count($select) > 0 )
	{
			// update
			$result = $this->update($tablename, $arrkeyvalue, $condstr, $condArr);
	}
	else
			{
			// insert
			$result = $this->insert($tablename, $arrkeyvalue);
	}
	return $result;
	}



	public function delete($tablename, $condstr, $arrcond)
	{
	$tablename = $this->dbprefix !== '' && $this->str_startsWith($tablename,$this->dbprefix) === false ? $this->dbprefix . $tablename : $tablename;

		$query = " delete from $tablename where " . $condstr;
		return $this->query($query, $arrcond);
	}

	public function get_cond_id($tablename, $condstr, $arrcond)
	{
	$tablename = $this->dbprefix !== '' && $this->str_startsWith($tablename,$this->dbprefix) === false ? $this->dbprefix . $tablename : $tablename;

	$query = " select " . $tablename . "_id" . " from $tablename where " .$condstr;
		$result = $this->query($query, $arrcond, true);
			if ($result == null){return -1;}
					if (count($result) > 0){return $result[0][0];}
					return -1;
	}

	public function get_max_id($tablename)
	{
	$tablename = $this->dbprefix !== '' && $this->str_startsWith($tablename,$this->dbprefix) === false ? $this->dbprefix . $tablename : $tablename;

		$query = " select max(" . $tablename . "_id) as tid" . " from $tablename ";
		$result = $this->query($query, array(), true);
		if ($result == null){return -1;}
		if (count($result) > 0){return $result[0][0];}
			return -1;
		}
		}

?>