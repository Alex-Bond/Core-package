<?php

$G_SESSION["yt_state"] = TEST_STATE_TRESULTS;
$i_now = time();
$i_timespent_total = $i_now - $G_SESSION['yt_teststart'];
$i_timeexceeded = ($G_SESSION["yt_teststop"] > 0) && ($G_SESSION["yt_teststop"] < $i_now) ? 1 : 0;

$i_gscale_gradeid = 0;
$i_grade_name = '';
if ($G_SESSION["yt_pointsmax"] <> 0)
    $i_percents = ($G_SESSION["yt_got_points"] / $G_SESSION["yt_pointsmax"]) * 100;
else
    $i_percents = 100;
$i_rSet1 = $g_db->SelectLimit("SELECT gscale_gradeid, grade_name FROM " . $srv_settings['table_prefix'] . "gscales_grades WHERE gscaleid=" . $G_SESSION["yt_gscaleid"] . " AND grade_from<=" . $i_percents . " ORDER BY gscale_gradeid ASC", 1);
if (!$i_rSet1) {
    showDBError(__FILE__, 1);
} else {
    if (!$i_rSet1->EOF) {
        $i_gscale_gradeid = $i_rSet1->fields["gscale_gradeid"];
        $i_grade_name = $i_rSet1->fields["grade_name"];
    }
    $i_rSet1->Close();
}

if ($g_db->Execute("UPDATE " . $srv_settings['table_prefix'] . "results SET result_timespent=$i_timespent_total, result_timeexceeded=" . $i_timeexceeded . ", result_points=" . $G_SESSION["yt_got_points"] . ", result_pointsmax=" . $G_SESSION["yt_pointsmax"] . ", gscaleid=" . $G_SESSION["yt_gscaleid"] . ", gscale_gradeid=" . $i_gscale_gradeid . " WHERE resultid=" . $G_SESSION["resultid"]) === false)
    showDBError(__FILE__, 2);

$G_SESSION['yt_test_finished'] = 1;
$key = new Rediska_Key('test_'.$G_SESSION["resultid"]);
$key->setValue($G_SESSION);

if ($G_SESSION["yt_attempts"] > 0) {
    $g_db->Execute("INSERT INTO " . $srv_settings['table_prefix'] . "tests_attempts (testid, userid, test_attempt_count) VALUES (" . $G_SESSION["testid"] . ", " . $G_SESSION["userid"] . ", 0);");
    $g_db->Execute("UPDATE " . $srv_settings['table_prefix'] . "tests_attempts SET test_attempt_count=test_attempt_count+1 WHERE testid=" . $G_SESSION["testid"] . " AND userid=" . $G_SESSION["userid"]);
}

