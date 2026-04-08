<?php
/**
 * Quote Form Handler
 * Sends quote request data to omair7461281@gmail.com
 */

// Set recipient email
$to_email = 'omair7461281@gmail.com';

// Set headers
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: <noreply@omairrana.com>' . "\r\n";

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Collect and sanitize form data
    $business = sanitize_input($_POST['business'] ?? '');
    $reference = sanitize_input($_POST['reference'] ?? '');
    $service = sanitize_input($_POST['service'] ?? '');
    $details = sanitize_input($_POST['details'] ?? '');
    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $countryCode = sanitize_input($_POST['countryCodeValue'] ?? '');
    $phone = sanitize_input($_POST['phone'] ?? '');
    $company = sanitize_input($_POST['company'] ?? '');
    
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
    
    // Create email subject
    $subject = "New Quote Request from {$business} - {$service_name}";
    
    // Create email body HTML
    $email_body = "
    <html>
    <head>
        <title>New Quote Request</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #4f46e5; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background: #f9fafb; }
            .section { margin-bottom: 20px; padding: 15px; background: white; border-left: 4px solid #4f46e5; }
            .field { margin-bottom: 10px; }
            .label { font-weight: bold; color: #4f46e5; }
            .value { color: #333; }
            .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Quote Request Received</h2>
                <p>You have received a new quote request from your website</p>
            </div>
            
            <div class='content'>
                <div class='section'>
                    <h3>Project Information</h3>
                    <div class='field'>
                        <span class='label'>Business Name:</span>
                        <span class='value'>{$business}</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Reference/Competitor:</span>
                        <span class='value'>" . ($reference ?: 'Not provided') . "</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Service Type:</span>
                        <span class='value'>{$service_name}</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Project Details:</span>
                        <span class='value'>{$details}</span>
                    </div>
                </div>
                
                <div class='section'>
                    <h3>Contact Information</h3>
                    <div class='field'>
                        <span class='label'>Name:</span>
                        <span class='value'>{$name}</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Email:</span>
                        <span class='value'>{$email}</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Phone:</span>
                        <span class='value'>{$countryCode} {$phone}</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Company:</span>
                        <span class='value'>" . ($company ?: 'Not provided') . "</span>
                    </div>
                </div>
                
                <div class='section'>
                    <h3>Request Details</h3>
                    <div class='field'>
                        <span class='label'>Submission Date:</span>
                        <span class='value'>" . date('Y-m-d H:i:s') . "</span>
                    </div>
                    <div class='field'>
                        <span class='label'>IP Address:</span>
                        <span class='value'>" . $_SERVER['REMOTE_ADDR'] . "</span>
                    </div>
                </div>
            </div>
            
            <div class='footer'>
                <p>This email was sent from your website quote form.</p>
                <p>Please respond to the customer within 24 hours.</p>
            </div>
        </div>
    </body>
    </html>";
    
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Log the attempt
    error_log("Attempting to send quote request email to: " . $to_email);
    
    // Send email
    $mail_sent = mail($to_email, $subject, $email_body, $headers);
    
    // Log the result
    error_log("Mail function result: " . ($mail_sent ? 'SUCCESS' : 'FAILED'));
    error_log("Mail error: " . error_get_last()['message'] ?? 'No error info available');
    
    if ($mail_sent) {
        // Success response
        $response = [
            'status' => 'success',
            'message' => 'Your quote request has been sent successfully! We will contact you within 24 hours.'
        ];
    } else {
        // Error response with more details
        $error_message = error_get_last()['message'] ?? 'Unknown error';
        $response = [
            'status' => 'error',
            'message' => 'Email sending failed. Server mail function may not be configured. Error: ' . $error_message
        ];
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
    
} else {
    // Not a POST request
    $response = [
        'status' => 'error',
        'message' => 'Invalid request method.'
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
