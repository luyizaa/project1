<?php
// Project 1 Auth file
// Referenced GiitHub ase230/module1/code/7_Security/Basics/bearer/bearer_auth.php

function getBearerToken() {
    $headers = getallheaders();
    
    // Check if Authorization header exists
    if (isset($headers['Authorization'])) {
        // Extract token from "Bearer TOKEN_HERE" format
        if (preg_match('/Bearer\s+(.*)$/i', $headers['Authorization'], $matches)) {
            return trim($matches[1]);
        }
    }

    return null;
}


//Tokens for the testing
function isValidToken($token) {
    $validTokens = [
        'ase230-project1-token' => 'ase230',
        'admin-token' => 'admin'
    ];
    
    return isset($validTokens[$token]) ? $validTokens[$token] : false;
}


//Checks is token is missin and throws error
function check_token() {
    $token = getBearerToken();

    if (!$token) {
        throw new Exception('Bearer token required');
    }

    $user = isValidToken($token);
    if (!$user) {
        throw new Exception('Invalid or expired token');
    }

    return $user;

}


//Error message
function sendJsonError($statusCode, $message) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode(['error' => $message]);
    exit;
}

?>