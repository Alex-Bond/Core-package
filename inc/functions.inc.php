<?php

function utf8_to_cp1251($txt) {
    /*
      $in_arr = array(chr(208), chr(192), chr(193), chr(194), chr(195), chr(196), chr
      (197), chr(168), chr(198), chr(199), chr(200), chr(201), chr(202), chr(203), chr
      (204), chr(205), chr(206), chr(207), chr(209), chr(210), chr(211), chr(212), chr
      (213), chr(214), chr(215), chr(216), chr(217), chr(218), chr(219), chr(220), chr
      (221), chr(222), chr(223), chr(224), chr(225), chr(226), chr(227), chr(228), chr
      (229), chr(184), chr(230), chr(231), chr(232), chr(233), chr(234), chr(235), chr
      (236), chr(237), chr(238), chr(239), chr(240), chr(241), chr(242), chr(243), chr
      (244), chr(245), chr(246), chr(247), chr(248), chr(249), chr(250), chr(251), chr
      (252), chr(253), chr(254), chr(255));

      $out_arr = array(chr(208) . chr(160), chr(208) . chr(144), chr(208) . chr(145),
      chr(208) . chr(146), chr(208) . chr(147), chr(208) . chr(148), chr(208) . chr(149),
      chr(208) . chr(129), chr(208) . chr(150), chr(208) . chr(151), chr(208) . chr(152),
      chr(208) . chr(153), chr(208) . chr(154), chr(208) . chr(155), chr(208) . chr(156),
      chr(208) . chr(157), chr(208) . chr(158), chr(208) . chr(159), chr(208) . chr(161),
      chr(208) . chr(162), chr(208) . chr(163), chr(208) . chr(164), chr(208) . chr(165),
      chr(208) . chr(166), chr(208) . chr(167), chr(208) . chr(168), chr(208) . chr(169),
      chr(208) . chr(170), chr(208) . chr(171), chr(208) . chr(172), chr(208) . chr(173),
      chr(208) . chr(174), chr(208) . chr(175), chr(208) . chr(176), chr(208) . chr(177),
      chr(208) . chr(178), chr(208) . chr(179), chr(208) . chr(180), chr(208) . chr(181),
      chr(209) . chr(145), chr(208) . chr(182), chr(208) . chr(183), chr(208) . chr(184),
      chr(208) . chr(185), chr(208) . chr(186), chr(208) . chr(187), chr(208) . chr(188),
      chr(208) . chr(189), chr(208) . chr(190), chr(208) . chr(191), chr(209) . chr(128),
      chr(209) . chr(129), chr(209) . chr(130), chr(209) . chr(131), chr(209) . chr(132),
      chr(209) . chr(133), chr(209) . chr(134), chr(209) . chr(135), chr(209) . chr(136),
      chr(209) . chr(137), chr(209) . chr(138), chr(209) . chr(139), chr(209) . chr(140),
      chr(209) . chr(141), chr(209) . chr(142), chr(209) . chr(143), );

      $txt = str_replace($in_arr, $out_arr, $txt);
     */
    //$txt = iconv ( "UTF-8", "CP1251", $txt );
    return $txt;
}

function readPostVar($i_name, $i_default = '') {
    return isset($_POST [$i_name]) ? $_POST [$i_name] : $i_default;
}

function send_email($i_email_to, $i_email_subject, $i_email_body, $i_email_headers) {
    @mail($i_email_to, $i_email_subject, $i_email_body, $i_email_headers);
}

function readGetVar($i_name) {
    return isset($_GET [$i_name]) ? $_GET [$i_name] : "";
}

function writeErrorsWarningsBar() {
    global $input_err_msg, $input_inf_msg;
    if (isset($input_err_msg) && $input_err_msg)
        echo '<p><b><font color="#cc0000">' . $input_err_msg . '</font></b>';
    if (isset($input_inf_msg) && $input_inf_msg)
        echo '<p><b><font color="#006000">' . $input_inf_msg . '</font></b>';
}

function getConfigItem($i_id) {
    global $g_db, $srv_settings;
    $i_result = null;
    $i_rSet1 = $g_db->SelectLimit("SELECT config_value FROM " . $srv_settings ['table_prefix'] . "config WHERE configid=" . (int) $i_id, 1);
    if ($i_rSet1) {
        if (!$i_rSet1->EOF) {
            $i_result = $i_rSet1->fields ["config_value"];
        }
    }
    return $i_result;
}

function setConfigItem($i_id, $i_value) {
    global $g_db, $srv_settings;
    $i_value = $g_db->qstr((string) $i_value, get_magic_quotes_gpc());
    return $g_db->Execute("UPDATE " . $srv_settings ['table_prefix'] . "config SET config_value=" . $i_value . " WHERE configid=" . (int) $i_id);
}

function showError($i_filename, $i_text) {
    global $g_db;
    exit("Error: " . $i_text . " (" . basename($i_filename) . ")");
}

function showDBError($i_filename, $i_lineno) {
    global $g_db;

    exit("Error: " . basename($i_filename) . ", SQL request error #" . $i_lineno . " " . $g_db->ErrorMsg());
}

function gotoLocation($i_url) {
    session_write_close();
    header('Location: ' . $i_url);
}

