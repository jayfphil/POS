<?php
    session_start(); 
    require './include/config_pos.php';

    $template->set('title', 'Monthly Report Summary');
    $template->place('header'); // Set page header code (from inc/templates):
    $m = (isset($_REQUEST['pickmonth']) && !empty($_REQUEST['pickmonth'])) ? $_REQUEST['pickmonth'] : date("m");
    $y = (isset($_REQUEST['pickyear']) && !empty($_REQUEST['pickyear'])) ? $_REQUEST['pickyear'] : date("Y");
    $fixmonth = 12;
    $fixyear = 2018;
    $d = cal_days_in_month(CAL_GREGORIAN,$m,$y);

    $result_existmonthlyreport = PDO_FetchRow("SELECT * FROM `sales_monthly_report_summary` WHERE `year` = '".$y."' AND `month` = '".$m."' ");

    if(isset($_POST['saved'])) {
        // print_r($_POST);

        if($result_existmonthlyreport) {
            PDO_Execute("UPDATE `sales_monthly_report_summary` SET `team_leader`='".$_POST['prepared_by']."',`total_gross_income`='".$_POST['total_gross_income']."', `space_rental`='".$_POST['space_rental']."', `total_ops_expenses`='".$_POST['total_ops_expenses']."', `payroll_fifth`='".$_POST['payroll_fifth']."', `payroll_twenty`='".$_POST['payroll_twenty']."', `total_net_income`='".$_POST['total_net_income']."', `electric_bill`='".$_POST['electric_bill']."', `total_bank_deposits`='".$_POST['total_bank_deposits']."', `water_bill`='".$_POST['water_bill']."', `taxes`='".$_POST['taxes']."', `balance`='".$_POST['balance']."' WHERE `year` = '".$y."' AND `month` = '".$m."'");

            for ($i = 1; $i <= $d; $i++) {

                  if($i<=10) {
                      $i="0".$i;
                  }

                  $result_salesdaily = PDO_FetchRow("SELECT * FROM `sales_management` WHERE `print_flag`=1 AND strftime('%Y', date_created) = '".$y."' AND strftime('%m', date_created) = '".$m."' AND strftime('%d', date_created) = '".$i."'");

                  $result_productoutput = PDO_FetchRow("SELECT SUM(`total_amtdisc`) as 'total_amtdisc',SUM(`cash_tender`) as 'cash_tender' FROM `transaction_tb` WHERE strftime('%Y', date_created) = '".$y."' AND strftime('%m', date_created) = '".$m."' AND strftime('%d', date_created) = '".$i."' AND `tt_voided` IS NULL");

                  $temp_income=(($result_productoutput['total_amtdisc']) ? $result_productoutput['total_amtdisc']: 0);
                  $temp_net_income=(($result_salesdaily['net_income']) ? $result_salesdaily['net_income']: 0);
                  $temp_total_expenses=(($result_salesdaily['total_expenses']) ? $result_salesdaily['total_expenses']: 0);

                  PDO_Execute("UPDATE `sales_monthly_report` SET `gross_income`='".$temp_income."',`expenses`='".$temp_total_expenses."', `net_income`='".$temp_net_income."', `bank_deposit_slip`='".$_POST['bank_slip'][$i]."', `bank_deposit_cash`='".$_POST['bank_cash'][$i]."', `remarks`='".$_POST['bank_remarks'][$i]."' WHERE `day`='".$i."' AND `sales_monthly_report_id` = '".$result_existmonthlyreport['id']."'");
            }

        } else {

            PDO_Execute("INSERT INTO `sales_monthly_report_summary` (`month`,`year`,`team_leader`,`total_gross_income`,`space_rental`,`total_ops_expenses`,`payroll_fifth`,`payroll_twenty`,`total_net_income`,`electric_bill`,`total_bank_deposits`,`water_bill`,`taxes`,`balance`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)",array($m,$y,$_POST['prepared_by'],$_POST['total_gross_income'],$_POST['space_rental'],$_POST['total_ops_expenses'],$_POST['payroll_fifth'],$_POST['payroll_twenty'],$_POST['total_net_income'],$_POST['electric_bill'],$_POST['total_bank_deposits'],$_POST['water_bill'],$_POST['taxes'],$_POST['balance']));

            $last = PDO_LastInsertId();

            for ($i = 1; $i <= $d; $i++) {

                  if($i<=10) {
                      $i="0".$i;
                  }

                  $result_salesdaily = PDO_FetchRow("SELECT * FROM `sales_management` WHERE `print_flag`=1 AND strftime('%Y', date_created) = '".$y."' AND strftime('%m', date_created) = '".$m."' AND strftime('%d', date_created) = '".$i."'");

                  $result_productoutput = PDO_FetchRow("SELECT SUM(`total_amtdisc`) as 'total_amtdisc',SUM(`cash_tender`) as 'cash_tender' FROM `transaction_tb` WHERE strftime('%Y', date_created) = '".$y."' AND strftime('%m', date_created) = '".$m."' AND strftime('%d', date_created) = '".$i."' AND `tt_voided` IS NULL");

                  $temp_income=(($result_productoutput['total_amtdisc']) ? $result_productoutput['total_amtdisc']: 0);
                  $temp_net_income=(($result_salesdaily['net_income']) ? $result_salesdaily['net_income']: 0);
                  $temp_total_expenses=(($result_salesdaily['total_expenses']) ? $result_salesdaily['total_expenses']: 0);

                  PDO_Execute("INSERT INTO `sales_monthly_report` (`sales_monthly_report_id`,`gross_income`,`expenses`,`net_income`,`bank_deposit_slip`,`bank_deposit_cash`,`remarks`,`day`) VALUES(?,?,?,?,?,?,?,?)",array($last,$temp_income,$temp_total_expenses,$temp_net_income,$_POST['bank_slip'][$i],$_POST['bank_cash'][$i],$_POST['bank_remarks'][$i],$i)); 
            }

        }
        $result_existmonthlyreport = PDO_FetchRow("SELECT * FROM `sales_monthly_report_summary` WHERE `year` = '".$y."' AND `month` = '".$m."' ");
        
    }

