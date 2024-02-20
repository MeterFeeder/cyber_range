<?php

require_once(__DIR__ . '/../../../config.php');
require_login();
global $USER;

echo json_encode($USER);
