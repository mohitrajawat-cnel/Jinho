<?php
/*
 * Plugin Name: Custom variation Hide
 *Description: form plugin in my file.
 * version: 10.0
 * Author:Prince
 * Author URI:https/form.com
 */
function action_woocommerce_variation_options( $loop, $variation_data, $variation ) {
    $is_checked = get_post_meta( $variation->ID, 'custom_variation_hide', true );

    // if ( $is_checked == 'yes' ) {
    //     $is_checked = 'checked'; 
    // } else {
    //     $is_checked = '';
    // }

    if ( $is_checked == 'no' ) {
        $is_checked = ''; 
    } else {
        $is_checked = 'checked'; 
    }

    ?>
    <label class="tips" data-tip="<?php esc_attr_e( 'This is my data tip', 'woocommerce' ); ?>">
        <?php esc_html_e( 'Hide Variation', 'woocommerce' ); ?>
        <input type="checkbox" class="checkbox variable_checkbox" name="custom_variation_hide[<?php echo esc_attr( $loop ); ?>]"<?php echo $is_checked; ?>/>
    </label>
    <?php
}
add_action( 'woocommerce_variation_options', 'action_woocommerce_variation_options', 10, 3);

// Save checkbox
function action_woocommerce_save_product_variation( $variation_id, $i ) {

   

   // $variation->get_formatted_name();

    if ( ! empty( $_POST['custom_variation_hide'] ) && ! empty( $_POST['custom_variation_hide'][$i] ) ) {

        $variation = wc_get_product($variation_id);

        //echo $variation->get_formatted_name();
        
         $explode = explode(':',$variation->get_formatted_name());
        //$variations = $product->get_available_variations();
//print_r($variation);
  //  echo trim($explode[1]);
        update_option('varoation_data_hwe',strip_tags($explode[1]));

        update_post_meta( $variation_id, 'custom_variation_hide', strip_tags($explode[1]) );
        // update_post_meta( $variation_id, 'custom_variation_hide','yes' );
    } else {
        update_post_meta( $variation_id, 'custom_variation_hide', 'no' ); 
    }       
}
add_action( 'woocommerce_save_product_variation', 'action_woocommerce_save_product_variation', 10, 2 );

add_filter('woocommerce_dropdown_variation_attribute_options_args', 'hide_variations_for_mindesk_users');

function hide_variations_for_mindesk_users( $args ){      
    
    global $wpdb;
    
  //print_r($args['product']);
  $variation_ids =  $args['product']->get_children();

 // $get_variation_attributes =  $args['product']->get_available_variations();

  $get_variation_attributes =  $args['product']->get_variation_attributes();
  foreach($get_variation_attributes as $get_variation_attributes_hwe)
  {
    $all_attributes = $get_variation_attributes_hwe;

  }

  foreach($all_attributes as $key => $all_attributes_hwe)
  {
    $all_attributes_hwe = strip_tags($all_attributes_hwe);

    foreach($variation_ids as $key_hwe => $variation_ids_hwe)
    {
        
        $vaiation_hide_value = get_post_meta( $variation_ids_hwe, 'custom_variation_hide',true); 
        $vaiation_hide_value= strip_tags($vaiation_hide_value);

        if($vaiation_hide_value != 'no')
        {
            
            if(trim($all_attributes_hwe) == trim($vaiation_hide_value))
            {
                unset($args['options'][$key]);
            }
        }
        

        
    }
 
   
  }
 
   return $args;
   
   
}

add_action( 'user_register', 'myplugin_registration_save', 170, 170 );

