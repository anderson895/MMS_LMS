  <!-- Main Content -->
  <div id="content">

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

   
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $db->user_acc($_SESSION['user_id'])?></span>
                <img class="img-profile rounded-circle"
                    src="image/admin_profile.svg">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

     
        <div class="col-xl-4 col-md-4 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Active Loans</div>
                            <div class="h1 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                    $tbl_loan=$db->conn->query("SELECT * FROM `loan` WHERE `status`='2'");
                                    echo $tbl_loan->num_rows > 0 ? $tbl_loan->num_rows : "0";
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-fw fas fa-comment-dollar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small stretched-link" href="loan.php">View Loan List</a>
                    <div class="small">
                        <i class="fa fa-angle-right"></i>
                    </div>
                </div>
            </div>
        </div>

      
        <div class="col-xl-4 col-md-4 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Payments Today</div>
                            <div class="h1 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                    $tbl_payment=$db->conn->query("SELECT sum(pay_amount) as total FROM `payment` WHERE date(date_created)='".date("Y-m-d")."'");
                                    echo $tbl_payment->num_rows > 0 ? "&#8369; ".number_format($tbl_payment->fetch_array()['total'],2) : "&#8369; 0.00";
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-fw fas fa-coins fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small stretched-link" href="payment.php">View Payments</a>
                    <div class="small">
                        <i class="fa fa-angle-right"></i>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-4 col-md-4 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Borrowers
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h1 mb-0 mr-3 font-weight-bold text-gray-800">
                                        <?php 
                                            $tbl_borrower=$db->conn->query("SELECT * FROM `borrower`");
                                            echo $tbl_borrower->num_rows > 0 ? $tbl_borrower->num_rows : "0";
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-fw fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small stretched-link" href="borrower.php">View Borrowers</a>
                    <div class="small">
                        <i class="fa fa-angle-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of Main Content -->