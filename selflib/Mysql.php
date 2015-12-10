<?php 
class Mysql {
	private function str_startsWith($haystack, $needle)
	{
		return $this->self->from($haystack)->startsWith($needle);
		//return $needle === "" || strpos($haystack, $needle) === 0;
	}

	// 문자열 끝
	private function str_endsWith($haystack, $needle)
	{
		return $this->self->from($haystack)->endsWith($needle);
		//return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}

	// 문자열 포함
	private function str_contains($haystack, $needle)
	{
		return $this->self->from($haystack)->contains($needle);
		//return strrpos($haystack, $needle) !== false;
	}

	var $_host = '';
	var $_dbname = '';
	var $_user = '';
	var $_pass = '';

	var $_dbPrefix = '';
	var $G_DEBUG = true;
	var $_dbh = null;

	// http://php.net/manual/kr/pdostatement.fetch.php
	var $_pdo_fetch = PDO::FETCH_ASSOC;


	public function __construct($connname='localhost')
	{
		include('MysqlConfig.php');
		$this->setConnectionInfo($mysqlconfig[$connname]['host'], $mysqlconfig[$connname]['dbname'], $mysqlconfig[$connname]['user'],$mysqlconfig[$connname]['pass'],$mysqlconfig[$connname]['dbprefix']);
	}

	public function setConnectionInfo($host='',$dbname='', $user='',$pass='', $dbPrefix = ''){
		$this->_host = $host;
		$this->_dbname = $dbname;
		$this->_user = $user;
		$this->_pass = $pass;
		$this->_dbPrefix = $dbPrefix;
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

	public function query($query, $arrArgs, $isSelect=false, $pdo_fetch = null)
	{	
		if ($pdo_fetch != null){
			$this->_pdo_fetch = $pdo_fetch;
		}

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

		for ($i=0;$i<count($arrArgs); $i++)
		{
			$stmt->bindParam($i+1, $arrArgs[$i]);
		}

		$stmt->execute();

		if ($isSelect)
		{
			$result = array();
			while($row = $stmt->fetch($this->_pdo_fetch, PDO::FETCH_ORI_NEXT))
			{					
				array_push($result, $row);
			}

			$result = $this->self->from($result);
		}
		else
		{
			$result = true;
		}

		$stmt = null;

		$this->close_dbhandler();
		return $result;
	}

	public function createTable($tablename, $columns)
	{
		$tablename = $this->_dbPrefix !== '' && $this->str_startsWith($tablename,$this->_dbPrefix) === false ? $this->_dbPrefix . $tablename : $tablename;

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
		
		return $this->query($sql, array());
	}

	public function addColumn($tablename, $columns)
    {
        $tablename = $this->_dbPrefix !== '' && $this->str_startsWith($tablename,$this->_dbPrefix) === false ? $this->_dbPrefix . $tablename : $tablename;
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

	public function isExistTable($tablename)
	{
		$tablename = $this->_dbPrefix !== '' && $this->str_startsWith($tablename,$this->_dbPrefix) === false ? $this->_dbPrefix . $tablename : $tablename;

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

	public function createSchema($tablename, $columns)
	{
		$tablename = $this->_dbPrefix !== '' && $this->str_startsWith($tablename,$this->_dbPrefix) === false ? $this->_dbPrefix . $tablename : $tablename;

		$is_isExistTable =  $this->isExistTable($tablename);
		if ($is_isExistTable)
		{
			return $this->addColumn($tablename, $columns);
		}
		else
		{
			return $this->createTable($tablename, $columns);
		}
	}

	public function select($tablename, $condstr='', $condArr=array(), $orderby = '', $pdo_fetch=null)
	{
		$tablename = $this->_dbPrefix !== '' && $this->str_startsWith($tablename,$this->_dbPrefix) === false ? $this->_dbPrefix . $tablename : $tablename;
		$query = " select * from $tablename ";

		if ($condstr != '')
		{
			$query = $query . " where $condstr";
		}
		if ($orderby != ''){
			$query = $query . " order by " . $orderby;
		}

		if ($pdo_fetch != null){
			$this->_pdo_fetch = $pdo_fetch;
		}

		return $this->query($query, $condArr, true);
	}

	public function selectByPkValue($tablename, $pk_value, $pdo_fetch = null)
	{
		$tablename = $this->_dbPrefix !== '' && $this->str_startsWith($tablename,$this->_dbPrefix) === false ? $this->_dbPrefix . $tablename : $tablename;

		if ($pdo_fetch != null){
			$this->_pdo_fetch = $pdo_fetch;
		}

		return $this->select($tablename, $tablename . "_id = ?", array($pk_value));
	}

	public function selectByPkKeyValue($tablename, $pk_key, $pk_value ,$pdo_fetch = null){
		$tablename = $this->_dbPrefix !== '' && $this->str_startsWith($tablename,$this->_dbPrefix) === false ? $this->_dbPrefix . $tablename : $tablename;
		if ($pdo_fetch != null){
			$this->_pdo_fetch = $pdo_fetch;
		}
		return $this->select($tablename, $pk_key . "= ?", array($pk_value));
	}

	private function getJustColumns($arrkeyvalue)
	{
		$justcols = array();
		foreach($arrkeyvalue as $key=>$value)
		{
			array_push($justcols, $key);
		}
		return $justcols;
	}

	public function addNotContainsCol($tablename, $arrkeyvalue)
	{
		if ($this->G_DEBUG)
		{
			$tablename = $this->_dbPrefix !== '' && $this->str_startsWith($tablename,$this->_dbPrefix) === false ? $this->_dbPrefix . $tablename : $tablename;
			$justcols = $this->getJustColumns($arrkeyvalue);
			$this->createSchema($tablename, $justcols);
		}
	}

	public function insert($tablename, $arrkeyvalue)
	{
		$tablename = $this->_dbPrefix !== '' && $this->str_startsWith($tablename,$this->_dbPrefix) === false ? $this->_dbPrefix . $tablename : $tablename;
		$this->addNotContainsCol($tablename, $arrkeyvalue);

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
		$tablename = $this->_dbPrefix !== '' && $this->str_startsWith($tablename,$this->_dbPrefix) === false ? $this->_dbPrefix . $tablename : $tablename;
		$this->addNotContainsCol($tablename, $arrkeyvalue);
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

	public function insertOrUpdate($tablename, $arrkeyvalue, $condstr, $condArr)
	{
		$tablename = $this->_dbPrefix !== '' && $this->str_startsWith($tablename,$this->_dbPrefix) === false ? $this->_dbPrefix . $tablename : $tablename;

		$this->addNotContainsCol($tablename, $arrkeyvalue);

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
		$tablename = $this->_dbPrefix !== '' && $this->str_startsWith($tablename,$this->_dbPrefix) === false ? $this->_dbPrefix . $tablename : $tablename;
		$query = " delete from $tablename where " . $condstr;
		return $this->query($query, $arrcond);
	}

	public function getCondId($tablename, $condstr, $arrcond)
	{
		$tablename = $this->_dbPrefix !== '' && $this->str_startsWith($tablename,$this->_dbPrefix) === false ? $this->_dbPrefix . $tablename : $tablename;

		$query = " select " . $tablename . "_id" . " from $tablename where " .$condstr;
		$result = $this->query($query, $arrcond, true);
		if ($result == null){return -1;}
		if ($result->count() > 0){return $result->get(0,0);}
		return -1;
	}

	public function getMaxId($tablename , $pkname = null)
	{
		$tablename = $this->_dbPrefix !== '' && $this->str_startsWith($tablename,$this->_dbPrefix) === false ? $this->_dbPrefix . $tablename : $tablename;

		if ($pkname == null){
			$pkname = $tablename . "_id";

		}

		$query = " select max($pkname) as tid" . " from $tablename ";
		$result = $this->query($query, array(), true);
		if ($result == null){return -1;}
		if ($result->count() > 0){return $result->get(0,'tid');}
		
		return -1;
	}

	public function getTableList(){
		$query = "show tables";		
		$this->_pdo_fetch = PDO::FETCH_NUM;
		
		return $this->query($query, array(),true);
	}

	public function GetColumnList($tablename){
		$query = "show columns from $tablename";
		
		return $this->query($query, array(),true);
	}
}

?>