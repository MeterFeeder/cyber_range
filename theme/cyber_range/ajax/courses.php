<?php

require_once(__DIR__ . '/../../../config.php');
require_login();
global $DB, $USER;

$sql  = "SELECT * FROM mdl_course WHERE 1";
$records = $DB->get_records_sql($sql);
header('content-type: application/json; charset=utf-8');
echo json_encode($records);