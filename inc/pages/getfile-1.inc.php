<?php

define('FPDF_FONTPATH', $DOCUMENT_FPDF . 'font/');
include_once($DOCUMENT_FPDF . 'html2fpdf.php');
$f_resultid = (int) readGetVar('resultid');
//error_reporting(0);

$i_can_access = false;
if ($G_SESSION['access_reportsmanager'] > 1) {
    $i_can_access = true;
} else {

    $i_rSet1 = $g_db->Execute("SELECT resultid FROM " . $srv_settings['table_prefix'] . "results WHERE userid=" . $G_SESSION['userid'] . " AND resultid=" . $f_resultid);
    if (!$i_rSet1) {
        showDBError(__FILE__, 1);
    } else {
        $i_can_access = $i_rSet1->RecordCount() > 0;
    }
}

$i_isok = $i_can_access;

if ($i_isok)
    $i_isok = ($i_rSet2 = $g_db->SelectLimit("SELECT * FROM " . $srv_settings['table_prefix'] . "results WHERE resultid=" . $f_resultid, 1));
if ($i_isok)
    $i_isok = (!$i_rSet2->EOF);

if ($i_isok)
    $i_isok = ($i_rSet3 = $g_db->SelectLimit("SELECT rtemplateid, test_name, test_result_showpoints, test_result_showgrade, test_result_showpdf FROM " . $srv_settings['table_prefix'] . "tests WHERE testid=" . $i_rSet2->fields["testid"], 1));
if ($i_isok)
    $i_isok = (!$i_rSet3->EOF);
if ($i_isok)
    $i_isok = ($i_rSet3->fields["test_result_showpdf"] > 0);

if ($i_isok)
    $i_isok = ($i_rSet4 = $g_db->SelectLimit("SELECT rtemplate_body FROM " . $srv_settings['table_prefix'] . "rtemplates WHERE rtemplateid=" . $i_rSet3->fields["rtemplateid"], 1));
if ($i_isok)
    $i_isok = (!$i_rSet4->EOF);

if ($i_isok)
    $i_isok = ($i_rSet5 = $g_db->SelectLimit("SELECT grade_name FROM " . $srv_settings['table_prefix'] . "gscales_grades WHERE gscaleid=" . $i_rSet2->fields["gscaleid"] . " AND gscale_gradeid=" . $i_rSet2->fields["gscale_gradeid"], 1));
if ($i_isok)
    $i_isok = (!$i_rSet5->EOF);

if ($i_isok)
    $i_isok = ($i_rSet6 = $g_db->SelectLimit("SELECT * FROM " . $srv_settings['table_prefix'] . "users WHERE userid=" . $G_SESSION['userid'], 1));
if ($i_isok)
    $i_isok = (!$i_rSet6->EOF);

$i_result_detailed_1_text = "";
$i_result_detailed_2_text = "";
if ($i_isok)
    $i_isok = ($i_rSet7 = $g_db->Execute("SELECT * FROM " . $srv_settings['table_prefix'] . "results_answers, " . $srv_settings['table_prefix'] . "questions WHERE resultid=" . $f_resultid . " AND " . $srv_settings['table_prefix'] . "results_answers.questionid=" . $srv_settings['table_prefix'] . "questions.questionid ORDER BY result_answerid"));
