<?php

// Import the SupabasePHP class
require 'SupabasePHP.php';

// Supabase API URL and API key
$apiUrl = 'https://xxx.supabase.co';
$apiKey = 'xxx';

// Create a new instance of the SupabasePHP class
$SupabasePHP = new SupabasePHP($apiUrl, $apiKey);

// Table name to update
$tableName = 'tableName';

// New data to be updated in the table
$datas = array(
    'text' => 'Text: ' . time()
);

// Filter criteria to determine which rows to update
$where = array(
    'id' => 8,
    'user_id' => 1
);

// Call the update method from the SupabasePHP class to perform the update operation
$result = $SupabasePHP->update($tableName, $where, $datas);

// Print the result of the update operation
print_r($result);
