<?php
 error_reporting(E_ALL);
 ini_set("display_errors", 1);
// DO NOT ADD ANYTHING TO THIS FILE!!

// This is a catch-all file for your project. You can change
// some of the values here, which are going to have affect
// on your project

// AgileProject - change to your own API name.
// agile_project - this is realm. It should be unique per-project
// jui - this is theme. Keep it jui unless you want to make your own theme

include 'atk4/loader.php';
$api=new Frontend('mdm_atk','jui');
$api->main();

/*
$api->dbConnect();
$api->db()->dsql()->table('page')->field('*')->do_getAllHash();
*/

?>
