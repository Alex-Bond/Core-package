<?php
require_once($DOCUMENT_INC."top.inc.php");
$f_testid = (int)readGetVar('testid');
$i_items = array();
array_push($i_items, array(0 => '<a class=bar2 href="test-manager.php">'.$lngstr['page_header_edittests'].'</a>', 0));
array_push($i_items, array(0 => '<a class=bar2 href="grades.php">'.$lngstr['page_header_grades'].'</a>', 0));
array_push($i_items, array(0 => '<a class=bar2 href="test-manager.php?action=editt&testid='.$f_testid.'">'.$lngstr['page_header_test_questions'].'</a>', 0));
writePanel2($i_items);
echo '<h2>'.$lngstr['page_header_import_questions'].'</h2>';
writeErrorsWarningsBar();
echo '<p><form method=post action="test-manager.php?testid='.$f_testid.'&action=import">';
echo '<table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">';
$i_rowno = 0;
echo '<tr class=rowtwo><td>'.$lngstr['page_importtest_ut_import_document'].'</td></tr>';
echo '<tr class=rowone><td>'.getTextArea('import_document', '', $lngstr['page_importtest_ut_import_document_hint'], 10, 70).'<br>'.$lngstr['page_importtest_ut_import_document_howto'].'</td></tr>';
echo '</table>'; 
echo '<p class=center><input class=btn type=submit name=bsubmit value=" '.$lngstr['button_import'].' "> <input class=btn type=submit name=bcancel value=" '.$lngstr['button_cancel'].' "></form>';
require_once($DOCUMENT_INC."btm.inc.php");
?>