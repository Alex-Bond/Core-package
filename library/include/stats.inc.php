<?php
class core_stats {
	public function add($event, $result, $user, $return_id = false) {
		global $db;
		
		$sql = "insert into `stats` (`event`,`result`,`userid`) values (" . $db->qstr ( $event ) . "," . $db->qstr ( $result ) . "," . $db->qstr ( $user ['id'] ) . ")";
		if ($db->Execute ( $sql ) === false) {
			core_error::db_insert_error ( $db->ErrorMsg () );
		} else {
			if ($return_id === true) {
				return $db->Insert_ID ();
			} else {
				return true;
			}
		}
	}
}

class lib_stats {
	public function get_dates_array() {
		function createDateRangeArray($strDateFrom, $strDateTo) {
			$aryRange = array ();
			
			$iDateFrom = mktime ( 1, 0, 0, substr ( $strDateFrom, 5, 2 ), substr ( $strDateFrom, 8, 2 ), substr ( $strDateFrom, 0, 4 ) );
			$iDateTo = mktime ( 1, 0, 0, substr ( $strDateTo, 5, 2 ), substr ( $strDateTo, 8, 2 ), substr ( $strDateTo, 0, 4 ) );
			
			if ($iDateTo >= $iDateFrom) {
				array_push ( $aryRange, date ( 'Y-m-d', $iDateFrom ) ); // first entry
				

				while ( $iDateFrom < $iDateTo ) {
					$iDateFrom += 86400; // add 24 hours
					//array_push ( $aryRange, date ( 'Y-m-d', $iDateFrom ) );
					$aryRange [date ( 'Y-m-d', $iDateFrom )] = date ( 'Y-m-d', $iDateFrom );
				}
			}
			return $aryRange;
		}
		$arr = createDateRangeArray ( '2010-09-01', date ( 'Y-m-d' ) );
		return $arr;
	}
	
