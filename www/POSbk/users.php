<?php 
    session_start(); 
    require './include/config.php';

    $template->set('title', 'Users');
    $template->place('header'); // Set page header code (from inc/templates):

    if(isset($_POST) && isset($_POST['savechanges'])) {
        
        $_POST["password"] = md5($_POST["password"]); 
        PDO_Execute("UPDATE `users_tb` SET `username`=?, `password`=?, `fullname`=? WHERE `id`=? ",array($_POST['username'],$_POST["password"],$_POST["fullname"],$_POST['user_id']));

    }
?>

    <!-- Data Table area Start-->
    <div class="data-table-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="data-table-list">
                            <div class="basic-tb-hd">
                                <h2>Users</h2>
                            </div>
                            <div class="table-responsive">
                                <table id="tablelist" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>User Name</th>
                                            <th>Full Name</th>
                                            <th>Created Date</th>
                                            <th>Last Updated</th>
                                            <th>Active</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php

                                                $result_users = PDO_FetchAll("SELECT * FROM `users_tb` WHERE `ut_active` IS NULL");
                                                if(count($result_users) > 0) {
                                                  
                                                    foreach ($result_users as $row_users) {

                                                            ?>
                                                                <tr>
                                                                    <td><a href="#" class="edituserrow" data-id="<?php echo $row_users['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit User"><?php echo $row_users['username']; ?></a></td>
                                                                    <td><a href="#" class="edituserrow" data-id="<?php echo $row_users['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit User"><?php echo $row_users['fullname']; ?></a></td>
                                                                    <td><a href="#" class="edituserrow" data-id="<?php echo $row_users['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit User"><?php echo $row_users['date_created']; ?></a></td>
                                                                    <td><a href="#" class="edituserrow" data-id="<?php echo $row_users['id']; ?>" data-toggle="modal" data-target=".modal" title="Edit User"><?php echo $row_users['last_updated']; ?></a></td>
                                                                    <td>
                                                                        <div class="toggle-select-act fm-cmp-mg">
                                                                            <div class="nk-toggle-switch disabled" data-ts-color="blue">
                                                                                <input id="ts1" type="checkbox" hidden="hidden" checked disabled>
                                                                                <label for="ts1" class="ts-helper"></label>
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
    <div class="modal fade" id="user_modal" role="dialog">
        <div class="modal-dialog modals-default">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post" action="users.php">
                    <div class="modal-body">
                        <input type="hidden" name="user_id" value="0">
                        <div class="form-example-wrap mg-t-30">
                            <div class="cmp-tb-hd cmp-int-hd">
                                <h2>Edit User</h2>
                            </div>
                            <div class="form-example-int form-horizental">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                            <label class="hrzn-fm">User Name</label>
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                            <div class="nk-int-st">
                                                <input type="text" class="form-control input-sm" name="username" placeholder="Enter Username">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-example-int form-horizental">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                            <label class="hrzn-fm">Password</label>
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                            <div class="nk-int-st">
                                                <input type="password" class="form-control input-sm" name="password" placeholder="Enter Password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-example-int form-horizental">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                            <label class="hrzn-fm">Name</label>
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                            <div class="nk-int-st">
                                                <input type="text" class="form-control input-sm" name="fullname" placeholder="Enter Name">
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