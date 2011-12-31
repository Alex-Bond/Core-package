<?php

require_once ("inc/init.inc.php");

if ($G_SESSION['access_reportsmanager'] > 1) {

    //INIT

    if (isset($_GET['action']) && $_GET['action'] == 'init') {

        if (isset($_GET['group'])) {
            $students_core = $core->getstudentsingroup($_GET['group']);
            foreach ($students_core as $key => $item) {
                $students_array[] = $item['id'];
            }
            $sql_add = implode(',', $students_array);
            $result_db = $g_db->Execute("SELECT * from kkztresults WHERE userid in(" . $sql_add . ") AND gscale_gradeid=0 AND result_points=0 AND result_lastact>" . (time() - 30));
        }
        if (isset($_GET['subjectid']))
            $result_db = $g_db->Execute("SELECT `kkztresults`.`resultid` from kkztresults, kkzttests WHERE `kkzttests`.`testid`=`kkztresults`.`testid` AND `kkzttests`.`subjectid`=" . $_GET['subjectid'] . " AND  `kkztresults`.gscale_gradeid=0 AND `kkztresults`.result_points=0 AND `kkztresults`.result_lastact>" . (time() - 30));
        if (isset($_GET['test']))
            $result_db = $g_db->Execute("SELECT * from kkztresults WHERE testid = " . $_GET['test'] . " AND gscale_gradeid=0 AND result_points=0 AND result_lastact>" . (time() - 30));
        if (isset($_GET['all']) && isset($_GET['id'])) {
            $courses_core = $core-> getcourses_id($_GET['id']);
            foreach ($courses_core as $key => $item) {
                $courses_array[] = $item['id'];
            }
            $sql_add = implode(',', $courses_array);
            $result_db = $g_db->Execute("SELECT * from kkztresults WHERE subjectid in(" . $sql_add . ") AND gscale_gradeid=0 AND result_points=0 AND result_lastact>" . (time() - 30));
        }
        if (isset($_GET['all']) && $G_SESSION['allcoms'] == 1)
            $result_db = $g_db->Execute("SELECT * from kkztresults WHERE gscale_gradeid=0 AND result_points=0 AND result_lastact>" . (time() - 30));
        if (!$result_db) {
            showDBError(__file__, 1);
        } else {
            while (!$result_db->EOF) {
                $key = new Rediska_Key('test_' . $result_db->fields['resultid']);
                $answer = $key->getValue();
                $answer['now_time'] = time();
                $return[] = $answer;
                $result_db->MoveNext();
            }
            echo json_encode($return);
        }
    }

    //UPDATE USERS

    if (isset($_GET['max']) && isset($_GET['action']) && $_GET['action'] == 'uusers') {
        $students_core = $core->getstudentsingroup($_GET['group']);
        foreach ($students_core as $key => $item) {
            $students_array[] = $item['id'];
        }
        $sql_add = implode(',', $students_array);
        $result_db = $g_db->Execute("SELECT * from kkztresults WHERE userid in(" . $sql_add . ") AND gscale_gradeid=0 AND result_points=0 AND resultid>" . $_GET['max'] . " AND result_lastact>" . (time() - 30));

        if (isset($_GET['group'])) {
            $students_core = $core->getstudentsingroup($_GET['group']);
            foreach ($students_core as $key => $item) {
                $students_array[] = $item['id'];
            }
            $sql_add = implode(',', $students_array);
            $result_db = $g_db->Execute("SELECT * from kkztresults WHERE userid in(" . $sql_add . ") AND gscale_gradeid=0 AND result_points=0 AND resultid>" . $_GET['max'] . " AND result_lastact>" . (time() - 30));
        }
        if (isset($_GET['subjectid']))
            $result_db = $g_db->Execute("SELECT `kkztresults`.`resultid` from kkztresults, kkzttests WHERE `kkzttests`.`testid`=`kkztresults`.`testid` AND `kkzttests`.`subjectid`=" . $_GET['subjectid'] . " AND  `kkztresults`.gscale_gradeid=0 AND `kkztresults`.result_points=0 AND `kkztresults`.resultid>" . $_GET['max'] . " AND `kkztresults`.result_lastact>" . (time() - 30));
        if (isset($_GET['test']))
            $result_db = $g_db->Execute("SELECT * from kkztresults WHERE testid = " . $_GET['test'] . " AND gscale_gradeid=0 AND result_points=0 AND resultid>" . $_GET['max'] . " AND result_lastact>" . (time() - 30));
        if (isset($_GET['all']) && $G_SESSION['allcoms'] != 1) {
            $courses_core = $core-> getcourses_id($G_SESSION['usercom']);
            foreach ($courses_core as $key => $item) {
                $courses_array[] = $item['id'];
            }
            $sql_add = implode(',', $courses_array);
            $result_db = $g_db->Execute("SELECT * from kkztresults WHERE subjectid in(" . $sql_add . ") AND gscale_gradeid=0 AND result_points=0 AND resultid>" . $_GET['max'] . " AND result_lastact>" . (time() - 30));
        }
        if (isset($_GET['all']) && $G_SESSION['allcoms'] == 1)
            $result_db = $g_db->Execute("SELECT * from kkztresults WHERE gscale_gradeid=0 AND result_points=0 AND resultid>" . $_GET['max'] . " AND result_lastact>" . (time() - 30));

        if (!$result_db) {
            showDBError(__file__, 1);
        } else {
            while (!$result_db->EOF) {
                $key = new Rediska_Key('test_' . $result_db->fields['resultid']);
                $answer = $key->getValue();
                $answer['now_time'] = time();
                $return[] = $answer;
                $result_db->MoveNext();
            }
            echo json_encode(isset($return) ? $return : null);
        }
    }

    // UPDATE USER

    if (isset($_GET['user']) && isset($_GET['action']) && $_GET['action'] == 'update') {
        $key = new Rediska_Key('test_' . $_GET['user']);
        $answer = $key->getValue();
        $answer['now_time'] = time();
        echo json_encode($answer);
    }
}

//USER ACTIVITY

if (isset($_GET['resultid']) && isset($_GET['action']) && $_GET['action'] == 'uact') {
    $result_db = $g_db->Execute("UPDATE kkztresults SET `result_lastact`=" . time() . " WHERE `resultid`=" . $_GET['resultid']);
    if (!$result_db) {
        showDBError(__file__, 1);
    }
}