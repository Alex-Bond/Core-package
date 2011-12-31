<?php

require_once("inc/init.inc.php");
if (isset($G_SESSION['userid'])) {
    if ($G_SESSION['access_testmanager'] > 0) {

        $page_title = $lngstr['page_title_edittests'];
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {

                case 'create':
                    if ($G_SESSION['access_testmanager'] > 1) {

                        include_once($DOCUMENT_PAGES . "test-manager-4.inc.php");
                    } else {
                        gotoLocation('test-manager.php' . getURLAddon('', array('action')));
                    }
                    break;
                case 'delete':
                    if ($G_SESSION['access_testmanager'] > 1) {
                        $f_confirmed = readGetVar('confirmed');

                        if ($f_confirmed == 1) {
                            if (isset($_GET['testid']) || isset($_POST["box_tests"])) {

                                include_once($DOCUMENT_PAGES . "test-manager-5.inc.php");
                            } else {
                                gotoLocation('test-manager.php');
                            }
                        } else if ($f_confirmed == '0') {
                            gotoLocation('test-manager.php');
                        } else {

                            $i_confirm_header = $lngstr['page_edittests_delete_test'];
                            $i_confirm_request = $lngstr['qst_delete_test'];
                            $i_confirm_url = 'test-manager.php?testid=' . (int) $_GET['testid'] . '&action=delete';
                            include_once($DOCUMENT_PAGES . "confirm.inc.php");
                        }
                    } else {
                        gotoLocation('test-manager.php' . getURLAddon('', array('action', 'testid', 'confirmed')));
                    }
                    break;
                case 'enable':
                    if ($G_SESSION['access_testmanager'] > 1) {
                        if (isset($_GET['testid'])) {
                            include_once($DOCUMENT_PAGES . "test-manager-6.inc.php");
                        }
                    } else {
                        gotoLocation('test-manager.php' . getURLAddon('', array('action')));
                    }
                    break;
                case 'notes':
                    if (isset($_GET['testid'])) {
                        include_once($DOCUMENT_PAGES . "test-manager-7.inc.php");
                    }
                    break;
                case 'editt':
                    $page_title = $lngstr['page_title_test_questions'] . $lngstr['item_separator'] . $page_title;
                    if (isset($_GET['testid'])) {
                        include_once($DOCUMENT_PAGES . "edit_questions-1.inc.php");
                    }
                    break;
                case 'upload':
                    $page_title = $lngstr['page_title_test_questions'] . $lngstr['item_separator'] . $page_title;
                    if (isset($_GET['testid'])) {
                        include_once($DOCUMENT_PAGES . "edit_questions-upload.inc.php");
                    }
                    break;
                case 'edits':
                    $page_title = $lngstr['page_title_test_sections'] . $lngstr['item_separator'] . $page_title;
                    if (isset($_GET['testid'])) {
                        include_once($DOCUMENT_PAGES . "test-manager-sections-1.inc.php");
                    }
                    break;
                case 'settings':
                    $page_title = $lngstr['page_title_test_settings'] . $lngstr['item_separator'] . $page_title;
                    if (isset($_GET['testid'])) {
                        if (isset($_POST['bsubmit']) || isset($_POST['bsubmit2']) || isset($_POST['to_upload'])) {
                            if ($G_SESSION['access_testmanager'] > 1) {
                                include_once($DOCUMENT_PAGES . "test-manager-3.inc.php");
                            } else {
                                if (isset($_POST['bsubmit2']))
                                    gotoLocation('test-manager.php' . getURLAddon('?action=editt', array('action')));
                                elseif (isset($_POST['to_upload'])) {
                                    gotoLocation('test-manager.php?action=upload&testid=' . $_GET['testid']);
                                    die();
                                }else
                                    gotoLocation('test-manager.php' . getURLAddon('', array('action', 'testid')));
                            }
                        } else if (isset($_POST['bcancel'])) {
                            gotoLocation('test-manager.php');
                        } else {
                            include_once($DOCUMENT_PAGES . "test-manager-2.inc.php");
                        }
                    }
                    break;
                case 'groups':
                    $page_title = $lngstr['page_title_test_assignto'] . $lngstr['item_separator'] . $page_title;
                    if (isset($_GET['testid']) || isset($_POST["box_tests"]) || isset($_GET["testids"])) {
                        include_once($DOCUMENT_PAGES . "test-manager-8.inc.php");
                    } else {
                        gotoLocation('test-manager.php');
                    }
                    break;
                case 'assignto':
                    if ($G_SESSION['access_testmanager'] > 1) {
                        if (isset($_GET['groupid']) && isset($_GET["testids"])) {
                            include_once($DOCUMENT_PAGES . "test-manager-9.inc.php");
                        } else {
                            gotoLocation('test-manager.php' . getURLAddon('?action=groups', array('action', 'groupid')));
                        }
                    } else {
                        gotoLocation('test-manager.php' . getURLAddon('?action=groups', array('action', 'groupid', 'set')));
                    }
                    break;
                case 'import':
                    if (isset($_GET['testid'])) {
                        if (isset($_POST['bsubmit'])) {
                            if (($G_SESSION['access_testmanager'] > 1) && ($G_SESSION['access_questionbank'] > 1)) {
                                include_once($DOCUMENT_PAGES . "test-manager-11.inc.php");
                            } else {
                                gotoLocation('test-manager.php' . getURLAddon('?action=editt', array('action')));
                            }
                        } else if (isset($_POST['bcancel'])) {
                            gotoLocation('test-manager.php' . getURLAddon('?action=editt', array('action')));
                        } else {
                            include_once($DOCUMENT_PAGES . "test-manager-10.inc.php");
                        }
                    }
                    break;

                case 'moveup':
                    if ($G_SESSION['access_testmanager'] > 1) {
                        if (isset($_GET['testid']) && isset($_GET['test_questionid']))
                            include_once($DOCUMENT_PAGES . "edit_questions-8.inc.php");
                    } else {
                        gotoLocation('test-manager.php' . getURLAddon('?action=editt', array('action')));
                    }
                    break;
                case 'movedown':
                    if ($G_SESSION['access_testmanager'] > 1) {
                        if (isset($_GET['testid']) && isset($_GET['test_questionid']))
                            include_once($DOCUMENT_PAGES . "edit_questions-9.inc.php");
                    } else {
                        gotoLocation('test-manager.php' . getURLAddon('?action=editt', array('action')));
                    }
                    break;
                case 'append':
                    if ($G_SESSION['access_testmanager'] > 1) {
                        if (isset($_GET['testid']) && (isset($_GET['questionid']) || isset($_POST["box_questions"]))) {
                            include_once($DOCUMENT_PAGES . "edit_questions-4.inc.php");
                        } else {
                            gotoLocation('test-manager.php' . getURLAddon('?action=editt', array('action')));
                        }
                    } else {
                        gotoLocation('test-manager.php' . getURLAddon('?action=editt', array('action')));
                    }
                    break;
                case 'deleteq':
                    if ($G_SESSION['access_testmanager'] > 1) {
                        $f_confirmed = readGetVar('confirmed');

                        if ($f_confirmed == 1) {
                            if (isset($_GET['testid']) && (isset($_GET['test_questionid']) || isset($_POST["box_qlinks"]))) {
                                include_once($DOCUMENT_PAGES . "edit_questions-5.inc.php");
                            } else {
                                gotoLocation('test-manager.php' . getURLAddon('', array('action', 'confirmed', 'test_questionid')));
                            }
                        } else if ($f_confirmed == '0') {
                            gotoLocation('test-manager.php' . getURLAddon('?action=editt', array('action', 'test_questionid')));
                        } else {
                            $i_confirm_header = $lngstr['page_edittests_delete_question_link'];
                            $i_confirm_request = $lngstr['qst_delete_question_link'];
                            $i_confirm_url = 'test-manager.php' . getURLAddon('');
                            include_once($DOCUMENT_PAGES . "confirm.inc.php");
                        }
                    } else {
                        gotoLocation('test-manager.php' . getURLAddon('?action=editt', array('action', 'confirmed')));
                    }
                    break;
                default:
                    include_once($DOCUMENT_PAGES . "test-manager-1.inc.php");
            }
        } else {
            include_once($DOCUMENT_PAGES . "test-manager-1.inc.php");
        }
    } else {

        $input_inf_msg = $lngstr['inf_cant_edit_tests'];
        include_once($DOCUMENT_PAGES . "home.inc.php");
    }
} else {

    $page_title = $lngstr['page_title_signin'];
    include_once($DOCUMENT_PAGES . "signin-1.inc.php");
}
?>
