<?php  
// $date = new DateTime();
// $timeZone = $date->getTimezone();
// echo $timeZone->getName();
    session_start(); 
    require './include/config.php';

    $template->set('title', 'Dashboard');
    $template->place('header'); // Set page header code (from inc/templates):

    $result_dailytotal = PDO_FetchRow("SELECT SUM(total_amt) as DailyTotal FROM `transaction_tb` WHERE `tt_voided` IS NULL AND DATE(`date_created`) LIKE  '%".$date_format."%'");

    $result_soldcups = PDO_FetchRow("SELECT SUM(a.quantity) as SoldCups FROM `transactionitems_tb` a INNER JOIN `transaction_tb` c ON a.`transact_id`=c.`id` WHERE c.`tt_voided` IS NULL AND DATE(a.`date_created`) LIKE  '%".$date_format."%'");

    $result_branch = PDO_FetchRow("SELECT * FROM `branch_tb` LIMIT 1");

    $result_items = PDO_FetchAll("SELECT a.`price`, a.`discount`, a.`addons_metajson`, b.`report_category` FROM `transactionitems_tb` a INNER JOIN `product_tb` b ON a.`product_id`=b.`id` INNER JOIN `transaction_tb` c ON a.`transact_id`=c.`id` WHERE c.`tt_voided` IS NULL AND DATE(a.`date_created`) LIKE  '%".$date_format."%'");

    $result_cupsincome=0;
    $result_cakeincome=0;
    $result_snackincomealacarte=0;
    $result_snackincomeunlimited=0;
    $total_discount=0;
    if(count($result_items) > 0) {
      foreach ($result_items as $row_items) {
        
        $dec = ($row_items['discount'] / 100); //its convert 10 into 0.10
        $mult = $row_items['price'] * $dec; // gives the value for subtract from main 
        $total_discount+=$mult;
        // echo $row_items['price']." - ".$mult."<br />";
        if($row_items['report_category']=="Cups"){
            $result_cupsincome+=($row_items['price']-$mult);
        }
        if($row_items['report_category']=="Cakes"){
            $result_cakeincome+=($row_items['price']-$mult);
        }
        if($row_items['report_category']=="Snacks - Ala Carte"){
            $result_snackincomealacarte+=($row_items['price']-$mult);
        }
        if($row_items['report_category']=="Snacks - Unlimited"){
            $result_snackincomeunlimited+=($row_items['price']-$mult);
        }

        if(isJson($row_items['addons_metajson'])) {
          foreach (json_decode($row_items['addons_metajson']) as $row_subitems => $subitems) {

            if($row_subitems=="free") {
                $explode_free = explode("-",$subitems);
                $subitems = $explode_free[1];
            } 

            if($row_items['report_category']=="Cups"){
                $result_cupsincome=$result_cupsincome+number_format(($subitems*1), 2);
            }
            if($row_items['report_category']=="Cakes"){
                $result_cakeincome=$result_cakeincome+number_format(($subitems*1), 2);
            }
            if($row_items['report_category']=="Snacks - Ala Carte"){
                $result_snackincomealacarte=$result_snackincomealacarte+number_format(($subitems*1), 2);
            }
            if($row_items['report_category']=="Snacks - Unlimited"){
                $result_snackincomeunlimited=$result_snackincomeunlimited+number_format(($subitems*1), 2);
            }
            
          }
        }

      }
    }

    $result_cups = PDO_FetchRow("SELECT * FROM `product_tb` WHERE `category_id` = 0 ");

    $result_existdailyreport = PDO_FetchRow("SELECT * FROM `sales_management` WHERE DATE(`date_created`) LIKE  '%".$date_format."%'");

    if(isset($_POST['save']) || isset($_POST['submit'])) {

        $fields=array();
        $values=array();
        $update=array();
        $avoid_post = (isset($_POST['submit'])) ? "submit" : "save";
        foreach ($_POST as $key => $value) {
          if($key<>$avoid_post) {
              $fields[]=$key;
              if(!$value) {
                $value=0;
              }
              $values[]=$value;
              $update[]=$key."='".$value."'";
          }
        }

        if(isset($_POST['submit'])) {
            $fields[]="print_flag";
            $values[]=1;
            $update[]="print_flag=1";
            // PDO_Execute("UPDATE `product_tb` SET `quantity`=$_POST[total_cups] WHERE `category_id`=0 ");
        }
        
        if($result_existdailyreport) {
            PDO_Execute("UPDATE `sales_management` SET ".implode(", ",$update)." WHERE DATE(`date_created`) LIKE  '%".$date_format."%'");

            $result_existdailyreport = PDO_FetchRow("SELECT * FROM `sales_management` WHERE DATE(`date_created`) LIKE  '%".$date_format."%'");
        } else {
            PDO_Execute("INSERT INTO `sales_management` (".implode(", ",$fields).") VALUES('" . implode( "','", $values ) . "')");
        }
        
    }
