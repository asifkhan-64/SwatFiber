<?php

    // This script assumes it is being called via AJAX or included in a context
    // where $connect is available from config.php.

    // --- Configuration and Setup ---
    include '../_stream/config.php';
    session_start();
    
    // --- Security Check ---
    if (empty($_SESSION["user"])) {
        header("LOCATION:../index.php");
        exit(); // Always exit after a header redirect
    }
    
    date_default_timezone_set('Asia/Karachi');
    $today = new DateTime();
    
    // --- Input and Database Fetch (IMPROVED SECURITY) ---
    $output = '';
    
    // Sanitize and cast input to integer. Use null-coalescing to handle missing POST data safely.
    $client_id = isset($_POST["customer"]) ? (int)$_POST["customer"] : 0; 

    // ** FIX 1 (Security): Use placeholder '?' in the query string. **
    $stmt = mysqli_prepare($connect, "SELECT * FROM client_tbl WHERE client_id = ?");
    
    if ($stmt) {
        // Bind the client ID parameter as an integer ('i')
        mysqli_stmt_bind_param($stmt, "i", $client_id);
        
        // Execute the statement
        mysqli_stmt_execute($stmt);
        
        // Get the result set
        $query_result = mysqli_stmt_get_result($stmt);
        
        // Fetch the single user data array
        $userData = mysqli_fetch_assoc($query_result);
        
        // Close the statement
        mysqli_stmt_close($stmt);

    } else {
        $output = "Database preparation error.";
        echo $output;
        exit();
    }
    
    // --- Billing Calculation Function ---

    /**
     * Calculates the billing details based on a 30-day cycle from the last paid date.
     * @param array $user User data array. Expects keys: 'last_paid_date', 'updated_bill_payment', 'unpaid_balance', 'old_remaining'.
     * @param DateTime $today The current date.
     * @return array Calculation details.
     */
    function calculateBill(array $user, DateTime $today): array {
        
        // --- Data Extraction and Casting ---
        $monthlyPrice = (float) ($user['updated_bill_payment'] ?? 0);
        $unpaidBalance = (float) ($user['unpaid_balance'] ?? 0);
        $oldRem = (float) ($user['old_remaining'] ?? 0);
        $daysInCycle = 30; // Fixed billing cycle length

        // Handle potential NULL or invalid dates
        try {
            // The 'last_paid_date' is the anchor for the billing cycle
            if (empty($user['last_paid_date'])) {
                return ['total_bill' => 0, 'error' => 'Last paid date is missing.'];
            }
            $connectionDate = new DateTime($user['last_paid_date']);
        } catch (Exception $e) {
            // Handle case where date field is null or invalid
            return ['total_bill' => 0, 'error' => 'Invalid last_paid_date format.'];
        }

        // 1. Find the start of the current cycle and the next expiry date
        $cycleStart = clone $connectionDate;
        $nextExpiry = clone $connectionDate;
        $nextExpiry->modify("+$daysInCycle days");

        // Loop forward by 30 days until $nextExpiry is AFTER $today.
        while ($nextExpiry <= $today) {
            $cycleStart->modify("+$daysInCycle days");
            $nextExpiry->modify("+$daysInCycle days");
        }
        
        // 2. Determine Status and Days Remaining
        $isExpired = $today >= $nextExpiry;
        $daysUntilExpiry = 0;

        if ($isExpired) {
             $status = 'Expired';
        } else {
             $status = 'Active';
             // The difference in days from $today to the expiry date
             $daysUntilExpiry = $today->diff($nextExpiry)->days;
        }
        
        // 3. Total Due
        if ($daysUntilExpiry < 10 || $isExpired) {
                $totalPreviousDues = $unpaidBalance + $oldRem;
                $totalBill = $monthlyPrice + $totalPreviousDues;
            } else {
                // Otherwise, show 0, formatted.
                $totalBill = $oldRem;
            }
        

        return [
            'total_bill' => $totalBill,
            'status' => $status,
            'days_until_expiry' => $daysUntilExpiry
        ];
    }
    
    // --- Final Output Generation ---
    if ($userData) {
        $billing_details = calculateBill($userData, $today);
        
        // Check for date error
        if (isset($billing_details['error'])) {
            $output = "Calculation Error: " . $billing_details['error'];
        } else {
            // FIX 2 (Logic): Extract variables for the conditional check
            $totalBillAmount = $billing_details['total_bill'];
            $daysUntilExpiry = $billing_details['days_until_expiry'];
            $isExpired = $billing_details['status'] === 'Expired';
            
            // Conditional Logic: Show amount if less than 10 days until expiry OR if the service is already expired.
            if ($daysUntilExpiry < 10 || $isExpired) {
                // Output the full total bill amount, formatted.
                $output = $totalBillAmount;
            } else {
                // Otherwise, show 0, formatted.
                $output = $totalBillAmount;
            }
        }

    } else {
        $output = "Error: Client ID '{$client_id}' not found.";
    }
    
    echo $output;
?>