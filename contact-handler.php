<?php
/**
 * The Brand Concierge — Contact & Inquiry Form Handler
 * Handles lead transmission to agency admin + automated confirmation email to user.
 * Supports both AJAX/JSON responses and standard HTTP POST redirects to thank-you.html.
 */

// -----------------------------------------------------------------------------
// 1. Configuration (Dummy / Customizable Settings)
// -----------------------------------------------------------------------------

// Agency Admin recipient (Change this to your live receiving email)
define('ADMIN_EMAIL', 'info@thebrandconcierge.in');
define('ADMIN_NAME', 'The Brand Concierge Inquiries');

// Automated sender / From email (Must be on your domain for SPF/DKIM delivery)
define('FROM_EMAIL', 'noreply@thebrandconcierge.in');
define('FROM_NAME', 'The Brand Concierge');

// Redirect target upon successful submission
define('SUCCESS_REDIRECT', 'thank-you.html');

// -----------------------------------------------------------------------------
// 2. Request & Environment Validation
// -----------------------------------------------------------------------------

$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
    || (isset($_POST['is_ajax']) && $_POST['is_ajax'] === '1');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if ($isAjax) {
        http_response_code(405);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
        exit;
    } else {
        header('Location: contact.html');
        exit;
    }
}

// -----------------------------------------------------------------------------
// 3. Honeypot Anti-Spam Check
// -----------------------------------------------------------------------------

// If honeypot field is filled, silently reject spam bot without revealing detection
if (!empty($_POST['tbc_website_hp'])) {
    if ($isAjax) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['success' => true, 'redirect' => SUCCESS_REDIRECT]);
        exit;
    } else {
        header('Location: ' . SUCCESS_REDIRECT);
        exit;
    }
}

// -----------------------------------------------------------------------------
// 4. Input Sanitization & Validation
// -----------------------------------------------------------------------------

function sanitize_input($data) {
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    return htmlspecialchars(strip_tags(trim((string)$data)), ENT_QUOTES, 'UTF-8');
}

$name    = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
$email   = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$phone   = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
$company = isset($_POST['company']) ? sanitize_input($_POST['company']) : 'Not Specified';
$service = isset($_POST['service']) ? sanitize_input($_POST['service']) : 'General Inquiry';
$details = isset($_POST['details']) ? sanitize_input($_POST['details']) : '';

$errors = [];

if (empty($name) || mb_strlen($name) < 2) {
    $errors[] = 'Please provide your full name (at least 2 characters).';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please provide a valid work email address.';
}

if (empty($phone) || mb_strlen(preg_replace('/[^0-9]/', '', $phone)) < 7) {
    $errors[] = 'Please provide a valid contact telephone number.';
}

if (empty($service)) {
    $errors[] = 'Please select a required service discipline.';
}

if (empty($details) || mb_strlen($details) < 10) {
    $errors[] = 'Please share a few details about your project (at least 10 characters).';
}

if (!empty($errors)) {
    if ($isAjax) {
        http_response_code(400);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['success' => false, 'error' => implode(' ', $errors)]);
        exit;
    } else {
        header('Location: contact.html?error=' . urlencode(implode(' ', $errors)));
        exit;
    }
}

// -----------------------------------------------------------------------------
// 5. Build Admin Notification Email (To Agency Team)
// -----------------------------------------------------------------------------

$submittedAt = date('d M Y, h:i A') . ' IST';
$clientIp = !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Unknown';

$adminSubject = "[New Inquiry] " . ($company !== 'Not Specified' ? $company : $name) . " — " . $service;

