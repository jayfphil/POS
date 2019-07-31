<?php  
    session_start(); 
    require './include/config.php';

    $template->set('title', 'Dashboard');
    $template->place('header'); // Set page header code (from inc/templates):

?>
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
                                        <i class="notika-icon notika-app"></i>
                                    </div>
                                    <div class="breadcomb-ctn">
                                        <h2>Sales Receipt</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
                                <div class="breadcomb-report">
                                    <button data-toggle="tooltip" data-placement="left" title="Print Receipt" class="btn" id="printreceipt"><i class="notika-icon notika-sent"></i></button>
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
    <div class="invoice-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="invoice-wrap">
                        <div class="invoice-img">
                            <img src="img/LuckyBunnyLogo.ico" alt="" />
                        </div>
                        <?php 

                                if(isset($_REQUEST['trans_no'])) {
                                      
                                                    $result_transact = PDO_FetchAll("SELECT * FROM `transaction_tb` WHERE `tt_voided` IS NULL AND `id`=$_REQUEST[trans_no]");
                                                    if(count($result_transact) > 0) {

                                                        foreach ($result_transact as $row_transact) {

                                                            $phpdate = strtotime( $row_transact['date_created'] );

                                                            $result_categorylabel = PDO_FetchRow("SELECT SUM(`price`*`quantity`) as 'grand_total',SUM(`quantity`) as 'items_cnt' FROM `transactionitems_tb` WHERE `transact_id`=$row_transact[id] AND `ti_voided` IS NULL");
                                                        

                                ?>
                        <div class="invoice-hds-pro">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="invoice-cmp-ds ivc-frm">
                                        <div class="invoice-frm">
                                            <span>Invoice from</span>
                                        </div>
                                        <div class="comp-tl">
                                            <h2>Lucky Bunny Ph</h2>
                                            <p>Mexico / Arayat / Macabebe / Apalit / Cabiao / Calumpit / Malolos / Baliuag / Angeles</p>
                                        </div>
                                        <div class="cmp-ph-em">
                                            <span>0965-198-2192</span>
                                            <span>LuckyBunnyPh@yahoo.com</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="invoice-cmp-ds ivc-to">
                                        <div class="invoice-frm">
                                            <span>Invoice to</span>
                                        </div>
                                        <div class="comp-tl">
                                            <h2>{Customer 123}</h2>
                                            <p>{Customer Address}</p>
                                        </div>
                                        <div class="cmp-ph-em">
                                            <span>{Customer Contact No.}</span>
                                            <span>{Customer Email Address}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center">
                                
                                    <span>Invoice#</span>
                                    <h6><?php echo str_pad($row_transact['id'], 5, '0', STR_PAD_LEFT); ?></h6>
                                
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center">
                               
                                    <span>Date</span>
                                    <h6><?php echo date("m/d/Y H:i:s",$phpdate); ?></h6>
                              
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center">
                               
                                    <span>Number of items Sold</span>
                                    <h6><?php echo ($result_categorylabel['items_cnt']) ? $result_categorylabel['items_cnt']: 0; ?></h6>
                              
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center">
                             
                                    <span>Grand Total</span>
                                    <h6>Php <?php echo ($result_categorylabel['grand_total']) ? number_format($result_categorylabel['grand_total'], 2): 0.00; ?></h6>
                         
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="invoice-sp">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Item Name</th>
                                                <th>Unit Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php

                                                        
                            
                                                                                                    $result_items = PDO_FetchAll("SELECT a.`id`, b.`product_name`,a.`price`,a.`quantity` FROM `transactionitems_tb` a INNER JOIN `product_tb` b ON a.`product_id`=b.`id` WHERE a.`transact_id`=$row_transact[id] AND a.`ti_voided` IS NULL");
                                                                                                    if(count($result_items) > 0) {
                                                                                                        
                                                                                                                    foreach ($result_items as $row_items) {
                                                                                                                        echo "<tr><td>".$row_items['id']."</td><td>".$row_items['product_name']."</td><td>Php ".number_format($row_items['price'], 2)."</td><td>".$row_items['quantity']."</td><td>".number_format(($row_items['price']*$row_items['quantity']), 2)."</td></tr>";
                                                                                                                    }
                                                                                                             
                                                                                                    }
                                                    

                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="invoice-ds-int">
                                    <h2>Remarks</h2>
                                    <p>Ornare non tortor. Nam quis ipsum vitae dolor porttitor interdum. Curabitur faucibus erat vel ante fermentum lacinia. Integer porttitor laoreet suscipit. Sed cursus cursus massa ut pellentesque. Phasellus vehicula dictum arcu, eu interdum massa bibendum. Ornare non tortor. Nam quis ipsum vitae dolor porttitor interdum. Curabitur faucibus erat vel ante fermentum lacinia. Integer porttitor laoreet suscipit. Sed cursus cursus massa ut pellentesque. Phasellus vehicula dictum arcu, eu interdum massa bibendum. </p>
                                </div>
                                <div class="invoice-ds-int invoice-last">
                                    <h2>Lucky Bunny For Your Business</h2>
                                    <p class="tab-mg-b-0">Ornare non tortor. Nam quis ipsum vitae dolor porttitor interdum. Curabitur faucibus erat vel ante fermentum lacinia. Integer porttitor laoreet suscipit. Sed cursus cursus massa ut pellentesque. Phasellus vehicula dictum arcu, eu interdum massa bibendum. Ornare non tortor. Nam quis ipsum vitae dolor porttitor interdum. Curabitur faucibus erat vel ante fermentum lacinia. Integer porttitor laoreet suscipit. Sed cursus cursus massa ut pellentesque. Phasellus vehicula dictum arcu, eu interdum massa bibendum. </p>
                                </div>
                            </div>
                        </div>
                        <?php 
                                } 
                                                    } else {
                                                        ?>
                                                            <div class="alert alert-danger alert-mg-b-0 text-center" role="alert">Some items has been voided!</div>
                                                        <?php
                                                    }

                                } else {
                                                    ?>
                                                        <div class="alert alert-warning text-center" role="alert">This transaction has been voided!</div>
                                                    <?php
                                                }

                                ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Invoice area End-->
<?php $template->place('footer'); ?>
    