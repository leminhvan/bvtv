<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        /* Load :: Common */
        $this->lang->load('users');
        $this->load->library('counter_visitor_online');
        $this->load->library(array('form_validation'));
        $this->load->helper(array('notify_helper', 'security'));
       if (!$this->ion_auth->logged_in()) {
            redirect('login');
        }
        $this->counter_visitor_online->UsersOnline();
    }


	public function index()
	{
        if($this->menu->check_phanquyen("users_1")){
            /* Get all users */
            $this->data['users'] = $this->ion_auth->users()->result();
            foreach ($this->data['users'] as $k => $user)
            {
                $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
            }

        	$this->template->js_add('var csrfName = "'.$this->security->get_csrf_token_name().'";
                                var csrfHash = "'.$this->security->get_csrf_hash().'";
                                function ajax_action(id, action) {
                                    var data ={"id": id, "action": action}; data[csrfName] = csrfHash;
                                    var agrs = {
                                        url : "users/ajax_action", // gửi ajax đến file result.php
                                        type : "post", // chọn phương thức gửi là post
                                        dataType:"json", // dữ liệu trả về dạng text
                                        data : data,
                                        success : function (result){
                                            if(result.csrfName){ csrfName = result.csrfName; csrfHash = result.csrfHash;}
                                            if(result.data == 1 && result.action == "del"){
                                                $("#data_id_"+id).remove();
                                                swal({   
                                                    title: "Đã xóa!",   
                                                    timer: 1000,   
                                                    type: "success", 
                                                });   
                                            }
                                            if(result.data == 0 && result.action == "del"){
                                                swal("Lỗi", result.data, "error"); 
                                            }

                                            if(result.action == "deactive"){
                                                var id_new = id.split("_"); id_new[1] = result.user.status; id_new = id_new.join("_");
                                                $("span[data-id="+id+"]").attr("data-id", id_new);
                                                if(result.user.status == 0){
                                                    swal({   
                                                        title: "Đã tạm ngừng " +result.user.account,   
                                                        timer: 1000,   
                                                        type: "success", 
                                                    });

                                                    $("span[data-id="+id_new+"]").text("Ngừng hoạt động").removeClass("label-success").addClass("label-default");
                                                }
                                                if(result.user.status == 1){
                                                    swal({   
                                                        title: "Kích hoạt " +result.user.account + " thành công!",   
                                                        timer: 1000,   
                                                        type: "success", 
                                                    });
                                                    $("span[data-id="+id_new+"]").text("Hoạt động").removeClass("label-default").addClass("label-success");;
                                                }
                                                 
                                            }
                                            
                                            if(result.action == "detail"){
                                                var re = result.data; 
                                                var tempalte = "";
                                                $.each(re ,function(key, val){console.log(val)
                                                    if(key == "username"|| key =="email" || key == "created_on" || key == "active"  || key == "first_name" || key =="gioitinh" || key =="sinhnhat" || key=="phone" || key=="avatar"){
                                                        if(key =="avatar"){
                                                            //val = "<img src=\''.base_url().'/uploads/"+re["username"]+"/avatar/"+ val + "\' width=\'50\' height=\'50\' alt=\'Chưa cập nhật\'/>";
                                                        }
                                                        if(key == "active"){
                                                            val = set_status(val);
                                                        }
                                                        if(key == "created_on"){
                                                            val = $.date(new Date(val*1000), "-");
                                                        }
                                                        tempalte += "<tr>";
                                                        tempalte +=      "<td width=\'120\' class=\'font-weight-bold\'>" + get_title(key) + "</td>";
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

                                    function get_title(key){
                                        var title_name = ["Tên tài khoản", "Email", "Ngày tạo", "Trạng thái", "Tên", "Giới tính", "Sinh nhật", "Điện thoại", "Ảnh đại diện"];
                                        var title_key = ["username", "email", "created_on", "active", "first_name", "gioitinh", "sinhnhat", "phone", "avatar"];
                                        var index = $.inArray(key , title_key);
                                        return title_name[index];
                                    }

                                    function set_status(val){
                                        var st = "Ngừng hoạt động";
                                        if(val == 1){st = "Hoạt động";}
                                        return st;
                                    }

                                }', "embed");
        
	        if($this->session->flashdata('notify') != NULL){
	            $this->template->js_add("notify('".$this->session->flashdata('notify')['message']."', '".$this->session->flashdata('notify')['type']."');",'embed');
	        }

            /* Load Template */
            $this->template->load('index', 'user/view', $this->data);
        }else{
        	$this->session->set_flashdata('notify', notify('Không có quyền truy cập','warning'));
        	redirect('dashboard');
        }
	}


	public function add()
	{
       if($this->menu->check_phanquyen("users_2")){
	        /* Variables */
	        $this->load->model('menu_model');
			$tables = $this->config->item('tables', 'ion_auth');
	        $this->template->js_add("assets/vendors/fileinput/fileinput.min.js","import");
			$this->template->js_add('assets/vendors//pwstrength/pwstrength.min.js','import');
			

			/* Validate form input */
			$this->form_validation->set_rules('first_name', 'lang:first_name', 'required');
			$this->form_validation->set_rules('username', 'lang:username', 'required');
			$this->form_validation->set_rules('goitinh', 'lang:goitinh');
			$this->form_validation->set_rules('email', 'lang:email', 'valid_email|is_unique['.$tables['users'].'.email]|required');
			$this->form_validation->set_rules('phone', 'lang:phone');
			$this->form_validation->set_rules('sinhnhat', 'lang:sinhnhat');
			$this->form_validation->set_rules('password', 'lang:password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');
			$this->form_validation->set_rules('password_confirm', 'lang:password_confirm', 'required|matches[password_confirm]');
			$this->form_validation->set_rules('avatar', 'lang:avatar', '');

			$temp =  $this->menu_model->get_all(); $re = array();
			foreach ($temp as $key => $value) {
				if($value['menu_parent_id'] == 0){
					$re[] = $value;
				}
			}
			$i = 0; $sub = array();
			foreach ($re as $key => $value) {
				foreach ($temp as $k => $v) {
					if($value['menu_id'] == $v['menu_parent_id']){
						$re[$i]['sub'] = TRUE;
						$sub[$value['menu_id']][] = $v;
					}
				}
				$i++;
			}
			$this->data['get_module'] = $re; $this->data['sub'] = $sub;

			$pq = $this->input->post('phanquyen');
				for ($i=0; $i < count($pq) ; $i++) { 
					if($pq[$i] == ""){
						array_splice($pq, $i, 1);
					}
				}
				$pq = explode(',', $pq);

			if ($this->form_validation->run() == TRUE && $pq != "")
			{

				$username = strtolower($this->input->post('username'));
				$email    = strtolower($this->input->post('email'));
				$password = $this->input->post('password');

				

				$additional_data = array(
					'first_name' => $this->input->post('first_name'),
					'gioitinh'  => $this->input->post('gioitinh'),
					'sinhnhat'    => $this->input->post('sinhnhat'),
					'phone'      => $this->input->post('phone'),
					'phanquyen'      => $pq,
					'avatar'      => $this->do_upload($username),
				);
			}
			if ($this->form_validation->run() == TRUE && $this->ion_auth->register($username, $password, $email, $additional_data)){
	            $this->session->set_flashdata('notify', notify(lang('actions_add_success'),'success'));
				redirect('hethong/users', 'refresh');
			}
			else{
	            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

				$this->data['username'] = array(
					'name'  => 'username',
					'id'    => 'username',
					'type'  => 'text',
	                'class' => 'form-control',
					'value' => $this->form_validation->set_value('username'),
				);
				$this->data['first_name'] = array(
					'name'  => 'first_name',
					'id'    => 'first_name',
					'type'  => 'text',
	                'class' => 'form-control',
					'value' => $this->form_validation->set_value('first_name'),
				);
				$this->data['gioitinh'] = $this->input->post('gioitinh') ? $this->input->post('gioitinh') : "";
				$this->data['email'] = array(
					'name'  => 'email',
					'id'    => 'email',
					'type'  => 'email',
	                'class' => 'form-control',
					'value' => $this->form_validation->set_value('email'),
				);
				$this->data['sinhnhat'] = array(
					'name'  => 'sinhnhat',
					'id'    => 'sinhnhat',
					'type'  => 'text',
					'autocomplete' => "off",
	                'class' => 'form-control date-picker',
					'value' => $this->form_validation->set_value('sinhnhat'),
				);
				$this->data['phone'] = array(
					'name'  => 'phone',
					'id'    => 'phone',
					'type'  => 'tel',
	                'pattern' => '^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$',
	                'class' => 'form-control',
					'value' => $this->form_validation->set_value('phone'),
				);
				$this->data['password'] = array(
					'name'  => 'password',
					'id'    => 'password',
					'type'  => 'password',
	                'class' => 'form-control',
					'value' => $this->form_validation->set_value('password'),
				);
				$this->data['password_confirm'] = array(
					'name'  => 'password_confirm',
					'id'    => 'password_confirm',
					'type'  => 'password',
	                'class' => 'form-control',
					'value' => $this->form_validation->set_value('password_confirm'),
				);
				$this->data['avatar'] = array(
								'name'  => 'avatar',
								'id'    => 'avatar',
								'type'  => 'file',
				                'class' => 'btn btn-default btn-file',
								'value' => $this->form_validation->set_value('avatar'),
							);
	            $this->form_validation->set_error_delimiters('<small class="form-text text-muted c-red">', '</small>');
	            /* Load Template */
	            $this->template->load('index', 'user/create', $this->data);
	        }
	    }else{
        	$this->session->set_flashdata('notify', notify('Không có quyền truy cập','warning'));
        	redirect('hethong/users');
        }
	}


	public function edit($id)
	{
        $id = (int) $id;

		if($this->menu->check_phanquyen("users_3")){
	        $this->template->js_add("assets/vendors/fileinput/fileinput.min.js","import");
	        $this->template->js_add('assets/vendors//pwstrength/pwstrength.min.js','import');
	        /* Data */
			$user          = $this->ion_auth->user($id)->row();
			$groups        = $this->ion_auth->groups()->result_array();
			$currentGroups = $this->ion_auth->get_users_groups($id)->result();
			
			$this->load->model('menu_model');
			$temp =  $this->menu_model->get_all(); $re = array();
			foreach ($temp as $key => $value) {
				if($value['menu_parent_id'] == 0){
					$re[] = $value;
				}
			}
			$i = 0; $sub = array();
			foreach ($re as $key => $value) {
				foreach ($temp as $k => $v) {
					if($value['menu_id'] == $v['menu_parent_id']){
						$re[$i]['sub'] = TRUE;
						$sub[$value['menu_id']][] = $v;
					}
				}
				$i++;
			}
			$this->data['get_module'] = $re; $this->data['sub'] = $sub;
			
			
			        
			/* Validate form input */
			$this->form_validation->set_rules('first_name', 'lang:edit_user_validation_fname_label', 'required');
			$this->form_validation->set_rules('username', 'lang:users_firstname', 'required');
			$this->form_validation->set_rules('gioitinh', 'lang:edit_user_validation_lname_label');
			$this->form_validation->set_rules('phone', 'lang:edit_user_validation_phone_label');
			$this->form_validation->set_rules('sinhnhat', 'lang:edit_user_validation_sinhnhat_label');
			$this->form_validation->set_rules('avatar', 'lang:users_avatar', '');

			if (isset($_POST) && ! empty($_POST))
			{
	            if ($this->_valid_csrf_nonce() === FALSE OR $id != $this->input->post('id'))
				{
					show_error($this->lang->line('error_csrf'));
				}

				$pq = $this->input->post('phanquyen');
				for ($i=0; $i < count($pq) ; $i++) { 
					if($pq[$i] == ""){
						array_splice($pq, $i, 1);
					}
				}
				$pq = implode(',', $pq);

	            if ($this->input->post('password'))
				{
					$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');
					$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required|matches[password]');
				}

				$check_upload = $this->do_upload($user->username, $user->avatar) ;
				if ($this->form_validation->run() == TRUE && $pq )
				{

					$data = array(
						'username' => $this->input->post('username'),
						'first_name' => $this->input->post('first_name'),
						'gioitinh'  => $this->input->post('gioitinh'),
						'sinhnhat'    => $this->input->post('sinhnhat'),
						'phone'      => $this->input->post('phone'),
						'phanquyen'      => $pq,
					);

					if($check_upload){
						$data['avatar'] = $check_upload;
					}
	                if ($this->input->post('password'))
					{
						$data['password'] = $this->input->post('password');
					}

	                if ($this->ion_auth->is_admin())
					{
	                    $groupData = $this->input->post('groups');

						if (isset($groupData) && !empty($groupData))
	                    {
							$this->ion_auth->remove_from_group('', $id);

							foreach ($groupData as $grp)
	                        {
								$this->ion_auth->add_to_group($grp, $id);
							}
						}
					}

	                if($this->ion_auth->update($user->id, $data))
				    {
	                    $this->session->set_flashdata('notify', notify('Cập nhật thành công','success'));

					    if ($this->ion_auth->is_admin())
						{

							redirect('hethong/users', 'refresh');
						}
						else
						{
							redirect('/', 'refresh');
						}
				    }
				    else
				    {
	                    $this->session->set_flashdata('notify', notify('Xảy ra lỗi','warning'));

					    if ($this->ion_auth->is_admin())
						{
							redirect('login', 'refresh');
						}
						else
						{
							redirect('/', 'refresh');
						}
				    }
				}


			}

			// display the edit user form
			$this->data['csrf'] = $this->_get_csrf_nonce();

			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			// pass the user to the view
			$this->data['user']          = $user;
			$this->data['groups']        = $groups;
			$this->data['currentGroups'] = $currentGroups;

			$this->data['username'] = array(
					'name'  => 'username',
					'id'    => 'username',
					'type'  => 'text',
	                'class' => 'form-control',
					'value' => $this->form_validation->set_value('username', $user->username),
				);
			$this->data['first_name'] = array(
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
	            'class' => 'form-control',
				'value' => $this->form_validation->set_value('first_name', $user->first_name)
			);
			$this->data['gioitinh'] = $this->input->post('gioitinh') ? $this->input->post('gioitinh') : $user->gioitinh;
			$this->data['sinhnhat'] = array(
				'name'  => 'sinhnhat',
				'id'    => 'sinhnhat',
				'type'  => 'text',
				'autocomplete' => "off",
	            'class' => 'form-control date-picker',
				'value' => $this->form_validation->set_value('sinhnhat', $user->sinhnhat)
			);
			$this->data['phone'] = array(
				'name'  => 'phone',
				'id'    => 'phone',
	            'type'  => 'tel',
	            'pattern' => '^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$',
	            'class' => 'form-control',
				'value' => $this->form_validation->set_value('phone', $user->phone)
			);
			$this->data['password'] = array(
				'name' => 'password',
				'id'   => 'password',
	            'class' => 'form-control',
				'type' => 'password'
			);
			$this->data['password_confirm'] = array(
				'name' => 'password_confirm',
				'id'   => 'password_confirm',
	            'class' => 'form-control',
				'type' => 'password'
			);
			$this->data['avatar'] = array(
								'name'  => 'avatar',
								'id'    => 'avatar',
								'type'  => 'file',
				                'class' => 'btn btn-default btn-file',
								'value' => $this->form_validation->set_value('avatar'),
							);

	        /* Load Template */
			$this->template->load('index', 'user/edit', $this->data);
		}else{
        	$this->session->set_flashdata('notify', notify('Không có quyền truy cập','warning'));
        	redirect('hethong/users');
        }
	}


    

    /**
    * Xóa users by ID
    *
    */

    public function ajax_action(){   
        $id = strip_tags($this->input->post('id', TRUE));
        $action = strip_tags($this->input->post('action', TRUE));
        $output = new stdClass;
        $output->csrfName = $this->security->get_csrf_token_name();
        $output->csrfHash = $this->security->get_csrf_hash();
        $output->data = 0; $output->action = $action;

        if ($this->ion_auth->logged_in()) {//co the thay doi quyen
           if($this->menu->check_phanquyen("users_4")){
	            if ($id>=0 AND $action == "del") {
	                $output->data = 1;  
	                $user   = $this->ion_auth->user($id)->row();
	                $this->ion_auth->delete_user($id);
	                $this->removeDirectory('./uploads/'.$user->username);
	            }
	        }
	        if($this->menu->check_phanquyen("users_1")){
	            if ($id>=0 AND $action == "detail") {
	                $output->data = $this->ion_auth->user($id)->row();        
	            }
	        }
	        if($this->menu->check_phanquyen("users_3")){
	            if ($id != "" AND $action == "deactive") {
	                $st = explode('_', $id); $re = FALSE; $user = FALSE;
	                if(count($st) >1){
	                    $id_ = (int) $st[0]; $status = $st[1];

	                    if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()){
	                        if($status == 1){
	                            $re = $this->ion_auth->deactivate($id_);
	                        }else{
	                            $re = $this->ion_auth->activate($id);
	                        }
	                        $a = $this->ion_auth->user($id_)->row();
	                        $user = array('account' =>$a->username, 'status' => $a->active, 'id' => $a->id);
	                    }
	                }
	                $output->user = $user;
	                $output->data = $re; 
	            }
	        }
            

        }

        if ($this->ion_auth->logged_in()){ //sua thong tin ca nhan thi chi can dang nhap
        	if (intval($id)>0 AND $action == "update") {
                $output->action = $action; 
                $str = $this->input->post('update_data', TRUE) ? strip_tags($this->input->post('update_data', TRUE)) : "";
                $data_ = json_decode($str, true);
                if($data_[0]['first_name'] !="" AND $data_[0]['email'] !="" ){
                	if($this->ion_auth->update(intval($id), $data_[0])){
	                    $output->data = 1;
	                } 
                }
            }
        }
        echo json_encode($output);

        
    }


	public function profile($id)
	{
        
        /* Data */
        $id = (int) $id;

        $this->data['user_info'] = $this->ion_auth->user($id)->result();
        foreach ($this->data['user_info'] as $k => $user)
        {
            $this->data['user_info'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
        }

        $this->template->js_add('var csrfName = "'.$this->security->get_csrf_token_name().'";
                                var csrfHash = "'.$this->security->get_csrf_hash().'";
                                function ajax_action(id, action, update_data) {
                                    var data ={"id": id, "action": action}; data[csrfName] = csrfHash; data["update_data"] = JSON.stringify(update_data);
                                    var agrs = {
                                        url : "'.base_url().'hethong/users/ajax_action", // gửi ajax đến file result.php
                                        type : "post", // chọn phương thức gửi là post
                                        dataType:"json", // dữ liệu trả về dạng text
                                        data : data,
                                        success : function (result){
                                            if(result.csrfName){ csrfName = result.csrfName; csrfHash = result.csrfHash;}
                                            if(result.action == "update" ){
                                                if(result.data == 1){
                                                	swal({   
	                                                    title: "Đã cập nhật!",   
	                                                    timer: 1000,   
	                                                    type: "success", 
	                                                });
                                                	$("#first_name_id").html(update_data[0]["first_name"]);
                                                	$("#sinhnhat_id").html(update_data[0]["sinhnhat"]);
                                                	$("#phone_id").html(update_data[0]["phone"]);
                                                	$("#email_id").html(update_data[0]["email"]);
                                                	$("#phone_id_").html(update_data[0]["phone"]);
                                                	$("#email_id_").html(update_data[0]["email"]);
                                                	$("#gioitinh_id").html(update_data[0]["gioitinh"]);
                                                	$(".pmb-block").removeClass("toggled");//quay lại
                                                }else{
                                                	swal({   
	                                                    title: "Có lỗi xảy ra, xin thử lại!",   
	                                                    timer: 1000,   
	                                                    type: "error", 
	                                                });
                                                }
                                            }
                                        }
                                    };
                                    // Truyền object vào để gọi ajax
                                    $.ajax(agrs);

                                }', "embed");
        $this->template->js_add('$("#profile_edit").on("click", function(){
        							var email = $("#email").val(); var first_name = $("#first_name").val(); console.log(email);
        							if(email != "" && first_name != ""){
        								var data = [{first_name: first_name, gioitinh: $("input:checked").val(), sinhnhat :$("#sinhnhat").val(), phone: $("#phone").val(), email: email}];
        								ajax_action('.$id.', "update", data);
        							}
    								if(email == ""){
    									$("#email").parent("div").parent("div").addClass("has-error");
    									$("#email").parent("div").next("small").show();
    								}else{
    									$("#email").parent("div").parent("div").removeClass("has-error");
        								$("#email").parent("div").next("small").hide();
    								}
    								if(first_name == ""){
    									$("#first_name").parent("div").parent("div").addClass("has-error");
    									$("#first_name").parent("div").next("small").show();
    								}else{
    									$("#first_name").parent("div").parent("div").removeClass("has-error");
        								$("#first_name").parent("div").next("small").hide();
    								}
                                });', "embed");
        /* Load Template */
		$this->template->load('index', 'user/profile', $this->data);
	}


	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}


	public function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE && $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function do_upload($username, $old_avatar = FALSE)
	{
		if (!empty($_FILES['avatar']['name']) AND $_FILES['avatar']['size'] > 1 ) {
			$dir = './uploads/'.$username.'/avatar/';
			if(!is_dir($dir)){
				mkdir($dir, 0777, true);
			}

			if($old_avatar){
				unlink($dir.$old_avatar);
			}
	        $config['upload_path'] = $dir;
	        $config['allowed_types'] = 'jpg|jpeg|png|gif';
	        $config['file_name'] = $_FILES['avatar']['name'];

	        $this->load->library('upload', $config);
	        $this->upload->initialize($config);
	        if (file_exists($dir.$config['file_name'])) {
	        	unlink($dir.$config['file_name']);
		        if ($this->upload->do_upload('avatar')) {
		          $uploadData = $this->upload->data();
		          return $uploadData['file_name'];
		        } 
	    	}else{
	    		if ($this->upload->do_upload('avatar')) {
		          $uploadData = $this->upload->data();
		          return $uploadData['file_name'];
		        } 
	    	}
	    }else{
	       return FALSE;
	    }
	}
	/**
	 * Remove the directory and its content (all files and subdirectories).
	 * @param string $dir the directory name
	 */
	function removeDirectory($path) {
        if(is_dir($path)){
    		$files = glob($path . '/*');
    		foreach ($files as $file) {
    			is_dir($file) ? $this->removeDirectory($file) : unlink($file);
    		}
    		rmdir($path);
        }
        //return;
	}
}

