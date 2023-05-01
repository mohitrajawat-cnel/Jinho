<div class="uap-ap-wrap">


<?php if (!empty($data['title'])):?>

	<h3><?php echo $data['title'];?></h3>

<?php endif;?>

<?php

	$stats = 0;

	if(isset($data['stats']['wallet'])){

		$stats = $data['stats']['wallet'];

	}

?>





<?php
global $wpdb;
$user_id = get_current_user_id();

if(isset($_POST['transfer_money_button']))
{
		$withdrawalMoneyBalance = get_user_meta( $user_id, 'mycred_default' );

		$user_shopping_wallet_balance = get_user_meta( $user_id, 'user_shopping_wallet' );

		$transferedMoney= 0;
		if(isset($_POST['transfer_money']) && $_POST['transfer_money'] !='')
		{
			$transferedMoney = $_POST['transfer_money'];
		}
	
		$remainingWithdrawalWalletMoney = $withdrawalMoneyBalance[0] - $transferedMoney;

		$total_user_shoppinnt_wallert = $user_shopping_wallet_balance[0] + $transferedMoney;
		
		update_user_meta( $user_id, 'mycred_default', $remainingWithdrawalWalletMoney );
	
		update_user_meta( $user_id, 'user_shopping_wallet', $total_user_shoppinnt_wallert );
}



$user_shopping_wallet = get_user_meta( $user_id, 'user_shopping_wallet' );
$withrwal_money = get_user_meta( $user_id, 'mycred_default' );
$charity_money = get_user_meta( $user_id, 'user_charity_wallet' );

$total_amount_wallet = $user_shopping_wallet[0] + $withrwal_money[0] + $charity_money[0];
?>

<div class="uap-row">



	<div class="uapcol-md-2 uap-account-wallet-tab1">

		<div class="uap-account-no-box uap-account-box-lightgray">

			<div class="uap-account-no-box-inside">

				<div class="uap-count"> <?php echo uap_format_price_and_currency($data['currency'], round($withrwal_money[0], 2) ); ?> </div>

				<div class="uap-detail"><?php echo esc_html__('Withdrawal Wallet', 'uap'); ?></div>

			</div>

		</div>

	</div>



	<div class="uapcol-md-2 uap-account-wallet-tab2">

		<div class="uap-account-no-box uap-account-box-red">

			<div class="uap-account-no-box-inside">

				<div class="uap-count"> <?php echo uap_format_price_and_currency($data['currency'], round($user_shopping_wallet[0], 2));?> </div>

				<div class="uap-detail"><?php echo esc_html__('Shopping Wallet', 'uap'); ?></div>

                <!-- <div class="uap-subnote"><?php echo esc_html__('what can be converted and moved into your Wallet', 'uap'); ?></div> -->

			</div>

		</div>

	</div>

</div>

<div class="uap-profile-box-wrapper">

        <div class="uap-profile-box-content">

        	<div class="uap-row ">

            	<div class="uap-col-xs-12">

                   <div class="uap-account-detault-message">
          
				   <form method="post">
				
						<label for="transfer-money"><?php _e("Transfer money from withdrawal wallet to shopping wallet"); ?></label>
					
						<input type="number" class="form-control" name="transfer_money" id="" style="width:28%;">
						<br>
					<div>

						<button type="submit" name="transfer_money_button" class="btn btn-primary">Transfer Money</button>
					</div>
				</form>
                  </div>

          		</div>

             </div>

        </div>

</div>
</hr>
<div class="uap-profile-box-wrapper">

        <div class="uap-profile-box-content">

        	<div class="uap-row ">

            	<div class="uap-col-xs-12">

                   <div class="uap-account-detault-message">
          
				   <form method="post">
				
						<label for="transfer-money"><?php _e("Withdrawal Balance"); ?></label>
					
						<input type="number" name="withdraw_money" class="form-control" placeholder="Please Enter Amount For Withdraw" style="width:28%;">
						<br>
					<div>

						<button type="submit" name="withdraw_money_button" class="btn btn-primary">Withdraw Balance</button>
					</div>
				</form>
                  </div>

          		</div>

             </div>

        </div>

</div>

