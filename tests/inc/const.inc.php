<?php
define('IGT_VERSION', '1.4.5');
define('IGT_TIMESTAMP', '1122210319');
define('DB_DRIVER_MYSQL', 'mysql');
define('DB_DRIVER_POSTGRESQL', 'postgres');
define('DB_DRIVER_MSSQL_ODBC', 'odbc_mssql');
define('DB_DRIVER_MSSQL', 'mssql');
define('DB_DRIVER_ORACLE', 'oci8');
  
$g_db_connectionsettings = array(
	DB_DRIVER_MYSQL => array(
 'dns' => DB_DRIVER_MYSQL,
 'server' => "%2\$s",
 ),
	DB_DRIVER_POSTGRESQL => array(
 'dns' => DB_DRIVER_POSTGRESQL,
 'server' => "%2\$s",
 ),
	DB_DRIVER_MSSQL_ODBC => array(
 'dns' => DB_DRIVER_MSSQL_ODBC,
 'server' => "Driver={SQL Server};Server=%2\$s;Database=%5\$s;",
 ),
	DB_DRIVER_MSSQL => array(
 'dns' => DB_DRIVER_MSSQL,
 'server' => "%2\$s",
 ),
	DB_DRIVER_ORACLE => array(
 'dns' => DB_DRIVER_ORACLE,
 'server' => "%2\$s",
 ),
);
define('IGT_DB_DEBUG_MODE', false);
define('QUESTION_TYPE_MULTIPLECHOICE', 0);
define('QUESTION_TYPE_TRUEFALSE', 1);
define('QUESTION_TYPE_MULTIPLEANSWER', 2);
define('QUESTION_TYPE_FILLINTHEBLANK', 3);
define('QUESTION_TYPE_ESSAY', 4);
define('QUESTION_TYPE_RANDOM', 5);
define('QUESTION_TYPE_COUNT', 6);
define('QUESTION_TYPE_MULTIPLEANSWER_BREAK', ', ');
define('REQUIRED_FIELD_MARK', '<font color="#800000">*</font>');
define('TEST_STATE_TESTINTRO', 1);
define('TEST_STATE_QINTRO', 2);
define('TEST_STATE_QSHOW', 3);
define('TEST_STATE_QFEEDBACK', 4);
define('TEST_STATE_TFEEDBACK', 5);
define('TEST_STATE_TRESULTS', 6);
define('MAX_ANSWER_COUNT', 20);
define('MAX_STATS_ANSWERS_FILLINTHEBLANK', 20);
define('MAX_UNSIGNED_INT', 2000000000);
define('SYSTEM_GROUP_ADMIN', 1); 
define('SYSTEM_GROUP_ADMIN_USERID', 1); 
define('SYSTEM_GROUP_GUEST_USERID', 2); 
define('SYSTEM_GROUP_INSTRUCTOR', 2); 
define('SYSTEM_GROUP_OPERATOR', 3); 
define('SYSTEM_GROUP_USER', 19); 
define('SYSTEM_GROUP_GUEST', 20); 
define('DEFAULT_GROUP', 19); 
define('SYSTEM_ETEMPLATES_REGISTRATION_INDEX', 2);
define('SYSTEM_ETEMPLATES_LOSTPASSWORD_INDEX', 50);
define('SYSTEM_GROUP_MAX_INDEX', 20);
define('SYSTEM_USER_MAX_INDEX', 2);
define('SYSTEM_ETEMPLATES_MAX_INDEX', 50);
define('SYSTEM_RTEMPLATES_MAX_INDEX', 1);
define('SYSTEM_SUBJECTS_MAX_INDEX', 1);
define('SYSTEM_GRADES_MAX_INDEX', 1);
define('DEFAULT_PASSWORD_LENGTH', 8);
define('DEFAULT_ANSWER_COUNT', 5);
define('SYSTEM_SESSION_ID', 'IGTSESSID');
define('SYSTEM_ARRAY_ITEM_SEPARATOR', ',');
define('SYSTEM_TRUNCATED_LENGTH_LONG', 255);
define('ETEMPLATE_TAG_USERNAME', '[USERNAME]');
define('ETEMPLATE_TAG_USER_PASSWORD', '[USER_PASSWORD]');
define('ETEMPLATE_TAG_USER_EMAIL', '[USER_EMAIL]');
define('ETEMPLATE_TAG_USER_TITLE', '[USER_TITLE]');
define('ETEMPLATE_TAG_USER_FIRST_NAME', '[USER_FIRST_NAME]');
define('ETEMPLATE_TAG_USER_LAST_NAME', '[USER_LAST_NAME]');
define('ETEMPLATE_TAG_USER_MIDDLE_NAME', '[USER_MIDDLE_NAME]');
define('ETEMPLATE_TAG_USER_ADDRESS', '[USER_ADDRESS]');
define('ETEMPLATE_TAG_USER_CITY', '[USER_CITY]');
define('ETEMPLATE_TAG_USER_STATE', '[USER_STATE]');
define('ETEMPLATE_TAG_USER_ZIP', '[USER_ZIP]');
define('ETEMPLATE_TAG_USER_COUNTRY', '[USER_COUNTRY]');
define('ETEMPLATE_TAG_USER_PHONE', '[USER_PHONE]');
define('ETEMPLATE_TAG_USER_FAX', '[USER_FAX]');
define('ETEMPLATE_TAG_USER_MOBILE', '[USER_MOBILE]');
define('ETEMPLATE_TAG_USER_PAGER', '[USER_PAGER]');
define('ETEMPLATE_TAG_USER_IPPHONE', '[USER_IPPHONE]');
define('ETEMPLATE_TAG_USER_WEBPAGE', '[USER_WEBPAGE]');
define('ETEMPLATE_TAG_USER_ICQ', '[USER_ICQ]');
define('ETEMPLATE_TAG_USER_MSN', '[USER_MSN]');
define('ETEMPLATE_TAG_USER_AOL', '[USER_AOL]');
define('ETEMPLATE_TAG_USER_GENDER', '[USER_GENDER]');
define('ETEMPLATE_TAG_USER_BIRTHDAY', '[USER_BIRTHDAY]');
define('ETEMPLATE_TAG_USER_HUSBANDWIFE', '[USER_HUSBANDWIFE]');
define('ETEMPLATE_TAG_USER_CHILDREN', '[USER_CHILDREN]');
define('ETEMPLATE_TAG_USER_TRAINER', '[USER_TRAINER]');
define('ETEMPLATE_TAG_USER_PHOTO', '[USER_PHOTO]');
define('ETEMPLATE_TAG_USER_COMPANY', '[USER_COMPANY]');
define('ETEMPLATE_TAG_USER_CPOSITION', '[USER_CPOSITION]');
define('ETEMPLATE_TAG_USER_DEPARTMENT', '[USER_CDEPARTMENT]');
define('ETEMPLATE_TAG_USER_COFFICE', '[USER_COFFICE]');
define('ETEMPLATE_TAG_USER_CADDRESS', '[USER_CADDRESS]');
define('ETEMPLATE_TAG_USER_CCITY', '[USER_CCITY]');
define('ETEMPLATE_TAG_USER_CSTATE', '[USER_CSTATE]');
define('ETEMPLATE_TAG_USER_CZIP', '[USER_CZIP]');
define('ETEMPLATE_TAG_USER_CCOUNTRY', '[USER_CCOUNTRY]');
define('ETEMPLATE_TAG_USER_CPHONE', '[USER_CPHONE]');
define('ETEMPLATE_TAG_USER_CFAX', '[USER_CFAX]');
define('ETEMPLATE_TAG_USER_CMOBILE', '[USER_CMOBILE]');
define('ETEMPLATE_TAG_USER_CPAGER', '[USER_CPAGER]');
define('ETEMPLATE_TAG_USER_CIPPHONE', '[USER_CIPPHONE]');
define('ETEMPLATE_TAG_USER_CWEBPAGE', '[USER_CWEBPAGE]');
define('ETEMPLATE_TAG_USER_CPHOTO', '[USER_CPHOTO]');
define('ETEMPLATE_TAG_USER_USERFIELD1', '[USER_USERFIELD1]');
define('ETEMPLATE_TAG_USER_USERFIELD2', '[USER_USERFIELD2]');
define('ETEMPLATE_TAG_USER_USERFIELD3', '[USER_USERFIELD3]');
define('ETEMPLATE_TAG_USER_USERFIELD4', '[USER_USERFIELD4]');
define('ETEMPLATE_TAG_USER_USERFIELD5', '[USER_USERFIELD5]');
define('ETEMPLATE_TAG_USER_USERFIELD6', '[USER_USERFIELD6]');
define('ETEMPLATE_TAG_USER_USERFIELD7', '[USER_USERFIELD7]');
define('ETEMPLATE_TAG_USER_USERFIELD8', '[USER_USERFIELD8]');
define('ETEMPLATE_TAG_USER_USERFIELD9', '[USER_USERFIELD9]');
define('ETEMPLATE_TAG_USER_USERFIELD10', '[USER_USERFIELD10]');
define('ETEMPLATE_TAG_TEST_NAME', '[TEST_NAME]');
define('ETEMPLATE_TAG_RESULT_DATE', '[RESULT_DATE]');
define('ETEMPLATE_TAG_RESULT_TIME_SPENT', '[RESULT_TIME_SPENT]');
define('ETEMPLATE_TAG_RESULT_TIME_EXCEEDED', '[RESULT_TIME_EXCEEDED]');
define('ETEMPLATE_TAG_RESULT_POINTS_SCORED', '[RESULT_POINTS_SCORED]');
define('ETEMPLATE_TAG_RESULT_POINTS_POSSIBLE', '[RESULT_POINTS_POSSIBLE]');
define('ETEMPLATE_TAG_RESULT_PERCENTS', '[RESULT_PERCENTS]');
define('ETEMPLATE_TAG_RESULT_GRADE', '[RESULT_GRADE]');
define('ETEMPLATE_TAG_RESULT_DETAILED_1', '[RESULT_DETAILED_1]');
define('ETEMPLATE_TAG_RESULT_DETAILED_2', '[RESULT_DETAILED_2]');
define('CONFIG_igttimestamp', 1);
define('CONFIG_igtversion', 2);
define('CONFIG_can_register', 3);
define('CONFIG_upon_registration', 54);
define('CONFIG_reg_intro', 4);
define('CONFIG_reg_username', 5);
define('CONFIG_reg_password', 6);
define('CONFIG_reg_email', 7);
define('CONFIG_reg_title', 55); 
define('CONFIG_reg_firstname', 8);
define('CONFIG_reg_lastname', 9);
define('CONFIG_reg_middlename', 10);
define('CONFIG_reg_address', 11);
define('CONFIG_reg_city', 12);
define('CONFIG_reg_state', 13);
define('CONFIG_reg_zip', 14);
define('CONFIG_reg_country', 15);
define('CONFIG_reg_phone', 16);
define('CONFIG_reg_fax', 17);
define('CONFIG_reg_mobile', 18);
define('CONFIG_reg_pager', 19);
define('CONFIG_reg_ipphone', 20);
define('CONFIG_reg_webpage', 21);
define('CONFIG_reg_icq', 22);
define('CONFIG_reg_msn', 23);
define('CONFIG_reg_aol', 56); 
define('CONFIG_reg_gender', 24);
define('CONFIG_reg_birthday', 25);
define('CONFIG_reg_husbandwife', 57); 
define('CONFIG_reg_children', 58); 
define('CONFIG_reg_trainer', 42);
define('CONFIG_reg_photo', 26);
define('CONFIG_reg_company', 27);
define('CONFIG_reg_cposition', 28);
define('CONFIG_reg_department', 29);
define('CONFIG_reg_coffice', 30);
define('CONFIG_reg_caddress', 31);
define('CONFIG_reg_ccity', 32);
define('CONFIG_reg_cstate', 33);
define('CONFIG_reg_czip', 34);
define('CONFIG_reg_ccountry', 35);
define('CONFIG_reg_cphone', 36);
define('CONFIG_reg_cfax', 37);
define('CONFIG_reg_cmobile', 38);
define('CONFIG_reg_cpager', 39);
define('CONFIG_reg_cipphone', 40);
define('CONFIG_reg_cwebpage', 41);
define('CONFIG_reg_cphoto', 59); 
define('CONFIG_reg_userfield1', 43);
define('CONFIG_reg_caption_userfield1', 44);
define('CONFIG_reg_type_userfield1', 72);
define('CONFIG_reg_values_userfield1', 73);
define('CONFIG_reg_userfield2', 45);
define('CONFIG_reg_caption_userfield2', 46);
define('CONFIG_reg_type_userfield2', 74);
define('CONFIG_reg_values_userfield2', 75);
define('CONFIG_reg_userfield3', 47);
define('CONFIG_reg_caption_userfield3', 48);
define('CONFIG_reg_type_userfield3', 76);
define('CONFIG_reg_values_userfield3', 77);
define('CONFIG_reg_userfield4', 49);
define('CONFIG_reg_caption_userfield4', 50);
define('CONFIG_reg_type_userfield4', 78);
define('CONFIG_reg_values_userfield4', 79);
define('CONFIG_reg_userfield5', 60);
define('CONFIG_reg_caption_userfield5', 61);
define('CONFIG_reg_type_userfield5', 80);
define('CONFIG_reg_values_userfield5', 81);
define('CONFIG_reg_userfield6', 62);
define('CONFIG_reg_caption_userfield6', 63);
define('CONFIG_reg_type_userfield6', 82);
define('CONFIG_reg_values_userfield6', 83);
define('CONFIG_reg_userfield7', 64);
define('CONFIG_reg_caption_userfield7', 65);
define('CONFIG_reg_type_userfield7', 84);
define('CONFIG_reg_values_userfield7', 85);
define('CONFIG_reg_userfield8', 66);
define('CONFIG_reg_caption_userfield8', 67);
define('CONFIG_reg_type_userfield8', 86);
define('CONFIG_reg_values_userfield8', 87);
define('CONFIG_reg_userfield9', 68);
define('CONFIG_reg_caption_userfield9', 69);
define('CONFIG_reg_type_userfield9', 88);
define('CONFIG_reg_values_userfield9', 89);
define('CONFIG_reg_userfield10', 70);
define('CONFIG_reg_caption_userfield10', 71);
define('CONFIG_reg_type_userfield10', 90);
define('CONFIG_reg_values_userfield10', 91);
define('CONFIG_list_length', 51);
define('CONFIG_store_logs', 52);
define('CONFIG_editor_type', 53);
define('CONFIG_CONST_donotshow', 0);
define('CONFIG_CONST_donotshow_autogenerate', 1);
define('CONFIG_CONST_show_donotrequire', 2);
define('CONFIG_CONST_show_autogenerate', 3);
define('CONFIG_CONST_show_require', 4);
define('CONFIG_CONST_type_singlelinetext', 0);
define('CONFIG_CONST_type_multilinetext', 1);
define('CONFIG_CONST_type_dropdownlist', 2);
define('CONFIG_CONST_noeditor', 0);
define('CONFIG_CONST_htmlareaeditor', 1);
define('CONFIG_CONST_iseditor', 2);
define('CONFIG_CONST_iseditor2', 3);
define('CONFIG_CONST_ckeditor', 4);
define('CONFIG_CONST_ckeditor_question', 5);
define('IGT_EMAIL_AGENT_NAME', 'X-Mailer: Email');
define('IGT_USERNAME_TEMPLATE', 'c%07d');
define('IGT_PASSWORD_LENGTH', 8);
define('INSTALL_MIN_PHP_VERSION', '4.0.3');
?>
