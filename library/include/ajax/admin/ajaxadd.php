<?php header('Content-type: text/html; charset=utf-8');?>
<script type="text/javascript">
$('#commissions_select').fadeIn("fast");
$('#commissions_select').corner();
function setgroup(id,name){
    $('#com').val(id);
    $('#namecom').html(name);
    $('#namecour').html("Оберіть предмет.");
    $('#commissions_select').fadeOut("fast");
}
		</script>
<div id="commissions_select" style="width:630px; margin-left:-315px; left:50%; top: 70px; position:absolute; padding:10px; background:#d3d3d3; display:none;">
<table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">
    <?php
	global $db, $core_api;
	$gr = $core_api->getcommissions();
	foreach($gr as $com){
		echo  "<div align=\"left\" style=\"float: left; width:300px; text-align:center; padding:3px; border:1px solid #EEE; cursor: pointer;\" onclick=\"setgroup(".$com['id'].",".$db->qstr($com['name']).")\">".$com['name']."</div>";
	}
    ?>
    

    <tr><td align="center"><br /><a onClick="$('#commissions_select').fadeOut('fast');" style="text-decoration: underline; cursor: pointer;">Зачинити</a></td></tr>
    </td>
  </tr>
</table>
</div>