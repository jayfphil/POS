<?php
    session_start(); 
    require './include/config_pos.php';
    // $date_format="2019-05-21";
    $template->set('title', 'Welcome');
    $template->place('header'); // Set page header code (from inc/templates):

    $result_category = PDO_FetchAll("SELECT `id`, `category_name` FROM `category_tb` WHERE `ct_deleted` IS NULL");
    $result_pending = PDO_FetchAll("SELECT `id`, `customer_name`, `total_amt`, `total_amtdisc` FROM `transaction_tb` WHERE `tt_voided` IS NULL AND `tt_completed` IS NULL AND DATE(`date_created`) LIKE  '%".$date_format."%'");
?>

    <div id="wrapper" class="toggled">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <div class="tab-content">
                  <?php 

                    if(count($result_category) > 0) {

                      foreach ($result_category as $row_category) {

                          echo "<div class='tab-pane' id='sideb".$row_category['id']."'>";
                          echo "<li class='sb_label'><label><a href='#' class='sideclose' title='Close'><img src='images/arrow.png' title='Close' class='img-responsive grayscale'></a>".$row_category['category_name']."</label></li>";

                          $result_product = PDO_FetchAll("SELECT `id`, `product_name`, `price`, `product_codetemp`, `quantity` FROM `product_tb` WHERE `pt_deleted` IS NULL AND `category_id`=$row_category[id] ORDER BY `product_name` ASC ");
                          if(count($result_product) > 0) {
                            foreach ($result_product as $row_product) {
                           
                              // echo '<button type="button" class="btn btn-outline-info btn-lg addtolist" >'.$row_product['product_name'].'</button>'; str_replace("'",  "&apos;",$row_product['product_codetemp'])
                              echo "<li><a class='addtolist' href='#' data-name='".$row_product['product_name']."' data-id='".$row_product['id']."' data-price='".$row_product['price']."' data-code='".$row_product['product_codetemp']."' data-count='".$row_category['id']."-".$row_product['id']."'> ".$row_product['product_name']."</a></li>";
                              // <span style='display:inline !important;' id='".$row_category['id']."-".$row_product['id']."' class='badge badge-warning'>".$row_product['quantity']."</span>
                            } 
                          }
                          echo "</div>";

                      } 
                    }

                  ?>

                </div>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">

              <!-- Transaction Table area Start-->
              <div class="data-table-area">
                  <form method="POST" id="form_transaction" action="receipt.php" autocomplete="off">
                          <input type="hidden" name="userid" value="<?php echo @$_SESSION["username"]; ?>">
                          <div class="row">
                              <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">

                                  <div class="row">

                                    <div id="hiddenclear" class="clearfix"><br /></div>
                                    <img src="img/LuckyBunnyLogo1.ico" class="img-responsive roundlogo" alt="" />
                                    <div class="amount-block">
                                      <div class="totalstyle amount">
                                        <div class="side-heading">Total</div>
                                        <div class="amount total-amount">
                                            <p>Php <span id="totalamt">0.00</span></p> 
                                          </div>
                                      </div>
                                      <div class="totalstyle amount-due">
                                        <div class="side-heading">Amount Due</div>
                                        <div class="amount">
                                            <p>Php <span id="amountdue">0.00</span></p>       
                                        </div>
                                      </div>
                                      <div class="totalstyle amount-discount">
                                        <div class="side-heading">Discount All</div>
                                        <div class="amount">
                                            <p><span id="discount_allval">0%</span></p>       
                                        </div>
                                      </div>

                                    </div>
                                  
                                  </div>
                                  <div class="row">
                                         
                                          <div class="table-responsive">
                                              <table id="data-table-transact" class="table table-sm">
                                                  <thead class="thead-dark">
                                                      <tr>
                                                          <th></th>
                                                          <th>Product Name</th>
                                                          <th>Product Code</th>
                                                          <th>Price</th>
                                                          <th>Discount</th>
                                                          <th>Action</th>
                                                          <th></th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                      <tr style="display:none;">
                                                          <td></td>
                                                          <td></td>
                                                          <td></td>
                                                          <td></td>
                                                          <td></td>
                                                          <td></td>
                                                          <td></td>
                                                      </tr>
                                                  </tbody>
                                              </table>
                                          </div>
                                    
                                  </div>
                              </div>
                              <div class="col-lg-4 col-md-8 col-sm-8 col-xs-12">

                                  <div class="row">

                                    <!-- <div id="accordion">
                                      <h3>Calculator</h3>
                                      <div> -->
                                        <div class="calc-container">
                                          <div class="calc-body calculator">
                                            <div class="calc-screen">
                                              <div class="calc-operation history">
                                                  <label>Customer Name :</label>
                                                  <input class="form-control form-control-sm" type="text" required name="customer_name" placeholder="Enter Customer Name" title="Enter Customer Name">
                                                  <input type="hidden" name="discount_all" value="0">
                                              </div>
                                              <div class="screen">
                                                <span class="last"></span>
                                                <span id="cashtend" class="calc-typed total">0</span>
                                              </div>
                                            </div>
                                            <div class="calc-button-row">
                                              <button type="button" class="calc-button l calc_cls imp" value="C/E" title="Clear">CE</button>
                                              <button type="button" class="calc-button calc_int" value="0" title="Zero">0</button>
                                              <button type="button" class="calc-button calc_dec" value="." title="Decimal">.</button>
                                              <button type="button" class="calc-button calc-button-sm l calc_bac imp" value="&larr;" title="Backspace">&larr;</button>
                                            </div>
                                            <div class="calc-button-row">
                                              <button type="button" class="calc-button calc_int" value="7" title="Seven">7</button>
                                              <button type="button" class="calc-button calc_int" value="8" title="Eight">8</button>
                                              <button type="button" class="calc-button calc_int" value="9" title="Nine">9</button>

                                              <button type="button" class="calc-button l discitem" data-toggle='modal' data-target='#discounts_modal' title="Discount entire sale">Dis%</button>

                                              <button type="button" class="calc-button l modals user_logout" data-toggle="modal" data-target="#confirm_modal" title="Log Out">LOut</button>

                                            </div>
                                            <div class="calc-button-row">
                                              <button type="button" class="calc-button calc_int" value="4" title="Four">4</button>
                                              <button type="button" class="calc-button calc_int" value="5" title="Five">5</button>
                                              <button type="button" class="calc-button calc_int" value="6" title="Six">6</button>

                                              <button type="button" class="calc-button l" data-toggle="modal" data-target="#customers_modal" title="Customers">Cust</button>
                                              <button type="button" class="calc-button l" data-toggle="modal" data-target="#voidlist_modal" title="List of Voided Transactions">Void</button>

                                            </div>
                                            <div class="calc-button-row">
                                              <button type="button" class="calc-button calc_int" value="1" title="One">1</button>
                                              <button type="button" class="calc-button calc_int" value="2" title="Two">2</button>
                                              <button type="button" class="calc-button calc_int" value="3" title="Three">3</button>

                                              <a href="cashier.php"><button type="button" class="calc-button l" title="Daily Report">DRpt</button></a>
                                              <?php if(@$_SESSION['username']==1) { ?><a href="summary_report.php"><button type="button" class="calc-button c" title="Administrator">Admin</button></a> <?php } ?>
                                            </div>
                                            <div class="calc-button-row">

                                              <button type="submit" id="pay" class="calc-button calc-button-md l imp btn-lg" data-type="success" title="Proceed Payment">Submit</button>

                                            </div>
                                          </div>
                                          <ul style="display:none" class="history-list"></ul>
                                        </div>
                                      <!-- </div>
                                    </div> -->
                                    
                                  </div>
                                  <div class="row">
                                  
                                    <div class="calc-container">
                                        <div class="calc-body calculator">
                                            <div class="calc-button-row nav">

                                              <?php 

                                                  if(count($result_category) > 0) {
                                             
                                                    foreach ($result_category as $row_category) {
                                             
                                                      echo '<a data-toggle="tab" href="#sideb'.$row_category['id'].'" class="sideclick calc-button l calc-button-lg" title="'.$row_category['category_name'].'">'.$row_category['category_name'].'</a>';

                                                    } 

                                                  }

                                              ?>

                                            </div>
                                        </div>
                                    </div>

                                  </div>

                              </div>
                          </div>
                  </form>
              </div>
              <!-- Transaction Table area End-->
    </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->
 
  <footer>

    <div class="icon-bar">
      <!-- align-bottom -->
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pendingorders_modal">
        Pending Orders <span class='badge badge-light text-white' id="pending_count"><?php if(count($result_pending)>0) { echo count($result_pending); } ?></span>
        <span class="sr-only">unread messages</span>
      </button> 
    </div>
    
  </footer>

