<?php
header ( 'Content-type: text/json; charset=utf-8' );
global $core_api;
if ($_GET ['com'] == 1) {
	$out ['course_text'] = "<option value=\"1\">Тимчасовий</option>";
} else {
	$gr = $core_api->getcourses_id ( $_GET ['com'] );
	foreach ( $gr as $item ) {
		$out ['course_text'] .= "<option value=\"" . $item ['id'] . "\">" . $item ['name'] . "</option>";
	}
}
echo json_encode ( $out );
?>