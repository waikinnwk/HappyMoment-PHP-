<?php
//以下欄位請依照自己需要調整
$googleAccount = 'myname@gmail.com';	//Google 帳號
$googlePassword = 'mypassword';				//Google 密碼
$calendarID = 'myname@gmail.com';	//Google Calendar 的 ID

//這裡透過程式碼動態修改php.ini 的include_path 值，告訴系統Zend Gdata Library 的位置
$slash = (strstr(ini_get('extension_dir'), '/'))?"/":"\\";	//Windows 與Unix 的斜線方向不同，需要考慮到
$includePath = dirname(__FILE__).$slash.'library';
ini_set('include_path', $includePath);	//動態設定php.ini

//這邊是在設定程式把Zend Gdata Library 載入程式碼中
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_AuthSub');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Calendar');
?>