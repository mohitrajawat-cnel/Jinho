<?php

    // $sql = "SELECT * FROM hfp_uap_ranks";
   
    // $result = $wpdb->get_results($sql);

    global $wpdb,$post;

  

    $mlm_amount_value = get_post_meta($post->ID,'mlm_amount_value_hwe',true);
    $mlm_amount_type = get_post_meta($post->ID,'mlm_amount_type_hwe',true);

   
    $mlm_amount_value_hwe = array();
    $mlm_amount_type_hwe = array();


    $mlm_amount_value_hwe[1]=0;
    $mlm_amount_value_hwe[2]=0;
    $mlm_amount_value_hwe[3]=0;
    $mlm_amount_value_hwe[4]=0;

    $mlm_amount_type_hwe[1]=0;
    $mlm_amount_type_hwe[2]=0;
    $mlm_amount_type_hwe[3]=0;
    $mlm_amount_type_hwe[4]=0;

    $mlm_amount_value_hwe = unserialize($mlm_amount_value);
    $mlm_amount_type_hwe = unserialize($mlm_amount_type);


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
<input type="number" step="0.01" min="0" class="uap-input-number" value="<?php echo $mlm_amount_value_hwe[1]; ?>" name="mlm_amount_value[1]">
<select name="mlm_amount_type[1]">
    <option value="flat" <?php if($mlm_amount_type_hwe[1] == 'flat'){ echo "selected"; } ?>>MYR</option>
    <option value="percentage" <?php if($mlm_amount_type_hwe[1] == 'percentage'){ echo "selected"; } ?>>%</option>
</select>
</td>
</tr>
<tr data-tr="2" id="uap_mlm_level_2">
<td>Level 2</td>
<td>
<input type="number" step="0.01" min="0" class="uap-input-number" value="<?php echo $mlm_amount_value_hwe[2]; ?>" name="mlm_amount_value[2]">
<select name="mlm_amount_type[2]">
    <option value="flat" <?php if($mlm_amount_type_hwe[2] == 'flat'){ echo "selected"; } ?>>MYR</option>
    <option value="percentage" <?php if($mlm_amount_type_hwe[2] == 'percentage'){ echo "selected"; } ?>>%</option>
</select>
</td>
</tr>
<tr data-tr="3" id="uap_mlm_level_3">
<td>Level 3</td>
<td>
<input type="number" step="0.01" min="0" class="uap-input-number" value="<?php echo $mlm_amount_value_hwe[3]; ?>" name="mlm_amount_value[3]">
<select name="mlm_amount_type[3]">
    <option value="flat" <?php if($mlm_amount_type_hwe[3] == 'flat'){ echo "selected"; } ?>>MYR</option>
    <option value="percentage" <?php if($mlm_amount_type_hwe[3] == 'percentage'){ echo "selected"; } ?>>%</option>
</select>
</td>
</tr>
<tr data-tr="4" id="uap_mlm_level_4">
<td>Level 4</td>
<td>
<input type="number" step="0.01" min="0" class="uap-input-number" value="<?php echo $mlm_amount_value_hwe[4]; ?>" name="mlm_amount_value[4]">
<select name="mlm_amount_type[4]">
    <option value="flat" <?php if($mlm_amount_type_hwe[4] == 'flat'){ echo "selected"; } ?>>MYR</option>
    <option value="percentage" <?php if($mlm_amount_type_hwe[4] == 'percentage'){ echo "selected"; } ?>>%</option>
</select>
</td>
</tr>

</tbody></table>

</form> 
</div>