function getAutoPassword($i_length) {
    $salt = "abchefghjkmnpqrstuvwxyz0123456789";
    srand((double) microtime() * 1000000);
    $pass = "";
    $i = 0;
    while ($i < $i_length) {
        $num = rand() % 33;
        $tmp = substr($salt, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}

function getRecordCount($i_table, $i_where = '') {
    global $g_db;
    $i_recordcount = 0;
    $i_rSet1 = $g_db->Execute("SELECT COUNT(*) as CROWS FROM " . $i_table . (($i_where != '') ? " WHERE " . $i_where : ''));
    if ($i_rSet1) {
        if (!$i_rSet1->EOF)
            $i_recordcount = (int) $i_rSet1->fields ['CROWS'];
        $i_rSet1->Close();
    }
    return $i_recordcount;
}

function getAutoUsername($i_startfrom) {
    global $g_db;

    $i_duplicates = 0;
    do {
        $i_startfrom++;
        $i_username = sprintf(IGT_USERNAME_TEMPLATE, $i_startfrom);
        $i_duplicates = getRecordCount($srv_settings ['table_prefix'] . 'users', "user_name=" . $g_db->qstr($i_username, get_magic_quotes_gpc()));
    } while ($i_duplicates > 0);
    return $i_username;
}

function convertTextValue($text) {
    return isset($text) ? htmlspecialchars($text) : "";
}

function writeATag($url, $content, $other = "") {
    echo '<a href="' . $url . '"' . $other . '>' . convertTextValue($content) . '</a>';
}

function writeTR2($i_column1, $i_column2) {
    echo getTR(array($i_column1, $i_column2));
}

function writeTR2Fixed($i_column1, $i_column2) {
    echo getTR(array($i_column1, $i_column2), array(' width="30%"', ' width="70%"'));
}

function getTR($i_columns, $i_addonses = array()) {
    global $i_rowno;
    $i_rownotext = ($i_rowno = ++$i_rowno % 2) ? "rowtwo" : "rowone";
    $i_result = '';
    $i_result .= '<tr class=' . $i_rownotext . ' valign=top>';
    foreach ($i_columns as $key => $i_column)
        $i_result .= '<td' . (isset($i_addonses [$key]) ? $i_addonses [$key] : '') . '>' . $i_column . '</td>';
    $i_result .= '</tr>';
    return $i_result;
}

function getSelectElement($i_name, $i_value, $i_values, $i_additional = '') {
    $i_result = "";
    $i_result .= '<select class=inp name="' . $i_name . '" id="' . $i_name . '"' . $i_additional . '>';
    foreach ($i_values as $key => $option)
        $i_result .= '<option value="' . convertTextValue($key) . '"' . ($key == $i_value ? " selected" : "") . '>' . convertTextValue($option) . '</option>';
    $i_result .= '</select>';
    return $i_result;
}

function convertTextAreaHTML($i_state, $i_text) {
    if ($i_state)
        return str_replace("  ", " &nbsp;", str_replace("\r", "", str_replace("\n", "<br />", $i_text)));
    else
        return str_replace("&nbsp;", " ", str_replace("\r", "", str_replace("<br>", "\n", str_replace("<br />", "\n", $i_text))));
}

function initTextEditor($i_type = 1, $i_names = array('oEdit1')) {
    global $srv_settings, $lngstr, $page_meta, $page_body_tag, $DOCUMENT_ROOT;
    switch ($i_type) {
        case CONFIG_CONST_htmlareaeditor :
            $page_meta = '
<script language=JavaScript type="text/javascript">
_editor_url = "' . $srv_settings ['url_root'] . 'editors/htmlarea/";
_editor_lang = "' . $srv_settings ['language'] . '";
</script>
<script language=JavaScript type="text/javascript" src="editors/htmlarea/htmlarea.js"></script>
<script language=JavaScript type="text/javascript">
HTMLArea.loadPlugin("TableOperations");
HTMLArea.loadPlugin("ImageManager");
HTMLArea.loadPlugin("CharacterMap");
function initDocument() {
var editor = new HTMLArea("' . $i_names [0] . '");
editor.config.pageStyle = "@import url(shared/style.css);";
editor.registerPlugin(TableOperations);
editor.registerPlugin(CharacterMap);
editor.generate();
}
</script><script language=JavaScript type="text/javascript">function submitForm() {}</script>';
            $page_body_tag = ' onload="HTMLArea.init(); HTMLArea.onload = initDocument"';
            break;
        case CONFIG_CONST_iseditor :
            $page_meta = '';
            switch ($srv_settings ['language']) {
                case 'de' :
                    $page_meta .= '<script language=JavaScript type="text/javascript" src="' . $srv_settings ['url_root'] . 'editors/iseditor/scripts/language/german/editor_lang.js"></script>';
                    break;
                case 'nl' :
                    $page_meta .= '<script language=JavaScript type="text/javascript" src="' . $srv_settings ['url_root'] . 'editors/iseditor/scripts/language/dutch/editor_lang.js"></script>';
                    break;
                case 'ru' :
                    $page_meta .= '<script language=JavaScript type="text/javascript" src="' . $srv_settings ['url_root'] . 'editors/iseditor/scripts/language/russian/editor_lang.js"></script>';
                    break;
            }
            $page_meta .= '<script language=JavaScript type="text/javascript" src="' . $srv_settings ['url_root'] . 'editors/iseditor/scripts/' . (strpos($_SERVER ['HTTP_USER_AGENT'], 'MSIE') ? '' : 'moz/') . 'editor.js"></script><script language=JavaScript type="text/javascript">function submitForm() {document.getElementById("' . $i_names [0] . '").value = ' . $i_names [0] . 'Editor.getHTMLBody();}</script>';
            break;
        case CONFIG_CONST_iseditor2 :
            $page_meta = '';
            switch ($srv_settings ['language']) {
                case 'de' :
                    $page_meta .= '<script language=JavaScript type="text/javascript" src="' . $srv_settings ['url_root'] . 'editors/iseditor2/scripts/language/german/editor_lang.js"></script>';
                    break;
                case 'nl' :
                    $page_meta .= '<script language=JavaScript type="text/javascript" src="' . $srv_settings ['url_root'] . 'editors/iseditor2/scripts/language/dutch/editor_lang.js"></script>';
                    break;
                case 'ru' :
                    $page_meta .= '<script language=JavaScript type="text/javascript" src="' . $srv_settings ['url_root'] . 'editors/iseditor2/scripts/language/russian/editor_lang.js"></script>';
                    break;
            }
            $page_meta .= '<script language="Javascript" src="editors/iseditor2/scripts/language/german/editor_lang.js"></script>
            <script language=JavaScript type="text/javascript" src="' . $srv_settings ['url_root'] . 'editors/iseditor2/scripts/innovaeditor.js"></script><script language=JavaScript type="text/javascript">function submitForm() {}</script>';
            break;

        case CONFIG_CONST_ckeditor :
            break;
    }
}

function getJSComplaintText($i_text) {
    return str_replace('"', '\"', str_replace("\r", "", str_replace("\n", " ", $i_text)));
}

function getTextEditor($i_type, $i_name, $i_value, $i_rows = 15, $i_cols = 50, $i_addon = '') {
    global $srv_settings, $lngstr;
    switch ($i_type) {
        case 0 :
            $i_value = convertTextAreaHTML(false, $i_value);
            return '<textarea name=' . $i_name . ' cols=' . $i_cols . ' rows=' . $i_rows . ' style="width: 99%;">' . convertTextValue($i_value) . '</textarea>';
        case CONFIG_CONST_htmlareaeditor :
            return '<textarea name=' . $i_name . ' cols=' . $i_cols . ' rows=' . $i_rows . ' id=' . $i_name . ' style="height: 25em; width: 85%;">' . convertTextValue($i_value) . '</textarea>';
            break;
        case CONFIG_CONST_iseditor :
            return '<script language=JavaScript type="text/javascript">var ' . $i_name . 'Editor = new InnovaEditor("' . $i_name . 'Editor"); ' . $i_name . 'Editor.width="100%"; ' . $i_name . 'Editor.cmdAssetManager="modalDialogShow(\'' . $srv_settings ['url_root'] . 'editors/iseditor/assetmanager/assetmanager.php?lang=' . $lngstr ['language_long'] . '\', 640, 465)"; ' . $i_name . 'Editor.btnFlash=true; ' . $i_name . 'Editor.btnMedia=true; ' . $i_name . 'Editor.btnStyles=true; ' . $i_name . 'Editor.css="shared/style_editor.css"; ' . $i_name . 'Editor.btnStrikethrough=true; ' . $i_name . 'Editor.btnSuperscript=true; ' . $i_name . 'Editor.btnSubscript=true; ' . $i_name . 'Editor.btnLTR=true; ' . $i_name . 'Editor.btnRTL=true; ' . $i_name . 'Editor.btnClearAll=true; ' . $i_name . 'Editor.btnCustomTag=true; ' . $i_addon . ' ' . $i_name . 'Editor.RENDER("' . getJSComplaintText($i_value) . '");</script><input type="hidden" name="' . $i_name . '" id="' . $i_name . '">';
            break;
        case CONFIG_CONST_iseditor2 :
            return '<textarea name=' . $i_name . ' cols=' . $i_cols . ' rows=' . $i_rows . ' id=' . $i_name . '>' . convertTextValue($i_value) . '</textarea><script language=JavaScript type="text/javascript">var ' . $i_name . 'Editor = new InnovaEditor("' . $i_name . 'Editor"); ' . $i_name . 'Editor.width="100%"; ' . $i_name . 'Editor.cmdAssetManager="modalDialogShow(\'' . $srv_settings ['url_root'] . 'editors/iseditor2/assetmanager/assetmanager.php?lang=' . $lngstr ['language_long'] . '\', 640, 465)"; ' . $i_name . 'Editor.btnFlash=true; ' . $i_name . 'Editor.btnMedia=true; ' . $i_name . 'Editor.btnStyles=true; ' . $i_name . 'Editor.css="shared/style.css"; ' . $i_name . 'Editor.btnStrikethrough=true; ' . $i_name . 'Editor.btnSuperscript=true; ' . $i_name . 'Editor.btnSubscript=true; ' . $i_name . 'Editor.btnLTR=true; ' . $i_name . 'Editor.btnRTL=true; ' . $i_name . 'Editor.btnClearAll=true; ' . $i_name . 'Editor.btnPasteText=true; ' . $i_addon . ' ' . $i_name . 'Editor.REPLACE("' . $i_name . '");</script>';
            break;
        case CONFIG_CONST_ckeditor :
            include_once $DOCUMENT_ROOT . "editors/ckeditor/ckeditor.php";
            include_once $DOCUMENT_ROOT . "editors/ckfinder/ckfinder.php";
            // The initial value to be displayed in the editor.
            $initialValue = $i_value;
            // Create class instance.
            $CKEditor = new CKEditor ();
            $CKEditor->returnOutput = true;
            // Path to CKEditor directory, ideally instead of relative dir, use an absolute path:
            //   $CKEditor->basePath = '/ckeditor/'
            // If not set, CKEditor will try to detect the correct path.
            $CKEditor->config['language'] = 'uk';
            $CKEditor->basePath = '/editors/ckeditor/';
            $ckfinder = new CKFinder ();
            $ckfinder->BasePath = '/editors/ckfinder/'; // Note: BasePath property in CKFinder class starts with capital letter
            $ckfinder->SetupCKEditorObject($CKEditor);

            // Create textarea element and attach CKEditor to it.
            return $CKEditor->editor($i_name, $initialValue);
            break;
        case CONFIG_CONST_ckeditor_question:
            include_once $DOCUMENT_ROOT . "editors/ckeditor/ckeditor.php";
            include_once $DOCUMENT_ROOT . "editors/ckfinder/ckfinder.php";
            // The initial value to be displayed in the editor.
            $initialValue = $i_value;
            // Create class instance.
            $CKEditor = new CKEditor ();
            $CKEditor->returnOutput = true;
            //$CKEditor->config['skin'] = 'v2';
            $CKEditor->config['removePlugins'] = 'elementspath';
            $CKEditor->config['language'] = 'uk';
            $CKEditor->config ['toolbar'] =
                    array(
                        array('Source'),
                        array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'),
                        array('SelectAll', 'RemoveFormat', 'Maximize', 'ShowBlocks'),
                        '/',
                        array('Font', 'FontSize'),
                        array('Bold', 'Italic', 'Underline', 'Subscript', 'Superscript' ),
                        array('TextColor', 'BGColor'),
                        array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'),
                        array('Image', 'Flash', 'SpecialChar'),
                        
            );
            $CKEditor->basePath = '/editors/ckeditor/';
            $ckfinder = new CKFinder ();
            $ckfinder->BasePath = '/editors/ckfinder/'; // Note: BasePath property in CKFinder class starts with capital letter
            $ckfinder->SetupCKEditorObject($CKEditor);

            // Create textarea element and attach CKEditor to it.
            return $CKEditor->editor($i_name, $initialValue);
    }
}

function getTextArea($i_name, $i_value, $hint = '', $i_rows = 5, $i_cols = 50) {
    return '<textarea name=' . $i_name . ' cols=' . $i_cols . ' rows=' . $i_rows . ' title="' . convertTextValue($hint) . '">' . convertTextValue($i_value) . '</textarea>';
}

function getPasswordBox($i_name, $i_value, $size = 50, $maxlength = 0) {
    return '<input name="' . $i_name . '" value="' . convertTextValue($i_value) . '" class=inp type=password size=' . $size . (!empty($maxlength) ? " maxlength=$maxlength" : "") . '>';
}

function getHiddenElement($i_name, $i_value, $size = 50, $maxlength = 0, $i_additional = '') {
    return getInputElement($i_name, $i_value, $size, $maxlength, $i_additional, $i_type = 'hidden');
}

function getInputElement($i_name, $i_value, $size = 50, $maxlength = 0, $i_additional = '', $i_type = 'text') {
    return '<input name="' . $i_name . '" value="' . convertTextValue(@$i_value) . '" class=inp type=' . $i_type . ' size=' . $size . ($maxlength > 0 ? " maxlength=$maxlength" : "") . $i_additional . '>';
}

function getCheckbox($i_name, $i_value, $text, $i_additional = '') {
    return '<input name="' . $i_name . '" type=checkbox' . ($i_value ? ' checked=checked' : '') . $i_additional . '>' . $text;
}

function getOption($i_name, $i_value, $checked, $text, $i_additional = '') {
    return '<input name="' . $i_name . '" value="' . convertTextValue(@$i_value) . '" type=radio ' . ($checked ? ' checked=checked' : '') . $i_additional . '>' . $text;
}

function writeInputElement($i_name, $i_value, $size = 50, $maxlength = 0, $i_additional = '') {
    echo getInputElement($i_name, $i_value, $size, $maxlength, $i_additional);
}

function getBar($i_content) {
    return '<table cellpadding=0 cellspacing=0 border=0 width="100%" style="border-left: 1px solid #E0E7F6; border-top: 1px solid #E0E7F6; border-right: 1px solid #CFD6E3; border-bottom: 1px solid #CFD6E3;"><tr><td colspan=3 height=1 style="background: #ffffff"><img src="images/1x1.gif" width=1 height=1></td></tr><tr style="background: url(images/bar-1-background.gif) repeat-x;"><td width=1 style="background: #ffffff"><img src="images/1x1.gif" width=1 height=32></td><td align=center>' . $i_content . '</td><td width=1 style="background: #ffffff"><img src="images/1x1.gif" width=1 height=32></td></tr><tr><td colspan=3 height=1 style="background: #ffffff"><img src="images/1x1.gif" width=1 height=1></td></tr></table>';
}

function getBarSmall($i_state, $i_content) {
    return '<table cellpadding=0 cellspacing=0 border=0 width="100%" style="border-left: 1px solid #E0E7F6; border-top: 1px solid #E0E7F6; border-right: 1px solid #CFD6E3;"><tr><td colspan=3 height=1 style="background: #ffffff"><img src="images/1x1.gif" width=1 height=1></td></tr><tr style="background: url(images/barsmall-' . $i_state . '-background.gif) repeat-x;"><td width=1 style="background: #ffffff"><img src="images/1x1.gif" width=1 height=24></td><td align=center>' . $i_content . '</td><td width=1 style="background: #ffffff"><img src="images/1x1.gif" width=1 height=24></td></tr><tr><td colspan=3 height=1 style="background: #ffffff"><img src="images/1x1.gif" width=1 height=1></td></tr></table>';
}

function writePanel2($i_items) {
    global $lngstr;
    echo '<p><table cellpadding=0 cellspacing=0 border=0 width="100%"><tr><td>';
    echo '<table cellpadding=0 cellspacing=0 border=0>';
    echo '<tr><td width=3><img src="images/1x1.gif" width=3 height=1></td>';
    reset($i_items);
    if (list (, $i_item ) = each($i_items))
        echo '<td>' . getBarSmall($i_item [1], '&nbsp;&nbsp;<b>' . $i_item [0] . '</b>&nbsp;&nbsp;') . '</td>';
    while (list (, $i_item ) = each($i_items)) {
        echo '<td width=5><img src="images/1x1.gif" width=5 height=1></td>';

        echo '<td>' . getBarSmall($i_item [1], '&nbsp;&nbsp;<b>' . $i_item [0] . '</b>&nbsp;&nbsp;') . '</td>';
    }
    echo '<td width=3><img src="images/1x1.gif" width=3 height=1></td></tr>';

    echo '</table>';
    echo '</td><td width=24 align=right><a href="javascript:ShowInfoBar(true);" title="' . $lngstr ['tooltip_showbar'] . '"><img src="images/dialog-info-small.gif" width=24 height=24 border=0></a></td><td width=3><img src="images/1x1.gif" width=3 height=1></td></tr></table>';
    echo '<table cellpadding=0 cellspacing=0 border=0 width="100%" style="background: #CFD6E3;"><tr><td height=1><img src="images/1x1.gif" width=1 height=1></tr></table>';
}

function writeInfoBar($i_content) {
    global $_COOKIES, $lngstr;
    $i_isvisible = (!isset($_COOKIE ["showinfobar"]) || $_COOKIE ["showinfobar"] != "N");
    if ($i_content) {
        echo '<div id="infobar" style="display: ' . ($i_isvisible ? 'block' : 'none') . ';"><table cellpadding=0 cellspacing=0 border=0 width="97%" align=center style="border: 1px solid #8097D1; background-color: #F3F6FF;">';
        echo '<tr><td width=32 vAlign=top style="padding: 5px;"><img src="images/dialog-info.gif" width=32 height=32></td><td width="100%" vAlign=middle style="padding: 5px;">';
        echo $i_content;
        echo '</td><td width=32 vAlign=top style="padding: 0px;"><a href="javascript:ShowInfoBar(false);" title="' . $lngstr ['tooltip_closebar'] . '"><img src="images/button-close-small.gif" width=12 height=12 border=0></a></td></tr></table></div>';
    }
}

function writeQryTableHeaders($i_url, $i_tablefields, $i_order_no, $i_direction, $i_orderid = '') {
    foreach ($i_tablefields as $i_fieldno => $i_field) {
        $i_content = $i_field [0];
        if ($i_field [2]) {
            $i_content = '<a class=rowhdr href="' . $i_url . '&order' . $i_orderid . '=' . $i_fieldno . '&direction' . $i_orderid . '=' . (($i_order_no == $i_fieldno && !$i_direction) ? "DESC" : "") . '">' . $i_content . '</a><br>';
            if ($i_order_no == $i_fieldno && !$i_direction)
                $i_content .= '<img src="images/button-order-asc.gif">';
            else
                $i_content .= '<a href="' . $i_url . '&order' . $i_orderid . '=' . $i_fieldno . '&direction' . $i_orderid . '="><img src="images/button-order-asc-inactive.gif" border=0></a>';
            if ($i_order_no == $i_fieldno && $i_direction)
                $i_content .= '<img src="images/button-order-desc.gif">';
            else
                $i_content .= '<a href="' . $i_url . '&order' . $i_orderid . '=' . $i_fieldno . '&direction' . $i_orderid . '=DESC"><img src="images/button-order-desc-inactive.gif" border=0></a>';
        }
        $i_content = '<td class=rowhdr1 vAlign=top title="' . $i_field [1] . '">' . $i_content . '</td>';
        echo $i_content;
    }
}

function unregisterTestData() {
    global $G_SESSION;

    unset($G_SESSION ['testid']);
    unset($G_SESSION ['resultid']);
    unset($G_SESSION ['yt_name']);
    unset($G_SESSION ['yt_result_etemplateid']);
    unset($G_SESSION ['yt_result_email']);
    unset($G_SESSION ['yt_result_emailtouser']);
    unset($G_SESSION ['yt_teststart']);
    unset($G_SESSION ['yt_testtime']);
    unset($G_SESSION ['yt_timeforceout']);
    unset($G_SESSION ['yt_attempts']);
    unset($G_SESSION ['yt_pointsmax']);
    unset($G_SESSION ['yt_teststop']);
    unset($G_SESSION ['yt_questionstart']);
    unset($G_SESSION ['yt_questioncount']);
    unset($G_SESSION ['yt_questions']);
    unset($G_SESSION ['yt_questionids']);
    unset($G_SESSION ['yt_answers']);
    unset($G_SESSION ['yt_shufflea']);
    unset($G_SESSION ['yt_test_qsperpage']);
    unset($G_SESSION ['yt_test_showqfeedback']);
    unset($G_SESSION ['yt_result_showanswers']);
    unset($G_SESSION ['yt_result_showpoints']);
    unset($G_SESSION ['yt_result_showgrade']);
    unset($G_SESSION ['yt_result_showpdf']);
    unset($G_SESSION ['yt_reportgradecondition']);
    unset($G_SESSION ['yt_gscaleid']);
    unset($G_SESSION ['yt_questionno']);
    unset($G_SESSION ['yt_got_answers']);
    unset($G_SESSION ['yt_got_points']);
    unset($G_SESSION ['yt_points_pending']);
    unset($G_SESSION ['yt_state']);
    unset($G_SESSION ['questionid']);
    unset($G_SESSION ['yt_teststoppedat']);
}

function getShuffledArray($i_array, $i_count = 0) {
    srand((float) microtime() * 10000000);
    shuffle($i_array);
    return $i_array;
}

function showTestQuestion($i_questionno, $i_questionid) {
    global $g_db, $G_SESSION, $lngstr, $srv_settings;

    $i_rSet2 = $g_db->Execute("SELECT * FROM " . $srv_settings ['table_prefix'] . "questions WHERE questionid=" . $i_questionid);
    if (!$i_rSet2) {
        showDBError('showTestQuestion', 1);
    } else {
        if (!$i_rSet2->EOF) {
            writeQuestion($i_rSet2->fields ["question_text"]);
            $i_question_type = $i_rSet2->fields ["question_type"];
        } else {
            printf($lngstr ['err_no_question_id_in_db'], $i_questionid);
            return false;
        }
        $i_rSet2->Close();
    }

    $i_answer_text = array();
    $i_answer_feedback = array();
    $i_answercount = 0;
    $i_rSet3 = $g_db->Execute("SELECT answer_text, answer_feedback FROM " . $srv_settings ['table_prefix'] . "answers WHERE questionid=" . $i_questionid . " ORDER BY answerid");
    if (!$i_rSet3) {
        showDBError('showTestQuestion', 2);
    } else {

        while (!$i_rSet3->EOF) {
            $i_answercount++;
            $i_answer_text [$i_answercount] = $i_rSet3->fields ["answer_text"];
            $i_answer_feedback [$i_answercount] = $i_rSet3->fields ["answer_feedback"];
            $i_rSet3->MoveNext();
        }
        $i_rSet3->Close();
        switch ($i_question_type) {
            case QUESTION_TYPE_MULTIPLECHOICE :
            case QUESTION_TYPE_TRUEFALSE :
            case QUESTION_TYPE_MULTIPLEANSWER :
                if ($i_answercount) {

                    if (!isset($G_SESSION ["yt_answers"] [$i_questionno])) {
                        $i_answer_order = array();
                        for ($i = 0; $i < $i_answercount; $i++)
                            $i_answer_order [$i] = $i + 1;

                        if ($i_answercount > 1 && ($i_question_type != QUESTION_TYPE_TRUEFALSE) && $G_SESSION ["yt_shufflea"])
                            $i_answer_order = getShuffledArray($i_answer_order);
                        $G_SESSION ['yt_answers'] [$i_questionno] = $i_answer_order;
                    }

                    foreach ($G_SESSION ["yt_answers"] [$i_questionno] as $i_id => $i_no)
                        writeAnswer($i_questionno, $i_id + 1, $i_question_type, $i_answer_text [$i_no], $i_answer_feedback [$i_no], @$_POST ['answer'] [$i_questionno]);
                } else {

                    echo sprintf($lngstr ['err_no_answers_in_question'], $i_questionid);
                    return false;
                }
                break;
            case QUESTION_TYPE_FILLINTHEBLANK :
            case QUESTION_TYPE_ESSAY :
                writeAnswer($i_questionno, 1, $i_question_type, '', '', @$_POST ['answer'] [$i_questionno]);
                break;
        }
    }
    return true;
}

function addNewUser($i_username = '', $i_password = '', $i_userdata = array(), $i_groupids = array()) {
    global $g_db, $srv_settings;
    $i_email = isset($i_userdata ['email']) ? $i_userdata ['email'] : '';
    $i_title = isset($i_userdata ['title']) ? $i_userdata ['title'] : '';
    $i_firstname = isset($i_userdata ['firstname']) ? $i_userdata ['firstname'] : '';
    $i_lastname = isset($i_userdata ['lastname']) ? $i_userdata ['lastname'] : '';
    $i_middlename = isset($i_userdata ['middlename']) ? $i_userdata ['middlename'] : '';
    $i_address = isset($i_userdata ['address']) ? $i_userdata ['address'] : '';
    $i_city = isset($i_userdata ['city']) ? $i_userdata ['city'] : '';
    $i_state = isset($i_userdata ['state']) ? $i_userdata ['state'] : '';
    $i_zip = isset($i_userdata ['zip']) ? $i_userdata ['zip'] : '';
    $i_country = isset($i_userdata ['country']) ? $i_userdata ['country'] : '';
    $i_phone = isset($i_userdata ['phone']) ? $i_userdata ['phone'] : '';
    $i_fax = isset($i_userdata ['fax']) ? $i_userdata ['fax'] : '';
    $i_mobile = isset($i_userdata ['mobile']) ? $i_userdata ['mobile'] : '';
    $i_pager = isset($i_userdata ['pager']) ? $i_userdata ['pager'] : '';
    $i_ipphone = isset($i_userdata ['ipphone']) ? $i_userdata ['ipphone'] : '';
    $i_webpage = isset($i_userdata ['webpage']) ? $i_userdata ['webpage'] : '';
    $i_icq = isset($i_userdata ['icq']) ? $i_userdata ['icq'] : '';
    $i_msn = isset($i_userdata ['msn']) ? $i_userdata ['msn'] : '';
    $i_aol = isset($i_userdata ['aol']) ? $i_userdata ['aol'] : '';
    $i_gender = isset($i_userdata ['gender']) ? $i_userdata ['gender'] : '';
    $i_birthday = isset($i_userdata ['birthday']) ? $i_userdata ['birthday'] : '';
    $i_husbandwife = isset($i_userdata ['husbandwife']) ? $i_userdata ['husbandwife'] : '';
    $i_children = isset($i_userdata ['children']) ? $i_userdata ['children'] : '';
    $i_trainer = isset($i_userdata ['trainer']) ? $i_userdata ['trainer'] : '';
    $i_photo = isset($i_userdata ['photo']) ? $i_userdata ['photo'] : '';
    $i_company = isset($i_userdata ['company']) ? $i_userdata ['company'] : '';
    $i_cposition = isset($i_userdata ['cposition']) ? $i_userdata ['cposition'] : '';
    $i_department = isset($i_userdata ['department']) ? $i_userdata ['department'] : '';
    $i_coffice = isset($i_userdata ['coffice']) ? $i_userdata ['coffice'] : '';
    $i_caddress = isset($i_userdata ['caddress']) ? $i_userdata ['caddress'] : '';
    $i_ccity = isset($i_userdata ['ccity']) ? $i_userdata ['ccity'] : '';
    $i_cstate = isset($i_userdata ['cstate']) ? $i_userdata ['cstate'] : '';
    $i_czip = isset($i_userdata ['czip']) ? $i_userdata ['czip'] : '';
    $i_ccountry = isset($i_userdata ['ccountry']) ? $i_userdata ['ccountry'] : '';
    $i_cphone = isset($i_userdata ['cphone']) ? $i_userdata ['cphone'] : '';
    $i_cfax = isset($i_userdata ['cfax']) ? $i_userdata ['cfax'] : '';
    $i_cmobile = isset($i_userdata ['cmobile']) ? $i_userdata ['cmobile'] : '';
    $i_cpager = isset($i_userdata ['cpager']) ? $i_userdata ['cpager'] : '';
    $i_cipphone = isset($i_userdata ['cipphone']) ? $i_userdata ['cipphone'] : '';
    $i_cwebpage = isset($i_userdata ['cwebpage']) ? $i_userdata ['cwebpage'] : '';
    $i_cphoto = isset($i_userdata ['cphoto']) ? $i_userdata ['cphoto'] : '';
    $i_ufield1 = isset($i_userdata ['ufield1']) ? $i_userdata ['ufield1'] : '';
    $i_ufield2 = isset($i_userdata ['ufield2']) ? $i_userdata ['ufield2'] : '';
    $i_ufield3 = isset($i_userdata ['ufield3']) ? $i_userdata ['ufield3'] : '';
    $i_ufield4 = isset($i_userdata ['ufield4']) ? $i_userdata ['ufield4'] : '';
    $i_ufield5 = isset($i_userdata ['ufield5']) ? $i_userdata ['ufield5'] : '';
    $i_ufield6 = isset($i_userdata ['ufield6']) ? $i_userdata ['ufield6'] : '';
    $i_ufield7 = isset($i_userdata ['ufield7']) ? $i_userdata ['ufield7'] : '';
    $i_ufield8 = isset($i_userdata ['ufield8']) ? $i_userdata ['ufield8'] : '';
    $i_ufield9 = isset($i_userdata ['ufield9']) ? $i_userdata ['ufield9'] : '';
    $i_ufield10 = isset($i_userdata ['ufield10']) ? $i_userdata ['ufield10'] : '';
    $i_userid = 0;
    $i_pass_hash = md5($i_password);

    $qry_str = "INSERT INTO " . $srv_settings ['table_prefix'] . "users(user_name, user_passhash, user_email, user_title, user_firstname, user_lastname, user_middlename, user_address, user_city, user_state, user_zip, user_country, user_phone, user_fax, user_mobile, user_pager, user_ipphone, user_webpage, user_icq, user_msn, user_aol, user_gender, user_birthday, user_husbandwife, user_children, user_trainer, user_photo, user_company, user_cposition, user_department, user_coffice, user_caddress, user_ccity, user_cstate, user_czip, user_ccountry, user_cphone, user_cfax, user_cmobile, user_cpager, user_cipphone, user_cwebpage, user_cphoto, user_ufield1, user_ufield2, user_ufield3, user_ufield4, user_ufield5, user_ufield6, user_ufield7, user_ufield8, user_ufield9, user_ufield10, user_joindate, user_logindate, user_enabled) VALUES(";
    $qry_str .= $g_db->qstr($i_username, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_pass_hash, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_email, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_title, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_firstname, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_lastname, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_middlename, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_address, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_city, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_state, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_zip, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_country, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_phone, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_fax, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_mobile, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_pager, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_ipphone, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_webpage, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_icq, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_msn, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_aol, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_gender, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_birthday, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_husbandwife, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_children, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_trainer, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_photo, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_company, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_cposition, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_department, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_coffice, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_caddress, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_ccity, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_cstate, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_czip, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_ccountry, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_cphone, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_cfax, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_cmobile, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_cpager, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_cipphone, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_cwebpage, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_cphoto, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_ufield1, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_ufield2, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_ufield3, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_ufield4, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_ufield5, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_ufield6, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_ufield7, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_ufield8, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_ufield9, get_magic_quotes_gpc()) . ",";
    $qry_str .= $g_db->qstr($i_ufield10, get_magic_quotes_gpc()) . ",";
    $qry_str .= time() . ",";
    $qry_str .= "0,";
    $qry_str .= "1)";
    if ($g_db->Execute($qry_str) === false)
        showDBError(__file__, 1);
    $i_userid = (int) $g_db->Insert_ID();

    manageUserGroups(array($i_userid), $i_groupids, true);
    return $i_userid;
}

function emailNewUserDetails($i_username = '', $i_password = '', $i_userdata = array()) {
    global $g_db, $srv_settings;
    $i_email = isset($i_userdata ['email']) ? $i_userdata ['email'] : '';
    $i_title = isset($i_userdata ['title']) ? $i_userdata ['title'] : '';
    $i_firstname = isset($i_userdata ['firstname']) ? $i_userdata ['firstname'] : '';
    $i_lastname = isset($i_userdata ['lastname']) ? $i_userdata ['lastname'] : '';
    $i_middlename = isset($i_userdata ['middlename']) ? $i_userdata ['middlename'] : '';
    $i_address = isset($i_userdata ['address']) ? $i_userdata ['address'] : '';
    $i_city = isset($i_userdata ['city']) ? $i_userdata ['city'] : '';
    $i_state = isset($i_userdata ['state']) ? $i_userdata ['state'] : '';
    $i_zip = isset($i_userdata ['zip']) ? $i_userdata ['zip'] : '';
    $i_country = isset($i_userdata ['country']) ? $i_userdata ['country'] : '';
    $i_phone = isset($i_userdata ['phone']) ? $i_userdata ['phone'] : '';
    $i_fax = isset($i_userdata ['fax']) ? $i_userdata ['fax'] : '';
    $i_mobile = isset($i_userdata ['mobile']) ? $i_userdata ['mobile'] : '';
    $i_pager = isset($i_userdata ['pager']) ? $i_userdata ['pager'] : '';
    $i_ipphone = isset($i_userdata ['ipphone']) ? $i_userdata ['ipphone'] : '';
    $i_webpage = isset($i_userdata ['webpage']) ? $i_userdata ['webpage'] : '';
    $i_icq = isset($i_userdata ['icq']) ? $i_userdata ['icq'] : '';
    $i_msn = isset($i_userdata ['msn']) ? $i_userdata ['msn'] : '';
    $i_aol = isset($i_userdata ['aol']) ? $i_userdata ['aol'] : '';
    $i_gender = isset($i_userdata ['gender']) ? $i_userdata ['gender'] : '';
    $i_birthday = isset($i_userdata ['birthday']) ? $i_userdata ['birthday'] : '';
    $i_husbandwife = isset($i_userdata ['husbandwife']) ? $i_userdata ['husbandwife'] : '';
    $i_children = isset($i_userdata ['children']) ? $i_userdata ['children'] : '';
    $i_trainer = isset($i_userdata ['trainer']) ? $i_userdata ['trainer'] : '';
    $i_photo = isset($i_userdata ['photo']) ? $i_userdata ['photo'] : '';
    $i_company = isset($i_userdata ['company']) ? $i_userdata ['company'] : '';
    $i_cposition = isset($i_userdata ['cposition']) ? $i_userdata ['cposition'] : '';
    $i_department = isset($i_userdata ['department']) ? $i_userdata ['department'] : '';
    $i_coffice = isset($i_userdata ['coffice']) ? $i_userdata ['coffice'] : '';
    $i_caddress = isset($i_userdata ['caddress']) ? $i_userdata ['caddress'] : '';
    $i_ccity = isset($i_userdata ['ccity']) ? $i_userdata ['ccity'] : '';
    $i_cstate = isset($i_userdata ['cstate']) ? $i_userdata ['cstate'] : '';
    $i_czip = isset($i_userdata ['czip']) ? $i_userdata ['czip'] : '';
    $i_ccountry = isset($i_userdata ['ccountry']) ? $i_userdata ['ccountry'] : '';
    $i_cphone = isset($i_userdata ['cphone']) ? $i_userdata ['cphone'] : '';
    $i_cfax = isset($i_userdata ['cfax']) ? $i_userdata ['cfax'] : '';
    $i_cmobile = isset($i_userdata ['cmobile']) ? $i_userdata ['cmobile'] : '';
    $i_cpager = isset($i_userdata ['cpager']) ? $i_userdata ['cpager'] : '';
    $i_cipphone = isset($i_userdata ['cipphone']) ? $i_userdata ['cipphone'] : '';
    $i_cwebpage = isset($i_userdata ['cwebpage']) ? $i_userdata ['cwebpage'] : '';
    $i_cphoto = isset($i_userdata ['cphoto']) ? $i_userdata ['cphoto'] : '';
    $i_ufield1 = isset($i_userdata ['ufield1']) ? $i_userdata ['ufield1'] : '';
    $i_ufield2 = isset($i_userdata ['ufield2']) ? $i_userdata ['ufield2'] : '';
    $i_ufield3 = isset($i_userdata ['ufield3']) ? $i_userdata ['ufield3'] : '';
    $i_ufield4 = isset($i_userdata ['ufield4']) ? $i_userdata ['ufield4'] : '';
    $i_ufield5 = isset($i_userdata ['ufield5']) ? $i_userdata ['ufield5'] : '';
    $i_ufield6 = isset($i_userdata ['ufield6']) ? $i_userdata ['ufield6'] : '';
    $i_ufield7 = isset($i_userdata ['ufield7']) ? $i_userdata ['ufield7'] : '';
    $i_ufield8 = isset($i_userdata ['ufield8']) ? $i_userdata ['ufield8'] : '';
    $i_ufield9 = isset($i_userdata ['ufield9']) ? $i_userdata ['ufield9'] : '';
    $i_ufield10 = isset($i_userdata ['ufield10']) ? $i_userdata ['ufield10'] : '';
    $i_isok = true;

    $i_isok = $i_isok && ($i_rSet3 = $g_db->SelectLimit("SELECT etemplate_from, etemplate_subject, etemplate_body FROM " . $srv_settings ['table_prefix'] . "etemplates WHERE etemplateid=" . SYSTEM_ETEMPLATES_REGISTRATION_INDEX, 1));
    if ($i_isok)
        $i_isok = $i_isok && (!$i_rSet3->EOF);

    if ($i_isok) {
        $i_email_headers = "From: " . $i_rSet3->fields ["etemplate_from"] . "\nReply-To: " . $i_rSet3->fields ["etemplate_from"] . "\n" . IGT_EMAIL_AGENT_NAME . "\nX-Priority: 3 (Normal)";
        $i_email_subject = $i_rSet3->fields ["etemplate_subject"];
        $i_email_body = $i_rSet3->fields ["etemplate_body"];
        if ($i_email_body) {
            $i_email_body = str_replace(ETEMPLATE_TAG_USERNAME, $i_username, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_PASSWORD, $i_password, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_TITLE, $i_title, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_FIRST_NAME, $i_firstname, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_LAST_NAME, $i_lastname, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_MIDDLE_NAME, $i_middlename, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_EMAIL, $i_email, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_ADDRESS, $i_address, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CITY, $i_city, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_STATE, $i_state, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_ZIP, $i_zip, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_COUNTRY, $i_country, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_PHONE, $i_phone, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_FAX, $i_fax, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_MOBILE, $i_mobile, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_PAGER, $i_pager, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_IPPHONE, $i_ipphone, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_WEBPAGE, $i_webpage, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_ICQ, $i_icq, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_MSN, $i_msn, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_AOL, $i_aol, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_GENDER, $i_gender, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_BIRTHDAY, $i_birthday, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_HUSBANDWIFE, $i_husbandwife, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CHILDREN, $i_children, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_TRAINER, $i_trainer, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_PHOTO, $i_photo, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_COMPANY, $i_company, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CPOSITION, $i_cposition, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_DEPARTMENT, $i_department, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_COFFICE, $i_coffice, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CADDRESS, $i_caddress, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CCITY, $i_ccity, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CSTATE, $i_cstate, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CZIP, $i_czip, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CCOUNTRY, $i_ccountry, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CPHONE, $i_cphone, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CFAX, $i_cfax, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CMOBILE, $i_cmobile, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CPAGER, $i_cpager, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CIPPHONE, $i_cipphone, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CWEBPAGE, $i_cwebpage, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_CPHOTO, $i_cphoto, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD1, $i_ufield1, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD2, $i_ufield2, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD3, $i_ufield3, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD4, $i_ufield4, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD5, $i_ufield5, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD6, $i_ufield6, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD7, $i_ufield7, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD8, $i_ufield8, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD9, $i_ufield9, $i_email_body);
            $i_email_body = str_replace(ETEMPLATE_TAG_USER_USERFIELD10, $i_ufield10, $i_email_body);
            send_email($i_email, $i_email_subject, $i_email_body, $i_email_headers);
        }
    }
}

function manageUserGroups($i_userids = array(), $i_groupids = array(), $i_addtogroup = true) {
    global $g_db, $G_SESSION, $srv_settings;

    foreach ($i_userids as $i_userid) {
        foreach ($i_groupids as $i_groupid) {
            if ($i_addtogroup) {
                $g_db->Execute("INSERT INTO " . $srv_settings ['table_prefix'] . "groups_users (groupid, userid) VALUES (" . $i_groupid . ", " . $i_userid . ")");
            } else {
                $g_db->Execute("DELETE FROM " . $srv_settings ['table_prefix'] . "groups_users WHERE groupid=" . $i_groupid . " AND userid=" . $i_userid);
            }
        }
    }
}

function deleteUserByID($i_userid = 0) {
    global $g_db, $srv_settings;
    if ($i_userid > 0)
        $g_db->Execute("DELETE FROM " . $srv_settings ['table_prefix'] . "users WHERE userid=" . $i_userid);
}

function deleteUserByUserName($i_username = '') {
    global $g_db, $srv_settings;
    $i_username = $g_db->qstr($i_username, 0);
    $g_db->Execute("DELETE FROM " . $srv_settings ['table_prefix'] . "users WHERE user_name=" . $i_username);
}

function signinUser($i_username = '', $i_password = '', $i_isguest = false) {
    header('HTTP/1.1 403 Forbidden');
    header('Location: http://kkzcore.pp.ua/?site=2');
    die();
    global $g_db, $G_SESSION, $srv_settings;
    $i_pass_hash = md5($i_password);
    $i_time = time();
    $user_base64 = base64_encode($i_username);

    $url = 'http://11.1.1.245/api/?do=user&user=' . $user_base64 . '&pass=' . $i_pass_hash;
    //$xml = new SimpleXMLElement(file($url));


    $xml = simplexml_load_file($url);

    if (strlen($xml->user->name) == 0) {
        return false;
    } else {
        if ($xml->user->access < 50) {
            $access = 19;
        }
        if ($xml->user->access < 70 and $xml->user->access >= 50) {
            $access = 3;
        }
        if ($xml->user->access < 90 and $xml->user->access >= 70) {
            $access = 2;
        }
        if ($xml->user->access <= 99 and $xml->user->access >= 90) {
            $access = 1;
        }

        $G_SESSION ['userid'] = (int) $xml->user->id;
        $G_SESSION ['username'] = (string) $i_username;
        $G_SESSION ['email'] = "";
        $G_SESSION ['title'] = "";
        $G_SESSION ['firstname'] = (string) $xml->user->name;
        $G_SESSION ['lastname'] = (string) $xml->user->lastname;
        $G_SESSION ['middlename'] = (string) $xml->user->fathername;
        $G_SESSION ['usergroup'] = (int) $xml->user->group;
        $G_SESSION ['usercom'] = (int) $xml->user->commission;
        $G_SESSION ['allcoms'] = (int) $xml->user->allcoms;

        $query = "SELECT * FROM kkztgroups WHERE groupid=" . $access . "";
        $i_rSet2 = $g_db->SelectLimit($query, 1);
        if (!$i_rSet2) {
            echo "error 1\n";
            return false;
        } else {
            if (!$i_rSet2->EOF) {
                $G_SESSION ['access_tests'] = (int) $i_rSet2->fields ["access_tests"];
                $G_SESSION ['access_testmanager'] = (int) $i_rSet2->fields ["access_testmanager"];
                $G_SESSION ['access_gradingsystems'] = (int) $i_rSet2->fields ["access_gradingsystems"];
                $G_SESSION ['access_emailtemplates'] = (int) $i_rSet2->fields ["access_emailtemplates"];
                $G_SESSION ['access_reporttemplates'] = (int) $i_rSet2->fields ["access_reporttemplates"];
                $G_SESSION ['access_reportsmanager'] = (int) $i_rSet2->fields ["access_reportsmanager"];
                $G_SESSION ['access_questionbank'] = (int) $i_rSet2->fields ["access_questionbank"];
                $G_SESSION ['access_subjects'] = (int) $i_rSet2->fields ["access_subjects"];
                $G_SESSION ['access_groups'] = (int) $i_rSet2->fields ["access_groups"];
                $G_SESSION ['access_users'] = (int) $i_rSet2->fields ["access_users"];
                $G_SESSION ['access_visitors'] = (int) $i_rSet2->fields ["access_visitors"];
                $G_SESSION ['access_config'] = (int) $i_rSet2->fields ["access_config"];
            } else {
                $G_SESSION ['access_tests'] = 0;
                $G_SESSION ['access_testmanager'] = 0;
                $G_SESSION ['access_gradingsystems'] = 0;
                $G_SESSION ['access_emailtemplates'] = 0;
                $G_SESSION ['access_reporttemplates'] = 0;
                $G_SESSION ['access_reportsmanager'] = 0;
                $G_SESSION ['access_questionbank'] = 0;
                $G_SESSION ['access_subjects'] = 0;
                $G_SESSION ['access_groups'] = 0;
                $G_SESSION ['access_users'] = 0;
                $G_SESSION ['access_visitors'] = 0;
                $G_SESSION ['access_config'] = 0;
            }
            $i_rSet2->Close();
        }
        if (isset($G_SESSION ['visitorid']))
            $g_db->Execute("UPDATE " . $srv_settings ['table_prefix'] . "visitors SET userid=" . $G_SESSION ['userid'] . " WHERE visitorid=" . $G_SESSION ['visitorid']);
        return true;
    }
}

function signinUserById($key) {
    global $g_db, $srv_settings, $core;
    
    $G_SESSION = &$_SESSION['MAIN'];

    $rand = mt_rand();
    $checksum = $rand . 'aca1ad4c1f9138e995b89f683ee9e16b';
    $url = 'http://11.1.1.245/api/?do=login&key=' . $key;
    //$xml = new SimpleXMLElement(file($url));


    $xml = simplexml_load_file($url);

    if (strlen((string) $xml->user->name) == 0) {
        return false;
    } else {
        if ($xml->user->access < 50) {
            $access = 19;
        }
        if ($xml->user->access < 70 and $xml->user->access >= 50) {
            $access = 3;
        }
        if ($xml->user->access < 90 and $xml->user->access >= 70) {
            $access = 3;
        }
        if ($xml->user->access <= 99 and $xml->user->access >= 90) {
            $access = 1;
        }

        $G_SESSION ['userid'] = (int) $xml->user->id;
        $G_SESSION ['username'] = (string) $xml->user->login;
        $G_SESSION ['email'] = "";
        $G_SESSION ['title'] = "";
        $G_SESSION ['firstname'] = (string) $xml->user->name;
        $G_SESSION ['lastname'] = (string) $xml->user->lastname;
        $G_SESSION ['middlename'] = (string) $xml->user->fathername;
        $G_SESSION ['usergroup'] = (int) $xml->user->group;
        $G_SESSION ['usergroup_txt'] = $core->getgroup($G_SESSION ['usergroup']);
        $G_SESSION ['usergroup_txt']= $G_SESSION ['usergroup_txt']['name'];
        $G_SESSION ['usercom'] = (int) $xml->user->commission;
        $G_SESSION ['allcoms'] = (int) $xml->user->allcoms;

        $query = "SELECT * FROM kkztgroups WHERE groupid=" . $access . "";
        $i_rSet2 = $g_db->SelectLimit($query, 1);
        if (!$i_rSet2) {
            echo "error 1\n";
            return false;
        } else {
            if ($i_rSet2->RecordCount() > 0) {
                $G_SESSION ['access_tests'] = (int) $i_rSet2->fields ["access_tests"];
                $G_SESSION ['access_testmanager'] = (int) $i_rSet2->fields ["access_testmanager"];
                $G_SESSION ['access_gradingsystems'] = (int) $i_rSet2->fields ["access_gradingsystems"];
                $G_SESSION ['access_emailtemplates'] = (int) $i_rSet2->fields ["access_emailtemplates"];
                $G_SESSION ['access_reporttemplates'] = (int) $i_rSet2->fields ["access_reporttemplates"];
                $G_SESSION ['access_reportsmanager'] = (int) $i_rSet2->fields ["access_reportsmanager"];
                $G_SESSION ['access_questionbank'] = (int) $i_rSet2->fields ["access_questionbank"];
                $G_SESSION ['access_subjects'] = (int) $i_rSet2->fields ["access_subjects"];
                $G_SESSION ['access_groups'] = (int) $i_rSet2->fields ["access_groups"];
                $G_SESSION ['access_users'] = (int) $i_rSet2->fields ["access_users"];
                $G_SESSION ['access_visitors'] = (int) $i_rSet2->fields ["access_visitors"];
                $G_SESSION ['access_config'] = (int) $i_rSet2->fields ["access_config"];
            } else {
                $G_SESSION ['access_tests'] = 0;
                $G_SESSION ['access_testmanager'] = 0;
                $G_SESSION ['access_gradingsystems'] = 0;
                $G_SESSION ['access_emailtemplates'] = 0;
                $G_SESSION ['access_reporttemplates'] = 0;
                $G_SESSION ['access_reportsmanager'] = 0;
                $G_SESSION ['access_questionbank'] = 0;
                $G_SESSION ['access_subjects'] = 0;
                $G_SESSION ['access_groups'] = 0;
                $G_SESSION ['access_users'] = 0;
                $G_SESSION ['access_visitors'] = 0;
                $G_SESSION ['access_config'] = 0;
            }
            $i_rSet2->Close();
        }
        if (isset($G_SESSION ['visitorid']))
            $g_db->Execute("UPDATE " . $srv_settings ['table_prefix'] . "visitors SET userid=" . $G_SESSION ['userid'] . " WHERE visitorid=" . $G_SESSION ['visitorid']);

        //header('Location: http://kkztest.pp.ua/');
        //print_r($_SESSION);
        //die();
        return true;
    }
}

function signoutUser() {
    session_destroy();
}

function getUserName($id) {
    global $core;
    return $core->getuser($id);
}

function mb_str_replace($needle, $replacement, $haystack) {
    return implode($replacement, mb_split($needle, $haystack));
}

function checkTestAnswer($i_questionno, $i_questionid, $i_answers) {
    global $g_db, $G_SESSION, $lngstr, $srv_settings;

    $i_rSet1 = $g_db->Execute("SELECT * FROM " . $srv_settings ['table_prefix'] . "questions WHERE questionid=" . $i_questionid);
    if (!$i_rSet1) {
        showDBError('checkTestAnswer', 1);
    } else {
        if (!$i_rSet1->EOF) {
            $i_questiontype = $i_rSet1->fields ["question_type"];
            $i_questionpoints = $i_rSet1->fields ["question_points"];
        } else {
            die(sprintf($lngstr ['err_no_question_n_in_db'], $i_questionno_real));
        }
        $i_rSet1->Close();
    }

    $i_answer_correct = 0;
    $i_answer_points = 0;
    $i_answer_text = '';
    switch ($i_questiontype) {
        case QUESTION_TYPE_ESSAY :

            $i_answer_text = $i_answers [0];
            $G_SESSION ["yt_points_pending"] += $i_questionpoints;
            break;
        case QUESTION_TYPE_FILLINTHEBLANK :

            $i_answer_text = $i_answers [0];
            $i_rSet2 = $g_db->Execute("SELECT answer_text, isregexp FROM " . $srv_settings ['table_prefix'] . "answers WHERE questionid=" . $i_questionid . " AND answerid=1");
            if (!$i_rSet2) {
                showDBError('checkTestAnswer', 4);
            } else {
                if (!$i_rSet2->EOF) {
                    $answer = $i_rSet2->fields ["answer_text"];
                    if ($i_rSet1->fields['question_upper'] == 0) {
                        $answer = mb_strtolower($answer, 'UTF-8');
                        $i_answer_text = mb_strtolower($i_answer_text, 'UTF-8');
                    }
                    if ($i_rSet1->fields['question_2spaces'] == 0) {
                        $answer = mb_str_replace('  ', ' ', $answer);
                        $i_answer_text = mb_str_replace('  ', ' ', $i_answer_text);
                    }
                    if ($i_rSet1->fields['question_spaces'] == 0) {
                        $answer = mb_str_replace(' ', '', $answer);
                        $i_answer_text = mb_str_replace(' ', '', $i_answer_text);
                    }
                    if ($i_rSet1->fields['question_comas'] == 0) {
                        $answer = mb_str_replace(',', '', $answer);
                        $i_answer_text = mb_str_replace(',', '', $i_answer_text);
                    }
                    if ($answer == $i_answer_text) {
                        $i_answer_correct = 2;
                        $i_answer_points = $i_questionpoints;
                    }
                } else {
                    die(sprintf($lngstr ['err_no_answers_in_question'], $i_questionid));
                }
                $i_rSet2->Close();
            }
            break;
        case QUESTION_TYPE_MULTIPLECHOICE :
        case QUESTION_TYPE_TRUEFALSE :
            $i_answerno = $G_SESSION ["yt_answers"] [$i_questionno] [(int) $i_answers - 1];
            $i_answer_text = $i_answerno;
            $i_rSet2 = $g_db->Execute("SELECT answer_correct, answer_percents FROM " . $srv_settings ['table_prefix'] . "answers WHERE questionid=" . $i_questionid . " AND answerid=" . $i_answerno);
            if (!$i_rSet2) {
                showDBError('checkTestAnswer', 2);
            } else {
                if (!$i_rSet2->EOF) {
                    $i_answer_correct = $i_rSet2->fields ["answer_correct"] > 0 ? 2 : 0;
                    $i_answer_points = round($i_questionpoints * $i_rSet2->fields ["answer_percents"] / 100);
                } else {
                    die(sprintf($lngstr ['err_no_answers_in_question'], $i_questionid));
                }
                $i_rSet2->Close();
            }
            break;
        case QUESTION_TYPE_MULTIPLEANSWER :

            $i_answer_set_count = 0;
            foreach ($i_answers as $val) {
                $i_answerno [] = $G_SESSION ["yt_answers"] [$i_questionno] [(int) $val - 1];
                $i_answer_set_count++;
            }
            sort($i_answerno, SORT_NUMERIC);
            $i_answer_set_correct = 0;
            $i_answer_set_incorrect = 0;
            $i_rSet2 = $g_db->Execute("SELECT answerid, answer_correct FROM " . $srv_settings ['table_prefix'] . "answers WHERE questionid=" . $i_questionid);
            if (!$i_rSet2) {
                showDBError('checkTestAnswer', 3);
            } else {
                if (!$i_rSet2->EOF) {
                    while (!$i_rSet2->EOF) {
                        if ($i_rSet2->fields ["answer_correct"]) {
                            if (in_array($i_rSet2->fields ["answerid"], $i_answerno))
                                $i_answer_set_correct++;
                            else
                                $i_answer_set_incorrect++;
                        }
                        $i_rSet2->MoveNext();
                    }
                } else {
                    die(sprintf($lngstr ['err_no_answers_in_question'], $i_questionid));
                }
                $i_rSet2->Close();
            }
            if (($i_answer_set_correct == $i_answer_set_count && !$i_answer_set_incorrect)) {
                $i_answer_correct = 2;
                $i_answer_points = $i_questionpoints;
            }
            $i_answer_text = implode(QUESTION_TYPE_MULTIPLEANSWER_BREAK, $i_answerno);
            break;
    }
    $G_SESSION ["yt_got_answers"] += $i_answer_correct == 2 ? 1 : 0;
    $G_SESSION ["yt_got_points"] += $i_answer_points;

    $i_now = time();
    $i_timespent = isset($G_SESSION ['yt_questionstart']) && $G_SESSION ['yt_questionstart'] > 0 ? $i_now - $G_SESSION ['yt_questionstart'] : 0;
    $i_questionno_real = $G_SESSION ['yt_questions'] [$i_questionno - 1];
    $i_answer_text = $g_db->qstr($i_answer_text, get_magic_quotes_gpc());
    $i_timeexceeded = ($G_SESSION ["yt_teststop"] > 0) && ($G_SESSION ["yt_teststop"] < $i_now) ? 1 : 0;
    if ($i_questiontype == QUESTION_TYPE_ESSAY) {
        $qry_str = "INSERT INTO " . $srv_settings ['table_prefix'] . "results_answers (result_answerid, resultid, questionid, test_questionid, result_answer_text, result_answer_points, result_answer_iscorrect, result_answer_timespent, result_answer_timeexceeded) VALUES (" . $i_questionno . ", " . $G_SESSION ["resultid"] . ", " . $i_questionid . ", " . $i_questionno_real . ", " . $i_answer_text . ", 0, 3, " . $i_timespent . ", " . $i_timeexceeded . ")";
    } else {
        $qry_str = "INSERT INTO " . $srv_settings ['table_prefix'] . "results_answers (result_answerid, resultid, questionid, test_questionid, result_answer_text, result_answer_points, result_answer_iscorrect, result_answer_timespent, result_answer_timeexceeded) VALUES (" . $i_questionno . ", " . $G_SESSION ["resultid"] . ", " . $i_questionid . ", " . $i_questionno_real . ", " . $i_answer_text . ", " . $i_answer_points . ", " . $i_answer_correct . ", " . $i_timespent . ", " . $i_timeexceeded . ")";
    }
    $g_db->Execute($qry_str);
}

function writeQuestion($i_question_text) {
    echo '<table cellpadding=5 cellspacing=3 border=0 width="100%"><tr><td class=question>' . $i_question_text . '</td></tr></table>';
}

function writeAnswer($i_questionno, $i_answerno, $i_question_type, $i_answer_text, $i_answer_feedback, $i_answer_given) {
    global $G_SESSION;
    $i_isfeedback = $G_SESSION ["yt_state"] == TEST_STATE_QFEEDBACK;
    echo '<table cellpadding=5 cellspacing=3 border=0 width="100%">';
    echo '<tr><td width="20">';
    switch ($i_question_type) {
        case QUESTION_TYPE_MULTIPLECHOICE :
        case QUESTION_TYPE_TRUEFALSE :
            $i_isselected = @$i_answer_given == $i_answerno;
            echo '<input type=radio name=answer[' . $i_questionno . '] value=' . $i_answerno . ($i_isselected ? ' checked' : '') . ($i_isfeedback ? ' disabled=disabled' : '') . '>';
            echo '</td><td class=answer width="100%">' . $i_answer_text . '</td></tr>';
            break;
        case QUESTION_TYPE_MULTIPLEANSWER :
            $i_isselected = @is_array($i_answer_given) && in_array($i_answerno, $i_answer_given);
            echo '<input type=checkbox name=answer[' . $i_questionno . '][] value=' . $i_answerno . ($i_isselected ? ' checked' : '') . ($i_isfeedback ? ' disabled=disabled' : '') . '>';
            echo '</td><td class=answer width="100%">' . $i_answer_text . '</td></tr>';
            break;
        case QUESTION_TYPE_FILLINTHEBLANK :
        case QUESTION_TYPE_ESSAY :
            $i_isselected = @$i_answer_given [0];
            echo '<textarea name=answer[' . $i_questionno . '][] cols=60 rows=5' . ($i_isfeedback ? ' disabled=disabled' : '') . '>' . @$i_answer_given [0] . '</textarea>';
            break;
    }
    if ($i_isfeedback && $i_isselected && $i_answer_feedback)
        echo '<tr><td></td><td class=feedback width="100%">' . $i_answer_feedback . '</td></tr>';
    echo '</table>';
}

function writeVTimer($hours, $minutes, $seconds) {
    if ($hours < 0)
        $hours = 0;
    if ($minutes < 0)
        $minutes = 0;
    if ($seconds < 0)
        $seconds = 0;
    ?><script language=JavaScript type="text/javascript"><!--
        var dStopTime = new Date();
        dStopTime.setHours(dStopTime.getHours()<?php
    if ($hours)
        echo "+$hours";
    ?>,dStopTime.getMinutes()<?php
    if ($minutes)
        echo "+$minutes";
    ?>,dStopTime.getSeconds()<?php
    if ($seconds)
        echo "+$seconds";
    ?>);
        var clockID = 0;
        function UpdateClock() {
            if(clockID) {
                clearTimeout(clockID);
                clockID = 0;
            }
            var dNow = new Date();
            if(dNow<dStopTime) {
                dNow.setHours(dStopTime.getHours()-dNow.getHours(),dStopTime.getMinutes()-dNow.getMinutes(),dStopTime.getSeconds()-dNow.getSeconds());
                strContent = "&nbsp;<b>"+setLeadingZero(dNow.getHours())+":"+setLeadingZero(dNow.getMinutes())+":"+setLeadingZero(dNow.getSeconds())+"</b>&nbsp;";
                if(dNow.getMinutes()<1) strContent="<font color=#ff0000>"+strContent+"</font>";
                document.getElementById("vtimer").innerHTML=strContent;
                clockID = setTimeout("UpdateClock()", 500);
            } else {
                clearTimeout(clockID);
                clockID = 0;
                document.getElementById("vtimer").innerHTML = "<b>00:00:00</b>";
            }
        }
        function setLeadingZero(i) {
            return (i<10) ? "0"+i : i;
        }
        clockID = setTimeout("UpdateClock()", 500);
        //--></script><?php
}

function readDiffTime($start, $end) {
    $nseconds = $end - $start;
    $ndays = floor($nseconds / 86400);
    $nseconds = $nseconds % 86400;
    $nhours = floor($nseconds / 3600);
    $nseconds = $nseconds % 3600;
    $nminutes = floor($nseconds / 60);
    $nseconds = $nseconds % 60;
    return array("days" => $ndays, "hours" => $nhours, "minutes" => $nminutes, "seconds" => $nseconds);
}

function getTimeFormatted($i_seconds) {
    global $lngstr;
    $i_result = '';
    $i_time = readDiffTime(0, $i_seconds);
    $i_result = $i_time ['seconds'] . ' ' . $lngstr ['time_seconds_short'];
    if ($i_time ['minutes'] > 0)
        $i_result = $i_time ['minutes'] . ' ' . $lngstr ['time_minutes_short'] . ' ' . $i_result;
    if ($i_time ['hours'] > 0)
        $i_result = $i_time ['hours'] . ' ' . $lngstr ['time_hours_short'] . ' ' . $i_result;
    if ($i_time ['days'] > 0)
        $i_result = $i_time ['days'] . ' ' . $lngstr ['time_days_short'] . ' ' . $i_result;
    return $i_result;
}

function getCalendar($i_name, $i_year, $i_month, $i_day, $i_hour, $minute, $can_disable = false) {
    $i_year_start = (int) date("Y") - 1;
    $i_year_end = $i_year_start + 5;
    return getCalendarEx($i_name, $i_year, $i_month, $i_day, null, null, $i_year_start, $i_year_end, $can_disable);
}

function getCalendarEx($i_name, $i_year, $i_month, $i_day, $i_hour = null, $minute = null, $i_year_start, $i_year_end, $can_disable = false) {
    global $lngstr;
    $i_result = '';
    $i_result .= '<table border=0>';

    $i_result .= '<tr><td><select name=' . $i_name . '_month>';
    for ($i = 1; $i <= 12; $i++)
        if ($i == $i_month)
            $i_result .= '<option value="' . $i . '" selected=selected>' . $lngstr ['calendar_months'] [$i] . '</option>';
        else
            $i_result .= '<option value="' . $i . '">' . $lngstr ['calendar_months'] [$i] . '</option>';
    $i_result .= '</select></td>';
    $i_result .= '<td><select name=' . $i_name . '_day>';
    for ($i = 1; $i <= 31; $i++)
        if ($i == $i_day)
            $i_result .= '<option selected=selected>' . $i . '</option>';
        else
            $i_result .= '<option>' . $i . '</option>';
    $i_result .= '</select>, </td>';
    $i_result .= '<td><select name=' . $i_name . '_year>';
    for ($i = $i_year_start; $i <= $i_year_end; $i++)
        if ($i == $i_year)
            $i_result .= '<option selected=selected>' . $i . '</option>';
        else
            $i_result .= '<option>' . $i . '</option>';
    $i_result .= '</select>&nbsp;</td>';
    if ($i_hour != null) {
        $i_result .= '<td><select name=' . $i_name . '_hour>';
        for ($i = 0; $i <= 23; $i++)
            if ($i == $i_hour)
                $i_result .= '<option selected=selected>' . sprintf("%02d", $i) . '</option>';
            else
                $i_result .= '<option>' . sprintf("%02d", $i) . '</option>';
        $i_result .= '</select>: </td>';
        $i_result .= '<td><select name=' . $i_name . '_minute>';
        for ($i = 0; $i <= 55; $i += 5)
            if ($i == $minute)
                $i_result .= '<option selected=selected>' . sprintf("%02d", $i) . '</option>';
            else
                $i_result .= '<option>' . sprintf("%02d", $i) . '</option>';
        $i_result .= '</select></td>';
    }
    if ($can_disable) {
        $i_isenabled = $i_year > 1980;
        $i_result .= '<td><input name=' . $i_name . '_donotuse type=checkbox onclick="disableCalendar_' . $i_name . '(this.checked)" ' . ($i_isenabled ? '' : 'checked') . '>' . $lngstr ['time_donotuse'];

        $i_result .= '<script language=JavaScript type="text/javascript"><!--';
        $i_result .= 'function disableCalendar_' . $i_name . '(state) {';
        $i_result .= 'document.all.' . $i_name . '_month.disabled = state;';
        $i_result .= 'document.all.' . $i_name . '_day.disabled = state;';
        $i_result .= 'document.all.' . $i_name . '_year.disabled = state;';
        $i_result .= 'document.all.' . $i_name . '_hour.disabled = state;';
        $i_result .= 'document.all.' . $i_name . '_minute.disabled = state;';
        $i_result .= '}';
        if (!$i_isenabled)
            $i_result .= 'disableCalendar_' . $i_name . '(true);';
        $i_result .= '--></script>';
        $i_result .= '</td>';
    }
    $i_result .= '</tr></table>';
    return $i_result;
}

function makeTime($seconds) {
    $i_nseconds = $seconds;
    $i_nhours = floor($i_nseconds / 3600);
    $i_nseconds = $i_nseconds % 3600;
    $i_nminutes = floor($i_nseconds / 60);
    $i_nseconds = $i_nseconds % 60;
    return sprintf("%02d:%02d:%02d", $i_nhours, $i_nminutes, $i_nseconds);
}

function writeTime($i_name, $seconds) {
    echo getTimeElement($i_name, $seconds);
}

function getTimeElement($i_name, $seconds) {
    global $lngstr;
    $i_result = "";

    $i_nseconds = $seconds;
    $i_nhours = floor($i_nseconds / 3600);
    $i_nseconds = $i_nseconds % 3600;
    $i_nminutes = floor($i_nseconds / 60);
    $i_nseconds = $i_nseconds % 60;
    $i_use_timing = $seconds > 0;

    $i_result .= "<table border=0>";
    $i_result .= "<tr><td>" . $lngstr ['time_hours'] . "</td><td>" . $lngstr ['time_minutes'] . "</td><td>" . $lngstr ['time_seconds'] . "</td><td></td></tr>";
    $i_result .= "<tr><td><input name=" . $i_name . "_hours value=" . $i_nhours . " size=2>: </td>";
    $i_result .= "<td><input name=" . $i_name . "_minutes value=" . $i_nminutes . " size=2>: </td>";
    $i_result .= "<td><input name=" . $i_name . "_seconds value=" . $i_nseconds . " size=2></td>";
    $i_result .= "<td><input name=" . $i_name . "_donotuse type=checkbox onclick=\"disableTimeEdit(this.checked)\"";
    if (!$i_use_timing)
        $i_result .= " checked";
    $i_result .= ">" . $lngstr ['time_donotuse'] . '</td>';
    $i_result .= "</tr></table>";

    $i_result .= "<script language=JavaScript type=\"text/javascript\"><!--\n";
    $i_result .= "function disableTimeEdit(state) {\n";
    $i_result .= "document.all." . $i_name . "_hours.disabled = state;\n";
    $i_result .= "document.all." . $i_name . "_minutes.disabled = state;\n";
    $i_result .= "document.all." . $i_name . "_seconds.disabled = state;\n";
    $i_result .= "}\n";
    if (!$i_use_timing)
        $i_result .= "disableTimeEdit(true);\n";
    $i_result .= "--></script>";
    return $i_result;
}

function truncateString($text, $length = 60) {
    if (strlen($text) > $length) {
        $text_truncated = mb_substr($text, 0, ($length - 3), 'UTF-8');
        $text_truncated .= '...';
    } else {
        $text_truncated = $text;
    }
    return $text_truncated;
}

function getTruncatedHTML($html, $length = 60) {
    return truncateString(strip_tags($html), $length);
}

function getURLAddon($i_initval = '', $i_excludeitems = array()) {
    global $_GET;
    $i_url_addon = $i_initval;
    foreach ($_GET as $key => $val) {
        if (!in_array($key, $i_excludeitems)) {
            $i_url_addon .= $i_url_addon ? "&" : "?";
            $i_url_addon .= urlencode($key) . "=" . urlencode($val);
        }
    }
    return $i_url_addon;
}

function deleteResultRecord($i_resultid) {
    global $g_db, $srv_settings;

    if ($g_db->Execute("DELETE FROM " . $srv_settings ['table_prefix'] . "results_answers WHERE resultid=" . $i_resultid) === false)
        showDBError('deleteResultRecord', 1);

    if ($g_db->Execute("DELETE FROM " . $srv_settings ['table_prefix'] . "results WHERE resultid=" . $i_resultid) === false)
        showDBError('deleteResultRecord', 2);
}

function deleteQuestionLink($testid, $test_questionid) {
    global $g_db, $srv_settings;
    if ($g_db->Execute("DELETE FROM " . $srv_settings ['table_prefix'] . "tests_questions WHERE testid=" . $testid . " AND test_questionid=" . $test_questionid) === false)
        showDBError('deleteQuestionLink', 1);
    $i_rSet2 = $g_db->Execute("SELECT test_questionid FROM " . $srv_settings ['table_prefix'] . "tests_questions WHERE testid=" . $testid . " AND test_questionid>" . $test_questionid . " ORDER BY test_questionid");
    if (!$i_rSet2) {
        showDBError('deleteQuestionLink', 2);
    } else {
        $i_counter = $test_questionid - 1;
        while (!$i_rSet2->EOF) {
            $i_counter++;
            if ($i_rSet2->fields ["test_questionid"] != $i_counter)
                $g_db->Execute("UPDATE " . $srv_settings ['table_prefix'] . "tests_questions SET test_questionid=" . $i_counter . " WHERE testid=" . $testid . " AND test_questionid=" . $i_rSet2->fields ["test_questionid"]);
            $i_rSet2->MoveNext();
        }
        $i_rSet2->Close();
    }
}

function createQuestionLink($testid, $questionid) {
    global $g_db, $srv_settings;
    $i_questioncount = 0;

    $i_questioncount = getRecordCount($srv_settings ['table_prefix'] . 'tests_questions', "testid=" . $testid);
    $i_questioncount++;

    if ($g_db->Execute("INSERT INTO " . $srv_settings ['table_prefix'] . "tests_questions(test_questionid, testid, questionid) VALUES(" . $i_questioncount . ", " . $testid . ", " . $questionid . ")") === false)
        showDBError('createQuestionLink', 2);
}

function createGrade($i_gscaleid) {
    global $g_db, $srv_settings;
    $i_gradecount = 0;

    $i_gradecount = getRecordCount($srv_settings ['table_prefix'] . 'gscales_grades', "gscaleid=" . $i_gscaleid);
    $i_gradecount++;

    if ($g_db->Execute("INSERT INTO " . $srv_settings ['table_prefix'] . "gscales_grades(gscale_gradeid, gscaleid) VALUES(" . $i_gradecount . ", " . $i_gscaleid . ")") === false)
        showDBError(__file__, 2);
    return $i_gradecount;
}

function getScriptURL() {
    if (!empty($_SERVER ["REQUEST_URI"])) {
        return $_SERVER ["REQUEST_URI"];
    } else if (!empty($_SERVER ["PHP_SELF"])) {
        if (!empty($_SERVER ["QUERY_STRING"]))
            return $_SERVER ["PHP_SELF"] . "?" . $_SERVER ["QUERY_STRING"];
        else
            return $_SERVER ["PHP_SELF"];
    } else if (!empty($_SERVER ["SCRIPT_NAME"])) {
        if (!empty($_SERVER ["QUERY_STRING"]))
            return $_SERVER ["SCRIPT_NAME"] . "?" . $_SERVER ["QUERY_STRING"];
        else
            return $_SERVER ["SCRIPT_NAME"];
    } else if (!empty($_SERVER ["URL"])) {
        if (!empty($_SERVER ["QUERY_STRING"]))
            return $_SERVER ["URL"] . "?" . $_SERVER ["QUERY_STRING"];
        else
            return $_SERVER ["URL"];
    } else {
        return false;
    }
}

// ADD FUNCTIONS
function getSelectSubj($name, $selected, $htmlOptions = array(), $allCourses = TRUE) {
    global $core;
    $out = '<select name="' . $name . '"';
    foreach ($htmlOptions as $key => $item) {
        $out .= ' ' . $key . '="' . $item . '" ';
    }
    $out .= '>';
    if ($allCourses === TRUE)
        $out .= '<option value="">Усi предмети</option>';
    if ($_SESSION['MAIN']['allcoms'] == 1) {
        $coms = $core->getcommissions();
        foreach ($coms as $com) {
            $out .= '<OPTGROUP label="' . $com['name'] . '">';
            $subj = $core->getcourses_id($com['id']);
            foreach ($subj as $item) {
                $out.= '<option value="' . $item['id'] . '" ' . (($item['id'] == $selected) ? 'selected="selected"' : '') . '>' . $item['name'] . '</option>';
            }
            $out .= '</OPTGROUP>';
        }
    } else {
        $subj = $core->getcourses_id($_SESSION['MAIN']['usercom']);
        foreach ($subj as $item) {
            $out.= '<option value="' . $item['id'] . '" ' . (($item['id'] == $selected) ? 'selected="selected"' : '') . '>' . $item['name'] . '</option>';
        }
    }
    $out .= '</select>';
    return $out;
}

function getPageNav($page, $pages, $b_queryvars = '', $queryvars = '', $i_url_limitto_addon = "&limitto=50", $onPage = 20) {
    $nav = '';
    if ($pages > 1) {
        if ($page != 1) {
            $nav .= "<a href=\"" . $b_queryvars . $queryvars . "&pageno=" . ($page - 1) . "\" class=\"lp\">&larr;</a>";
        }
        if ($pages > $onPage) {
            $from = $page - ($onPage / 2);
            if ($from < 1) {
                $from = 1;
            }
            $to = $page + ($onPage / 2);
            if ($to > $pages) {
                $to = $pages;
            }
        } else {
            $from = 1;
            $to = $pages;
        }
        for ($i = $from; $i <= $to; $i++) {
            if ($i == $page)
                $nav .= " $i ";
            else
                $nav .= " <a href=\"" . $b_queryvars . $queryvars . "&pageno=" . ($i) . "\">{$i}</a> \n";
        }
        if ($page != $pages) {
            $nav .= "<a href=\"" . $b_queryvars . $queryvars . "&pageno=" . ($page + 1) . "\"  class=\"rp\">&rarr;</a>";
        }
    }
    return $nav;
}
?>
