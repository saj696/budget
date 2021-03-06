<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="clearfix"></div>

<div class="row show-grid">
    <div class="col-lg-12" style="overflow: auto;">
        <table class="table table-hover table-bordered">
            <th class="text-center"><?php echo $this->lang->line('LABEL_CROP')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_TYPE')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_VARIETY')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_TARGETED_QUANTITY')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_COGS')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_HO_AND_GEN_EXP')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_MARKETING')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_FINANCE_COST')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_TARGET_PROFIT')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_NET_SALES_PRICE')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_SALES_COMMISSION_PER')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_SALES_BONUS')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_OTHER_INCENTIVE')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_BUDGETED_MRP')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_TOTAL_NET_PROFIT')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_TOTAL_NET_SALES')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_TARGET_PROFIT_PER')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_REMARKS')?></th>

            <?php
            $crop_name = '';
            $product_type_name = '';
            $grand_total_net_profit = 0;
            $grand_total_net_sales = 0;

            foreach($varieties as $key=>$variety)
            {
                $detail = Pricing_helper::get_pricing_automated_info($year, $variety['varriety_id']);
                $net_sales_price = round($detail['cogs'] + ($detail['ho_and_gen_exp']/100)*$detail['cogs'] + ($detail['marketing']/100)*$detail['cogs'] + ($detail['finance_cost']/100)*$detail['cogs']+ ($detail['target_profit']/100)*$detail['cogs'], 2);
                $budgeted_mrp = $net_sales_price + ($detail['sales_commission']/100)*$net_sales_price + ($detail['sales_bonus']/100)*$net_sales_price + ($detail['other_incentive']/100)*$net_sales_price;
                $existing_data = Pricing_helper::get_pricing_automated_existing_info($year, $variety['varriety_id']);
                $total_net_profit = $detail['targeted_quantity']*($detail['target_profit']/100)*$detail['cogs'];
                $grand_total_net_profit += $total_net_profit;
                $total_net_sales = $net_sales_price*$detail['targeted_quantity'];
                $grand_total_net_sales += $total_net_sales;
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
                <td class="text-center"><?php echo $variety['varriety_name'];?></td>
                <td class="text-center"><?php echo $detail['targeted_quantity'];?></td>
                <td class="text-center"><?php echo $detail['cogs'];?></td>
                <td class="text-center"><?php echo round(($detail['ho_and_gen_exp']/100)*$detail['cogs'], 2);?></td>
                <td class="text-center"><?php echo round(($detail['marketing']/100)*$detail['cogs'], 2);?></td>
                <td class="text-center"><?php echo round(($detail['finance_cost']/100)*$detail['cogs'], 2);?></td>
                <td class="text-center"><?php echo ($detail['cogs']*($detail['target_profit']/100));?></td>
                <td class="text-center"><?php echo $net_sales_price;?><input type="hidden" name="net_sales_price" class="net_sales_price" value="<?php echo $net_sales_price;?>" /></td>
                <td class="text-center"><input type="text" readonly name="pricing[<?php echo $variety['crop_id'];?>][<?php echo $variety['product_type_id'];?>][<?php echo $variety['varriety_id'];?>][sales_commission]" class="form-control sales_commission numbersOnly" value="<?php if(isset($existing_data['sales_commission'])){echo $existing_data['sales_commission'];}else{echo $detail['sales_commission'];}?>" /></td>
                <td class="text-center"><input type="text" readonly name="pricing[<?php echo $variety['crop_id'];?>][<?php echo $variety['product_type_id'];?>][<?php echo $variety['varriety_id'];?>][sales_bonus]" class="form-control sales_bonus numbersOnly" value="<?php if(isset($existing_data['sales_bonus'])){echo $existing_data['sales_bonus'];}else{echo $detail['sales_bonus'];}?>" /></td>
                <td class="text-center"><input type="text" readonly name="pricing[<?php echo $variety['crop_id'];?>][<?php echo $variety['product_type_id'];?>][<?php echo $variety['varriety_id'];?>][other_incentive]" class="form-control other_incentive numbersOnly" value="<?php if(isset($existing_data['other_incentive'])){echo $existing_data['other_incentive'];}else{echo $detail['other_incentive'];}?>" /></td>
                <td class="text-center"><input type="text" readonly name="pricing[<?php echo $variety['crop_id'];?>][<?php echo $variety['product_type_id'];?>][<?php echo $variety['varriety_id'];?>][mrp]" class="form-control total_budgeted_mrp numbersOnly" value="<?php if(isset($existing_data['mrp'])){echo $existing_data['mrp'];}else{echo round($budgeted_mrp, 2);}?>" /></td>
                <td class="text-center"><?php echo $total_net_profit;?></td>
                <td class="text-center total_net_sales"><?php echo $total_net_sales;?></td>
                <td class="text-center"><?php echo $detail['target_profit'];?></td>
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
                <td class="text-center"><?php echo $this->lang->line('LABEL_TOTAL');?></td>
                <td class="text-center" style="vertical-align: middle;"><label class="label label-danger"><?php echo $grand_total_net_profit;?></label></td>
                <td class="text-center" style="vertical-align: middle;"><label class="label label-danger"><?php echo $grand_total_net_sales;?></label></td>
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

        $(document).on("keyup",".other_incentive",function()
        {
            var net_sales_price = parseFloat($(this).closest('.main_tr').find(".net_sales_price").val());
            var sales_commission = parseFloat($(this).closest('.main_tr').find(".sales_commission").val());
            var sales_bonus = parseFloat($(this).closest('.main_tr').find(".sales_bonus").val());
            var other_incentive = parseFloat($(this).val());

            var total_budgeted_mrp = (net_sales_price + (sales_commission/100)*net_sales_price + (sales_bonus/100)*net_sales_price + (other_incentive/100)*net_sales_price).toFixed(2);
            $(this).closest('.main_tr').find(".total_budgeted_mrp").val(total_budgeted_mrp);
        });
    });
</script>