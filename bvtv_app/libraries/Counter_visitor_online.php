<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Counter_visitor_online extends CI_Model 
{

   var $timeoutSeconds = 5;//Đây là thời gian refresh tính bằng giây
    var $numberOfUsers = 1;//mặc định số người truy cập(ít nhất là 1 người đang xem - chính là bạn đó ^_^)
    function __construct()
    {
        parent::__construct();
        $this->timeoutSeconds = $this->config->item('user_expire', 'ion_auth');
     }

   
 
   function UsersOnline() {
        $this->refresh();                                                                            
    }
 
   function getnumberOfUsers() {
        return $this->numberOfUsers;
    }
 
 
    function refresh() {
      date_default_timezone_set('Asia/Ho_Chi_Minh'); //set mui gio, khong se bi sai
        $currentTime = date("Y-m-d H:i:s");
        $allow=true; 
        $query=$this->db->get('sys_useronline');//$data->query("SELECT ip FROM sys_usersonline");
        $row = $query->result_array(); 
        if(count($row) > 0){
        	foreach ($row as $key => $value) { //so sanh session id
                  if(session_id() == $value["session"]){ 
                    $allow = false;
                      $data = array(
                          'timestamp' => $currentTime,
                      );
                      $this->db->where('session', $value["session"]);
                      $this->db->update('sys_useronline', $data);
                    //}
                  }   else{
                  	$timeout = strtotime($currentTime) - strtotime($value["timestamp"]);
		              if ($timeout > $this->timeoutSeconds) { //neu con trong thoi han thi up date thoi gian
		              	$this->db->where('session', $value["session"]);
		                $this->db->delete('sys_useronline');
		              }
                  }
                  
            }
        }
            
      if($allow){//neu chua co ip thi save
        $data = array(
            'timestamp' => $currentTime,
            'ip' => $this->get_client_ip(),
            'session' => session_id()
        );
        $this->db->insert('sys_useronline', $data);
      }
          $num = $this->db->get('sys_useronline')->num_rows();
          //if($num > 0){
            $this->numberOfUsers  = $num;//$num;
          //}
          
      }

      // Function to get the client IP address
      function get_client_ip() {
          $ipaddress = '';
          if (isset($_SERVER['HTTP_CLIENT_IP']))
              $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
          else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
              $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
          else if(isset($_SERVER['HTTP_X_FORWARDED']))
              $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
          else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
              $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
          else if(isset($_SERVER['HTTP_FORWARDED']))
              $ipaddress = $_SERVER['HTTP_FORWARDED'];
          else if(isset($_SERVER['REMOTE_ADDR']))
              $ipaddress = $_SERVER['REMOTE_ADDR'];
          else
              $ipaddress = 'UNKNOWN';
          return $ipaddress;
      }
}

