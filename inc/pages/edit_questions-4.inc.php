<?php
$f_testid = (int)readGetVar('testid');
if(isset($_POST["box_questions"])) {
	foreach($_POST["box_questions"] as $f_questionid) { 
 createQuestionLink($f_testid, (int)$f_questionid);
}
} else {
	$f_questionid = (int)readGetVar('questionid'); 
	createQuestionLink($f_testid, $f_questionid);
}
 
gotoLocation('test-manager.php'.getURLAddon('?action=editt', array('action')));
?>