	public function visitors_csv() {
		global $db;
		$visitors = array ();
		//$recordSet = &$db->Execute ( 'SELECT dates.time, count(stats.id) as cnt FROM dates LEFT JOIN stats ON (TO_DAYS(dates.time) = TO_DAYS(stats.date) AND stats.event=\'login\') WHERE dates.time <NOW() GROUP BY dates.time ORDER BY dates.time' );
		$recordSet = &$db->Execute ( 'SELECT FROM_DAYS(TO_DAYS(stats.date)) as time, count(TO_DAYS(stats.date)) as cnt FROM stats WHERE stats.event=\'login\' GROUP BY time ORDER BY time DESC' );
		if (! $recordSet)
			core_error::db_exec_error ( $db->ErrorMsg () );
		while ( ! $recordSet->EOF ) {
			$visitors [$recordSet->fields ['time']] = $recordSet->fields ['cnt'];
			$recordSet->MoveNext ();
		}
		$dates = lib_stats::get_dates_array ();
		foreach ( $dates as $item ) {
			if (isset ( $visitors [$item] ))
				echo $item . ";" . $visitors [$item] . "\n";
			else
				echo $item . ";0\n";
		}
		die ();
	}
public function read_csv() {
		global $db;
		$visitors = array ();
		//$recordSet = &$db->Execute ( 'SELECT dates.time, count(stats.id) as cnt FROM dates LEFT JOIN stats ON (TO_DAYS(dates.time) = TO_DAYS(stats.date) AND stats.event=\'book\') WHERE dates.time <NOW() GROUP BY dates.time ORDER BY dates.time' );
		$recordSet = &$db->Execute ( 'SELECT FROM_DAYS(TO_DAYS(stats.date)) as time, count(TO_DAYS(stats.date)) as cnt FROM stats WHERE stats.event=\'book\' GROUP BY time ORDER BY time DESC' );
		if (! $recordSet)
			core_error::db_exec_error ( $db->ErrorMsg () );
		while ( ! $recordSet->EOF ) {
			$visitors [$recordSet->fields ['time']] = $recordSet->fields ['cnt'];
			$recordSet->MoveNext ();
		}
		$dates = lib_stats::get_dates_array ();
		foreach ( $dates as $item ) {
			if (isset ( $visitors [$item] ))
				echo $item . ";" . $visitors [$item] . "\n";
			else
				echo $item . ";0\n";
		}
		die ();
	}
	public function home_html() {
		global $content, $document;
		$document ['title'] = 'Статистика';
		$content = '<div style="width:100%; position:relative;" id="tabs">
<div style="height:20px; width:140px;"  class="tab" onclick="window.location.href = \'/?do=stats\'">Головна статистики</div>
<div style="height:20px; width:180px;"  class="tab" onclick="window.location.href=\'/?do=stats&f=students\'">Статистика по студентам</div>
<div style="height:20px; width:70px;" class="tab" onclick="window.location.href=\'/?do=stats&f=report\'">Звiтнiсть</div>
</div>
<br /><br /><br />
<script type="text/javascript" src="/js/amline/swfobject.js"></script>
<div id="amcharts_visitors">You need to upgrade your Flash Player</div>
<script type="text/javascript">
	var so = new SWFObject("/js/amline/amline.swf", "amline", "100%", "400", "8", "#FFFFFF");
	so.addVariable("path", "/js/amline/");
	so.addVariable("chart_settings", encodeURIComponent("<settings><font>Tahoma</font><hide_bullets_count>18</hide_bullets_count><data_type>csv</data_type><background><color>E3E3E3</color><alpha>100</alpha><border_alpha>10</border_alpha></background><plot_area><margins><left>50</left><right>40</right><bottom>65</bottom></margins></plot_area><grid><x><alpha>10</alpha><approx_count>9</approx_count></x><y_left><alpha>10</alpha></y_left></grid><axes><x><width>1</width><color>0D8ECF</color></x><y_left><width>1</width><color>0D8ECF</color></y_left></axes><indicator><color>0D8ECF</color><x_balloon_text_color>FFFFFF</x_balloon_text_color><line_alpha>50</line_alpha><selection_color>0D8ECF</selection_color><selection_alpha>20</selection_alpha></indicator><legend><enabled>0</enabled></legend><zoom_out_button><text_color_hover>FF0F00</text_color_hover></zoom_out_button><graphs><graph gid=\'0\'><title>Кiлькiсть користувачiв</title><color>00CC33</color><color_hover>FF0F00</color_hover><line_width>2</line_width><fill_alpha>30</fill_alpha><bullet>round</bullet></graph></graphs><labels><label lid=\'0\'><text><![CDATA[<b>Вiдвiдування системи</b>]]></text><y>25</y><text_size>13</text_size><align>center</align></label></labels></settings>"));
	so.addVariable("data_file", encodeURIComponent("/?do=ajax&f=stats&sf=visits"));
	so.write("amcharts_visitors");
</script>
<br />
<div id="amcharts_read">You need to upgrade your Flash Player</div>
<script type="text/javascript">
	var so = new SWFObject("/js/amline/amline.swf", "amline", "100%", "400", "8", "#FFFFFF");
	so.addVariable("path", "/js/amline/");
	so.addVariable("chart_settings", encodeURIComponent("<settings><font>Tahoma</font><hide_bullets_count>18</hide_bullets_count><data_type>csv</data_type><background><color>E3E3E3</color><alpha>100</alpha><border_alpha>10</border_alpha></background><plot_area><margins><left>50</left><right>40</right><bottom>65</bottom></margins></plot_area><grid><x><alpha>10</alpha><approx_count>9</approx_count></x><y_left><alpha>10</alpha></y_left></grid><axes><x><width>1</width><color>0D8ECF</color></x><y_left><width>1</width><color>0D8ECF</color></y_left></axes><indicator><color>0D8ECF</color><x_balloon_text_color>FFFFFF</x_balloon_text_color><line_alpha>50</line_alpha><selection_color>0D8ECF</selection_color><selection_alpha>20</selection_alpha></indicator><legend><enabled>0</enabled></legend><zoom_out_button><text_color_hover>FF0F00</text_color_hover></zoom_out_button><graphs><graph gid=\'0\'><title>Кiлькiсть користувачiв</title><color>00CCFF</color><color_hover>FF9900</color_hover><line_width>2</line_width><fill_alpha>30</fill_alpha><bullet>round</bullet></graph></graphs><labels><label lid=\'0\'><text><![CDATA[<b>Вiдкриття книг</b>]]></text><y>25</y><text_size>13</text_size><align>center</align></label></labels></settings>"));
	so.addVariable("data_file", encodeURIComponent("/?do=ajax&f=stats&sf=read"));
	so.write("amcharts_read");
</script>
		';
	}
	public function students_home() {
		global $content, $document, $core_api;
		$document ['title'] = 'Статистика';
		$gr = $core_api->getgroups ();
		foreach ( $gr as $item ) {
			$groups .= "<option value=\"" . $item ['id'] . "\">" . $item ['name'] . "</option>\n";
		}
		$content = '
		<div style="width:100%; position:relative;" id="tabs">
<div style="height:20px; width:140px;"  class="tab" onclick="window.location.href = \'/?do=stats\'">Головна статистики</div>
<div style="height:20px; width:180px;"  class="tab" onclick="window.location.href=\'/?do=stats&f=students\'">Статистика по студентам</div>
<div style="height:20px; width:70px;" class="tab" onclick="window.location.href=\'/?do=stats&f=report\'">Звiтнiсть</div>
</div>
<br /><br /><br />
		<table width="100%" border="0">
  <tr>
    <td width="50%" valign="top" style="border-right: 1px solid #000;">Група:<br />
      <label for="groups"></label>
      <select name="groups" size="20" id="groups" style="width:100%;" onchange="get_students($(this).val());">
        ' . $groups . '
    </select></td>
    <td valign="top">Студент:<br />
      <span style="border-right: 1px solid #000;">
      <select name="students" size="20" id="students" style="width:100%;">
      </select>
    </span></td>
  </tr>
</table>
<p align="center">
  <input type="submit" name="go" id="go" value="Переглянути статистику" onclick="if($(\'#students\').val()>0){window.location.href=\'/?do=stats&f=students&id=\'+$(\'#students\').val();}" />
</p>';
	}
	public function get_students() {
		header ( 'Content-type: text/json; charset=utf-8' );
		global $core_api;
		$gr = $core_api->getstudentsingroup ( $_GET ['id'] );
		foreach ( $gr as $item ) {
			$out ['students_text'] .= "<option value=\"" . $item ['id'] . "\">" . $item ['lastname'] . " " . $item ['name'] . "</option>";
		}
		echo json_encode ( $out );
		die ();
	}
	public function students_id() {
		global $content, $document, $core_api, $db;
		$document ['title'] = 'Статистика';
		
		$user = $core_api->getuser ( $_GET ['id'] );
		$content = '
		<div style="width:100%; position:relative;" id="tabs">
<div style="height:20px; width:140px;"  class="tab" onclick="window.location.href = \'/?do=stats\'">Головна статистики</div>
<div style="height:20px; width:180px;"  class="tab" onclick="window.location.href=\'/?do=stats&f=students\'">Статистика по студентам</div>
<div style="height:20px; width:70px;" class="tab" onclick="window.location.href=\'/?do=stats&f=report\'">Звiтнiсть</div>
</div>
<br /><br /><br />
		<h3 align="center">' . $user ['lastname'] . ' ' . $user ['firstname'] . ' ' . $user ['middlename'] . '</h3>
		';
		$recordSet = &$db->Execute ( "SELECT *  FROM `stats` WHERE `userid` = " . $_GET ['id'] . " ORDER BY `date` DESC" );
		if (! $recordSet)
			core_error::db_exec_error ( $db->ErrorMsg () );
		
		$content .= "<table width=\"100%\" border=\"0\" cellspacing=\0\" cellpadding=\"5\">";
		function get_event($event, $result, $id) {
			global $db;
			if ($event == 'login') {
				if ($result == 'yes')
					return "<font color=green><i>Увiйшов до системи.</i></font>";
				
				if ($result == 'dubses')
					return "<font color=red><i>Помилка входу. Користувач вже у системi.</i></font>";
			}
			if ($event == 'book') {
				$name = &$db->Execute ( "select name from books where id=" . $result );
				if (! $name)
					core_error::db_exec_error ( $db->ErrorMsg () );
				
				$out = "<font color=darkblue>Прочитав книгу <b>" . $name->fields ['name'] . "</b></font>";
				return $out;
			
			}
			if ($event == 'tests') {
				$out = "<font color=orange>Перейшов до системи тестування.</font>";
				return $out;
			
			}
			if ($event == 'onflash') {
				$out = "Завантажив на флеш " . $result . " книги. <img src='/images/eye.png' style='cursor: pointer;' onClick='open_onflash_stat(" . $id . ");' />";
				return $out;
			
			}
			return $event . " " . $result;
		}
		
		while ( ! $recordSet->EOF ) {
			$content .= "
        <tr style=\"border-bottom:1px solid #fff; \">
          <td align=center width=200>" . $recordSet->fields ['date'] . "</td>
          <td align=left>" . get_event ( $recordSet->fields ['event'], $recordSet->fields ['result'], $recordSet->fields ['id'] ) . "</td>
          </tr>";
			
			$recordSet->MoveNext ();
		}
		$content .= '</table>
		<script language="JavaScript">
function open_onflash_stat(id){
	window.open(\'/?do=stats&f=on_flash&id=\'+id,\'core_lib\',\'width=820,height=500\');
}
</script>
		';
	}
	public function on_flash() {
		global $db;
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Список лiтератури</title>
</head>

<body>
<table width="800" border="1" cellspacing="0">
  <tr>
    <th width="70%">Назва</th>
    <th>Автор</th>
  </tr>';
		$recordSet = &$db->Execute ( "SELECT books.name, books.autor  FROM `onflash`,`books` WHERE onflash.`unicid` = " . $_GET ['id'] . " AND books.`id`=onflash.`book`" );
		if (! $recordSet)
			core_error::db_exec_error ( $db->ErrorMsg () );
		
		while ( ! $recordSet->EOF ) {
			echo '<tr><td>' . $recordSet->fields ['name'] . '</td><td>' . $recordSet->fields ['autor'] . '</td></tr>';
			$recordSet->MoveNext ();
		}
		echo '</table></body></html>';
		die ();
	}
	public function report_html() {
		global $content, $document;
		$document ['title'] = 'Статистика';
		
		$content = '
		<div style="width:100%; position:relative;" id="tabs">
<div style="height:20px; width:140px;"  class="tab" onclick="window.location.href = \'/?do=stats\'">Головна статистики</div>
<div style="height:20px; width:180px;"  class="tab" onclick="window.location.href=\'/?do=stats&f=students\'">Статистика по студентам</div>
<div style="height:20px; width:70px;" class="tab" onclick="window.location.href=\'/?do=stats&f=report\'">Звiтнiсть</div>
</div>
<br /><br />
		<h3 align="center">Роздрукувати звiт</h3>
<table width="500" border="0" align="center">
  <tr>
    <th width="50%" align="right">Дата початку:</th>
    <td><input name="start_date" type="text" id="start_date" /></td>
  </tr>
  <tr>
    <th align="right">Дата закiнчння:</th>
    <td><input name="end_date" type="text" id="end_date" /></td>
  </tr>
  <tr>
    <th align="right">&nbsp;</th>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th align="right">ПIП Завiдуючого бiблiотеки:</th>
    <td><input name="fiozav" type="text" id="fiozav" size="40" /></td>
  </tr>
</table>
<p align="center">
  <input type="button" name="print" id="print" value="Роздрукувати" onclick="open_print_report();" />
</p>
<script language="JavaScript">
function open_print_report(){
	if($("#start_date").val().length>0 && $("#end_date").val().length>0 && $("#fiozav").val().length>0){
		window.open(\'/?do=stats&f=print_report&start=\'+$("#start_date").val()+\'&end=\'+$("#end_date").val()+\'&fio=\'+$("#fiozav").val(),\'core_lib\',\'width=400,height=200\');
	}else{
		alert(\'Усi поля обов`язковi\');
	}
}
$(function() {
		$.datepicker.setDefaults($.datepicker.regional[\'uk\']);
		var dates = $( "#start_date, #end_date" ).datepicker({ dateFormat: \'yy-mm-dd\',minDate: new Date(2010, 9 - 1, 1), maxDate: -0 , firstDay: 1, onSelect: function( selectedDate ) {
				var option = this.id == "start_date" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" );
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
			}});
	});
</script>
';
	}
	public function report_print($from, $to, $fio) {
		global $db, $core_api;
		
		if (! isset ( $from ) or strlen ( $from ) < 10 or ! isset ( $to ) or strlen ( $to ) < 10 or ! isset ( $fio )) {
			die ( "Заповнiть усi поля форми." );
		}
		$from_ts = mktime ( 0, 0, 0, substr ( $from, 5, 2 ), substr ( $from, 8, 2 ), substr ( $from, 0, 4 ) );
		$to_ts = mktime ( 0, 0, 0, substr ( $to, 5, 2 ), substr ( $to, 8, 2 ), substr ( $to, 0, 4 ) );
		$print ['from'] = date ( 'd.m.Y', $from_ts );
		$print ['to'] = date ( 'd.m.Y', $to_ts );
		$from = $db->qstr ( $from );
		$to = $db->qstr ( $to );
		
		//Visits
		$recordSet = &$db->Execute ( 'select count(*) as cnt from stats where event="login" AND result="yes" AND (date>' . $from . ' AND date<' . $to . ')' );
		if (! $recordSet)
			core_error::db_exec_error ( $db->ErrorMsg () );
		$print ['visits_int'] = $recordSet->fields ['cnt'];
		
		//Reads
		$recordSet = &$db->Execute ( 'select count(*) as cnt from stats where event="book" AND (date>' . $from . ' AND date<' . $to . ')' );
		if (! $recordSet)
			core_error::db_exec_error ( $db->ErrorMsg () );
		$print ['reads_int'] = $recordSet->fields ['cnt'];
		
		//On flash
		$recordSet = &$db->Execute ( 'select sum(result) as cnt from stats where event="onflash" AND (date>' . $from . ' AND date<' . $to . ')' );
		if (! $recordSet)
			core_error::db_exec_error ( $db->ErrorMsg () );
		$print ['onflash_int'] = $recordSet->fields ['cnt'];
		
		// Init count
		$recordSet = &$db->Execute ( 'SELECT SUM((select count(*) from stats where event="addbook" AND (date>' . $from . ' AND date<' . $to . '))-(select count(*) from stats where event="delbook" AND (date>' . $from . ' AND date<' . $to . '))) as cnt' );
		if (! $recordSet)
			core_error::db_exec_error ( $db->ErrorMsg () );
		$init_int = $recordSet->fields ['cnt'];
		
		// Books count
		$recordSet = &$db->Execute ( 'select count(*) as cnt from books where cheked=1 and course!=0' );
		if (! $recordSet)
			core_error::db_exec_error ( $db->ErrorMsg () );
		$print ['books_int'] = $init_int + $recordSet->fields ['cnt'];
		
		// Lits count
		$recordSet = &$db->Execute ( 'select count(*) as cnt from books where cheked=1 and course!=0 and (vid=0 OR vid=1)' );
		if (! $recordSet)
			core_error::db_exec_error ( $db->ErrorMsg () );
		$print ['lits_int'] = $recordSet->fields ['cnt'];
		
		// Multimedia
		$print ['multimedia_int'] = $print ['$books_int'] - $print ['$list_int'];
		
		//Added books
		$recordSet = &$db->Execute ( 'select count(*) as cnt from stats where event="addbook" AND (date>' . $from . ' AND date<' . $to . ')' );
		if (! $recordSet)
			core_error::db_exec_error ( $db->ErrorMsg () );
		$print ['adds_int'] = $recordSet->fields ['cnt'];
		
		//Deleted books
		$recordSet = &$db->Execute ( 'select count(*) as cnt from stats where event="delbook" AND (date>' . $from . ' AND date<' . $to . ')' );
		if (! $recordSet)
			core_error::db_exec_error ( $db->ErrorMsg () );
		$print ['dels_int'] = $recordSet->fields ['cnt'];
		
		// Output
		

		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Звіт</title>
</head>
<body style="font-size: 14px;" onload="window.print();">
<h1 align="center">Звіт</h1>
<p align="center">Про роботу та базу цифрової бібліотеки<br>
  з ' . $print ['from'] . ' року до ' . $print ['to'] . '.</p>
<table width="100%" border="0">
  <tr>
    <td width="50%" valign="top">Відвідань: ' . $print ['visits_int'] . '.<br>
Прочитано книг: ' . $print ['reads_int'] . '.<br>
Завантажено на flash-носії: ' . $print ['onflash_int'] . '. </td>
    <td valign="top">Всього книг у базі на ' . $print ['to'] . ': ' . $print ['books_int'] . '.<br>
Літуратури: ' . $print ['lits_int'] . '.<br>
Мультимедіа (відео/аудио): ' . $print ['multimedia_int'] . '.<br>
Додано книг: ' . $print ['adds_int'] . '.<br>
Видалено книг: ' . $print ['dels_int'] . '. </td>
  </tr>
</table>
<h3 align="center"> Список літератури:</h3>
		';
		
		$coms = $core_api->getcommissions ();
		
		foreach ( $coms as $com ) {
			$courses = $core_api->getcourses_id ( $com ['id'] );
			$i = 0;
			$sql_where = "";
			foreach ( $courses as $item ) {
				if (isset ( $i )) {
					$sql_where .= " course=" . $item ['id'];
					unset ( $i );
				} else {
					$sql_where .= " OR course=" . $item ['id'];
				}
			}
			$recordSet = &$db->Execute ( "select * from books WHERE (" . $sql_where . ") and cheked=1 ORDER BY course, autor, name" );
			if (! $recordSet)
				core_error::db_exec_error ( $db->ErrorMsg () );
			
			echo '<p><b>Комісія</b>: ' . $com ['name'] . '<br><b>Книг</b>: ' . $recordSet->RecordCount () . '</p>
	<table width="100%" border="1" cellspacing="0" bordercolor="#666666">
	  <tr>
	      <th scope="col">Назва</th>
	          <th scope="col">Автор</th>
	              <th scope="col">Предмет</th>
	                </tr>';
			
			while ( ! $recordSet->EOF ) {
				$cour_id = $recordSet->fields ['course'];
				$cour = $courses [$cour_id];
				echo '<tr>
	            							        <td>' . $recordSet->fields ['name'] . ' &nbsp; </td>
	            							            <td>' . $recordSet->fields ['autor'] . ' &nbsp; </td>
	            							                <td>' . $cour ['name'] . ' &nbsp; </td>
	            							                  </tr>';
				$recordSet->MoveNext ();
			}
			echo '</table>';
		}
		echo '
			<p>&nbsp;</p>
<table width="100%" border="0">
  <tr>
    <td width="33%" align="center"><p>Завідувач бібліотеки<br>
    ' . $fio . '    </p></td>
    <td align="center">_____________________</td>
    <td width="33%" align="center">' . date ( 'd.m.Y', time () ) . '</td>
  </tr>
</table>
</body>
</html>';
		die ();
	
	}
}