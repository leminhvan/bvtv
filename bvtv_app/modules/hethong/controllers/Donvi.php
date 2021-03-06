<?php if (!defined('BASEPATH'))  exit('Không có quyền truy cập');

/**
 * Controller donvi
 * @created on : Monday, 28-May-2018 13:52:39
 * @author Le Minh Van
 * Copyright 2018
 */

class Donvi extends CI_Controller{
  
    public function __construct(){
        parent::__construct();         
        $this->load->model('donvi_model');
        $this->load->library(array('form_validation'));
         $this->load->library('counter_visitor_online');
        $this->load->helper(array('form', 'url','notify_helper'));
        $this->lang->load('donvi');

        if (!$this->ion_auth->logged_in()) {
            redirect('login');
        }
        $this->counter_visitor_online->UsersOnline();
    }
    

    /**
    * lấy tất cả row donvi
    *
    */
    public function index(){
        if($this->menu->check_phanquyen("donvi_1")){
            $this->data['donvis']   = $this->donvi_model->get_all();
            $this->template->js_add('var csrfName = "'.$this->security->get_csrf_token_name().'";
                                    var csrfHash = "'.$this->security->get_csrf_hash().'";
                                    function ajax_action(id, action) {
                                        var data ={"id": id, "action": action}; data[csrfName] = csrfHash;
                                        var agrs = {
                                            url : "'.base_url().'hethong/donvi/ajax_action", // gửi ajax đến file result.php
                                            type : "post", // chọn phương thức gửi là post
                                            dataType:"json", // dữ liệu trả về dạng text
                                            data : data,
                                            success : function (result){
                                                if(result.csrfName){ csrfName = result.csrfName; csrfHash = result.csrfHash;}
                                                if(result.data == 1 && result.action == "del"){
                                                    $("#data_id_"+id).remove();
                                                    swal({   
                                                        title: "Đã xóa!",   
                                                        timer: 2000,   
                                                        type: "success", 
                                                    });   
                                                }
                                                if(result.data == 0 && result.action == "del"){
                                                    swal("Lỗi", result.data, "error"); 
                                                }
                                                
                                                if(result.action == "detail"){
                                                    var re = result.data; 
                                                    var tempalte = "";
                                                    $.each(re ,function(key, val){
                                                        if(key != "hcgoc_id" ){
                                                            tempalte += "<tr>";
                                                            tempalte +=      "<td width=\'120\' class=\'font-weight-bold\'>" + key + "</td>";
                                                            tempalte +=      "<td > " + val +"</td>";
                                                            tempalte += "</tr>";
                                                        }
                                                    });
                                                    $(".result").html(tempalte);
                                                }  
                                            }
                                        };
                                        // Truyền object vào để gọi ajax
                                        $.ajax(agrs);
                                    }', "embed");
            
            if($this->session->flashdata('notify') != NULL){
                $this->template->js_add("notify('".$this->session->flashdata('notify')['message']."', '".$this->session->flashdata('notify')['type']."');",'embed');
            }
            $this->template->load('index', 'donvi/view',$this->data);
        }else{
            $this->session->set_flashdata('notify', notify('Không có quyền truy cập','warning'));
            redirect('dashboard');
        }
    }

    

    /**
    * Tạo mới data cho donvi
    *
    */
    public function add() {
        if($this->menu->check_phanquyen("donvi_2")){
            $this->data['donvi']           = $this->donvi_model->add();
            $this->data['action']            = 'hethong/donvi/save';
            

            //them js va css
            // $this->template->js_add('','embed');    
          
            $this->template->load('index', 'donvi/form',$this->data);
        }else{
            $this->session->set_flashdata('notify', notify('Không có quyền truy cập','warning'));
            redirect('hethong/donvi');
        }
    }

    /**
    * Sửa data cho donvi
    *
    */
    public function edit($id='') {
        if($this->menu->check_phanquyen("donvi_3")){
            if ($id != ''){
                $this->data['donvi']      = $this->donvi_model->get_one($id);
                $this->data['action']       = 'hethong/donvi/save/' . $id;           
               
                    
                $this->template->load('index', 'donvi/form',$this->data);
            } else{
                $this->session->set_flashdata('notify', notify('Không thấy data','info'));
                redirect(site_url('hethong/donvi'));
            }
        }else{
            $this->session->set_flashdata('notify', notify('Không có quyền truy cập','warning'));
            redirect('hethong/donvi');
        }
    }
    
    /**
    * Cập nhật database  donvi
    *
    */
    public function save($id =NULL){
        if($this->menu->check_phanquyen("donvi_3")){
            // validation config
            $config = array(
                      
                        array(
                            'field' => 'donvi_kyhieu',
                            'label' => lang('donvi_kyhieu'),
                            'rules' => 'trim|required'
                            ),
                        
                        array(
                            'field' => 'donvi_mota',
                            'label' => lang('donvi_mota'),
                            'rules' => 'trim'
                            ),
                                   
                      );
                
            // if id NULL then add new data
            if(!$id) {    
                      $this->form_validation->set_rules($config);
                      if ($this->form_validation->run() == TRUE) {
                          if ($this->input->post()) {
                              $this->donvi_model->save();
                              $this->session->set_flashdata('notify', notify('Thêm thành công','success'));
                              redirect('hethong/donvi');
                          }
                      }else{ // If validation incorrect 
                          $this->add();
                      }
             }
             else{ // Update data if Form Edit send Post and ID available
                    $this->form_validation->set_rules($config);
                    $this->form_validation->set_error_delimiters('<small class="form-text text-muted red-text">', '</small>');
                    if ($this->form_validation->run() == TRUE)  {
                        if ($this->input->post())  {
                            $this->donvi_model->update($id);
                            $this->session->set_flashdata('notify', notify('Update thành công','success'));
                            redirect('hethong/donvi');
                        }
                    } else{ // If validation incorrect 
                        $this->edit($id);
                    }
             }
         }else{
            $this->session->set_flashdata('notify', notify('Không có quyền truy cập','warning'));
            redirect('hethong/donvi');
        }
    }
    
    /**
    * Xóa donvi by ID
    *
    */

    public function ajax_action(){   
        $id = strip_tags($this->input->post('id', TRUE));
        $action = strip_tags($this->input->post('action', TRUE));
        $output = new stdClass;
        $output->csrfName = $this->security->get_csrf_token_name();
        $output->csrfHash = $this->security->get_csrf_hash();
        $output->data = 0; $output->action = $action;

        if ($this->ion_auth->logged_in() ) {//co the thay doi quyen
           if($this->menu->check_phanquyen("donvi_4")){
                if ($id>=0 AND $action == "del") {
                    $this->donvi_model->destroy($id);   $output->data = 1;        
                }
            }
            if($this->menu->check_phanquyen("donvi_1")){
                if ($id>=0 AND $action == "detail") {
                    $output->data = $this->donvi_model->get_one($id); $output->action = $action;       
                }
            }

        }
        echo json_encode($output);
    }
}
?>