<?php

require_once("inc/init.inc.php");
if (isset($G_SESSION['userid'])) {
    if ($G_SESSION['access_tests'] > 1) {

        $page_title = $lngstr['page_title_test'];
        if (!isset($_GET['action']))
            $_GET['action'] = '';
        switch ($_GET['action']) {
            case 'results':
                if (isset($G_SESSION["yt_state"]) && ($G_SESSION["yt_state"] == TEST_STATE_TRESULTS)) {
                    include_once($DOCUMENT_PAGES . "test-showresults.inc.php");
                }
                break;
            default:
                if (!isset($G_SESSION['testid']) || (isset($G_SESSION["yt_state"]) && ($G_SESSION["yt_state"] >= TEST_STATE_TRESULTS))) {

                    include_once($DOCUMENT_PAGES . "test-3.inc.php");
                } else {

                    if (isset($_POST['bsubmit'])) {
                        if ($G_SESSION["yt_test_qsperpage"] == 0) {

                            include_once($DOCUMENT_PAGES . "test-7.inc.php");
                        } else {

                            include_once($DOCUMENT_PAGES . "test-2.inc.php");
                        }
                    } else {
                        if ($G_SESSION["yt_test_qsperpage"] == 0) {

                            include_once($DOCUMENT_PAGES . "test-6.inc.php");
                        } else {

                            include_once($DOCUMENT_PAGES . "test-1.inc.php");
                        }
                    }
                }
                break;
        }
    } else {
        $input_inf_msg = $lngstr['inf_cant_passtest'];
        include_once($DOCUMENT_PAGES . "home.inc.php");
    }
} else {

    $page_title = $lngstr['page_title_signin'];
    include_once($DOCUMENT_PAGES . "signin-1.inc.php");
}
?>
