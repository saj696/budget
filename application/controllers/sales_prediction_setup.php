<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/root_controller.php';

class Sales_prediction_setup extends ROOT_Controller
{
    private  $message;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->load->model("sales_prediction_setup_model");
    }

    public function index($task="list", $year_id=0)
    {
        if($task=="list")
        {
            $this->budget_list();
        }
        elseif($task=="add" || $task=="edit")
        {
            $this->budget_add_edit($year_id);
        }
        elseif($task=="save")
        {
            $this->budget_save();
        }
        else
        {
            $this->budget_list();
        }
    }

    public function budget_list($page=0)
    {
        $config = System_helper::pagination_config(base_url() . "sales_prediction_setup/index/list/",$this->sales_prediction_setup_model->get_total_prediction_years(),4);
        $this->pagination->initialize($config);
        $data["links"] = $this->pagination->create_links();

        if($page>0)
        {
            $page=$page-1;
        }

        $data['predictions'] = $this->sales_prediction_setup_model->get_prediction_year_info($page);
        $data['title']="Sales Prediction Setup List";

        $ajax['status']=true;
        $ajax['content'][]=array("id"=>"#content","html"=>$this->load->view("sales_prediction_setup/list",$data,true));

        if($this->message)
        {
            $ajax['message']=$this->message;
        }

        $ajax['page_url']=base_url()."sales_prediction_setup/index/list/".($page+1);
        $this->jsonReturn($ajax);
    }

    public function budget_add_edit($year)
    {
        $data['years'] = Query_helper::get_info('ait_year',array('year_id value','year_name text'),array('del_status = 0'));
        $data['crops'] = $this->budget_common_model->get_ordered_crops();
        $data['types'] = $this->budget_common_model->get_ordered_crop_types();

        if(strlen($year)>1)
        {
            $data['predictions'] = $this->sales_prediction_setup_model->get_prediction_detail($year);
            $data['title'] = "Edit Sales Prediction Setup";
            $ajax['page_url']=base_url()."sales_prediction_setup/index/edit/";
        }
        else
        {
            $data['predictions'] = array();
            $data['title'] = "Sales Prediction Setup";
            $ajax['page_url'] = base_url()."sales_prediction_setup/index/add";
        }

        $ajax['status'] = true;
        $ajax['content'][] = array("id"=>"#content","html"=>$this->load->view("sales_prediction_setup/add_edit",$data,true));

        $this->jsonReturn($ajax);
    }

    public function budget_save()
    {
        $user = User_helper::get_user();
        $data = array();
        $setup_data = array();
        $year_id = $this->input->post('year_id');
        $time = time();

        if(!$this->check_validation())
        {
            $ajax['status']=false;
            $ajax['message']=$this->message;
            $this->jsonReturn($ajax);
        }
        else
        {
            $this->db->trans_start();  //DB Transaction Handle START

            $crop_type_Post = $this->input->post('prediction');
            $detail_post = $this->input->post('detail');
            $year = $this->input->post('year');

            $setup_data['year'] = $year;
            $setup_data['ho_and_general_exp'] = $this->input->post('ho_and_general_exp');
            $setup_data['marketing'] = $this->input->post('marketing');
            $setup_data['finance_cost'] = $this->input->post('finance_cost');

            if(strlen($year_id)>1)
            {
                $setup_data['modified_by'] = $user->user_id;
                $setup_data['modification_date'] = $time;
                Query_helper::update('budget_sales_prediction_setup', $setup_data, array("year ='$year_id'"));

                // Initial update
                $update_status = array('status'=>0);
                Query_helper::update('budget_sales_prediction',$update_status,array("year ='$year'", "prediction_phase =".$this->config->item('prediction_phase_initial')));
                $existing_varieties = $this->sales_prediction_setup_model->get_existing_varieties($year);

                foreach($crop_type_Post as $cropTypeKey=>$crop_type)
                {
                    foreach($detail_post as $detailKey=>$details)
                    {
                        if($detailKey==$cropTypeKey)
                        {
                            $data['year'] = $year;
                            $data['prediction_phase'] = $this->config->item('prediction_phase_initial');
                            $data['targeted_profit'] = $this->input->post('targeted_profit');
                            $data['sales_commission'] = $this->input->post('sales_commission');
                            $data['sales_bonus'] = $this->input->post('sales_bonus');
                            $data['other_incentive'] = $this->input->post('other_incentive');
                            $data['crop_id'] = $crop_type['crop'];
                            $data['type_id'] = $crop_type['type'];

                            foreach($details as $variety_id=>$detail_type)
                            {
                                $data['variety_id'] = $variety_id;

                                foreach($detail_type as $type=>$amount)
                                {
                                    $data[$type] = $amount;
                                }

                                if(in_array($variety_id, $existing_varieties))
                                {
                                    $crop_id = $data['crop_id'];
                                    $type_id = $data['type_id'];

                                    $data['modified_by'] = $user->user_id;
                                    $data['modification_date'] = $time;
                                    $data['status'] = 1;
                                    Query_helper::update('budget_sales_prediction', $data, array("year ='$year'", "crop_id ='$crop_id'", "type_id ='$type_id'", "variety_id ='$variety_id'", "prediction_phase =".$this->config->item('prediction_phase_initial')));
                                }
                                else
                                {
                                    $data['created_by'] = $user->user_id;
                                    $data['creation_date'] = $time;
                                    Query_helper::add('budget_sales_prediction', $data);
                                }
                            }
                        }
                    }
                }
            }
            else
            {
                $setup_data['created_by'] = $user->user_id;
                $setup_data['creation_date'] = $time;
                Query_helper::add('budget_sales_prediction_setup', $setup_data);

                foreach($crop_type_Post as $cropTypeKey=>$crop_type)
                {
                    foreach($detail_post as $detailKey=>$details)
                    {
                        if($detailKey==$cropTypeKey)
                        {
                            $data['year'] = $year;
                            $data['prediction_phase'] = $this->config->item('prediction_phase_initial');
                            $data['targeted_profit'] = $this->input->post('targeted_profit');
                            $data['sales_commission'] = $this->input->post('sales_commission');
                            $data['sales_bonus'] = $this->input->post('sales_bonus');
                            $data['other_incentive'] = $this->input->post('other_incentive');
                            $data['crop_id'] = $crop_type['crop'];
                            $data['type_id'] = $crop_type['type'];

                            foreach($details as $variety_id=>$detail_type)
                            {
                                $data['variety_id'] = $variety_id;
                                $data['created_by'] = $user->user_id;
                                $data['creation_date'] = $time;

                                foreach($detail_type as $type=>$amount)
                                {
                                    $data[$type] = $amount;
                                }

                                Query_helper::add('budget_sales_prediction', $data);
                            }
                        }
                    }
                }
            }

            $this->db->trans_complete();   //DB Transaction Handle END

            if ($this->db->trans_status() === TRUE)
            {
                $this->message=$this->lang->line("MSG_CREATE_SUCCESS");
            }
            else
            {
                $this->message=$this->lang->line("MSG_NOT_SAVED_SUCCESS");
            }

            $this->budget_list();//this is similar like redirect
        }
    }

    private function check_validation()
    {
        $valid=true;
        $crop_type_Post = $this->input->post('prediction');
        $detail_post = $this->input->post('detail');
        $year = $this->input->post('year');

        if(is_array($crop_type_Post) && sizeof($crop_type_Post)>0)
        {
            foreach($crop_type_Post as $crop_type)
            {
                $crop_type_array[] = array('crop'=>$crop_type['crop'], 'type'=>$crop_type['type']);
            }

            $new_arr = array_unique($crop_type_array, SORT_REGULAR);

            if($crop_type_array != $new_arr)
            {
                $valid=false;
                $this->message .= $this->lang->line("DUPLICATE_CROP_TYPE").'<br>';
            }
        }

        if(!$crop_type_Post || !$detail_post)
        {
            $valid=false;
            $this->message .= $this->lang->line("SET_PREDICTION").'<br>';
        }

        if(!$year)
        {
            $valid=false;
            $this->message .= $this->lang->line("SELECT_YEAR").'<br>';
        }

        if(strlen($this->input->post('year_id'))==1)
        {
            $existence = $this->sales_prediction_setup_model->check_sales_prediction_existence($year);
            if($existence)
            {
                $valid=false;
                $this->message .= $this->lang->line("PREDICTION_SETUP_ALREADY_DONE").'<br>';
            }
        }

        return $valid;
    }

    public function get_varieties_by_crop_type()
    {
        $crop_id = $this->input->post('crop_id');
        $type_id = $this->input->post('type_id');
        $current_id = $this->input->post('current_id');

        $data['varieties'] = $this->sales_prediction_setup_model->get_variety_by_crop_type($crop_id, $type_id);

        if(sizeof($data['varieties'])>0)
        {
            $data['serial'] = $current_id;
            $data['title'] = 'Variety List';
            $ajax['status'] = true;
            $ajax['content'][]=array("id"=>'#variety'.$current_id,"html"=>$this->load->view("sales_prediction_setup/variety_list",$data,true));
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['status'] = true;
            $ajax['content'][]=array("id"=>'#variety'.$current_id,"html"=>"<label class='label label-danger'>".$this->lang->line('NO_VARIETY_EXIST')."</label>","",true);
            $this->jsonReturn($ajax);
        }
    }

    public function check_sales_prediction_this_year()
    {
        $year = $this->input->post('year');
        $existence = $this->sales_prediction_setup_model->check_sales_prediction_existence($year);

        if($existence)
        {
            $ajax['status'] = false;
            $ajax['message'] = $this->lang->line("PREDICTION_SETUP_ALREADY_DONE");
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['status'] = true;
            $this->jsonReturn($ajax);
        }
    }

}
