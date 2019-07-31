<?php 
    session_start(); 
    require './include/config.php';

    $template->set('title', 'Product');
    $template->place('header'); // Set page header code (from inc/templates):

    $result_category = PDO_FetchAll("SELECT `id`, `category_name` FROM `category_tb` WHERE `ct_deleted` IS NULL");
    $result_addons = PDO_FetchAll("SELECT `id`, `addons_name` FROM `addons_tb` WHERE `at_deleted` IS NULL");

    if(isset($_POST) && isset($_POST['savechanges'])) {

        if(!$_POST['product_id']) {
            PDO_Execute("INSERT INTO `product_tb` (product_name,price,quantity,category_id,product_codetemp,user_id,addons_flag,report_category) VALUES(?,?,?,?,?,?,?,?)",array($_POST['product_name'],$_POST['price'],$_POST['quantity'],$_POST['category_id'],$_POST['product_codetemp'],@$_SESSION["username"],$_POST['addons_id'],$_POST['report_category']));
        } else {
            PDO_Execute("UPDATE `product_tb` SET `product_name`=?,`price`=?, `quantity`=?, `category_id`=?, `product_codetemp`=?, `user_id`=?, `addons_flag`=?, `report_category`=? WHERE `id`=? ",array($_POST['product_name'],$_POST['price'],$_POST['quantity'],$_POST['category_id'],$_POST['product_codetemp'],@$_SESSION["username"],$_POST['addons_id'],$_POST['report_category'],$_POST['product_id']));
        }

        if(!$_POST['category_id']) {
            PDO_Execute("INSERT INTO `auditlog_tb` (product_id,product_name,count_beforechange,count_afterchange,log_sources) VALUES(?,?,?,?,?)",array($_POST['product_id'],$_POST['product_name'],$_POST['old_quantity'],$_POST['quantity'],"Updated from Admin Panel Inventory"));
        }

    }

