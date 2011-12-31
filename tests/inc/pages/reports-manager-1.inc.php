<?php
require_once ($DOCUMENT_INC . "top.inc.php");
$i_items = array();
array_push($i_items, array(0 => $lngstr['page_header_results'], 1));
writePanel2($i_items);
echo '<h2>' . $lngstr['page_header_results'] . '</h2>';
writeErrorsWarningsBar();
writeInfoBar($lngstr['tooltip_reportsmanager']);
$i_pagewide_id = 0;

$i_userid_addon = "";
$i_userid_addon_wosubject = "";
$i_groupid_addon = "";
$i_sql_where_addon = "1=0 AND ";

if ($G_SESSION['access_reportsmanager'] > 1) {
    if (isset($_GET["userid"]) && $_GET["userid"] != "") {
        $i_userid_addon_wosubject = $i_userid_addon .= "&userid=" . (int) $_GET["userid"];
        $i_sql_where_addon = $srv_settings['table_prefix'] . "results.userid=" . (int) $_GET["userid"] .
                " AND ";
    } else {
        $i_sql_where_addon = "";
    }
    if (isset($_GET['subjectid'])) {
        $i_userid_addon .= '&subjectid=' . urlencode($_GET['subjectid']);
    }
    if (isset($_GET['groupid'])) {
        $i_groupid_addon .= '&groupid=' . urlencode($_GET['groupid']);
    }
} else {
    $i_sql_where_addon = $srv_settings['table_prefix'] . "results.userid=" . $G_SESSION['userid'] .
            " AND ";
}

$i_testid_addon = "";
if (isset($_GET["testid"]) && $_GET["testid"] != "") {
    $i_testid_addon .= "&testid=" . (int) $_GET["testid"];
    $i_sql_where_addon .= $srv_settings['table_prefix'] . "results.testid=" . (int)
            $_GET["testid"] . " AND ";
}
$i_subjectid_addon = "";
if (isset($_GET["subjectid"]) && $_GET["subjectid"] != "") {
    $i_testid_addon .= "&subjectid=" . (int) $_GET["subjectid"];
}
/*
  $url = 'http://11.1.1.245/api/?do=courses_id&id=' . $G_SESSION['usercom'];
  $xml = simplexml_load_file($url);
  $i = 1;
  foreach ($xml->course as $course) {
  $courses_apply[$i] = $course->id[0];
  $courses_apply[$i]['name'] = $course->name[0];
  $i++;
  }
 * 
 */
$i_direction = "";
$i_order_addon = "";
$i_sql_order_addon = "";
$i_tablefields = array(
    array(
        $lngstr["label_report_hdr_resultid"],
        $lngstr["label_report_hdr_resultid_hint"],
        $srv_settings['table_prefix'] . "results.resultid"
    ),
    array(
        $lngstr["label_report_hdr_result_datestart"],
        $lngstr["label_report_hdr_result_datestart_hint"],
        $srv_settings['table_prefix'] .
        "results.result_datestart"
    ),
    array($lngstr["label_report_hdr_user_name"],
        $lngstr["label_report_hdr_user_name_hint"]
    ),
    array(
        $lngstr["label_report_hdr_test_name"],
        $lngstr["label_report_hdr_test_name_hint"],
        $srv_settings['table_prefix'] . "tests.test_name"
    ),
    array($lngstr["label_edittests_hdr_subjectid"],
        $lngstr["label_edittests_hdr_subjectid_hint"],
        $srv_settings['table_prefix'] . "tests.subjectid"
    ),
    array(
        $lngstr['page-report']['hdr_test_attempts'],
        $lngstr['page-report']['hdr_test_attempts_hint'],
        ""
    ),
    array(
        $lngstr["label_report_hdr_result_timeexceeded"],
        $lngstr["label_report_hdr_result_timeexceeded_hint"],
        $srv_settings['table_prefix'] . "results.result_timeexceeded"
    ),
    array(
        $lngstr["label_report_hdr_result_points"],
        $lngstr["label_report_hdr_result_points_hint"],
        /* $srv_settings['table_prefix'] . "results.result_points" */ ""
    ),
    /* array($lngstr["label_report_hdr_result_pointsmax"],
      $lngstr["label_report_hdr_result_pointsmax_hint"],
      $srv_settings['table_prefix'] . "results.result_pointsmax"
      ), */
    /* array($lngstr["label_report_hdr_result_score"],
      $lngstr["label_report_hdr_result_score_hint"],
      "result_score"
      ), */
    array($lngstr["label_report_hdr_gscale_gradeid"],
        $lngstr["label_report_hdr_gscale_gradeid_hint"],
        $srv_settings['table_prefix'] .
        "gscales_grades.gscale_gradeid"
    ),
);
$i_order_no = isset($_GET["order"]) ? (int) $_GET["order"] : 0;
if (!isset($_GET["direction"]) && $i_order_no == 0) {
    $_GET["direction"] = 'DESC';
}
if ($i_order_no >= count($i_tablefields))
    $i_order_no = -1;
