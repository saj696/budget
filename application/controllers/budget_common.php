<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/root_controller.php';

class Budget_common extends ROOT_Controller
{
    private  $message;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->load->model("budget_common_model");
    }

    public function get_dropDown_territory_by_zone()
    {
        $zone_id = $this->input->post('zone_id');
        $territories = $this->budget_common_model->get_territory_by_zone($zone_id);

        foreach($territories as $territory)
        {
            $data[] = array('value'=>$territory['territory_id'], 'text'=>$territory['territory_name']);
        }

        $ajax['status'] = true;
        $ajax['content'][] = array("id"=>"#territory","html"=>$this->load->view("dropdown",array('drop_down_options'=>$data),true));
        $this->jsonReturn($ajax);
    }

    public function get_dropDown_customer_by_territory()
    {
        $zone_id = $this->input->post('zone_id');
        $territory_id = $this->input->post('territory_id');

        $customers = $this->budget_common_model->get_customer_by_territory($zone_id, $territory_id);

        foreach($customers as $customer)
        {
            $data[] = array('value'=>$customer['distributor_id'], 'text'=>$customer['distributor_name']);
        }

        $ajax['status'] = true;
        $ajax['content'][] = array("id"=>"#customer","html"=>$this->load->view("dropdown",array('drop_down_options'=>$data),true));
        $this->jsonReturn($ajax);
    }

    public function get_dropDown_type_by_crop()
    {
        $crop_id = $this->input->post('crop_id');

        $types = $this->budget_common_model->get_type_by_crop($crop_id);

        foreach($types as $type)
        {
            $data[] = array('value'=>$type['product_type_id'], 'text'=>$type['product_type']);
        }

        $ajax['status'] = true;
        $ajax['content'][] = array("id"=>"#type","html"=>$this->load->view("dropdown",array('drop_down_options'=>$data),true));
        $this->jsonReturn($ajax);
    }

    public function get_dropDown_variety_by_crop_type()
    {
        $crop_id = $this->input->post('crop_id');
        $type_id = $this->input->post('type_id');
        $year = $this->input->post('year');
        $customer_id = $this->input->post('customer');

        $data['varieties'] = $this->budget_common_model->get_variety_by_crop_type($crop_id, $type_id, $year, $customer_id);

        if(sizeof($data['varieties'])>0)
        {
            $data['title'] = 'Variety List';
            $ajax['status'] = true;
            $ajax['content'][]=array("id"=>"#customer_varieties","html"=>$this->load->view("customer_sales_target/variety_list",$data,true));
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['status'] = true;
            $ajax['content'][]=array("id"=>"#customer_varieties","html"=>"","",true);
            $this->jsonReturn($ajax);
        }
    }
}