<?php
  // Set page footer code (from inc/templates):
  $template->place('footer');
?>

<!-- The Modals -->
<div class="modal fade" id="customers_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Today's Customers</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
            <table id="data-table-customers" class="table table-hover table-md responsive">
                <thead class="thead-dark">
                    <tr>
                        <th>Transaction No. </th>
                        <th>Customer Name</th>
                        <th>Transaction Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        
                        $result_customers = PDO_FetchAll("SELECT `id`,`customer_name`, `date_created` FROM `transaction_tb` WHERE DATE(`date_created`) LIKE  '%".$date_format."%'");
              
                        if(count($result_customers) > 0) {
                          
                            foreach ($result_customers as $row_customers) {
                                    
                                    if($row_customers['customer_name']) {
                                    ?>
                                        <tr>
                                            <td><?php echo str_pad($row_customers['id'], 5, '0', STR_PAD_LEFT); ?></td>
                                            <td><?php echo $row_customers['customer_name']; ?></td>
                                            <td><?php echo $row_customers['date_created']; ?></td>
                                        </tr>
                                    <?php
                                    }

                            } 
                        }
                    ?>
                </tbody>
            </table>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="voidlist_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Today's List of Void Transaction</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
            <table id="data-table-voided" class="table table-hover table-md responsive">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Order Code</th>
                        <th>Qty.</th>
                        <th>Name</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 

                        $result_voided = PDO_FetchAll("SELECT `id`, `customer_name`, `total_amt` FROM `transaction_tb` WHERE `tt_voided` IS NOT NULL AND DATE(`date_created`) LIKE  '%".$date_format."%'");
                        if(count($result_voided) > 0) {
                            foreach ($result_voided as $row_voided) {

                              $voided_items = PDO_FetchRow("SELECT GROUP_CONCAT(b.`product_codetemp`) AS 'code_temp', SUM(a.`quantity`) AS 'all_qty' FROM `transactionitems_tb` a INNER JOIN `product_tb` b ON a.`product_id`=b.`id` WHERE a.`transact_id`=$row_voided[id]");
                              echo "<tr><td>".$row_voided['id']."</td><td>".$voided_items['code_temp']."</td><td>".$voided_items['all_qty']."</td><td>".$row_voided['customer_name']."</td><td>Php ".number_format($row_voided['total_amtdisc'], 2, ".", "")."</td></tr>";

                            } 
                        }
                    ?>
                </tbody>
            </table>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="discounts_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Discounts (%)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

          <div class="form-group">
            <label for="disc_rate">Discount All Rate</label>
            <input type="number" class="form-control" id="disc_rate" min="0" max="100" placeholder="Enter Rate">
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="apply_discount" class="btn btn-primary">Apply</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="pendingorders_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><span id="date_time"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="table-responsive">
            <table id="data-table-pending" class="table table-hover table-lg responsive">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Order Code</th>
                        <th>Qty.</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Done</th>
                        <th>Void</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        
                        if(count($result_pending) > 0) {
                            foreach ($result_pending as $row_pending) {

                                $result_items = PDO_FetchAll("SELECT b.`product_codetemp`, a.`quantity`, a.`addons_metajson`, a.`price`, a.`discount` FROM `transactionitems_tb` a INNER JOIN `product_tb` b ON a.`product_id`=b.`id` WHERE a.`transact_id`=$row_pending[id] AND a.`ti_voided` IS NULL");
                                $product_display=array();
                                $count=0;
                                $pend_total=0;
                                if(count($result_items) > 0) {

                                  foreach ($result_items as $row_items) {

                                      $addons_display="";

                                      if(isJson($row_items['addons_metajson'])) {
                                          foreach (json_decode($row_items['addons_metajson']) as $row_subitems => $subitems) {

                                            if($row_subitems=="free") {
                                                $explode_free = explode("-",$subitems);
                                                $row_subitems = $explode_free[0];
                                            } 

                                            $result_addons = PDO_FetchOne("SELECT `addons_codetemp` FROM `addons_tb` WHERE `id`='".$row_subitems."' AND `at_deleted` IS NULL ");
                                            $addons_display.=" + ".$result_addons;
                                          }
                                      }
                                      $product_display[]=$row_items['product_codetemp'].$addons_display;
                                      $count++;
                                  }

                                  echo "<tr><td>".str_pad($row_pending['id'], 5, '0', STR_PAD_LEFT)."</td><td>".implode(" / ",$product_display)."</td><td>".$count."</td><td>".$row_pending['customer_name']."</td><td>Php ".number_format($row_pending['total_amtdisc'], 2, ".", "")."</td><td><a href='#' class='done_order' data-toggle='modal' data-target='#confirm_modal' data-id='".$row_pending['id']."'><img src='images/check.png' title='Done' class='img-responsive'></a></td><td><a href='#' class='void_order' data-toggle='modal' data-target='#confirm_modal' data-user='".$_SESSION['username']."' data-id='".$row_pending['id']."'><img src='images/cross.png' title='Void' class='img-responsive'></a></td></tr>";

                              }

                            } 
                        }
                    ?>
                </tbody>
            </table>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="confirm_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-success" data-dismiss="modal" id="confirmsubmit">Yes</button>
      </div>
    </div>
  </div>
</div>

<!-- The Modals -->
<script src="js/nojs/realtimedate.js"></script>
<script type="text/javascript">window.onload = date_time('date_time');</script>