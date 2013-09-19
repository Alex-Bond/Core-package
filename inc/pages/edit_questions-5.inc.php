<?php
$f_testid = (int)readGetVar('testid');
if(isset($_POST["box_qlinks"]) && is_array($_POST["box_qlinks"])) { 
	$i_qlinks = $_POST["box_qlinks"];
rsort($i_qlinks, SORT_NUMERIC);
foreach($i_qlinks as $f_test_questionid) { 
 deleteQuestionLink($f_testid, (int)$f_test_questionid);
}
} else {
	$f_test_questionid = (int)readGetVar('test_questionid'); 
	deleteQuestionLink($f_testid, $f_test_questionid);
}
 
gotoLocation('test-manager.php'.getURLAddon('?action=editt', array('action')));
?>