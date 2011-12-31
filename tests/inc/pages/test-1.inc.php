<?php

$g_nocontrolpanel = true;
require_once($DOCUMENT_INC . "top-2.inc.php");
if (!isset($G_SESSION['yt_teststop'])) {
    if ($G_SESSION['yt_testtime'] > 0) {

        $G_SESSION['yt_teststop'] = time() + $G_SESSION['yt_testtime'];
    } else {

        $G_SESSION['yt_teststop'] = 0;
    }
}
if ($G_SESSION['yt_teststop'] > 0) {
    $i_testtime = readDiffTime(time(), $G_SESSION['yt_teststop']);
    writeVTimer($i_testtime['hours'], $i_testtime['minutes'], $i_testtime['seconds']);
}
echo '<p><form method=post action="test.php"><table cellpadding=0 cellspacing=5 border=0 width="100%">';
echo '<tr><td>';
echo '<table class=rowtable3 cellpadding=5 cellspacing=1 border=0 width="100%">';
echo '<tr class=rowtwo valign=top><td id=vtimer align=center title="' . $lngstr['label_test_timer_hint'] . '" width=150>' . $lngstr['label_no_time_limit'] . '</td><td id=qcounter align=center title="' . sprintf($lngstr['label_questionsindicator_hint'], $G_SESSION['yt_questionno'], $G_SESSION['yt_questioncount']) . '"><nobr>&nbsp;' . sprintf($lngstr['label_questionsindicator'], $G_SESSION['yt_questionno'], $G_SESSION['yt_questioncount']) . '&nbsp;</nobr></td><td width="60%" id=testname align=center title="' . $lngstr['label_test_testname_hint'] . '">' . convertTextValue($G_SESSION['yt_name']) . '</td></tr>';
echo '<tr><td class=rowone colspan=3>';
writeErrorsWarningsBar();
if (isset($input_err_msg) && $input_err_msg)
    echo '</td></tr><tr><td class=rowone colspan=3>';
echo '<table cellpadding=0 cellspacing=0 border=0 width="100%"><tr vAlign=top><td width=1><img src="images/1x1.gif" width=1 height=260></td><td>';

if (!isset($G_SESSION['questionid'])) {

    $i_questionno_real = $G_SESSION['yt_questions'][$G_SESSION['yt_questionno'] - 1];
    $G_SESSION['questionid'] = $G_SESSION['yt_questionids'][$i_questionno_real];
    $i_rSet1 = $g_db->Execute("SELECT questionid FROM " . $srv_settings['table_prefix'] . "tests_questions WHERE test_questionid=" . $i_questionno_real . " AND testid=" . $G_SESSION['testid']);

    $G_SESSION['yt_questionstart'] = time();
}

$i_internalerror = false;
if (!showTestQuestion($G_SESSION['yt_questionno'], $G_SESSION['questionid']))
    $i_internalerror = true;
echo '</td></tr></table></td></tr>';
if ($G_SESSION["yt_state"] == TEST_STATE_QFEEDBACK) {
    echo '<tr><td class=rowtwo colspan=3><input class=btn type=submit name=bsubmit value=" ' . $lngstr['button_continue'] . ' "></td></tr></table>';
} else if ($i_internalerror) {
    unregisterTestData();
    echo '<tr><td class=rowtwo colspan=3><input class=btn type=submit name=bcancel value=" ' . $lngstr['button_cancel'] . ' "></td></tr></table>';
} else {
    echo '<tr><td class=rowtwo colspan=3><input class=btn type=submit name=bsubmit value=" ' . $lngstr['button_answer'] . ' "></td></tr></table>';
}
echo '</td></tr></table></form>';
require_once($DOCUMENT_INC . "btm-2.inc.php");
?>
