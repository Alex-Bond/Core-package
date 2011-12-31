<?php

if (! defined ( 'KKZSYSTEM' )) {
	die ( "Error" );
}

class core {
	private $date;
	private $db;
	private $core_api;
	private $core_url;
	private $index;
	private $site;
	private $do;
	public $user;
	
	function __construct($sql, $core, $core_url, $index, $site) {
		$this->date = date ( 'Y-m-d H:i:s' );
		$this->db = &$sql;
		$this->core_api = &$core;
		$this->core_url = $core_url;
		$this->index = $index;
		$this->site = $site;
		
	}
	public function check($do) {
		$this->do = $do;
		$this->cron();
		if ($this->do != 'ajax' and isset ( $_COOKIE ['PHPSESSID'] )) {
			if ($this->db_check ( $_COOKIE ['PHPSESSID'] ) === false) {
				header ( "Location: " . $this->core_url . "?site=" . $this->site );
			} else {
				return $this->user;
			}
		} elseif ($this->do != 'ajax' and isset ( $_GET ['key'] )) {
			if ($this->login ( $_GET ['key'] ) === true) {
				header ( "Location: " . $this->index );
			} else {
				header ( "Location: " . $this->core_url . "?site=" . $this->site );
			}
		} elseif ($this->do != 'ajax') {
			header ( "Location: " . $this->core_url . "?site=" . $this->site );
		} else {
			$this->db_check ( $_COOKIE ['PHPSESSID'] );
			return $this->user;
		}
	}
	private function db_check($ses) {
		
		$recordSet = &$this->db->Execute ( "select * from sessions WHERE sessions.name=" . $this->db->qstr ( $ses ) );
		
		if (! $recordSet)
			core_error::db_exec_error ( $this->db->ErrorMsg () );
		
		if ($recordSet->RecordCount () == 0) {
			setcookie ( 'PHPSESSID', null );
			
			return false;
		} else {
			$apiuser = $this->core_api->getuser ( $recordSet->fields ['userid'] );
			$this->user ['id'] = $apiuser ['userid'];
			$this->user ['name'] = $apiuser ['firstname'];
			$this->user ['lastname'] = $apiuser ['lastname'];
			$this->user ['fathername'] = $apiuser ['middlename'];
			$this->user ['group'] = $apiuser ['usergroup'];
			$this->user ['access'] = $apiuser ['access'];
			$this->user ['access'] = $apiuser ['access'];
			$this->user ['sesuid'] = $recordSet->fields ['name'];
			$this->user ['sesid_base'] = $recordSet->fields ['id'];
			$exp = date ( 'U' ) + 300;
			$sql1 = "update `sessions` set `exp`=$exp where name=" . $this->db->qstr ( $this->user ['sesuid'] );
			if ($this->db->Execute ( $sql1 ) === false) {
				core_error::db_update_error ( $this->db->errorMsg () );
			}
			
			return true;
		}
	}
	private function login($key) {
		$apiuser = $this->core_api->login ( $key );
		if ($apiuser ['userid'] < 1) {
			return false;
		} else {
			$this->user ['id'] = $apiuser ['userid'];
			$this->user ['name'] = $apiuser ['firstname'];
			$this->user ['lastname'] = $apiuser ['lastname'];
			$this->user ['fathername'] = $apiuser ['middlename'];
			$this->user ['group'] = $apiuser ['usergroup'];
			$this->user ['access'] = $apiuser ['access'];
			$exp = date ( 'U' ) + 300;
			$sql1 = "update `sessions` set `exp`=" . $exp . " where name=" . $this->db->qstr ( $this->user ['sesuid'] );
			if ($this->db->Execute ( $sql1 ) === false) {
				core_error::db_update_error ( $this->db->errorMsg () );
			}
			
			@session_start ();
			
			core_stats::add ( 'login', 'yes', $this->user );
			
			//Sessions
			$name = session_id ();
			$exp = date ( 'U' ) + 300;
			$sql = "insert into `sessions` (`name`,`userid`,`exp`) values (" . $this->db->qstr ( $name ) . "," . $this->db->qstr ( $this->user ['id'] ) . "," . $exp . ")";
			if ($this->db->Execute ( $sql ) === false) {
				core_error::db_insert_error ( $this->db->ErrorMsg () );
			} else {
				return true;
			}
		}
	}
	private function cron() {
		$date = date ( 'U' );
		
		$sql = "delete from `sessions` where exp<" . $this->db->qstr ( $date );
		if ($this->db->Execute ( $sql ) === false) {
			core_error::db_delete_error ( $this->db->ErrorMsg () );
		}
	}
}