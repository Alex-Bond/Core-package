<?php header('Content-type: text/html; charset=utf-8');?>
<script type="text/javascript">
$('#courses_select').fadeIn("fast");
$('#courses_select').corner();
function setgroup(id,name){
    $('#course').val(id);
    $('#namecour').html(name);
    $('#courses_select').fadeOut("fast");
}
		</script>
<div id="courses_select" style="width:630px; margin-left:-315px; left:50%; top: 70px; position:absolute; padding:10px; background:#d3d3d3; display:none;">
<table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">
    <?php
    global $db, $core_api;
    $com = $_GET['com'];
	$gr = $core_api->getcourses_id($com);
	foreach($gr as $group){
		echo  "<div align=\"left\" style=\"float: left; width:300px; text-align:center; padding:3px; border:1px solid #EEE; cursor: pointer;\" onclick=\"setgroup(".$group['id'].",".$db->qstr($group['name']).")\">".$group['name']."</div>";
	}
	
    ?>
    </td>
  </tr>
  <tr><td align="center"><br /><a onClick="$('#courses_select').fadeOut('fast');" style="text-decoration: underline; cursor: pointer;">Зачинити</a></td></tr>
</table>
</div>