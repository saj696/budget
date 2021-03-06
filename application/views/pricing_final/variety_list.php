<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="clearfix"></div>

<div class="row show-grid">
    <div class="col-lg-12 main_div" style="overflow: auto;">
        <table class="table table-hover table-bordered">
            <th class="text-center"><?php echo $this->lang->line('LABEL_CROP')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_TYPE')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_VARIETY')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_TARGETED_QUANTITY')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_TARGETED_PROFIT_PER')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_LAST_YEAR_MRP')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_AUTOMATED_MRP')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_MGT_MRP')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_MARKETING_MRP')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_FINAL_MRP')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_SALES_COMMISSION_PER')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_SALES_COMMISSION_AMOUNT')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_SALES_BONUS_MGT')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_SALES_BONUS_AMOUNT')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_OTHER_INCENTIVE_MGT')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_OTHER_INCENTIVE_AMOUNT')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_NET_SALES_PRICE')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_FINAL_NET_PROFIT')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_MARKETING_NET_PROFIT')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_MANAGEMENT_NET_PROFIT')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_AUTOMATED_NET_PROFIT')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_TOTAL_NET_SALES')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_TOTAL_NET_PROFIT')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_PROFIT_PER')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_REMARKS')?></th>

            <?php
            $crop_name = '';
            $product_type_name = '';
            $grand_total_net_sales = 0;
            $grand_total_net_profit = 0;

            foreach($varieties as $key=>$variety)
            {
                $net_sales_price = 0;
                $total_net_sales_price = 0;
                $net_profit = 0;
                $total_net_profit = 0;
                $profit_percentage = 0;

                $detail = Pricing_helper::get_pricing_final_info($year, $variety['varriety_id']);
                $existing_data = Pricing_helper::get_pricing_final_existing_info($year, $variety['varriety_id']);
                $existing_mrp = isset($existing_data['mrp'])?$existing_data['mrp']:0;

                if(is_array($existing_data) && sizeof($existing_data)>0)
                {
                    $net_sales_price = round($existing_data['mrp'] - ($existing_data['sales_bonus']/100)*$existing_data['mrp'] - ($existing_data['other_incentive']/100)*$existing_data['mrp'] - ($existing_data['sales_commission']/100)*$existing_data['mrp']);
                    $total_net_sales_price = round($net_sales_price*$detail['targeted_quantity']);
                    $grand_total_net_sales += $total_net_sales_price;
                    $net_profit = round($net_sales_price - $detail['total_cogs']);
                    $total_net_profit = round($net_profit*$detail['targeted_quantity']);
                    $grand_total_net_profit += $total_net_profit;
                    if($detail['total_cogs']>0)
                    {
                        $profit_percentage = round(($net_profit/$net_sales_price)*100);
                    }
                    else
                    {
                        $profit_percentage = 0;
                    }
                }
            ?>
            <tr class="main_tr">
                <td class="text-center">
                    <?php
                    if($crop_name == '')
                    {
                        echo $variety['crop_name'];
                        $crop_name = $variety['crop_name'];
                    }
                    elseif($crop_name == $variety['crop_name'])
                    {
                        echo "&nbsp;";
                    }
                    else
                    {
                        echo $variety['crop_name'];
                        $crop_name = $variety['crop_name'];
                    }
                    ?>
                </td>
                <td class="text-center">
                    <?php
                    if($product_type_name == '')
                    {
                        echo $variety['product_type'];
                        $product_type_name = $variety['product_type'];
                    }
                    elseif($product_type_name == $variety['product_type'])
                    {
                        echo "&nbsp;";
                    }
                    else
                    {
                        echo $variety['product_type'];
                        $product_type_name = $variety['product_type'];
                    }
                    ?>
                </td>
                <td class="text-center"><?php echo $variety['varriety_name'];?><input type="hidden" name="total_cogs" class="total_cogs" value="<?php echo $detail['total_cogs'];?>" /><input type="hidden" name="cogs" class="cogs" value="<?php echo $detail['cogs'];?>" /></td>
                <td class="text-center"><?php echo $detail['targeted_quantity'];?><input type="hidden" name="targeted_quantity" class="targeted_quantity" value="<?php echo $detail['targeted_quantity'];?>" /></td>
                <td class="text-center"><input type="text" <?php if(sizeof($existing_data)>0){echo 'disabled';}?> name="pricing[<?php echo $variety['crop_id'];?>][<?php echo $variety['product_type_id'];?>][<?php echo $variety['varriety_id'];?>][target_profit]" class="form-control target_profit numbersOnly" value="<?php if(isset($existing_data['target_profit'])){echo round($existing_data['target_profit']);}else{echo round($detail['target_profit']);}?>" /></td>
                <td class="text-center"><?php echo $detail['last_year_mrp'];?></td>
                <td class="text-center"><?php echo $detail['automated_mrp'];?></td>
                <td class="text-center"><?php echo $detail['management_mrp'];?></td>
                <td class="text-center"><?php echo $detail['marketing_mrp'];?></td>
                <td class="text-center"><input type="text" <?php if(sizeof($existing_data)>0){echo 'disabled';}?> name="pricing[<?php echo $variety['crop_id'];?>][<?php echo $variety['product_type_id'];?>][<?php echo $variety['varriety_id'];?>][mrp]" class="form-control mrp_final numbersOnly trigger_class" value="<?php echo isset($existing_data['mrp'])?$existing_data['mrp']:'';?>" /></td>
                <td class="text-center"><input type="text" <?php if(sizeof($existing_data)>0){echo 'disabled';}?> name="pricing[<?php echo $variety['crop_id'];?>][<?php echo $variety['product_type_id'];?>][<?php echo $variety['varriety_id'];?>][sales_commission]" class="form-control sales_commission trigger_class" value="<?php if(isset($existing_data['sales_commission'])){echo $existing_data['sales_commission'];}else{echo $detail['sales_commission'];}?>" /></td>
                <td class="text-center sales_commission_amount"><?php if(isset($existing_data['sales_commission'])){echo round(($existing_data['sales_commission']/100)*$existing_mrp);}else{echo round(($detail['sales_commission']/100)*$existing_mrp);}?></td>
                <td class="text-center"><input type="text" <?php if(sizeof($existing_data)>0){echo 'disabled';}?> name="pricing[<?php echo $variety['crop_id'];?>][<?php echo $variety['product_type_id'];?>][<?php echo $variety['varriety_id'];?>][sales_bonus]" class="form-control sales_bonus numbersOnly trigger_class" value="<?php if(isset($existing_data['sales_bonus'])){echo $existing_data['sales_bonus'];}else{echo $detail['sales_bonus'];}?>" /></td>
                <td class="text-center sales_bonus_amount"><?php if(isset($existing_data['sales_bonus'])){echo round(($existing_data['sales_bonus']/100)*$existing_mrp);}else{echo round(($detail['sales_bonus']/100)*$existing_mrp);}?></td>
                <td class="text-center"><input type="text" <?php if(sizeof($existing_data)>0){echo 'disabled';}?> name="pricing[<?php echo $variety['crop_id'];?>][<?php echo $variety['product_type_id'];?>][<?php echo $variety['varriety_id'];?>][other_incentive]" class="form-control other_incentive numbersOnly trigger_class" value="<?php if(isset($existing_data['other_incentive'])){echo $existing_data['other_incentive'];}else{echo $detail['other_incentive'];}?>" /></td>
                <td class="text-center other_incentive_amount"><?php if(isset($existing_data['other_incentive'])){echo round(($existing_data['other_incentive']/100)*$existing_mrp);}else{echo round(($detail['other_incentive']/100)*$existing_mrp);}?></td>
                <td class="text-center net_sales_price"><?php if(isset($net_sales_price)){echo $net_sales_price;}?></td>
                <td class="text-center net_profit"><?php if(isset($net_profit)){echo round($net_profit);}?></td>
                <td class="text-center"><?php echo isset($detail['marketing_net_profit'])?$detail['marketing_net_profit']:0;?></td>
                <td class="text-center"><?php echo isset($detail['management_net_profit'])?$detail['management_net_profit']:0;?></td>
                <td class="text-center"><?php echo isset($detail['automated_net_profit'])?$detail['automated_net_profit']:0;?></td>
                <td class="text-center total_net_sales"><?php if(isset($total_net_sales_price)){echo $total_net_sales_price;}?></td>
                <td class="text-center total_net_profit"><?php if(isset($total_net_profit)){echo $total_net_profit;}?></td>
                <td class="text-center profit_percentage"><?php if(isset($profit_percentage)){echo $profit_percentage;}?></td>
                <td class="text-center" style="vertical-align: middle;">
                    <label class="label label-primary load_remark">+R</label>
                    <div class="row popContainer" style="display: none;">
                        <table class="table table-bordered">
                            <tr>
                                <td>
                                    <div class="col-lg-12">
                                        <textarea class="form-control" name="pricing[<?php echo $variety['crop_id'];?>][<?php echo $variety['product_type_id'];?>][<?php echo $variety['varriety_id'];?>][remarks]" placeholder="Add Remarks"><?php if(isset($existing_confirmed_quantity['remarks'])){echo $existing_confirmed_quantity['remarks'];}?><?php echo isset($existing_data['remarks'])?$existing_data['remarks']:'';?></textarea>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="pull-right" style="border: 0px;">
                                    <div class="col-lg-12">
                                        <label class="label label-primary crossSpan"><?php echo $this->lang->line('OK');?></label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"><?php echo $this->lang->line('LABEL_TOTAL');?></td>
                <td class="text-center"><label class="label label-danger grand_total_net_sales"><?php if(isset($grand_total_net_sales)){echo $grand_total_net_sales;}?></label></td>
                <td class="text-center"><label class="label label-danger grand_total_net_profit"><?php if(isset($grand_total_net_profit)){echo $grand_total_net_profit;}?></label></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
            </tr>
        </table>
    </div>
</div>

<script>

    jQuery(document).ready(function()
    {
        $(document).on("click", ".load_remark", function(event)
        {
            $(this).closest('td').find('.popContainer').show();
        });

        $(document).on("click",".crossSpan",function()
        {
            $(".popContainer").hide();
        });

        $(document).on("keyup",".trigger_class",function()
        {
            var total_cogs = parseFloat($(this).closest('.main_tr').find(".total_cogs").val());
            var targeted_quantity = parseInt($(this).closest('.main_tr').find(".targeted_quantity").val());
            var sales_commission = parseFloat($(this).closest('.main_tr').find(".sales_commission").val());
            var sales_bonus = parseFloat($(this).closest('.main_tr').find(".sales_bonus").val());
            var other_incentive = parseFloat($(this).closest('.main_tr').find(".other_incentive").val());
            var mrp_final = parseFloat($(this).closest('.main_tr').find(".mrp_final").val());

            var net_sales_price = Math.round(mrp_final - (sales_commission/100)*mrp_final - (sales_bonus/100)*mrp_final - (other_incentive/100)*mrp_final);
            var total_net_sales_price = Math.round(net_sales_price*targeted_quantity);

            $(this).closest('.main_tr').find(".net_sales_price").html(net_sales_price);
            $(this).closest('.main_tr').find(".total_net_sales").html(total_net_sales_price);

            var net_profit = Math.round(net_sales_price - total_cogs);
            var total_net_profit = Math.round(net_profit*targeted_quantity);

            $(this).closest('.main_tr').find(".net_profit").html(net_profit);
            $(this).closest('.main_tr').find(".total_net_profit").html(total_net_profit);

            var profit_percentage = Math.round((net_profit/net_sales_price)*100);
            $(this).closest('.main_tr').find(".profit_percentage").html(profit_percentage);

            // Calculate Grand totals (Net Profit and sales)
            var total_net_profit_attr = $(this).closest('.main_div').find(".total_net_profit");
            var total_net_sales_attr = $(this).closest('.main_div').find(".total_net_sales");
            var total_net_profit_sum = 0;
            var total_net_sales_sum = 0;

            total_net_profit_attr.each(function()
            {
                var net_profit_attr_val = $(this).html();
                if(net_profit_attr_val)
                {
                    net_profit_attr_val = parseFloat( net_profit_attr_val.replace( /^\$/, "" ));
                    total_net_profit_sum += !isNaN( net_profit_attr_val ) ? net_profit_attr_val : 0;
                }
            });

            var grand_total_net_profit = Math.round(total_net_profit_sum);
            $(".grand_total_net_profit").html(grand_total_net_profit);

            total_net_sales_attr.each(function()
            {
                var net_sales_attr_val = $(this).html();
                if(net_sales_attr_val)
                {
                    net_sales_attr_val = parseFloat( net_sales_attr_val.replace( /^\$/, "" ));
                    total_net_sales_sum += !isNaN( net_sales_attr_val ) ? net_sales_attr_val : 0;
                }
            });

            var grand_total_net_sales = Math.round(total_net_sales_sum);
            $(".grand_total_net_sales").html(grand_total_net_sales);
        });

        // Bonus Amount Showing
        $(document).on("keyup",".sales_commission",function()
        {
            var mrp_final = parseFloat($(this).closest('.main_tr').find('.mrp_final').val());
            var sales_commission = parseFloat($(this).val());
            var sales_commission_amount = Math.round((sales_commission/100)*mrp_final);
            $(this).closest('.main_tr').find('.sales_commission_amount').html(sales_commission_amount);
        });
        $(document).on("keyup",".sales_bonus",function()
        {
            var mrp_final = parseFloat($(this).closest('.main_tr').find('.mrp_final').val());
            var sales_bonus = parseFloat($(this).val());
            var sales_bonus_amount = Math.round((sales_bonus/100)*mrp_final);
            $(this).closest('.main_tr').find('.sales_bonus_amount').html(sales_bonus_amount);
        });
        $(document).on("keyup",".other_incentive",function()
        {
            var mrp_final = parseFloat($(this).closest('.main_tr').find('.mrp_final').val());
            var other_incentive = parseFloat($(this).val());
            var other_incentive_amount = Math.round((other_incentive/100)*mrp_final);
            $(this).closest('.main_tr').find('.other_incentive_amount').html(other_incentive_amount);
        });
    });
</script>