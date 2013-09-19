<?php
initTextEditor($G_SESSION['config_editortype'], array('rtemplate_body'));
require_once($DOCUMENT_INC."top.inc.php");
$f_rtemplateid = (int)readGetVar('rtemplateid');
$i_items = array();

array_push($i_items, array(0 => '<a class=bar2 href="config.php">'.$lngstr['page_header_config'].'</a>', 0));
array_push($i_items, array(0 => '<a class=bar2 href="email-templates.php">'.$lngstr['page_header_emailtemplates'].'</a>', 0));
array_push($i_items, array(0 => '<a class=bar2 href="report-templates.php">'.$lngstr['page_header_rtemplates'].'</a>', 0));
array_push($i_items, array(0 => '<a class=bar2 href="visitors.php">'.$lngstr['page_header_visitors'].'</a>', 0));
writePanel2($i_items);
echo '<h2>'.$lngstr['page_header_rtemplates_edit'].'</h2>';
function getTemplateTag($i_tag) {
	return '["'.$i_tag.'","'.$i_tag.'"]';
}
writeErrorsWarningsBar();
 
$i_rSet1 = $g_db->Execute("SELECT * FROM ".$srv_settings['table_prefix']."rtemplates WHERE rtemplateid=$f_rtemplateid");
if(!$i_rSet1) {
	showDBError(__FILE__, 1);
} else {
	if(!$i_rSet1->EOF) {
 echo '<p><form name=rtemplateForm method=post action="report-templates.php?rtemplateid='.$f_rtemplateid.'&action=edit" onsubmit="return submitForm();">';
echo '<table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">';
$i_rowno = 0;
writeTR2($lngstr['page-rtemplates']['rtemplateid'], $i_rSet1->fields["rtemplateid"]);
writeTR2($lngstr['page-rtemplates']['rtemplatename'], getInputElement('rtemplate_name', $i_rSet1->fields["rtemplate_name"]));
writeTR2($lngstr['page-rtemplates']['rtemplatedescription'], getTextArea('rtemplate_description', $i_rSet1->fields["rtemplate_description"]));
$i_addon = 'rtemplate_bodyEditor.btnCustomTag=true; rtemplate_bodyEditor.arrCustomTag=[';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USERNAME).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_PASSWORD).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_TITLE).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_CITY).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_FIRST_NAME).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_LAST_NAME).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_MIDDLE_NAME).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_EMAIL).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_ADDRESS).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_CITY).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_STATE).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_ZIP).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_COUNTRY).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_PHONE).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_FAX).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_MOBILE).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_PAGER).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_IPPHONE).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_WEBPAGE).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_ICQ).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_MSN).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_AOL).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_GENDER).', ';
 
 $i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_HUSBANDWIFE).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_CHILDREN).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_TRAINER).', ';
 
 $i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_COMPANY).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_CPOSITION).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_DEPARTMENT).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_COFFICE).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_CADDRESS).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_CCITY).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_CSTATE).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_CZIP).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_CCOUNTRY).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_CPHONE).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_CFAX).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_CMOBILE).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_CPAGER).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_CIPPHONE).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_CWEBPAGE).', ';
 
 $i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_USERFIELD1).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_USERFIELD2).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_USERFIELD3).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_USERFIELD4).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_USERFIELD5).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_USERFIELD6).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_USERFIELD7).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_USERFIELD8).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_USER_USERFIELD9).', ';
 
 $i_addon .= getTemplateTag(ETEMPLATE_TAG_TEST_NAME).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_RESULT_DATE).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_RESULT_TIME_SPENT).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_RESULT_TIME_EXCEEDED).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_RESULT_POINTS_SCORED).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_RESULT_POINTS_POSSIBLE).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_RESULT_PERCENTS).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_RESULT_GRADE).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_RESULT_DETAILED_1).', ';
$i_addon .= getTemplateTag(ETEMPLATE_TAG_RESULT_DETAILED_2);
$i_addon .= '];';
writeTR2($lngstr['page-rtemplates']['rtemplatebody'], getTextEditor($G_SESSION['config_editortype'], 'rtemplate_body', $i_rSet1->fields["rtemplate_body"], NULL, NULL, $i_addon));
echo '</table>';
 
 echo '<p class=center><input class=btn type=submit name=bsubmit value=" '.$lngstr['button_update'].' "> <input class=btn type=submit name=bcancel value=" '.$lngstr['button_cancel'].' "></form>';
}
$i_rSet1->Close();
}
require_once($DOCUMENT_INC."btm.inc.php");
?>