if ($G_SESSION["yt_result_etemplateid"] > 0) {
    $i_email_tos = explode(SYSTEM_ARRAY_ITEM_SEPARATOR, $G_SESSION["yt_result_email"]);
    if (!empty($i_email_tos) && is_array($i_email_tos)) {
        $i_isok = true;

        $i_isok = $i_isok && ($i_rSet3 = $g_db->SelectLimit("SELECT etemplate_from, etemplate_subject, etemplate_body FROM " . $srv_settings['table_prefix'] . "etemplates WHERE etemplateid=" . $G_SESSION["yt_result_etemplateid"], 1));
        if ($i_isok)
            $i_isok = $i_isok && (!$i_rSet3->EOF);

        $i_isok = $i_isok && ($i_rSet4 = $g_db->SelectLimit("SELECT * FROM " . $srv_settings['table_prefix'] . "users WHERE userid=" . $G_SESSION["userid"], 1));
        if ($i_isok)
            $i_isok = $i_isok && (!$i_rSet4->EOF);

        $i_result_detailed_1_text = "";
        $i_result_detailed_2_text = "";
        $i_isok = $i_isok && ($i_rSet5 = $g_db->Execute("SELECT * FROM " . $srv_settings['table_prefix'] . "results_answers, " . $srv_settings['table_prefix'] . "questions WHERE resultid=" . $G_SESSION["resultid"] . " AND " . $srv_settings['table_prefix'] . "results_answers.questionid=" . $srv_settings['table_prefix'] . "questions.questionid ORDER BY result_answerid"));
        if ($i_isok) {
            $i_questionno = 0;
            while (!$i_rSet5->EOF) {
                $i_questionno++;

                if ($i_questionno > 1)
                    $i_result_detailed_1_text .= "\n";
                $i_result_detailed_1_text .= $i_questionno . ". " . trim(getTruncatedHTML($i_rSet5->fields["question_text"]), SYSTEM_TRUNCATED_LENGTH_LONG) . "\n";
                $i_result_detailed_1_text .= $lngstr['email_answer_iscorrect'] . ($i_rSet5->fields["result_answer_iscorrect"] == 3 ? $lngstr['label_undefined'] : ($i_rSet5->fields["result_answer_iscorrect"] == 2 ? $lngstr['label_yes'] : ($i_rSet5->fields["result_answer_iscorrect"] == 1 ? $lngstr['label_partially'] : $lngstr['label_no']))) . "\n";
                $i_result_detailed_1_text .= $lngstr['email_answer_points'] . $i_rSet5->fields["result_answer_points"] . "\n";

                if ($i_rSet5->fields["result_answer_iscorrect"] == 0 || $i_rSet5->fields["result_answer_iscorrect"] == 1 || $i_rSet5->fields["result_answer_iscorrect"] == 3) {
                    if ($i_result_detailed_2_text)
                        $i_result_detailed_2_text .= "\n";
                    $i_result_detailed_2_text .= $i_questionno . ". " . trim(getTruncatedHTML($i_rSet5->fields["question_text"]), SYSTEM_TRUNCATED_LENGTH_LONG) . "\n";
                    $i_result_detailed_2_text .= $lngstr['email_answer_iscorrect'] . ($i_rSet5->fields["result_answer_iscorrect"] == 3 ? $lngstr['label_undefined'] : ($i_rSet5->fields["result_answer_iscorrect"] == 2 ? $lngstr['label_yes'] : ($i_rSet5->fields["result_answer_iscorrect"] == 1 ? $lngstr['label_partially'] : $lngstr['label_no']))) . "\n";
                    $i_result_detailed_2_text .= $lngstr['email_answer_points'] . $i_rSet5->fields["result_answer_points"] . "\n";
                }
                $i_rSet5->MoveNext();
            }
            $i_rSet5->Close();
        }

        if ($i_isok) {
            $i_email_headers = "From: " . $i_rSet3->fields["etemplate_from"] . "\nReply-To: " . $i_rSet3->fields["etemplate_from"] . "\n" . IGT_EMAIL_AGENT_NAME . "\nX-Priority: 3 (Normal)";
            $i_email_subject = $i_rSet3->fields["etemplate_subject"];
            $i_email_body = $i_rSet3->fields["etemplate_body"];
            $i_email_body = str_replace(ETEMPLATE_TAG_USERNAME, $i_rSet4->fields["user_name"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_TITLE, $i_rSet4->fields["user_title"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_FIRST_NAME, $i_rSet4->fields["user_firstname"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_LAST_NAME, $i_rSet4->fields["user_lastname"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_MIDDLE_NAME, $i_rSet4->fields["user_middlename"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_EMAIL, $i_rSet4->fields["user_email"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_ADDRESS, $i_rSet4->fields["user_address"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CITY, $i_rSet4->fields["user_city"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_STATE, $i_rSet4->fields["user_state"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_ZIP, $i_rSet4->fields["user_zip"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_COUNTRY, $i_rSet4->fields["user_country"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_PHONE, $i_rSet4->fields["user_phone"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_FAX, $i_rSet4->fields["user_fax"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_MOBILE, $i_rSet4->fields["user_mobile"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_PAGER, $i_rSet4->fields["user_pager"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_IPPHONE, $i_rSet4->fields["user_ipphone"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_WEBPAGE, $i_rSet4->fields["user_webpage"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_ICQ, $i_rSet4->fields["user_icq"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_MSN, $i_rSet4->fields["user_msn"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_AOL, $i_rSet4->fields["user_aol"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_GENDER, $i_rSet4->fields["user_gender"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_BIRTHDAY, $i_rSet4->fields["user_birthday"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_HUSBANDWIFE, $i_rSet4->fields["user_husbandwife"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CHILDREN, $i_rSet4->fields["user_children"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_TRAINER, $i_rSet4->fields["user_trainer"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_PHOTO, $i_rSet4->fields["user_photo"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_COMPANY, $i_rSet4->fields["user_company"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CPOSITION, $i_rSet4->fields["user_cposition"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_DEPARTMENT, $i_rSet4->fields["user_department"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_COFFICE, $i_rSet4->fields["user_coffice"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CADDRESS, $i_rSet4->fields["user_caddress"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CCITY, $i_rSet4->fields["user_ccity"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CSTATE, $i_rSet4->fields["user_cstate"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CZIP, $i_rSet4->fields["user_czip"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CCOUNTRY, $i_rSet4->fields["user_ccountry"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CPHONE, $i_rSet4->fields["user_cphone"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CFAX, $i_rSet4->fields["user_cfax"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CMOBILE, $i_rSet4->fields["user_cmobile"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CPAGER, $i_rSet4->fields["user_cpager"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CIPPHONE, $i_rSet4->fields["user_cipphone"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CWEBPAGE, $i_rSet4->fields["user_cwebpage"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CPHOTO, $i_rSet4->fields["user_cphoto"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD1, $i_rSet4->fields["user_ufield1"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD2, $i_rSet4->fields["user_ufield2"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD3, $i_rSet4->fields["user_ufield3"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD4, $i_rSet4->fields["user_ufield4"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD5, $i_rSet4->fields["user_ufield5"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD6, $i_rSet4->fields["user_ufield6"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD7, $i_rSet4->fields["user_ufield7"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD8, $i_rSet4->fields["user_ufield8"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD9, $i_rSet4->fields["user_ufield9"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD10, $i_rSet4->fields["user_ufield10"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_TEST_NAME, $G_SESSION["yt_name"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_DATE, date($lngstr['date_format_full'], $G_SESSION["yt_teststart"]), $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_TIME_SPENT, getTimeFormatted($i_timespent_total), $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_TIME_EXCEEDED, $i_timeexceeded ? $lngstr['label_yes'] : $lngstr['label_no'], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_POINTS_SCORED, $G_SESSION["yt_got_points"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_POINTS_POSSIBLE, $G_SESSION["yt_pointsmax"], $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_PERCENTS, ($G_SESSION["yt_pointsmax"] <> 0 ? round(($G_SESSION["yt_got_points"] / $G_SESSION["yt_pointsmax"]) * 100) : 0), $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_GRADE, $i_grade_name, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_DETAILED_1, $i_result_detailed_1_text, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_RESULT_DETAILED_2, $i_result_detailed_2_text, $i_email_body);
            foreach ($i_email_tos as $i_email_to) {
                send_email($i_email_to, $i_email_subject, $i_email_body, $i_email_headers);
            }
            if ($G_SESSION["yt_result_emailtouser"]) {
                send_email($i_rSet4->fields["user_email"], $i_email_subject, $i_email_body, $i_email_headers);
            }
        }
    }
}
$G_SESSION["yt_teststoppedat"] = $i_now;
?>
