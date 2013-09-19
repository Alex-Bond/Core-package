<?php

$f_testid = (int) readGetVar('testid');

$f_test_enabled = (int) (boolean) readPostVar('test_enabled');
$f_subjectid = (int) readPostVar('subjectid');
$f_gscaleid = (int) readPostVar('gscaleid');
$f_test_name = readPostVar('test_name');
$f_test_name = $g_db->qstr($f_test_name, get_magic_quotes_gpc());
$f_test_internet = (int) (boolean) readPostVar('test_internet');
$f_test_datestart_year = (int) readPostVar('test_datestart_year');
$f_test_datestart_month = (int) readPostVar('test_datestart_month');
$f_test_datestart_day = (int) readPostVar('test_datestart_day');
$f_test_datestart_hour = (int) readPostVar('test_datestart_hour');
$f_test_datestart_minute = (int) readPostVar('test_datestart_minute');
$f_test_datestart = mktime($f_test_datestart_hour, $f_test_datestart_minute, 0, $f_test_datestart_month, $f_test_datestart_day, $f_test_datestart_year);
$f_test_dateend_year = (int) readPostVar('test_dateend_year');
$f_test_dateend_month = (int) readPostVar('test_dateend_month');
$f_test_dateend_day = (int) readPostVar('test_dateend_day');
$f_test_dateend_hour = (int) readPostVar('test_dateend_hour');
$f_test_dateend_minute = (int) readPostVar('test_dateend_minute');
$f_test_dateend = mktime($f_test_dateend_hour, $f_test_dateend_minute, 0, $f_test_dateend_month, $f_test_dateend_day, $f_test_dateend_year);
$f_test_time_donotuse = (boolean) readPostVar('test_time_donotuse');
if ($f_test_time_donotuse) {
    $f_test_time = 0;
} else {
    $f_test_time_hours = (int) readPostVar('test_time_hours');
    $f_test_time_minutes = (int) readPostVar('test_time_minutes');
    $f_test_time_seconds = (int) readPostVar('test_time_seconds');
    $f_test_time = $f_test_time_seconds + $f_test_time_minutes * 60 + $f_test_time_hours *
            3600;
    if ($f_test_time < 0)
        $f_test_time = 0;
}
$f_test_timeforceout = (int) (boolean) readPostVar('test_timeforceout');
$f_test_attempts = (int) readPostVar('test_attempts');
$f_test_qsperpage = (int) (boolean) readPostVar('test_qsperpage');
$f_test_shuffleq = (int) (boolean) readPostVar('test_shuffleq');
$f_test_shufflea = (int) (boolean) readPostVar('test_shufflea');
$f_test_showqfeedback = (int) (boolean) readPostVar('test_showqfeedback');
$f_test_result_showgrade = (int) (boolean) readPostVar('test_result_showgrade');
$f_test_result_showanswers = (int) (boolean) readPostVar('test_result_showanswers');
$f_test_result_showpoints = (int) (boolean) readPostVar('test_result_showpoints');
$f_rtemplateid = (int) readPostVar('rtemplateid');
$f_test_reportgradecondition = (int) readPostVar('test_reportgradecondition');
$f_test_result_showpdf = (int) (boolean) readPostVar('test_result_showpdf');
$f_test_result_email = readPostVar('test_result_email');
$f_test_result_email = $g_db->qstr($f_test_result_email, get_magic_quotes_gpc());
$f_result_etemplateid = (int) readPostVar('result_etemplateid');
$f_test_result_emailtouser = (int) (boolean) readPostVar('test_result_emailtouser');
$f_test_description = readPostVar('test_description');
$f_test_description = $g_db->qstr($f_test_description, get_magic_quotes_gpc());
$f_test_instructions = readPostVar('test_instructions');

if (trim(str_replace("<p>", "", str_replace("</p>", "", str_replace("&nbsp;", "", str_replace("<div>", "", str_replace("</div>", "", str_replace("<span>", "", str_replace("</span>", "", str_replace("<br>", "", str_replace("<br />", "", $f_test_instructions)))))))))) ==
        "")
    $f_test_instructions = "";
$f_test_instructions = $g_db->qstr($f_test_instructions, get_magic_quotes_gpc());
$f_test_notes = readPostVar('test_notes');
$f_test_notes = $g_db->qstr($f_test_notes, get_magic_quotes_gpc());
$f_test_forall = (int) (boolean) readPostVar('test_forall', 0);
$f_group = isset($_POST['group']) ? $_POST['group'] : array();

/*
  if ($i_rSet2 = $g_db->Execute("SELECT subjectid FROM " . $srv_settings['table_prefix'] .
  "subjects WHERE subjectid=$f_subjectid"))
  $sql_subject_exists = $i_rSet2->RecordCount() > 0;
  else
  $sql_subject_exists = false;
  if (!$sql_subject_exists)
  $input_err_msg .= $lngstr['err_subject_doesnotexist'];
 */
if ($input_err_msg) {
    include_once ($DOCUMENT_PAGES . "test-manager-2.inc.php");
} else {

    if ($g_db->Execute("UPDATE " . $srv_settings['table_prefix'] .
                    "tests SET subjectid=$f_subjectid, gscaleid=$f_gscaleid, rtemplateid=$f_rtemplateid, test_reportgradecondition=$f_test_reportgradecondition, result_etemplateid=$f_result_etemplateid, test_name=$f_test_name, test_description=$f_test_description, test_time=$f_test_time, test_timeforceout=$f_test_timeforceout, test_attempts=$f_test_attempts, test_shuffleq=$f_test_shuffleq, test_shufflea=$f_test_shufflea, test_qsperpage=$f_test_qsperpage, test_showqfeedback=$f_test_showqfeedback, test_result_showgrade=$f_test_result_showgrade, test_result_showanswers=$f_test_result_showanswers, test_result_showpoints=$f_test_result_showpoints, test_result_showpdf=$f_test_result_showpdf, test_result_email=$f_test_result_email, test_result_emailtouser=$f_test_result_emailtouser, test_datestart=$f_test_datestart, test_dateend=$f_test_dateend, test_instructions=$f_test_instructions, test_notes=$f_test_notes, test_forall=$f_test_forall, test_enabled=$f_test_enabled, test_internet=$f_test_internet WHERE testid=$f_testid")
            === false)
        showDBError(__file__, 1);

    if ($g_db->Execute("DELETE FROM " . $srv_settings['table_prefix'] .
                    "groups_tests WHERE testid=" . $f_testid) === false)
        showDBError(__file__, 2);
    foreach ($f_group as $i_groupid => $i_ischecked) {
        if ($i_ischecked)
            $g_db->Execute("INSERT INTO " . $srv_settings['table_prefix'] .
                    "groups_tests (groupid, testid) VALUES (" . $i_groupid . ", " . $f_testid . ")");
    }
    if (isset($_POST['bsubmit2']))
        gotoLocation('test-manager.php' . getURLAddon('?action=editt', array('action')));
    elseif (isset($_POST['to_upload'])) {
        gotoLocation('test-manager.php?action=upload&testid=' . $_GET['testid']);
        die();
    } else
        gotoLocation('test-manager.php' . getURLAddon('', array('action', 'testid')));
}
?>
