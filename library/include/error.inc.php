<?php
class core_error {
	public function db_exec_error($err) {
		core_error::show ( "Data Base", "exec query", $err );
	}
	public function db_insert_error($err) {
		core_error::show ( "Data Base", "inserting to data base", $err );
	}
	public function db_update_error($err) {
		core_error::show ( "Data Base", "updating in data base", $err );
	}
	public function db_delete_error($err) {
		core_error::show ( "Data Base", "deleting from data base", $err );
	}
	public function file_delete_error($file){
		core_error::show ( "Files", "deleting from hard drive", "Do not have acces to file ".$file );
	}
	public function access_error(){
		core_error::show ("User Access", "in taking access to page","403 - Forbidden");
	}
	public function show($type, $error, $server) {
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Error :: Core sytems</title>
</head>

<body>
<p><strong>CORE SYSTEMS</strong></p>
<p>Error in ' . $type . ' part.</p>
<p>Error ' . $error . '.</p>
<p>Server answer: ' . $server . '</p>
</body>
</html>
		';
		die ();
	}
}
?>