<?php 
    session_start(); 
    require './include/config.php';

    $template->set('title', 'Library Code');
    $template->place('header'); // Set page header code (from inc/templates):

?>

    <!-- Data Table area Start-->
    <div class="data-table-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="data-table-list">
                            <div class="basic-tb-hd">
                                <h2>Library Code</h2>
                            </div>
                            <div class="table-responsive">
                                <table id="tablelist" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Code Name</th>
                                            <th>Code Description</th>
                                            <th>Created Date</th>
                                            <th>Last Updated</th>
                                            <th>Created By</th>
                                            <th>Active</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php

                                                $result_codelibrary = PDO_FetchAll("SELECT * FROM `codelibrary_tb` WHERE `cl_deleted` IS NULL");
                                                if(count($result_codelibrary) > 0) {
                                                  
                                                    foreach ($result_codelibrary as $row_codelibrary) {

                                                            ?>
                                                                <tr>
                                                                    <td><a href="#" data-toggle="tooltip" data-placement="top" title="Edit"><?php echo $row_codelibrary['code_name']; ?></a></td>
                                                                    <td><a href="#" data-toggle="tooltip" data-placement="top" title="Edit"><?php echo $row_codelibrary['code_description']; ?></a></td>
                                                                    <td><a href="#" data-toggle="tooltip" data-placement="top" title="Edit"><?php echo $row_codelibrary['date_created']; ?></a></td>
                                                                    <td><a href="#" data-toggle="tooltip" data-placement="top" title="Edit"><?php echo $row_codelibrary['last_updated']; ?></a></td>
                                                                    <td><a href="#" data-toggle="tooltip" data-placement="top" title="Edit">Administrator</a></td>
                                                                    <td>
                                                                        <div class="toggle-select-act fm-cmp-mg">
                                                                            <div class="nk-toggle-switch">
                                                                                <input id="ts1" type="checkbox" hidden="hidden" checked>
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
<?php
    // Set page footer code (from inc/templates):
    $template->place('footer');
?>