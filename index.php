<?php

$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$target = ($basePath === '' ? '' : $basePath) . '/public/';

header('Location: ' . $target, true, 302);
exit;
