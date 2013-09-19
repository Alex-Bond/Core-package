<?php
require_once($DOCUMENT_INC."top.inc.php");
echo '<h2>'.$i_confirm_header.'</h2>';
echo $i_confirm_request;
echo "<br>";
writeATag($i_confirm_url.'&confirmed=1', $lngstr['label_yes']);
echo " / ";
writeATag($i_confirm_url.'&confirmed=0', $lngstr['label_no']);
require_once($DOCUMENT_INC."btm.inc.php");
?>