<?php
// Test AJAX endpoints
echo "<h2>AJAX Endpoints Test</h2>";

// Test getdepartments endpoint
echo "<h3>Testing getdepartments endpoint:</h3>";
$url = 'http://localhost/websitebinan/getdepartments';
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'Content-Type: application/json'
    ]
]);

try {
    $response = file_get_contents($url, false, $context);
    if ($response !== false) {
        $data = json_decode($response, true);
        echo "✅ getdepartments endpoint working<br>";
        echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "<br><br>";
    } else {
        echo "❌ getdepartments endpoint failed<br><br>";
    }
} catch (Exception $e) {
    echo "❌ Error testing getdepartments: " . $e->getMessage() . "<br><br>";
}

// Test getalljobs endpoint
echo "<h3>Testing getalljobs endpoint:</h3>";
$url = 'http://localhost/websitebinan/getalljobs';

try {
    $response = file_get_contents($url, false, $context);
    if ($response !== false) {
        $data = json_decode($response, true);
        echo "✅ getalljobs endpoint working<br>";
        echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "<br><br>";
    } else {
        echo "❌ getalljobs endpoint failed<br><br>";
    }
} catch (Exception $e) {
    echo "❌ Error testing getalljobs: " . $e->getMessage() . "<br><br>";
}

// Test admin get_jobs endpoint
echo "<h3>Testing admin get_jobs endpoint:</h3>";
$url = 'http://localhost/websitebinan/admin/ajax/get_jobs';
$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => ''
    ]
]);

try {
    $response = file_get_contents($url, false, $context);
    if ($response !== false) {
        $data = json_decode($response, true);
        echo "✅ admin get_jobs endpoint working<br>";
        echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "<br><br>";
    } else {
        echo "❌ admin get_jobs endpoint failed<br><br>";
    }
} catch (Exception $e) {
    echo "❌ Error testing admin get_jobs: " . $e->getMessage() . "<br><br>";
}
?> 