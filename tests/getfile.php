<?php

require_once("inc/init.inc.php");
if (isset($G_SESSION['userid'])) {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'tpdf':
                if (isset($_GET['resultid'])) {
                    include_once($DOCUMENT_PAGES . "getfile-1.inc.php");
                }
                break;
        }
    }
}
?>