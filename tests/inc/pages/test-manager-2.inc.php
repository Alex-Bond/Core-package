<?php

initTextEditor($G_SESSION['config_editortype'], array('test_instructions'));
require_once ($DOCUMENT_INC . "top.inc.php");
$f_testid = (int) readGetVar('testid');
$G_SESSION['ckfinder'] = array(
    'type' => 'test',
    'id' => $f_testid 
);
$i_items = array();
array_push($i_items, array(0 => '<a class=bar2 href="test-manager.php">' . $lngstr['page_header_edittests'] . '</a>', 0));
array_push($i_items, array(0 => '<a class=bar2 href="grades.php">' . $lngstr['page_header_grades'] . '</a>', 0));
writePanel2($i_items);
echo '<h2>' . $lngstr['page_header_test_settings'] . '</h2>';
writeErrorsWarningsBar();
$i_rSet1 = $g_db->Execute("SELECT * FROM " . $srv_settings['table_prefix'] . "tests WHERE testid=$f_testid");
if (!$i_rSet1) {
    showDBError(__file__, 1);
} else {
    if (!$i_rSet1->EOF) {
        $i_subjects = array();
        $i_rSet2 = $g_db->Execute("SELECT * FROM " . $srv_settings['table_prefix'] . "subjects");
        if (!$i_rSet2) {
            showDBError(__file__, 2);
        } else {
            while (!$i_rSet2->EOF) {
                $i_subjects[$i_rSet2->fields["subjectid"]] = $i_rSet2->fields["subject_name"];
                $i_rSet2->MoveNext();
            }
            $i_rSet2->Close();
        }

        echo '<p><form method=post action="test-manager.php?testid=' . $f_testid . '&action=settings" onsubmit="return submitForm();">';
        echo '<table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">';
        echo '<tr class=rowtwo valign=top><td colspan=2>' . getCheckbox('test_enabled', $i_rSet1->fields["test_enabled"], $lngstr['page_edittests_testenabled']) . '</td></tr>';

        $i_rowno = 1;
        /* $url = 'http://11.1.1.245/api/?do=courses_id&id=' . $G_SESSION['usercom'];
          $xml = simplexml_load_file($url);
         */
        echo '<tr class=rowone valign=top><td>Предмет тесту:</td><td>';
        /*
          echo '<select class=inp name="subjectid" id="subjectid">';
          foreach ($xml->course as $course) {
          if ($course->id == $i_rSet1->fields["subjectid"])
          $selected = ' selected=selected';
          else
          $selected = '';
          echo '<option value="' . $course->id . '" ' . $selected . '>' . $course->name . '</option>';
          }

          echo '< /select >';
         */
        echo getSelectSubj('subjectid', $i_rSet1->fields["subjectid"], array('class' => 'inp'), FALSE);

        echo ' </td> </tr> ';


        writeTR2($lngstr['page_edittests_testname'], getInputElement('test_name', $i_rSet1->fields["test_name"]));
        writeTR2($lngstr['page_edittests_testdescription'], getInputElement('test_description', $i_rSet1->fields["test_description"]));
        writeTR2($lngstr['page_edittests_testinstructions'], getTextEditor($G_SESSION['config_editortype'], 'test_instructions', $i_rSet1->fields["test_instructions"]));
        writeTR2($lngstr['page_edittests_testinternet'], getCheckbox('test_internet', $i_rSet1->fields["test_internet"], 'Доступний'));
        writeTR2($lngstr['page_edittests_teststart'], getCalendar("test_datestart", date('Y', $i_rSet1->fields["test_datestart"]), date('m', $i_rSet1->fields["test_datestart"]), date('d', $i_rSet1->fields["test_datestart"]), date('H', $i_rSet1->fields["test_datestart"]), date('i', $i_rSet1->fields["test_datestart"])));
        writeTR2($lngstr['page_edittests_testend'], getCalendar("test_dateend", date('Y', $i_rSet1->fields["test_dateend"]), date('m', $i_rSet1->fields["test_dateend"]), date('d', $i_rSet1->fields["test_dateend"]), date('H', $i_rSet1->fields["test_dateend"]), date('i', $i_rSet1->fields["test_dateend"])));
        writeTR2($lngstr['page_edittests_testtime'], getTimeElement('test_time', $i_rSet1->fields["test_time"]) . '<br>' . getCheckbox('test_timeforceout', $i_rSet1->fields["test_timeforceout"], $lngstr['page_edittests_testtimeforceout']));
        writeTR2($lngstr['page-testmanager']['attempts_allowed'], getSelectElement('test_attempts', $i_rSet1->fields["test_attempts"], $lngstr['page-testmanager']['attempts_allowed_list']));


        $i_gradingsystems = array();
        $i_rSet5 = $g_db->Execute("SELECT gscaleid, gscale_name FROM " . $srv_settings['table_prefix'] . "gscales ORDER BY gscaleid");

        if (!$i_rSet5) {
            showDBError(__file__, 5);
        } else {
            while (!$i_rSet5->EOF) {
                $i_gradingsystems[$i_rSet5->fields["gscaleid"]] = $i_rSet5->fields["gscale_name"];
                $i_rSet5->MoveNext();
            }
            $i_rSet5->Close();
        }

        writeTR2($lngstr['page_edittests_gradingsystem'], getSelectElement('gscaleid', $i_rSet1->fields["gscaleid"], $i_gradingsystems));
        writeTR2($lngstr['page_edittests_showquestions'], getSelectElement('test_qsperpage', $i_rSet1->fields["test_qsperpage"], array(1 => $lngstr['page_edittests_onebyone'], 0 => $lngstr['page_edittests_allquestions'])));
        writeTR2($lngstr['page_edittests_shuffle'], getCheckbox('test_shuffleq', $i_rSet1->fields["test_shuffleq"], $lngstr['page_edittests_shuffleq']) . '<br>' . getCheckbox('test_shufflea', $i_rSet1->fields["test_shufflea"], $lngstr['page_edittests_shufflea']));
        writeTR2($lngstr['page_edittests_resultsettings'], getCheckbox('test_showqfeedback', $i_rSet1->fields["test_showqfeedback"], $lngstr['page_edittests_result_showqfeedback']) . '<br>' . getCheckbox('test_result_showgrade', $i_rSet1->fields["test_result_showgrade"], $lngstr['page_edittests_result_showgrade']) . '<br>' . getCheckbox('test_result_showanswers', $i_rSet1->fields["test_result_showanswers"], $lngstr['page_edittests_result_showanswers']) . '<br>' . getCheckbox('test_result_showpoints', $i_rSet1->fields["test_result_showpoints"], $lngstr['page_edittests_result_showpoints']));
        $i_rtemplates_text = array(0 => $lngstr['page-testsettings']['no_report']);
        $i_rSet6 = $g_db->Execute("SELECT rtemplateid, rtemplate_name FROM " . $srv_settings['table_prefix'] . "rtemplates ORDER BY rtemplateid");

        if (!$i_rSet6) {
            showDBError(__file__, 6);
        } else {
            while (!$i_rSet6->EOF) {
                $i_rtemplates_text[$i_rSet6->fields["rtemplateid"]] = $i_rSet6->fields["rtemplate_name"];
                $i_rSet6->MoveNext();
            }
            $i_rSet6->Close();
        }
        $i_gradeconditions = array(0 => $lngstr['page-testsettings']['no_condition']);
        $i_rSet7 = $g_db->Execute("SELECT gscale_gradeid, grade_name FROM " . $srv_settings['table_prefix'] . "gscales_grades WHERE gscaleid=" . $i_rSet1->fields["gscaleid"]);

        if (!$i_rSet7) {
            showDBError(__file__, 7);
        } else {
            while (!$i_rSet7->EOF) {
                $i_gradeconditions[$i_rSet7->fields["gscale_gradeid"]] = $i_rSet7->fields["grade_name"];
                $i_rSet7->MoveNext();
            } $i_rSet7->Close();
        }

        writeTR2($lngstr['page_edittests_advancedreport'], $lngstr['page-testsettings']['report_template'] . ' ' . getSelectElement('rtemplateid', $i_rSet1->fields["rtemplateid"], $i_rtemplates_text) . '<br>' . $lngstr['page-testsettings']['report_grade_condition'] . ' ' . getSelectElement('test_reportgradecondition', $i_rSet1->fields["test_reportgradecondition"], $i_gradeconditions) . '<br>' . getCheckbox('test_result_showpdf', $i_rSet1->fields["test_result_showpdf"], $lngstr['page_edittests_advancedreport_showpdf']));
        $i_etemplates_text = array(0 => $lngstr['label_do_not_send_email']);
        $i_rSet4 = $g_db->Execute("SELECT etemplateid, etemplate_name FROM " . $srv_settings['table_prefix'] . "etemplates ORDER BY etemplateid");

        if (!$i_rSet4) {
            showDBError(__file__, 4);
        } else {
            while (!$i_rSet4->EOF) {
                $i_etemplates_text[$i_rSet4->fields["etemplateid"]] = $i_rSet4->fields["etemplate_name"];
                $i_rSet4->MoveNext();
            } $i_rSet4->Close();
        }

        writeTR2($lngstr['page_edittests_sendresultsbyemail'], $lngstr['page_edittests_sendresultsbyemail_template'] . ' ' . getSelectElement('result_etemplateid', $i_rSet1->fields["result_etemplateid"], $i_etemplates_text) . '<br>' . $lngstr['page_edittests_sendresultsbyemail_to'] . ' ' . getInputElement('test_result_email', $i_rSet1->fields["test_result_email"]) . ', ' . getCheckbox('test_result_emailtouser', $i_rSet1->fields["test_result_emailtouser"], $lngstr['page_edittests_result_emailtouser']));
        $i_groups_text = "";
        $url = 'http://11.1.1.245/api/?do=groups';
        $xml = simplexml_load_file($url);
        //$i_rSet2 = $g_db->Execute("SELECT * FROM " . $srv_settings['table_prefix'] . "users");        
        foreach ($xml->group as $group) {
            $i_rSet3 = $g_db->Execute("SELECT COUNT(*) as count FROM kkztgroups_tests WHERE kkztgroups_tests.groupid=" . $group->id . " AND kkztgroups_tests.testid=" . $f_testid);
            $i_groups_text .= '<br>';
            if ($i_rSet3->fields["count"] > 0) {
                $cheked = " checked=checked";
            } else {
                $cheked = "";
            } $i_groups_text .= '<input name="group[' . $group->id . ']" type=checkbox ' . $cheked . '>' . utf8_to_cp1251($group->name);
        } $i_rSet3->Close();
        writeTR2($lngstr['page_edittests_assignedto'], '<p>' . $i_groups_text . '<p>' . getCheckbox('test_forall', $i_rSet1->fields["test_forall"], $lngstr['page_edittests_assignto_everybody']));
        writeTR2($lngstr['page_edittests_testnotes'], getTextArea('test_notes', $i_rSet1->fields["test_notes"]));
        echo '</table>';
        echo '<p class=center><input class=btn type=submit name=bsubmit value=" ' . $lngstr['button_update'] . ' "> 
            <input class=btn type=submit name=bsubmit2 value=" ' . $lngstr['button_update_and_edit_questions'] . ' " /> 
                <input class=btn type=submit name="to_upload" value=" ' . $lngstr['button_update_and_upload_questions'] . ' " /> 
                    <input class=btn type=submit name=bcancel value=" ' . $lngstr['button_cancel'] . ' " /></form>';
    }
    $i_rSet1->Close();
}

require_once ($DOCUMENT_INC . "btm.inc.php");
?>
