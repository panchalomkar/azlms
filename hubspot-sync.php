<?php
$hapikey = '859732d9-3a0c-467b-af3e-a24a49e1d367';
function send_contact_to_hubspot($name, $email, $phone = '', $message = '') {
    $hapikey = '859732d9-3a0c-467b-af3e-a24a49e1d367';  // Replace with your HubSpot API key
    $url = "https://api.hubapi.com/contacts/v1/contact/?hapikey=$hapikey";
    //$url = "https://api.hubapi.com/crm/v3/objects/contacts?hapikey=$hapikey";

    $data = [
        'properties' => [
            'email' => $email,
            'firstname' => $name,
            'phone' => $phone,
            'contact_message' => $message,
            // HubSpot has default properties like phone; 'message' might be custom property
        ]
    ];

    $payload = json_encode($data);

    // echo "<pre>";
    // print_r($payload);
    // exit;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "<pre>";
    print_r( $response);
    exit;

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
// $name = "Mohan Pal";
// $email = "mohan.pal@akosmdtech.com";
// $phone = "9711500543";
// $message = "Hi, This is for test";
// $sent = send_contact_to_hubspot($name, $email, $phone, $message);

//     if ($sent) {
//         echo "Contact request sent successfully!";
//     } else {
//         echo "Failed to send contact request to HubSpot.";
// }


function send_contact_to_hubspot_oauth() {
    $access_token = 'pat-na1-ee459c74-9b67-4d0f-955f-f237dacbc301'; // Access token NOT an API key   

    $url_owner = 'https://api.hubapi.com/crm/v3/owners';
    $ch_owner = curl_init();
    curl_setopt($ch_owner, CURLOPT_URL, $url_owner);
    curl_setopt($ch_owner, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch_owner, CURLOPT_RETURNTRANSFER, true);

    $response_owner = curl_exec($ch_owner);
    $http_code = curl_getinfo($ch_owner, CURLINFO_HTTP_CODE);
    curl_close($ch_owner);
   
 

    if ($http_code === 200) {
        $owners = json_decode($response_owner, true);

        echo "<pre>";
        print_r($owners);
        echo "</pre>";
        echo $owners['results'][1]['id'];
    } else {
        echo "Error fetching owners. HTTP Status Code: $http_code\n";
        echo "Response: $response";
    }

    exit;

    $url = 'https://api.hubapi.com/crm/v3/objects/contacts';

    $name = "Mohan Akos";
    $email = "mohan.pal11@akosmdtech.com";
    $phone = "9711500513";
    $message = "Hi, This is for test";

    $data = [
        'properties' => [
            'email' => $email,
            'firstname' =>$name,
            'phone' => $phone,
            'message' =>$message
        ]
    ];

    $payload = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        "Authorization: Bearer $access_token"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "<pre>";
    print_r( $response);

    if ($status === 201) {
        return true;
    } else {
        error_log("HubSpot sync failed: $response");
        return false;
    }
}


$sent = send_contact_to_hubspot_oauth();

?>