function myplugin_registration_save( $user_id ) {
    global $wpdb;
    if(is_admin() && isset($_POST['user_login']))
    {

        $country_code='+60';
        if(isset($_POST['dig_countrycodec']) && $_POST['dig_countrycodec'] != '')
        {
            $country_code = $_POST['dig_countrycodec'];
        }
        elseif(isset($_POST['admin_user_country_code']) && $_POST['admin_user_country_code'] != '')
        {
            $country_code = "+".$_POST['admin_user_country_code'];
        }
        
        $all_mobile_number = $country_code.$_POST['user_login'];
        update_user_meta($user_id,'digits_phone',$all_mobile_number);
        update_user_meta($user_id,'digt_countrycode',$country_code);
        update_user_meta($user_id,'digits_phone_no',$_POST['user_login']);

        $countryArray = array(
            'AD'=>array('name'=>'ANDORRA','code'=>'376'),
            'AE'=>array('name'=>'UNITED ARAB EMIRATES','code'=>'971'),
            'AF'=>array('name'=>'AFGHANISTAN','code'=>'93'),
            'AG'=>array('name'=>'ANTIGUA AND BARBUDA','code'=>'1268'),
            'AI'=>array('name'=>'ANGUILLA','code'=>'1264'),
            'AL'=>array('name'=>'ALBANIA','code'=>'355'),
            'AM'=>array('name'=>'ARMENIA','code'=>'374'),
            'AN'=>array('name'=>'NETHERLANDS ANTILLES','code'=>'599'),
            'AO'=>array('name'=>'ANGOLA','code'=>'244'),
            'AQ'=>array('name'=>'ANTARCTICA','code'=>'672'),
            'AR'=>array('name'=>'ARGENTINA','code'=>'54'),
            'AS'=>array('name'=>'AMERICAN SAMOA','code'=>'1684'),
            'AT'=>array('name'=>'AUSTRIA','code'=>'43'),
            'AU'=>array('name'=>'AUSTRALIA','code'=>'61'),
            'AW'=>array('name'=>'ARUBA','code'=>'297'),
            'AZ'=>array('name'=>'AZERBAIJAN','code'=>'994'),
            'BA'=>array('name'=>'BOSNIA AND HERZEGOVINA','code'=>'387'),
            'BB'=>array('name'=>'BARBADOS','code'=>'1246'),
            'BD'=>array('name'=>'BANGLADESH','code'=>'880'),
            'BE'=>array('name'=>'BELGIUM','code'=>'32'),
            'BF'=>array('name'=>'BURKINA FASO','code'=>'226'),
            'BG'=>array('name'=>'BULGARIA','code'=>'359'),
            'BH'=>array('name'=>'BAHRAIN','code'=>'973'),
            'BI'=>array('name'=>'BURUNDI','code'=>'257'),
            'BJ'=>array('name'=>'BENIN','code'=>'229'),
            'BL'=>array('name'=>'SAINT BARTHELEMY','code'=>'590'),
            'BM'=>array('name'=>'BERMUDA','code'=>'1441'),
            'BN'=>array('name'=>'BRUNEI DARUSSALAM','code'=>'673'),
            'BO'=>array('name'=>'BOLIVIA','code'=>'591'),
            'BR'=>array('name'=>'BRAZIL','code'=>'55'),
            'BS'=>array('name'=>'BAHAMAS','code'=>'1242'),
            'BT'=>array('name'=>'BHUTAN','code'=>'975'),
            'BW'=>array('name'=>'BOTSWANA','code'=>'267'),
            'BY'=>array('name'=>'BELARUS','code'=>'375'),
            'BZ'=>array('name'=>'BELIZE','code'=>'501'),
            'CA'=>array('name'=>'CANADA','code'=>'1'),
            'CC'=>array('name'=>'COCOS (KEELING) ISLANDS','code'=>'61'),
            'CD'=>array('name'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE','code'=>'243'),
            'CF'=>array('name'=>'CENTRAL AFRICAN REPUBLIC','code'=>'236'),
            'CG'=>array('name'=>'CONGO','code'=>'242'),
            'CH'=>array('name'=>'SWITZERLAND','code'=>'41'),
            'CI'=>array('name'=>'COTE D IVOIRE','code'=>'225'),
            'CK'=>array('name'=>'COOK ISLANDS','code'=>'682'),
            'CL'=>array('name'=>'CHILE','code'=>'56'),
            'CM'=>array('name'=>'CAMEROON','code'=>'237'),
            'CN'=>array('name'=>'CHINA','code'=>'86'),
            'CO'=>array('name'=>'COLOMBIA','code'=>'57'),
            'CR'=>array('name'=>'COSTA RICA','code'=>'506'),
            'CU'=>array('name'=>'CUBA','code'=>'53'),
            'CV'=>array('name'=>'CAPE VERDE','code'=>'238'),
            'CX'=>array('name'=>'CHRISTMAS ISLAND','code'=>'61'),
            'CY'=>array('name'=>'CYPRUS','code'=>'357'),
            'CZ'=>array('name'=>'CZECH REPUBLIC','code'=>'420'),
            'DE'=>array('name'=>'GERMANY','code'=>'49'),
            'DJ'=>array('name'=>'DJIBOUTI','code'=>'253'),
            'DK'=>array('name'=>'DENMARK','code'=>'45'),
            'DM'=>array('name'=>'DOMINICA','code'=>'1767'),
            'DO'=>array('name'=>'DOMINICAN REPUBLIC','code'=>'1809'),
            'DZ'=>array('name'=>'ALGERIA','code'=>'213'),
            'EC'=>array('name'=>'ECUADOR','code'=>'593'),
            'EE'=>array('name'=>'ESTONIA','code'=>'372'),
            'EG'=>array('name'=>'EGYPT','code'=>'20'),
            'ER'=>array('name'=>'ERITREA','code'=>'291'),
            'ES'=>array('name'=>'SPAIN','code'=>'34'),
            'ET'=>array('name'=>'ETHIOPIA','code'=>'251'),
            'FI'=>array('name'=>'FINLAND','code'=>'358'),
            'FJ'=>array('name'=>'FIJI','code'=>'679'),
            'FK'=>array('name'=>'FALKLAND ISLANDS (MALVINAS)','code'=>'500'),
            'FM'=>array('name'=>'MICRONESIA, FEDERATED STATES OF','code'=>'691'),
            'FO'=>array('name'=>'FAROE ISLANDS','code'=>'298'),
            'FR'=>array('name'=>'FRANCE','code'=>'33'),
            'GA'=>array('name'=>'GABON','code'=>'241'),
            'GB'=>array('name'=>'UNITED KINGDOM','code'=>'44'),
            'GD'=>array('name'=>'GRENADA','code'=>'1473'),
            'GE'=>array('name'=>'GEORGIA','code'=>'995'),
            'GH'=>array('name'=>'GHANA','code'=>'233'),
            'GI'=>array('name'=>'GIBRALTAR','code'=>'350'),
            'GL'=>array('name'=>'GREENLAND','code'=>'299'),
            'GM'=>array('name'=>'GAMBIA','code'=>'220'),
            'GN'=>array('name'=>'GUINEA','code'=>'224'),
            'GQ'=>array('name'=>'EQUATORIAL GUINEA','code'=>'240'),
            'GR'=>array('name'=>'GREECE','code'=>'30'),
            'GT'=>array('name'=>'GUATEMALA','code'=>'502'),
            'GU'=>array('name'=>'GUAM','code'=>'1671'),
            'GW'=>array('name'=>'GUINEA-BISSAU','code'=>'245'),
            'GY'=>array('name'=>'GUYANA','code'=>'592'),
            'HK'=>array('name'=>'HONG KONG','code'=>'852'),
            'HN'=>array('name'=>'HONDURAS','code'=>'504'),
            'HR'=>array('name'=>'CROATIA','code'=>'385'),
            'HT'=>array('name'=>'HAITI','code'=>'509'),
            'HU'=>array('name'=>'HUNGARY','code'=>'36'),
            'ID'=>array('name'=>'INDONESIA','code'=>'62'),
            'IE'=>array('name'=>'IRELAND','code'=>'353'),
            'IL'=>array('name'=>'ISRAEL','code'=>'972'),
            'IM'=>array('name'=>'ISLE OF MAN','code'=>'44'),
            'IN'=>array('name'=>'INDIA','code'=>'91'),
            'IQ'=>array('name'=>'IRAQ','code'=>'964'),
            'IR'=>array('name'=>'IRAN, ISLAMIC REPUBLIC OF','code'=>'98'),
            'IS'=>array('name'=>'ICELAND','code'=>'354'),
            'IT'=>array('name'=>'ITALY','code'=>'39'),
            'JM'=>array('name'=>'JAMAICA','code'=>'1876'),
            'JO'=>array('name'=>'JORDAN','code'=>'962'),
            'JP'=>array('name'=>'JAPAN','code'=>'81'),
            'KE'=>array('name'=>'KENYA','code'=>'254'),
            'KG'=>array('name'=>'KYRGYZSTAN','code'=>'996'),
            'KH'=>array('name'=>'CAMBODIA','code'=>'855'),
            'KI'=>array('name'=>'KIRIBATI','code'=>'686'),
            'KM'=>array('name'=>'COMOROS','code'=>'269'),
            'KN'=>array('name'=>'SAINT KITTS AND NEVIS','code'=>'1869'),
            'KP'=>array('name'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF','code'=>'850'),
            'KR'=>array('name'=>'KOREA REPUBLIC OF','code'=>'82'),
            'KW'=>array('name'=>'KUWAIT','code'=>'965'),
            'KY'=>array('name'=>'CAYMAN ISLANDS','code'=>'1345'),
            'KZ'=>array('name'=>'KAZAKSTAN','code'=>'7'),
            'LA'=>array('name'=>'LAO PEOPLES DEMOCRATIC REPUBLIC','code'=>'856'),
            'LB'=>array('name'=>'LEBANON','code'=>'961'),
            'LC'=>array('name'=>'SAINT LUCIA','code'=>'1758'),
            'LI'=>array('name'=>'LIECHTENSTEIN','code'=>'423'),
            'LK'=>array('name'=>'SRI LANKA','code'=>'94'),
            'LR'=>array('name'=>'LIBERIA','code'=>'231'),
            'LS'=>array('name'=>'LESOTHO','code'=>'266'),
            'LT'=>array('name'=>'LITHUANIA','code'=>'370'),
            'LU'=>array('name'=>'LUXEMBOURG','code'=>'352'),
            'LV'=>array('name'=>'LATVIA','code'=>'371'),
            'LY'=>array('name'=>'LIBYAN ARAB JAMAHIRIYA','code'=>'218'),
            'MA'=>array('name'=>'MOROCCO','code'=>'212'),
            'MC'=>array('name'=>'MONACO','code'=>'377'),
            'MD'=>array('name'=>'MOLDOVA, REPUBLIC OF','code'=>'373'),
            'ME'=>array('name'=>'MONTENEGRO','code'=>'382'),
            'MF'=>array('name'=>'SAINT MARTIN','code'=>'1599'),
            'MG'=>array('name'=>'MADAGASCAR','code'=>'261'),
            'MH'=>array('name'=>'MARSHALL ISLANDS','code'=>'692'),
            'MK'=>array('name'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','code'=>'389'),
            'ML'=>array('name'=>'MALI','code'=>'223'),
            'MM'=>array('name'=>'MYANMAR','code'=>'95'),
            'MN'=>array('name'=>'MONGOLIA','code'=>'976'),
            'MO'=>array('name'=>'MACAU','code'=>'853'),
            'MP'=>array('name'=>'NORTHERN MARIANA ISLANDS','code'=>'1670'),
            'MR'=>array('name'=>'MAURITANIA','code'=>'222'),
            'MS'=>array('name'=>'MONTSERRAT','code'=>'1664'),
            'MT'=>array('name'=>'MALTA','code'=>'356'),
            'MU'=>array('name'=>'MAURITIUS','code'=>'230'),
            'MV'=>array('name'=>'MALDIVES','code'=>'960'),
            'MW'=>array('name'=>'MALAWI','code'=>'265'),
            'MX'=>array('name'=>'MEXICO','code'=>'52'),
            'MY'=>array('name'=>'MALAYSIA','code'=>'60'),
            'MZ'=>array('name'=>'MOZAMBIQUE','code'=>'258'),
            'NA'=>array('name'=>'NAMIBIA','code'=>'264'),
            'NC'=>array('name'=>'NEW CALEDONIA','code'=>'687'),
            'NE'=>array('name'=>'NIGER','code'=>'227'),
            'NG'=>array('name'=>'NIGERIA','code'=>'234'),
            'NI'=>array('name'=>'NICARAGUA','code'=>'505'),
            'NL'=>array('name'=>'NETHERLANDS','code'=>'31'),
            'NO'=>array('name'=>'NORWAY','code'=>'47'),
            'NP'=>array('name'=>'NEPAL','code'=>'977'),
            'NR'=>array('name'=>'NAURU','code'=>'674'),
            'NU'=>array('name'=>'NIUE','code'=>'683'),
            'NZ'=>array('name'=>'NEW ZEALAND','code'=>'64'),
            'OM'=>array('name'=>'OMAN','code'=>'968'),
            'PA'=>array('name'=>'PANAMA','code'=>'507'),
            'PE'=>array('name'=>'PERU','code'=>'51'),
            'PF'=>array('name'=>'FRENCH POLYNESIA','code'=>'689'),
            'PG'=>array('name'=>'PAPUA NEW GUINEA','code'=>'675'),
            'PH'=>array('name'=>'PHILIPPINES','code'=>'63'),
            'PK'=>array('name'=>'PAKISTAN','code'=>'92'),
            'PL'=>array('name'=>'POLAND','code'=>'48'),
            'PM'=>array('name'=>'SAINT PIERRE AND MIQUELON','code'=>'508'),
            'PN'=>array('name'=>'PITCAIRN','code'=>'870'),
            'PR'=>array('name'=>'PUERTO RICO','code'=>'1'),
            'PT'=>array('name'=>'PORTUGAL','code'=>'351'),
            'PW'=>array('name'=>'PALAU','code'=>'680'),
            'PY'=>array('name'=>'PARAGUAY','code'=>'595'),
            'QA'=>array('name'=>'QATAR','code'=>'974'),
            'RO'=>array('name'=>'ROMANIA','code'=>'40'),
            'RS'=>array('name'=>'SERBIA','code'=>'381'),
            'RU'=>array('name'=>'RUSSIAN FEDERATION','code'=>'7'),
            'RW'=>array('name'=>'RWANDA','code'=>'250'),
            'SA'=>array('name'=>'SAUDI ARABIA','code'=>'966'),
            'SB'=>array('name'=>'SOLOMON ISLANDS','code'=>'677'),
            'SC'=>array('name'=>'SEYCHELLES','code'=>'248'),
            'SD'=>array('name'=>'SUDAN','code'=>'249'),
            'SE'=>array('name'=>'SWEDEN','code'=>'46'),
            'SG'=>array('name'=>'SINGAPORE','code'=>'65'),
            'SH'=>array('name'=>'SAINT HELENA','code'=>'290'),
            'SI'=>array('name'=>'SLOVENIA','code'=>'386'),
            'SK'=>array('name'=>'SLOVAKIA','code'=>'421'),
            'SL'=>array('name'=>'SIERRA LEONE','code'=>'232'),
            'SM'=>array('name'=>'SAN MARINO','code'=>'378'),
            'SN'=>array('name'=>'SENEGAL','code'=>'221'),
            'SO'=>array('name'=>'SOMALIA','code'=>'252'),
            'SR'=>array('name'=>'SURINAME','code'=>'597'),
            'ST'=>array('name'=>'SAO TOME AND PRINCIPE','code'=>'239'),
            'SV'=>array('name'=>'EL SALVADOR','code'=>'503'),
            'SY'=>array('name'=>'SYRIAN ARAB REPUBLIC','code'=>'963'),
            'SZ'=>array('name'=>'SWAZILAND','code'=>'268'),
            'TC'=>array('name'=>'TURKS AND CAICOS ISLANDS','code'=>'1649'),
            'TD'=>array('name'=>'CHAD','code'=>'235'),
            'TG'=>array('name'=>'TOGO','code'=>'228'),
            'TH'=>array('name'=>'THAILAND','code'=>'66'),
            'TJ'=>array('name'=>'TAJIKISTAN','code'=>'992'),
            'TK'=>array('name'=>'TOKELAU','code'=>'690'),
            'TL'=>array('name'=>'TIMOR-LESTE','code'=>'670'),
            'TM'=>array('name'=>'TURKMENISTAN','code'=>'993'),
            'TN'=>array('name'=>'TUNISIA','code'=>'216'),
            'TO'=>array('name'=>'TONGA','code'=>'676'),
            'TR'=>array('name'=>'TURKEY','code'=>'90'),
            'TT'=>array('name'=>'TRINIDAD AND TOBAGO','code'=>'1868'),
            'TV'=>array('name'=>'TUVALU','code'=>'688'),
            'TW'=>array('name'=>'TAIWAN, PROVINCE OF CHINA','code'=>'886'),
            'TZ'=>array('name'=>'TANZANIA, UNITED REPUBLIC OF','code'=>'255'),
            'UA'=>array('name'=>'UKRAINE','code'=>'380'),
            'UG'=>array('name'=>'UGANDA','code'=>'256'),
            'US'=>array('name'=>'UNITED STATES','code'=>'1'),
            'UY'=>array('name'=>'URUGUAY','code'=>'598'),
            'UZ'=>array('name'=>'UZBEKISTAN','code'=>'998'),
            'VA'=>array('name'=>'HOLY SEE (VATICAN CITY STATE)','code'=>'39'),
            'VC'=>array('name'=>'SAINT VINCENT AND THE GRENADINES','code'=>'1784'),
            'VE'=>array('name'=>'VENEZUELA','code'=>'58'),
            'VG'=>array('name'=>'VIRGIN ISLANDS, BRITISH','code'=>'1284'),
            'VI'=>array('name'=>'VIRGIN ISLANDS, U.S.','code'=>'1340'),
            'VN'=>array('name'=>'VIET NAM','code'=>'84'),
            'VU'=>array('name'=>'VANUATU','code'=>'678'),
            'WF'=>array('name'=>'WALLIS AND FUTUNA','code'=>'681'),
            'WS'=>array('name'=>'SAMOA','code'=>'685'),
            'XK'=>array('name'=>'KOSOVO','code'=>'381'),
            'YE'=>array('name'=>'YEMEN','code'=>'967'),
            'YT'=>array('name'=>'MAYOTTE','code'=>'262'),
            'ZA'=>array('name'=>'SOUTH AFRICA','code'=>'27'),
            'ZM'=>array('name'=>'ZAMBIA','code'=>'260'),
            'ZW'=>array('name'=>'ZIMBABWE','code'=>'263')
        );

        foreach($countryArray as $key_country => $countryArray_hwe)
        {
            if($countryArray_hwe['code'] == $_POST['admin_user_country_code'])
            {
                update_user_meta($user_id,'uap_country',strtolower($key_country));
            }
        }    
    }
    // $refid= $_POST['digits_reg_referralid'];
    //echo $select_user_mlm_id = "select * from ".$wpdb->prefix."uap_affiliates where uid='$user_id'";
    // $results = $wpdb->get_results($select_user_mlm_id);
    // print_r($results);
    // die();
    // if(count($results) > 0)
    // {
    //     foreach($results as $results_hwe)
    //     {
    //        // $
    //     }
    //     $sql = "INSERT INTO ".$wpdb->prefix."uap_mlm_relations
    //     (`affiliate_id`,`parent_affiliate_id`) 
    //     values ($user_id, $refid)";
    
    //     $wpdb->query($sql);
    // }
}

//Created code by Sourabh
add_action( 'user_register', 'add_shoppingAnd_charityWallet' );

function add_shoppingAnd_charityWallet( $userId ) {
    // update_user_meta( $userId, 'user_shopping_wallet', 0 );
    // update_user_meta( $userId, 'user_charity_wallet', 0 );
}

add_action( 'wp_head', 'myplugin_registration_savehwe', 10, 1 );
function myplugin_registration_savehwe() {
    $refid='';
    if(isset($_REQUEST['ref']) && $_REQUEST['ref']!='')
    {
        $refid=$_REQUEST['ref'];
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>
            jQuery(document).ready(function(){
            var refid= '<?php echo $refid;?>';
            setTimeout(function(){
                jQuery('input[name="digits_reg_referralid"]').attr('value',refid);
            }, 5000);
            //jQuery('input[name="digits_reg_referralid"]').attr('value',refid);
            });
        </script>
        <?php
    }
}
add_action( 'save_post_product', 'action_save_product_data', 20);
function action_save_product_data( $post_id ) {

    $mlm_amount_value_hwe =serialize($_POST['mlm_amount_value']);
    $mlm_amount_type_hwe= serialize($_POST['mlm_amount_type']);

    update_post_meta($post_id, 'mlm_amount_value_hwe', $mlm_amount_value_hwe);
    update_post_meta($post_id, 'mlm_amount_type_hwe', $mlm_amount_type_hwe);
}

////////////add ditribution amount for three wallet submenu//////////////
add_action( 'mycred_add_menu', 'mycredpro_add_sub_menu_page' );
function mycredpro_add_sub_menu_page( $mycred ) {

	add_submenu_page(
		MYCRED_SLUG,
		'Set Distribution Wallet',
		'Set Distribution Wallet',
		$mycred->edit_plugin_cap(),
		'custom_distribution_set_for_wallet',
		'custom_distribution_set_for_each_wallet'
	);

}


function custom_distribution_set_for_each_wallet()
{
    ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
        <title>Wallet Money Distribution</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        </head>
        <style>
            .label_commission
            {
                text-align:left !important;
            }
        </style>
        <body>

        <?php
        global $wpdb;

                if(isset($_POST['save_distribution_amount_wallets']))
                {
                    $withdrawal_wallet_distribution = $_POST['withdrawal_wallet_distribution'];
                    $shopping_wallet_distribution = $_POST['shopping_wallet_distribution'];
                    $charity_wallet_distribution = $_POST['charity_wallet_distribution'];

                    update_option('withdrawal_wallet_distribution',$withdrawal_wallet_distribution);
                    update_option('shopping_wallet_distribution',$shopping_wallet_distribution);
                    update_option('charity_wallet_distribution',$charity_wallet_distribution);
                }

                $withdrawal_wallet_distribution_hwe=0;
                $shopping_wallet_distribution_hwe=0;
                $charity_wallet_distribution_hwe=0;

                if(!empty(get_option('withdrawal_wallet_distribution')))
                {
                    $withdrawal_wallet_distribution_hwe = get_option('withdrawal_wallet_distribution');
                }
                if(!empty(get_option('shopping_wallet_distribution')))
                {
                    $shopping_wallet_distribution_hwe = get_option('shopping_wallet_distribution');
                }
                if(!empty(get_option('charity_wallet_distribution')))
                {
                    $charity_wallet_distribution_hwe = get_option('charity_wallet_distribution');
                }
                

        ?>

        <div class="container">
        <h2>Distribution Percentage Set</h2>
        <form class="form-horizontal" method="post">
            <div class="form-group">
            <label class="control-label col-sm-2 label_commission" for="email">Withdrawal Wallet:</label>
            <div class="col-sm-4">
                <input type="number" class="form-control" id="email" placeholder="Enter Number" name="withdrawal_wallet_distribution" value="<?php echo $withdrawal_wallet_distribution_hwe; ?>">
            </div>
            </div>
            <div class="form-group">
            <label class="control-label col-sm-2 label_commission" for="pwd">Shopping Wallet:</label>
            <div class="col-sm-4">          
                <input type="number" class="form-control" id="email" placeholder="Enter Number" name="shopping_wallet_distribution" value="<?php echo $shopping_wallet_distribution_hwe; ?>">
            </div>
            </div>
            <div class="form-group">
            <label class="control-label col-sm-2 label_commission" for="email">Charity Wallet:</label>
            <div class="col-sm-4">
                <input type="number" class="form-control" id="email" placeholder="Enter Number" name="charity_wallet_distribution" value="<?php echo $charity_wallet_distribution_hwe; ?>">
            </div>
            </div>
           
            <div class="form-group">        
            <div class="col-sm-offset-2 col-sm-10">
                <button name="save_distribution_amount_wallets" type="submit" class="btn btn-primary">Submit</button>
            </div>
            </div>
        </form>
        </div>

        </body>
        </html>

    <?php
}
add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) {
    
    global $wpdb;

    $user_id = $user->data->ID;
    $results = $wpdb->get_results("SELECT * from ".$wpdb->prefix."uap_affiliates where uid='$user_id'");
    foreach($results as $results_hwe)
    {
        $affiliate_id  =$results_hwe->id;
    }

       $data = custom_payment_show($affiliate_id);

    //    print_r($data);

    //    die("fddfg");
    // $total_amount_wallet = $data['paid_payments_value'] + $data['unpaid_payments_value'];


    $winthdraw_money_distribution_per = get_option('withdrawal_wallet_distribution');
    $shopping_wallet_distribution_per = get_option('shopping_wallet_distribution');
    $charity_wallet_distribution_per = get_option('charity_wallet_distribution');

    if(empty($charity_wallet_distribution_per))
    {
        $charity_wallet_distribution_per =0;
    }

    //$total_amount_wallet = get_user_meta( $user_id, 'mycred_default_total',);

    $user_shopping_wallet = get_user_meta( $user_id, 'user_shopping_wallet' );
    $withrwal_money = get_user_meta( $user_id, 'mycred_default' );
    $charity_money = get_user_meta( $user_id, 'user_charity_wallet' );

    $total_amount_wallet = abs($user_shopping_wallet[0]) + abs($withrwal_money[0]) + abs($charity_money[0]);

    ?>
 
    <h3><?php _e("User Wallets", "blank"); ?></h3>

    <table class="form-table">
    <tr>
        <th><label for="address"><?php _e("Total Amount"); ?></label></th>
        <td>
            <span style="font-size:20px;font-weight:bold;">MYR<?php echo round($total_amount_wallet[0], 2); ?></span>
           
        </td>
    </tr>
    <tr>
        <th><label for="address"><?php _e("Withdrawal Wallet"); ?></label></th>
        <td>
        <span style="font-size:16px;font-weight:bold;">MYR<?php echo round($withrwal_money[0], 2); ?></span><br />
           
        </td>
    </tr>
    <tr>
        <th><label for="city"><?php _e("Shopping Wallet"); ?></label></th>
        <td>
            <span style="font-size:16px;font-weight:bold;">MYR<?php echo round($user_shopping_wallet[0], 2); ?></span><br />
            
        </td>
    </tr>
    <tr>
    <th><label for="postalcode"><?php _e("Charity Wallet"); ?></label></th>
        <td>
            <span style="font-size:16px;font-weight:bold;">MYR<?php echo round($charity_money[0], 2); ?></span><br />
        </td>
    </tr>
    <th><label for="transfer-money"><?php _e("Transfer money from withdrawal wallet to charity wallet"); ?></label></th>
        <td>
            <input type="number" name="transfer_money" id="" value="">
        </td>
    </tr>
    </table>
<?php }

add_action( 'edit_user_profile_update', 'update_user_profileFunc' );
function update_user_profileFunc( $userId ) {
    // $withdrawalMoneyBalance = get_user_meta( $userId, 'mycred_default_total' );
    // $transferedMoney = $_REQUEST['transfer_money'];
    // $remainingWithdrawalWalletMoney = $withdrawalMoneyBalance[0] - $transferedMoney;
    // update_user_meta( $userId, 'mycred_default', $remainingWithdrawalWalletMoney );
    // update_user_meta( $userId, 'mycred_default_total', $remainingWithdrawalWalletMoney );
    // update_user_meta( $userId, 'user_shopping_wallet', $transferedMoney );
}

function custom_payment_show($affiliate_id=0,$exclude_sources_from_referrals=''){

    /*

     * @param int

     * @return array

     */


    global $wpdb;



    $table = $wpdb->prefix . 'uap_affiliates';

    $table_b = $wpdb->base_prefix . 'users';

    $query = "SELECT COUNT(a.id) as c FROM $table as a INNER JOIN $table_b as b ON a.uid=b.ID;";

    $temp_data = $wpdb->get_row( $query );

    $array['affiliates'] = (empty($temp_data->c)) ? 0 : $temp_data->c;



    $table = $wpdb->prefix . 'uap_payments';

    $q = "SELECT COUNT(id) as c FROM $table";

    if ($affiliate_id){

        $q .= $wpdb->prepare( " WHERE affiliate_id=%d ", $affiliate_id );

    }

    $temp_data = $wpdb->get_row($q);

    $array['payments'] = (empty($temp_data->c)) ? 0 : $temp_data->c;



    $q = "SELECT SUM(amount) as a FROM $table";

    if ($affiliate_id){

        $affiliate_id = esc_sql($affiliate_id);

        $q .= $wpdb->prepare(" WHERE affiliate_id=%d ", $affiliate_id );

    }

    $temp_data = $wpdb->get_row($q);

    $array['paid_payments_value'] = (empty($temp_data->a)) ? 0 : $temp_data->a;



    $table = $wpdb->prefix . 'uap_referrals';

    $q = "SELECT SUM(amount) as a FROM $table WHERE payment='0' AND status='2'";

    if ($affiliate_id){

        $affiliate_id = esc_sql($affiliate_id);

        $q .= $wpdb->prepare(" AND affiliate_id=%d ", $affiliate_id );

    }

    if (!empty($exclude_sources_from_referrals)){

        $q .= " AND source NOT IN ('$exclude_sources_from_referrals') ";

    }

    $temp_data = $wpdb->get_row($q);

    $array['unpaid_payments_value'] = (empty($temp_data->a)) ? 0 : $temp_data->a;



    $table = $wpdb->prefix . 'uap_referrals';

    $q = "SELECT COUNT(id) as c FROM $table WHERE payment='0' AND status='2'";

    if ($affiliate_id){

        $affiliate_id = esc_sql($affiliate_id);

        $q .= $wpdb->prepare( " AND affiliate_id=%d ", $affiliate_id );

    }

    if (!empty($exclude_sources_from_referrals)){

        $q .= " AND source NOT IN ('$exclude_sources_from_referrals') ";

    }

    $temp_data = $wpdb->get_row($q);

    $array['unpaid_referrals_count'] = (empty($temp_data->c)) ? 0 : $temp_data->c;



    $q = "SELECT COUNT(id) as c FROM $table WHERE payment='2' AND status='2'";

    if ($affiliate_id){

        $affiliate_id = esc_sql($affiliate_id);

        $q .= $wpdb->prepare(" AND affiliate_id=%d ", $affiliate_id );

    }

    if (!empty($exclude_sources_from_referrals)){

        $q .= " AND source NOT IN ('$exclude_sources_from_referrals') ";

    }

    $temp_data = $wpdb->get_row($q);

    $array['paid_referrals_count'] = (empty($temp_data->c)) ? 0 : $temp_data->c;



    $table = $wpdb->prefix . 'uap_referrals';

    $q = "SELECT COUNT(id) as c FROM $table WHERE 1=1";

    if ($affiliate_id){

        $affiliate_id = esc_sql($affiliate_id);

        $q .= $wpdb->prepare(" AND affiliate_id=%d ", $affiliate_id );

    }

    if (!empty($exclude_sources_from_referrals)){

        $q .= " AND source NOT IN ('$exclude_sources_from_referrals') ";

    }

    $temp_data = $wpdb->get_row($q);

    $array['referrals'] = (empty($temp_data->c)) ? 0 : $temp_data->c;





    $temp_table = $wpdb->prefix . 'uap_visits';

    $q = $wpdb->prepare( "SELECT COUNT(id) as c, COUNT(IF(referral_id != 0,1,null)) d FROM $temp_table WHERE affiliate_id=%d ", $affiliate_id );

    $temp_data = $wpdb->get_row($q);

    $array['visits'] = (isset($temp_data->c)) ? $temp_data->c : 0;

    $array['converted'] = (isset($temp_data->d)) ? $temp_data->d : 0;


    // print_r($array);

    // die("dghdfg");
    return $array;

}
////////////add ditribution amount for three wallet submenu//////////////
function filter_woocommerce_cart_totals_coupon_label( $label, $coupon ) {

   
    $coupon_class='';
    $coupon_class= str_replace('Coupon: ','',$label);
    if($coupon_class != '')
    {

    ?>
        <script>
                jQuery(document).ready(function(){
                var coupon_class = '<?php echo $coupon_class; ?>';
                jQuery(".coupon-"+coupon_class+" th").html("");
                jQuery(".coupon-"+coupon_class+" th").html("Wallet Payment");
                
                });
        </script>
    <?php
   }
    
    return $label;
}
add_filter( 'woocommerce_cart_totals_coupon_label', 'filter_woocommerce_cart_totals_coupon_label', 10, 2 );


///add credit levels for upline in variation data //////////////////
function action_woocommerce_save_product_variation_mlm_credit( $variation_id, $i ) {


    $mlm_amount_variation_value = $_POST['mlm_amount_variation_value'];
    $mlm_amount_variation_id_array = $mlm_amount_variation_value[$variation_id];
    
    $mlm_amount_variation_type = $_POST['mlm_amount_variation_type'];
    $mlm_amount_variation_type_id_array = $mlm_amount_variation_type[$variation_id];
    
    update_post_meta($variation_id,'mlm_amount_variation_value',serialize($mlm_amount_variation_id_array));
    update_post_meta($variation_id,'mlm_amount_variation_type',serialize($mlm_amount_variation_type_id_array));
     
     }
     add_action( 'woocommerce_save_product_variation', 'action_woocommerce_save_product_variation_mlm_credit', 10, 2 );
    ////////////////////end add credit levels for upline in variation data//////////////

// display the extra data in the order admin panel
// Adding Meta container admin shop_order pages
add_action( 'add_meta_boxes', 'custom_refferal_upline_show' );
if ( ! function_exists( 'custom_refferal_upline_show' ) )
{
    function custom_refferal_upline_show()
    {
        add_meta_box( 'mv_other_fields', __('Upline Refferal Details','woocommerce'), 'custom_refferal_upline_show_hwe', 'shop_order', 'side', 'core' );
    }
}

// Adding Meta field in the meta container admin shop_order pages
if ( ! function_exists( 'custom_refferal_upline_show_hwe' ) )
{
    function custom_refferal_upline_show_hwe()
    {
        global $post,$wpdb;

        ?>
        <div id="uap_woo_referral_details" class="">
     
            <div class="handle-actions hide-if-no-js">
                
            <?php

                $select_afflate = $wpdb->get_results("SELECT * from ".$wpdb->prefix."uap_referrals where reference='$post->ID'",ARRAY_A);
                if(count($select_afflate) > 0)
                {
                    foreach($select_afflate as $select_afflate_hwe)
                    {
                        $id = $select_afflate_hwe['id'];
                        $select_afflate_parent = $wpdb->get_results("SELECT * from ".$wpdb->prefix."uap_referrals where reference='mlm_".$id."'",ARRAY_A);
                        
                        if(count($select_afflate_parent) > 0)
                        {
                            $key=1;
                            foreach($select_afflate_parent as $select_afflate_parent_hwe)
                            {
                                $affiliate_id = $select_afflate_parent_hwe['affiliate_id'];
                                $amount = $select_afflate_parent_hwe['amount'];
                                $currency = $select_afflate_parent_hwe['currency'];


                                $select_uid = $wpdb->get_results("SELECT * from ".$wpdb->prefix."uap_affiliates where id='".$affiliate_id."'",ARRAY_A);
                                if(count($select_uid) > 0)
                                {
                                    foreach($select_uid as $select_uid_hwe)
                                    {
                                        $user_id = $select_uid_hwe['uid'];
                                        $rank_id = $select_uid_hwe['rank_id'];

                                        $user = get_userdata($user_id);
                                        $username = get_user_meta($user_id,'first_name',true);
                                        $user_name_link = '<a href="'.get_site_url().'/wp-admin/admin.php?page=ultimate_affiliates_pro&tab=user_profile&affiliate_id='.$affiliate_id.'" target="_blank">'.$username.'</a>';

                                        $select_rank = $wpdb->get_results("SELECT * from ".$wpdb->prefix."uap_ranks where id='".$rank_id."'",ARRAY_A);
                                        if(count($select_rank) > 0)
                                        {
                                            foreach($select_rank as $select_rank_hwe)
                                            {
                                                $rank_name =  $select_rank_hwe['label'];
                                            }
                                        }
                                        
                                    }
                                }

                            ?>
                                <div><b><?php echo $key; ?> level upline: <?php echo $rank_name; ?> | <?php echo $user_name_link; ?> | <?php echo $currency.$amount; ?> </b></div>
                            <?php
                              $key++;
                            }
                        }
                    }
                   
                }
            ?>
                    
                
            </div>
        </div>
        <?php

    }
}
function custom_code_for_admin_monu(){

    if(is_admin())
    {
    ?>
         <style>
        tr.user-url-wrap{ 
            display: none; 
           
        }
        tr.user-last-name-wrap{
            display:none;
        }
        .form-field:nth-child(2){
             display: none;
        }
        .form-field:nth-child(6){
            display:none;
        }
        .form-field:nth-child(5){
            display:none;
        }
        </style>
        <script>
            jQuery(document).ready(function(){
                jQuery(".user-user-login-wrap:nth-child(1) th label").text("Mobile Number");
                jQuery(".form-field.form-required:nth-child(1) th label").text("Mobile Number");

                jQuery("#createuser .form-table tbody tr:nth-child(3) th label").html("");
                jQuery("#createuser .form-table tbody tr:nth-child(3) th label").html("Name");
                // jQuery(".form-field.form-required:nth-child(1) th label").text("Mobile Number");

            })
        </script>
       <?php

        }
   
    } 
add_action('admin_head','custom_code_for_admin_monu');

// add_action( 'user_register', 'afilet', 10, 1 );

// function afilet( $user_id ) {
//     global $wpdb;

//     if(is_admin())
//     {
//         $date = date("Y-m-d H:i:s");
//         $affiliate = "INSERT INTO hfp_uap_affiliates SET `uid`='".$user_id."',rank='1',date='$date',status='1'";
        

//         if($wpdb->query($affiliate))
//         {
//             $lastid = $wpdb->insert_id;
//             $refid= $_POST['digits_reg_referralid'];

//             $refid = $refid - 60000;
//             $sql_data = "INSERT INTO hfp_uap_mlm_relations
//                 (`affiliate_id`,`parent_affiliate_id`) 
//                 values ($lastid, $refid)";
    
//             $wpdb->query($sql_data);
//         }


//     }
   
// }
add_action("wp_head","custom_afilate_import");
function custom_afilate_import()
{
    // $file = fopen("import_affiliates.csv","r");
    // $key=0;
    // $headerLine = true;
   // global $wpdb;
    // while(($affiliate_data =fgetcsv($file, 1000, ",")) !== FALSE)
    // {

       
    //     if($headerLine) { $headerLine = false; }
    //     else {
    //         $affiliate_id = $affiliate_data[0];
    //         $user_login = $affiliate_data[1];
    //         $name = $affiliate_data[2];
    //         $email = $affiliate_data[3];
    //         $rank = $affiliate_data[4];
    //         $visits = $affiliate_data[5];
    //         $refferals = $affiliate_data[6];
    //         $paid_amount = $affiliate_data[7];
    //         $unpaid_amount = $affiliate_data[8];
    //         $wp_role = $affiliate_data[9];
    //         $affiliate_since = $affiliate_data[10];
    //         $upline = $affiliate_data[11];

    //         if($rank == 'Member')
    //         {
    //             $rank_id = 1;
    //         }
    //         elseif($rank == 'VIP')
    //         {
    //             $rank_id =2;
    //         }
    //         elseif($rank == 'VVIP')
    //         {
    //             $rank_id =3;
    //         }
    //         elseif($rank == 'SVIP')
    //         {
    //             $rank_id =4;
    //         }
    //         elseif($rank == 'Coach')
    //         {
    //             $rank_id =5;
    //         }

    //         $user_password = "Testuserhwe@1".$key.rand(10,1000);

    //         preg_match_all('!\d+!', $upline, $upline_id);
        
    //         $meta = array(
    //             'first_name' => $name,
    //             'user_login' => $user_login,
    //             'affiliate_id' => (int)$affiliate_id,
    //             "affiliate_upline_id" =>(int)$upline_id[0][0]
    //         );
    //     // $userdata = array ('user_login' => $user_login, 'user_pass' => $user_password);
    //     $user_id = wp_insert_user( array(
    //         'user_login' => $user_login,
    //         'user_pass' => $user_password,
    //         'user_email' => '',
    //         'first_name' => $name,
    //         'display_name' => $name,
    //         'role' => 'customer'
    //       ));
    //     foreach( $meta as $key => $val ) {
    //         update_user_meta( $user_id, $key, $val ); 
    //     }

    //     $change_affilate_rank = "UPDATE ".$wpdb->prefix."uap_affiliates SET rank_id='$rank_id' where uid='$user_id'";
    //     $wpdb->query($change_affilate_rank);
    //     print_r($user_id);

    //     die("dfgdfg");

    //     }

    //     $key++;
    // }
    // fclose($file);


    // $files = fopen("import_affiliates.csv","r");
    // $key1=0;
    // $headerLine1 = true;
    // while(($affiliate_data_hwe =fgetcsv($files, 1000, ",")) !== FALSE)
    // {

       
    //     if($headerLine1) { $headerLine1 = false; }
    //     else {
    //         $affiliate_id = $affiliate_data_hwe[0];

    //         $get_user_id_by_affiliate_id = "SELECT * from ".$wpdb->prefix."usermeta where meta_key='affiliate_id' && meta_value='$affiliate_id'";
    //         $results = $wpdb->get_results($get_user_id_by_affiliate_id,ARRAY_A);
    //         foreach($results as $results_hwe)
    //         {
    //             $user_id = $results_hwe['user_id'];
    //         }

    //         $upline_id = $affiliate_data_hwe[11];
    //         preg_match_all('!\d+!', $upline_id, $upline_idhwe);
    //         $upline_affiliate_id =(int)$upline_idhwe[0][0];

    //         $get_upline_user_id_by_affiliate_id = "SELECT * from ".$wpdb->prefix."usermeta where meta_key='affiliate_id' && meta_value='$upline_affiliate_id'";
    //         $results1 = $wpdb->get_results($get_upline_user_id_by_affiliate_id,ARRAY_A);
    //         if(count($results1) > 0)
    //         {
    //             foreach($results1 as $results_hwe_hwe)
    //             {
    //                 $upline_user_id = $results_hwe_hwe['user_id'];
    //             }
    //         }
           
    //         $timestamp = strtotime($affiliate_data_hwe[10]);
    //         $date = date("Y-m-d H:i:s",$timestamp);

    //         if($user_id != '' && $upline_user_id != '')
    //         {
    //             $select_upline = "SELECT * from ".$wpdb->prefix."uap_affiliate_referral_users_relations where affiliate_id='$user_id' && referral_wp_uid='$upline_user_id'";
    //             $get_upline_records = $wpdb->get_results($select_upline,ARRAY_A);
    //             if(count($get_upline_records) <= 0)
    //             {
    //                 $make_upline_refferal  = "INSERT INTO ".$wpdb->prefix."uap_affiliate_referral_users_relations SET affiliate_id='$user_id',referral_wp_uid='$upline_user_id',DATE='$date'";
    //                 $wpdb->query($make_upline_refferal);
    //             }
               
    //         }
           
      
    //         $get_affiliiate_id = get_user_meta($affiliate_id,"");

    //         $user_login = $affiliate_data[1];
    //         $name = $affiliate_data[2];
    //         $email = $affiliate_data[3];
    //         $rank = $affiliate_data[4];
    //         $visits = $affiliate_data[5];
    //         $refferals = $affiliate_data[6];
    //         $paid_amount = $affiliate_data[7];
    //         $unpaid_amount = $affiliate_data[8];
    //         $wp_role = $affiliate_data[9];
    //         $affiliate_since = $affiliate_data[10];
    //         $upline = $affiliate_data[11];

          
    //     // $userdata = array ('user_login' => $user_login, 'user_pass' => $user_password);
    //     $user_id = wp_insert_user( array(
    //         'user_login' => $user_login,
    //         'user_pass' => $user_password,
    //         'user_email' => '',
    //         'first_name' => $name,
    //         'display_name' => $name,
    //         'role' => 'customer'
    //       ));
    //     foreach( $meta as $key => $val ) {
    //         update_user_meta( $user_id, $key, $val ); 
    //     }

    //     $change_affilate_rank = "UPDATE ".$wpdb->prefix."uap_affiliates SET rank_id='$rank_id' where uid='$user_id'";
    //     $wpdb->query($change_affilate_rank);
    //     print_r($user_id);

    //     die("dfgdfg");

    //     }

    //     $key1++;
    // }
    // fclose($files);

}

function create_custom_menu_affiliate_import(){
	add_menu_page( 
		__( 'Affiliate Import', 'Affiliate' ),
		'Affiliate Import',
		'manage_options',
		'affiliate_import',
		'affiliate_import_function',
		"",
		6
	); 
}
add_action( 'admin_menu', 'create_custom_menu_affiliate_import' );


function affiliate_import_function(){

    if(isset($_POST['submitdfhdfgd'])) 
    {
        
       //  $handle = fopen($_FILES['csvfile']['tmp_name'], "r");
    
       // $file = fopen(dirname(dirname(dirname(dirname(__FILE__))))."/import_affiliates.csv","r");
        $file = fopen($_FILES['csvfile']['tmp_name'],"r");
        $key=0;
        $headerLine = true;
        global $wpdb;
        while(($affiliate_data =fgetcsv($file, 1000, ",")) !== FALSE)
        {

            if($headerLine) { $headerLine = false; }
            else {
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

                   
                    $meta = array(
                        'first_name' => $name,
                        'user_login' => $user_login,
                        'affiliate_id' => (int)$affiliate_id,
                        "affiliate_upline_id" =>(int)$upline_idhwe1
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
                            foreach( $meta as $key => $val ) {
                                update_user_meta( $user_id, $key, $val ); 
                            }
                
                            // $selectgsd = "SELECT * from ".$wpdb->prefix."uap_affiliates where uid='$user_id'";
                            // $affiliate_result = $wpdb->get_results($selectgsd,ARRAY_A);
                            // if(count($affiliate_result) > 0)
                            // {
                            //      $change_affilate_rank = "UPDATE ".$wpdb->prefix."uap_affiliates SET rank_id='$rank_id' where uid='$user_id'";
                            //     $wpdb->query($change_affilate_rank);
                            // }
                      
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
                
                }
                
                $key1++;
            }
            
        }

    fclose($file);


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

<?php
}