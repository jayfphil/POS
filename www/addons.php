<?php 
    session_start(); 
    require './include/config.php';

    $template->set('title', 'Product');
    $template->place('header'); // Set page header code (from inc/templates):

    if(isset($_POST) && isset($_POST['savechanges'])) {

        if(!$_POST['addons_id']) {
            PDO_Execute("INSERT INTO `addons_tb` (addons_name,price,quantity,category_text,addons_codetemp,user_id) VALUES(?,?,?,?,?,?)",array($_POST['addons_name'],$_POST['price'],$_POST['quantity'],implode(", ",$_POST['categories']),$_POST['addons_codetemp'],@$_SESSION["username"]));
        } else {
            PDO_Execute("UPDATE `addons_tb` SET `addons_name`=?,`price`=?, `quantity`=?, `category_text`=?, `addons_codetemp`=?, `user_id`=? WHERE `id`=? ",array($_POST['addons_name'],$_POST['price'],$_POST['quantity'],implode(", ",$_POST['categories']),$_POST['addons_codetemp'],@$_SESSION["username"],$_POST['addons_id']));
        }

    }

    $result_category = PDO_FetchAll("SELECT `id`, `category_name` FROM `category_tb` WHERE `ct_deleted` IS NULL");
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
                                        <h2 class="pull-left">Add-Ons</h2>
                                        <button type="button" data-toggle="modal" data-target=".modal" title="Add Add-Ons" class="btn pull-right addaddonsrow"><i class="notika-icon notika-edit"></i> Add Add-Ons</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="tablelist" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Category</th>
                                            <th>Stocks</th>
                                            <th>Price (PHP)</th>
                                            <th>Created Date</th>
                                            <th>Last Updated</th>
                                            <th>Active</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php

                                                $result_addons = PDO_FetchAll("SELECT * FROM `addons_tb`");
                                                if(count($result_addons) > 0) {
                                                  
                                                    foreach ($result_addons as $row_addons) {
                                                        
                                                        $checked_active="";
                                                        if(!$row_addons['at_deleted']) {
                                                            $checked_active="checked";
                                                        }

                                                        
                                                        if($row_addons['category_text']) {
                                                            $categories = PDO_FetchRow("SELECT GROUP_CONCAT(`category_name`) AS 'groupcat' FROM `category_tb` WHERE `id` IN (".$row_addons['category_text'].")");
                                                        } else {
                                                            $categories['groupcat']="None";
                                                        }

                                        ?>
                                                                <tr>
                                                                    <td><a href="#" class="editaddonsrow" data-id="<?php echo $row_addons['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Adds-On"><?php echo $row_addons['addons_name']; ?></a></td>
                                                                    <td><a href="#" class="editaddonsrow" data-id="<?php echo $row_addons['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Adds-On"><?php echo $row_addons['addons_codetemp']; ?></a></td>
                                                                    <td><a href="#" class="editaddonsrow" data-id="<?php echo $row_addons['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Adds-On"><?php echo $categories['groupcat']; ?></a></td>
                                                                    <td><a href="#" class="editaddonsrow" data-id="<?php echo $row_addons['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Adds-On"><?php echo $row_addons['quantity']; ?></a></td>
                                                                    <td><a href="#" class="editaddonsrow" data-id="<?php echo $row_addons['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Adds-On"><?php echo number_format($row_addons['price'], 2); ?></a></td>
                                                                    <td><a href="#" class="editaddonsrow" data-id="<?php echo $row_addons['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Adds-On"><?php echo $row_addons['date_created']; ?></a></td>
                                                                    <td><a href="#" class="editaddonsrow" data-id="<?php echo $row_addons['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Adds-On"><?php echo $row_addons['last_updated']; ?></a></td>
                                                                    <td>
                                                                        <div class="toggle-select-act fm-cmp-mg">
                                                                            <div class="nk-toggle-switch" data-ts-color="red">
                                                                                <input id="ts_delete_<?php echo $row_addons['id']; ?>" class="tick_delete" data-prompt="success" type="checkbox" hidden="hidden" data-type="addonstb" data-id="<?php echo $row_addons['id']; ?>" <?php echo $checked_active; ?> value="<?php echo $row_addons['at_deleted']; ?>">
                                                                                <label for="ts_delete_<?php echo $row_addons['id']; ?>" class="ts-helper"></label>
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
                    <form method="post" action="addons.php">
                        <input type="hidden" name="addons_id" value="0">
                        <div class="form-example-wrap mg-t-30">
                            <div class="cmp-tb-hd cmp-int-hd">
                                <h2>Edit Adds-On</h2>
                            </div>
                            <div class="form-example-int form-horizental">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                            <label class="hrzn-fm">Name</label>
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                            <div class="nk-int-st">
                                                <input type="text" class="form-control input-sm" name="addons_name" placeholder="Enter Name">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-example-int form-horizental mg-t-15">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                            <label class="hrzn-fm">Code</label>
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                            <div class="nk-int-st">
                                                <input type="text" class="form-control input-sm" name="addons_codetemp" placeholder="Enter Code">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-example-int form-horizental mg-t-15">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                            <label class="hrzn-fm">Category</label>
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                            <?php
                                                if(count($result_category) > 0) {

                                                  foreach ($result_category as $row_category) {

                                                    if($row_category['id']<=10) {
                                                        $row_category['id']="0".$row_category['id'];
                                                    }

                                                      echo '<div class="col-sm-12 mg-t-15"><div class="toggle-select-act fm-cmp-mg"><div class="nk-toggle-switch" data-ts-color="purple"><input id="cat'.$row_category['id'].'" type="checkbox" hidden="hidden" name="categories[]" value="'.$row_category['id'].'"><label for="cat'.$row_category['id'].'" class="ts-helper"></label><label for="cat'.$row_category['id'].'" class="ts-label">'.$row_category['category_name'].'</label></div></div></div>';

                                                  } 

                                                }
                                            ?>
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