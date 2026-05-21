<?php
require 'public/index.php';
$db = \Config\Database::connect();
$builder = $db->table('department_content');
$query = $builder->get();
foreach ($query->getResult() as $row) {
    if (stripos($row->dept_name, 'Information') !== false || stripos($row->dept_name, 'CIO') !== false) {
        echo "Found: " . $row->dept_name . "\n";
    }
}
?>
