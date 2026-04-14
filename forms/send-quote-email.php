<?php
// Configuration
$to_email = 'omair7461284@gmail.com';
$from_email = 'noreply@umairrana.com'; // Change this to your domain email
$subject = 'New Quote Request - Umair Rana Website';

// Set headers
$headers = "From: $from_email\r\n";
$headers .= "Reply-To: {$_POST['email']}\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

// Sanitize input data
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Get and sanitize form data
$business = sanitize($_POST['business'] ?? '');
$reference = sanitize($_POST['reference'] ?? 'Not provided');
$service = sanitize($_POST['service'] ?? '');
$details = sanitize($_POST['details'] ?? '');
$name = sanitize($_POST['name'] ?? '');
$email = sanitize($_POST['email'] ?? '');
$phone = sanitize($_POST['phone'] ?? '');
$countryCode = sanitize($_POST['countryCodeValue'] ?? '');
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

// Create email content
$email_content = "
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .section { margin-bottom: 20px; }
        .section h3 { color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
        .field { margin-bottom: 10px; }
        .field strong { color: #333; }
        .footer { background: #333; color: white; padding: 10px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>New Quote Request</h2>
            <p>Someone has requested a quote through your website</p>
        </div>
        
        <div class='content'>
            <div class='section'>
                <h3>Project Information</h3>
                <div class='field'><strong>Business:</strong> $business</div>
                <div class='field'><strong>Reference/Competitor:</strong> $reference</div>
                <div class='field'><strong>Service:</strong> $service_name</div>
                <div class='field'><strong>Project Details:</strong><br>" . nl2br($details) . "</div>
            </div>
            
            <div class='section'>
                <h3>Contact Information</h3>
                <div class='field'><strong>Name:</strong> $name</div>
                <div class='field'><strong>Email:</strong> $email</div>
                <div class='field'><strong>Phone:</strong> $countryCode $phone</div>
                <div class='field'><strong>Company:</strong> $company</div>
            </div>
            
            <div class='section'>
                <h3>Request Details</h3>
                <div class='field'><strong>Submitted:</strong> " . date('Y-m-d H:i:s') . "</div>
                <div class='field'><strong>IP Address:</strong> " . $_SERVER['REMOTE_ADDR'] . "</div>
            </div>
        </div>
        
        <div class='footer'>
            <p>This email was sent from the quote request form on Umair Rana's website</p>
        </div>
    </div>
</body>
</html>
";

// Send email
if (mail($to_email, $subject, $email_content, $headers)) {
    // Success response
    $response = [
        'success' => true,
        'message' => 'Thank you for your request! We will contact you within 24 hours.'
    ];
} else {
    // Error response
    $response = [
        'success' => false,
        'message' => 'Sorry, there was an error sending your request. Please try again later.'
    ];
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
