<?php

/*

 * Main Class for deal with referrals.

 * Extend this to each service.(Woo, UMP, etc.)

 */



if (!class_exists('Referral_Main')):



class Referral_Main{

	protected static $user_id;

	protected static $affiliate_id;

	protected static $source;

	protected static $campaign;

	protected static $visit_id = 0;

	protected static $currency;

	protected static $special_payment_type = '';

	protected static $coupon_code = '';



	public function __construct($user_id=0, $affiliate_id=0){

		/*

		 * @param int, int

		 * @return non

		 */

		if ($user_id){

			self::$user_id = $user_id;

		}

		if ($affiliate_id){

			self::$affiliate_id = $affiliate_id;

		}

		self::$affiliate_id = apply_filters( 'uap_filter_affiliate_id', self::$affiliate_id );



		self::$source = '';

		self::$campaign = '';

		self::$currency = get_option('uap_currency');

		if (!self::$currency){

			self::$currency = 'USD';

		}



		/// SIGN UP REFERRALS

		add_action( 'user_register', array($this, 'insert_signup_referral'), 99, 1 );



		/// PPC

		add_action('uap_insert_into_cookie_new_affiliate', [$this, 'ppc'], 1, 1);

	}



	protected function set_affiliate_id(){

		/*

		 * @param none

		 * @return none

		 */



		global $indeed_db,$wpdb;

		$lifetime = get_option('uap_lifetime_commissions_enable');

		self::$affiliate_id = apply_filters( 'uap_set_affiliate_id_filter', self::$affiliate_id );


		if (empty(self::$affiliate_id) && empty($_COOKIE['uap_referral'])){ /// SEARCH INTO DB

			
			
				$self_user_ids = self::$user_id;
				$affiliate_id = $indeed_db->save_affiliate($self_user_ids);


					if (!empty($affiliate_id)){

						/// assign default rank

						$settings = $indeed_db->return_settings_from_wp_option('register');

						if (!empty($settings['uap_register_new_user_rank'])){

							$indeed_db->update_affiliate_rank_by_uid($self_user_ids, $settings['uap_register_new_user_rank']);

						}

						if(isset($_POST['uap_affiliate_mlm_parent']))
						{
							$refid= $_POST['uap_affiliate_mlm_parent'];
							$refid = $refid - 60000;
							$sql_data = "INSERT INTO ".$wpdb->prefix."uap_mlm_relations
								(`affiliate_id`,`parent_affiliate_id`) 
								values ($affiliate_id, $refid)";
							$wpdb->query($sql_data);
						}
						elseif(get_user_meta($self_user_ids,'referral_id',true) != '')
						{
						
							$refid= get_user_meta($self_user_ids,'referral_id',true);
							$refid = $refid - 60000;
							$sql_data = "INSERT INTO ".$wpdb->prefix."uap_mlm_relations
								(`affiliate_id`,`parent_affiliate_id`) 
								values ($affiliate_id, $refid)";
							$wpdb->query($sql_data);
						}


							$date =date("y-m-d H:i:s");
							$sql_data1 = "INSERT INTO ".$wpdb->prefix."uap_affiliate_referral_users_relations
								(`affiliate_id`,`referral_wp_uid`,`DATE`) 
								values ($affiliate_id, $self_user_ids,'".$date."')";

							$wpdb->query($sql_data1);
						}


						
						$indeed_db->set_mlm_relation_on_new_affiliate($affiliate_id);
				
		
				if ($lifetime){

					/// LIFETIME

					self::$affiliate_id = $indeed_db->search_affiliate_id_for_current_user(self::$user_id);

				

					if (self::$affiliate_id){

						self::$special_payment_type = 'lifetime';

					}

				

				} else if (self::$special_payment_type=='reccuring'){

					/// RECCURING

					self::$affiliate_id = $indeed_db->search_affiliate_id_for_current_user(self::$user_id);
					
				}

			


		} else if (empty(self::$affiliate_id) && !empty($_COOKIE['uap_referral'])){ /// SEARCH INTO COOKIE

			/// get affiliate id from cookie

		

			$cookie_data = unserialize(stripslashes($_COOKIE['uap_referral']));

	
			// print_r($cookie_data);
			// die("mohit");

			if (!empty($cookie_data['affiliate_id'])){

				if ( get_option( 'uap_default_ref_format' ) == 'username' ){

						$temporaryAffiliateId = $indeed_db->get_affiliate_id_by_username( $cookie_data['affiliate_id'] );

				}

				if ( empty( $temporaryAffiliateId ) ){

						self::$affiliate_id = $cookie_data['affiliate_id'];

						

				}



				self::$campaign = (empty($cookie_data['campaign'])) ? '' : $cookie_data['campaign'];

				self::$visit_id = (empty($cookie_data['visit_id'])) ? 0 : $cookie_data['visit_id'];

			}

		}

		if (self::$affiliate_id){

		

			$old_affiliate = $indeed_db->search_affiliate_id_for_current_user(self::$user_id);
			
			if ($old_affiliate){

				$rewrite_referrals = get_option('uap_rewrite_referrals_enable');

				if ($rewrite_referrals){

					/// update user - affiliate relation, use new affiliate

					$indeed_db->update_affiliate_referral_user_relation_by_ids($old_affiliate, self::$affiliate_id, self::$user_id);

				} else {

					/// use old affiliate

					$lifetime = get_option('uap_lifetime_commissions_enable');

					if ( $lifetime ){

							self::$affiliate_id = $old_affiliate;
					
					}



				}

			} else {

				/// insert user - affiliate relation


				$indeed_db->insert_affiliate_referral_user_new_relation(self::$affiliate_id, self::$user_id);

			}
			
		}


	}



