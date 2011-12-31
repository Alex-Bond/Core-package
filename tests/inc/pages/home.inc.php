<?php
require_once ($DOCUMENT_INC . "top.inc.php");

function ip_vs_net($ip, $network, $mask) {
    if (((ip2long($ip)) & (ip2long($mask))) == ip2long($network)) {
        return 1;
    } else {
        return 0;
    }
}

if ((ip_vs_net($_SERVER['REMOTE_ADDR'], "11.1.1.0", "255.255.255.0") OR ip_vs_net($_SERVER['REMOTE_ADDR'], "192.168.0.0", "255.255.255.0")) OR $G_SESSION ['access_reportsmanager'] > 1) {
    $sqladd = '';
} else {
    $input_inf_msg = 'Увага! Ваш комп\'ютер знаходиться за межами комп\'ютерної мережі Київського Коледжу Зв\'язку. Вам доступні тільки тести дозволені до проходження з мережі Інтернет.';
    $sqladd = ' AND `kkzttests`.`test_internet`=1 ';
}


if ($G_SESSION ['access_tests'] > 0 AND $G_SESSION ['access_reportsmanager'] == 0) {
    echo '<h2>' . $lngstr ['page_header_panel'] . '</h2>' . "\n";
} else {
    echo '<h2>Проходження тестування real-time</h2>' . "\n";
}

writeErrorsWarningsBar();


