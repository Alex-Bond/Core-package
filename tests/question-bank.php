<?php
require_once ("inc/init.inc.php");
if (isset ( $G_SESSION ['userid'] )) {
	if ($G_SESSION ['access_questionbank'] > 0) {
		
		$page_title = $lngstr ['page_title_questionbank'];
		if (isset ( $_GET ['action'] )) {
			switch ($_GET ['action']) {
				case 'createq' :
					if ($G_SESSION ['access_questionbank'] > 1) {
						include_once ($DOCUMENT_PAGES . "edit_questions-7.inc.php");
					} else {
						if (isset ( $_GET ['testid'] ))
							gotoLocation ( 'test-manager.php' . getURLAddon ( '?action=editt', array ('action' ) ) );
						else
							gotoLocation ( 'question-bank.php' . getURLAddon ( '', array ('action' ) ) );
					}
					break;
				case 'deleteq' :
					if ($G_SESSION ['access_questionbank'] > 1) {
						$f_confirmed = readGetVar ( 'confirmed' );
						
						if ($f_confirmed == 1) {
							if (isset ( $_GET ['questionid'] ) || isset ( $_POST ["box_questions"] )) {
								
								include_once ($DOCUMENT_PAGES . "edit_questions-6.inc.php");
							} else {
								gotoLocation ( 'question-bank.php' . getURLAddon ( '', array ('action', 'confirmed', 'questionid' ) ) );
							}
						} else if ($f_confirmed == '0') {
							gotoLocation ( 'question-bank.php' . getURLAddon ( '', array ('action', 'questionid' ) ) );
						} else {
							
							$i_confirm_header = $lngstr ['page_edittests_delete_question'];
							$i_confirm_request = $lngstr ['qst_delete_question'];
							$i_confirm_url = 'question-bank.php' . getURLAddon ();
							include_once ($DOCUMENT_PAGES . "confirm.inc.php");
						}
					} else {
						if (isset ( $_GET ['testid'] ))
							gotoLocation ( 'test-manager.php' . getURLAddon ( '?action=editt', array ('action', 'questionid', 'confirmed' ) ) );
						else
							gotoLocation ( 'question-bank.php' . getURLAddon ( '', array ('action', 'questionid', 'confirmed' ) ) );
					}
					break;
				case 'editq' :
					$page_title = $lngstr ['page_title_edit_question'] . $lngstr ['item_separator'] . $page_title;
					if (isset ( $_GET ['questionid'] )) {
						if (isset ( $_POST ['bsubmit'] ) || isset ( $_POST ['bsubmit2'] )) {
							if ($G_SESSION ['access_questionbank'] > 1) {
								include_once ($DOCUMENT_PAGES . "edit_questions-3.inc.php");
							} else {
								if (isset ( $_GET ['testid'] ))
									gotoLocation ( 'test-manager.php' . getURLAddon ( '?action=editt', array ('action', 'questionid' ) ) );
								else
									gotoLocation ( 'question-bank.php' . getURLAddon ( '', array ('action' ) ) );
							}
						} else if (isset ( $_POST ['bcancel'] )) {
							if (isset ( $_GET ['testid'] ))
								gotoLocation ( 'test-manager.php' . getURLAddon ( '?action=editt', array ('action', 'questionid' ) ) );
							else
								gotoLocation ( 'question-bank.php' . getURLAddon ( '', array ('action' ) ) );
						} else {
							include_once ($DOCUMENT_PAGES . "edit_questions-2.inc.php");
						}
					}
					break;
				case 'statsq' :
					$page_title = $lngstr ['page_title_question_stats'] . $lngstr ['item_separator'] . $page_title;
					if (isset ( $_GET ['questionid'] ) || isset ( $_POST ["box_questions"] )) {
						include_once ($DOCUMENT_PAGES . "question-bank-2.inc.php");
					} else {
						gotoLocation ( 'question-bank.php' );
					}
					break;
				default :
					include_once ($DOCUMENT_PAGES . "question-bank-1.inc.php");
			}
		} else {
			include_once ($DOCUMENT_PAGES . "question-bank-1.inc.php");
		}
	} else {
		
		$input_inf_msg = $lngstr ['inf_cant_edit_questions'];
		include_once ($DOCUMENT_PAGES . "home.inc.php");
	}
} else {
	
	$page_title = $lngstr ['page_title_signin'];
	include_once ($DOCUMENT_PAGES . "signin-1.inc.php");
}
?>
