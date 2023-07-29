<?php

require 'SupabasePHP.php';

$apiUrl = 'https://xxxx.supabase.co';
$apiKey = '';

$SupabasePHP = new SupabasePHP($apiUrl, $apiKey);

$tableName = 'tableName';
$data = array(
    'column' => 'Hello, World!'
);

$result = $SupabasePHP->add($tableName, $data);
print_r($result);
