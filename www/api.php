<?php 
// session_start();
require './include/config_pos.php';

if(isset($_POST)) { 
    // $output['success']=0;

    if(isset($_POST['cashtend'])) {

        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";
        // exit();
        PDO_Execute("INSERT INTO `transaction_tb` (user_id,cash_tender,total_amt,customer_name,discount_all,total_amtdisc,date_created) VALUES(?,?,?,?,?,?,date('now','+1 day'))",array($_POST['userid'],$_POST['cashtend'],$_POST['totalamt'],$_POST['customer_name'],$_POST['discount_all'],$_POST['amountdue']));

        $last = PDO_LastInsertId();

        foreach ($_POST['products'] as $product => $values) {

            if(!isset($values['sugar_level'])) {
                $values['sugar_level']="";
            }

            // PDO_Execute("UPDATE `product_tb` SET `quantity`=`quantity`-1 WHERE `id`='".$values['identifier']."' ");
            PDO_Execute("INSERT INTO `transactionitems_tb` (transact_id,product_id,price,quantity,sugar_level,discount,date_created) VALUES(?,?,?,?,?,?,date('now','+1 day'))",array($last,$values['identifier'],$values['price'],1,$values['sugar_level'],$values['discount']));
            $lastitem = PDO_LastInsertId();

            if(isset($_POST['addons'][$product])) {

                foreach ($_POST['addons'][$product] as $prod => $val) {

                    PDO_Execute("UPDATE `transactionitems_tb` SET `addons_metajson`='".json_encode($val)."' WHERE `transact_id`='".$last."' AND `product_id`='".$prod."' AND `id`='".$lastitem."' ");

                    // foreach ($val as $k => $v) {
                    //     if($k=="free") {
                    //         $v = explode("-",$v);
                    //         PDO_Execute("UPDATE `addons_tb` SET `quantity`=`quantity`-1 WHERE `id`='".$v[0]."' ");
                    //     } else {
                    //         PDO_Execute("UPDATE `addons_tb` SET `quantity`=`quantity`-1 WHERE `id`='".$k."' ");
                    //     }
                    // }
                }

            }

        }
        
        $output['success']=1;
        $output['last_id']=$last;
        echo json_encode( $output );
    }

    if(isset($_POST['done'])) {

        PDO_Execute("UPDATE `transaction_tb` SET `tt_completed`=1 WHERE `id`='$_POST[done]' ");
        $output['success']=1;
        echo json_encode( $output );
    }

    if(isset($_POST['void'])) {

        PDO_Execute("UPDATE `transaction_tb` SET `tt_voided`=1 WHERE `id`='$_POST[void]' ");
        $output['success']=1;
        echo json_encode( $output );
    }

    if(isset($_POST['process_id']) && isset($_POST['process_value']) && isset($_POST['process_type'])) {

        $output['success']=0;

        if($_POST['process_type']=="product") {
            $table="`product_tb`";
            $field="`pt_deleted`";
        }

        if($_POST['process_type']=="ingredients") {
            $table="`ingredients_tb`";
            $field="`it_deleted`";
        }

        if($_POST['process_type']=="category") {
            $table="`category_tb`";
            $field="`ct_deleted`";
        }

        if($_POST['process_type']=="addonstb") {
            $table="`addons_tb`";
            $field="`at_deleted`";
        }

        if($_POST['process_type']=="editableprice") {
            $table="`product_tb`";
            $field="`pt_customprice`";

            if($_POST['process_value']!="true"){
                $_POST['process_value']="=NULL";
            } else {
                $_POST['process_value']="=1";
            }
        } else {
            if($_POST['process_value']=="true"){
                $_POST['process_value']="=NULL";
            } else {
                $_POST['process_value']="=1";
            }
        }
       
        PDO_Execute("UPDATE ".$table." SET ".$field.$_POST['process_value']." WHERE `id`='$_POST[process_id]' ");
        $output['success']=1;

        echo json_encode( $output );
    }
    
}

if(isset($_REQUEST)) { 

    // $output['success']=0;

    if(isset($_REQUEST['product_id'])) {

        $result_productdetail['products'] = PDO_FetchRow("SELECT * FROM `product_tb` WHERE `id`=?",array($_REQUEST['product_id']));

        $addons_find=$result_productdetail['products']['category_id'];
        if($result_productdetail['products']['category_id']<=10) {
            $addons_find="0".$result_productdetail['products']['category_id'];
        }

        $result_productdetail['addons'] = PDO_FetchAll("SELECT * FROM `addons_tb` WHERE `at_deleted` IS NULL AND `category_text` LIKE '%".$addons_find."%' ");

        echo json_encode( $result_productdetail );

    }

    if(isset($_REQUEST['addons_id'])) {

        $result_addonsdetail = PDO_FetchRow("SELECT * FROM `addons_tb` WHERE `id`=?",array($_REQUEST['addons_id']));
        echo json_encode( $result_addonsdetail );

    }

    if(isset($_REQUEST['category_id'])) {

        $result_categorydetail = PDO_FetchRow("SELECT * FROM `category_tb` WHERE `id`=?",array($_REQUEST['category_id']));
        echo json_encode( $result_categorydetail );
        
    }

    if(isset($_REQUEST['ingredients_id'])) {

        $result_ingredientsdetail = PDO_FetchRow("SELECT * FROM `ingredients_tb` WHERE `id`=?",array($_REQUEST['ingredients_id']));
        echo json_encode( $result_ingredientsdetail );
        
    }

    if(isset($_REQUEST['user_id'])) {

        $result_userdetail = PDO_FetchRow("SELECT * FROM `users_tb` WHERE `id`=?",array($_REQUEST['user_id']));
        echo json_encode( $result_userdetail );

    }

    if(isset($_REQUEST['password'])) {

        $output['success']=0;
        $_REQUEST["password"] = md5($_REQUEST["password"]); 
        $result = PDO_FetchAll("SELECT * FROM `users_tb` WHERE id<>3 AND password = ? AND ut_active IS NULL",array($_REQUEST["password"]));
        if(count($result) > 0) { 
            $output['success']=1; 
        }

        echo json_encode( $output );
        
    }

}

?>