	protected function valid_referral(){

		/*

		 * @param none

		 * @return boolean

		 */

		global $indeed_db;

		/// CHECK FOR OWN REFERRENCE

		$isValid = apply_filters( 'uap_filter_before_valid_referral', true, self::$affiliate_id, self::$user_id );

		if ( !$isValid ){

				return false;

		}



		if (self::$affiliate_id && self::$user_id && $indeed_db->affiliate_get_id_by_uid(self::$user_id)==self::$affiliate_id){

			$allowOwnRefference = true;

			if (!get_option('uap_allow_own_referrence_enable')){

					$allowOwnRefference = false;//own referrence not allowed

			}

			$allowOwnRefference = apply_filters( 'uap_allow_own_referrence_filter', $allowOwnRefference );

			if ( !$allowOwnRefference ){

					return false;

			}

		}

		if (self::$affiliate_id && $indeed_db->is_affiliate_active(self::$affiliate_id)){

				return TRUE;

		}



		return FALSE;

	}



	public function save_referral_unverified($args=array(),$order_id=0){ // protected

	
		/*

		 * UNVERIFIED STATUS

		 * @param array

		 * @return boolean

		 */

		global $indeed_db,$wpdb;

		$keys = array(

						'refferal_wp_uid',

						'campaign',

						'affiliate_id',

						'visit_id',

						'description',

						'source',

						'reference',

						'reference_details',

						'amount',

						'currency',

		);

		//mohit
		if(!is_admin() || (is_admin() && isset($_REQUEST['page']) && $_REQUEST['page'] == 'affiliate_import'))
		{
			$get_current_login_id = get_current_user_id();

			$get_affilate_data = $wpdb->get_results("SELECT * from ".$wpdb->prefix."uap_affiliates where uid='$get_current_login_id'",ARRAY_A);
	
			if(count($get_affilate_data) > 0)
			{
				foreach($get_affilate_data as $get_affilate_data_hwe)
				{
					$affilate_id_hwe = $get_affilate_data_hwe['id'];
				}
	
				$args['affiliate_id'] = $affilate_id_hwe;
	
	
			}
		}
	

		$args = apply_filters( 'uap_public_filter_insert_referral_args', $args );



		foreach ($keys as $key){

			if (!isset($args[$key])){

				return FALSE;

			}

		}



		/// NEGATIVE REFERRALS?

		if ($args['amount']<0){

			$args['amount'] = 0;

		}



		/// EMPTY REFERRALS

		

		$general_settings_data = $indeed_db->return_settings_from_wp_option('general-settings');

		if (empty($general_settings_data['uap_empty_referrals_enable'])){

			///don't insert referrals with 0$

			$min = 0.01;

			if ($args['amount']<$min){

				return;

			}

		}
	
		/// EMPTY REFERRALS



		$args['date'] = current_time( 'Y-m-d H:i:s' );//date('Y-m-d H:i:s', time());

		$args['status'] = 1;//unverified

		$args['payment'] = 0;//unpaid

		$args['parent_referral_id'] = '';// empty for moment, will be updated if it's case

		$args['child_referral_id'] = '';//always will be empty

		$referral_id = $indeed_db->save_referral($args);




		if ($referral_id){

			$indeed_db->update_visit_referral_id($args['visit_id'], $referral_id);

			if (get_option('uap_mlm_enable')){

				$limit = get_option('uap_mlm_matrix_depth');

				$first_child_username = $indeed_db->get_wp_username_by_affiliate_id($args['affiliate_id']);



				$theAmount = $args['amount'];

				$uap_mlm_use_amount_from = get_option('uap_mlm_use_amount_from');

				if ($uap_mlm_use_amount_from && $uap_mlm_use_amount_from=='product_price' && isset($args['product_price'])){

					$theAmount = $args['product_price'];

				}
				//mohit_hwe
				if(isset($args['product_price']))
				{
					$product_price_hwe = $args['product_price'];
				}
				else
				{
					$product_price_hwe = $args['amount'];
				}

				// print_r($args);

				// die("fgdfgdf");
				// if(strpos($args['reference_details'],',') !== false)
				// {
				// 	$explode = explode(',',$args['reference_details']); 
				// 	$args['reference_details'] = $explode[0];
				// }


				$this->mlm_do_save_referral_unverified($args['affiliate_id'], $referral_id, 1, $limit, $theAmount, $first_child_username,$referral_id,$args['reference_details'],$product_price_hwe,$order_id);
				
			
			}

		}

	

		return TRUE;

	}



