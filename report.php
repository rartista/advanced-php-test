<?php
 
require_once('boot.php');

$report_controller = new ReportController($_REQUEST);
$report_controller->run();