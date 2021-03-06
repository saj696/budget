$(document).ready(function()
{
    $(document).ajaxStart(function()
    {
        $("#loading").show();
    });

    $(document).ajaxSuccess(function(event,xhr,options)
    {
        if(xhr.responseJSON)
        {
            if(xhr.responseJSON.content)
            {
                load_template(xhr.responseJSON.content);
            }

        }
    });

    $(document).ajaxComplete(function(event,xhr,options)
    {
        if(xhr.responseJSON)
        {
            if(xhr.responseJSON.page_url)
            {
                //window.history.pushState(null, "Search Results",xhr.responseJSON.page_url);
                window.history.replaceState(null, "Search Results",xhr.responseJSON.page_url);
            }

            //$("#loading").hide();
            $("#loading").hide();
            if(xhr.responseJSON.message)
            {
                animate_message(xhr.responseJSON.message);
            }
        }
    });

    $(document).ajaxError(function(event,xhr,options)
    {

        $("#loading").hide();

        animate_message("Server Error");

    });

    //binds form submission with ajax
    $(document).on("submit", "form", function(event)
    {
        if($(this).hasClass('external'))
        {
            return true;
        }
        event.preventDefault();
        $.ajax({
            url: $(this).attr("action"),
            type: $(this).attr("method"),
            dataType: "JSON",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (data, status)
            {

            },
            error: function (xhr, desc, err)
            {


            }
        });
    });

    //bind any anchor tag to ajax request
    $(document).on("click", "a", function(event)
    {

        if($(this).hasClass('external')||$(this).hasClass('ui-corner-all'))
        {
            return true;
        }
        event.preventDefault();
        $.ajax({
            url: $(this).attr("href"),
            type: 'POST',
            dataType: "JSON",
            success: function (data, status)
            {

            },
            error: function (xhr, desc, err)
            {
                console.log("error");

            }
        });

    });
    //bind save button to submit the form
    $(document).on("click", "#button_save", function(event)
    {
        $("#save_form").submit();

    });

    //load dashboard first time
    load_main_page();
    // binds form submission and fields to the validation engine

    //action for menu-item click
    $(document).on("click", ".main-menu-container .menu-item", function(event)
    {
        $.ajax({
            url: base_url+"dashboard/get_sub_menu/",
            type: 'POST',
            dataType: "JSON",
            data:{menu_id:$(this).attr("data-menu-id")},
            success: function (data, status)
            {

            },
            error: function (xhr, desc, err)
            {
                console.log("error");

            }
        });
        return false;
    });

    //action for submenu click
    $(document).on("click", ".sub-menu-container .menu-item", function(event)
    {
        $.ajax({
            url: $(this).attr("data-menu-link"),
            type: 'POST',
            dataType: "JSON",
            success: function (data, status)
            {

            },
            error: function (xhr, desc, err)
            {
                console.log("error");

            }
        });
    });
});

function load_main_page()
{
    $.ajax({
        url: location,
        type: 'POST',
        dataType: "JSON",
        success: function (data, status)
        {
            $.ajax({
                url: base_url+"home/sidebar/",
                type: 'POST',
                dataType: "JSON",
                success: function (data, status)
                {

                },
                error: function (xhr, desc, err)
                {
                    console.log("error");

                }
            });

        },
        error: function (xhr, desc, err)
        {
            console.log("error");

        }
    });
}

function load_template(content)
{
    for(i=0;i<content.length;i++)
    {
        $(content[i].id).html(content[i].html);
        //console.log(content[i].id);
        //console.log(content[i].html);
    }
}

function animate_message(message)
{
    $("#message").hide();
    $("#message").html(message);
    $('#message').slideToggle("slow").delay(3000).slideToggle("slow");
    //$('#message').toggle("slide",{direction:"right"},500);

}

//call this function
//first parameter will be this
//2nd parameter will be with # for id . for class
function display_browse_image(brose_bttion,display_id)
{
    if (brose_bttion.files && brose_bttion.files[0])
    {
        var reader = new FileReader();

        reader.onload = function (e)
        {
            $(display_id).attr('src', e.target.result);
        }

        reader.readAsDataURL(brose_bttion.files[0]);
    }
}

function turn_off_triggers()
{
    $(document).off("change", "#year");
    $(document).off("change", "#division");
    $(document).off("change", "#zone");
    $(document).off("change", "#territory");
    $(document).off("change", "#district");
    $(document).off("change", "#customer");
    $(document).off("change", "#crop");
    $(document).off("change", "#type");
    $(document).off("click", ".budget_add_more_button");
    $(document).off("click", ".budget_add_more_delete");
    $(document).off("change", ".crop_id");
    $(document).off("change", ".type_id");
    $(document).off("change", ".variety_id");
    $(document).off("keyup", ".variety_quantity");
    $(document).off("keyup", ".quantity");
    $(document).off("keyup", ".number_only_class");
    $(document).off("click", "#finalise");
    $(document).off("click", "#load_report");
    $(document).off("click", "#myButtonRight");
    $(document).off("click", "#myButtonLeft");
    $(document).off("click", ".load_remark");
    $(document).off("click", ".load_month");
    $(document).off("keyup", ".month_qty");
    $(document).off("change", "#selection_type");
    $(document).off("change", "#crop_select");
    $(document).off("change", "#type_select");
    $(document).off("blur", ".consignment_no");
    $(document).off("keyup", ".trigger_class");
    $(document).off("keyup", ".sales_commission");
    $(document).off("keyup", ".sales_bonus");
    $(document).off("keyup", ".other_incentive");
    $(document).off("change", "#report_type");
    $(document).off("click", ".target_from_customer");
}

function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}

function print_rpt(){
    URL= base_url + "print_page/Print_a4_Eng.php?selLayer=PrintArea";
    day = new Date();
    id = day.getTime();
    eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=yes,scrollbars=yes ,location=0,statusbar=0 ,menubar=yes,resizable=1,width=880,height=600,left = 20,top = 50');");
}