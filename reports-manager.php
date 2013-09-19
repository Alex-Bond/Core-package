<?php

require_once("inc/init.inc.php");
if (isset($G_SESSION['userid'])) {
    if ($G_SESSION['access_reportsmanager'] > 1) {

        $page_title = $lngstr['page_title_results'];
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'viewq':
                    $page_title = $lngstr['page_title_results_questions'] . $lngstr['item_separator'] . $page_title;
                    if (isset($_GET['resultid'])) {
                        include_once($DOCUMENT_PAGES . "reports-manager-2.inc.php");
                    }
                    break;
                case 'viewa':
                    $page_title = $lngstr['page_title_results_answers'] . $lngstr['item_separator'] . $page_title;
                    if (isset($_GET['resultid']) && isset($_GET['answerid'])) {
                        include_once($DOCUMENT_PAGES . "reports-manager-3.inc.php");
                    }
                    break;
                case 'delete':
                    if ($G_SESSION['access_reportsmanager'] > 2) {

                        $f_confirmed = readGetVar('confirmed');
                        if ($f_confirmed == 1) {
                            if (isset($_GET['resultid']) || isset($_POST["box_results"])) {

                                include_once($DOCUMENT_PAGES . "reports-manager-4.inc.php");
                            } else {
                                gotoLocation('reports-manager.php');
                            }
                        } else if ($f_confirmed == '0') {
                            gotoLocation('reports-manager.php');
                        } else {

                            $i_confirm_header = $lngstr['page_results_delete_record'];
                            $i_confirm_request = $lngstr['qst_delete_record'];
                            $i_confirm_url = 'reports-manager.php' . getURLAddon();
                            include_once($DOCUMENT_PAGES . "confirm.inc.php");
                        }
                    } else {
                        gotoLocation('reports-manager.php' . getURLAddon('', array('action', 'resultid')));
                    }
                    break;
                case 'setpoints':
                    if ($G_SESSION['access_reportsmanager'] > 2) {
                        if (isset($_GET['resultid']) && isset($_GET['answerid'])) {
                            include_once($DOCUMENT_PAGES . "reports-manager-5.inc.php");
                        }
                    } else {
                        gotoLocation('reports-manager.php' . getURLAddon('?action=viewa', array('action', 'resultid', 'answerid', 'set')));
                    }
                    break;
                case 'attempts':
                    if ($G_SESSION['access_reportsmanager'] > 2) {
                        if (isset($_GET['testid']) && isset($_GET['userid'])) {
                            include_once($DOCUMENT_PAGES . "reports-manager-6.inc.php");
                        }
                    } else {
                        gotoLocation('reports-manager.php' . getURLAddon('?action=', array('action', 'testid', 'userid', 'set')));
                    }
                    break;
                default:
                    include_once($DOCUMENT_PAGES . "reports-manager-1.inc.php");
            }
        } else {
            include_once($DOCUMENT_PAGES . "reports-manager-1.inc.php");
        }
    } else {

        $input_inf_msg = $lngstr['inf_cant_view_results'];
        include_once($DOCUMENT_PAGES . "home.inc.php");
    }
} else {

    $page_title = $lngstr['page_title_signin'];
    include_once($DOCUMENT_PAGES . "signin-1.inc.php");
}
?>
