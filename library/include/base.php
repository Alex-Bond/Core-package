<?php

/**
 * @author Alex Bond
 * @copyright 2009
 * @version 2.1
 * @name KKZ Library Base
 */

if (! defined ( 'KKZSYSTEM' )) {
	core_error::access_error ();
}
$content = '';

function litview() {
	global $user;
	$file = $_GET ['file'];
	$id = $_GET ['id'];
	
	core_stats::add ( "book", $id, $user );
	
	$file1 = './books/' . $file;
	if (file_exists ( $file1 )) {
		header ( 'Content-Description: File Transfer' );
		header ( 'Content-Type: application/octet-stream' );
		header ( 'Content-Disposition: attachment; filename=' . basename ( $file1 ) );
		header ( 'Content-Transfer-Encoding: binary' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Pragma: public' );
		header ( 'Content-Length: ' . filesize ( $file1 ) );
		ob_clean ();
		flush ();
		readfile ( $file1 );
		exit ();
	} else {
		die ( '404 - Not found' );
	}
}

function home_vis() {
	global $content, $document, $user, $settings, $core_api;
	$document ['title'] = "Обирання розділу";
	$content .= <<< HTML
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td><table width="100%" border="0" cellspacing="5" cellpadding="0">
                <tr>
                  <td width="50%" valign="top" style="border-right:#000 solid 1px; font-size: 18px;">Комісія:<br>
                  
HTML;
	
	$coms = $core_api->getcommissions ();
	foreach ( $coms as $com ) {
		$content .= "<div class=\"com_row\" id=\"com_r" . $com ['id'] . "\" onclick=\"show_predm(" . $com ['id'] . ")\" onmouseover=\"this.style.background='url(/images/predm_row_bg.png) no-repeat center left';\" onmouseout=\"row_off(" . $com ['id'] . ");\"> " . $com ['name'] . "</div>\n";
	}
	
	$content .= <<< HTML
                    
                    
                   <p>&nbsp;</p></td>
                  <td valign="top" class="content_predm">Предмет:<br>
                    <div id="predmety"> </div></td>
                </tr>
              </table></td>
            </tr>
HTML;
	if ($user ['access'] >= $settings ['admin_access']) {
		$content .= "
 <tr>
              <td>
              <div class=\"redactor_title\">Панель керівника</div>
              <div class=\"redactor_content\">
                <table width=\"100%\" border=\"0\" cellspacing=\"5\" cellpadding=\"0\">
  <tr>
    <td width=\"50%\">
    <p><a onclick=\"open_addform();\" style=\"cursor: pointer;\"><img src=\"images/add.png\" width=\"24\" height=\"24\" hspace=\"10\" vspace=\"0\" border=\"0\" align=\"left\" /></a><a onclick=\"open_addform();\" style=\"cursor: pointer;\"> Додати літературу</a></p>";
		if ($user ['access'] >= $settings ['root_access']) {
			$content .= "
    <p><a href=\"?do=base&f=onflash_admin\"><img src=\"images/CD.png\" width=\"24\" height=\"24\" hspace=\"9\" border=\"0\" align=\"left\" /></a><a href=\"?do=base&f=onflash_admin\">На Flash</a></p>
    ";
		}
		$content .= "</td>
    <td valign=\"top\">
    <p><a href=\"?do=stats\"><img src=\"images/stats.png\" alt=\"\" width=\"24\" height=\"24\" hspace=\"10\" vspace=\"0\" border=\"0\" align=\"left\" /></a> <a href=\"?do=stats\">Статистика</a></p>";
		if ($user ['access'] >= $settings ['root_access']) {
			$content .= "
    <p><a href=\"?do=base&f=all_edit\"><img src=\"images/document_edit.png\" width=\"24\" height=\"24\" hspace=\"9\" border=\"0\" align=\"left\" /></a><a href=\"?do=base&f=all_edit\">Масове редагування</a></p>
    ";
		}
		$content .= " </td>
  </tr>
  </table></div>
  
";
	
	}
	$content .= "</td></tr></table>";
	
	return $content;
	return $document;
}

function listiner($course) {
	global $content, $db, $document, $settings, $core_api, $user;
	$document ['title'] = "Обзор книг";
	if ($user ['access'] < $settings ['root_access']) {
		$cheked = "AND `cheked`=1 AND `show`=1";
	} else {
		$cheked = "";
	}
	$cour = $core_api->getcourse ( $course );
	
	$recordSet = &$db->Execute ( "select books.image, books.id, books.name, autor, books.com, file, cheked,onflash,vid, `show` from books WHERE course=" . $db->qstr ( $course ) . " " . $cheked . " ORDER BY books.id DESC" );
	
	if (! $recordSet)
		core_error::db_exec_error ( $db->ErrorMsg () );
	else
		while ( ! $recordSet->EOF ) {
			
			if ($recordSet->fields ['image'] > 0) {
				$image = "<img src=\"/books/thumbs/" . $recordSet->fields ['image'] . "\" width=\"100\" height=\"100\" hspace=\"0\" vspace=\"0\" />";
			} else {
				$image = "<img src=\"/images/no_face.png\" width=\"100\" height=\"100\" hspace=\"0\" vspace=\"0\" />";
			}
			if ($recordSet->fields ['autor']) {
				$autor = $recordSet->fields ['autor'];
			} else {
				$autor = "---";
			}
			if ($recordSet->fields ['cheked'] == 0) {
				$style = "style=\"background: #FF8080;\"";
			} elseif($recordSet->fields ['show'] == 0){
				$style = "style=\"background: #FCDD38\"";
			}else {
				$style = "";
			}
			if ($recordSet->fields ['vid'] == 1 or $recordSet->fields ['vid'] == 0) {
				$open = "<td align=\"center\" onClick=\"window.open('?do=base&f=litview&file=" . $recordSet->fields ['file'] . "&id=" . $recordSet->fields ['id'] . "','_blank');\" style=\"cursor:pointer;\"><img src=\"/images/down.png\" alt=\"\" hspace=\"0\" vspace=\"0\" /><br/>
                      <span class=\"f_books_row\">Відкрити</span></td>";
			} else {
				$open = "<td align=\"center\" onClick=\"video('" . $recordSet->fields ['name'] . "','" . $recordSet->fields ['file'] . "')\" style=\"cursor:pointer;\"><img src=\"/images/movie.png\" alt=\"\" hspace=\"0\" vspace=\"0\" /><br/>
                      <span class=\"f_books_row\">Передивитись</span></td>";
			}
			if ($user ['access'] > $settings ['root_access']) {
				$admin = "<tr>
                    <td align=\"right\"><strong><em>Адміністрування:</em></strong></td>
                    <td>[<a href=\"?do=base&f=litedit&id=" . $recordSet->fields ['id'] . "&com=" . $_GET ['com'] . "\" \">Редагування</a>] [<a href=\"?do=base&f=litdel&id=" . $recordSet->fields ['id'] . "&com=" . $_GET ['com'] . "\" onClick=\"return confirm('Ви дійсно хочете видалити книгу?');\">Видалити</a>]</td>
                  </tr>";
			}
			$content .= "<div class=\"book_row\" " . $style . ">
            <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
              <tr>
                <td width=\"110\"><table width=\"110\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                  <tr>
                    <td height=\"110\" align=\"center\" bgcolor=\"#FFFFFF\">" . $image . "</td>
                  </tr>
                </table></td>
                <td valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
                  <tr>
                    <td colspan=\"2\" class=\"book_title\"><strong>" . $recordSet->fields ['name'] . "</strong></td>
                    </tr>
                  <tr>
                    <td width=\"98\" align=\"right\"><strong><em>Автор:</em></strong></td>
                    <td width=\"896\"><em>" . $autor . "</em></td>
                  </tr>
                  <tr>
                    <td align=\"right\"><strong><em>Розміщення:</em></strong></td>
                    <td><em>" . $cour ['name'] . "</em></td>
                  </tr>
                    ";
			if (isset ( $admin ))
				$content .= $admin;
			
			$content .= "
                  
                </table></td>
                <td width=\"300\">
                
                <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                  <tr>
                  ";
			if ($recordSet->fields ['onflash'] == 1)
				$content .= "
                    <td width=\"50%\" align=\"center\" onClick=\"onflash(" . $recordSet->fields ['id'] . ")\"><img src=\"/images/add.png\" alt=\"\" hspace=\"0\" vspace=\"0\" /><br/>
                      <span class=\"f_books_row\">На Flash</span></td>
                      ";
			$content .= "
                    $open
                  </tr>
                </table>
                
                </td>
              </tr>
            </table>
          </div><br>\n\n";
			
			$recordSet->MoveNext ();
		}
	
	$recordSet->Close ();
	
	return $content;
	return $document;
}
function logout() {
	global $db, $user;
	setcookie ( 'PHPSESSID', null );
	$sql = "delete from `sessions` where name=" . $db->qstr ( $user ['sesuid'] );
	if ($db->Execute ( $sql ) === false) {
		core_error::db_delete_error ( $db->ErrorMsg () );
	}
	header ( "Location:  ./" );
	die();
}

function litadd() {
	global $sql, $content, $document, $db, $user, $settings;
	$document ['title'] = "Додавання літератури";
	$content = "";
	$uploaddir_books = './books/';
	$uploaddir_thumbs = './books/thumbs/';
	
	function make_diferent() {
		list ( $usec, $sec ) = explode ( ' ', microtime () );
		return ( float ) $sec + (( float ) $usec * 100000);
	}
	mt_srand ( make_diferent () );
	
	function my_explode($delim, $str, $lim = 1) {
		if ($lim > - 2)
			return explode ( $delim, $str, abs ( $lim ) );
		
		$lim = - $lim;
		$out = explode ( $delim, $str );
		if ($lim >= count ( $out ))
			return $out;
		
		$out = array_chunk ( $out, count ( $out ) - $lim + 1 );
		
		return array_merge ( array (implode ( $delim, $out [0] ) ), $out [1] );
	}
	$newname = mt_rand ();
	
	if (isset ( $_FILES ['image'] ) && strlen ( $_FILES ['image'] ['name'] ) > 0) {
		$razrer = my_explode ( ".", basename ( $_FILES ['image'] ['name'] ), - 2 );
		$tochka = ".";
		$newname1 = $newname . $tochka . $razrer [1];
		
		if (! move_uploaded_file ( $_FILES ['image'] ['tmp_name'], $uploaddir_thumbs . $newname1 )) {
			$error = "Помилка при записі превью.";
		}
	} else {
		$newname1 = "";
	}
	
	$razrer1 = my_explode ( ".", basename ( $_FILES ['file'] ['name'] ), - 2 );
	$tochka = ".";
	$newname = $newname . $tochka . $razrer1 [1];
	
	if (move_uploaded_file ( $_FILES ['file'] ['tmp_name'], $uploaddir_books . $newname )) {
		$name_add = $_POST ['name'];
		$autor_add = $_POST ['autor'];
		$data_add = date ( 'Y-m-d H:i:s' );
		( int ) $com_add = $_POST ['com'];
		( int ) $course_add = $_POST ['course'];
		( int ) $vid = $_POST ['type'];
		( int ) $onflash = $_POST ['onflash'];
		( int ) $show = $_POST ['show'];
		if ($user ['access'] >= $settings ['root_access']) {
			$cheked = 1;
		} else {
			$cheked = 0;
		}
		if (! $error) {
			
			$newname1 = $db->qstr ( $newname1 );
			$name_add = $db->qstr ( $name_add );
			$autor_add = $db->qstr ( $autor_add );
			$newname = $db->qstr ( $newname );
			
			$sql = "insert into books (`image`,`name`,`autor`,`file`,`data`,`com`,`course`,`vid`,`cheked`,`onflash`,`show`) ";
			
			$sql .= "values ($newname1,$name_add,$autor_add,$newname,NOW(),'$com_add','$course_add','$vid','$cheked','$onflash','$show')";
			
			if ($db->Execute ( $sql ) === false) {
				core_error::db_insert_error ( $db->ErrorMsg () );
			} else {
				core_stats::add('addbook', $db->Insert_ID(), $user);
				$inform = "Лiтература завантажена.";
			}
		}
	
	} else {
		$error = "Помилка при записi файлу.";
	}
	if ($error) {
		$data = "<td align=\"center\" bgcolor=\"#CC0033\">$error</td>";
	} else {
		$data = "<td align=\"center\" bgcolor=\"#00FF33\">$inform</td>";
	}
	$content .= "
  <table width=\"500\" border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">
  <tr>
    <td>Додавання літератури</td>
  </tr>
  <tr>
    $data
  </tr>
  <tr>
    <td align=\"center\"><a href=\"/\">Перейти на головну.</a></td>
  </tr>
</table>
  ";
	
	return $content;
	return $document;
}

function litdel() {
	global $db, $content, $document, $user;
	$document ['title'] = "Видалення літератури";
	$content = "";
	$uploaddir_books = './books/';
	$uploaddir_thumbs = './books/thumbs/';
	
	$recordSet = &$db->Execute ( 'select * from books where id=' . $db->qstr ( $_GET ['id'] ) . ' LIMIT 1' );
	
	if (! $recordSet)
		core_error::db_exec_error ( $db->ErrorMsg () );
	else {
		$recordSet->fields ['file'];
		if (! unlink ( $uploaddir_books . $recordSet->fields ['file'] ))
			core_error::file_delete_error ( $recordSet->fields ['file'] );
		if ($recordSet->fields ['image'] > 0) {
			if (! unlink ( $uploaddir_thumbs . $recordSet->fields ['image'] ))
				core_error::file_delete_error ( $recordSet->fields ['file'] );
		}
		
		$sql = "delete from `books` where id=" . $db->qstr ( $_GET ['id'] );
		if ($db->Execute ( $sql ) === false) {
			core_error::db_delete_error ( $db->ErrorMsg () );
		} else {
			core_stats::add('delbook', $_GET ['id'], $user);
			$inform = "Файл видалений.";
		}
	
	}
	if (isset ( $_GET ['edit_all'] )) {
		$back = "<td align=\"center\" style=\"cursor: pointer;\"><a href=\"/?do=base&f=all_edit&commission=" . $_GET ['edit_all'] . "\">До списку книг</a></td>";
	} else {
		$back = "<td align=\"center\"><a href=\"/?do=base&f=listiner&com=" . $_GET ['com'] . "\">До списку книг</a></td>";
	}
	$content .= "
  <table width=\"500\" border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">
  <tr>
    <td>Видалення літератури</td>
  </tr>
  <tr>
    <td align=\"center\" bgcolor=\"#00FF33\">" . $inform . "</td>
  </tr>
  <tr>
    " . $back . "
  </tr>
</table>
  ";
	
	return $content;
	return $document;
}

function litedit() {
	global $sql, $content, $document, $db, $core_api, $user;
	$document ['title'] = "Редагування лiтератури";
	$content = "";
	( int ) $id = $_GET ['id'];
	
	if ($_POST ['edit']) {
		( int ) $id = $_GET ['id'];
		$name = $_POST ['name'];
		$autor = $_POST ['autor'];
		( int ) $com = $_POST ['com'];
		( int ) $course = $_POST ['course'];
		( int ) $cheked = $_POST ['cheked'];
		( int ) $show = $_POST ['show'];
		( int ) $onflash = $_POST ['onflash'];
		( int ) $type = $_POST ['type'];
		
		$name = $db->qstr ( $name );
		$autor = $db->qstr ( $autor );
		
		$sql1 = "update books set `name`=$name,`autor`=$autor,`com`='$com',`course`='$course',`cheked`='$cheked',`show`='$show',`onflash`='$onflash',`vid`='$type' where id='$id'";
		
		if ($db->Execute ( $sql1 ) === false) {
			core_error::db_update_error ( $db->errorMsg () );
		} else {
			core_stats::add('editbook', $id, $user);
			$inform = "Література оновлена.";
		}
	}
	if ($inform) {
		$content .= "<br />
    <table width=\"300\" border=\"0\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">
  <tr>
    <td align=\"center\" bgcolor=\"#66CC00\" style=\"color:#FFF\"> $inform</td>
  </tr>
</table></br>";
	}
	$recordSet = &$db->Execute ( 'SELECT * FROM `books` WHERE `id` =' . $db->qstr ( $id ) );
	
	if (! $recordSet)
		core_error::db_exec_error ( $db->ErrorMsg () );
	else {
		$name_upd = str_replace ( "\"", "&#34;", $recordSet->fields ['name'] );
		$autor_upd = str_replace ( "\"", "&#34;", $recordSet->fields ['autor'] );
		// Course
		$course = $core_api->getcourse ( $recordSet->fields ['course'] );
		// Commission
		$commission = $core_api->getcommission ( $course ['com'] );
		if ($recordSet->fields ['image'] > 0) {
			$image = $recordSet->fields ['image'];
		} else {
			$image = "---";
		}
		if (isset ( $_GET ['edit_all'] )) {
			$back = "<td align=\"center\" style=\"cursor: pointer;\"><a href=\"/?do=base&f=all_edit&commission=" . $_GET ['edit_all'] . "\">До списку книг</a></td>";
		} else {
			$back = "<td align=\"center\" style=\"cursor: pointer;\"><a href=\"/?do=base&f=listiner&com=" . $recordSet->fields ['course'] . "\">До списку книг</a></td>";
		}
		
		$content .= "
<script type=\"text/javascript\">
function type_change(){
	type = $('#type').val();
	if(type==1){
		$('#filefile').html(\"Файл літератури:\");
		}else{
			$('#filefile').html(\"Файл відео:\");
			}
	}
    
function com_chenge(){
	com1 = 'index.php?do=ajax&AJAX=true&&f=add&com=true';
	$('#comandcour').load(com1);
	}
function course_chenge(){
    com = $('#com').val();
	com1 = 'index.php?do=ajax&AJAX=true&&f=add_courses&com=' + com;
	$('#comandcour').load(com1);
	}
</script>

<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"0\">
  <tr>
    <td>Редагування літератури</td>
  </tr>
  <tr>
    <td>
    
    <form action=\"\" method=\"post\" name=\"editform\">
    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
      <tr>
        <td width=\"145\" align=\"right\">Тип:</td>
        <td width=\"427\"><select name=\"type\" id=\"type\" onchange=\"type_change();\">
          <option value=\"1\">Література</option>
          <option value=\"2\">Відео</option>
        </select></td>
      </tr>
      <tr>
        <td align=\"right\" id=\"filefile\">Файл літератури:</td>
        <td>" . $recordSet->fields ['file'] . "</td>
      </tr>
      <tr>
        <td align=\"right\" id=\"filefile\">Обкладинка (превью):</td>
        <td>" . $image . "</td>
      </tr>
      <tr>
        <td align=\"right\">Назва:</td>
        <td><input name=\"name\" type=\"text\" id=\"name\" size=\"50\" value=\"" . $name_upd . "\"></td>
      </tr>
      <tr>
        <td align=\"right\">Автор:</td>
        <td><input name=\"autor\" type=\"text\" id=\"autor\" size=\"50\" value=\"" . $autor_upd . "\" ></td>
      </tr>
      <tr>
        <td align=\"right\">Комісія:</td>
        <td onclick=\"edit_comcour();\" style=\"cursor:pointer;\" id=\"namecom\">" . $commission ['name'] . " <img src=\"/images/pencil.png\">
          </td>
      </tr>
      <tr>
        <td align=\"right\" >Предмет</td>
        <td id=\"namecour\" onclick=\"edit_comcour();\" style=\"cursor:pointer;\">" . $course ['name'] . " <img src=\"/images/pencil.png\">
         </td>
      </tr>
      <tr>
          <td width=\"200\" align=\"right\">Перевiрений:</td>
          <td><select name=\"cheked\" id=\"cheked\">
          <option value=\"0\">Нi</option>
          <option value=\"1\">Так</option>
        </select></td>
        </tr>
        <tr>
          <td width=\"200\" align=\"right\">Вивiд студенту:</td>
          <td><select name=\"show\" id=\"show\">
          <option value=\"0\">Виключений</option>
          <option value=\"1\">Включений</option>
        </select></td>
        </tr>
        <tr>
          <td width=\"200\" align=\"right\">На Flash:</td>
          <td><select name=\"onflash\" id=\"onflash\">
          <option value=\"0\">Не можливо</option>
          <option value=\"1\">Можливо</option>
        </select></td>
        </tr>
    </table>
    <input name=\"com\" type=\"hidden\" id=\"com\" value=\"" . $recordSet->fields ['com'] . "\" />
     <input name=\"course\" type=\"hidden\" id=\"course\" value=\"" . $recordSet->fields ['course'] . "\" />
     
     <input name=\"edit\" type=\"hidden\" id=\"edit\" value=\"edit\" />
    
    </td>
  </tr>
  <tr>
    <td align=\"center\"><input type=\"submit\" name=\"submit\" id=\"submit\" value=\"Відредагувати\" /></td>
  </tr>
  <tr>
    " . $back . "
  </tr>
</table>
</form>
<div style=\"position:absolute; width:100%; top:0; left:0;\" id=\"comandcour\"></div>
<script language=\"javascript\">

var obj = null;
obj = document.getElementById(\"type\");
obj.value = '" . $recordSet->fields ['vid'] . "';
type_change();

obj = document.getElementById(\"cheked\");
obj.value = '" . $recordSet->fields ['cheked'] . "';

obj = document.getElementById(\"show\");
obj.value = '" . $recordSet->fields ['show'] . "';

obj = document.getElementById(\"onflash\");
obj.value = '" . $recordSet->fields ['onflash'] . "';

</script>
";
	}
	
	return $content;
	return $document;
}

function onflash_admin() {
	global $db, $user, $content, $document;
	
	$document ['title'] = "На Flash";
	
	if (isset($_POST ['uid'])) {
		$id = $_POST ['uid'];
		require_once ("./include/plczip.lib.php");
		$archive = new PclZip ( 'onflash.zip' );
		$archive->create ( '' );
		$recordSet = &$db->Execute ( "select onflash.user, onflash.book, books.id, books.file from onflash LEFT JOIN books ON books.id=onflash.book WHERE onflash.user=" . $db->qstr ( $id ) . " AND onflash.unicid=0" );
		$count =  $recordSet->RecordCount( );
		if (! $recordSet)
			core_error::db_exec_error ( $db->ErrorMsg () );
		else {
			while ( ! $recordSet->EOF ) {
				$archive->add ( './books/' . $recordSet->fields ['file'] );
				$recordSet->MoveNext ();
			}
		}
		
		$id_stat = core_stats::add('onflash', $count, $user, true);
		$sql1 = "update onflash set `unicid`=$id_stat where user='$id' and onflash.unicid=0";
		
		if ($db->Execute ( $sql1 ) === false) {
			core_error::db_update_error ( $db->errorMsg () );
		}
		$content .= "  <table width=\"500\" border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">
  <tr>
    <td>На Flash</td>
  </tr>
  <tr>
    <td align=\"center\" >Завантажити архiв: <a href=\"./onflash.zip\"><b>onflash.zip</b></a></td>
  </tr>
  <tr>
    <td align=\"center\"><a href=\"/?do=base&f=onflash_admin\" style=\"cursor: pointer;\">Повернутися назад</a></td>
  </tr>
</table>";
	} else {
		$content .= "<table width=\"500\" border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">
  <tr>
    <td>На Flash</td>
  </tr>
  <tr><form action=\"\" method=\"post\">
    <td align=\"center\" >Номер: <input name=\"uid\" type=\"text\" id=\"uid\" size=\"50\" /><br /><input type=\"submit\" name=\"submit\" id=\"submit\" value=\"Видати\" /></td>
  </tr></form>
</table>";
	}
	return $content;
	return $document;
}

// GLOBAL EDIT


function edit_all_home() {
	global $content, $core_api, $db, $document;
	$document['title']='Масове редагування';
	
	if (isset ( $_GET ['commission'] ) and $_GET ['commission'] == 1) {
		$coms_out .= '<option value="1" selected="selected">Тимчасова</option>';
	} else {
		$coms_out .= '<option value="1">Тимчасова</option>';
	}
	$coms = $core_api->getcommissions ();
	foreach ( $coms as $item ) {
		if (isset ( $_GET ['commission'] ) and $_GET ['commission'] == $item ['id']) {
			$coms_out .= "<option value=\"" . $item ['id'] . "\" selected=\"selected\">" . $item ['name'] . "</option>";
		} else {
			$coms_out .= "<option value=\"" . $item ['id'] . "\">" . $item ['name'] . "</option>";
		}
	}
	
	if (isset ( $_GET ['commission'] )) {
		
		if ($_GET ['commission'] == 1) {
			$recordSet = &$db->Execute ( "select * from books WHERE course=0 ORDER BY course" );
		} else {
			$courses = $core_api->getcourses_id ( $_GET ['commission'] );
			$i = 0;
			foreach ( $courses as $item ) {
				if (isset ( $i )) {
					$sql_where .= " course=" . $item ['id'];
					unset ( $i );
				} else {
					$sql_where .= " OR course=" . $item ['id'];
				}
			}
			
			$recordSet = &$db->Execute ( "select * from books WHERE " . $sql_where . " ORDER BY course" );
		}
		$i = 0;
		if (! $recordSet)
			core_error::db_exec_error ( $db->ErrorMsg () );
		else
			while ( ! $recordSet->EOF ) {
				$i ++;
				$cour_id = $recordSet->fields ['course'];
				$cour = $courses [$cour_id];
				
				$row_color = "";
				if ($recordSet->fields ['cheked'] == '0') {
					$row_color = 'color: white; background: #660000;';
				}
				if ($recordSet->fields ['show'] == '0') {
					$row_color = 'color: black; background: #ffcc33;';
				}
				if (isset ( $_GET ['inform'] )) {
					$inform = '<table width="100%" border="0" style="background-color: green; color: white; text-align: center;"><tr><td>' . $_GET ['inform'] . '</td></tr></table><br />';
				} else {
					$inform = '';
				}
				
				if ($recordSet->fields ['vid'] == 1 or $recordSet->fields ['vid'] == 0) {
					$open = "<img onClick=\"window.open('?do=base&f=litview&file=" . $recordSet->fields ['file'] . "&id=" . $recordSet->fields ['id'] . "','_blank');\" style=\"cursor:pointer;\" src=\"/images/arrow-270.png\" alt=\"\" hspace=\"0\" vspace=\"0\" />";
				} else {
					$open = "<img onClick=\"video('" . $recordSet->fields ['name'] . "','" . $recordSet->fields ['file'] . "')\" src=\"/images/television.png\" alt=\"\" hspace=\"0\" vspace=\"0\" style=\"cursor:pointer;\" />";
				}
				
				$out .= '<tr style="' . $row_color . '">
      <td width="30" align="center"><input name="id[' . $i . ']" type="checkbox" id="id[' . $i . ']" value="' . $recordSet->fields ['id'] . '" /></td>
      <td>' . $recordSet->fields ['name'] . '</td>
      <td>' . $recordSet->fields ['autor'] . '</td>
      <td>' . $cour ['name'] . '</td>
      <td align="center">
      '.$open.' 
      <a href="/?do=base&f=litedit&id=' . $recordSet->fields ['id'] . '&edit_all=' . $_GET ['commission'] . '"><img src="/images/edit_small.png" width="16" height="16" align="texttop" border="0" /></a>&nbsp;
      <a href="/?do=base&f=litdel&id=' . $recordSet->fields ['id'] . '&edit_all=' . $_GET ['commission'] . '" onClick="return confirm(\'Ви дійсно хочете видалити книгу?\');"><img src="/images/fire-big.png" alt="" width="16" height="16" border="0" /></a>
      </td>
    </tr>';
				$recordSet->MoveNext ();
			}
	} else {
		$books = "";
	}
	$content = '
		
	<form id="commission_form" name="commission_form" method="get" action="">
	<input name="do" type="hidden" id="do" value="base" />
	<input name="f" type="hidden" id="f" value="all_edit" />
  <select name="commission" id="commission" onchange="if(this.value>0){document.forms.commission_form.submit(); return false;}">
    <option value="0">Оберiть комiссiю</option>
    ' . $coms_out . '
  </select>
</form><br />
' . $inform . '
<SCRIPT LANGUAGE="JavaScript">
function Check(crk)
{
if(crk.checked==true){
$("input[type=checkbox]").attr("checked","checked");
}else{
$("input[type=checkbox]").removeAttr("checked");
}
}
</script>
<form id="list_form" name="list_form" method="post" action="">
  <table width="100%" border="1" cellspacing="1">
    <tr>
      <th width="30" align="center" scope="col"><input name="all" type="checkbox" id="all" value="1" onClick="Check(this)" />
      </th>
      <th scope="col">Назва</th>
      <th scope="col">Автор</th>
      <th scope="col">Предмет</th>
      <th width="50" align="center" scope="col">V/E/D</th>
    </tr>
    ' . $out . '
  </table>
  <p align="right">
  <input name="com" type="hidden" id="com" />
  <input name="course" type="hidden" id="course" />
    <select name="todo" id="todo" onchange="if(this.value==\'move\'){$(\'#edit\').dialog(\'open\');} if(this.value==\'del\'){if(confirm(\'Ви дійсно хочете видалити книгу(и)?\')){document.forms.list_form.submit();}}">
      <option value="0">Що зробити:</option>
      <option value="move">Перенести</option>
      <option value="del">Видалити</option>
    </select>
  </p>
</form>
<table width="100%" border="0">
  <tr>
    <td width="50%"><img src="/images/660F0A_dot.gif" width="16" height="16" align="absmiddle" /> - Книга не перевiрена</td>
    <td><a href="/?do=base&f=all_edit&recal=1" style="text-decoration: underline;">Перерахувати базу</a></td>
  </tr>
  <tr>
    <td><img src="/images/ffcc33_dot.gif" width="16" height="16" align="absmiddle" /> - Вивiд книги студенту вiдключений</td>
    <td>&nbsp;</td>
  </tr>
</table>
<div id="edit" title="Перенесення" style="font-size:14px;">
	<form id="form1" name="form1" method="post" action="" onSubmit="$(\'#com\').val($(\'#com_edit\').val()); $(\'#course\').val($(\'#course_edit\').val()); document.forms.list_form.submit();">
  <table width="100%" border="0">
  <tr>
  <td>Комiсiя:</td>
  <td>Предмет:</td>
  </tr>
    <tr>
      <td width="50%">
        <select name="com_edit" size="25" id="com_edit" style="width:100%;" onChange="get_courses()">
          ' . $coms_out . '
      </select></td>
      <td><select name="course_edit" size="25" id="course_edit" style="width:100%;">
      </select></td>
    </tr>
  </table>
  <p align="center">
    <input type="submit" name="edit" id="edit" value="Вiдредагувати" />
  </p>
</form>		
</div>

<script type="text/javascript">
	$("#edit").dialog({
					autoOpen: false,
					width: 600,
					modal: true					
				});
	function get_courses(){
	com = $("#com_edit").val();
			$.getJSON(\'/index.php?do=ajax&f=get_courses&com=\'+com, function(data) {$(\'#course_edit\').html(data.course_text);});
return true;
	}
	get_courses();
</script>
';
	return $content;
}
function edit_all_move() {
	global $content, $db;
	
	$com = $db->qstr ( $_POST ['com'] );
	$course = $db->qstr ( $_POST ['course'] );
	foreach ( $_POST ['id'] as $item ) {
		( int ) $id = $item;
		
		$sql = "update books set `course`=" . $course . ",`com`=" . $com . " where id='$id'";
		
		if ($db->Execute ( $sql ) === false) {
			core_error::db_update_error ( $db->errorMsg () );
		}
	}
	header ( "Location: /?do=base&f=all_edit&commission=" . $_GET ['commission'] . "&inform=Лiтература перенесена" );
	die ();
}

function edit_all_del() {
	global $content, $db;
	
	$uploaddir_books = './books/';
	$uploaddir_thumbs = './books/thumbs/';
	
	foreach ( $_POST ['id'] as $item ) {
		( int ) $id = $item;
		
		$recordSet = &$db->Execute ( 'select * from books where id=' . $id . ' LIMIT 1' );
		
		if (! $recordSet)
			core_error::db_exec_error ( $db->ErrorMsg () );
		else {
			$recordSet->fields ['file'];
			if (! unlink ( $uploaddir_books . $recordSet->fields ['file'] ))
				core_error::file_delete_error ( $recordSet->fields ['file'] );
			if ($recordSet->fields ['image'] > 0) {
				if (! unlink ( $uploaddir_thumbs . $recordSet->fields ['image'] ))
					core_error::file_delete_error ( $recordSet->fields ['file'] );
			}
			
			$sql = "delete from `books` where id=" . $db->qstr ( $_GET ['id'] );
			if ($db->Execute ( $sql ) === false) {
				core_error::db_delete_error ( $db->ErrorMsg () );
			}
		}
	}
	header ( "Location: /?do=base&f=all_edit&commission=" . $_GET ['commission'] . "&inform=Лiтература видалена" );
	die ();
}
function edit_all_recals() {
	global $content, $db, $core_api;
	$courses = $core_api->getcourses_all ();
	
	$i = 0;
	foreach ( $courses as $item ) {
		if (isset ( $i )) {
			$sql_where .= " course!=" . $item ['id'];
			unset ( $i );
		} else {
			$sql_where .= " AND course!=" . $item ['id'];
		}
	}
	
	$sql = "update books set `course`=0,`com`=0 where " . $sql_where;
	
	if ($db->Execute ( $sql ) === false) {
		core_error::db_update_error ( $db->errorMsg () );
	}
	header ( "Location: /?do=base&f=all_edit&commission=1&inform=База перевiрена та оновлена згiдно з центральною базою. Лiтература що без предмету булу перенесана до тимчасової комiсiї." );
	die ();
}