?>

    <!-- Data Table area Start-->
    <div class="data-table-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="data-table-list">
                            <div class="basic-tb-hd">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h2 class="pull-left">Products</h2>
                                        <button type="button" data-toggle="modal" data-target=".modal" title="Add Product" class="btn pull-right additemrow"><i class="notika-icon notika-edit"></i> Add Product</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="tablelist" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Product Code</th>
                                            <th>Product Type</th>
                                            <th>Free Add-Ons</th>
                                            <th>Report Category</th>
                                            <th>Stocks</th>
                                            <th>Price (PHP)</th>
                                            <th>Editable Price</th>
                                            <th>Created Date</th>
                                            <th>Last Updated</th>
                                            <th>Active</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php

                                                $result_product = PDO_FetchAll("SELECT * FROM `product_tb`");
                                                if(count($result_product) > 0) {
                                                  
                                                    foreach ($result_product as $row_product) {
                                                        
                                                        $checked_active="";
                                                        $checked_customprice="";

                                                        if(!$row_product['pt_deleted']) {
                                                            $checked_active="checked";
                                                        }

                                                        if($row_product['pt_customprice']) {
                                                            $checked_customprice="checked";
                                                        }
                                                        
                                                        $result_categorylabel =PDO_FetchOne("SELECT `category_name` FROM `category_tb` WHERE `id`=?",array($row_product['category_id']));

                                                        $result_addonslabel =PDO_FetchOne("SELECT `addons_name` FROM `addons_tb` WHERE `id`=?",array($row_product['addons_flag']));

                                                            ?>
                                                                <tr>
                                                                    <td><a href="#" class="edititemrow" data-id="<?php echo $row_product['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Product"><?php echo $row_product['product_name']; ?></a></td>
                                                                    <td><a href="#" class="edititemrow" data-id="<?php echo $row_product['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Product"><?php echo $row_product['product_codetemp']; ?></a></td>
                                                                    <td><a href="#" class="edititemrow" data-id="<?php echo $row_product['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Product"><?php echo (($result_categorylabel) ? $result_categorylabel : "Main Item"); ?></a></td>
                                                                    <td><a href="#" class="edititemrow" data-id="<?php echo $row_product['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Product"><?php echo (($result_addonslabel) ? $result_addonslabel : "No Free Add-Ons"); ?></a></td>
                                                                    <td><a href="#" class="edititemrow" data-id="<?php echo $row_product['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Product"><?php echo $row_product['report_category']; ?></a></td>
                                                                    <td><a href="#" class="edititemrow" data-id="<?php echo $row_product['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Product"><?php echo $row_product['quantity']; ?></a></td>
                                                                    <td><a href="#" class="edititemrow" data-id="<?php echo $row_product['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Product"><?php echo number_format($row_product['price'], 2); ?></a></td>
                                                                    <td>
                                                                        <div class="toggle-select-act fm-cmp-mg">
                                                                            <div class="nk-toggle-switch" data-ts-color="lime">
                                                                                <input id="ts_editable_<?php echo $row_product['id']; ?>" class="tick_editable" data-prompt="info" type="checkbox" hidden="hidden" data-type="editableprice" data-id="<?php echo $row_product['id']; ?>" <?php echo $checked_customprice; ?> value="<?php echo $row_product['pt_customprice']; ?>">
                                                                                <label for="ts_editable_<?php echo $row_product['id']; ?>" class="ts-helper"></label>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td><a href="#" class="edititemrow" data-id="<?php echo $row_product['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Product"><?php echo $row_product['date_created']; ?></a></td>
                                                                    <td><a href="#" class="edititemrow" data-id="<?php echo $row_product['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Product"><?php echo $row_product['last_updated']; ?></a></td>
                                                                    <td>
                                                                        <div class="toggle-select-act fm-cmp-mg">
                                                                            <div class="nk-toggle-switch" data-ts-color="red">
                                                                                <?php if($row_product['category_id']<>0) { ?>
                                                                                    <input id="ts_delete_<?php echo $row_product['id']; ?>" class="tick_delete" data-prompt="success" type="checkbox" hidden="hidden" data-type="product" data-id="<?php echo $row_product['id']; ?>" <?php echo $checked_active; ?> value="<?php echo $row_product['pt_deleted']; ?>">
                                                                                    <label for="ts_delete_<?php echo $row_product['id']; ?>" class="ts-helper"></label>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php

                                                    } 
                                                }

                                        ?>
                                        
                                    </tbody>
                                </table>
                               
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data Table area End-->

    <!-- The Modal -->
    <div class="modal fade" id="product_modal" role="dialog">
        <div class="modal-dialog modals-default">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="product.php">
                        <input type="hidden" name="product_id" value="0">
                        <input type="hidden" name="category_id" value="0">
                        <input type="hidden" name="report_category" value="0">
                        <input type="hidden" name="old_quantity" value="0">
                        <div class="form-example-wrap mg-t-30">
                            <div class="cmp-tb-hd cmp-int-hd">
                                <h2>Edit Product</h2>
                            </div>
                            <div class="form-example-int form-horizental">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                            <label class="hrzn-fm">Product Name</label>
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                            <div class="nk-int-st">
                                                <input type="text" class="form-control input-sm" name="product_name" placeholder="Enter Product Name" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-example-int form-horizental mg-t-15">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                            <label class="hrzn-fm">Product Code</label>
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                            <div class="nk-int-st">
                                                <input type="text" class="form-control input-sm" name="product_codetemp" placeholder="Enter Product Code" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-example-int form-horizental mg-t-15 modal_ptype">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                            <label class="hrzn-fm">Product Type</label>
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                            <select class="form-control" name="category_id" required>
                                                <option disabled selected value='0'>- Please select -</option>
                                                <?php 
                                                if(count($result_category) > 0) {

                                                  foreach ($result_category as $row_category) {

                                                      echo "<option value='".$row_category['id']."'>";
                                                      echo $row_category['category_name'];
                                                      echo "</option>";

                                                  } 
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-example-int form-horizental mg-t-15 modal_ptype">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                            <label class="hrzn-fm">Free Add-Ons</label>
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                            <select class="form-control" name="addons_id" required>
                                                <option value='0'>No Free Add-Ons</option>
                                                <?php 
                                                if(count($result_addons) > 0) {

                                                  foreach ($result_addons as $row_addons) {

                                                      echo "<option value='".$row_addons['id']."'>";
                                                      echo $row_addons['addons_name'];
                                                      echo "</option>";

                                                  } 
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-example-int form-horizental mg-t-15 modal_ptype">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                            <label class="hrzn-fm">Report Category</label>
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                            <select class="form-control" name="report_category" required>
                                                <option disabled selected value='0'>- Please select -</option>
                                                <option>Cups</option>
                                                <option>Snacks - Ala Carte</option>
                                                <option>Snacks - Unlimited</option>
                                                <option>Cakes</option> 
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-example-int form-horizental mg-t-15">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                            <label class="hrzn-fm">Stocks</label>
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                            <div class="nk-int-st">
                                                <input type="number" class="form-control input-sm" name="quantity" placeholder="Enter Quantity">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-example-int form-horizental mg-t-15">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                            <label class="hrzn-fm">Price</label>
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                            <div class="nk-int-st">
                                                <input type="number" class="form-control input-sm" name="price" placeholder="Enter Price">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="savechanges" class="btn btn-default">Save changes</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
    // Set page footer code (from inc/templates):
    $template->place('footer');
?>