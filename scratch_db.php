<?php
require 'public/index.php';
$db = \Config\Database::connect();
print_r($db->getFieldNames('content_tbl'));
?>