if ($i_order_no >= 0) {
    $i_direction = (isset($_GET["direction"]) && $_GET["direction"]) ? "DESC" : "";
    $i_order_addon = "&order=" . $i_order_no . "&direction=" . $i_direction;
    $i_sql_order_addon = " ORDER BY " . $i_tablefields[$i_order_no][2] . " " . $i_direction;
}

$i_url_limitto_addon = "";
$i_url_pageno_addon = "";
$i_url_limit_addon = "";
$i_pageno = 0;

$courses_all = $core->getcourses_all();
$groups_all = $core->getgroups();

$sqladd = "";

/*
  foreach ($courses as $course) {
  $courses_all[$course['id']] = array('name' => $course['name']);
  }
 */

echo '<h3>Фiльтри:</h3>';

// FILTERS
?>
<script type="text/javascript">
    function goGroup(val){
        document.location.href='reports-manager.php?groupid='+val+'<?php echo $i_testid_addon . $i_order_addon . $i_url_limit_addon; ?>';
    }
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
    <?php
    echo '<tr><td width="60">Група:</td>
    <td><select name=userid onChange="goGroup(this.value);">';
    echo '<option value=""  onclick="document.location.href=\'reports-manager.php\';">Без фільтра за групою</option>';


    $url = 'http://11.1.1.245/api/?do=groups';
    $xml = simplexml_load_file($url);

    foreach ($xml->group as $group) {

        echo "<option value=" . $group->id;
        if (isset($_GET["groupid"]) && ($_GET["groupid"] == $group->id))
            echo " selected=selected";
        echo " value='" . $group->id . "'>" . $group->name . "</option>\n";
    }
    echo '</select></td></tr>';
    echo '<tr><td>Студент:</td><td>';
    ?>
    <script type="text/javascript">
        function goStudent(val){
            if(val!=''){
                document.location.href='reports-manager.php?userid='+val+'<?php echo $i_groupid_addon . $i_testid_addon . $i_order_addon . $i_url_limit_addon; ?>';
            } else {
                document.location.href='reports-manager.php?<?php echo $i_groupid_addon . $i_testid_addon . $i_order_addon . $i_url_limit_addon; ?>';
            }
        }
    </script>
    <?php
    echo '<select name=userid onChange="goStudent(this.value);">';
    echo '<option value=""  onclick="document.location.href=\'reports-manager.php\';">Без фільтра по студенту</option>';

    if (isset($_GET['groupid']) && strlen($_GET['groupid']) > 0) {
        $url1 = 'http://11.1.1.245/api/?do=users2&id=' . $_GET['groupid'];
        $xml1 = simplexml_load_file($url1);
        foreach ($xml1->user as $user) {
            echo "<option value=" . $user->id;
            if (isset($_GET["userid"]) && ($_GET["userid"] == $user->id))
                echo " selected=selected";
            echo " value='" . $user->id . "'>" . $user->lastname . " " . $user->name . "</option>\n";
            $userToSql[] = $user->id;
        }
    } else {
        $groups_students = (array) $core->getgroupswithstudents();
        foreach ($groups_students as $group) {
            echo "<OPTGROUP label='" . $group->name . "'>";
            foreach ($group->users as $user) {
                echo "<option value=" . $user->id;
                if (isset($_GET["userid"]) && ($_GET["userid"] == $user->id))
                    echo " selected=selected";
                echo " value='" . $user->id . "'>" . $user->lastname . " " . $user->name . "</option>\n";
            }
            echo '</OPTGROUP>';
        }
    }
    echo '</select></td></tr>';
    echo '<tr><td>Предмет:</td><td>';
    echo getSelectSubj('subjectid', ((isset($_GET['subjectid']) ? $_GET['subjectid'] : '')), array('onchange' => 'document.location.href=\'reports-manager.php?subjectid=\'+this.value+\'' . $i_groupid_addon . $i_userid_addon_wosubject . $i_order_addon . $i_url_limit_addon . '\';'));

    $sqladd1 = '';
    if (!isset($_GET ['subjectid']) OR $_GET ['subjectid'] == '') {
        if ($G_SESSION['allcoms'] != 1) {
            $courses = $core->getcourses_id($G_SESSION['usercom']);
            foreach ($courses as $course) {
                $courses_apply[$course['id']] = $course['id'];
            }
            $sqladd .= " AND subjectid IN (" . implode(',', $courses_apply) . ")";
            $sqladd1 .= " AND subjectid IN (" . implode(',', $courses_apply) . ")";
        } else
            $sqladd .= '';
    } else {
        if ($G_SESSION['allcoms'] != 1) {
            $courses = $core->getcourses_id($G_SESSION['usercom']);
            foreach ($courses as $course) {
                $courses_apply[$course['id']] = $course['id'];
            }
            $sqladd .= " AND subjectid = " . $_GET ['subjectid'];
            $sqladd1 .= " AND subjectid IN (" . implode(',', $courses_apply) . ")";
        } else
            $sqladd .= " AND subjectid = " . $_GET ['subjectid'];
    }
    if (count($userToSql) > 0) {
        $sqladd .= ' AND `kkztresults`.`userid` IN (' . implode(',', $userToSql) . ')';
    }
    echo '</td></tr><tr><td>Тест:</td><td>';
    echo '<select name=testid onchange="document.location.href=\'reports-manager.php?testid=\'+this.value+\'' .
    $i_groupid_addon . $i_userid_addon . $i_order_addon . $i_url_limit_addon . '\';">';
    echo '<option value="" onclick="document.location.href=\'reports-manager.php\';">Усi тести</option>';


    $i_rSet2 = $g_db->Execute("SELECT * FROM kkzttests WHERE 1=1 " . $sqladd1 . " order by test_name");
    if (!$i_rSet2) {
        showDBError(__file__, 2);
    } else {
        while (!$i_rSet2->EOF) {
            echo "<option value=" . $i_rSet2->fields["testid"];
            if (isset($_GET["testid"]) && ($_GET["testid"] == $i_rSet2->fields["testid"]))
                echo " selected=selected";
            echo ">" . convertTextValue($i_rSet2->fields["test_name"]) . "</option>\n";
            $i_rSet2->MoveNext();
        }
        $i_rSet2->Close();
    }
    echo '</select></td></tr></table>';

