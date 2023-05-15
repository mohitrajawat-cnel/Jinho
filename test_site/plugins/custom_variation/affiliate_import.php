<?php
include 'wp-config.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

global $wpdb;
if(current_user_can( 'manage_options' ) ) {

} else {

    echo "<center><h2>You can not access this page</h2></center>";
   die();
}

if(isset($_POST['submitdfhdfgd'])) 
    {
        
       //  $handle = fopen($_FILES['csvfile']['tmp_name'], "r");
    
       // $file = fopen(dirname(dirname(dirname(dirname(__FILE__))))."/import_affiliates.csv","r");
        $file = fopen($_FILES['csvfile']['tmp_name'],"r");
        $key=0;
        $headerLine = true;
      
        while(($affiliate_data =fgetcsv($file, 1000, ",")) !== FALSE)
        {

            if($headerLine) { $headerLine = false; }
            else {

                
                    $meta = array();

                    $affiliate_id='';
                    $user_login='';
                    $name='';
                    $email='';
                    $rank='';
                    $visits='';
                    $refferals='';
                    $paid_amount='';
                    $paid_amount='';
                    $unpaid_amount='';
                    $wp_role='';
                    $affiliate_since='';
                    $upline='';

                    
                    
                    $affiliate_id = $affiliate_data[0];
                    $user_login = $affiliate_data[1];
                    $name = $affiliate_data[2];
                    $email = $affiliate_data[3];
                    $rank = $affiliate_data[4];
                    $visits = $affiliate_data[5];
                    $refferals = $affiliate_data[6];
                    $paid_amount = $affiliate_data[7];
                    $unpaid_amount = $affiliate_data[8];
                    $wp_role = $affiliate_data[9];
                    $affiliate_since = $affiliate_data[10];
                    $upline = $affiliate_data[11];

                    if($user_login == '' || $name == '' || !is_numeric($user_login))
                    {
                        continue;
                    }

                  
                    if($rank == 'Member')
                    {
                        $rank_id = 1;
                    }
                    elseif($rank == 'VIP')
                    {
                        $rank_id =2;
                    }
                    elseif($rank == 'VVIP')
                    {
                        $rank_id =3;
                    }
                    elseif($rank == 'SVIP')
                    {
                        $rank_id =4;
                    }
                    elseif($rank == 'Coach')
                    {
                        $rank_id =5;
                    }

                    $user_password = "Testuserhwe@1".$key.rand(10,1000);
                    preg_match_all('!\d+!', $upline, $upline_id);
                    $upline_idhwe1=0;
                    foreach($upline_id as $upline_idhwe4)
                    {
                        foreach($upline_idhwe4 as $upline_idhwe4hwe){
                            $upline_idhwe1 = $upline_idhwe4hwe;
                        }
                        
                    }

                    $get_country_code = substr($user_login, 0, 2);
                    $phone_number = substr($user_login, 2);

                    $country_code = "+".$get_country_code;
                    $digits_phone = "+".$user_login;


                    $meta = array(
                        'first_name' => $name,
                        'user_login' => $user_login,
                        'affiliate_id' => (int)$affiliate_id,
                        "affiliate_upline_id" =>(int)$upline_idhwe1,
                        "digits_phone"=>$digits_phone,
                        "digt_countrycode"=>$country_code,
                        "digits_phone_no"=>$phone_number
                    );

                  
                   
                    $check_user_register = "SELECT * from ".$wpdb->prefix."users where user_login='$user_login'";
                    $count_users = $wpdb->get_results($check_user_register,ARRAY_A);
                    if(count($count_users) <= 0)
                    {
                    
                        $user_id = wp_insert_user( array(
                            'user_login' => $user_login,
                            'user_pass' => $user_password,
                            'user_email' => '',
                            'first_name' => $name,
                            'display_name' => $name,
                            'role' => 'customer'
                        ));

                        //$lastid = $wpdb->insert_id;

                        $selectgsd = "SELECT * from `".$wpdb->prefix."uap_affiliates` WHERE `uid`='$user_id'";
                        $affiliate_result = $wpdb->get_results($selectgsd,ARRAY_A);
                        if(count($affiliate_result) > 0)
                        {
                             $change_affilate_rank = "UPDATE ".$wpdb->prefix."uap_affiliates SET rank_id='$rank_id' where uid='$user_id'";
                            $wpdb->query($change_affilate_rank);
                        }
                        else
                        {
                            $change_affilate_gsdrank = "INSERT INTO ".$wpdb->prefix."uap_affiliates SET rank_id='$rank_id',uid='$user_id',start_data='2023-03-24 16:04:30',status='1'";
                            $wpdb->query($change_affilate_gsdrank);
                        }

                        foreach( $meta as $key => $val ) {
                            update_user_meta( $user_id, $key, $val ); 
                        }
                
                      
                    }

            }

            $key++;
        }


        // $files = fopen($_FILES['csvfile']['tmp_name'], "r");
        $key1=0;
        $headerLine1 = true;
        while(($affiliate_data_hwe =fgetcsv($file, 1000, ",")) !== FALSE)
        {

        
            if($headerLine1) { $headerLine1 = false; }
            else 
            {
                $affiliate_id = $affiliate_data_hwe[0];

                $get_user_id_by_affiliate_id = "SELECT * from ".$wpdb->prefix."usermeta where meta_key='affiliate_id' && meta_value='$affiliate_id'";
                $results = $wpdb->get_results($get_user_id_by_affiliate_id,ARRAY_A);
                foreach($results as $results_hwe)
                {
                    $user_id = $results_hwe['user_id'];
                }

                $upline_id = $affiliate_data_hwe[11];
                preg_match_all('!\d+!', $upline_id, $upline_idhwe);
                $upline_affiliate_id =(int)$upline_idhwe[0][0];

                $get_upline_user_id_by_affiliate_id = "SELECT * from ".$wpdb->prefix."usermeta where meta_key='affiliate_id' && meta_value='$upline_affiliate_id'";
                $results1 = $wpdb->get_results($get_upline_user_id_by_affiliate_id,ARRAY_A);
                if(count($results1) > 0)
                {
                    foreach($results1 as $results_hwe_hwe)
                    {
                        $upline_user_id = $results_hwe_hwe['user_id'];
                    }
                }
            
                $timestamp = strtotime($affiliate_data_hwe[10]);
                $date = date("Y-m-d H:i:s",$timestamp);

                if($user_id != '' && $upline_user_id != '')
                {
                    $select_upline = "SELECT * from ".$wpdb->prefix."uap_affiliate_referral_users_relations where affiliate_id='$user_id' && referral_wp_uid='$upline_user_id'";
                    $get_upline_records = $wpdb->get_results($select_upline,ARRAY_A);
                    if(count($get_upline_records) <= 0)
                    {
                        $make_upline_refferal  = "INSERT INTO ".$wpdb->prefix."uap_affiliate_referral_users_relations SET affiliate_id='$user_id',referral_wp_uid='$upline_user_id',DATE='$date'";
                        $wpdb->query($make_upline_refferal);

                        
                    }


                    // $selectgsd = "SELECT * from `".$wpdb->prefix."uap_affiliates` WHERE `uid`='$user_id'";
                    // $affiliate_result = $wpdb->get_results($selectgsd,ARRAY_A);
                    // if(count($affiliate_result) > 0)
                    // {
                    //     $sql_data = "INSERT INTO ".$wpdb->prefix."uap_mlm_relations
                    //     (`affiliate_id`,`parent_affiliate_id`) 
                    //     values ($affiliate_id, $refid)";
                    //    $wpdb->query($sql_data);
                    // }
                
                }
                
                $key1++;
            }
            
        }

    fclose($file);


    ?>
      <script>
        alert("Affiliate Imported Successfully.");
    </script>
    <?php
    }

    ?>
<html lang="en">
    <head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <body>

        <div class="container">
            <div class="custom_affiliate" style="position: absolute;top: 10%;left: 40%;width: 25%;">
                <form enctype='multipart/form-data' method='post'>
                
                    <label>Upload Affiliate CSV file</label>
                    <input type='file' name='csvfile'>
                    </br>
                    <input type='submit' name='submitdfhdfgd' class="btn btn-primary" value='Affiliate Import'>
                
                </form>

            </div>
        </div>

    </body>
    </html>