<?php
header ( 'Content-type: text/html; charset=utf-8' );
global $core_api;
$coms = $core_api->getcommissions ();
$coms_out = '<option value="1">Тимчасова</option>';
foreach ( $coms as $item ) {
		$coms_out .= "<option value=\"" . $item ['id'] . "\">" . $item ['name'] . "</option>";
}
?>

<div id="comandcour_dialog" title="Комісія та предмет"
	style="font-size: 14px;">
<form id="form1" name="form1" method="post" action="">
<table width="100%" border="0">
	<tr>
		<td>Комiсiя:</td>
		<td>Предмет:</td>
	</tr>
	<tr>
		<td width="50%"><select name="com_edit" size="25" id="com_edit"
			style="width: 100%;" onChange="get_courses()">
			<?php echo $coms_out;?>
		</select></td>
		<td><select name="course_edit" size="25" id="course_edit"
			style="width: 100%;">
		</select></td>
	</tr>
</table>
</form>
</div>
<script type="text/javascript">
$("#comandcour_dialog").dialog({
	autoOpen: true,
	width: 640,
	modal: true,
	buttons: {
		Назначити: function() {
			$('#com').val($('#com_edit').val());
			$('#course').val($('#course_edit').val());
			$('#namecom').html($('#com_edit option:selected').text() + " <img src=\"/images/pencil.png\">"); 
			$('#namecour').html($('#course_edit option:selected').text() + " <img src=\"/images/pencil.png\">"); 
			$( this ).dialog( "close" );
		},		
		Вiдмiна: function() {
			$( this ).dialog( "close" );
		}
	}
});
function get_courses(){
	com = $('#com_edit').val();
			$.getJSON("/index.php?do=ajax&f=get_courses&com="+com, function(data) {
				$("#course_edit").html(data.course_text);
				$("#course_edit").val($('#course').val());
				});
	
return true;
	}
$("#com_edit").val($('#com').val());
	get_courses();
</script>