<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $data["link_new"]="#";
    $data["hide_new"]="1";
    $data["link_back"]=base_url().'indirect_cost_setup/index/list';
    $data["hide_approve"]="1";
    $this->load->view("action_buttons_edit",$data);
//print_r($cost);
?>
<form class="form_valid" id="save_form" action="<?php echo base_url();?>indirect_cost_setup/index/save" method="post">
    <input type="hidden" name="setup_id" id="setup_id" value="<?php if(isset($cost['id'])){echo $cost['id'];}else{echo 0;}?>"/>
    <div class="row widget">
        <div class="widget-header">
            <div class="title">
                <?php echo $title; ?>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_YEAR');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select name="year" id="year" class="form-control validate[required]">
                    <?php
                    $this->load->view('dropdown',array('drop_down_options'=>$years,'drop_down_selected'=>isset($cost['year'])?$cost['year']:''));
                    ?>
                </select>
            </div>
        </div>

        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_HO_AND_GEN_EXP_PER');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="ho_and_gen_exp" class="form-control validate[required]" value="<?php if(isset($cost['ho_and_gen_exp'])){echo $cost['ho_and_gen_exp'];}?>" />
            </div>
        </div>

        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_MARKETING_PER');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="marketing" class="form-control validate[required]" value="<?php if(isset($cost['marketing'])){echo $cost['marketing'];}?>" />
            </div>
        </div>

        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_FINANCE_COST_PER');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="finance_cost" class="form-control validate[required]" value="<?php if(isset($cost['finance_cost'])){echo $cost['finance_cost'];}?>" />
            </div>
        </div>
    </div>

    <div class="row widget">
        <div class="widget-header">
            <div class="title">
            </div>
            <div class="clearfix"></div>
        </div>

        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_TARGET_PROFIT_PER');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="target_profit" class="form-control validate[required]" value="<?php if(isset($cost['target_profit'])){echo $cost['target_profit'];}?>" />
            </div>
        </div>

        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_SALES_COMMISSION_PER');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="sales_commission" class="form-control validate[required]" value="<?php if(isset($cost['sales_commission'])){echo $cost['sales_commission'];}?>" />
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</form>

<script type="text/javascript">

    jQuery(document).ready(function()
    {
        $(".form_valid").validationEngine();
        turn_off_triggers();
    });

</script>