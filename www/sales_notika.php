<?php 
    session_start(); 
    require './include/config.php';

    $template->set('title', 'Transaction');
    $template->place('header'); // Set page header code (from inc/templates):

    $btncolors = Array("cyan","teal","amber","orange","deeporange","red","pink","lightblue","blue","indigo","lime","lightgreen","green","purple","deeppurple","gray","bluegray","black");

?>

	<!-- Category area Start-->
	<div class="breadcomb-area">

			<div class="container_sub">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="accordion-wn-wp">
                                <div class="accordion-stn sm-res-mg-t-30">
                                    <div class="panel-group" data-collapse-color="nk-red" id="accordionMenu" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-collapse notika-accrodion-cus">
                                                <div class="panel-heading" role="tab">
                                                    <h4 class="panel-title">
                                                        <a data-toggle="collapse" data-parent="#accordionMenu" href="#accordionMenu-one" aria-expanded="true">Category Menu</a>
                                                    </h4>
                                                </div>
                                                <div id="accordionMenu-one" class="collapse in" role="tabpanel">
                                                    <div class="panel-body">
                                                        <div class="widget-tabs-list">
                                                                    <ul class="nav nav-tabs">
                                                                        <?php 

                                                                            $result_category = PDO_FetchAll("SELECT `id`, `category_name` FROM `category_tb` WHERE `ct_deleted` IS NULL");
                                                                            if(count($result_category) > 0) {
                                                                                $c=0;
                                                                                foreach ($result_category as $row_category) {
                                                                                    $active="";
                                                                                    if($c==0) {
                                                                                        $active="class='active'";
                                                                                    }
                                                                                    echo '<li '.$active.'><a data-toggle="tab" href="#menu'.$c.'">'.$row_category['category_name'].'</a></li>';
                                                                                    $c++;

                                                                                } 
                                                                            }

                                                                        ?>
                                                                    </ul>
                                                                    <div class="tab-content tab-custom-st">
                                                                        <?php 

                                                                            if(count($result_category) > 0) {
                                                                                $c_sub=0;
                                                                                foreach ($result_category as $row_category) {
                                                                                    $sub_active="";
                                                                                    if($c_sub==0) {
                                                                                        $sub_active="in active";
                                                                                    }

                                                                                    echo '<div id="menu'.$c_sub.'" class="tab-pane fade '.$sub_active.'"><div class="tab-ctn"><div class="material-design-btn">';

                                                                                    $result_product = PDO_FetchAll("SELECT `id`, `product_name`, `price` FROM `product_tb` WHERE `pt_deleted` IS NULL AND `category_id`=$row_category[id] ");
                                                                                    if(count($result_product) > 0) {
                                                                                        foreach ($result_product as $row_product) {

                                                                                            echo '<button type="button" class="btn addtolist notika-btn-'.$btncolors[array_rand($btncolors)].'" data-id="'.$row_product['id'].'" data-price="'.$row_product['price'].'" data-category="'.$row_category['category_name'].'">'.$row_product['product_name'].'</button>';

                                                                                        } 
                                                                                    }

                                                                                    echo '</div></div></div>';

                                                                                    $c_sub++;

                                                                                } 
                                                                            }

                                                                        ?>
                                                                    </div>
                                                        </div>          
                                                    </div>
                                                </div>
                                        </div>
                                                                    
                                    </div>
                                </div>
						</div>
					</div>
                    
				</div>
			</div>
	
	</div>
	<!-- Category area End-->
    <!-- Transaction Table area Start-->
    <div class="data-table-area">
        <form method="POST" id="form_transaction" action="receipt.php">
            <div class="container_sub"> 
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">

                        <div class="data-table-list">
                               
                                <div class="table-responsive">
                                    <table id="data-table-basic1" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Product Type</th>
                                                <th>Quantity</th>
                                                <th>Price (PHP)</th>
                                                <th>Total Price (PHP)</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                    
                                </div>
                            
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-element-list">

                                <div id="hiddenclear" class="clearfix"><br /></div>
                                <div class="form-example-int">
                                    <div class="form-group">
                                        <label>Cash Tender</label>
                                        <div class="nk-int-st">
                                            <input type="number" id="cashtend" name="cashtend" class="form-control input-lg" data-mask="Php 999,999,999.99" placeholder="PHP">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-example-int">
                                    <div class="form-group">
                                        <label>Total Amount (Php)</label>
                                        <div class="nk-int-st">
                                           <input type="number" id="totalamt" name="totalamt" readonly class="form-control input-lg" placeholder="Total Amount" value="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-int-st notification-demo material-design-btn">
                                    <button type="submit" id="paysub" class="btn btn-success btn-lg pull-right" data-type="success"><i class="notika-icon notika-next"></i> Submit</button>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Transaction Table area End-->

<?php
    // Set page footer code (from inc/templates):
    $template->place('footer');
?>