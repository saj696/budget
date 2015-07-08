<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Assign_target_to_customer_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
    }

    public function get_variety_info()
    {
        $user = User_helper::get_user();
        $this->db->select('avi.varriety_id, avi.varriety_name');
        $this->db->select('aci.crop_name');
        $this->db->select('apt.product_type');
        $this->db->from('ait_varriety_info avi');

        $this->db->join("ait_crop_info aci","aci.crop_id = avi.crop_id","LEFT");
        $this->db->join("ait_product_type apt","apt.product_type_id = avi.product_type_id","LEFT");
        $this->db->order_by('avi.order_variety, avi.crop_id, avi.product_type_id');
        $this->db->where('avi.status', 'Active');
        $results = $this->db->get()->result_array();
        return $results;
    }

}