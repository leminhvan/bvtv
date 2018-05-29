<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    var $data;

    public function index()	{
        $this->load->library(array('form_validation'));
        $this->load->helper(array('form', 'url','notify_helper'));
        

        if ($this->ion_auth->logged_in()) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('passwd', 'Password', 'trim|required');
        $this->form_validation->set_error_delimiters('<p class="c-red f-8">', '</p>');
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('login');
        }
        else {
            $user = $this->input->post('username');
            $remember = FALSE;
            if($this->input->post('remember') ==1 ){ $remember = TRUE; } 
            if ($this->ion_auth->login($user, $this->input->post('passwd'), $remember)) {
                $this->session->set_flashdata('notify', notify('Chào '.$user.'!','success'));
                redirect('dashboard');
            }
            else {
                //$this->session->set_flashdata('message', $this->ion_auth->errors());
                $this->session->set_flashdata('notify', notify('Thông tin đăng nhập sai','warning'));
                redirect(site_url('login'));
            }
        }
    }

    public function logout() {
        if ($this->ion_auth->logout()) {
            redirect('login');
        }
    }

   
}