?>
<style>
    html {
      margin: 2em;
    }

    body {
      background-color: white;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    select.form-control {
      background: transparent;
      border: none;
      border-bottom: 1px solid #000000;
      -webkit-box-shadow: none;
      box-shadow: none;
      border-radius: 0;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    select.form-control:focus {
      background: transparent;
      -webkit-box-shadow: none;
      box-shadow: none;
    }

    textarea {
      resize:none;
    }

    @media print {
        .btn {
           display: none;
        }
    }
  </style>



<!-- PRINT -->

<div id="dvData" style="display:none;">

        <table>
          <thead>
              <tr class="table-success text-dark text-center">
                  <th><?php echo date("F", mktime(0, 0, 0, $m))." / ".$y; ?></th>
                  <th colspan="6">LUCKY BUNNY PH ; SALES MONTHLY REPORT (SUMMARY)</th>
              </tr>
              <tr class="table-success text-dark text-center">
                  <th rowspan="2">Day</th>
                  <th rowspan="2">Gross Income</th>
                  <th rowspan="2">Expenses</th>
                  <th rowspan="2">Net Income</th>
                  <th colspan="2">Bank Deposit Slip Ref. No.</th>
                  <th rowspan="2">Remarks</th>
              </tr>
              <tr class="table-success text-dark text-center">
                  <th>Reference No.</th>
                  <th>Cash</th>
              </tr>
          </thead>
          <tbody>
              <?php

                $total_income=0;
                $total_net_income=0;
                for ($i = 1; $i <= $d; $i++) {

                    if($i<=10) {
                        $i="0".$i;
                    }

                    $result_salesdaily = PDO_FetchRow("SELECT * FROM `sales_management` WHERE `print_flag`=1 AND strftime('%Y', date_created) = '".$y."' AND strftime('%m', date_created) = '".$m."' AND strftime('%d', date_created) = '".$i."'");

                    $result_productoutput = PDO_FetchRow("SELECT SUM(`total_amtdisc`) as 'total_amtdisc',SUM(`cash_tender`) as 'cash_tender' FROM `transaction_tb` WHERE strftime('%Y', date_created) = '".$y."' AND strftime('%m', date_created) = '".$m."' AND strftime('%d', date_created) = '".$i."' AND `tt_voided` IS NULL");

                    $result_existmonthlyreport_sub = PDO_FetchRow("SELECT * FROM `sales_monthly_report` WHERE `day`='".$i."' AND `sales_monthly_report_id` = '".$result_existmonthlyreport['id']."' ");

                    $total_income+=(($result_productoutput['total_amtdisc']) ? $result_productoutput['total_amtdisc']: 0.00);
                    $total_net_income+=(($result_salesdaily['net_income']) ? $result_salesdaily['net_income']: 0.00);

                    echo "<tr class='table-light text-dark text-center'><td>".$i."</td><td><span class='gross_income'>".(($result_productoutput['total_amtdisc']) ? $result_productoutput['total_amtdisc']: 0.00)."</span></td><td><span class='expenses'>".(($result_salesdaily['total_expenses']) ? $result_salesdaily['total_expenses']: 0.00)."</span></td><td><span class='net_income'>".(($result_salesdaily['net_income']) ? $result_salesdaily['net_income']: 0.00)."</span></td><td><input type='text' name='bank_slip[".$i."]' class='form-control' value='".$result_existmonthlyreport_sub['bank_deposit_slip']."'></td><td><input type='number' class='form-control monthly_sales' name='bank_cash[".$i."]' value='".$result_existmonthlyreport_sub['bank_deposit_cash']."'></td><td><textarea name='bank_remarks[".$i."]'>".$result_existmonthlyreport_sub['remarks']."</textarea></td></tr>";

                }
              ?>

              <tr class='table-light text-dark'>
                  <th colspan="8">GRAND TOTAL: <input type='text' class='form-control' id="grand_total" readonly placeholder="PHP"></th>
              </tr>
          </tbody>
      </table>

    </div>

<!-- PRINT -->



<form action="monthly_report_summary.php" method="POST">

  <!-- <div class="fixed-top p-3">
    <div class="container">
      <div class="row">
        <div class="col-sm-4">
          <?php // echo $m." / ".$y; ?>
        </div>
      </div>
    </div>
  </div>
   -->

  <div class="table-responsive">
    
    <table class="table table-sm responsive table-bordered table-dark">
      <thead>
        <tr class="table-success text-dark text-center">
        <?php
          for ($addyear = 1; $addyear <= $fixmonth; $addyear++) {
              if(($fixyear+$addyear)==$y) {
                  echo "<th><font color='red'>".($fixyear+$addyear)."</font></th>";
              } else {
                  echo "<th><a href='monthly_report_summary.php?pickyear=".($fixyear+$addyear)."&pickmonth=".@$_REQUEST['pickmonth']."'>".($fixyear+$addyear)."</a></th>";
              }
          }
        ?>
        </tr>
        <tr class="table-success text-dark text-center">
        <?php
          for ($months = 1; $months <= $fixmonth; $months++) {
              if($months<=10) {
                  $months="0".$months;
              }
              if($months==$m) {
                  echo "<th><font color='red'>".date("F", mktime(0, 0, 0, $months))."</font></th>";
              } else {
                  echo "<th><a href='monthly_report_summary.php?pickyear=".@$_REQUEST['pickyear']."&pickmonth=".$months."'>".date("F", mktime(0, 0, 0, $months))."</a></th>";
              }
          }
        ?>
        </tr>
      </thead>
    </table>

    <table id="data-table-monthlyreportsummary" class="table table-sm responsive table-bordered table-dark">
          <thead>
              <tr class="table-success text-dark text-center">
                  <th><?php echo date("F", mktime(0, 0, 0, $m))." / ".$y; ?></th>
                  <th colspan="6">LUCKY BUNNY PH ; SALES MONTHLY REPORT (SUMMARY)</th>
              </tr>
              <tr class="table-success text-dark text-center">
                  <th rowspan="2">Day</th>
                  <th rowspan="2">Gross Income</th>
                  <th rowspan="2">Expenses</th>
                  <th rowspan="2">Net Income</th>
                  <th colspan="2">Bank Deposit Slip Ref. No.</th>
                  <th rowspan="2">Remarks</th>
              </tr>
              <tr class="table-success text-dark text-center">
                  <th>Reference No.</th>
                  <th>Cash</th>
              </tr>
          </thead>
          <tbody>
              <?php

                $total_income=0;
                $total_net_income=0;
                for ($i = 1; $i <= $d; $i++) {

                    if($i<=10) {
                        $i="0".$i;
                    }

                    $result_salesdaily = PDO_FetchRow("SELECT * FROM `sales_management` WHERE `print_flag`=1 AND strftime('%Y', date_created) = '".$y."' AND strftime('%m', date_created) = '".$m."' AND strftime('%d', date_created) = '".$i."'");

                    $result_productoutput = PDO_FetchRow("SELECT SUM(`total_amtdisc`) as 'total_amtdisc',SUM(`cash_tender`) as 'cash_tender' FROM `transaction_tb` WHERE strftime('%Y', date_created) = '".$y."' AND strftime('%m', date_created) = '".$m."' AND strftime('%d', date_created) = '".$i."' AND `tt_voided` IS NULL");

                    $result_existmonthlyreport_sub = PDO_FetchRow("SELECT * FROM `sales_monthly_report` WHERE `day`='".$i."' AND `sales_monthly_report_id` = '".$result_existmonthlyreport['id']."' ");

                    $total_income+=(($result_productoutput['total_amtdisc']) ? $result_productoutput['total_amtdisc']: 0.00);
                    $total_net_income+=(($result_salesdaily['net_income']) ? $result_salesdaily['net_income']: 0.00);

                    echo "<tr class='table-light text-dark text-center'><td>".$i."</td><td><span class='gross_income'>".(($result_productoutput['total_amtdisc']) ? $result_productoutput['total_amtdisc']: 0.00)."</span></td><td><span class='expenses'>".(($result_salesdaily['total_expenses']) ? $result_salesdaily['total_expenses']: 0.00)."</span></td><td><span class='net_income'>".(($result_salesdaily['net_income']) ? $result_salesdaily['net_income']: 0.00)."</span></td><td><input type='text' name='bank_slip[".$i."]' class='form-control' value='".$result_existmonthlyreport_sub['bank_deposit_slip']."'></td><td><input type='number' class='form-control monthly_sales' name='bank_cash[".$i."]' value='".$result_existmonthlyreport_sub['bank_deposit_cash']."'></td><td><textarea name='bank_remarks[".$i."]'>".$result_existmonthlyreport_sub['remarks']."</textarea></td></tr>";

                }
              ?>

              <tr class='table-light text-dark'>
                  <th colspan="8">GRAND TOTAL: <input type='text' class='form-control' id="grand_total" readonly placeholder="PHP"></th>
              </tr>
          </tbody>
      </table>
  </div>

  <div class="form-group row text-center">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <input type="text" class="form-control" id="prepared_by" name="prepared_by">
    </div>
    <label for="prepared_by" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-form-label"><small><i>(Signature Over Printed Name)</i></small><br /><b>PREPARED BY</b> <i>(TEAM LEADER)</i></label>

  </div>
  <div class="form-group row">

    <label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 col-form-label"><i>Monthly Operational Expenses</i></label>

    <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Total GROSS Income</label>
    <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
      <input type="text" class="form-control" id="total_gross_income" name="total_gross_income" readonly placeholder="PHP" value="<?php echo $total_income; ?>">
    </div>

  </div>
  <div class="form-group row">

    <label for="space_rental" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Space Rental</label>
    <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
      <input type="number" min="0" class="form-control field_expenses" id="space_rental" name="space_rental" value="<?php echo ($result_existmonthlyreport['space_rental']) ? $result_existmonthlyreport['space_rental'] : 0.00; ?>">
    </div>

    <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Total OPS. EXPENSES</label>
    <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
      <input type="text" class="form-control" id="total_ops_expenses" name="total_ops_expenses" readonly placeholder="PHP">
    </div>

  </div>
  <div class="form-group row">

    <label for="payroll_fifth" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Payroll 5th</label>
    <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
      <input type="number" min="0" class="form-control field_expenses" id="payroll_fifth" name="payroll_fifth" value="<?php echo ($result_existmonthlyreport['payroll_fifth']) ? $result_existmonthlyreport['payroll_fifth'] : 0.00; ?>">
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      &nbsp;
    </div>

  </div>
  <div class="form-group row">

    <label for="payroll_twenty" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Payroll 20th</label>
    <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
      <input type="number" min="0" class="form-control field_expenses" id="payroll_twenty" name="payroll_twenty" value="<?php echo ($result_existmonthlyreport['payroll_twenty']) ? $result_existmonthlyreport['payroll_twenty'] : 0.00; ?>">
    </div>

    <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Total NET INCOME</label>
    <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
      <input type="text" class="form-control" id="total_net_income" name="total_net_income" readonly placeholder="PHP" value="<?php echo $total_net_income; ?>">
    </div>

  </div>
  <div class="form-group row">

    <label for="electric_bill" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Electric Bill</label>
    <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
      <input type="number" min="0" class="form-control field_expenses" id="electric_bill" name="electric_bill" value="<?php echo ($result_existmonthlyreport['electric_bill']) ? $result_existmonthlyreport['electric_bill'] : 0.00; ?>" >
    </div>

    <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Total BANK DEPOSITS</label>
    <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
      <input type="text" class="form-control" id="total_bank_deposits" name="total_bank_deposits" readonly placeholder="PHP">
    </div>

  </div>
  <div class="form-group row">

    <label for="water_bill" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Water Bill</label>
    <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
      <input type="number" min="0" class="form-control field_expenses" id="water_bill" name="water_bill" value="<?php echo ($result_existmonthlyreport['water_bill']) ? $result_existmonthlyreport['water_bill'] : 0.00; ?>">
    </div>

    <label for="sample" class="col-lg-6 col-md-6 col-sm-6 col-xs-12 col-form-label"><i>Balance:</i></label>

  </div>
  <div class="form-group row">

    <label for="taxes" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Taxes</label>
    <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
      <input type="number" min="0" class="form-control field_expenses" id="taxes" name="taxes" value="<?php echo ($result_existmonthlyreport['taxes']) ? $result_existmonthlyreport['taxes'] : 0.00; ?>">
    </div>

    <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">&nbsp;</label>
    <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
      <input type="text" class="form-control" id="balance" name="balance" readonly placeholder="PHP">
    </div>

  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="col text-center">
        <a href="pos.php"><button type="button" class="btn btn-secondary">Go Back</button></a>
        <button type="submit" name="saved" class="btn btn-success">Save as Draft</button>
        <button type="button" class="btn btn-info" id="btnExport">Download Excel</button>
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
calc_expenses();
calc_monthlysales();

$("#btnExport").click(function (e) {
      $('#dvData').show();
      var e_window = window.open('data:application/vnd.ms-excel,' + $('#dvData').html());
      e.preventDefault();
      $('#dvData').hide();
      e_window.close();
  });

function htmltocanvas() {

  if(validateForm()) {

    html2canvas(document.querySelector("#invoice-area")).then(canvas => {
        // document.body.appendChild(canvas)
        download(canvas, document.getElementById("invoice-area").getAttribute("data-date")+'.png');
    });

  } else {

    swal("Please fill up all fields!");
    
  }

}

function validateForm() {

  var isValid = true;

  $('.form-control').each(function() {

    if ( $(this).val() === '' )
        isValid = false;

  });

  return isValid;

}

// // we used jQuery 'keyup' to trigger the computation as the user type
$('.monthly_sales').keyup(function () {
    calc_monthlysales();   
});

$('.field_expenses').keyup(function () {
    calc_expenses();   
});

function calc_expenses() {

    var sum = 0;
    $('.field_expenses').each(function() {
        sum += Number($(this).val());
    });

    $('.expenses').each(function() {
        sum += Number($(this).text());
    });

    $('#total_ops_expenses').val(customFixed(sum));
    calc_income();
}

function calc_monthlysales() {

    var sum_bank = 0;
    var sum_netincome = 0;

    $('.monthly_sales').each(function() {
        sum_bank += Number($(this).val());
    });

    $('.net_income').each(function() {
        sum_netincome += Number($(this).text());
    });

    sum_netincome = sum_netincome + sum_bank;

    $('#grand_total').val(customFixed(sum_netincome));
    $('#total_bank_deposits').val(customFixed(sum_bank));
    calc_income();
}

function calc_income() {

    var total_net_income=0;
    var total_grand=0;

    if(parseInt($("#total_gross_income").val()) || parseInt($("#total_ops_expenses").val())) {
        total_net_income = parseInt($("#total_gross_income").val()) - parseInt($("#total_ops_expenses").val());
    }
    // console.log(parseInt($("#total_gross_income").val()));
    // console.log(parseInt($("#total_ops_expenses").val()));
    $('#total_net_income').val(total_net_income);

    if(parseInt($("#total_net_income").val())) {
        total_grand = parseInt($("#total_net_income").val());
    }

    $('#balance').val(total_grand);
  
}

function customFixed(num) {

    if(num % 1 != 0 && num) {
        var with2Decimals = num.toString().match(/^-?\d+(?:\.\d{0,2})?/)[0];
    } else {
        var with2Decimals = parseFloat(num).toFixed(2);
    }
    
    return with2Decimals;
}

</script>