<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/root_controller.php';

class Packing_material_setup extends ROOT_Controller
{
    private  $message;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->load->model("packing_material_setup_model");
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
        $data['varieties'] = $this->packing_material_setup_model->get_variety_info();
        $data['title']="Packing Material & Sticker Setup";
        $ajax['page_url']=base_url()."packing_material_setup/index/add";

        $ajax['status']=true;
        $ajax['content'][]=array("id"=>"#content","html"=>$this->load->view("packing_material_setup/add_edit",$data,true));

        $this->jsonReturn($ajax);
    }

    public function budget_save()
    {
        $user = User_helper::get_user();
        $data = Array();
        $time = time();

        if(!$this->check_validation())
        {
            $ajax['status']=false;
            $ajax['message']=$this->lang->line('NO_VALID_INPUT');
            $this->jsonReturn($ajax);
        }
        else
        {
            $this->db->trans_start();  //DB Transaction Handle START

            $setupPost = $this->input->post('setup');

            foreach($setupPost as $variety=>$detail)
            {
                unset($data['packing_status']);
                unset($data['sticker_status']);

                foreach($detail as $field=>$value)
                {
                    $data[$field] = $value;
                }

                if($this->packing_material_setup_model->check_variety_existence($variety))
                {
                    $data['modified_by'] = $user->user_id;
                    $data['modification_date'] = $time;
                    Query_helper::update('budget_packing_material_setup',$data,array("variety_id ='$variety'"));
                }
                else
                {
                    $variety_data = $this->packing_material_setup_model->get_variety_detail($variety);
                    $data['crop_id'] = $variety_data['crop_id'];
                    $data['product_type_id'] = $variety_data['product_type_id'];
                    $data['variety_id'] = $variety_data['varriety_id'];
                    $data['variety_name'] = $variety_data['varriety_name'];
                    $data['created_by'] = $user->user_id;
                    $data['creation_date'] = $time;
                    Query_helper::add('budget_packing_material_setup',$data);
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

            $this->budget_add_edit();//this is similar like redirect
        }
    }

    private function check_validation()
    {
        return true;
    }

}