	protected function referral_verified($reference='', $source='', $check_if_can_do=TRUE){

		/*

		 * VERIFIED STATUS

		 * @param string, string

		 * @return none

		 */

		if ($check_if_can_do){

			/// Don't change the Referral Status to Verified

			$dont = get_option('uap_workflow_referral_status_dont_automatically_change');

			if ($dont){

				return; /// stop from change status of referral

			}

		}

		global $indeed_db;

		$referral_id = $indeed_db->get_referral_id_for_reference($reference, $source);

		if ($referral_id){

			$indeed_db->change_referral_status($referral_id, 2);

		}

	}



	protected function referral_refuse($reference='', $source=''){

		/*

		 * REFUSE STATUS

		 * @param string, string

		 * @return none

		 */

		global $indeed_db;

		$referral_id = $indeed_db->get_referral_id_for_reference($reference, $source);

		if ($referral_id){

			$indeed_db->change_referral_status($referral_id, 0);

		}

	}



	protected function mlm_do_save_referral_unverified($child_affiliate_id=0, $child_referral_id=0, $count=1, $limit=0, $amount=0, $first_child_username='', $first_child_referrence='',$custom_get_product_id='',$product_price='',$order_id=0){


		/*

		 * @param int, int, int, int, int, string, string

		 * @return none

		 */

		/// CHECK LIMIT DEPTH
		global $wpdb;

	
		if ($limit<$count){

			return;

		}
	

	
		if ($child_affiliate_id && $child_referral_id){

			global $indeed_db;

			$parent_id = $indeed_db->mlm_get_parent($child_affiliate_id);

			$description = 'From MLM';

			if (!empty($first_child_username)){

				$description = 'From ' . $first_child_username;

			}
	
			$reference = '-';

			if (!empty($first_child_referrence)){

				$reference = 'mlm_' . $first_child_referrence;

			}

			// echo "parent_id : ".$child_affiliate_id;

			// die("terter");
			if ($parent_id){

				

				$args = array(

						'refferal_wp_uid' => '-',

						'campaign' => '-',

						'affiliate_id' => $parent_id,

						'visit_id' => '-',

						'description' => $description,

						'source' => 'mlm',

						'reference' => $reference,

						'reference_details' => '-',

						'parent_referral_id' => '',//will be updated if it;s case

						'child_referral_id' => $child_referral_id,

				);

				$args['date'] = current_time( 'Y-m-d H:i:s' );//date('Y-m-d H:i:s', time());

				$args['status'] = 1;//unverified

				$args['payment'] = 0;//unpaid

               //mohit_hwe


				/// SET AMOUNT

				//$args['amount'] = $indeed_db->mlm_get_amount($parent_id, $amount, $count);

				///custom mlm ration

				if($product_price != '')
				{
					$amount=$product_price;
				}

				if($order_id != 0)
				{
					
						//$custom_get_product_data = explode(',',$custom_get_product_id);
						$order = new WC_Order($order_id);

						self::$user_id = (int)$order->get_user_id();
		
						$items = $order->get_items();
		
						$shipping = $order->get_total_shipping();
		
						if ($shipping){
		
							$shipping_per_item = $shipping / count($items);
		
						} else {
		
							$shipping_per_item = 0;
		
						}
					
						
						$args['amount'] = 0;
						
						foreach ($items as $item)
						{
							/// foreach in lines

							

							$custom_get_product_ids = $item['product_id'];

							
							///base price

							$product_price = round($item['line_total'], 3);



							///add shipping if it's case

							if (!empty($shipping_per_item) && !$exclude_shipping){

								$product_price += round($shipping_per_item, 3);

							}



							/// add taxes if it's case

							if (!empty($item['line_tax']) && !$exclude_tax){

								$product_price += round($item['line_tax'], 3);

							}

							
							$amount =$product_price;

							$variation_id = $item->get_variation_id();

							$get_variation_amount = get_post_meta($variation_id,'mlm_amount_variation_value',true);
							$unseriliaze_custom_variation_amount = unserialize($get_variation_amount);

							
							$custom_single_product_amount_data = get_post_meta($custom_get_product_ids,'mlm_amount_value_hwe',true);
							$unseriliaze_custom_single_product_amount_data = unserialize($custom_single_product_amount_data);

							if($unseriliaze_custom_variation_amount[$count] != '' && $get_variation_amount !='')
							{
								

								$get_variation_type = get_post_meta($variation_id,'mlm_amount_variation_type',true);
								$unseriliaze_custom_variation_type_data = unserialize($get_variation_type);
								$custom_single_product_variation_amount_type = $unseriliaze_custom_variation_type_data[$count];

								
								if($custom_single_product_variation_amount_type == 'flat')
								{
									$args['amount'] = $args['amount'] + abs($unseriliaze_custom_variation_amount[$count]);
									
								}
								else
								{
									
									$args['amount'] = $args['amount'] + (($product_price * abs($unseriliaze_custom_variation_amount[$count])) / 100);
									
								}

								// echo "<br>";
								// echo "mohitA".$args['amount'];

							}
							elseif($unseriliaze_custom_single_product_amount_data[$count] != '' && $custom_single_product_amount_data != '')
							{
								

								$custom_single_product_amount_type_data = get_post_meta($custom_get_product_ids,'mlm_amount_type_hwe',true);
								$unseriliaze_custom_single_product_amount_type_data = unserialize($custom_single_product_amount_type_data);
								$custom_single_product_amount_type = $unseriliaze_custom_single_product_amount_type_data[$count];
							
								if($custom_single_product_amount_type == 'flat')
								{
									$args['amount'] = $args['amount'] + abs($unseriliaze_custom_single_product_amount_data[$count]);
								}
								else
								{
									$args['amount'] = $args['amount'] + (($product_price * abs($unseriliaze_custom_single_product_amount_data[$count])) / 100);
								}

								// echo "<br>";
								// echo "mohitB".$args['amount'];

							}
							else
							{
								
								$args['amount'] = $args['amount'] + $indeed_db->mlm_get_amount($parent_id, $amount, $count);
								
								// echo "<br>";
								// echo "mohitC".$amount;
							}

								
							//}
						
							
						}

						
				}

				
				// else
				// {
				// 	foreach ($items as $item)
				// 	{ 
				// 		/// foreach in lines

				// 		$custom_get_product_ids = $item['product_id'];

						
				// 		///base price

				// 		$product_price = round($item['line_total'], 3);



				// 		///add shipping if it's case

				// 		if (!empty($shipping_per_item) && !$exclude_shipping){

				// 			$product_price += round($shipping_per_item, 3);

				// 		}



				// 		/// add taxes if it's case

				// 		if (!empty($item['line_tax']) && !$exclude_tax){

				// 			$product_price += round($item['line_tax'], 3);

				// 		}

						
				// 		$amount =$product_price;

				// 		$variation_id = $item->get_variation_id();

						

						
				// 	}

				// 	$get_variation_amount = get_post_meta($variation_id,'mlm_amount_variation_value',true);
				// 	$unseriliaze_custom_variation_amount = unserialize($get_variation_amount);

				

				// 	$custom_single_product_amount_data = get_post_meta($custom_get_product_id,'mlm_amount_value_hwe',true);

				// 	if($unseriliaze_custom_variation_amount[$count] != '')
				// 	{
				// 		echo $unseriliaze_custom_variation_amount[$count];
				// 		die("mohitasdas");
				// 	}
				// 	elseif($custom_single_product_amount_data != '')
				// 	{
				// 		$unseriliaze_custom_single_product_amount_data = unserialize($custom_single_product_amount_data);

				// 		$custom_single_product_amount_type_data = get_post_meta($custom_get_product_id,'mlm_amount_type_hwe',true);
				// 		$unseriliaze_custom_single_product_amount_type_data = unserialize($custom_single_product_amount_type_data);

				// 		$custom_single_product_amount_type = $unseriliaze_custom_single_product_amount_type_data[$count];

				// 		if($custom_single_product_amount_type == 'flat')
				// 		{
				// 			if($unseriliaze_custom_single_product_amount_data[$count] != '')
				// 			{
							
				// 				$args['amount'] = $unseriliaze_custom_single_product_amount_data[$count];
				// 			}
				// 			else
				// 			{
							
				// 				$args['amount'] = $indeed_db->mlm_get_amount($parent_id, $amount, $count);
				// 			}
							
				// 		}
				// 		else
				// 		{
				// 			if($unseriliaze_custom_single_product_amount_data[$count] != '')
				// 			{
						
				// 				$args['amount'] = $unseriliaze_custom_single_product_amount_data[$count] * $product_price / 100;
				// 			}
				// 			else
				// 			{
							
				// 				$args['amount'] = $indeed_db->mlm_get_amount($parent_id, $amount, $count);
							
				// 			}
							
				// 		}

				
				// 	}
				// 	else
				// 	{
				// 			$args['amount'] = $indeed_db->mlm_get_amount($parent_id, $amount, $count);
				// 	}
				// }
	// if($count == 2)
	// {

	// 	print_r($args['amount']);
	// 	die("ghfghd");
	// }
								///end custom mlm ration

								//mohit
	
				
				if($parent_id)
				{
					$get_result_user_wallet = $wpdb->get_results("SELECT * from ".$wpdb->prefix."uap_affiliates where id='$parent_id'");
					if(count($get_result_user_wallet) > 0)
					{

						foreach($get_result_user_wallet as $get_result_user_wallet_hwe)
						{
							
							$user_parent_id = $get_result_user_wallet_hwe->uid;
						}
						// $get_userwallet = get_user_meta($user_parent_id,'mycred_default');
						// if(empty($get_userwallet[0]))
						// {
						// 	$get_userwallet[0]=0;
						// }

						$user_register_amount = $args['amount'];
						// $total_wallet_amount = $get_userwallet[0] + $user_register_amount;

						// $update_wallet_amount = update_user_meta($user_parent_id,'mycred_default',$total_wallet_amount);
					
						$get_userwallet = get_user_meta($user_parent_id,'mycred_default');
						if(empty($get_userwallet[0]))
						{
							$get_userwallet[0]=0;
						}
						$total_wallet_amount = $get_userwallet[0] + $user_register_amount;

						$get_user_shopping_wallet= get_user_meta($user_parent_id,'user_shopping_wallet');
						if(empty($get_user_shopping_wallet[0]))
						{
							$get_user_shopping_wallet[0]=0;
						}
						$total_shopping_wallet_money = $get_user_shopping_wallet[0] + $user_register_amount;

						$get_user_charity_wallet= get_user_meta($user_parent_id,'user_charity_wallet');
						if(empty($get_user_charity_wallet[0]))
						{
							$get_user_charity_wallet[0]=0;
						}
						$total_charity_wallet = $get_user_charity_wallet[0] + $user_register_amount;

						
						$winthdraw_money_distribution_per = get_option('withdrawal_wallet_distribution');
						$shopping_wallet_distribution_per = get_option('shopping_wallet_distribution');
						$charity_wallet_distribution_per = get_option('charity_wallet_distribution');

						$withrwal_money = $total_wallet_amount * $winthdraw_money_distribution_per /100;
						$shopping_money = $total_shopping_wallet_money * $shopping_wallet_distribution_per /100;
						$charity_money = $total_charity_wallet * $charity_wallet_distribution_per /100;

						

						update_user_meta($user_parent_id,'mycred_default',$withrwal_money);
						update_user_meta($user_parent_id,'user_shopping_wallet',$shopping_money);
						update_user_meta($user_parent_id,'user_charity_wallet',$charity_money);
					
					}
				
				}

				$args['currency'] = self::$currency;

			

				/// save referral

				$inserted_referral_id = $indeed_db->save_referral($args);



				//update the child referral

				$indeed_db->referral_update_child($child_referral_id, $inserted_referral_id);


				/// search for parent

				$count++;

				$this->mlm_do_save_referral_unverified($parent_id, $inserted_referral_id, $count, $limit, $amount, $first_child_username, $first_child_referrence,$custom_get_product_id,$product_price,$order_id);

			}

		}

	}


