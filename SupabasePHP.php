<?php

// SupabasePHP class for interacting with the Supabase API using PHP
class SupabasePHP {
    // Private properties to store the API URL and API Key
    private $apiUrl;
    private $apiKey;

    // Constructor method to initialize the SupabasePHP object with API URL and API Key
    public function __construct($apiUrl, $apiKey) {
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    // Method to add data to a specific table in the Supabase database
    public function add($table, $data) {
        // Prepare the headers for the HTTP request
        $headers = array(
            'apikey: ' . $this->apiKey,
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json',
            'Prefer: return=minimal'
        );

        // Convert the data to JSON format
        $jsonData = json_encode($data);

        // Initialize cURL session and set the necessary options
        $ch = curl_init($this->apiUrl . '/rest/v1/' . $table);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL session and capture the response
        $response = curl_exec($ch);
        curl_close($ch);

        // Decode the response JSON into an associative array
        $responseData = json_decode($response, true);

        // Check the response for errors and return appropriate messages
        if ($responseData === false) {
            return 'cURL Error: ' . curl_error($ch); // If there was a cURL error
        }elseif (isset($responseData['error'])) {
            return 'Add Data Error: ' . $responseData['error']['message']; // If there was an error, return the error message
        } else {
            return true; // If no specific error or data ID, return true indicating success
        }
    }
}
