<?php
header ( 'Content-type: text/html; charset=utf-8' );
global $core_api;
$coms = $core_api->getcommissions ();
$coms_out = '<option value="1">Тимчасова</option>';
foreach ( $coms as $item ) {
		$coms_out .= "<option value=\"" . $item ['id'] . "\">" . $item ['name'] . "</option>";
}
?>
<div id="addform_base" title="Додавання літератури"
	style="font-size: 14px; font-face: Segoe UI;">
<form action="" method="post" enctype="multipart/form-data"
	name="addform">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
	<tr>
		<td width="145" align="right">Тип:</td>
		<td width="427"><select name="type" id="type"
			onchange="type_change();">
			<option value="1">Література</option>
			<option value="2">Відео/Аудiо</option>
		</select></td>
	</tr>
	<tr>
		<td align="right" id="filefile">Файл літератури:</td>
		<td><input name="file" type="file" id="file" size="50" /></td>
	</tr>
	<tr>
		<td align="right" id="filefile">Обкладинка (превью):</td>
		<td><input name="image" type="file" id="image" size="50" /><br>
		<span style="font-size: 10px">Розмір обкладинки (превью) повинен бути
		100х100 та мати формати jpg, png або gif.</span></td>
	</tr>
	<tr>
		<td align="right">Назва:</td>
		<td><input name="name" type="text" id="name" size="50" /></td>
	</tr>
	<tr>
		<td align="right">Автор:</td>
		<td><input name="autor" type="text" id="autor" size="50" /></td>
	</tr>
	<tr>
		<td align="right">Комісія:</td>
		<td onclick="open_form_jqui('#comandcour_dialog');"
			style="cursor: pointer;" id="namecom"><img src="/images/pencil.png"></td>
	</tr>
	<tr>
		<td align="right">Предмет</td>
		<td id="namecour" onclick="open_form_jqui('#comandcour_dialog');" style="cursor: pointer;"><img src="/images/pencil.png"></td>
	</tr>
	<tr>
		<td width="145" align="right">На Flash:</td>
		<td width="427"><input type="radio" name="onflash" id="onflash" value="1" checked />
          <label for="onflash">Можливо</label> 
          <input name="onflash" type="radio" id="onflash" value="0" />
          <label for="onflash">Не можливо</label>
		
		</td>
	</tr>
	<tr>
		<td width="145" align="right">Вивiд студенту:</td>
		<td width="427"><input type="radio" name="show" id="show" value="1" checked />
          <label for="show">Видимий</label> 
          <input name="show" type="radio" id="show" value="0" />
          <label for="show">Не видимий</label>
		
		</td>
	</tr>
</table>
<input name="com" type="hidden" id="com" value="" /> <input
	name="course" type="hidden" id="course" value="" /> <input name="do"
	type="hidden" id="do" value="base" /> <input name="f" type="hidden"
	id="f" value="litadd" /></form>
</div>
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
$("#addform_base").dialog({
	autoOpen: true,
	width: 640,
	modal: true,
	buttons: {
		Завантажити: function() {
			document.forms.addform.submit();
		},		
		Вiдмiна: function() {
			$( this ).dialog( "close" );
		}
	}
});
$("#comandcour_dialog").dialog({
	autoOpen: false,
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
	com = $("#com_edit").val();
			$.getJSON("/index.php?do=ajax&f=get_courses&com="+com, function(data) {$("#course_edit").html(data.course_text);});
return true;
	}
</script>