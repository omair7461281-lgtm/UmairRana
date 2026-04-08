<?php
/**
 * Quote Form Handler with Fallback
 * Sends quote request data to omair7461281@gmail.com
 * Falls back to saving data if email fails
 */

// Set recipient email
$to_email = 'omair7461281@gmail.com';

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to save submission to file
function save_submission_to_file($data) {
    $filename = 'quote-submissions.txt';
    $timestamp = date('Y-m-d H:i:s');
    $separator = str_repeat("=", 50) . "\n";
    
    $content = $separator;
    $content .= "NEW QUOTE REQUEST - {$timestamp}\n";
    $content .= $separator;
    $content .= "Business: " . $data['business'] . "\n";
    $content .= "Reference: " . ($data['reference'] ?: 'Not provided') . "\n";
    $content .= "Service: " . $data['service_name'] . "\n";
    $content .= "Details: " . $data['details'] . "\n";
    $content .= "Name: " . $data['name'] . "\n";
    $content .= "Email: " . $data['email'] . "\n";
    $content .= "Phone: " . $data['countryCode'] . ' ' . $data['phone'] . "\n";
    $content .= "Company: " . ($data['company'] ?: 'Not provided') . "\n";
    $content .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $content .= "\n";
    
    return file_put_contents($filename, $content, FILE_APPEND | LOCK_EX);
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
    
    // Set headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <noreply@omairrana.com>' . "\r\n";
    
    // Try to send email first
    $mail_sent = mail($to_email, $subject, $email_body, $headers);
    
    if ($mail_sent) {
        // Success response
        $response = [
            'status' => 'success',
            'message' => 'Your quote request has been sent successfully! We will contact you within 24 hours.'
        ];
    } else {
        // Email failed, try to save to file
        $submission_data = [
            'business' => $business,
            'reference' => $reference,
            'service_name' => $service_name,
            'details' => $details,
            'name' => $name,
            'email' => $email,
            'countryCode' => $countryCode,
            'phone' => $phone,
            'company' => $company
        ];
        
        if (save_submission_to_file($submission_data)) {
            // File save successful
            $response = [
                'status' => 'success',
                'message' => 'Your quote request has been received! We will contact you within 24 hours. (Email service temporarily unavailable, data saved securely)'
            ];
        } else {
            // Both email and file save failed
            $response = [
                'status' => 'error',
                'message' => 'Sorry, there was an error processing your request. Please contact us directly at omair7461281@gmail.com'
            ];
        }
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
