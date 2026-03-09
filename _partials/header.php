<?php
include('../_stream/config.php');
$sesssionEmail = $_SESSION["user"];

if (empty($sesssionEmail)) {
    header("LOCATION: ../index.php");
}
$query = mysqli_query($connect, "SELECT user_role FROM login_user WHERE email = '$sesssionEmail' ");
$fetch_query = mysqli_fetch_assoc($query);

$get = mysqli_query($connect, "SELECT * FROM `shop_info`");
$fet = mysqli_fetch_assoc($get);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title><?php echo $fet['shop_title']; ?></title>
    <meta content="<?php echo $fet['shop_title']; ?>" name="description" />
    <meta content="ThemeDesign" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="../assets/logo.png">
    <link rel="stylesheet" href="../assets/plugins/morris/morris.css">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css">

    <link href="../assets/package/dist/sweetalert2.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/customStyles.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/style1.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="../assets/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/bootstrap-slider.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/bootstrap-datetimepicker.css">
    <link rel="stylesheet" type="text/css" href="../assets/bootstrap-datepicker.min.css">

    <link rel="stylesheet" type="text/css" href="../assets/animat.css">

    <script src='../assets/kit.js' crossorigin='anonymous'></script>

    


    <style>

        @keyframes float {
        0% {
            transform: translateY(0); /* Start position */
        }
        50% {
            transform: translateY(-10px); /* Move up 5 pixels */
        }
        100% {
            transform: translateY(0); /* Return to start */
        }
        }

        .timeline-content {
        animation: float 4s ease-in-out infinite !important; /* Apply the animation */
        zoon: 0.5 !important;
        }

        .timeline  {
            animation-delay: 1s !important; /* Start half a second later */
        }

        .main-timeline2 .timeline:nth-child(2) .timeline-content {
        animation-delay: 0.5s !important; /* Start half a second later */
        }

        .main-timeline2 .timeline:nth-child(3) .timeline-content {
        animation-delay: 0.5s !important; /* Start one second later */
        }

        body {
            font-family: "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", Helvetica, Arial, sans-serif; 
        }

        .my-element {
            display: inline-block;
            margin: 0 0.5rem;

            animation: Fade;
            animation-duration: 2s;
        }

        .animate__animated.animate__bounce {
            --animate-duration: 2s;
        }

        :root {
            --animate-duration: 800ms;
            --animate-delay: 1s;
        }

        img {
            transition: transform 0.5s ease;
            
        }

        img:hover {
            transform: scale(1.2);
            z-index: 999;
            backdrop-filter: blur(100px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* Update Topbar and Navbar background */
        .topbar, 
        .topbar-left, 
        .navbar-custom {
            background-color: #26166a !important;
        }

        /* Ensure the hamburger menu icon and other links remain visible */
        .button-menu-mobile, 
        .nav-link {
            color: #ffffff !important;
        }

        /* Optional: Add a slight border or shadow if it blends too much with the sidebar */
        .topbar {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Change the toggle button background and hover state */
        .button-menu-mobile {
            background-color: #26166a !important; /* Matches the deep purple in your image */
            color: #ffffff !important; /* Keeps the hamburger icon white */
            border: none !important;
        }

        /* Ensure the background stays purple even when the sidebar is open/closed */
        .button-menu-mobile-topbar,
        .open-left {
            background-color: #26166a !important;
        }

        /* Optional: Slight hover effect for better UX */
        .button-menu-mobile:hover {
            background-color: #1a0f4a !important; 
            color: #ffffff !important;
        }

        /* --- New Sidebar Styles --- */

    /* 1. Main Sidebar Container & Background Gradient */
    .side-menu.left {
        background-color: #ffffff; /* Fallback */
        /* Light blue to white gradient (simulating the image) */
        background: linear-gradient(135deg, #1ea3ea 5%, #1ea3ea 70%, #ffffff 100%, #ffffff 100%) !important;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        border-right: 1px solid #ddd;
    }

    /* 2. Style the Menu Title (e.g., "Main") */
    #sidebar-menu ul li.menu-title {
        color: #1a0f4a !important; /* Dark purple/blue for title */
        background-color: transparent !important;
        border-bottom: 2px solid #1ea3ea !important;
        margin-bottom: 10px;
    }

    /* 3. General Menu Item Styling (White Background / Stripes) */
    #sidebar-menu ul li a {
        color: #1a0f4a !important; /* Dark text for readability */
        background-color: #ffffff !important;
        border-bottom: 1px solid #1ea3ea !important; /* Blue border creates stripe effect */
        transition: all 0.3s ease;
        padding: 12px 20px !important;
        display: flex;
        align-items: center;
        
    }

    /* 4. Active & Hover State for Menu Items */
    #sidebar-menu ul li a.active,
    #sidebar-menu ul li a:hover {
        padding: 15px !important;
        zoom: 1.1 !important
        color: #000000 !important; /* White text when active */
        background-color: #1ea3ea !important;
        background: linear-gradient(135deg, #1ea3ea 40%, #1ea3ea 40%, #c7c7c7 70%, #ffffff 100%) !important;

        box-shadow: inset 0 -3px 0 rgba(0,0,0,0.1);
    }

    /* 5. Icon & Text Alignment */
    #sidebar-menu ul li a i {
        color: #1ea3ea !important; /* Icon matches blue */
        margin-right: 10px;
        font-size: 1.1rem;
    }

    #sidebar-menu ul li a.active i,
    #sidebar-menu ul li a:hover i {
        color: #000000 !important; /* Icon goes white when active */
    }

    /* 6. Submenu Styling (if any are expanded) */
    #sidebar-menu ul li.has_sub ul.list-unstyled {
        background-color: #f8f9fa !important; /* Slightly off-white for submenu */
        border-left: 3px solid #1ea3ea;
    }

    #sidebar-menu ul li.has_sub ul.list-unstyled li a {
        background-color: transparent !important;
        border-bottom: none !important;
        color: #1a0f4a !important;
        padding-left: 45px !important;
    }

    /* 7. Ensure Logo Area at Top looks good */
    .left-side-logo {
        background-color: #ffffff !important;
        border-bottom: 1px solid #ddd;
        color: #1a0f4a !important;
    }
    .left-side-logo a {
        color: #1a0f4a !important;
    }

    /* --- Keep existing Topbar / Fixes --- */
    .timeline-content {
        animation: float 4s ease-in-out infinite !important;
        zoom: 0.5 !important; /* Fixes 'zoon' typo */
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    img:hover {
        transform: scale(1.2);
        z-index: 999;
    }

    /* --- Fix Chevron/Arrow Alignment --- */

    #sidebar-menu ul li.has_sub > a .menu-arrow {
        float: right !important; /* Forces the arrow to the right */
        margin-right: 0;
        margin-left: auto; /* Works if the parent is display: flex */
        position: absolute;
        right: 5px; /* Adjust this to match your sidebar padding */
        top: 15px; /* Adjust based on your item height */
    }

    /* If your template uses the 'mdi' icon class for arrows */
    #sidebar-menu ul li.has_sub > a i.mdi-chevron-right,
    #sidebar-menu ul li.has_sub > a i.mdi-chevron-down {
        position: absolute;
        right: 15px;
        line-height: inherit;
    }

    /* Ensure the parent link has relative positioning so the absolute arrow stays inside it */
    #sidebar-menu ul li a {
        position: relative !important;
        display: block !important;
        width: 100%;
    }

    .content {
        background-color: rgb(118 197 240);
        /* background: linear-gradient(135deg, #1ea3ea 40%, #1ea3ea 50%, #c7c7c7 50%, #ffffff 100%) !important; */
    }

    .card {
        background-color: rgb(186 227 247);
    }

    .footer {
        border-top: 1px solid #26166a;
        background-color: rgb(118 197 240);
    }

    .body {
        color: black
    }

    

    </style>
    

    <link rel="stylesheet" type="text/css" href="../assets/all.css">


