<?php
require_once './Explorer/Explorer.php';
require_once './Explorer/Breadcrumbs.php';
require_once './Explorer/Dir.php';
require_once './Explorer/File.php';

$explorer = new Explorer();
$explorer->run();
