<?php 
    session_start(); 
    require './include/config.php';

    $template->set('title', 'Ingredients');
    $template->place('header'); // Set page header code (from inc/templates):

    if(isset($_POST) && isset($_POST['savechanges'])) {

        if(!$_POST['ingredients_id']) {
            PDO_Execute("INSERT INTO `ingredients_tb` (ingredients_name,quantity,measurement_type,user_id,cups_serving) VALUES(?,?,?,?,?)",array($_POST['ingredients_name'],$_POST['quantity'],$_POST['measurement_type'],@$_SESSION["username"],$_POST['cups_serving']));
        } else {
            PDO_Execute("UPDATE `ingredients_tb` SET `ingredients_name`=?, `user_id`=?, `quantity`=?, `measurement_type`=?, `cups_serving`=? WHERE `id`=? ",array($_POST['ingredients_name'],@$_SESSION["username"],$_POST['quantity'],$_POST['measurement_type'],$_POST['cups_serving'],$_POST['ingredients_id']));
        }

    }

    $result_units = PDO_FetchAll("SELECT * FROM `units_tb`");
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
                                        <h2 class="pull-left">Ingredients</h2>
                                        <button type="button" data-toggle="modal" data-target=".modal" title="Add Ingredients" class="btn pull-right addintrow"><i class="notika-icon notika-edit"></i> Add Ingredients</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="tablelist" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Ingredients Name</th>
                                            <th>Qty</th>
                                            <th>Unit Measurement</th>
                                            <th>Cups Serving</th>
                                            <th>Products</th>
                                            <th>Created Date</th>
                                            <th>Last Updated</th>
                                            <th>Active</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php

                                                $result_ingredients = PDO_FetchAll("SELECT * FROM `ingredients_tb`");
                                                if(count($result_ingredients) > 0) {
                                                  
                                                    foreach ($result_ingredients as $row_ingredients) {
                                                        $checked="";
                                                        if(!$row_ingredients['it_deleted']) {
                                                            $checked="checked";
                                                        }

                                                        $result_unitlabel =PDO_FetchOne("SELECT `unit_name` FROM `units_tb` WHERE `id`=?",array($row_ingredients['measurement_type']));
                                                        ?>
                                                            <tr>
                                                                <td><a href="#" class="editintrow" data-id="<?php echo $row_ingredients['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Ingredients"><?php echo $row_ingredients['ingredients_name']; ?></a></td>
                                                                <td><a href="#" class="editintrow" data-id="<?php echo $row_ingredients['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Ingredients"><?php echo $row_ingredients['quantity']; ?></a></td>
                                                                <td><a href="#" class="editintrow" data-id="<?php echo $row_ingredients['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Ingredients"><?php echo (($result_unitlabel) ? $result_unitlabel : "No Measurement type"); ?></a></td>
                                                                <td><a href="#" class="editintrow" data-id="<?php echo $row_ingredients['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Ingredients"><?php echo $row_ingredients['cups_serving']; ?></a></td>
                                                                <td><a href="#" class="editintrow" data-id="<?php echo $row_ingredients['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Ingredients"><?php echo $row_ingredients['products']; ?></a></td>
                                                                <td><a href="#" class="editintrow" data-id="<?php echo $row_ingredients['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Ingredients"><?php echo $row_ingredients['date_created']; ?></a></td>
                                                                <td><a href="#" class="editintrow" data-id="<?php echo $row_ingredients['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Ingredients"><?php echo $row_ingredients['last_updated']; ?></a></td>
                                                                <td>
                                                                    <div class="toggle-select-act fm-cmp-mg">
                                                                        <div class="nk-toggle-switch disabled" data-ts-color="red">
                                                                            <input id="ts<?php echo $row_ingredients['id']; ?>" class="tick_delete" data-prompt="success" type="checkbox" hidden="hidden" data-type="category" data-id="<?php echo $row_ingredients['id']; ?>" value="1" disabled <?php echo $checked; ?>>
                                                                            <label for="ts<?php echo $row_ingredients['id']; ?>" class="ts-helper"></label>
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
    <div class="modal fade" id="ingredients_modal" role="dialog">
        <div class="modal-dialog modals-default">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="ingredients.php">
                        <input type="hidden" name="ingredients_id" value="0">
                        <div class="form-example-wrap mg-t-30">
                            <div class="cmp-tb-hd cmp-int-hd">
                                <h2>Edit Ingredients</h2>
                            </div>
                            <div class="form-example-int form-horizental">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                            <label class="hrzn-fm">Ingredients Name</label>
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                            <div class="nk-int-st">
                                                <input type="text" class="form-control input-sm" name="ingredients_name" placeholder="Enter Ingredients Name">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-example-int form-horizental mg-t-15">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                            <label class="hrzn-fm">Cups Serving</label>
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                            <div class="nk-int-st">
                                                <input type="number" class="form-control input-sm" name="cups_serving" placeholder="Enter how many cups per serving">
                                            </div>
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
                                            <label class="hrzn-fm">Unit Measurement</label>
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                            <select class="form-control" name="measurement_type" required>
                                                <option disabled value='0'>-Please select-</option>
                                                <?php 
                                                if(count($result_units) > 0) {

                                                  foreach ($result_units as $row_units) {

                                                      echo "<option value='".$row_units['id']."'>";
                                                      echo $row_units['unit_name'];
                                                      echo "</option>";

                                                  } 
                                                }
                                                ?>
                                            </select>
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