</head>

<body class="fixed-left">
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div>
    <div id="wrapper">
        <div class="left side-menu">
            <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
                <i class="ion-close"></i>
            </button>
            <div class="left-side-logo d-block d-lg-none">
                <div class="text-center">
                    <a class="logo"><img src="../assets/logo.png" width="15%">&nbsp;&nbsp;&nbsp;<?php echo $fet['shop_title']; ?></a>
                </div>
            </div>
            <div class="sidebar-inner  slimscrollleft">
                <div id="sidebar-menu">
                    <ul>
                        <li class="menu-title">Main</li>

                        <?php
                        
                        $getUserRole = mysqli_query($connect, "SELECT user_role FROM login_user WHERE email = '$sesssionEmail' ");
                        $fetchUserRole = mysqli_fetch_assoc($getUserRole);
                        if ($fetchUserRole['user_role'] == '1'){                        
                        ?>
                        <li>
                            <a href="dashboard.php" class="waves-effect">
                                <i class="dripicons-meter"></i>
                                <span> Dashboard </span>
                            </a>
                        </li>


                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-feed"></i> <span> Packages</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="package_list.php">Package List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-graph-pie"></i> <span> Area</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="areas_list.php">Area List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-user-id"></i> <span> Clients</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="client_add.php">Add Client</a></li>
                                <li><a href="client_list.php">Active Client List</a></li>
                                <li><a href="deactive_client_list.php">Deactive Client List</a></li>
                                <li><a href="client_payment_list.php">Payment List</a></li>

                            </ul>
                        </li>


                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-user-group"></i> <span> System Users</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="user_new.php">Add User</a></li>
                                <li><a href="users_list.php">User List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-document"></i> <span> Bill Payments</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="bill_payment.php">Add Bill Payment</a></li>
                                <li><a href="bill_payment_list.php">Bill Payment List</a></li>
                                <li><a href="generate_bill.php">Generate Bill</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-document"></i> <span> Dues</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="receive_dues_payment.php">Receive Dues</a></li>
                                <li><a href="dues_payment_list.php">Dues Payment List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-checklist"></i> <span> Inventory</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="inventory_add.php">Add Inventory</a></li>
                                <li><a href="inventory_list.php">Inventory List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-ticket"></i> <span> Store/Line Item</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="storeline_items_list.php">Add Items List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-tags"></i> <span> Installation Type</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="installation_type_list.php">Installation Type</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-basket"></i> <span> Purchase Stock</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="select_option.php">Add Stock</a></li>
                                <li><a href="store_stock_list.php">Store Stock List</a></li>
                                <li><a href="line_stock_list.php">Line Stock List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-broadcast"></i>
                                <span> Expenses</span> <span class="menu-arrow float-right"><i
                                        class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">

                                <li><a href="expense_category_new.php">Expense Category</a></li>
                                <li><a href="expense_new.php">Add Expense</a></li>
                                <li><a href="expense_list.php">Expenses List</a></li>
                                
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-alarm"></i>
                                <span> Complaints</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="complaint_new.php">Add Complaint</a></li>
                                <li><a href="complaint_list.php">Complaint List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-to-do"></i> <span> Company Details </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="shop_info.php">Company Info</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-user"></i> <span> Admin</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="profile.php">Profile</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-stack"></i> <span> Backup</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="backup_page.php">DB Backup</a></li>
                            </ul>
                        </li>
                        <?php
                        }elseif ($fetchUserRole['user_role'] == '2') {
                        ?>

                        <li>
                            <a href="dashboard.php" class="waves-effect">
                                <i class="dripicons-meter"></i>
                                <span> Dashboard </span>
                            </a>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-user-id"></i> <span> Clients</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="client_add.php">Add Client</a></li>
                                <li><a href="client_list.php">Active Client List</a></li>
                                <li><a href="deactive_client_list.php">Deactive Client List</a></li>
                                <li><a href="client_payment_list.php">Payment List</a></li>

                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-alarm"></i>
                                <span> Complaints</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="complaint_new.php">Add Complaint</a></li>
                                <li><a href="complaint_list.php">Complaint List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-user"></i> <span> Profile</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="profile.php">Profile</a></li>
                            </ul>
                        </li>
                        
                        
                        <?php
                        }elseif ($fetchUserRole['user_role'] == '3') {
                        ?>

                        

                        <li>
                            <a href="dashboard.php" class="waves-effect">
                                <i class="dripicons-meter"></i>
                                <span> Dashboard </span>
                            </a>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-user-group"></i> <span> System Users</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="users_list.php">User List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-document"></i> <span> Bill Payments</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="bill_payment.php">Add Bill Payment</a></li>
                                <li><a href="bill_payment_list.php">Bill Payment List</a></li>
                                <li><a href="generate_bill.php">Generate Bill</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-document"></i> <span> Dues</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="receive_dues_payment.php">Receive Dues</a></li>
                                <li><a href="dues_payment_list.php">Dues Payment List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-checklist"></i> <span> Inventory</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="inventory_add.php">Add Inventory</a></li>
                                <li><a href="inventory_list.php">Inventory List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-ticket"></i> <span> Store/Line Item</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="storeline_items_list.php">Add Items List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-user"></i> <span> Profile</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="profile.php">Profile</a></li>
                            </ul>
                        </li>
                        
                        <?php
                        }elseif ($fetchUserRole['user_role'] == '4') {
                        ?>
                        <li>
                            <a href="dashboard.php" class="waves-effect">
                                <i class="dripicons-meter"></i>
                                <span> Dashboard </span>
                            </a>
                        </li>


                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-feed"></i> <span> Packages</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="package_list.php">Package List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-graph-pie"></i> <span> Area</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="areas_list.php">Area List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-user-id"></i> <span> Clients</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="client_list.php">Active Client List</a></li>
                                <li><a href="deactive_client_list.php">Deactive Client List</a></li>
                                <li><a href="client_payment_list.php">Payment List</a></li>

                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-document"></i> <span> Bill Payments</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="bill_payment_list.php">Bill Payment List</a></li>
                                <li><a href="generate_bill.php">Generate Bill</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-document"></i> <span> Dues</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="dues_payment_list.php">Dues Payment List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-checklist"></i> <span> Inventory</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="inventory_list.php">Inventory List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-ticket"></i> <span> Store/Line Item</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="storeline_items_list.php">Add Items List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-tags"></i> <span> Installation Type</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="installation_type_list.php">Installation Type</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-basket"></i> <span> Purchase Stock</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="store_stock_list.php">Store Stock List</a></li>
                                <li><a href="line_stock_list.php">Line Stock List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-broadcast"></i>
                                <span> Expenses</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="expense_list.php">Expenses List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-alarm"></i>
                                <span> Complaints</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="complaint_list.php">Complaint List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-user"></i> <span> Profile</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="profile.php">Profile</a></li>
                            </ul>
                        </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div> <!-- end sidebarinner -->
        </div>
        <div class="content-page">
            <div class="content">
                <div class="topbar">
                    <div class="topbar-left d-none d-lg-block">
                        <div class="text-center pt-2">
                            <a class="text-white ">
                                <h5 class="animate__animated animate__bounce"><img  src="../assets/logo.png" width="15%">&nbsp;&nbsp;&nbsp;<?php echo $fet['shop_title']; ?></h5>
                            </a>
                        </div>
                    </div>
                    <nav class="navbar-custom">
                        <ul class="list-inline float-right mb-0">
                            <li class="list-inline-item dropdown notification-list">
                                <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    <img src="../assets/images/user.png" alt="user" class="rounded-circle animate__animated animate__bounce" style="border:1px solid #54CC96; box-shadow: 1px 1px 3px 1px #ccc">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown ">
                                    <a class="dropdown-item" href="signout.php"><i class="mdi mdi-logout m-r-5 text-muted"></i> Logout</a>
                                </div>
                            </li>
                        </ul>
                        <ul class="list-inline menu-left mb-0">
                            <li class="list-inline-item">
                                <button type="button" class="button-menu-mobile open-left waves-effect">
                                    <i class="ion-navicon"></i>
                                </button>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </nav>
                </div>