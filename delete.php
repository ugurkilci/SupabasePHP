<?php

// Import the SupabasePHP class
require 'SupabasePHP.php';

// Supabase API URL and API key
$apiUrl = 'https://xxx.supabase.co';
$apiKey = 'xxx';

// Create a new instance of the SupabasePHP class
$SupabasePHP = new SupabasePHP($apiUrl, $apiKey);

// Table name to delete
$tableName = 'tableName';

// Filter criteria to determine which rows to delete
$where = array(
    'id' => 7
);

// Call the delete method from the SupabasePHP class to perform the delete operation
$result = $SupabasePHP->delete($tableName, $where);

// Print the result of the delete operation
print_r($result);
