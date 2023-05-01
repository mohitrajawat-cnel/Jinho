<!-- mohit -->
<?php

    // $sql = "SELECT * FROM hfp_uap_ranks";
   
    // $result = $wpdb->get_results($sql);

    global $wpdb,$post;

  

    $mlm_amount_variation_value = get_post_meta($data['variantion_id'],'mlm_amount_variation_value',true);
    $mlm_amount_variation_value= unserialize($mlm_amount_variation_value);
    $mlm_amount_variation_type = get_post_meta($data['variantion_id'],'mlm_amount_variation_type',true);
    $mlm_amount_variation_type= unserialize($mlm_amount_variation_type);

   
    // $mlm_amount_value_hwe = array();
    // $mlm_amount_type_hwe = array();


    // $mlm_amount_value_hwe[1]=0;
    // $mlm_amount_value_hwe[2]=0;
    // $mlm_amount_value_hwe[3]=0;
    // $mlm_amount_value_hwe[4]=0;

    // $mlm_amount_type_hwe[1]=0;
    // $mlm_amount_type_hwe[2]=0;
    // $mlm_amount_type_hwe[3]=0;
    // $mlm_amount_type_hwe[4]=0;

    // $mlm_amount_value_hwe = unserialize($mlm_amount_value);
    // $mlm_amount_type_hwe = unserialize($mlm_amount_type);


?>
<div id="uap_woo_wsr" class="panel woocommerce_options_panel options_group" >


<form action="" method="post">


<table class="uap-dashboard-inside-table" id="mlm-amount-for-each-level">
<thead>
<tr>
<th class="uap-mlm-levels-table-header-col">Level</th>
<th>Value</th>
</tr>
</thead>
<tbody><tr data-tr="1" id="uap_mlm_level_1">
<td>Level 1</td>
<td>
<input type="number" step="0.01" min="0" class="uap-input-number" value="<?php echo $mlm_amount_variation_value[1]; ?>" name="mlm_amount_variation_value[<?php echo $data['variantion_id'];?>][1]">
<select name="mlm_amount_variation_type[<?php echo $data['variantion_id'];?>][1]">
    <option value="flat" <?php if($mlm_amount_variation_type[1] == 'flat'){ echo "selected"; } ?>>MYR</option>
    <option value="percentage" <?php if($mlm_amount_variation_type[1] == 'percentage'){ echo "selected"; } ?>>%</option>
</select>
</td>
</tr>
<tr data-tr="2" id="uap_mlm_level_2">
<td>Level 2</td>
<td>
<input type="number" step="0.01" min="0" class="uap-input-number" value="<?php echo $mlm_amount_variation_value[2]; ?>" name="mlm_amount_variation_value[<?php echo $data['variantion_id'];?>][2]">
<select name="mlm_amount_variation_type[<?php echo $data['variantion_id'];?>][2]">
    <option value="flat" <?php if($mlm_amount_variation_type[2] == 'flat'){ echo "selected"; } ?>>MYR</option>
    <option value="percentage" <?php if($mlm_amount_variation_type[2] == 'percentage'){ echo "selected"; } ?>>%</option>
</select>
</td>
</tr>
<tr data-tr="3" id="uap_mlm_level_3">
<td>Level 3</td>
<td>
<input type="number" step="0.01" min="0" class="uap-input-number" value="<?php echo $mlm_amount_variation_value[3]; ?>" name="mlm_amount_variation_value[<?php echo $data['variantion_id'];?>][3]">
<select name="mlm_amount_variation_type[<?php echo $data['variantion_id'];?>][3]">
    <option value="flat" <?php if($mlm_amount_variation_type[3] == 'flat'){ echo "selected"; } ?>>MYR</option>
    <option value="percentage" <?php if($mlm_amount_variation_type[3] == 'percentage'){ echo "selected"; } ?>>%</option>
</select>
</td>
</tr>
<tr data-tr="4" id="uap_mlm_level_4">
<td>Level 4</td>
<td>
<input type="number" step="0.01" min="0" class="uap-input-number" value="<?php echo $mlm_amount_variation_value[4]; ?>" name="mlm_amount_variation_value[<?php echo $data['variantion_id'];?>][4]">
<select name="mlm_amount_variation_type[<?php echo $data['variantion_id'];?>][4]">
    <option value="flat" <?php if($mlm_amount_variation_type[4] == 'flat'){ echo "selected"; } ?>>MYR</option>
    <option value="percentage" <?php if($mlm_amount_variation_type[4] == 'percentage'){ echo "selected"; } ?>>%</option>
</select>
</td>
</tr>

</tbody></table>

</form> 
</div>


<!-- <div id="_variation_settings" clas="panel woocommerce_options_panel" >


    <div class="form-row form-row-full options">


    <h4><?php esc_html_e( 'Ultimate Affiliate Pro - Specific Referral Rate', 'uap' );?></h4>
    


    <p class="form-row form-row-full"><?php esc_html_e( 'Customize Referral Rate for current product variation.', 'uap');?></p>


    <p class="form-row form-row-full">


        <label><?php esc_html_e('Referral Rate Type', 'uap');?></label>


            <select name="uap-woo-wsr-variable-product-type[<?php echo $data['variantion_id'];?>]">


                <?php if ( $data['types'] ):?>


                    <?php foreach ( $data['types'] as $key => $value ):?>


                        <option value="<?php echo $key;?>" <?php echo ( $data['uap-woo-wsr-type'] == $key ) ? 'selected' : '';?> ><?php echo $value;?></option>


                    <?php endforeach;?>


                <?php endif;?>


            </select>


    </p>





    <p class="form-row form-row-full">


        <label><?php esc_html_e('Referral Value', 'uap');?></label>


        <input type="number" step="0.01" min="0" name="uap-woo-wsr-variable-product-value[<?php echo $data['variantion_id'];?>]" value="<?php echo $data['uap-woo-wsr-value'];?>" />


    </p>


    <?php


    $offerType = get_option( 'uap_referral_offer_type' );


    if ( $offerType == 'biggest' ){


    		$offerType = esc_html__( 'Biggest', 'uap' );


    } else {


    		$offerType = esc_html__( 'Lowest', 'uap' );


    }


    echo esc_html__( 'If there are multiple Amounts set for the same action, like Ranks, Offers, Product or Category rate the ', 'uap' ) . '<strong>' . $offerType . '</strong> ' . esc_html__( 'will be taken in consideration. You may change that from', 'uap' ) . ' <a href="' . admin_url( 'admin.php?page=ultimate_affiliates_pro&tab=settings' ) . '" target="_blank">' . esc_html__( 'here.', 'uap' ) . '</a>';


    ?>


  </div>


</div>

 -->
