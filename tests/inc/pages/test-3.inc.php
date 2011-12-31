<?php

$f_testid = (int) readGetVar('testid');

unregisterTestData();

if ($f_testid) {
    $i_now = time();
    $i_rSet1 = $g_db->SelectLimit("SELECT * FROM " . $srv_settings['table_prefix'] . "tests WHERE testid=$f_testid AND test_enabled=1 AND test_datestart<=$i_now AND test_dateend>$i_now", 1);
    if (!$i_rSet1) {
        showDBError(__FILE__, 1);
    } else {
        if (!$i_rSet1->EOF) {

            $yt_questionids = array();
            $i_rSet2 = $g_db->Execute("SELECT " . $srv_settings['table_prefix'] . "tests_questions.questionid, " . $srv_settings['table_prefix'] . "questions.subjectid, " . $srv_settings['table_prefix'] . "questions.question_points, " . $srv_settings['table_prefix'] . "questions.question_type FROM " . $srv_settings['table_prefix'] . "tests_questions, " . $srv_settings['table_prefix'] . "questions WHERE testid=$f_testid AND " . $srv_settings['table_prefix'] . "tests_questions.questionid=" . $srv_settings['table_prefix'] . "questions.questionid");
            if (!$i_rSet2) {
                showDBError(__FILE__, 2);
            } else {
                $i_questioncounter = 0;
                $yt_pointsmax = 0;
                while (!$i_rSet2->EOF) {
                    if ($i_rSet2->fields['question_type'] != QUESTION_TYPE_RANDOM) {
                        $yt_questionids[$i_questioncounter + 1] = $i_rSet2->fields['questionid'];
                        $yt_pointsmax += $i_rSet2->fields['question_points'];
                    } else {

                        $i_questioncount_by_subjectid = getRecordCount($srv_settings['table_prefix'] . 'questions', "subjectid=" . $i_rSet2->fields['subjectid'] . " AND question_type NOT IN (" . QUESTION_TYPE_RANDOM . ")" . ($yt_questionids ? " AND questionid NOT IN (" . implode(',', $yt_questionids) . ")" : ""));
                        if ($i_questioncount_by_subjectid > 0) {

                            srand((double) microtime() * 10000000);
                            $i_rSet3 = $g_db->SelectLimit("SELECT questionid, question_points FROM " . $srv_settings['table_prefix'] . "questions WHERE subjectid=" . $i_rSet2->fields['subjectid'] . " AND question_type NOT IN (" . QUESTION_TYPE_RANDOM . ")" . ($yt_questionids ? " AND questionid NOT IN (" . implode(',', $yt_questionids) . ")" : ""), 1, rand(0, $i_questioncount_by_subjectid - 1));
                            if (!$i_rSet3) {
                                showDBError(__FILE__, 3);
                            } else {
                                if (!$i_rSet3->EOF) {
                                    $yt_questionids[$i_questioncounter + 1] = $i_rSet3->fields['questionid'];
                                    $yt_pointsmax += $i_rSet3->fields['question_points'];
                                }
                                $i_rSet3->Close();
                            }
                        } else {
                            showError(__FILE__, $lngstr['err_no_questions_left_in_bank']);
                        }
                    }
                    $i_questioncounter++;
                    $i_rSet2->MoveNext();
                }
                $i_rSet2->Close();
            }
            $yt_questioncount = $i_questioncounter;
            if ($yt_questioncount > 0) {

                $yt_test_qsperpage = $i_rSet1->fields["test_qsperpage"];
                $yt_test_showqfeedback = (boolean) $i_rSet1->fields["test_showqfeedback"];
                $yt_result_showanswers = (boolean) $i_rSet1->fields["test_result_showanswers"];
                $yt_result_showpoints = (boolean) $i_rSet1->fields["test_result_showpoints"];
                $yt_result_showgrade = (boolean) $i_rSet1->fields["test_result_showgrade"];
                $yt_gscaleid = (int) $i_rSet1->fields["gscaleid"];


                $grade = $g_db->Execute("SELECT * from kkztgscales_grades WHERE gscaleid=" . $i_rSet1->fields["gscaleid"] . " ORDER BY grade_from");
                if (!$grade) {
                    showDBError(__FILE__, 2);
                } else {
                    while (!$grade->EOF) {
                        $points[] = array(
                            'name' => $grade->fields['grade_name'],
                            'from' => $grade->fields['grade_from'],
                            'to' => $grade->fields['grade_to'],
                        );
                        $grade->MoveNext();
                    }
                }
                $yt_points = $points;

                for ($i = 0; $i < $yt_questioncount; $i++)
                    $yt_questions[$i] = $i + 1;

                if ($i_rSet1->fields["test_shuffleq"])
                    $yt_questions = getShuffledArray($yt_questions);
                $yt_shufflea = (boolean) $i_rSet1->fields["test_shufflea"];

                $i_testtime = (int) $i_rSet1->fields["test_time"];
                $yt_test_timeforceout = (boolean) $i_rSet1->fields["test_timeforceout"];
                if ($i_testtime < 0)
                    $i_testtime = 0;

                if ($g_db->Execute("INSERT INTO " . $srv_settings['table_prefix'] . "results (testid,userid,result_datestart,result_lastact) VALUES (" . $f_testid . "," . $G_SESSION["userid"] . "," . $i_now . "," . $i_now . ")") === false)
                    showDBError(__FILE__, 5);
                $yt_resultid = $g_db->Insert_ID();
                $G_SESSION["testid"] = $f_testid;
                $G_SESSION["resultid"] = $yt_resultid;
                $G_SESSION["yt_name"] = $i_rSet1->fields["test_name"];
                $G_SESSION["yt_result_etemplateid"] = $i_rSet1->fields["result_etemplateid"];
                if ($i_rSet1->fields["result_etemplateid"] > 0) {
                    $G_SESSION["yt_result_email"] = $i_rSet1->fields["test_result_email"];
                    $G_SESSION["yt_result_emailtouser"] = $i_rSet1->fields["test_result_emailtouser"];
                }
                $G_SESSION["yt_teststart"] = $i_now;
                $G_SESSION["yt_testtime"] = $i_testtime;
                $G_SESSION["yt_timeforceout"] = $yt_test_timeforceout;
                $G_SESSION["yt_attempts"] = $i_rSet1->fields["test_attempts"];
                $G_SESSION["yt_pointsmax"] = $yt_pointsmax;
                $G_SESSION["yt_questioncount"] = $yt_questioncount;
                $G_SESSION["yt_questions"] = $yt_questions;
                $G_SESSION["yt_questionids"] = $yt_questionids;
                $G_SESSION["yt_answers"] = array();
                $G_SESSION["yt_shufflea"] = $yt_shufflea;
                $G_SESSION["yt_test_qsperpage"] = $yt_test_qsperpage;
                $G_SESSION["yt_test_showqfeedback"] = $yt_test_showqfeedback;
                $G_SESSION["yt_result_showanswers"] = $yt_result_showanswers;
                $G_SESSION["yt_result_showpoints"] = $yt_result_showpoints;
                $G_SESSION["yt_result_showgrade"] = $yt_result_showgrade;
                $G_SESSION["yt_result_showpdf"] = (boolean) $i_rSet1->fields["test_result_showpdf"] && ($i_rSet1->fields["rtemplateid"] > 0);
                $G_SESSION["yt_reportgradecondition"] = $i_rSet1->fields["test_reportgradecondition"];
                $G_SESSION["yt_gscaleid"] = $yt_gscaleid;
                $G_SESSION["yt_points"] = $yt_points;

                $G_SESSION["yt_questionno"] = 1;
                $G_SESSION["yt_got_answers"] = 0;
                $G_SESSION["yt_got_points"] = 0;
                $G_SESSION["yt_points_pending"] = 0;

                $G_SESSION["yt_state"] = TEST_STATE_TESTINTRO;

                // TO REDIS

                $key = new Rediska_Key('test_'.$G_SESSION["resultid"]);
                $key->expire(60 * 60 * 24 * 10);
                $key->setValue($G_SESSION);

                if (!empty($i_rSet1->fields["test_instructions"]))
                    include_once($DOCUMENT_PAGES . "test-5.inc.php");
                else
                    gotoLocation('test.php');
            } else {

                $input_err_msg = $lngstr['err_no_questions'];
                include_once($DOCUMENT_PAGES . "home.inc.php");
            }
        } else {
            $input_err_msg = $lngstr['err_no_tests'];
            include_once($DOCUMENT_PAGES . "home.inc.php");
        }
        $i_rSet1->Close();
    }
} else {
    $input_err_msg = $lngstr['err_no_test_selected'];
    include_once($DOCUMENT_PAGES . "home.inc.php");
}
?>
