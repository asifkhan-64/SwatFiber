<?php
// Define the base time zone for date operations
date_default_timezone_set('Asia/Karachi'); // Adjust to your specific time zone
$today = new DateTime();
$current_month_name = $today->format('F Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Billing Calculator</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .header {
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="header text-primary mb-4">Customer Billing Dashboard</h1>
    <p class="lead">Billing calculations based on the connection date and a fixed 30-day cycle, including any previous unpaid balance.</p>

    <?php

    // --- 1. SAMPLE USER DATA (Updated to use connection_date and unpaid_balance) ---
    // In a real application, this data would come from a database query.
    $users = [
        [
            'id' => 101,
            'name' => 'Ali Khan',
            'monthly_price' => 1500, // PKR
            'connection_date' => '2025-11-22', // Connection/Start Date
            'unpaid_balance' => 0, // No previous dues
        ],
        [
            'id' => 102,
            'name' => 'Zara Ahmed',
            'monthly_price' => 2500, // PKR
            'connection_date' => '2025-10-05', // Connection/Start Date
            'unpaid_balance' => 2500, // One month previous bill pending
        ],
        [
            'id' => 103,
            'name' => 'Faisal Butt',
            'monthly_price' => 1000, // PKR
            'connection_date' => '2025-09-29', // Connection/Start Date
            'unpaid_balance' => 1000, // One month previous bill pending
        ]
    ];

    /**
     * Calculates the total amount due based on a 30-day cycle from the connection date.
     * * @param array $user User data array (must contain 'connection_date' and 'unpaid_balance').
     * @param DateTime $today The current date.
     * @return array Calculation details.
     */
    function calculateBill(array $user, DateTime $today): array {
        $connectionDate = new DateTime($user['connection_date']);
        $monthlyPrice = $user['monthly_price'];
        $unpaidBalance = $user['unpaid_balance']; // New: Get previous unpaid balance
        $daysInCycle = 30; // Fixed billing cycle length

        // 1. Find the start of the current cycle and the next expiry date
        $cycleStart = clone $connectionDate;
        $nextExpiry = clone $connectionDate;
        $nextExpiry->modify("+$daysInCycle days");

        // Loop forward by 30 days until $nextExpiry is in the future
        // This effectively finds the current active 30-day billing block
        while ($nextExpiry <= $today) {
            $cycleStart->modify("+$daysInCycle days");
            $nextExpiry->modify("+$daysInCycle days");
        }
        
        // 2. Determine Status and Days Remaining
        $isExpired = $nextExpiry <= $today;
        $daysUntilExpiry = 0;
        $cycleEnd = clone $nextExpiry;
        $cycleEnd->modify('-1 day'); // Cycle ends one day before next expiry

        if ($isExpired) {
             $status = 'Expired';
             // If expired, the bill is for the period that just passed (or is currently overdue)
             // For reporting, we use the period that $nextExpiry currently defines.
        } else {
             $status = 'Active';
             // Calculate days remaining in the current cycle
             $daysUntilExpiry = $nextExpiry->diff($today)->days;
        }
        
        // 3. Total Due 
        // Total Due = Current Month's Price + Unpaid Previous Balance
        $totalDue = $monthlyPrice + $unpaidBalance; // New: Sum the amounts

        return [
            'cycle_start' => $cycleStart->format('M jS, Y'),
            'cycle_end' => $cycleEnd->format('M jS, Y'),
            'days_in_cycle' => $daysInCycle,
            'monthly_price' => $monthlyPrice,
            'unpaid_balance' => $unpaidBalance, // New: Return unpaid balance
            'next_expiry_date' => $nextExpiry->format('M jS, Y'),
            'total_bill' => $totalDue,
            'status' => $status,
            'days_until_expiry' => $daysUntilExpiry
        ];
    }
    
    // --- 2. DISPLAY THE BILLING DATA ---
    ?>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="table-primary">
                <tr>
                    <th>Customer ID</th>
                    <th>Customer Name</th>
                    <th>Monthly Price (PKR)</th>
                    <th>Current Cycle Start Date</th>
                    <th>Next Expiry Date</th>
                    <th>Previous Dues (PKR)</th> <!-- New Column -->
                    <th>Total Bill Due (PKR)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <?php 
                    $billing_details = calculateBill($user, $today);
                    // Add a warning class for users expiring soon or who have dues
                    $row_class = '';
                    
                    if ($billing_details['status'] === 'Expired') {
                        $row_class = 'table-danger'; // Red if expired
                    } elseif ($billing_details['unpaid_balance'] > 0) {
                         $row_class = 'table-danger'; // Red if previous balance is due
                    } else {
                        $days_until_expiry = $billing_details['days_until_expiry'];
                        if ($days_until_expiry <= 5 && $billing_details['status'] === 'Active') {
                            $row_class = 'table-warning'; // Yellow warning if expiring soon
                        }
                    }
                    ?>
                    <tr class="<?php echo $row_class; ?>">
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo number_format($user['monthly_price']); ?></td>
                        <td>
                            <?php echo $billing_details['cycle_start']; ?>
                        </td>
                        <td>
                            <strong><?php echo $billing_details['next_expiry_date']; ?></strong>
                            <?php if ($billing_details['status'] === 'Active'): ?>
                                <span class="badge bg-info text-dark ms-2"><?php echo $billing_details['days_until_expiry']; ?> days left</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($billing_details['unpaid_balance'] > 0): ?>
                                <strong class="text-danger"><?php echo number_format($billing_details['unpaid_balance']); ?></strong>
                            <?php else: ?>
                                <?php echo number_format(0); ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo number_format($billing_details['total_bill']); ?></strong>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo $billing_details['status'] === 'Active' && $billing_details['unpaid_balance'] === 0 ? 'success' : 'danger'; ?>">
                                <?php 
                                    if ($billing_details['unpaid_balance'] > 0) {
                                        echo 'DUE';
                                    } elseif ($billing_details['status'] === 'Expired') {
                                        echo 'EXPIRED';
                                    } else {
                                        echo 'ACTIVE';
                                    }
                                ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="alert alert-info mt-4">
        <strong>Note on Billing:</strong> The total bill due is calculated as: **Current Monthly Price** + **Previous Unpaid Balance**. Customers with any outstanding balance are highlighted.
    </div>

</div>

<!-- Bootstrap JS Bundle (optional, but good practice) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>