	public function insert_signup_referral($user_id=0){

		/*

		 * @param int

		 * @return none

		 */

		 

		if (get_option('uap_sign_up_referrals_enable') && $user_id){

			

			self::$user_id = $user_id;

			if(get_option('uap_pay_to_become_affiliate_enabled') != '1')
			{

			$this->set_affiliate_id();

			}

			if ($this->valid_referral()){


				require_once UAP_PATH . 'public/Affiliate_Referral_Amount.class.php';

				$do_math = new Affiliate_Referral_Amount(self::$affiliate_id, '');

				$amount = $do_math->get_signup_amount();

				

				$args = array(

						'refferal_wp_uid' => self::$user_id,

						'campaign' => self::$campaign,

						'affiliate_id' => self::$affiliate_id,

						'visit_id' => self::$visit_id,

						'description' => 'User SignUp',

						'source' => 'User SignUp',

						'reference' => 'user_id_' . $user_id,

						'reference_details' => 'User SignUp',

						'amount' => $amount,

						'currency' => self::$currency,

				);

				$this->save_referral_unverified($args);
					
					

				$default_sts = get_option('uap_sign_up_default_referral_status');

				

				if ($default_sts==2){

					/// MAKE VERIFIED

					$this->referral_verified('user_id_' . $user_id, '', FALSE); 
					
					// print_r($this);
					// die('rrrrrr');

				}

			}

		}

	}