$adminHtml = '
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>' . htmlspecialchars($adminSubject) . '</title>
</head>
<body style="margin:0; padding:30px 15px; background-color:#FAF7F2; font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif; color:#141414; line-height:1.6;">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center">
        <table width="600" border="0" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background-color:#FFFFFF; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.06); border:1px solid #EFE8E0;">
          
          <!-- Luxury Header -->
          <tr>
            <td style="background-color:#141414; padding:32px 35px; text-align:left; border-bottom:3px solid #B05025;">
              <div style="font-size:12px; font-weight:700; letter-spacing:0.2em; text-transform:uppercase; color:#B05025; margin-bottom:6px;">NEW CLIENT INQUIRY</div>
              <h1 style="margin:0; font-size:24px; font-weight:600; color:#FFFFFF; letter-spacing:-0.01em;">The Brand Concierge</h1>
            </td>
          </tr>

          <!-- Content Body -->
          <tr>
            <td style="padding:35px 35px 25px 35px;">
              <p style="margin:0 0 20px 0; font-size:15px; color:#4A4A50;">
                A new project inquiry has been transmitted through the website contact form:
              </p>

              <!-- Details Table -->
              <table width="100%" border="0" cellpadding="10" cellspacing="0" style="border-collapse:collapse; background-color:#FAF7F2; border-radius:10px; margin-bottom:25px;">
                <tr>
                  <td width="35%" style="font-size:13px; font-weight:700; color:#7A7A82; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #E8E0D5;">Client Name</td>
                  <td style="font-size:15px; font-weight:600; color:#141414; border-bottom:1px solid #E8E0D5;">' . $name . '</td>
                </tr>
                <tr>
                  <td style="font-size:13px; font-weight:700; color:#7A7A82; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #E8E0D5;">Work Email</td>
                  <td style="font-size:15px; color:#B05025; border-bottom:1px solid #E8E0D5;"><a href="mailto:' . $email . '" style="color:#B05025; text-decoration:underline;">' . $email . '</a></td>
                </tr>
                <tr>
                  <td style="font-size:13px; font-weight:700; color:#7A7A82; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #E8E0D5;">Phone Number</td>
                  <td style="font-size:15px; color:#141414; border-bottom:1px solid #E8E0D5;"><a href="tel:' . $phone . '" style="color:#141414; text-decoration:none;">' . $phone . '</a></td>
                </tr>
                <tr>
                  <td style="font-size:13px; font-weight:700; color:#7A7A82; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #E8E0D5;">Company / Brand</td>
                  <td style="font-size:15px; color:#141414; border-bottom:1px solid #E8E0D5;">' . $company . '</td>
                </tr>
                <tr>
                  <td style="font-size:13px; font-weight:700; color:#7A7A82; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #E8E0D5;">Service Required</td>
                  <td style="font-size:14px; font-weight:700; color:#B05025; border-bottom:1px solid #E8E0D5;">' . $service . '</td>
                </tr>
                <tr>
                  <td style="font-size:13px; font-weight:700; color:#7A7A82; text-transform:uppercase; letter-spacing:0.05em;">Received At</td>
                  <td style="font-size:13px; color:#6A6A70;">' . $submittedAt . '</td>
                </tr>
              </table>

              <!-- Project Narrative -->
              <div style="margin-bottom:25px;">
                <div style="font-size:13px; font-weight:700; color:#141414; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:8px;">Project Scope &amp; Ambition:</div>
                <div style="background-color:#FFFFFF; border-left:3px solid #B05025; padding:15px 18px; border-radius:0 8px 8px 0; background-color:#FAF7F2; font-size:14px; color:#222225; line-height:1.7; white-space:pre-wrap;">' . nl2br($details) . '</div>
              </div>

              <!-- Quick Action Button -->
              <div style="text-align:center; padding:10px 0 20px 0;">
                <a href="mailto:' . $email . '?subject=' . rawurlencode('Re: Your inquiry to The Brand Concierge') . '" style="display:inline-block; background-color:#B05025; color:#FFFFFF; padding:13px 28px; border-radius:999px; text-decoration:none; font-weight:600; font-size:14px; letter-spacing:0.04em;">Reply Directly to ' . $name . ' &rarr;</a>
              </div>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background-color:#F5EFE6; padding:18px 35px; text-align:center; font-size:12px; color:#8A8A92; border-top:1px solid #E8E0D5;">
              Client IP: ' . $clientIp . ' &bull; The Brand Concierge Secure Inquiry Gateway
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>';

// -----------------------------------------------------------------------------
// 6. Build User Confirmation Email (Auto-Responder To Client)
// -----------------------------------------------------------------------------

$userSubject = "Thank you for contacting The Brand Concierge";