// FILTER END

    if (isset($_GET['testid']) && strlen($_GET['testid']) > 0) {
        $sqladd .= ' AND kkztresults.testid=' . $_GET['testid'];
    }
    if (isset($_GET['subjectid']) && strlen($_GET['subjectid']) > 0) {
        $sqladd .= ' AND kkzttests.subjectid = ' . $_GET['subjectid'];
    }

    /* if (isset($_GET['groupid']) && strlen($_GET['groupid']) > 0 && (!isset($_GET['userid']) || strlen($_GET['userid']) == 0)) {
      $sqladd .= ' AND kkztresults.userid IN (';
      $sqladd .= implode(',', $userToSql);
      $sqladd .= ')';
      } */

    if (isset($_GET['userid']) && strlen($_GET['userid']) > 0) {
        $sqladd .= ' AND kkztresults.userid = ' . $_GET['userid'];
    }

    $i_limitcount = isset($_GET["limitto"]) ? (int) $_GET["limitto"] : $G_SESSION['config_itemsperpage'];
    if ($i_limitcount > 0) {
        $i_recordcount = getRecordCount($srv_settings['table_prefix'] . 'results, ' . $srv_settings['table_prefix'] .
                'tests, ' . $srv_settings['table_prefix'] . 'gscales_grades', $i_sql_where_addon .
                " " . $srv_settings['table_prefix'] . "results.testid=" . $srv_settings['table_prefix'] .
                "tests.testid AND " . $srv_settings['table_prefix'] . "results.gscaleid=" . $srv_settings['table_prefix'] .
                "gscales_grades.gscaleid AND " . $srv_settings['table_prefix'] .
                "results.gscale_gradeid=" . $srv_settings['table_prefix'] .
                "gscales_grades.gscale_gradeid" . $sqladd);
        $i_pageno = isset($_GET["pageno"]) ? (int) $_GET["pageno"] : 1;
        if ($i_pageno < 1)
            $i_pageno = 1;
        $i_limitfrom = ($i_pageno - 1) * $i_limitcount;
        $i_pageno_count = floor(($i_recordcount - 1) / $i_limitcount) + 1;
        if ($i_limitfrom > $i_recordcount) {
            $i_pageno = $i_pageno_count;
            $i_limitfrom = ($i_pageno - 1) * $i_limitcount;
        }
        $i_url_limitto_addon .= "&limitto=" . $i_limitcount;
        $i_url_pageno_addon .= "&pageno=" . $i_pageno;
        $i_url_limit_addon .= $i_url_limitto_addon . $i_url_pageno_addon;
    } else {
        $i_url_limitto_addon = "&limitto=";
        $i_url_limit_addon .= $i_url_limitto_addon;
        $i_limitfrom = -1;
        $i_limitcount = -1;
    }


    echo '<p><form name=resultsForm class=iactive method=post><table cellpadding=0 cellspacing=0 border=0 width="100%"><tr><td>';
    echo '<table cellpadding=0 cellspacing=0 border=0 width="100%" style="background: url(images/toolbar-background.gif) repeat-x"><tr vAlign=center><td width=2><img src="images/toolbar-left.gif" width=2 height=32></td>';
    echo '<td width="100%">&nbsp;</td>';
    if ($i_limitcount > 0) {
        if ($i_pageno > 1) {
            echo '<td width=32><a href="reports-manager.php?pageno=1' . $i_url_limitto_addon .
            $i_userid_addon . $i_testid_addon . $i_order_addon .
            '"><img src="images/button-first-big.gif" border=0 title="' . $lngstr['button_first_page'] .
            '"></a></td>';
            echo '<td width=32><a href="reports-manager.php?pageno=' . max(($i_pageno - 1), 1) . $i_url_limitto_addon . $i_userid_addon . $i_groupid_addon . $i_testid_addon . $i_order_addon .
            '"><img src="images/button-prev-big.gif" border=0 title="' . $lngstr['button_prev_page'] .
            '"></a></td>';
        } else {
            echo '<td width=32><img src="images/button-first-big-inactive.gif" border=0 title="' .
            $lngstr['button_first_page'] . '"></td>';
            echo '<td width=32><img src="images/button-prev-big-inactive.gif" border=0 title="' .
            $lngstr['button_prev_page'] . '"></td>';
        }
        if ($i_pageno < $i_pageno_count) {
            echo '<td width=32><a href="reports-manager.php?pageno=' . min(($i_pageno + 1), $i_pageno_count) . $i_url_limitto_addon . $i_userid_addon . $i_groupid_addon . $i_testid_addon . $i_subjectid_addon . $i_order_addon .
            '"><img src="images/button-next-big.gif" border=0 title="' . $lngstr['button_next_page'] .
            '"></a></td>';
            echo '<td width=32><a href="reports-manager.php?pageno=' . $i_pageno_count . $i_url_limitto_addon .
            $i_userid_addon . $i_groupid_addon . $i_testid_addon . $i_subjectid_addon . $i_order_addon .
            '"><img src="images/button-last-big.gif" border=0 title="' . $lngstr['button_last_page'] .
            '"></a></td>';
        } else {
            echo '<td width=32><img src="images/button-next-big-inactive.gif" border=0 title="' .
            $lngstr['button_next_page'] . '"></td>';
            echo '<td width=32><img src="images/button-last-big-inactive.gif" border=0 title="' .
            $lngstr['button_last_page'] . '"></td>';
        }
    }
    echo '<td width=2><img src="images/toolbar-right.gif" width=2 height=32></td></tr></table>';
    echo '</td></tr><tr><td>';
    echo '<table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">';
    echo '<tr>';
