<?php

$i_isallanswered = true;
if (!empty($_POST['answer']) && is_array($_POST['answer'])) {
    for ($i_questionno = 1; $i_questionno <= $G_SESSION["yt_questioncount"]; $i_questionno++) {
        if (empty($_POST['answer'][$i_questionno])) {
            $i_isallanswered = false;
            break;
        }
    }
} else {
    $i_isallanswered = false;
}
if (!$i_isallanswered) {
    $input_err_msg = $lngstr['err_answer_every_question'];
    include_once($DOCUMENT_PAGES . "test-6.inc.php");
    exit;
}

$i_now = time();
if (!$G_SESSION['yt_timeforceout'] || !($G_SESSION['yt_teststop'] > 0) || !($G_SESSION['yt_teststop'] < $i_now)) {
    for ($i_questionno = 1; $i_questionno <= $G_SESSION["yt_questioncount"]; $i_questionno++) {

        $i_questionno_real = $G_SESSION["yt_questions"][$i_questionno - 1];
        $i_questionid = $G_SESSION["yt_questionids"][$i_questionno_real];

        checkTestAnswer($i_questionno, $i_questionid, $_POST['answer'][$i_questionno]);
    }
}

$G_SESSION['yt_questionno'] = $G_SESSION['yt_questioncount'] + 1;
$key = new Rediska_Key('test_' . $G_SESSION["resultid"]);
$key->setValue($G_SESSION);

if ($G_SESSION["yt_test_showqfeedback"]) {

    $G_SESSION["yt_state"] = TEST_STATE_QFEEDBACK;
    include_once($DOCUMENT_PAGES . "test-6.inc.php");

    include_once($DOCUMENT_PAGES . "test-saveresults.inc.php");
} else {

    include_once($DOCUMENT_PAGES . "test-saveresults.inc.php");

    gotoLocation('test.php?action=results');
}
?>
