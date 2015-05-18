<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $data["link_new"]=base_url()."sales_prediction_setup/index/add";
    $data["link_back"]="#";
    $data["hide_back"]="1";
    $this->load->view("action_buttons_edit",$data);
?>
<form class="form_valid" id="save_form" action="<?php echo base_url();?>sales_prediction_setup/index/save" method="post">
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
                        $this->load->view('dropdown',array('drop_down_options'=>$years,'drop_down_selected'=>''));
                    ?>
                </select>
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_HO_AND_GENERAL_EXP_PERCENT');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="" class="form-control" />
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_MARKETING_PERCENT');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="" class="form-control" />
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_FINANCE_COST_PERCENT');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="" class="form-control" />
            </div>
        </div>
    </div>

<!--    ////////////////////////////////////////////////////// SALES PREDICTION /////////////////////////////////////////////////////////-->

    <div id="budget_add_more_container" class="budget_add_more_container">
        <div class="row widget">
            <div class="widget-header">
                <div class="title">
                    <?php echo $this->lang->line('LABEL_SALES_PREDICTION_SETUP'); ?>
                </div>
                <div class="clearfix"></div>
            </div>
    
            <div class="crop">
                <div class="col-xs-1">
                    <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CROP');?></label>
                </div>
                <div class="col-xs-2">
                    <select name="target[0][crop]" class="form-control crop_id" id="crop0">
                        <?php
                        $this->load->view('dropdown',array('drop_down_options'=>$crops,'drop_down_selected'=>''));
                        ?>
                    </select>
                </div>
            </div>
    
            <div class="type" style="display: none;">
                <div class="col-xs-1">
                    <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_TYPE');?></label>
                </div>
                <div class="col-xs-2">
                    <select name="target[0][type]" class="form-control type_id" id="type0" data-type-current-id="0">
                        <?php
                        $this->load->view('dropdown',array('drop_down_options'=>$types,'drop_down_selected'=>''));
                        ?>
                    </select>
                </div>
            </div>
    
            <div class="col-xs-6 variety_quantity" id="variety0" data-variety-current-id="0">
            </div>
        </div>
    </div>

    <div class="row text-center" id="add_more">
        <button type="button" class="btn btn-warning budget_add_more_button"><?php echo $this->lang->line('ADD_MORE');?></button>
    </div>

    <h1>&nbsp;</h1>

    <div class="clearfix"></div>
</form>

<div class="budget_add_more_content" style="display: none;">
    <div class="row widget budget_add_more_holder budget_add_more_container"  data-current-id="0">
        <div class="widget-header">
            <div class="title">
                <?php echo $this->lang->line('SALES_TARGET'); ?>
            </div>
            <button type="button" class="btn btn-danger pull-right budget_add_more_delete"><?php echo $this->lang->line('DELETE'); ?></button>
            <div class="clearfix"></div>
        </div>

        <div class="crop">
            <div class="col-xs-1">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CROP');?></label>
            </div>
            <div class="col-xs-2">
                <select class="form-control crop_id" id="crop_id">
                    <?php
                    $this->load->view('dropdown',array('drop_down_options'=>$crops,'drop_down_selected'=>''));
                    ?>
                </select>
            </div>
        </div>

        <div class="type" style="display: none;">
            <div class="col-xs-1">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_TYPE');?></label>
            </div>
            <div class="col-xs-2">
                <select name="" class="form-control type_id" id="type_id">
                    <?php
                    $this->load->view('dropdown',array('drop_down_options'=>$types,'drop_down_selected'=>''));
                    ?>
                </select>
            </div>
        </div>

        <div class="col-xs-6 variety_quantity" id="variety_quantity">
        </div>
    </div>
</div>

<script type="text/javascript">

    jQuery(document).ready(function()
    {
        turn_off_triggers();
        $(".form_valid").validationEngine();

        // ROW INCREMENT FUNCTION
        $(document).on("click", ".budget_add_more_button", function(event)
        {
            var current_id=parseInt($('.budget_add_more_content .budget_add_more_holder').attr('data-current-id'));
            current_id=current_id+1;

            $('.budget_add_more_content .budget_add_more_holder').attr('data-current-id',current_id);

            $('.budget_add_more_content .budget_add_more_holder #crop_id').attr('name','target['+current_id+'][crop]');
            $('.budget_add_more_content .budget_add_more_holder #type_id').attr('name','target['+current_id+'][type]');

            $('.budget_add_more_content .budget_add_more_holder .crop_id').attr('data-crop-current-id',current_id);
            $('.budget_add_more_content .budget_add_more_holder .type_id').attr('data-type-current-id',current_id);

            $('.budget_add_more_content .budget_add_more_holder .crop_id').attr('id','crop'+current_id);
            $('.budget_add_more_content .budget_add_more_holder .type_id').attr('id','type'+current_id);

            $('.budget_add_more_content .budget_add_more_holder .variety_quantity').attr('data-variety-current-id',current_id);
            $('.budget_add_more_content .budget_add_more_holder .variety_quantity').attr('id','variety'+current_id);

            var html=$('.budget_add_more_content').html();
            $('#budget_add_more_container').append(html);
        });

        // Incremented Row Delete Button
        $(document).on("click", ".budget_add_more_delete", function(event)
        {
            $(this).closest('.budget_add_more_container').remove();
        });

        $(document).on("change",".crop_id",function()
        {
            var current_id=parseInt($(this).parents().next('.type').find('.type_id').attr('data-type-current-id'));

            //alert(current_id);
            if($(this).val().length>0)
            {
                $(this).parents().next('.type').show();

                $.ajax({
                    url: base_url+"budget_common/get_dropDown_type_by_crop/",
                    type: 'POST',
                    dataType: "JSON",
                    data:{crop_id:$(this).val(), current_id:current_id},
                    success: function (data, status)
                    {

                    },
                    error: function (xhr, desc, err)
                    {
                        console.log("error");
                    }
                });
            }
            else
            {
                $(this).parents().next('.type').hide();
                $(this).parents().next('.type').val('');
            }
        });

        $(document).on("change",".type_id",function()
        {
            var current_id=parseInt($(this).parents().next('.variety_quantity').attr('data-variety-current-id'));

            $.ajax({
                url: base_url+"sales_prediction_setup/get_dropDown_variety_by_crop_type/",
                type: 'POST',
                dataType: "JSON",
                data:{crop_id:$("#crop"+current_id).val(), type_id:$(this).val(), current_id: current_id},
                success: function (data, status)
                {

                },
                error: function (xhr, desc, err)
                {
                    console.log("error");
                }
            });
        });

        $(document).on("keyup", ".number_only_class", function()
        {
            this.value = this.value.replace(/[^0-9\.]/g,'');
        });

    });
</script>