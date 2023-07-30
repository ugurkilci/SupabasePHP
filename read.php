<?php

// Import the SupabasePHP class
require 'SupabasePHP.php';

// Supabase API URL and API key
$apiUrl = 'https://xxx.supabase.co';
$apiKey = 'xxx';

// Create a new instance of the SupabasePHP class
$SupabasePHP = new SupabasePHP($apiUrl, $apiKey);

// Table name to read
$tableName = 'tableName';

// All read
$result = $SupabasePHP->read($tableName);

// Only "id"s read
$result = $SupabasePHP->read($tableName, "id");

// Pagination read 0...9
$result = $SupabasePHP->read($tableName, "id", "0-9");

// Print the result of the read operation
print_r($result);
