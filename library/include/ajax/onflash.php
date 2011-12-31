<?php
header ( 'Content-type: text/html; charset=utf-8' );

global $user, $db;

$userid = $user ['id'];
$ses = $user ['sesid_base'];

if (isset ( $_GET ['id'] ) and ($_GET ['id'] > 0)) {
	
	$id = $_GET ['id'];
	$sql = "insert into onflash (`book`,`user`,`sesid`) values (" . $db->qstr ( $id ) . ",'$userid','$ses')";
	
	if ($db->Execute ( $sql ) === false) {
		core_error::db_insert_error ( $db->ErrorMsg () );
	}
}
if (isset ( $_GET ['del'] )) {
	$sql = "delete from `onflash` where id=" . $db->qstr ( $_GET ['del'] );
	if ($db->Execute ( $sql ) === false) {
		core_error::db_delete_error ( $db->ErrorMsg () );
	} else {
		$inform = "Файл видалений.";
	}
}

?>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}

.onflash_bg {
	height: 100%;
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	background: url(/images/onflash_bg.png);
	display: none;
	/*z-index:999999;*/
}

.onflash_content {
	width: 100% auto;
	padding: 10px;
	background: #d3d3d3;
	opacity: 1;
	-moz-opacity: 1; /* для старых браузеров на основе Gecko */
	filter: alpha(opacity =             100); /* Для IE6&7 */
}

#onflash_scrolltext {
	width: 100% auto;
	height: 300px;
	padding: 0 0 0 10px;
	overflow: hidden;
	display: block;
}
-->
</style>
<script type="text/javascript" src="./js/jScrollPane.js"></script>
<script type="text/javascript" src="./js/jquery.mousewheel.min.js"></script>
<link rel="stylesheet" type="text/css" media="all"
	href="./css/jScrollPane.css" />
<script type="text/javascript">
$('.onflash_bg').fadeIn("fast");
//$('.onflash_content').fadeIn("fast");
			$(function()
			{
				$('#onflash_scrolltext').jScrollPane({showArrows:true, scrollbarWidth:19, dragMaxHeight:32});
			});
			

$('.book_row').corner();
$('.onflash_content').corner();

function onflash_del(id){
    com1 = 'index.php?do=ajax&AJAX=true&&f=onflash&del=' + id;
	$('#temp_abs').load(com1);
 }
		</script>
<div class="onflash_bg">
<table width="1169" border="0" align="center" cellpadding="5"
	cellspacing="0">
	<tr>
		<td>
		<div class="onflash_content">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="50%"><img src="images/Buy_64.png" width="64"
							height="64" hspace="10" vspace="10" align="middle" />Відправка на
						Flash</td>
						<td align="right" onclick="$('.onflash_bg').fadeOut('fast');">Додати
						ще<img src="images/Right_64.png" width="64" height="64"
							hspace="10" vspace="10" align="middle" /></td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td>
				<div id="onflash_scrolltext">
          <?
										$recordSet = &$db->Execute ( 'select onflash.book, onflash.id AS oid, books.name, image, books.id, autor, books.com, course FROM onflash LEFT JOIN books ON books.id=onflash.book WHERE unicid = 0 AND sesid=' . $db->qstr ( $ses ) );
										
										if (! $recordSet)
											core_error::db_exec_error ( $db->ErrorMsg () );
										else {
											while ( ! $recordSet->EOF ) {
												
												if ($recordSet->fields ['image'] > 0) {
													$image = "<img src=\"/books/images/" . $recordSet->fields ['image'] . ".png\" width=\"100\" height=\"100\" hspace=\"0\" vspace=\"0\" />";
												} else {
													$image = "<img src=\"/images/no_face.png\" width=\"100\" height=\"100\" hspace=\"0\" vspace=\"0\" />";
												}
												if (strlen($recordSet->fields ['autor']) > 0) {
													$autor = $recordSet->fields ['autor'];
												} else {
													$autor = "---";
												}
												
												echo "<div class=\"book_row\">
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
                </table></td>
                <td width=\"100\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\0\">
                  <tr>
                    <td align=\"center\" onclick=\"onflash_del(" . $recordSet->fields ['oid'] . ")\"><img src=\"/images/del.png\" alt=\"\" hspace=\"0\" vspace=\"0\" /><br/>
                      <span class=\"f_books_row\">Видалити</span></td>
                  </tr>
                </table></td>
              </tr>
            </table>
          </div><br>\n\n";
												
												$recordSet->MoveNext ();
											}
										}
										
										?>

          
          </div>
				</td>
			</tr>
			<tr>
				<td align="center"><br>
				Ваш унікальний номер для запису:<br>
				<span style="font-size: 48px; height: 30px;"><?
				echo $userid;
				?></span><br>
				&nbsp;<span style="text-decoration: underline">Назвіть його
				адміністратору бібліотеки, щоб записати літературу на флеш.</span><br>
				</td>
			</tr>
		</table>
		</div>
		</td>
	</tr>
</table>

</div>
