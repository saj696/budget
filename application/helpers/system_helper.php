<?php
class System_helper
{
    public static function pagination_config($base_url, $total_rows, $segment)
    {
        $CI =& get_instance();

        $config["base_url"] = $base_url;
        $config["total_rows"] = $total_rows;
        $config["per_page"] = $CI->config->item('view_per_page');
        $config['num_links'] = $CI->config->item('links_per_page');
        $config['use_page_numbers'] = true;
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['uri_segment'] = $segment;
        return $config;
    }

    public static function display_date($date)
    {
        return date('d-M-Y',$date);
    }

    public static function get_time($str)
    {
        $time=strtotime($str);
        if($time===false)
        {
            return 0;
        }
        else
        {
            return $time;
        }
    }

    public static function upload_file($save_dir="images")
    {
        $CI = & get_instance();
        $CI->load->library('upload');
        $config=array();
        $config['upload_path'] = FCPATH.$save_dir;
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = $CI->config->item("max_file_size");
        $config['overwrite'] = false;
        $config['remove_spaces'] = true;

        $uploaded_files=array();
        foreach ($_FILES as $key => $value)
        {
            if(strlen($value['name'])>0)
            {
                $CI->upload->initialize($config);
                if (!$CI->upload->do_upload($key))
                {
                    $uploaded_files[$key]=array("status"=>false,"message"=>$value['name'].': '.$CI->upload->display_errors());
                }
                else
                {
                    $uploaded_files[$key]=array("status"=>true,"info"=>$CI->upload->data());
                }
            }
        }

        return $uploaded_files;
    }

    public static function get_pdf($html)
    {
        include(FCPATH."mpdf60/mpdf.php");
        $mpdf=new mPDF();
        $mpdf->SetDisplayMode('fullpage');

        $mpdf->WriteHTML($html);
        $mpdf->Output();
        exit;
    }

    public static function get_parent_id_of_task($controller_name)
    {
        $CI =& get_instance();
        $CI->db->from('budget_task');
        $CI->db->where('controller',$controller_name);
        $result=$CI->db->get()->row_array();
        if($result)
        {
            return $result['parent'];
        }
        else
        {
            return 0;
        }
    }

    public static function get_current_year()
    {
        $CI = & get_instance();

        $CI->db->from('ait_year ay');
        $CI->db->select('ay.*');
        $CI->db->where("DATE_FORMAT(start_date,'%Y-%m-%d') <=",date('Y-m-d'));
        $CI->db->where("DATE_FORMAT(end_date,'%Y-%m-%d') >=",date('Y-m-d'));
        $result = $CI->db->get()->row_array();
        return $result['year_id'];
    }

    public static function get_prediction_years($year_id)
    {
        $CI = & get_instance();
        $prediction_array = array();
        $prediction_config = $CI->config->item('prediction_years');

        $CI->db->from('ait_year year');
        $CI->db->select('year.year_name');
        $CI->db->where('year.year_id',$year_id);
        $result = $CI->db->get()->row_array();
        $year = $result['year_name'];

        for($i=0; $i<$prediction_config; $i++)
        {
            $year++;
            $CI->db->from('ait_year year');
            $CI->db->select('year.year_id');
            $CI->db->where('year.year_name',$year);
            $result = $CI->db->get()->row_array();
            if(sizeof($result)>0 && strlen($result['year_id'])>1)
            {
                $prediction_array[] = array('year_id'=>$result['year_id'], 'year_name'=>$year);
            }
        }
        return $prediction_array;
    }

    public static function get_year_name($year_id)
    {
        $CI = & get_instance();
        $CI->db->from('ait_year year');
        $CI->db->select('year.year_name');
        $CI->db->where('year.year_id',$year_id);
        $result = $CI->db->get()->row_array();
        $year_name = $result['year_name'];
        return $year_name;
    }
}