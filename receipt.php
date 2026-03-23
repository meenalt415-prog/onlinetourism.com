<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

use Dompdf\Dompdf;

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    echo 'Bad request';
    exit;
}

require_once './src/Database.php';
require_once './src/dompdf/autoload.inc.php';

// ✅ Make sure $db exists
// If Database.php doesn’t define $db, uncomment this:
// $db = new mysqli('localhost', 'root', '', 'your_database_name');

$id = $db->real_escape_string($_GET['id']);

// ✅ Fetch booking + customer + package details in one query
$sql = "
SELECT 
    b.*,
    c.name AS customer_name,
    c.phone AS customer_phone,
    c.email AS customer_email,
    p.package_name,
    d.name AS destination
FROM booking b
LEFT JOIN customers c ON b.customer = c.id
LEFT JOIN tour_packages p ON b.package_id = p.id
LEFT JOIN destinations d ON p.destination_id = d.id
WHERE b.id = '$id'
";

$res = $db->query($sql);

if (!$res || $res->num_rows === 0) {
    http_response_code(404);
    echo 'Booking not found';
    exit;
}

$data = $res->fetch_object();

// ✅ Use proper time zone
$tz = new DateTimeZone('Asia/Kolkata');
$now = new DateTime('now', $tz);
$bookingDate = new DateTime($data->booking_date, $tz);

// ✅ Prepare HTML for PDF
$html = '
<div>
    <table border="1" style="border-collapse: collapse; width:100%; padding: 40px; font-family: Arial, sans-serif;">
        <tr>
            <td colspan="4" style="text-align: center; padding-top: 20px">
                <h1 style="margin:0;">Online Tourism Pvt. Ltd.</h1>
                <p style="padding-bottom:20px; line-height:1.6;">
                    Phone: +91 987654321 | Email: info@onlinetourism.com<br>
                    Website: <a href="http://www.onlinetourism.com">www.onlinetourism.com</a><br>
                    Office Hours: 9 AM – 6 PM (Mon–Sat)
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: right; font-weight:bold; padding:10px">
                Date: ' . $now->format('d-m-Y H:i:s') . '
            </td>
        </tr>
        <tr style="background-color:#f2f2f2;">
            <td colspan="2" style="text-align: left; font-weight:bold; padding:10px">
                Booking Receipt No: #' . $data->booking_id . '/' . $id . '
            </td>
            <td colspan="2" style="text-align: left; font-weight:bold; padding:10px">
                Booking Date: ' . $data->package_booking_date . '
            </td>
        </tr>
        <tr>
            <td colspan="4" style="padding:15px;">
                <h3 style="margin-bottom:10px;">Traveler Details</h3>
                <table width="100%" style="font-size:14px;">
                    <tr><td style="width:180px;"><b>Customer Name</b></td><td>' . htmlspecialchars($data->customer_name ?? 'N/A') . '</td></tr>
                    <tr><td><b>Contact Number</b></td><td>' . htmlspecialchars($data->customer_phone ?? 'N/A') . '</td></tr>
                    <tr><td><b>Email</b></td><td>' . htmlspecialchars($data->customer_email ?? 'N/A') . '</td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="padding:15px;">
                <h3 style="margin-bottom:10px;">Package Details</h3>
                <table width="100%" style="font-size:14px;">
                    <tr><td><b>Package Name</b></td><td>' . htmlspecialchars($data->package_name ?? 'N/A') . '</td></tr>
                    <tr><td><b>Destination</b></td><td>' . htmlspecialchars($data->destination ?? 'N/A') . '</td></tr>
                    <tr><td><b>Total Price</b></td><td>₹ ' . number_format($data->total_price, 2) . '</td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="padding:15px;">
                <h3 style="margin-bottom:10px;">Payment Details</h3>
                <table width="100%" style="font-size:14px;">
                    <tr><td style="width:180px;"><b>Payment Mode</b></td><td>' . htmlspecialchars($data->payment_mode ?? 'N/A') . '</td></tr>
                    <tr><td><b>Payment Status</b></td><td>' . htmlspecialchars($data->payment_status ?? 'N/A') . '</td></tr>
                    <tr><td><b>Transaction ID</b></td><td>' . htmlspecialchars($data->txnid ?? 'N/A') . '</td></tr>
                    <tr><td><b>Payment Date</b></td><td>' . htmlspecialchars($data->payment_date ?? 'N/A') . '</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="margin-top:30px; text-align:center;">
        <strong>Thank you for booking!</strong><br>
        We wish you a wonderful and safe journey ahead.
    </div>

    <div style="position: fixed; bottom: 0; left: 0; width: 100%; text-align:center; font-size:12px; padding:10px;">
        <em>This is a computer-generated receipt; no signature required.</em>
    </div>
</div>
';

// ✅ Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// ✅ Stream as a downloadable file
$dompdf->stream("tourism_receipt_{$data->booking_id}.pdf", ["Attachment" => true]);

exit;
?>
