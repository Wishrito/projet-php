<?php
if (!defined('ACCESS_ALLOWED')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied.');
}
session_start();

header('Location: index.php');
exit();