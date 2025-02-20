<?php
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

function isValidPhoneNumber($phone_number, $customer_id, $api_key) {
    $api_key = getenv('TELESIGN_API_KEY');
    $customer_id = getenv('TELESIGN_CUSTOMER_ID');

    $api_url = "https://rest-ww.telesign.com/v1/phoneid/$phone_number";
    
    $headers = [
        "Authorization: Basic " . base64_encode("$customer_id:$api_key"),
        "Content-Type: application/json" // Telesign API needs application/json to properly handle the data, so I updated this for correct request formatting
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {  // Included error handling for the cURL request, so if the request fails the function prints the error
        echo 'Curl error: '. curl_error($ch); 
        curl_close($ch);
        return false; 
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    
    if ($http_code !== 200) {  // Added a check to print the status code if the API request fails
        echo "Error: API returned $http_code\n"; 
        return false;
    }
    
    $data = json_decode($response, true);

    if (!isset($data['numbering']['phone_type'])) {
        echo "Unexpected API response: " . $response . "\n"; // Added a error message to display unexpected responses
        return false; 
    }
    
    $valid_types = ["FIXED_LINE", "MOBILE", "VALID"];
    return in_array(strtoupper($data['numbering']['phone_type']), $valid_types);
}

// Usage example
$phone_number = "9876543210"; // Replace with actual phone number
$result = isValidPhoneNumber($phone_number, $customer_id, $api_key);
var_dump($result); // Output result
?>