?>
<form action="cashier.php" method="POST">
    <!-- Breadcomb area Start-->
    <div class="breadcomb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="breadcomb-list">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="breadcomb-wp">
                                    <div class="breadcomb-icon">
                                        <i class="notika-icon notika-edit"></i>
                                    </div>
                                    <div class="breadcomb-ctn">
                                        <h2>Cashier's Daily Report & Sales Management</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
                                <div class="breadcomb-report">
                                    <button type="submit" name="save" data-toggle="tooltip" data-placement="top" title="Save as Draft" class="btn btn-info"><i class="notika-icon notika-refresh"></i></button>
                                    <button type="submit" name="submit" data-toggle="tooltip" data-placement="top" title="Print Receipt" class="btn btn-primary" <?php if($result_existdailyreport['print_flag']==1) { echo "disabled"; } ?> ><i class="notika-icon notika-sent"></i></button>
                                    <!-- onclick="htmltocanvas();" -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcomb area End-->
    <!-- Invoice area Start-->
    <div class="invoice-area" id="invoice-area" data-date="<?php echo date("Ymd")."-dailyreport"; ?>">
        <div class="container">
            <div class="invoice-wrap">
                
                  <fieldset>
                    <div class="row">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="text-center">
                          <b>LUCKY BUNNY PH; CASHIER'S DAILY REPORT & SALES MANAGEMENT</b>
                          <hr />
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">

                      <label for="date_created" class="col-lg-1 col-md-2 col-sm-2 col-xs-12 col-form-label">Date</label>
                      <div class="col-lg-5 col-md-4 col-sm-4 col-xs-12">
                        <div class="nk-int-st">
                            <input type="text" class="form-control" readonly name="date_created" value="<?php echo ($result_existdailyreport['date_created']) ? $result_existdailyreport['date_created']: date("Y-m-d"); ?>"> 
                        </div>
                      </div>

                      <label for="branch" class="col-lg-1 col-md-2 col-sm-2 col-xs-12 col-form-label">Branch</label>
                      <div class="col-lg-5 col-md-4 col-sm-4 col-xs-12">
                        <div class="nk-int-st">
                            <input type="text" class="form-control" id="branch" name="branch" value="<?php echo ($result_branch['branch_name']) ? $result_branch['branch_name']: "[Branch Name]"; ?>" readonly required>
                        </div>
                      </div>

                    </div>
                    <div class="form-group row">

                      <label for="cashier1" class="col-lg-1 col-md-2 col-sm-2 col-xs-12 col-form-label">1st Cashier</label>
                      <div class="col-lg-5 col-md-4 col-sm-4 col-xs-12">
                        <div class="nk-int-st">
                            <input type="text" class="form-control" id="cashier1" name="cashier1" value="<?php echo ($result_existdailyreport['cashier1']) ? $result_existdailyreport['cashier1']: ""; ?>" required>
                        </div>
                      </div>

                      <label for="cashier2" class="col-lg-1 col-md-2 col-sm-2 col-xs-12 col-form-label">2nd Cashier</label>
                      <div class="col-lg-5 col-md-4 col-sm-4 col-xs-12">
                        <div class="nk-int-st">
                            <input type="text" class="form-control" id="cashier2" name="cashier2" value="<?php echo ($result_existdailyreport['cashier2']) ? $result_existdailyreport['cashier2']: ""; ?>" required>
                        </div>
                      </div>

                    </div>

                    <div class="row">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <hr />
                        <h6>PARTICULARS</h6>
                      </div>
                    </div>

                    <div class="form-group row">

                      <label for="released_cups" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Total Number of <b>RELEASED</b> Cups</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="number" min="0" class="form-control" id="released_cups" name="released_cups" value="<?php echo ($result_existdailyreport['released_cups']) ? $result_existdailyreport['released_cups']: $result_cups['quantity']; ?>" readonly >
                        </div>
                      </div>

                      <label for="cups_income" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Cups Income</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="number" min="0" class="form-control" id="cups_income" name="cups_income" value="<?php echo $result_cupsincome; ?>" readonly required>
                        </div>
                      </div>

                    </div>
                    <div class="form-group row">

                      <label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 col-form-label"><i>Less:</i></label>
                      <label for="snacks_alacarte" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Snacks - Ala Carte</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="number" min="0" class="form-control" id="snacks_alacarte" name="snacks_alacarte" value="<?php echo ($result_snackincomealacarte) ? $result_snackincomealacarte:""; ?>" readonly required>
                        </div>
                      </div>

                    </div>
                    <div class="form-group row">

                      <label for="sold_cups" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Total Number of <b>SOLD</b> Cups</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="number" min="0" class="form-control lessen_cups" id="sold_cups" name="sold_cups" value="<?php echo ($result_soldcups['SoldCups']) ? $result_soldcups['SoldCups']: ""; ?>" readonly>
                        </div>
                      </div>

                      <label for="snacks_unlimited" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Snacks - Unlimited</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="number" min="0" class="form-control" id="snacks_unlimited" name="snacks_unlimited" value="<?php echo ($result_snackincomeunlimited) ? $result_snackincomeunlimited:""; ?>" readonly required>
                        </div>
                      </div>

                    </div>
                    <div class="form-group row">

                      <label for="rejected_cups" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Total Number of <b>REJECTED</b> Cups</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="number" min="0" class="form-control lessen_cups" id="rejected_cups" name="rejected_cups" value="<?php echo ($result_existdailyreport['rejected_cups']) ? $result_existdailyreport['rejected_cups']: ""; ?>" required>
                        </div>
                      </div>

                      <label for="cakes_income" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Cakes Income</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="number" min="0" class="form-control" id="cakes_income" name="cakes_income" value="<?php echo ($result_cakeincome) ? $result_cakeincome:""; ?>" readonly required>
                        </div>
                      </div>

                    </div>
                    <div class="form-group row">

                      <label for="missing_cups" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Total Number of <b>MISSING</b> Cups</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="number" min="0" class="form-control lessen_cups" id="missing_cups" name="missing_cups" value="<?php echo ($result_existdailyreport['missing_cups']) ? $result_existdailyreport['missing_cups']: ""; ?>" required>
                        </div>
                      </div>

                      <label for="merchandises" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Merchandises</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="text" class="form-control" id="merchandises" name="merchandises" value="<?php echo ($result_existdailyreport['merchandises']) ? $result_existdailyreport['merchandises']: ""; ?>" required>
                        </div>
                      </div>

                    </div>
                    <div class="form-group row">

                      <label for="compli_cups" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Total Number of <b>COMPLI</b> Cups</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="number" min="0" class="form-control lessen_cups" id="compli_cups" name="compli_cups" value="<?php echo ($result_existdailyreport['compli_cups']) ? $result_existdailyreport['compli_cups']: ""; ?>" required>
                        </div>
                      </div>

            
                    </div>
                    <div class="form-group row">

                      <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        &nbsp;
                      </div>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <textarea id="total_cups" readonly style="resize:none;" name="total_cups"><?php echo ($result_existdailyreport['total_cups']) ? $result_existdailyreport['total_cups']: ""; ?></textarea>
                      </div>

        

                    </div>
                    <div class="form-group row">

                      <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-form-label">Witness for the Rejected, Missing & Compli Cups:</label>

                    </div>
                    <div class="form-group row">


                      <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <div class="nk-int-st">
                            <input type="text" class="form-control" id="witness1" name="witness1" value="<?php echo ($result_existdailyreport['witness1']) ? $result_existdailyreport['witness1']: ""; ?>" required>
                            <label class="col-form-label">Witness Staff</label>
                        </div>
                      </div>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="text" class="form-control" id="witness2" name="witness2" value="<?php echo ($result_existdailyreport['witness2']) ? $result_existdailyreport['witness2']: ""; ?>" required>
                            <label class="col-form-label">Witness Staff</label>
                        </div>
                      </div>

                      <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <b>TOTAL INCOME</b>
                      </div>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="text" class="form-control" id="total_income" name="total_income" readonly placeholder="PHP" value="<?php echo ($result_dailytotal['DailyTotal']) ? ($result_dailytotal['DailyTotal']-$total_discount):""; ?>" required>
                        </div>
                      </div>

                    </div>
                    <div class="row">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <hr />
                      </div>
                    </div>

                    <div class="form-group row">

                      <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-form-label"><i>Less:</i> Daily Operational Expenses</label>
                      

                    </div>
                    <div class="form-group row">

                      <label for="drinking_water" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Drinking Water</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="number" min="0" class="form-control field_expenses" id="drinking_water" name="drinking_water" value="<?php echo ($result_existdailyreport['drinking_water']) ? $result_existdailyreport['drinking_water']: ""; ?>" required>
                        </div>
                      </div>

                      <label for="general_merchandise" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">General Merchandise</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="number" min="0" class="form-control field_expenses" id="general_merchandise" name="general_merchandise" value="<?php echo ($result_existdailyreport['general_merchandise']) ? $result_existdailyreport['general_merchandise']: ""; ?>" required>
                        </div>
                      </div>

                    </div>
                    <div class="form-group row">

                      <label for="iced" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Tube Ice / Crushed Ice</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="number" min="0" class="form-control field_expenses" id="iced" name="iced" value="<?php echo ($result_existdailyreport['iced']) ? $result_existdailyreport['iced']: ""; ?>" required>
                        </div>
                      </div>
                      <label for="groceries" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Groceries (Snacks)</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="number" min="0" class="form-control field_expenses" id="groceries" name="groceries" value="<?php echo ($result_existdailyreport['groceries']) ? $result_existdailyreport['groceries']: ""; ?>" required>
                        </div>
                      </div>
                      
                    </div>
                    <div class="form-group row">

                      <label for="liquefied_gas" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Liquefied Petroleum Gas</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="number" min="0" class="form-control field_expenses" id="liquefied_gas" name="liquefied_gas" value="<?php echo ($result_existdailyreport['liquefied_gas']) ? $result_existdailyreport['liquefied_gas']: ""; ?>" required>
                        </div>
                      </div>

                      <label for="discounts" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Discounts (SC, PWD, Promos)</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="text" class="form-control field_expenses" id="discounts" name="discounts" value="<?php echo $total_discount; ?>" readonly>
                        </div>
                      </div>

                    </div>
                    <div class="form-group row">

                      <label for="communication" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Communication</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="number" min="0" class="form-control field_expenses" id="communication" name="communication" value="<?php echo ($result_existdailyreport['communication']) ? $result_existdailyreport['communication']: ""; ?>" required>
                        </div>
                      </div>

                      

                    </div>
                    <div class="form-group row">

                      <label for="stocks_deliveries" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Stocks Deliveries (Milktea)</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="number" min="0" class="form-control field_expenses" id="stocks_deliveries" name="stocks_deliveries" value="<?php echo ($result_existdailyreport['stocks_deliveries']) ? $result_existdailyreport['stocks_deliveries']: ""; ?>" required>
                        </div>
                      </div>
                      <label for="total_expenses" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">TOTAL EXPENSES</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="number" min="0" class="form-control" id="total_expenses" name="total_expenses" placeholder="PHP" value="<?php echo ($result_existdailyreport['total_expenses']) ? $result_existdailyreport['total_expenses']: ""; ?>" readonly required>
                        </div>
                      </div>

                      

                    </div>
                    <div class="form-group row">

                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
                          I / We hereby certify that the financial statements and informations declared at the back of this document are true and correct to the best of my / our knowledge.
                      </div>

                      <label for="net_income" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">NET INCOME OF THE DAY</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <div class="nk-int-st">
                            <input type="text" class="form-control" id="net_income" name="net_income" readonly placeholder="PHP" required>
                        </div>
                      </div>

                    </div>
                    <div class="form-group row">

                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
                          And I / We understand that any PROVEN false statement may qualify me for any legal action that may take by the management of Lucky Bunny Ph.
                      </div>

                      <label for="grand_total" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label short-div">GRAND TOTAL</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12 short-div">
                        <div class="nk-int-st">
                            <input type="text" class="form-control" id="grand_total" name="grand_total" readonly placeholder="PHP" required>
                        </div>
                      </div>

                    </div>
                    <div class="form-group row">

                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
                        <hr />
                          (Cashier's On-Duty, Signature/s Over Printed Name)
                      </div>

                      <label for="audited_by" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label short-div">AUDITED BY:</label>
                      <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12 short-div">
                        <div class="nk-int-st">
                            <input type="text" class="form-control" id="audited_by" name="audited_by" value="<?php echo ($result_existdailyreport['audited_by']) ? $result_existdailyreport['audited_by']: ""; ?>" required <?php if(@$_SESSION['username']==3) { echo "readonly"; } ?>  title="Team Leader or Admin">
                        </div>
                      </div>

                    </div>
                    </fieldset>
                  

            </div>
        </div>
    </div>
    <!-- Invoice area End-->

    </form>  
<?php $template->place('footer'); ?>

<script type="text/javascript" src="js\html2canvas.min.js"></script>
<script type="text/javascript" src="js\html2canvas.js"></script>

<script>
  calc_income();

  $('button[name="submit"]').on('click', function () {
       
      if(!validateForm()) {
          swal("Please fill up all fields!");
          return false;
      }

      $( "form" ).submit();

  });

  function htmltocanvas() {

      html2canvas(document.querySelector("#invoice-area")).then(canvas => {
          // document.body.appendChild(canvas)
          download(canvas, document.getElementById("invoice-area").getAttribute("data-date")+'.png');
      });

  }

  function validateForm() {

    var isValid = true;

    $('.form-control').each(function() {

        if ( $(this).val() === '' ) {
          isValid = false;
          // $(this).css("background-color","red");
        }

    });

    return isValid;

  }

  // we used jQuery 'keyup' to trigger the computation as the user type
  $('.lessen_cups').keyup(function () {
      calc_cups();   
  });

  $('.field_expenses').keyup(function () {
      calc_expenses();   
  });

  function calc_expenses() {

      var sum = 0;
      $('.field_expenses').each(function() {
          sum += Number($(this).val());
      });

      $('#total_expenses').val(sum);
      calc_income();
  }

  function calc_cups() {

      // initialize the sum (total price) to zero
      var sum = 0;
      // we use jQuery each() to loop through all the textbox with 'price' class
      // and compute the sum for each loop
      $('.lessen_cups').each(function() {
          sum += Number($(this).val());
      });

      total_cups = parseInt($("#released_cups").val()) - sum;
      // set the computed value to 'totalPrice' textbox
      $('#total_cups').val(total_cups);
      // calc_income();
  }

  function calc_income() {

      var total_net_income=0;
      var total_grand=0;

      if(parseInt($("#total_income").val()) || parseInt($("#total_expenses").val())) {
          total_net_income = parseInt($("#total_income").val()) - parseInt($("#total_expenses").val());
      }
      
      $('#net_income').val(total_net_income);

      if(parseInt($("#net_income").val())) {
          total_grand = parseInt($("#net_income").val());
      }

      $('#grand_total').val(total_grand);
    
  }

</script>
<?php
        if(isset($_POST['submit'])) {
            echo '<script type="text/javascript">','htmltocanvas();','</script>';
        }
?>