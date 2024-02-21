<?php

require_once(__DIR__ . '/../../../config.php');
require_login();
global $DB, $USER;
$courseId = optional_param('id', null, PARAM_INT);
if(isset($courseId)) {
    $sql = "SELECT * FROM {course} WHERE id=?";
    $records = $DB->get_record_sql($sql, array($courseId));    
} else {
    $sql  = "SELECT * FROM {course} WHERE 1";
    $records = $DB->get_records_sql($sql);
}
header('content-type: application/json; charset=utf-8');
echo json_encode($records);