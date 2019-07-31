<?php
    session_start(); 
    require './include/config_pos.php';

    $template->set('title', 'Daily Sales Monitoring');
    $template->place('header'); // Set page header code (from inc/templates):
    $m = (isset($_REQUEST['pickmonth'])) ? $_REQUEST['pickmonth'] : date("m");
    $y = date("Y");
    $d = cal_days_in_month(CAL_GREGORIAN,$m,$y);

?>
<style>
  td {
    white-space: nowrap;
  }

  tr {
     animation: animateElement linear 0 !important; 
     animation-iteration-count: 0 !important; 
  }
</style>

<form action="pos.php">

  <div class="table-responsive">

    <table class="table table-sm responsive table-bordered table-dark">
      <thead>
        <tr class="table-success text-dark text-center">
        <?php
          for ($months = 1; $months <= 12; $months++) {
              if($months<=10) {
                  $months="0".$months;
              }
              if($months==$m) {
                  echo "<th><font color='red'>".date("F", mktime(0, 0, 0, $months))."</font></th>";
              } else {
                  echo "<th><a href='daily_sales_monitoring.php?pickmonth=".$months."'>".date("F", mktime(0, 0, 0, $months))."</a></th>";
              }
          }
        ?>
        </tr>
      </thead>
    </table>

    <div id="dvData" style="display:none;">

        <table>
          <thead>
              <tr>                  
                  <th>LUCKY BUNNY PH (Milktea, Snacks, Hangout) SALES MONITORING SHEET</th>
                  <th><?php echo date("F", mktime(0, 0, 0, $m))." / ".$y; ?></th>
                  <?php
                    for ($i = 1; $i <= ($d+1); $i++) {
                        echo "<th>&nbsp;</th>";
                    }
                  ?>
              </tr>
              <tr>
                  <th>Month</th>
                  <?php
                    for ($i = 1; $i <= $d; $i++) {
                        echo "<th>".$i."</th>";
                    }
                  ?>
                  <th>Total</th>
              </tr>
          </thead>
          <tbody>
              <?php 
                  $annual_total=0;
                  $result_category = PDO_FetchAll("SELECT `id`, `category_name` FROM `category_tb` WHERE `ct_deleted` IS NULL");
                  if(count($result_category) > 0) {
                    
                      foreach ($result_category as $row_category) {

                              if($row_category['category_name']) {
                              ?>
                                  <tr>
                                      <th><?php echo $row_category['category_name']; ?></th>
                                      <?php
                                        for ($i = 1; $i <= ($d+2); $i++) {
                                            echo "<th>&nbsp;</th>";
                                        }
                                      ?>
                                  </tr>
                              <?php

                                  $result_product = PDO_FetchAll("SELECT `id`, `product_name` FROM `product_tb` WHERE `category_id`=$row_category[id] AND `pt_deleted` IS NULL");
                                  $c=1;
                                  if(count($result_product) > 0) {
                                    
                                      foreach ($result_product as $row_product) {

                                              if($row_product['product_name']) {
                                              ?>
                                                  <tr>
                                                      <td><?php echo $row_product['product_name']; ?></td>
                                                  
                                              <?php
                                                  $gcount=0;
                                                  $gtotal="0.00";
                                                  for ($i = 1; $i <= $d; $i++) {

                                                      if($i<=10) {
                                                          $i="0".$i;
                                                      }

                                                      $result_productoutput = PDO_FetchRow("SELECT SUM(a.`price`*a.`quantity`) as 'grand_total',SUM(a.`quantity`) as 'items_cnt' FROM `transactionitems_tb` a INNER JOIN `transaction_tb` b ON a.`transact_id`=b.`id` WHERE strftime('%Y', a.`date_created`) = '".$y."' AND strftime('%m', a.`date_created`) = '".$m."' AND strftime('%d', a.`date_created`) = '".$i."' AND a.`product_id`=$row_product[id] AND a.`ti_voided` IS NULL AND b.`tt_voided` IS NULL AND b.`tt_completed` IS NOT NULL");

                                                      echo "<td>".(($result_productoutput['items_cnt']) ? $result_productoutput['items_cnt']: "")."</td>";
                                                      $gcount+=(($result_productoutput['items_cnt']) ? $result_productoutput['items_cnt']: 0);
                                                      $gtotal+=(($result_productoutput['grand_total']) ? number_format($result_productoutput['grand_total'], 2): 0.00);

                                                  }

                                              ?>
                                                      <td><?php echo $gcount." - ".$gtotal; ?></td>
                                                  </tr>
                                              <?php
                                              $annual_total+=$gtotal;

                                              }

                                      } 
                                  }

                              }

                      } 
                  }

                  $result_addons = PDO_FetchAll("SELECT `id`, `addons_name` FROM `addons_tb` WHERE `at_deleted` IS NULL");
                  if(count($result_addons) > 0) {
                    
                    ?>
                    <tr>
                        <th>Add-Ons</th>
                        <?php
                          for ($i = 1; $i <= ($d+2); $i++) {
                              echo "<th>&nbsp;</th>";
                          }
                        ?>
                    </tr>
                    <?php

                      foreach ($result_addons as $row_addons) {

                              if($row_addons['addons_name']) {

                                ?>
                                  <tr>
                                      <td><?php echo $row_addons['addons_name']; ?></td>
                                  
                                <?php
                                    $gcount_adds=0;
                                    $gtotal_adds="0.00";
                                    for ($da = 1; $da <= $d; $da++) {
                                        $addons_display=0;
                                        $result_productoutput = PDO_FetchAll("SELECT `addons_metajson` FROM `transactionitems_tb` WHERE strftime('%Y', date_created) = '".$y."' AND strftime('%m', date_created) = '".$m."' AND strftime('%d', date_created) = '".$da."' AND `ti_voided` IS NULL");

                                        $product_display=array();
                                        if(count($result_productoutput) > 0) {
                                          
                                          foreach ($result_productoutput as $row_items) {

                                              if(isJson($row_items['addons_metajson'])) {

                                                foreach (json_decode($row_items['addons_metajson']) as $row_subitems => $subitems) {

                                                  if($row_subitems==$row_addons['id']) {
                                                      $gtotal_adds+=$subitems;
                                                      $addons_display++;
                                                      $gcount_adds+=$addons_display;
                                                  }

                                                }

                                              }

                                          }

                                        }

                                        echo "<td>".(($addons_display) ? $addons_display: "")."</td>";

                                    }

                                ?>
                                        <td><?php echo $gcount_adds." - ".number_format($gtotal_adds, 2); ?></td>
                                    </tr>
                                <?php
                                $annual_total+=$gtotal_adds;

                              }
                      }
                  }
              ?>
          </tbody>
          <tfoot>
            <tr>
                <?php
                  for ($i = 1; $i <= ($d+1); $i++) {
                      echo "<th>&nbsp;</th>";
                  }
                ?>
                <th><b>Php <?php echo number_format($annual_total, 2); ?></b></th>
            </tr>
          </tfoot>
      </table>

    </div>

    <table id="data-table-dailysalesmonitoring" data-date="<?php echo date("Ymd")."-dailysales"; ?>" class="table table-sm responsive table-bordered table-dark">
          <thead>
              <tr class="table-success text-dark text-center">
                  <th><?php echo date("F", mktime(0, 0, 0, $m))." / ".$y; ?></th>
                  <th colspan="<?php echo $d+1; ?>">LUCKY BUNNY PH (Milktea, Snacks, Hangout) SALES MONITORING SHEET</th>
              </tr>
              <tr class="table-success text-dark text-center">
                  <th>Month</th>
                  <?php
                    for ($i = 1; $i <= $d; $i++) {
                        echo "<th>".$i."</th>";
                    }
                  ?>
                  <th>Total</th>
              </tr>
          </thead>
          <tbody>
              <?php 
                  $annual_total=0;
                  $result_category = PDO_FetchAll("SELECT `id`, `category_name` FROM `category_tb` WHERE `ct_deleted` IS NULL");
                  if(count($result_category) > 0) {
                    
                      foreach ($result_category as $row_category) {

                              if($row_category['category_name']) {
                              ?>
                                  <tr class="table-success text-center text-secondary">
                                      <th colspan="<?php echo $d+2; ?>"><?php echo $row_category['category_name']; ?></th>
                                  </tr>
                              <?php

                                  $result_product = PDO_FetchAll("SELECT `id`, `product_name` FROM `product_tb` WHERE `category_id`=$row_category[id] AND `pt_deleted` IS NULL");
                                  $c=1;
                                  if(count($result_product) > 0) {
                                    
                                      foreach ($result_product as $row_product) {

                                              if($row_product['product_name']) {
                                              ?>
                                                  <tr class="table-light text-dark">
                                                      <td><?php echo $row_product['product_name']; ?></td>
                                                  
                                              <?php
                                                  $gcount=0;
                                                  $gtotal="0.00";
                                                  for ($i = 1; $i <= $d; $i++) {

                                                      if($i<=10) {
                                                          $i="0".$i;
                                                      }
                                                      
                                                      $result_productoutput = PDO_FetchRow("SELECT SUM(a.`price`*a.`quantity`) as 'grand_total',SUM(a.`quantity`) as 'items_cnt' FROM `transactionitems_tb` a INNER JOIN `transaction_tb` b ON a.`transact_id`=b.`id` WHERE strftime('%Y', a.`date_created`) = '".$y."' AND strftime('%m', a.`date_created`) = '".$m."' AND strftime('%d', a.`date_created`) = '".$i."' AND a.`product_id`=$row_product[id] AND a.`ti_voided` IS NULL AND b.`tt_voided` IS NULL AND b.`tt_completed` IS NOT NULL");

                                                      echo "<td>".(($result_productoutput['items_cnt']) ? $result_productoutput['items_cnt']: "")."</td>";
                                                      $gcount+=(($result_productoutput['items_cnt']) ? $result_productoutput['items_cnt']: 0);
                                                      $gtotal+=(($result_productoutput['grand_total']) ? number_format($result_productoutput['grand_total'], 2): 0.00);

                                                  }

                                              ?>
                                                      <td><?php echo $gcount." - ".number_format($gtotal, 2); ?></td>
                                                  </tr>
                                              <?php
                                              $annual_total+=$gtotal;

                                              }

                                      } 
                                  }

                              }

                      } 
                  }

                  $result_addons = PDO_FetchAll("SELECT `id`, `addons_name` FROM `addons_tb` WHERE `at_deleted` IS NULL");
                  if(count($result_addons) > 0) {
                    
                    ?>
                    <tr class="table-success text-center text-secondary">
                        <th colspan="<?php echo $d+2; ?>">Add-Ons</th>
                    </tr>
                    <?php

                      foreach ($result_addons as $row_addons) {

                              if($row_addons['addons_name']) {

                                ?>
                                  <tr class="table-light text-dark">
                                      <td><?php echo $row_addons['addons_name']; ?></td>
                                  
                                <?php
                                    $gcount_adds=0;
                                    $gtotal_adds="0.00";
                                    for ($da = 1; $da <= $d; $da++) {
                                        $addons_display=0;
                                        $result_productoutput = PDO_FetchAll("SELECT `addons_metajson` FROM `transactionitems_tb` WHERE strftime('%Y', date_created) = '".$y."' AND strftime('%m', date_created) = '".$m."' AND strftime('%d', date_created) = '".$da."' AND `ti_voided` IS NULL");

                                        $product_display=array();
                                        if(count($result_productoutput) > 0) {
                                          
                                          foreach ($result_productoutput as $row_items) {

                                              if(isJson($row_items['addons_metajson'])) {

                                                foreach (json_decode($row_items['addons_metajson']) as $row_subitems => $subitems) {

                                                  if($row_subitems==$row_addons['id']) {
                                                      $gtotal_adds+=$subitems;
                                                      $addons_display++;
                                                      $gcount_adds+=$addons_display;
                                                  }

                                                }

                                              }

                                          }

                                        }

                                        echo "<td>".(($addons_display) ? $addons_display: "")."</td>";

                                    }

                                ?>
                                        <td><?php echo $gcount_adds." - ".number_format($gtotal_adds, 2); ?></td>
                                    </tr>
                                <?php
                                $annual_total+=$gtotal_adds;

                              }
                      }
                  }
              ?>
          </tbody>
          <tfoot>
            <tr class="table-success text-dark text-center">
                <th colspan="<?php echo $d+1; ?>">&nbsp;</th>
                <th><b>Php <?php echo number_format($annual_total, 2); ?></b></th>
            </tr>
          </tfoot>
      </table>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="col text-center">
        <button type="submit" class="btn btn-secondary">Go Back</button>
        <button type="button" class="btn btn-primary" id="btnExport">Download Excel</button>
        <button type="button" class="btn btn-primary" onclick="htmltocanvas();">Download Image</button>
      </div>
    </div>
  </div>

</form>

<?php
  // Set page footer code (from inc/templates):
  $template->place('footer');
?>
<script type="text/javascript" src="js\html2canvas.min.js"></script>
<script type="text/javascript" src="js\html2canvas.js"></script>
<script>
  $("#btnExport").click(function (e) {
      $('#dvData').show();
      var e_window = window.open('data:application/vnd.ms-excel,' + $('#dvData').html());
      e.preventDefault();
      $('#dvData').hide();
      e_window.close();
  });

  function htmltocanvas() {

      html2canvas(document.querySelector("#data-table-dailysalesmonitoring")).then(canvas => {
          // document.body.appendChild(canvas)
          download(canvas, document.getElementById("data-table-dailysalesmonitoring").getAttribute("data-date")+'.png');
      });

  }
</script>