$userHtml = '
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>' . htmlspecialchars($userSubject) . '</title>
</head>
<body style="margin:0; padding:30px 15px; background-color:#FAF7F2; font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif; color:#141414; line-height:1.6;">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center">
        <table width="600" border="0" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background-color:#FFFFFF; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.06); border:1px solid #EFE8E0;">
          
          <!-- Elegant Header -->
          <tr>
            <td style="background-color:#141414; padding:36px 35px; text-align:center; border-bottom:3px solid #B05025;">
              <div style="font-size:11px; font-weight:700; letter-spacing:0.25em; text-transform:uppercase; color:#B05025; margin-bottom:8px;">INQUIRY RECEIVED</div>
              <h1 style="margin:0; font-size:26px; font-weight:600; color:#FFFFFF; letter-spacing:0.02em;">The Brand Concierge</h1>
              <p style="margin:6px 0 0 0; font-size:13px; color:#BDBDBF; letter-spacing:0.08em; text-transform:uppercase;">Strategy &bull; Design &bull; Storytelling</p>
            </td>
          </tr>

          <!-- Message Body -->
          <tr>
            <td style="padding:35px 35px 25px 35px;">
              <h2 style="margin:0 0 16px 0; font-size:20px; font-weight:600; color:#141414;">Dear ' . $name . ',</h2>
              <p style="margin:0 0 18px 0; font-size:15px; color:#4A4A50; line-height:1.7;">
                Thank you for reaching out to <strong>The Brand Concierge</strong>. We have successfully received your inquiry regarding <strong>' . $service . '</strong>.
              </p>
              <p style="margin:0 0 25px 0; font-size:15px; color:#4A4A50; line-height:1.7;">
                Our creative directors and strategy leadership are currently reviewing your project details. Every brand partnership begins with thorough alignment, and we will get back to you within <strong>24 to 48 business hours</strong> to discuss the next steps.
              </p>

              <!-- Inquired Summary Card -->
              <div style="background-color:#FAF7F2; border:1px solid #EFE8E0; border-radius:12px; padding:20px 22px; margin-bottom:28px;">
                <div style="font-size:12px; font-weight:700; color:#B05025; text-transform:uppercase; letter-spacing:0.12em; margin-bottom:10px;">Summary of Your Inquiry</div>
                <div style="font-size:14px; color:#141414; margin-bottom:6px;"><strong>Service:</strong> ' . $service . '</div>
                ' . ($company !== 'Not Specified' ? '<div style="font-size:14px; color:#141414; margin-bottom:6px;"><strong>Company:</strong> ' . $company . '</div>' : '') . '
                <div style="font-size:14px; color:#141414;"><strong>Contact Email:</strong> ' . $email . '</div>
              </div>

              <p style="margin:0 0 25px 0; font-size:14px; color:#6A6A70; line-height:1.6;">
                In the meantime, feel free to explore our curated portfolio of brand transformations at <a href="https://thebrandconcierge.in/work.html" style="color:#B05025; text-decoration:underline;">thebrandconcierge.in/work.html</a>.
              </p>

              <div style="border-top:1px solid #EFE8E0; padding-top:20px; margin-top:20px;">
                <p style="margin:0; font-size:14px; font-weight:600; color:#141414;">Warm regards,</p>
                <p style="margin:4px 0 0 0; font-size:14px; color:#B05025; font-weight:600;">The Brand Concierge Team</p>
                <p style="margin:4px 0 0 0; font-size:13px; color:#7A7A82;">New Delhi, India &bull; <a href="https://thebrandconcierge.in" style="color:#7A7A82; text-decoration:none;">thebrandconcierge.in</a></p>
              </div>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background-color:#F5EFE6; padding:18px 35px; text-align:center; font-size:12px; color:#8A8A92; border-top:1px solid #E8E0D5;">
              &copy; ' . date('Y') . ' The Brand Concierge. All Rights Reserved.
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>';

// -----------------------------------------------------------------------------
// 7. Headers & Dispatch
// -----------------------------------------------------------------------------

// Admin Mail Headers
$adminHeaders  = "MIME-Version: 1.0\r\n";
$adminHeaders .= "Content-Type: text/html; charset=UTF-8\r\n";
$adminHeaders .= "From: " . FROM_NAME . " <" . FROM_EMAIL . ">\r\n";
$adminHeaders .= "Reply-To: " . $name . " <" . $email . ">\r\n";
$adminHeaders .= "X-Mailer: PHP/" . phpversion();

// User Mail Headers
$userHeaders  = "MIME-Version: 1.0\r\n";
$userHeaders .= "Content-Type: text/html; charset=UTF-8\r\n";
$userHeaders .= "From: " . FROM_NAME . " <" . FROM_EMAIL . ">\r\n";
$userHeaders .= "Reply-To: " . ADMIN_NAME . " <" . ADMIN_EMAIL . ">\r\n";
$userHeaders .= "X-Mailer: PHP/" . phpversion();

// Dispatch Emails via PHP mail()
// @ suppresses warning on local dev environments where sendmail/SMTP is not active
$adminSent = @mail(ADMIN_EMAIL, $adminSubject, $adminHtml, $adminHeaders);
$userSent  = @mail($email, $userSubject, $userHtml, $userHeaders);

// -----------------------------------------------------------------------------
// 8. Output Response
// -----------------------------------------------------------------------------

if ($isAjax) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'success'  => true,
        'redirect' => SUCCESS_REDIRECT,
        'message'  => 'Your inquiry has been successfully transmitted. A confirmation email has been dispatched.'
    ]);
    exit;
} else {
    header('Location: ' . SUCCESS_REDIRECT);
    exit;
}
