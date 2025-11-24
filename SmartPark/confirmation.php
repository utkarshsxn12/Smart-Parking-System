<?php
session_start();

$booking = $_SESSION['bookingData'] ?? null;
$payment = $_SESSION['paymentData'] ?? null;

if (!$booking) {
    echo 'No booking found.';
    exit;
}

?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>SmartPark | Confirmation</title>
  <style>
    body { font-family: system-ui, -apple-system, sans-serif; margin: 40px; }
    .card { border: 1px solid #ddd; border-radius: 8px; padding: 20px; max-width: 720px; }
    .row { display: flex; gap: 24px; }
    .col { flex: 1; }
    h2 { margin: 0 0 12px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 8px 10px; border-bottom: 1px solid #eee; text-align: left; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Booking Confirmation</h2>
    <div class="row">
      <div class="col">
        <table>
          <tr><th>Booking ID</th><td><?php echo htmlspecialchars($booking['bookingId']); ?></td></tr>
          <tr><th>Slot</th><td><?php echo htmlspecialchars($booking['slotNumber']); ?></td></tr>
          <tr><th>Date</th><td><?php echo htmlspecialchars($booking['bookingDate']); ?></td></tr>
          <tr><th>Check-in</th><td><?php echo htmlspecialchars($booking['checkInTime']); ?></td></tr>
          <tr><th>Check-out</th><td><?php echo htmlspecialchars($booking['checkOutTime']); ?></td></tr>
          <tr><th>Amount</th><td>₹<?php echo htmlspecialchars($booking['amount']); ?></td></tr>
        </table>
      </div>
      <div class="col">
        <table>
          <tr><th>Name</th><td><?php echo htmlspecialchars($booking['userName']); ?></td></tr>
          <tr><th>Email</th><td><?php echo htmlspecialchars($booking['userEmail']); ?></td></tr>
          <tr><th>Phone</th><td><?php echo htmlspecialchars($booking['userPhone']); ?></td></tr>
          <?php if ($payment) { ?>
          <tr><th>Payment ID</th><td><?php echo htmlspecialchars($payment['transactionId']); ?></td></tr>
          <tr><th>Paid At</th><td><?php echo htmlspecialchars($payment['paymentDate']); ?></td></tr>
          <?php } ?>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
