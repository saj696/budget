<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/root_controller.php';

class Report_quantity_allocation extends ROOT_Controller
{
    private  $message;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->load->model("report_quantity_allocation_model");
    }

    public function index($task="search",$id=0)
    {
        if($task=="search")
        {
            $this->budget_search();
        }
        elseif($task=="report")
        {
            $this->budget_report();
        }
        else
        {
            $this->budget_search();
        }
    }

    private function budget_search()
    {
        $data['title'] = "Quantity Allocation Report";
        $data['years'] = Query_helper::get_info('ait_year',array('year_id value','year_name text'),array('del_status = 0'));
        $data['crops'] = $this->budget_common_model->get_ordered_crops();
        $data['divisions'] = $this->budget_common_model->get_division_by_access();

        $ajax['status'] = true;
        $ajax['content'][] = array("id" =>"#content", "html" => $this->load->view("report_quantity_allocation/search", $data, true));

        if($this->message)
        {
            $ajax['message'] = $this->message;
        }

        $ajax['page_url'] = base_url()."report_quantity_allocation/index/search/";
        $this->jsonReturn($ajax);
    }

    private function budget_report()
    {
        if(!$this->check_validation())
        {
            $ajax['status']=false;
            $ajax['message']=$this->message;
            $this->jsonReturn($ajax);
        }
        else
        {
            $user = User_helper::get_user();
            $selection_type = $this->input->post('selection_type');
            $year = $this->input->post('year');
            $from_month = $this->input->post('from_month');
            $to_month = $this->input->post('to_month');
            $crop_id = $this->input->post('crop_id');
            $type_id = $this->input->post('type_id');
            $variety_id = $this->input->post('variety_id');
            $division = $this->input->post('division');
            $zone = $this->input->post('zone');
            $territory = $this->input->post('territory');
            $district = $this->input->post('district');
            $customer = $this->input->post('customer');

            if($user->budget_group==$this->config->item('user_group_division') && strlen($division)<2)
            {
                $ajax['status']=false;
                $ajax['message']=$this->lang->line('LABEL_SELECT_DIVISION');
                $this->jsonReturn($ajax);
            }
            elseif($user->budget_group==$this->config->item('user_group_zone') && strlen($zone)<2)
            {
                $ajax['status']=false;
                $ajax['message']=$this->lang->line('LABEL_SELECT_ZONE');
                $this->jsonReturn($ajax);
            }
            elseif($user->budget_group==$this->config->item('user_group_territory') && strlen($territory)<2)
            {
                $ajax['status']=false;
                $ajax['message']=$this->lang->line('LABEL_SELECT_TERRITORY');
                $this->jsonReturn($ajax);
            }
            else
            {
                $data['title'] = "Quantity Allocation Report";
                $data['allocations'] = $this->report_quantity_allocation_model->get_quantity_allocation_info($selection_type, $year, $from_month, $to_month, $division, $zone, $territory, $district, $customer, $crop_id, $type_id, $variety_id);
                $ajax['status'] = true;
                $ajax['content'][] = array("id" => "#report_list", "html" => $this->load->view("report_quantity_allocation/report", $data, true));
                $this->jsonReturn($ajax);
            }
        }
    }

    private function check_validation()
    {
        return true;
    }
}
