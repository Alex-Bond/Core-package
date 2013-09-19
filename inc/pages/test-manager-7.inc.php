<?php 
$f_testid = (int)readGetVar('testid');
$i_rSet1 = $g_db->Execute("SELECT test_notes FROM ".$srv_settings['table_prefix']."tests WHERE testid=".$f_testid);
if(!$i_rSet1) {
	showDBError(__FILE__, 1);
} else {
	if(!$i_rSet1->EOF) {
 echo '<p><font face=Arial size=2>'.nl2br($i_rSet1->fields["test_notes"]).'</font></p>';
}
$i_rSet1->Close();
}
?>
