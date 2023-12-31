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

    /**
     * Method to add data to a specific table in the Supabase database.
     *
     * @param string $table The name of the table where data will be added.
     * @param array $data An associative array containing the data to be added to the table.
     * @return mixed Returns true if data is successfully added, or an error message on failure.
     */
    public function add($table, $data) {
        // Prepare the headers for the HTTP request.
        $headers = array(
            'apikey: ' . $this->apiKey,
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json',
            'Prefer: return=minimal'
        );

        // Convert the data to JSON format.
        $jsonData = json_encode($data);

        // Initialize cURL session and set the necessary options.
        $ch = curl_init($this->apiUrl . '/rest/v1/' . $table);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL session and capture the response.
        $response = curl_exec($ch);
        curl_close($ch);

        // Decode the response JSON into an associative array.
        $responseData = json_decode($response, true);

        // Check the response for errors and return appropriate messages.
        if ($responseData === false) {
            return 'cURL Error: ' . curl_error($ch); // If there was a cURL error.
        } elseif (isset($responseData['error'])) {
            return 'Add Data Error: ' . $responseData['error']['message']; // If there was an error, return the error message.
        } else {
            return true; // If no specific error or data ID, return true indicating success.
        }
    }

    /**
     * Update data in the specified table using the given criteria.
     *
     * @param string $tableName The name of the table to update.
     * @param array $where An associative array specifying the filter criteria for the update operation.
     * @param array $data An associative array containing the new data to be updated in the table.
     * @param string $filter [Optional] The filter type to use for the update operation (default is "eq").
     * @return string The response from the API after performing the update operation.
     */
    public function update($tableName, $where, $data, $filter = "eq") {
        // Create a new array for building "eq" (equal) filter parameters for the update operation.
        $queryParams = array();
        foreach ($where as $key => $value) {
            $queryParams[$key] = $filter . '.' . $value;
        }
        
        // Convert the array to query string parameters.
        $queryString = http_build_query($queryParams);
        
        // Build the API URL for the update operation with the provided table name and query parameters.
        $url = $this->apiUrl . '/rest/v1/' . $tableName . '?' . $queryString;
        
        // Prepare the header information for the API request.
        $headers = array(
            'apikey: ' . $this->apiKey,
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json',
            'Prefer: return=minimal',
        );

        // Initialize cURL session and set options for the request.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Perform the cURL request to the API and capture the response.
        $response = curl_exec($ch);
        curl_close($ch);

        // Return the API response.
        return $response;
    }

    /**
     * Delete data from the specified table using the given criteria.
     *
     * @param string $tableName The name of the table from which data will be deleted.
     * @param array $where An associative array specifying the filter criteria for the delete operation.
     * @param string $filter [Optional] The filter type to use for the delete operation (default is "eq").
     * @return string The response from the API after performing the delete operation.
     */
    public function delete($tableName, $where, $filter = "eq") {
        // Create a new array for building "eq" (equal) filter parameters for the delete operation.
        $queryParams = array();
        foreach ($where as $key => $value) {
            $queryParams[$key] = $filter . '.' . $value;
        }

        // Convert the array to query string parameters.
        $queryString = http_build_query($queryParams);

        // Build the API URL for the delete operation with the provided table name and query parameters.
        $url = $this->apiUrl . '/rest/v1/' . $tableName . '?' . $queryString;

        // Prepare the header information for the API request.
        $headers = array(
            'apikey: ' . $this->apiKey,
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json',
            'Prefer: return=minimal',
        );

        // Initialize cURL session and set options for the request.
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Perform the cURL request to the API and capture the response.
        $response = curl_exec($ch);
        curl_close($ch);

        // Return the API response.
        return $response;
    }

    /**
     * Read data from the specified table using the given criteria.
     *
     * @param string $tableName The name of the table from which data will be read.
     * @param string $where [Optional] The query parameters for filtering the data (default is "*").
     * @param string $range [Optional] The range header to limit the number of results (e.g., "0-9", "10-19").
     * @return string The response from the API after performing the read operation.
     */
    public function read($tableName, $where = "*", $range = "") {
        $url = $this->apiUrl . '/rest/v1/' . $tableName . '?select=' . $where;

        // If the $where parameter does not start with "*", it means specific query parameters are provided.
        // In that case, update the API URL with the provided table name and query parameters.
        if (substr($where, 0, 1) != "*") {
            $url = $this->apiUrl . '/rest/v1/' . $tableName . '?select=' . $where;
        }

        // Prepare the header information for the API request.
        $headers = array(
            'apikey: ' . $this->apiKey,
            'Authorization: Bearer ' . $this->apiKey,
            'Range: ' . @$range
        );

        // Initialize cURL session.
        $ch = curl_init($url);

        // Set cURL options.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response instead of outputting
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Set headers

        // Execute cURL session and get the response.
        $response = curl_exec($ch);

        // Return the API response.
        return $response;
    }

}