if ($G_SESSION ['access_tests'] > 0 AND $G_SESSION ['access_reportsmanager'] == 0) {


    echo "\n" . '<p><table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">' . "\n";
    echo '<tr><td class=rowhdr1 title="' . $lngstr ['page_panel_hdr_test_hint'] . '">' . $lngstr ['page_panel_hdr_test'] . '</td><td class=rowhdr1>Предмет</td><td class=rowhdr1 title="' . $lngstr ['page_panel_hdr_teststatus_hint'] . '">' . $lngstr ['page_panel_hdr_teststatus'] . '</td><td class=rowhdr1 title="' . $lngstr ['page_panel_hdr_gettest_hint'] . '">' . $lngstr ['page_panel_hdr_gettest'] . '</td></tr>' . "\n";
    $now = time();
    $i_test_count = 0;





    $i_rSet1 = $g_db->Execute("	
	SELECT `kkzttests`.testid, test_name, subjectid, test_description, test_datestart, test_attempts, test_forall, count(`kkztgroups_tests`.groupid) AS GTCOUNT, `kkzttests_attempts`.test_attempt_count
FROM `kkzttests`
LEFT JOIN `kkzttests_attempts` ON `kkzttests_attempts`.testid = `kkzttests`.testid AND `kkzttests_attempts`.userid = " . $G_SESSION ['userid'] . " 
LEFT JOIN `kkztgroups_tests` ON `kkztgroups_tests`.testid=`kkzttests`.testid
WHERE `kkzttests`.test_enabled=1 AND `kkzttests`.test_dateend>" . $now . " 
AND ((`kkztgroups_tests`.groupid=" . $G_SESSION ['usergroup'] . "  AND `kkzttests`.test_forall=0) OR `kkzttests`.test_forall=1) " . $sqladd . " 
 GROUP BY `kkzttests`.testid
 ");
    if (!$i_rSet1) {
        showDBError(__file__, 1);
    } else {
        while (!$i_rSet1->EOF) {

            $i_isallowed = $i_rSet1->fields ["test_forall"];
            if (!$i_isallowed)
                $i_isallowed = $i_rSet1->fields ["GTCOUNT"] > 0;


            $i_attempt_count = 0;

//i_rset3
//$i_rSet3 = $g_db->Execute ( "SELECT test_attempt_count FROM " . $srv_settings ['table_prefix'] . "tests_attempts WHERE testid=" . $i_rSet1->fields ["testid"] . " AND userid=" . $G_SESSION ['userid'] );
//if (! $i_rSet3) {
//	showDBError ( __file__, 3 );
//} else {
//	if (! $i_rSet3->EOF)
//$i_attempt_count = $i_rSet3->fields ["test_attempt_count"];
            $i_attempt_count = $i_rSet1->fields ["test_attempt_count"];
//	$i_rSet3->Close ();
//}

            if ($i_isallowed) {
                $i_rownotext = (++$i_test_count % 2) ? "rowtwo" : "rowone";
                $course = $core->getcourse($i_rSet1->fields ["subjectid"]);
                $course = $course['name'];
                if ($i_rSet1->fields ["test_datestart"] >= $now) {

                    echo '<tr class=' . $i_rownotext . '><td><b>' . ($i_rSet1->fields ["test_name"] ? convertTextValue($i_rSet1->fields ["test_name"]) : $lngstr ['label_noname']) . '</b><br>' . convertTextValue($i_rSet1->fields ["test_description"]) . '</td><td>' . $course . '</td><td align=center>' . sprintf($lngstr ['page_panel_status_will_be_available_on'], date($lngstr ['date_format'], $i_rSet1->fields ["test_datestart"])) . '</td><td align=center><b>' . $lngstr ['page_panel_get_test_link'] . '</b></td></tr>' . "\n";
                } else if ($i_rSet1->fields ["test_attempts"] > 0) {

                    if ($i_attempt_count >= $i_rSet1->fields ["test_attempts"]) {
                        echo '<tr class=' . $i_rownotext . '><td><b>' . ($i_rSet1->fields ["test_name"] ? convertTextValue($i_rSet1->fields ["test_name"]) : $lngstr ['label_noname']) . '</b><br>' . convertTextValue($i_rSet1->fields ["test_description"]) . '</td><td>' . $course . '</td><td align=center>' . $lngstr ['page-takeatest'] ['attempts_limit_reached'] . '</td><td align=center><b>' . $lngstr ['page_panel_get_test_link'] . '</b></td></tr>' . "\n";
                    } else {
                        echo '<tr class=' . $i_rownotext . '><td width="60%"><b><a href="test.php?testid=' . $i_rSet1->fields ["testid"] . '">' . ($i_rSet1->fields ["test_name"] ? convertTextValue($i_rSet1->fields ["test_name"]) : $lngstr ['label_noname']) . '</a></b><br>' . convertTextValue($i_rSet1->fields ["test_description"]) . '</td><td>' . $course . '</td><td align=center>' . sprintf($lngstr ['page-takeatest'] ['attempts_left'], $i_rSet1->fields ["test_attempts"] - $i_attempt_count) . '</td><td align=center><b><a href="test.php?testid=' . $i_rSet1->fields ["testid"] . '">' . $lngstr ['page_panel_get_test_link'] . '</a></b></td></tr>' . "\n";
                    }
                } else {

                    echo '<tr class=' . $i_rownotext . '><td width="60%"><b><a href="test.php?testid=' . $i_rSet1->fields ["testid"] . '">' . ($i_rSet1->fields ["test_name"] ? convertTextValue($i_rSet1->fields ["test_name"]) : $lngstr ['label_noname']) . '</a></b><br>' . convertTextValue($i_rSet1->fields ["test_description"]) . '</td><td>' . $course . '</td><td align=center>' . $lngstr ['page_panel_status_available'] . '</td><td align=center><b><a href="test.php?testid=' . $i_rSet1->fields ["testid"] . '">' . $lngstr ['page_panel_get_test_link'] . '</a></b></td></tr>' . "\n";
                }
            }
            $i_rSet1->MoveNext();
        }
        $i_rSet1->Close();
    }
} elseif ($G_SESSION['access_reportsmanager'] > 1) {
    ?>

    <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr><td width="60">
                Група: </td><td>
                <select name="group" onchange="document.location.href='/?group='+this.value" >
                    <?php
                    if (!isset($_GET['group'])) {
                        echo '<option value=""></option>';
                    }
                    $url = 'http://11.1.1.245/api/?do=groups';
                    $xml = simplexml_load_file($url);

                    foreach ($xml->group as $group) {

                        echo "<option value=" . $group->id;
                        if (isset($_GET["group"]) && ($_GET["group"] == $group->id))
                            echo " selected=selected";
                        echo " value='" . $group->id . "'>" . $group->name . "</option>\n";
                    }
                    ?>
                </select>
            </td></tr><tr><td>
                Предмет: 
            </td><td>
                <select name="subjectid" onchange="document.location.href='/?subjectid='+this.value" >
                    <?php
                    if (!isset($_GET['subjectid'])) {
                        echo '<option value=""></option>';
                        $selected = 0;
                    } else {
                    $selected = $_GET['subjectid'];
                }
                    if ($_SESSION['MAIN']['allcoms'] == 1) {
                        $coms = $core->getcommissions();
                        foreach ($coms as $com) {
                            echo '<OPTGROUP label="' . $com['name'] . '">';
                            $subj = $core->getcourses_id($com['id']);
                            foreach ($subj as $item) {
                                echo '<option value="' . $item['id'] . '" ' . (($item['id'] == $selected) ? 'selected="selected"' : '') . '>' . $item['name'] . '</option>';
                            }
                            echo '</OPTGROUP>';
                        }
                    } else {
                        $subj = $core->getcourses_id($G_SESSION['usercom']);
                        foreach ($subj as $item) {
                            echo '<option value="' . $item['id'] . '" ' . (($item['id'] == $selected) ? 'selected="selected"' : '') . '>' . $item['name'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </td></tr><tr><td>
                Тест: 
            </td><td>
                <select name="test" onchange="document.location.href='/?test='+this.value" >
                    <?php
                    $sqladd1 = '';
                    if ($G_SESSION['allcoms'] != 1) {
                        $courses = $core->getcourses_id($G_SESSION['usercom']);
                        foreach ($courses as $course) {
                            $courses_apply[$course['id']] = $course['id'];
                        }
                        $sqladd1 .= " WHERE subjectid IN (" . implode(',', $courses_apply) . ")";
                    }


                    if (!isset($_GET['test'])) {
                        echo '<option value=""></option>';
                    }
                    $i_rSet2 = $g_db->Execute("SELECT * FROM kkzttests " . $sqladd1);
                    if (!$i_rSet2) {
                        showDBError(__file__, 2);
                    } else {
                        while (!$i_rSet2->EOF) {
                            echo "<option value=" . $i_rSet2->fields["testid"];
                            if (isset($_GET["test"]) && ($_GET["test"] == $i_rSet2->fields["testid"]))
                                echo " selected=selected";
                            echo ">" . convertTextValue($i_rSet2->fields["test_name"]) . "</option>\n";
                            $i_rSet2->MoveNext();
                        }
                        $i_rSet2->Close();
                    }
                    ?>
                </select>
            </td></tr>
        <?php
        echo '<tr><td colspan="2">';
        if (isset($_GET['all'])) {
            echo '<b>Показати всіх</b>';
        } else {
            echo '<a href="/?all=1">Показати всіх</a>';
        }
        echo '</td></tr>';
        echo '</table>';
        if ((isset($_GET['group']) && $_GET['group'] > 0) OR (isset($_GET['subjectid']) && $_GET['subjectid'] > 0) OR (isset($_GET['test']) && $_GET['test'] > 0) OR (isset($_GET['all']) && $_GET['all'] > 0)) {
            ?>

            <script type="text/javascript" src="/js/home.admin.js"></script>
            <script type="text/javascript" src="/js/jquery-ui-1.8.4.custom.min.js"></script>
            <style type="text/css" >
                .row td {
                    border-bottom:thin dotted #999999;
                }
                .t_header th {
                    border-bottom:thin dotted #999999;
                }
                .row:hover {
                    background-color: #dfb;
                }
                .td_questions {
                    text-align:center;
                }
                .td_time {
                    padding: 0;
                    border-right: 1px solid;
                    border-left: 1px solid;
                }
                .td_point {
                    text-align:center;
                    font-weight: bold;
                    font-size: 16px;
                }
            </style>

            <table width="100%" border="0" cellspacing="0" cellpadding="5" id="realtime">
                <tr class="t_header">
                    <th>ПIП</th>
                    <th>Тест</th>
                    <th width="140">Пройдено питань</th>
                    <th width="150">Час</th>
                    <th width="50">Оцiнка</th>
                </tr>
                <tbody>

                </tbody>
            </table>
            <script type="text/javascript">
                type = <?php
        if (isset($_GET['group']) && $_GET['group'] > 0)
            echo 1;
        if (isset($_GET['subjectid']) && $_GET['subjectid'] > 0)
            echo 3;
        if (isset($_GET['test']) && $_GET['test'] > 0)
            echo 2;
        if (isset($_GET['all']) && $_GET['all'] > 0)
            echo 4;
        ?>;
            rtime_init(<?php
        if (isset($_GET['group']) && $_GET['group'] > 0)
            echo $_GET['group'];
        if (isset($_GET['subjectid']) && $_GET['subjectid'] > 0)
            echo $_GET['subjectid'];
        if (isset($_GET['test']) && $_GET['test'] > 0)
            echo $_GET['test'];
        if (isset($_GET['all']) && $_GET['all'] > 0 && $G_SESSION['allcoms']!=1 )
            echo $G_SESSION['usercom'];
        if (isset($_GET['all']) && $_GET['all'] > 0 && $G_SESSION['allcoms']==1)
            echo 0;
        ?>);
            </script>
            <?php
        }
    }

    if (isset($i_test_count) && !$i_test_count AND $G_SESSION['access_reportsmanager'] == 0) {
        echo '<tr class=rowone><td colspan=3>' . $lngstr ['err_no_tests'] . '</td></tr>' . "\n";
    }
    if ($G_SESSION['access_reportsmanager'] == 0) {
        echo '</table>' . "\n";
    }

    require_once ($DOCUMENT_INC . "btm.inc.php");
    ?>
