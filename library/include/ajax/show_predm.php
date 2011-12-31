<?php //header('Content-type: text/html; charset=utf-8');


$com = $_GET ['com'];
global $sql, $content, $user, $settings;
if ($user ['access'] < $settings ['admin_access']) {
	$cheked = "AND `cheked`=1 AND `show`=1";
} else {
	$cheked = "";
}

global $core_api, $db;
$cour = $core_api->getcourses_id ( $com );
foreach ( $cour as $cor ) {

	$recordSet = &$db->Execute ( "select COUNT(*) as `count` from books WHERE course=" . $cor ['id'] . " $cheked  order by id" );
	
	if (! $recordSet) {
		core_error::db_exec_error ( $db->ErrorMsg () );
	}
	
	if ($recordSet->fields ['count'] > 0) {
		$content .= "<div class=\"com_row\" onclick=\"location.href='./?do=base&f=listiner&com=" . $cor ['id'] . "'\" onmouseover=\"this.style.background='url(images/predm_row_bg_full.png) no-repeat center left';\" onmouseout=\"this.style.background='';\">" . $cor ['name'] . "</div>";
	} else {
		$content .= "<div class=\"com_row\" style = \"color:#CCC\" title=\"У цьому предметі нема лiтератури.\">" . $cor ['name'] . "</div>";
	}

}
echo $content; 