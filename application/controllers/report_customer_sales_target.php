<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/root_controller.php';

class Report_customer_sales_target extends ROOT_Controller
{
    private  $message;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->load->model("report_customer_sales_target_model");
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
        $data['title'] = "Customer Sales Target Report";
        $data['years'] = Query_helper::get_info('ait_year',array('year_id value','year_name text'),array('del_status = 0'));
        $data['crops'] = $this->budget_common_model->get_ordered_crops();
        $data['divisions'] = $this->budget_common_model->get_division_by_access();

        $ajax['status'] = true;
        $ajax['content'][] = array("id" =>"#content", "html" => $this->load->view("report_customer_sales_target/search", $data, true));

        if($this->message)
        {
            $ajax['message'] = $this->message;
        }

        $ajax['page_url'] = base_url()."report_customer_sales_target/index/search/";
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
            $year = $this->input->post('year');
            $crop_id = $this->input->post('crop_id');
            $type_id = $this->input->post('type_id');
            $variety_id = $this->input->post('variety_id');
            $division = $this->input->post('division');
            $zone = $this->input->post('zone');
            $territory = $this->input->post('territory');
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
                $data['customers'] = $this->report_customer_sales_target_model->get_customers($division, $zone, $territory, $customer);
                $data['targets'] = $this->report_customer_sales_target_model->get_sales_target_info($year, $division, $zone, $territory, $customer, $crop_id, $type_id, $variety_id);
                $ajax['status'] = true;
                $ajax['content'][] = array("id" => "#report_list", "html" => $this->load->view("report_customer_sales_target/report", $data, true));
                $this->jsonReturn($ajax);
            }
        }
    }

    private function check_validation()
    {
        return true;
    }
}
