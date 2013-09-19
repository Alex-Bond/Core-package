<?php

if (isset($G_SESSION['questionid'])) {
    if ((isset($_POST['answer']) && isset($_POST['answer'][$G_SESSION['yt_questionno']])) || ($G_SESSION["yt_state"] == TEST_STATE_QFEEDBACK)) {
        $i_now = time();
        if (!$G_SESSION['yt_timeforceout'] || !($G_SESSION['yt_teststop'] > 0) || !($G_SESSION['yt_teststop'] < $i_now)) {
            if ($G_SESSION["yt_test_showqfeedback"] && ($G_SESSION["yt_state"] != TEST_STATE_QFEEDBACK)) {

                checkTestAnswer($G_SESSION['yt_questionno'], $G_SESSION['questionid'], $_POST['answer'][$G_SESSION['yt_questionno']]);

                $G_SESSION["yt_state"] = TEST_STATE_QFEEDBACK;
                include_once($DOCUMENT_PAGES . "test-1.inc.php");
            } else {
                $G_SESSION["yt_state"] = TEST_STATE_QSHOW;
                if (!$G_SESSION["yt_test_showqfeedback"]) {

                    checkTestAnswer($G_SESSION['yt_questionno'], $G_SESSION['questionid'], $_POST['answer'][$G_SESSION['yt_questionno']]);
                }

                unset($G_SESSION['questionid']);
                unset($G_SESSION['yt_questionstart']);

                $G_SESSION['yt_questionno']++;

                if ($G_SESSION['yt_questionno'] <= $G_SESSION['yt_questioncount']) {
                    $key = new Rediska_Key('test_'.$G_SESSION["resultid"]);
                    $key->setValue($G_SESSION);

                    setUserSessionTest($G_SESSION["userid"], $G_SESSION["resultid"]);

                    gotoLocation('test.php');
                } else {

                    include_once($DOCUMENT_PAGES . "test-saveresults.inc.php");

                    gotoLocation('test.php?action=results');
                }
            }
        } else {

            include_once($DOCUMENT_PAGES . "test-saveresults.inc.php");

            gotoLocation('test.php?action=results');
        }
    } else {
        $input_err_msg = $lngstr['err_no_answer_given'];
        include_once($DOCUMENT_PAGES . "test-1.inc.php");
    }
} else {

    include_once($DOCUMENT_PAGES . "test-1.inc.php");
}
?>
