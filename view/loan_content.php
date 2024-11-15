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
        <h1 class="h3 mb-0 text-gray-800">Loan List</h1>
    </div>
    <button class="mb-2 btn btn-lg btn-success" href="#" data-toggle="modal" data-target="#addModal"><span class="fa fa-plus"></span> Create new Loan Application</button>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Borrower</th>
                            <th>Loan Detail</th>
                            <th>Payment Detail</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $tbl_loan=$db->display_loan();
                            $i=1;
                            while($fetch=$tbl_loan->fetch_array()){
                        ?>
                        
                        <tr>
                            <td><?php echo $i++;?></td>
                            <td>
                                <p><small>Name: <strong><?php echo $fetch['lastname'].", ".$fetch['firstname']." ".substr($fetch['middlename'], 0, 1)."."?></strong></small></p>
                                <p><small>Contact: <strong><?php echo $fetch['contact_no']?></strong></small></p>
                                <p><small>Address: <strong><?php echo $fetch['address']?></strong></small></p>
                            </td>
                            <td>
                                <p><small>Reference no: <strong><?php echo $fetch['ref_no']?></strong></small></p>
                                <p><small>Loan Type: <strong><?php echo $fetch['ltype_name']?></strong></small></p>
                                <p><small>Loan Plan: <strong><?php echo $fetch['lplan_month']." months[".$fetch['lplan_interest']."%, ".$fetch['lplan_penalty']."%]"?></strong> interest, penalty</small></p>
                                <?php
                                    $monthly =($fetch['amount'] + ($fetch['amount'] * ($fetch['lplan_interest']/100))) / $fetch['lplan_month'];
                                    $penalty=$monthly * ($fetch['lplan_penalty']/100);
                                    $totalAmount=$fetch['amount']+$monthly;
                                ?>
                                <p><small>Amount: <strong><?php echo "&#8369; ".number_format($fetch['amount'], 2)?></strong></small></p>
                                <p><small>Total Payable Amount: <strong><?php echo "&#8369; ".number_format($totalAmount, 2)?></strong></small></p>
                                <p><small>Monthly Payable Amount: <strong><?php echo "&#8369; ".number_format($monthly, 2)?></strong></small></p>
                                <p><small>Overdue Payable Amount: <strong><?php echo "&#8369; ".number_format($penalty, 2)?></strong></small></p>
                                <?php
                                    if (preg_match('/[1-9]/', $fetch['date_released'])){ 
                                        echo '<p><small>Date Released: <strong>'.date("M d, Y", strtotime($fetch['date_released'])).'</strong></small></p>';
                                    }
                                ?>
                                
                            </td>
                            <td>
                                <?php
                                    $payment=$db->conn->query("SELECT * FROM `payment` WHERE `loan_id`='$fetch[loan_id]'") or die($this->conn->error);
                                    $paid = $payment->num_rows;
                                    $offset = $paid > 0 ? " offset $paid ": "";
                                    
                                    
                                    if($fetch['status'] == 2){
                                        $next = $db->conn->query("SELECT * FROM `loan_schedule` WHERE `loan_id`='$fetch[loan_id]' ORDER BY date(due_date) ASC limit 1 $offset ")->fetch_assoc()['due_date'];
                                        $add = (date('Ymd',strtotime($next)) < date("Ymd") ) ?  $penalty : 0;
                                        echo "<p><small>Next Payment Date: <br /><strong>".date('F d, Y',strtotime($next))."</strong></small></p>";
                                        echo "<p><small>Montly Amount: <br /><strong>&#8369; ".number_format($monthly, 2)."</strong></small></p>";
                                        echo "<p><small>Penalty: <br /><strong>&#8369; ".$add."</strong></small></p>";
                                        echo "<p><small>Payable Amount: <br /><strong>&#8369; ".number_format($monthly+$add, 2)."</strong></small></p>";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php 
                                    if($fetch['status']==0){
                                        echo '<span class="badge badge-warning">For Approval</span>';
                                    }else if($fetch['status']==1){
                                        echo '<span class="badge badge-info">Approved</span>';
                                    }else if($fetch['status']==2){
                                        echo '<span class="badge badge-primary">Released</span>';
                                    }else if($fetch['status']==3){
                                        echo '<span class="badge badge-success">Completed</span>';
                                    }else if($fetch['status']==4){
                                        echo '<span class="badge badge-danger">Denied</span>';
                                    }
                                    
                                ?>
                            </td>
                            <td>
                                <?php 
                                    if($fetch['status']==2){
                                ?>
                                    <button class="btn btn-sm btn-primary" href="#" data-toggle="modal" data-target="#viewSchedule<?php echo $fetch['loan_id']?>">View Payment Schedule</button>
                                <?php
                                    }else if($fetch['status']==3){
                                ?>
                                    <button class="btn btn-lg btn-success" readonly="readonly">COMPLETED</button>
                                <?php
                                    }else{
                                ?>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item bg-warning text-white" href="#" data-toggle="modal" data-target="#updateloan<?php echo $fetch['loan_id']?>">Edit</a>
                                            <a class="dropdown-item bg-danger text-white" href="#" data-toggle="modal" data-target="#deleteborrower<?php echo $fetch['loan_id']?>">Delete</a>
                                        </div>
                                    </div>
                                <?php
                                    }
                                ?>
                            </td>
                        </tr>
                        
                        
                        <!-- Update User Modal -->
                        <div class="modal fade" id="updateloan<?php echo $fetch['loan_id']?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <form method="POST" action="updateLoan.php">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title text-white">Edit Loan</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <div class="form-group col-xl-6 col-md-6">
                                                    <label>Borrower</label>
                                                    <br />
                                                    <input type="hidden" value="<?php echo $fetch['loan_id']?>" name="loan_id"/>
                                                    <select name="borrower" class="borrow" required="required" style="width:100%;">
                                                        <?php
                                                            $tbl_borrower=$db->display_borrower();
                                                            while($row=$tbl_borrower->fetch_array()){
                                                        ?>
                                                            <option value="<?php echo $row['borrower_id']?>" <?php echo ($fetch['borrower_id']==$row['borrower_id'])?'selected':''?>><?php echo $row['lastname'].", ".$row['firstname']." ".substr($row['middlename'], 0, 1)?>.</option>
                                                        <?php
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-xl-6 col-md-6">
                                                    <label>Loan type</label>
                                                    <br />
                                                    <select name="ltype" class="loan" required="required" style="width:100%;">
                                                        <?php
                                                            $tbl_ltype=$db->display_ltype();
                                                            while($row=$tbl_ltype->fetch_array()){
                                                        ?>
                                                            <option value="<?php echo $row['ltype_id']?>" <?php echo ($fetch['ltype_id']==$row['ltype_id'])?'selected':''?>><?php echo $row['ltype_name']?></option>
                                                        <?php
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-xl-6 col-md-6">
                                                    <label>Loan Plan</label>
                                                    <select name="lplan" class="form-control" required="required" id="ulplan">
                                                        <?php
                                                            $tbl_lplan=$db->display_lplan();
                                                            while($row=$tbl_lplan->fetch_array()){
                                                        ?>
                                                            <option value="<?php echo $row['lplan_id']?>" <?php echo ($fetch['lplan_id']==$row['lplan_id'])?'selected':''?>><?php echo $row['lplan_month']." months[".$row['lplan_interest']."%, ".$row['lplan_penalty']."%]"?></option>
                                                        <?php
                                                            }
                                                        ?>
                                                    </select>
                                                    <label>Months[Interest%, Penalty%]</label>
                                                </div>
                                                <div class="form-group col-xl-6 col-md-6">
                                                    <label>Loan Amount</label>
                                                    <input type="number" name="loan_amount" class="form-control" id="uamount" value="<?php echo $fetch['amount']?>" required="required"/>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-xl-6 col-md-6">
                                                    <label>Purpose</label>
                                                    <textarea name="purpose" class="form-control" style="resize:none; height:200px;" required="required"><?php echo $fetch['purpose']?></textarea>
                                                </div>
                                                <div class="form-group col-xl-6 col-md-6">
                                                    <button type="button" class="btn btn-primary btn-block" id="updateCalculate">Calculate Amount</button>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-xl-4 col-md-4">
                                                    <center><span>Total Payable Amount</span></center>
                                                    <center><span id="utpa"><?php echo "&#8369; ".number_format($totalAmount, 2)?></span></center>
                                                </div>
                                                <div class="col-xl-4 col-md-4">
                                                    <center><span>Monthly Payable Amount</span></center>
                                                    <center><span id="umpa"><?php echo "&#8369; ".number_format($monthly, 2)?></span></center>
                                                </div>
                                                <div class="col-xl-4 col-md-4">
                                                    <center><span>Penalty Amount</span></center>
                                                    <center><span id="upa"><?php echo "&#8369; ".number_format($penalty, 2)?></span></center>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-row">
                                                <div class="form-group col-xl-6 col-md-6">
                                                    <label>Status</label>
                                                    <select class="form-control" name="status">
                                                        <?php
                                                            if($fetch['status']==4){
                                                        ?>
                                                            <option value="0" <?php echo ($fetch['status']==0)?'selected':''?>>For Approval</option>
                                                            <option value="1" <?php echo ($fetch['status']==1)?'selected':''?>>Approved</option>
                                                            <option value="4" <?php echo ($fetch['status']==4)?'selected':''?>>Denied</option>
                                                        <?php
                                                            }else if($fetch['status']==2){
                                                        ?>
                                                            <option value="2" readonly="readonly">Released</option>
                                                        <?php
                                                            }else{
                                                        ?>
                                                            <option value="0" <?php echo ($fetch['status']==0)?'selected':''?>>For Approval</option>
                                                            <option value="1" <?php echo ($fetch['status']==1)?'selected':''?>>Approved</option>
                                                            <option value="2" <?php echo ($fetch['status']==2)?'selected':''?>>Released</option>
                                                            <option value="4" <?php echo ($fetch['status']==4)?'selected':''?>>Denied</option>
                                                        <?php
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <button type="submit" name="update" class="btn btn-warning">Update</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        
                        
                        <!-- Delete Loan Modal -->
                        
                        <div class="modal fade" id="deleteborrower<?php echo $fetch['loan_id']?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h5 class="modal-title text-white">System Information</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">Are you sure you want to delete this record?</div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                        <a class="btn btn-danger" href="deleteLoan.php?loan_id=<?php echo $fetch['loan_id']?>">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- View Payment Schedule -->
                        <div class="modal fade" id="viewSchedule<?php echo $fetch['loan_id']?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-info">
                                        <h5 class="modal-title text-white">Payment Schedule</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-5 col-xl-5">
                                                <p>Reference No:</p>
                                                <p><strong><?php echo $fetch['ref_no']?></strong></p>
                                            </div>
                                            <div class="col-md-7 col-xl-7">
                                                <p>Name:</p>
                                                <p><strong><?php echo $fetch['firstname']." ".substr($fetch['middlename'], 0, 1).". ".$fetch['lastname']?></strong></p>
                                            </div>
                                        </div>
                                        <hr />
                                        
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-sm-6"><center>Months</center></div>
                                                <div class="col-sm-6"><center>Monthly Payment</center></div>
                                            </div>
                                            <hr />
                                            <?php 
                                                $tbl_schedule=$db->conn->query("SELECT * FROM `loan_schedule` WHERE `loan_id`='".$fetch['loan_id']."'");
                                                
                                                while($row=$tbl_schedule->fetch_array()){
                                            ?>
                                            <div class="row">
                                                <div class="col-sm-6 p-2 pl-5" style="border-right: 1px solid black; border-bottom: 1px solid black;"><strong><?php echo date("F d, Y" ,strtotime($row['due_date']));?></strong></div>
                                                <div class="col-sm-6 p-2 pl-5" style="border-bottom: 1px solid black;"><strong><?php echo "&#8369; ".number_format($monthly, 2); ?></strong></div>
                                            </div>
                                                <?php
                                                }
                                            ?>
                                        
                                        </div>	
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        
                        
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
       
    </div>
</div>