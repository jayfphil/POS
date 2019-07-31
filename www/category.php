<?php 
    session_start(); 
    require './include/config.php';

    $template->set('title', 'Category');
    $template->place('header'); // Set page header code (from inc/templates):

    if(isset($_POST) && isset($_POST['savechanges'])) {

        if(!$_POST['category_id']) {
            PDO_Execute("INSERT INTO `category_tb` (category_name,user_id) VALUES(?,?)",array($_POST['category_name'],@$_SESSION["username"]));
        } else {
            PDO_Execute("UPDATE `category_tb` SET `category_name`=?, `user_id`=? WHERE `id`=? ",array($_POST['category_name'],@$_SESSION["username"],$_POST['category_id']));
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
                                        <h2 class="pull-left">Category</h2>
                                        <button type="button" data-toggle="modal" data-target=".modal" title="Add Category" class="btn pull-right addcatrow"><i class="notika-icon notika-edit"></i> Add Category</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="tablelist" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Category Name</th>
                                            <th>Created Date</th>
                                            <th>Last Updated</th>
                                            <th>Active</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php

                                                $result_category = PDO_FetchAll("SELECT * FROM `category_tb`");
                                                if(count($result_category) > 0) {
                                                  
                                                    foreach ($result_category as $row_category) {
                                                        $checked="";
                                                        if(!$row_category['ct_deleted']) {
                                                            $checked="checked";
                                                        }
                                                        ?>
                                                            <tr>
                                                                <td><a href="#" class="editcatrow" data-id="<?php echo $row_category['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Category"><?php echo $row_category['category_name']; ?></a></td>
                                                                <td><a href="#" class="editcatrow" data-id="<?php echo $row_category['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Category"><?php echo $row_category['date_created']; ?></a></td>
                                                                <td><a href="#" class="editcatrow" data-id="<?php echo $row_category['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit Category"><?php echo $row_category['last_updated']; ?></a></td>
                                                                <td>
                                                                    <div class="toggle-select-act fm-cmp-mg">
                                                                        <div class="nk-toggle-switch" data-ts-color="red">
                                                                            <input id="ts<?php echo $row_category['id']; ?>" class="tick_delete" data-prompt="success" type="checkbox" hidden="hidden" data-type="category" data-id="<?php echo $row_category['id']; ?>" value="1" <?php echo $checked; ?>>
                                                                            <label for="ts<?php echo $row_category['id']; ?>" class="ts-helper"></label>
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
    <div class="modal fade" id="category_modal" role="dialog">
        <div class="modal-dialog modals-default">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post" action="category.php">
                    <div class="modal-body">
                        <input type="hidden" name="category_id" value="0">
                        <div class="form-example-wrap mg-t-30">
                            <div class="cmp-tb-hd cmp-int-hd">
                                <h2>Edit Category</h2>
                            </div>
                            <div class="form-example-int form-horizental">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                            <label class="hrzn-fm">Category Name</label>
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                            <div class="nk-int-st">
                                                <input type="text" class="form-control input-sm" name="category_name" placeholder="Enter Category Name">
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