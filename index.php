<?php
ini_set('display_errors', 1);
error_reporting(~0);
require 'vendor/autoload.php';
require 'Bot.php';

use \Bot\Bot;

$tax = new Bot();

header("Content-Type: application/json");
$ret = $tax->run();

print $ret;
?>
