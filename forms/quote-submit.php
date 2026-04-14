<?php
/**
* Quote Form Submission Handler
* Sends quote request data to mailumairrana@gmail.com
*/

// Replace with your real receiving email address
$receiving_email_address = 'mailumairrana@gmail.com';

// Set response headers
header('Content-Type: application/json');

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Validate required fields
$required_fields = ['business', 'service', 'details', 'name', 'email', 'phone'];
$missing_fields = [];

foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit;
}

// Validate email
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
    exit;
}

// Sanitize input data
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$business = sanitize($_POST['business']);
$reference = sanitize($_POST['reference'] ?? 'Not provided');
$service = sanitize($_POST['service']);
$details = sanitize($_POST['details']);
$name = sanitize($_POST['name']);
$email = sanitize($_POST['email']);
$phone = sanitize($_POST['countryCodeValue'] ?? '') . ' ' . sanitize($_POST['phone']);
$company = sanitize($_POST['company'] ?? 'Not provided');

// Get service name from value
$service_names = [
    'web-cms' => 'Development',
    'mobile-app' => 'Integrations', 
    'e-commerce' => 'SEO',
    'ui-ux' => 'Digital Marketing',
    'digital-marketing' => 'Content Publishing',
    'seo' => 'Other'
];
$service_name = $service_names[$service] ?? $service;

// Email subject
$subject = "New Quote Request from {$business} - {$service_name} Service";

// Email message
$message = "
<html>
<head>
    <title>New Quote Request</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #007bff; }
        .value { margin-top: 5px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>New Quote Request</h2>
            <p>Someone is interested in your services!</p>
        </div>
        <div class='content'>
            <div class='field'>
                <div class='label'>Business Name:</div>
                <div class='value'>{$business}</div>
            </div>
            <div class='field'>
                <div class='label'>Reference/Competitor:</div>
                <div class='value'>{$reference}</div>
            </div>
            <div class='field'>
                <div class='label'>Service Type:</div>
                <div class='value'>{$service_name}</div>
            </div>
            <div class='field'>
                <div class='label'>Project Details:</div>
                <div class='value'>" . nl2br($details) . "</div>
            </div>
            <div class='field'>
                <div class='label'>Contact Name:</div>
                <div class='value'>{$name}</div>
            </div>
            <div class='field'>
                <div class='label'>Email Address:</div>
                <div class='value'>{$email}</div>
            </div>
            <div class='field'>
                <div class='label'>Phone Number:</div>
                <div class='value'>{$phone}</div>
            </div>
            <div class='field'>
                <div class='label'>Company:</div>
                <div class='value'>{$company}</div>
            </div>
        </div>
        <div class='footer'>
            <p>This quote request was submitted on " . date('Y-m-d H:i:s') . "</p>
        </div>
    </div>
</body>
</html>";

// Email headers
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: {$name} <{$email}>" . "\r\n";
$headers .= "Reply-To: {$email}" . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send email
if (mail($receiving_email_address, $subject, $message, $headers)) {
    echo json_encode([
        'success' => true, 
        'message' => 'Thank you for your quote request! We will contact you within 24 hours.'
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Sorry, there was an error sending your request. Please try again later.'
    ]);
}
?>
