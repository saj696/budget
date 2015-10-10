<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="clearfix"></div>


<div class="row show-grid">
    <div class="col-lg-12">
        <table class="table table-hover table-bordered">
            <th class="text-center"><?php echo $this->lang->line('LABEL_CROP')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_TYPE')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_VARIETY')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_BUDGETED_SALES_QTY_HO')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_VARIANCE')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_BUDGETED_PURCHASE_QUANTITY')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_ACTUAL_PURCHASE_CONFIRMED')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_PI_VALUE_US')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_MONTH_SETUP')?></th>
            <th class="text-center"><?php echo $this->lang->line('LABEL_REMARKS')?></th>
            <?php
            foreach($varieties as $variety)
            {
                $budgeted_sales_quantity = $this->confirmed_quantity_setup_model->get_budgeted_sales_quantity($year, $variety['crop_id'], $variety['product_type_id'], $variety['varriety_id']);
                $min_stock_quantity = $this->confirmed_quantity_setup_model->get_budget_min_stock_quantity($variety['crop_id'], $variety['product_type_id'], $variety['varriety_id']);
                $current_stock = Purchase_helper::get_current_stock($variety['crop_id'], $variety['product_type_id'], $variety['varriety_id']);
            ?>
            <tr>
                <td class="text-center"><?php echo $variety['crop_name'];?></td>
                <td class="text-center"><?php echo $variety['product_type'];?></td>
                <td class="text-center"><?php echo $variety['varriety_name'];?></td>
                <td class="text-center"><?php echo $budgeted_sales_quantity;?></td>
                <td class="text-center"><?php echo $current_stock - $min_stock_quantity;?></td>
                <td class="text-center"><?php echo $budgeted_sales_quantity - ($current_stock - $min_stock_quantity);?></td>
                <td class="text-center"><input type="text" class="form-control variety_total_quantity numbersOnly" name="quantity[<?php echo $variety['crop_id'];?>][<?php echo $variety['product_type_id'];?>][<?php echo $variety['varriety_id'];?>][confirmed_quantity]" value="" /></td>
                <td class="text-center"><input type="text" class="form-control variety_total_quantity numbersOnly" name="quantity[<?php echo $variety['crop_id'];?>][<?php echo $variety['product_type_id'];?>][<?php echo $variety['varriety_id'];?>][pi_value]" value="" /></td>
                <td class="text-center" style="vertical-align: middle;">
                    <label class="label label-primary load_remark">+S</label>
                    <div class="row popContainer" style="display: none;">
                        <table class="table table-bordered">
                            <tr>
                                <td>
                                    <?php echo $this->lang->line('LABEL_CONFIRMED_QUANTITY');?>
                                </td>
                                <td>
                                    <label class="label label-primary confirmed_quantity">100</label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php echo $this->lang->line('LABEL_REMAINING_QUANTITY');?>
                                </td>
                                <td>
                                    <label class="label label-primary remaining_quantity">80</label>
                                </td>
                            </tr>
                        </table>

                        <table class="table table-bordered">
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
                <td class="text-center" style="vertical-align: middle;">
                    <label class="label label-primary load_remark">+R</label>
                    <div class="row popContainer" style="display: none;">
                        <table class="table table-bordered">
                            <tr>
                                <td>
                                    <div class="col-lg-12">
                                        <textarea class="form-control" name="quantity[<?php echo $variety['crop_id'];?>][<?php echo $variety['product_type_id'];?>][<?php echo $variety['varriety_id'];?>][remarks]" placeholder="Add Remarks"></textarea>
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
    });
</script>