	public function pay_bonus($amount_value=0, $rank_name=''){

		/*

		 * @param double, string

		 * @return none

		 */

		global $indeed_db;

		$status = get_option('uap_bonus_on_rank_default_referral_sts');

		if ($status===FALSE){

			$status = 2; /// verified

		}

		$args = array(

				'refferal_wp_uid' => 0,

				'campaign' => '',

				'affiliate_id' => self::$affiliate_id,

				'visit_id' => '',

				'description' => esc_html__('Bonus for reaching rank: ', 'uap') . $rank_name,

				'source' => 'bonus',

				'reference' => 0,

				'reference_details' => 'Bonus',

				'amount' => $amount_value,

				'currency' => self::$currency,

				'date' => current_time( 'Y-m-d H:i:s' ), //date('Y-m-d H:i:s', time()),

				'status' => $status,

				'payment' => 0,

				'parent_referral_id' => '',

				'child_referral_id' => '',

		);

		$indeed_db->save_referral($args);

	}

	public function ppc($affiliateId=0)

	{

			global $indeed_db;

			if (empty($affiliateId)){

					return;

			}

			$isOn = get_option('uap_pay_per_click_enabled');

			if (empty($isOn)){

					return;

			}

			$referralStatus = get_option('uap_pay_per_click_default_referral_sts');



			self::$user_id = $indeed_db->get_uid_by_affiliate_id($affiliateId);

			self::$affiliate_id = $affiliateId;

			$affiliateRank = $indeed_db->get_affiliate_rank(self::$affiliate_id);

			$amountValue = $indeed_db->getPPCValueForRank($affiliateRank);



			$args = array(

					'refferal_wp_uid' => 0,

					'campaign' => '',

					'affiliate_id' => self::$affiliate_id,

					'visit_id' => '',

					'description' => 'ppc',

					'source' => 'ppc',

					'reference' => 0,

					'reference_details' => 'ppc',

					'amount' => $amountValue,

					'currency' => self::$currency,

					'date' => current_time( 'Y-m-d H:i:s' ),//date('Y-m-d H:i:s', time()),

					'status' => $referralStatus,

					'payment' => 0,

					'parent_referral_id' => '',

					'child_referral_id' => '',

			);

			$indeed_db->save_referral($args);

	}

}

endif;

