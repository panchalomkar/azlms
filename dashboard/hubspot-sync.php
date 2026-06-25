<?php
$apikey = '859732d9-3a0c-467b-af3e-a24a49e1d367';
function send_contact_to_hubspot($name, $email, $phone = '', $message = '') {
    $hapikey = 'YOUR_HUBSPOT_API_KEY';  // Replace with your HubSpot API key
    $url = "https://api.hubapi.com/contacts/v1/contact/?hapikey=$hapikey";

    $data = [
        'properties' => [
            ['property' => 'email', 'value' => $email],
            ['property' => 'firstname', 'value' => $name],
            // HubSpot has default properties like phone; 'message' might be custom property
        ]
    ];

    // Add optional phone if provided
    if (!empty($phone)) {
        $data['properties'][] = ['property' => 'phone', 'value' => $phone];
    }

    // If you want to store the message, you'll need a custom property in HubSpot (e.g., 'contact_message')
    if (!empty($message)) {
        $data['properties'][] = ['property' => 'contact_message', 'value' => $message];
    }

    $payload = json_encode($data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200 || $http_code == 201) {
        return true;  // Success
    } else {
        // Optionally log error: $response
        return false;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = required_param('name', PARAM_TEXT);
    $email = required_param('email', PARAM_EMAIL);
    $phone = optional_param('phone', PARAM_TEXT);
    $message = optional_param('message', PARAM_TEXT);

    $sent = send_contact_to_hubspot($name, $email, $phone, $message);

    if ($sent) {
        echo "Contact request sent successfully!";
    } else {
        echo "Failed to send contact request to HubSpot.";
    }
}


?>