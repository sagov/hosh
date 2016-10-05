<?php

require_once dirName(__FILE__).'/private/manager/manager.php';

$config = array('url_path' => '/manager.php');

$hoshmanager = new HoshManager($config);
if (isset($_GET['controller'])) {
    $hoshmanager->setController($_GET['controller']);;
}
if (isset($_GET['action'])) {
    $hoshmanager->setAction($_GET['action']);
}
if (isset($_GET['lang'])) {
    $hoshmanager->setLanguage($_GET['lang']);
}

$hoshmanager->run();