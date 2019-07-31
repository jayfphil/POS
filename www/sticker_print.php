<?php 
  session_start(); 
  require './include/config.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <style>
        /*@media print {*/
          #invoice-POS, .sticker-POS{
            box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
            padding:2mm;
            margin: 0 auto;
            width: 45mm;
            background: #FFF;
            border-radius: 5%;
            border: 1px solid;
          }

          /*#invoice-POS:after {
              content: " ";
              display: block;
              position: relative;
              top: 0px;
              left: 0px;
              width: 100%;
              height: 26px;
              background: -webkit-linear-gradient(#FFFFFF 0%, transparent 0%), -webkit-linear-gradient(135deg, #e9ebee 33.33%, transparent 33.33%) 0 0%, #e9ebee -webkit-linear-gradient(45deg, #e9ebee 33.33%, #FFFFFF 33.33%) 0 0%;
              background: -o-linear-gradient(#FFFFFF 0%, transparent 0%), -o-linear-gradient(135deg, #e9ebee 33.33%, transparent 33.33%) 0 0%, #e9ebee -o-linear-gradient(45deg, #e9ebee 33.33%, #FFFFFF 33.33%) 0 0%;
              background: -moz-linear-gradient(#FFFFFF 0%, transparent 0%), -moz-linear-gradient(135deg, #e9ebee 33.33%, transparent 33.33%) 0 0%, #e9ebee -moz-linear-gradient(45deg, #e9ebee 33.33%, #FFFFFF 33.33%) 0 0%;
              background-repeat: repeat-x;
              background-size: 0px 100%, 14px 27px, 14px 27px;
          }*/

          ::selection {background: #f31544; color: #FFF;}
          ::moz-selection {background: #f31544; color: #FFF;}
          h1 {
            font-size: 1.5em;
            color: #222;
          }
          h2 {font-size: .9em;}
          h3 {
            font-size: 1.2em;
            font-weight: 300;
            /*line-height: 2em;*/
          }
          h6 {
              margin:0px;
          }
          p{
            font-size: .6em;
            color: #666;
            /*line-height: 1.2em;*/
          }
           
          #top, #mid,#bot{ /* Targets all id with 'col-' */
            border-bottom: 1px solid #EEE;
          }

          #top{min-height: 30px;}
          #mid{min-height: 100px;} 
          #bot{ min-height: 50px;}

          #mid .logo img{
              float: left;
              height: 60px;
              width: 60px;
              /*background: url(img/LuckyBunnyLogo.ico) no-repeat;*/
              background-size: 60px 60px;
          }
          .info{
            display: block;
            /*float:left;*/
            margin-left: 0;
          }
          .title{
            float: right;
          }
          .title p{text-align: right;} 
          table{
            width: 100%;
            border-collapse: collapse;
          }
          td{
            /*padding: 5px 0 5px 15px;*/
            border-bottom: 1px dashed #EEE;
            /*text-align:center;*/
            font-size: 10px;
          }
          th{
            font-size: 10px;
          }
          .stickertitle {
            background: linear-gradient(to left,gray, #0F2609);
            color:white;
            font-size: 1em;
            border-radius: 10%;
          }
          .tabletitle{
            padding: 5px;
            font-size: .5em;
            background: #EEE;
          }
          .service{border-bottom: 1px dashed #EEE;}
          .item{width: 24mm;}
          .itemtext{font-size: .5em;}

          #legalcopy{
            margin-top: 5mm;
            text-align: center;
          }
        /*}*/
    </style>
    <script type="text/javascript" src="js\html2canvas.min.js"></script>
    <script type="text/javascript" src="js\html2canvas.js"></script>
</head>
<body> 
<!-- onload="window.print()" -->
<?php 

if(isset($_REQUEST['receiptno'])) {
                                     
    $result_branch = PDO_FetchRow("SELECT * FROM `branch_tb` LIMIT 1");

    $result_transact = PDO_FetchAll("SELECT * FROM `transaction_tb` WHERE `id`=$_REQUEST[receiptno]");
    if(count($result_transact) > 0) {

        foreach ($result_transact as $row_transact) {

            $phpdate = strtotime( $row_transact['date_created'] );

            $result_categorylabel = PDO_FetchRow("SELECT SUM(`price`*`quantity`) as 'grand_total',SUM(`quantity`) as 'items_cnt' FROM `transactionitems_tb` WHERE `transact_id`=$row_transact[id] AND `ti_voided` IS NULL");

            $result_cashieruser =PDO_FetchOne("SELECT `fullname` FROM `users_tb` WHERE `id`=?",array($row_transact['user_id']));
        
?>

  <div id="invoice-POS" data-date="<?php echo date("YmdhisA"); ?>">
    
    <center id="top" class="service">
      <h2>SALES INVOICE</h2>
    </center><!--End InvoiceTop-->
    
    <div id="mid">
        <div class="logo"><img src="img/LuckyBunnyLogo.ico"></div>
          <div class="info"> 
            <h2>Lucky Bunny</h2>
            <h5><?php echo ($result_branch['branch_name']) ? $result_branch['branch_name']: "[Branch Name]"; ?></h5>
            <h6><?php echo ($result_branch['branch_city'] || $result_branch['branch_country'] || $result_branch['branch_region'] || $result_branch['branch_areacode'] || $result_branch['branch_postalcode']) ? $result_branch['branch_city']." ".$result_branch['branch_postalcode'].", ".$result_branch['branch_region']." ".$result_branch['branch_areacode'].", ".$result_branch['branch_country']: "[Branch Address]"; ?></h6>
            <div id="table">
                <table>
                    <tr class="service"><td class="tableitem"><p class="itemtext">Cashier :</p></td><td class="tableitem"><p class="itemtext"><?php echo $result_cashieruser; ?></p></td><td class="tableitem"><p class="itemtext">Date :</p></td><td class="tableitem"><p class="itemtext"><?php echo date("m/d/Y",$phpdate); ?></p></td></tr>
                    <tr class="service"><td class="tableitem"><p class="itemtext">Time :</p></td><td class="tableitem"><p class="itemtext"><?php echo date("H:i:s",$phpdate); ?></p></td><td class="tableitem"><p class="itemtext">TRN :</p></td><td class="tableitem"><p class="itemtext"><?php echo str_pad($row_transact['id'], 5, '0', STR_PAD_LEFT); ?></p></td></tr>
                </table>
            </div>
      </div>
    </div><!--End Invoice Mid-->
    
    <div id="bot">

                    <div id="table">
                        <table>
                            <tr class="tabletitle">
                                <td class="item"><h2>Item</h2></td>
                                <td class="Rate"><h2>Disc.</h2></td>
                                <td class="Rate">&nbsp;</td>
                                <td class="Rate"><h2>Unit Price</h2></td>
                            </tr>

                            <?php 

                                $result_items = PDO_FetchAll("SELECT a.`id`, b.`product_codetemp`, a.`price`, a.`discount`, a.`quantity`, a.`addons_metajson` FROM `transactionitems_tb` a INNER JOIN `product_tb` b ON a.`product_id`=b.`id` WHERE a.`transact_id`=$row_transact[id] AND a.`ti_voided` IS NULL");

                                $count=0;
                                $grand_total=0;
                                if(count($result_items) > 0) {
                                    
                                                foreach ($result_items as $row_items) {

                                                  $dec = ($row_items['discount'] / 100); //its convert 10 into 0.10
                                                  $mult = $row_items['price'] * $dec; // gives the value for subtract from main 
                                                  $grand_total+=($row_items['price']-$mult);

                                                    ?>

                                                        <tr class="service">
                                                            <td class="tableitem"><p class="itemtext"><?php echo $row_items['product_codetemp']; ?></p></td>
                                                            <td class="tableitem"><p class="itemtext"><?php if($row_items['discount']<>0) { echo $row_items['discount']."%"; } ?></p></td>
                                                            <td class="tableitem"><p class="itemtext">-<?php // echo number_format($row_items['price'], 2); ?></p></td>
                                                            <td class="tableitem">
                                                              <p class="itemtext"><?php echo number_format(($row_items['price']-$mult), 2); ?></p>
                                                            </td>
                                                        </tr>

                                                    <?php
                                                    if(isJson($row_items['addons_metajson'])) {
                                                      foreach (json_decode($row_items['addons_metajson']) as $row_subitems => $subitems) {

                                                        if($row_subitems=="free") {
                                                            $explode_free = explode("-",$subitems);
                                                            $row_subitems = $explode_free[0];
                                                            $subitems = $explode_free[1];
                                                        } 

                                                        $result_addons = PDO_FetchOne("SELECT `addons_codetemp` FROM `addons_tb` WHERE `id`='".$row_subitems."' AND `at_deleted` IS NULL ");
                                                          ?>

                                                              <tr class="service">
                                                                  <td class="tableitem"><p class="itemtext" style="text-indent: 2em;"><?php echo $result_addons; ?></p></td>
                                                                  <td class="tableitem"><p class="itemtext">&nbsp;</p></td>
                                                                  <td class="tableitem"><p class="itemtext">-<?php // echo number_format($subitems, 2); ?></p></td>
                                                                  <td class="tableitem">
                                                                    <p class="itemtext"><?php echo number_format(($subitems*1), 2); ?></p>
                                                                  </td>
                                                              </tr>

                                                          <?php
                                                          $grand_total=$grand_total+number_format(($subitems*1), 2);

                                                      }
                                                    }
                                                $count++;
                                                }
                                         
                                }

                            ?>

                            <tr class="tabletitle">
                                <td colspan="3" class="Rate"><h2>Sub-total</h2></td>
                                <td class="payment"><h2><?php echo number_format($grand_total, 2); ?></h2></td>
                            </tr>

                            <tr class="tabletitle">
                                <td colspan="3" class="Rate"><h2>Discount All</h2></td>
                                <td class="payment"><h2><?php echo ($row_transact['discount_all']) ? $row_transact['discount_all'] : 0; ?>%</h2></td>
                            </tr>

                            <tr class="tabletitle">
                                <td colspan="3" class="Rate"><h2>Total</h2></td>
                                <td class="payment"><h2><?php echo number_format($row_transact['total_amtdisc'], 2); ?></h2></td>
                            </tr>

                            <tr class="tabletitle">
                                <td colspan="3" class="Rate"><h2>Number of items Sold</h2></td>
                                <td class="payment"><h2><?php echo $count; ?></h2></td>
                            </tr>

                            <tr class="tabletitle">
                                <td colspan="3" class="Rate"><h2>Cash (Php) Tendered</h2></td>
                                <td class="payment"><h2><?php echo number_format($row_transact['cash_tender'], 2); ?></h2></td>
                            </tr>

                            <tr class="tabletitle">
                                <td colspan="3" class="Rate"><h2>Change Due</h2></td>
                                <td class="payment"><h2><?php echo number_format($row_transact['cash_tender']-$row_transact['total_amtdisc'], 2); ?></h2></td>
                            </tr>

                        </table>
                    </div><!--End Table-->

                    <div id="legalcopy">
                        <p class="legal"><strong>Thank you for your business!</strong><br />Payment is expected within 31 days; please process this invoice within that time. <!-- There will be a 5% interest charge per month on late invoices.   -->
                        </p>
                    </div>

                </div><!--End InvoiceBot-->
  </div>

<?php

          }
      }
  }


  if(isset($_REQUEST['productno'])) {

        $result_items = PDO_FetchAll("SELECT a.`id`, b.`product_name`,a.`addons_metajson`, a.`sugar_level`, a.`price`,c.`customer_name` FROM `transactionitems_tb` a INNER JOIN `product_tb` b ON a.`product_id`=b.`id` INNER JOIN `transaction_tb` c ON a.`transact_id`=c.`id` WHERE a.`transact_id`=$_REQUEST[productno] AND a.`ti_voided` IS NULL AND b.`report_category`='Cups' ");
        
        if(count($result_items) > 0) {

            foreach ($result_items as $row_items) {
                $addons_display="";
                if(isJson($row_items['addons_metajson'])) {
                    foreach (json_decode($row_items['addons_metajson']) as $row_subitems => $subitems) {

                      if($row_subitems=="free") {
                          $explode_free = explode("-",$subitems);
                          $row_subitems = $explode_free[0];
                          $subitems = $explode_free[1];
                      } 

                      $result_addons = PDO_FetchOne("SELECT `addons_name` FROM `addons_tb` WHERE `id`='".$row_subitems."' AND `at_deleted` IS NULL ");
                      $addons_display.=" + ".$result_addons;
                      $row_items['price']=$row_items['price']+$subitems;

                    }
                }
            $sticker_identifier= $_REQUEST['productno']."-".$row_items['id']."-".$row_items['customer_name'];
?>
  <!-- customernameandproductcode -->
  <div id="sticker-<?php echo $sticker_identifier; ?>" class="sticker-POS" data-date="<?php echo date("YmdhisA")."-".$sticker_identifier; ?>"> 
    <table>
      <thead>
        <tr class="stickertitle">
          <th><?php echo $row_items['customer_name']; ?></th>
          <th><?php echo $row_items['sugar_level']; ?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Lucky Bunny</td>
          <td>#<?php echo str_pad($_REQUEST['productno'], 5, '0', STR_PAD_LEFT); ?></td>
        </tr>
        <tr>
          <td colspan="2"><?php echo $row_items['product_name'].$addons_display; ?></td>
        </tr>
        <tr>
          <td><?php echo date("m/d/Y"); ?></td>
          <td><?php echo date("h:i A"); ?></td>
        </tr>
      </tbody>
    </table>
  </div>

  <script>

    html2canvas(document.querySelector("#sticker-<?php echo $sticker_identifier; ?>")).then(canvas => {
        // document.body.appendChild(canvas)
        download(canvas, document.getElementById("sticker-<?php echo $sticker_identifier; ?>").getAttribute("data-date")+'.png');
    });

  </script>
<?php       
            }
          }
    } 
?>

  <center><button type="button" onclick="window.top.close()">Close</button></center>

</body>
</html>

<script>
var receiptExists = document.getElementById("invoice-POS");

if(receiptExists) {

  html2canvas(document.querySelector("#invoice-POS")).then(canvas => {
      // document.body.appendChild(canvas)
      download(canvas, receiptExists.getAttribute("data-date")+'.png');
  });

}
</script>