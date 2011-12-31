var add_form = 0;
var com_iss = 0;
var comcour = 0;
 
function show_predm(com) {

	com1 = 'index.php?do=ajax&showcourses=true&com=' + com;

	$('#predmety').load(com1);

	if (com_iss > 0) {
		row = com_iss;
		document.getElementById('com_r' + row).style.background = 'transparent';
		$('#com_r' + com)
				.css(
						'background: url(images/predm_row_bg.png) no-repeat center left');
		com_iss = com;
	} else {
		name = '#com_r' + com;
		$('#com_r' + com)
				.css(
						'background: url(images/predm_row_bg.png) no-repeat center left');
		com_iss = com;
	}
}
function row_off(com) {
	if (com != com_iss) {
		document.getElementById('com_r' + com).style.background = 'transparent';
	}
}
function onflash(id) {
	com1 = 'index.php?do=ajax&AJAX=true&&f=onflash&id=' + id;
	$('#temp_abs').load(com1);
}

function get_students(id) {
	$.getJSON('/?do=ajax&AJAX=true&f=stats&sf=get_students&id=' + id, function(data) {
			$("#students").html(data.students_text);
		});
}

function open_addform() {
	if (add_form == 0) {
		com1 = 'index.php?do=ajax&AJAX=true&f=litadd';
		$('#temp').load(com1);
		add_form = 1;
		return true;
	} else {
		$("#addform_base").dialog('open');
	}
}

function edit_comcour() {
	if (comcour == 0) {
		com1 = 'index.php?do=ajax&AJAX=true&f=edit_comcour';
		$('#temp').load(com1);
		comcour = 1;
		return true;
	} else {
		$("#comandcour_dialog").dialog("open");
		return true;
	}
}

function open_form_jqui(name) {
	$(name).dialog('open');
	return true;
}
function close_form_jqui(name) {
	$(name).dialog('close');
	return true;
}
function video(name, file) {
	scroll(0,0);
	$('.video_bg').fadeIn("slow");
	$('#videoplayer1').fadeIn("slow");

	link = "/books/" + file;

	var flashvars = {
		"uid" : "videoplayer",
		"comment" : name,
		"st" : "./styles/video20-95.txt",
		"file" : link
	};

	var attributes = {
		id : "videoplayer"
	};
	var params = {
		wmode : "opaque",
		allowFullScreen : "true",
		allowScriptAccess : "always",
		bgcolor : "#000000"
	};
	new swfobject.embedSWF("/swf/uppod.swf", "videoplayer", "500", "375",
			"9.0.0", false, flashvars, params, attributes);
}

function video_close() {
	uppodSend('videoplayer', 'stop');
	$('.video_bg').fadeOut("slow");
	$('#videoplayer1').fadeOut("slow");
}
function type_change() {
	type = $('#type').val();
	if (type == 1) {
		$('#filefile').html("Файл літератури:");
	} else {
		$('#filefile').html("Файл відео/аудiо:");
	}
}
