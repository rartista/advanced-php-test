<?php
 
require_once('boot.php');

if (!isset($_REQUEST['type'])) {
    ViewRenderer::viewFail('Please specify a type');
}

$export_controller = new ExportController($_REQUEST);
$export_controller->run();
