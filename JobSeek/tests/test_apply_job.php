<?php
/**
 * Test script for api/apply_job.php
 * 
 * NOTE: This is a standalone test script demonstrating TDD intent.
 * It simulates a multipart/form-data upload via curl.
 */

$url = 'http://localhost/JobSeek/api/apply_job.php';

// Create a dummy PDF file
$dummy_file = __DIR__ . '/dummy_resume.pdf';
file_put_contents($dummy_file, '%PDF-1.4 dummy pdf content');

// Setup curl
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);

// Create CURLFile
$cfile = new CURLFile($dummy_file, 'application/pdf', 'dummy_resume.pdf');

// Prepare POST data
$post_data = [
    'job_id' => 1,
    'cover_letter' => 'Test cover letter',
    'resume' => $cfile
];

curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($response === false) {
    echo "Curl error: " . curl_error($ch) . "\n";
} else {
    echo "HTTP Status: $http_code\n";
    echo "Response: $response\n";
}

curl_close($ch);

// Clean up
unlink($dummy_file);
?>
