<?php
/**
 * Quote Form Handler with SMTP (PHPMailer)
 * Sends quote request data to omair7461281@gmail.com
 */

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer
require 'vendor/autoload.php';

// Set recipient email
$to_email = 'omair7461281@gmail.com';

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
    
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'omair7461281@gmail.com';
        $mail->Password   = 'your-app-password'; // IMPORTANT: Use app password, not regular password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        // Recipients
        $mail->setFrom('noreply@omairrana.com', 'Umair Rana Website');
        $mail->addAddress($to_email);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $email_body;
        $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '</p>'], "\n", $email_body));
        
        $mail->send();
        
        // Success response
        $response = [
            'status' => 'success',
            'message' => 'Your quote request has been sent successfully! We will contact you within 24 hours.'
        ];
        
    } catch (Exception $e) {
        // Error response
        $response = [
            'status' => 'error',
            'message' => 'Email sending failed: ' . $mail->ErrorInfo
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
