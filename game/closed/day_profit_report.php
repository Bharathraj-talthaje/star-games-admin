<?php
include "connection/config.php";
if (!checks("admin"))
{
    redirect("login.php");
}


$gatConfig = query("select * from settings");

while($g = fetch($gatConfig)){
    $data[$g['data_key']] = $g['data'];
    
}


$start_time = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
$end_time = mktime(23, 59, 59, date("m"), date("d"), date("Y"));

$blog = query("SELECT * FROM `transactions` WHERE remark='Deposit' AND created_at > $start_time AND created_at < $end_time order by sn desc");



?>

<!DOCTYPE html>
<html lang="en">


<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Panel</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="css/horizontal-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="images/favicon.png" />
    
    <style>
        .ref {
                background: #4caf50;
                padding: 10px;
                color: white;
                font-size: 15px;
                border-radius: 5px;
                margin-top: 20px;
                display:none;
        }
    </style>
</head>


<body class="sidebar-dark" style="font-family: 'Oxygen', sans-serif;">
  <div class="container-fluid page-body-wrapper">

    <?php include "include/header.php"; ?>

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-body">
                       
                                
                                 <h4 class="card-title" style="margin-top:20px;">Todays deposits (<?php echo date('d/m/Y') ?>)</h4>
                                 
                        <div class="row">
                            <div class="col-12">
                                
                                <div class="table-responsive">
                                    <table id="order-listing" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Sn</th>
                                                    <th>User Mobile</th>
                                                    <th>User Name</th>
                                                    <th>Amount</th>
                                                    <th>Type</th>
                                                    <th>Remark</th>
                                                    <th>Created by</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                        <tbody>

                                            <?php 
                                                $total_deposit=0;
                                            $i = 1; while ($bl = fetch($blog)) {
                                            
                                            if($bl['owner']=="")
                                            {
                                                $owner = "User";
                                            }
                                            else if($bl['owner'] == "admin@gmail.com")
                                            {
                                                $owner = "Admin";
                                            }
                                            else
                                            {
                                                $owner= "Partner";
                                            }
                                            
                                            $getUser = fetch(query("select name from users where mobile='".$bl['user']."'"));
                                            
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php out($bl['user']); ?></td>
                                                <td><?php out($getUser['name']); ?></td>
                                                <td><?php out($bl['amount']); $total_deposit+=$bl['amount']; ?></td>
                                                <td><?php if($bl['type']=="0"){ echo "Deduct"; }else { echo "Added"; }  ?></td>
                                                <td><?php out($bl['remark']); ?></td>
                                                <td><?php out($owner); ?></td>
                                                <td><?php out(date('d/m/y h:i A',$bl['created_at'])); ?></td>
                                                
                                               
                                               
                                            </tr>
                                            <?php $i++; } ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card mt-3">
                    <div class="card-body"> 
                                
                         <h4 class="card-title" style="margin-top:20px;">
                            Total deposit: <b><?php echo $total_deposit; ?></b>
                        </h4>
                         <h4 class="card-title" style="margin-top:20px;">
                            Today's profit: <b class="text-success">
                                <?php 
                                    $percent=$data['profit_percentage']; 
                                    echo round($total_deposit*$percent/100,2); 
                                ?>
                            </b>
                        </h4>
                     </div>
                 </div>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
            <footer class="footer">
                <div class="w-100 clearfix">
                    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
                </div>
            </footer>
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
</div>
<!-- container-scroller -->
<!-- plugins:js -->
<script src="vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<script src="vendors/datatables.net/jquery.dataTables.js"></script>
<script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="js/off-canvas.js"></script>
<script src="js/hoverable-collapse.js"></script>
<script src="js/template.js"></script>
<script src="js/settings.js"></script>
<script src="js/todolist.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="js/data-table.js"></script>
<!-- End custom js for this page-->
</body>

</html>