if ($i_isok) {
    $i_questionno = 0;
    while (!$i_rSet7->EOF) {
        $i_questionno++;

        if ($i_questionno > 1)
            $i_result_detailed_1_text .= "\n";
        $i_result_detailed_1_text .= $i_questionno . ". " . trim(getTruncatedHTML($i_rSet7->fields["question_text"]), SYSTEM_TRUNCATED_LENGTH_LONG) . "\n";
        $i_result_detailed_1_text .= $lngstr['email_answer_iscorrect'] . ($i_rSet7->fields["result_answer_iscorrect"] == 3 ? $lngstr['label_undefined'] : ($i_rSet7->fields["result_answer_iscorrect"] == 2 ? $lngstr['label_yes'] : ($i_rSet7->fields["result_answer_iscorrect"] == 1 ? $lngstr['label_partially'] : $lngstr['label_no']))) . "\n";
        $i_result_detailed_1_text .= $lngstr['email_answer_points'] . $i_rSet7->fields["result_answer_points"] . "\n";

        if ($i_rSet7->fields["result_answer_iscorrect"] == 0 || $i_rSet7->fields["result_answer_iscorrect"] == 1 || $i_rSet7->fields["result_answer_iscorrect"] == 3) {
            if ($i_result_detailed_2_text)
                $i_result_detailed_2_text .= "\n";
            $i_result_detailed_2_text .= $i_questionno . ". " . trim(getTruncatedHTML($i_rSet7->fields["question_text"]), SYSTEM_TRUNCATED_LENGTH_LONG) . "\n";
            $i_result_detailed_2_text .= $lngstr['email_answer_iscorrect'] . ($i_rSet7->fields["result_answer_iscorrect"] == 3 ? $lngstr['label_undefined'] : ($i_rSet7->fields["result_answer_iscorrect"] == 2 ? $lngstr['label_yes'] : ($i_rSet7->fields["result_answer_iscorrect"] == 1 ? $lngstr['label_partially'] : $lngstr['label_no']))) . "\n";
            $i_result_detailed_2_text .= $lngstr['email_answer_points'] . $i_rSet7->fields["result_answer_points"] . "\n";
        }
        $i_rSet7->MoveNext();
    }
    $i_rSet7->Close();
}
if ($i_isok) {

    $pdf = new HTML2FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 11);
    $pdf->SetTextColor(0, 0, 0);
    $i_timespent_total = $i_rSet2->fields["result_timespent"];
    $i_email_body = $i_rSet4->fields["rtemplate_body"];
    $i_email_body = str_replace(ETEMPLATE_TAG_USERNAME, $i_rSet6->fields["user_name"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_TITLE, $i_rSet6->fields["user_title"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_FIRST_NAME, $i_rSet6->fields["user_firstname"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_LAST_NAME, $i_rSet6->fields["user_lastname"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_MIDDLE_NAME, $i_rSet6->fields["user_middlename"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_EMAIL, $i_rSet6->fields["user_email"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_ADDRESS, $i_rSet6->fields["user_address"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_CITY, $i_rSet6->fields["user_city"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_STATE, $i_rSet6->fields["user_state"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_ZIP, $i_rSet6->fields["user_zip"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_COUNTRY, $i_rSet6->fields["user_country"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_PHONE, $i_rSet6->fields["user_phone"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_FAX, $i_rSet6->fields["user_fax"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_MOBILE, $i_rSet6->fields["user_mobile"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_PAGER, $i_rSet6->fields["user_pager"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_IPPHONE, $i_rSet6->fields["user_ipphone"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_WEBPAGE, $i_rSet6->fields["user_webpage"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_ICQ, $i_rSet6->fields["user_icq"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_MSN, $i_rSet6->fields["user_msn"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_AOL, $i_rSet6->fields["user_aol"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_GENDER, $i_rSet6->fields["user_gender"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_BIRTHDAY, $i_rSet6->fields["user_birthday"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_HUSBANDWIFE, $i_rSet6->fields["user_husbandwife"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_CHILDREN, $i_rSet6->fields["user_children"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_TRAINER, $i_rSet6->fields["user_trainer"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_PHOTO, $i_rSet6->fields["user_photo"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_COMPANY, $i_rSet6->fields["user_company"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_CPOSITION, $i_rSet6->fields["user_cposition"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_DEPARTMENT, $i_rSet6->fields["user_department"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_COFFICE, $i_rSet6->fields["user_coffice"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_CADDRESS, $i_rSet6->fields["user_caddress"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_CCITY, $i_rSet6->fields["user_ccity"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_CSTATE, $i_rSet6->fields["user_cstate"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_CZIP, $i_rSet6->fields["user_czip"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_CCOUNTRY, $i_rSet6->fields["user_ccountry"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_CPHONE, $i_rSet6->fields["user_cphone"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_CFAX, $i_rSet6->fields["user_cfax"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_CMOBILE, $i_rSet6->fields["user_cmobile"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_CPAGER, $i_rSet6->fields["user_cpager"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_CIPPHONE, $i_rSet6->fields["user_cipphone"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_CWEBPAGE, $i_rSet6->fields["user_cwebpage"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_CPHOTO, $i_rSet6->fields["user_cphoto"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD1, $i_rSet6->fields["user_ufield1"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD2, $i_rSet6->fields["user_ufield2"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD3, $i_rSet6->fields["user_ufield3"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD4, $i_rSet6->fields["user_ufield4"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD5, $i_rSet6->fields["user_ufield5"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD6, $i_rSet6->fields["user_ufield6"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD7, $i_rSet6->fields["user_ufield7"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD8, $i_rSet6->fields["user_ufield8"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD9, $i_rSet6->fields["user_ufield9"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD10, $i_rSet6->fields["user_ufield10"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_TEST_NAME, $i_rSet3->fields["test_name"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_DATE, date($lngstr['date_format_full'], $i_rSet2->fields["result_datestart"]), $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_TIME_SPENT, getTimeFormatted($i_timespent_total), $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_TIME_EXCEEDED, ($i_rSet2->fields["result_timeexceeded"] > 0) ? $lngstr['label_yes'] : $lngstr['label_no'], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_POINTS_SCORED, $i_rSet2->fields["result_points"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_POINTS_POSSIBLE, $i_rSet2->fields["result_pointsmax"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_PERCENTS, (($i_rSet2->fields["result_pointsmax"] > 0) ? round(($i_rSet2->fields["result_points"] / $i_rSet2->fields["result_pointsmax"]) * 100) : 0), $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_GRADE, $i_rSet5->fields["grade_name"], $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_DETAILED_1, nl2br($i_result_detailed_1_text), $i_email_body);
    $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_DETAILED_2, nl2br($i_result_detailed_2_text), $i_email_body);
    $pdf->WriteHTML($i_email_body);
    $pdf->Output('test-results-' . $f_resultid . '.pdf', 'I');
}
error_reporting(7);
?>