//echo '<td class=rowhdr1 title="' . $lngstr['label_hdr_select_hint'] . '" width=22><input type=checkbox name=toggleAll onclick="toggleCBs(this);"></td>';
    writeQryTableHeaders('reports-manager.php?action=' . $i_userid_addon . $i_groupid_addon . $i_testid_addon . $i_url_limit_addon, $i_tablefields, $i_order_no, $i_direction);
    echo '<td class=rowhdr1 colspan=2>' . $lngstr['label_hdr_action'] . '</td></tr>';


//if (!isset($_GET['testid']) && !isset($_GET['subjectid']))
//    $sqladd = " AND kkzttests.subjectid = 5 AND kkzttests.subjectid = 6";

    $i_rSet1 = $g_db->SelectLimit(" SELECT kkztresults.resultid, kkztresults.result_datestart,
kkztresults.userid, kkztresults.testid, kkzttests.test_name, kkzttests.subjectid, kkzttests.test_attempts, 
kkztresults.result_timeexceeded, kkztresults.result_points, kkztresults.result_pointsmax, 
ROUND( (
kkztresults.result_points / kkztresults.result_pointsmax
) *100, 0 ) AS result_score, kkztresults.gscaleid, kkztgscales_grades.grade_name, 
kkztgscales_grades.grade_description
FROM kkztresults, kkzttests, kkztgscales_grades
WHERE kkzttests.testid = kkztresults.testid
AND kkztresults.testid = kkzttests.testid
AND kkztresults.gscaleid = kkztgscales_grades.gscaleid
AND kkztresults.gscale_gradeid = kkztgscales_grades.gscale_gradeid
" . $sqladd . "" . $i_sql_order_addon, $i_limitcount, $i_limitfrom);

    if (!$i_rSet1) {
        showDBError(__file__, 1);
    }
    $i_counter = 0;
    while (!$i_rSet1->EOF) {
        $rowname = ($i_counter % 2) ? "rowone" : "rowtwo";
        $user = getUserName($i_rSet1->fields["userid"]);
        echo '<tr id=tr_' . $i_pagewide_id . ' class=' . $rowname . ' onmouseover="rollTR(' . $i_pagewide_id . ',1);" onmouseout="rollTR(' . $i_pagewide_id . ',0);">';
        //echo '<td align=center width=22><input id=cb_' . $i_pagewide_id .' type=checkbox name=box_results[] value="' . $i_rSet1->fields["resultid"] .'" onclick="toggleCB(this);"></td>';
        echo '<td align=right>' . $i_rSet1->fields["resultid"] .
        '</td><td>' . date($lngstr['date_format'], $i_rSet1->fields["result_datestart"]) .
        '</td><td>' . $groups_all[$user['group']] . ' » <a href="reports-manager.php?userid=' . (isset($_GET["userid"]) && $_GET["userid"] != "" ? $_GET["userid"] : $i_rSet1->fields["userid"]) . $i_testid_addon . $i_order_addon . $i_url_limit_addon . '">' . $user['lastname'] . ' ' . $user['name'] . '</a></td>
            <td><a href="reports-manager.php?testid=' . (isset($_GET["testid"]) && $_GET["testid"] != "" ? $_GET["testid"] : $i_rSet1->fields["testid"]) . $i_userid_addon . $i_order_addon . $i_url_limit_addon . '">' . convertTextValue($i_rSet1->fields["test_name"]) . '</a></td>' .
        '<td><a href="reports-manager.php?subjectid=' . (isset($_GET["subjectid"]) && $_GET["subjectid"] != "" ? $_GET["subjectid"] : $i_rSet1->fields["subjectid"]) . $i_userid_addon . $i_order_addon . $i_url_limit_addon . '">' . $courses_all[$i_rSet1->fields["subjectid"]]['name'] . '</a></td>';



        if ($i_rSet1->fields["test_attempts"]) {

            $i_attempt_count = 0;
            $i_rSet4 = $g_db->CacheExecute(500,"SELECT test_attempt_count FROM " . $srv_settings['table_prefix'] .
                    "tests_attempts WHERE testid=" . $i_rSet1->fields["testid"] . " AND userid=" . $i_rSet1->fields["userid"]);
            if (!$i_rSet4) {
                showDBError(__file__, 3);
            } else {
                if (!$i_rSet4->EOF)
                    $i_attempt_count = $i_rSet4->fields["test_attempt_count"];
                $i_rSet4->Close();
            }
            echo '<td align=center>' . (($i_rSet1->fields["test_attempts"] <= $i_attempt_count) ?
                    '<a href="reports-manager.php?action=attempts&set=0&testid=' . $i_rSet1->fields["testid"] .
                    '&userid=' . $i_rSet1->fields["userid"] . $i_order_addon . $i_url_limit_addon .
                    '"><img src="images/button-checkbox-2.gif" width=13 height=13 border=0 title="' .
                    $lngstr['label_yes'] . '"></a>' :
                    '<a href="reports-manager.php?action=attempts&set=1&testid=' . $i_rSet1->fields["testid"] .
                    '&userid=' . $i_rSet1->fields["userid"] . $i_order_addon . $i_url_limit_addon .
                    '"><img src="images/button-checkbox-0.gif" width=13 height=13 border=0 title="' .
                    $lngstr['label_no'] . '"></a>') . '</td>';
        } else {
            echo '<td align=center><img src="images/button-checkbox-0.gif" width=13 height=13 border=0 title="' .
            $lngstr['label_no'] . '"></td>';
        }
        echo '<td align=center>' . ($i_rSet1->fields["result_timeexceeded"] ?
                '<img src="images/button-checkbox-2.gif" width=13 height=13 border=0 title="' .
                $lngstr['label_yes'] . '">' :
                '<img src="images/button-checkbox-0.gif" width=13 height=13 border=0 title="' .
                $lngstr['label_no'] . '">') . '</td><td align=center>' . $i_rSet1->fields["result_points"] . ' з ' . $i_rSet1->fields["result_pointsmax"] . ' (' . sprintf("%.1f", $i_rSet1->fields["result_score"]) . '%)</td>
               <td align=center title="' . convertTextValue($i_rSet1->fields["grade_description"]) . '" class=point>' . convertTextValue($i_rSet1->fields["grade_name"]) . '</td>';
        echo '<td align=center width=22><a href="reports-manager.php?resultid=' . $i_rSet1->fields["resultid"] . '&action=viewq"><img width=20 height=20 border=0 src="images/button-view.gif" title="' .
        $lngstr['label_action_test_result_view'] . '"></a></td>';
        if ($G_SESSION['allcoms'] == 1)
            echo '<td align=center width=22>
      <a href="reports-manager.php?resultid=' . $i_rSet1->fields["resultid"] . '&action=delete" onclick="return confirmMessage(this, \'' . $lngstr['qst_delete_record'] . '\')">
      <img width=20 height=20 border=0 src="images/button-cross.gif" title="' . $lngstr['label_action_test_result_delete'] . '">
      </a>
      </td>';

        echo '</tr>';
        $i_counter++;
        $i_pagewide_id++;
        $i_rSet1->MoveNext();
    }
    $i_rSet1->Close();

    echo '</table>';
    echo '</td></tr></table></form>';

    echo '<br/><center><font style="font-size:16px;">';
    echo getPageNav($i_pageno, $i_pageno_count, '/reports-manager.php?', $i_url_limitto_addon . $i_order_addon . $i_userid_addon . $i_groupid_addon . $i_testid_addon, "&limitto=" . $i_limitfrom);
    echo '</font></center>';

    require_once ($DOCUMENT_INC . "btm.inc.php");
    ?>
