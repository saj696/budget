<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/root_controller.php';

class Ti_sales_target extends ROOT_Controller
{
    private  $message;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->load->model("ti_sales_target_model");
    }

    public function index($task="add",$id=0)
    {
        if($task=="add" || $task=="edit")
        {
            $this->budget_add_edit($id);
        }
        elseif($task=="save")
        {
            $this->budget_save();
        }
        else
        {
            $this->budget_add_edit($id);
        }
    }

    public function budget_add_edit()
    {
        $user = User_helper::get_user();
        $data['years'] = Query_helper::get_info('ait_year',array('year_id value','year_name text'),array('del_status = 0'));
        $data['crops'] = $this->budget_common_model->get_ordered_crops();
        $data['types'] = $this->budget_common_model->get_ordered_crop_types();
        $data['varieties'] = $this->budget_common_model->get_ordered_varieties();

        $data['title']="TI Sales Target";
        $ajax['page_url']=base_url()."ti_sales_target/index/add";

        $ajax['status']=true;
        $ajax['content'][]=array("id"=>"#content","html"=>$this->load->view("ti_sales_target/add_edit",$data,true));

        $this->jsonReturn($ajax);
    }

    public function budget_save()
    {
        $user = User_helper::get_user();
        $data = array();
        $notification_data = array();
        $time = time();

        if(!$this->check_budgeting_time_validation())
        {
            $ajax['status']=false;
            $ajax['message']=$this->lang->line("BUDGETING_TIME_OVER");
            $this->jsonReturn($ajax);
        }
        else
        {
            if(!$this->check_validation())
            {
                $ajax['status']=false;
                $ajax['message']=$this->lang->line("NO_VALID_INPUT");
                $this->jsonReturn($ajax);
            }
            else
            {
                $this->db->trans_start();  //DB Transaction Handle START

                $year = $this->input->post('year');
                $varietyPost = $this->input->post('variety');
                $data['year'] = $year;
                $data['division_id'] = $user->division_id;
                $data['zone_id'] = $user->zone_id;
                $data['territory_id'] = $user->territory_id;

                if($this->input->post('forward') && $this->input->post('forward')==1)
                {
                    $notification_data['year'] = $year;
                    $notification_data['sending_division'] = $user->division_id;
                    $notification_data['sending_zone'] = $user->zone_id;
                    $notification_data['sending_territory'] = $user->territory_id;
                    $notification_data['receiving_division'] = $user->division_id;
                    $notification_data['receiving_zone'] = $user->zone_id;
                    $notification_data['direction'] = $this->config->item('direction_up');
                    $notification_data['created_by'] = $user->user_id;
                    $notification_data['creation_date'] = $time;

                    if($this->ti_sales_target_model->check_ti_notification_existence($year))
                    {
                        $id = $this->ti_sales_target_model->check_ti_notification_existence($year);
                        $notification_data['is_action_taken'] = 0;
                        Query_helper::update('budget_sales_target_notification',$notification_data,array("id ='$id'"));
                    }
                    else
                    {
                        Query_helper::add('budget_sales_target_notification',$notification_data);
                    }
                }

                foreach($varietyPost as $crop_id=>$varietyDetail)
                {
                    $data['crop_id'] = $crop_id;
                    foreach($varietyDetail as $type_id=>$varietyInfo)
                    {
                        $data['type_id'] = $type_id;
                        foreach($varietyInfo as $variety_id=>$detail)
                        {
                            $data['variety_id'] = $variety_id;
                            foreach($detail as $key=>$value)
                            {
                                $data[$key] = $value;
                            }

                            $data['created_by'] = $user->user_id;
                            $data['creation_date'] = $time;

                            if($data['budgeted_quantity']>0)
                            {
                                if($this->ti_sales_target_model->check_territory_variety_existence($year, $variety_id))
                                {
                                    $id = $this->ti_sales_target_model->get_territory_variety_id($year, $variety_id);
                                    Query_helper::update('budget_sales_target',$data,array("id ='$id'"));
                                }
                                else
                                {
                                    Query_helper::add('budget_sales_target',$data);
                                }
                            }
                        }
                    }
                }

                $this->db->trans_complete();   //DB Transaction Handle END

                if ($this->db->trans_status() === TRUE)
                {
                    $ajax['status'] = true;
                    $ajax['message']=$this->lang->line("MSG_CREATE_SUCCESS");
                    $this->jsonReturn($ajax);
                }
                else
                {
                    $ajax['status'] = true;
                    $ajax['message']=$this->lang->line("MSG_NOT_SAVED_SUCCESS");
                    $this->jsonReturn($ajax);
                }

                $this->budget_add_edit();   //this is similar like redirect
            }
        }
    }

    private function check_validation()
    {
        $varietyPost = $this->input->post('variety');
        $validation = array();

        foreach($varietyPost as $crop_id=>$varietyDetail)
        {
            foreach($varietyDetail as $type_id=>$varietyInfo)
            {
                foreach($varietyInfo as $variety_id=>$detail)
                {
                    foreach($detail as $key=>$value)
                    {
                        $data[$key] = $value;
                    }

                    if($data['budgeted_quantity']>0)
                    {
                        $validation[] = $data['budgeted_quantity'];
                    }
                }
            }
        }

        if(sizeof($validation)>0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    private function check_budgeting_time_validation()
    {
        $year = $this->input->post('year');

        if($this->ti_sales_target_model->check_ti_budgeting_time_existence($year))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_variety_detail()
    {
        $user = User_helper::get_user();
        $year = $this->input->post('year');
        $crop_id = $this->input->post('crop_id');
        $type_id = $this->input->post('type_id');
        $user_territory = $user->territory_id;

        $data['year'] = $year;
        $data['distributors'] = Query_helper::get_info('ait_distributor_info',array('distributor_id value','distributor_name text'),array("territory_id ='$user_territory'",'del_status =0'));
        $data['varieties'] = $this->ti_sales_target_model->get_varieties_by_crop_type($crop_id, $type_id);

        if(isset($data['year']) && sizeof($data['varieties'])>0)
        {
            $ajax['status'] = true;
            $ajax['content'][]=array("id"=>'#variety_quantity',"html"=>$this->load->view("ti_sales_target/variety",$data,true));
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['status'] = true;
            $ajax['content'][]=array("id"=>'#variety_quantity',"html"=>"","",true);
            $this->jsonReturn($ajax);
        }
    }

    public function get_dropDown_type_by_crop()
    {
        $crop_id = $this->input->post('crop_id');
        $types = $this->budget_common_model->get_type_by_crop($crop_id);

        $data = array();
        if(is_array($types) && sizeof($types)>0)
        {
            foreach($types as $type)
            {
                $data[] = array('value'=>$type['product_type_id'], 'text'=>$type['product_type']);
            }
        }

        $ajax['status'] = true;
        $ajax['content'][] = array("id"=>'#type_select',"html"=>$this->load->view("dropdown",array('drop_down_options'=>$data),true));
        $this->jsonReturn($ajax);
    }

}
