<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Counter_visitor extends CI_Model 
{
  private $count_restart;
    function __construct()
    {
        parent::__construct();
        
     }

    function count(){
        $count_all = 0;
        $temp_count = array();
        $month_data;
        date_default_timezone_set('Asia/Ho_Chi_Minh'); //set mui gio, khong se bi sai

        $query = $this->db->query("SELECT * FROM sys_counter WHERE counter_id = 1"); //lay data trong bang
        $temp = $query->result_array();
        foreach ($temp as $key => $value) {
          $count_all = $value['count'];
          $month_data = new DateTime($value['timer']);
        }
        

       if(!isset($_SESSION['hasVisited'])){
          $_SESSION['hasVisited']="yes";
          $count_all ++;

          //them vao database
          $data = array(
                'count' => $count_all,
            ); 
          //insert de tang ngay
          $this->db->insert('sys_counter', $data);
          unset($data);
          //luon giu total count o id 1
          $data = array(
                'count' => $count_all,
                'timer' => $value['timer'] //khong cap nhat timer
            ); 
          $this->db->where('counter_id', 1);
          $this->db->update('sys_counter', $data);
        }//end check sesion

        $day_now = date('d');
        $month_now = date('m');

        if($day_now == 1 AND $month_now != $month_data->format('m') AND $this->db->get('sys_counter')->num_rows() != 1){//neu ngay=1va bang chua xoa data thi xoa data
          $temp_count = $this->counter_restart($count_all);
        }else{
          $temp_count = $this->count_day_month();
        }

        return array($count_all, $temp_count[0], $temp_count[1]);
    }//end function   

    public function count_day_month()
    {
      $count_month = 0; $count_day = 0;
      $query = $this->db->query("SELECT * FROM sys_counter"); //lay data trong bang
       //dem so trong ngay
          $temp = $query->result_array();
//$a = array();
          $day_now = date('d');

          //neu $day_now = 1 thi xoa het va tao lai count_id 1
            $month_now = date('m');
            foreach ($temp as $key => $value) {
               //$diff = getdate($value['timer']);
              $diff = new DateTime($value['timer']);
              // array_push($a, $diff->format('d'));
              if($day_now == $diff->format('d') ) {
                $count_day++;
              }
              if($month_now == $diff->format('m') ) {
                $count_month++;
              }
            }
          return array($count_month , $count_day);
    }


    public function counter_restart($count_all)
    {
            date_default_timezone_set('Asia/Ho_Chi_Minh'); //set mui gio, khong se bi sai
            $this->db->empty_table('sys_counter');
            $this->db->query("ALTER TABLE sys_counter AUTO_INCREMENT 1");
            $data = array(
                'count' => $count_all,
            ); 
            //isert de tang ngay
            $this->db->insert('sys_counter', $data);

            //sau khi xoa, goi lai ham
            $count_month = 0; $count_day = 0;
            $query = $this->db->query("SELECT * FROM sys_counter"); //lay data trong bang
            $temp = $query->result_array();
            $day_now = date('d');
            $month_now = date('m');
            foreach ($temp as $key => $value) {
               //$diff = getdate($value['timer']);
              $diff = new DateTime($value['timer']);
              // array_push($a, $diff->format('d'));
              if($day_now == $diff->format('d') ) {
                $count_day++;
              }
              if($month_now == $diff->format('m') ) {
                $count_month++;
              }
            }
            return array($count_month , $count_day);
    }
}

