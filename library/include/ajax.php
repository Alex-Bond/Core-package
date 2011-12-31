<?php
global $do, $f;
if(isset($_GET['com']) && isset($_GET['showcourses'])){
    include ("./include/ajax/show_predm.php");
}
if($do=="ajax"){
    if($f=='onflash'){
    include ("./include/ajax/onflash.php");
    }
    
    if($f=='litadd'){
    include ("./include/ajax/admin/add.php");
    }
    if($f=='add'){
    include ("./include/ajax/admin/ajaxadd.php");
    }
    if($f=='edit_comcour'){
    include ("./include/ajax/admin/edit_comcour.php");
    }
	if($f=='get_courses'){
    include ("./include/ajax/admin/get_courses.php");
    }
    if($f=='stats'){
    	switch ($_GET['sf']){
    		case 'visits': lib_stats::visitors_csv(); break;
    		case 'read': lib_stats::read_csv(); break;
    		case 'get_students': lib_stats::get_students(); break;
    		default: break;
    	}
    }
    
    die();
}