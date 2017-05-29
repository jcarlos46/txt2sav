<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url'));
        $this->load->model('content_model');
        $this->content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras laoreet, magna non malesuada feugiat, dolor purus suscipit nisi, et lacinia tellus ex non elit. Mauris pellentesque sodales dolor, ut scelerisque lacus imperdiet vel. Sed auctor libero in elementum condimentum. Sed ut placerat orci, id varius diam. Proin dignissim tortor ut scelerisque ornare. Nullam et massa eget lectus convallis tempus. Sed pulvinar nunc ut lacinia luctus. Nullam mollis sit amet leo in consectetur. Sed nec purus est. Pellentesque vitae quam sollicitudin, commodo est et, faucibus ex. Ut dignissim maximus porttitor. Nulla interdum ligula faucibus dui porta dignissim.";
    }

    public function index()
    {
        $data = array();
        $data['callback'] = base_url('redirect');
        $this->load->view('header');
        $this->load->view('index', $data);
    }

    public function content($md5)
    {
        $content = $this->content_model->getWhere("md5='".$md5."'");
        if(count($content) == 0) redirect('/');
        $content = end($content);

        $data['md5'] = $content['md5'];
        $data['content'] = $content['content'];

        $this->load->view('header');
        $this->load->view('head', $data);
        $this->load->view('content', $data);
    }

    public function edit($md5)
    {
        $content = $this->content_model->getWhere("md5='".$md5."'");
        if(count($content) == 0) redirect('/');
        $content = end($content);

        $data['action'] = 'api/edit';
        $data['md5'] = $md5;
        $data['content'] = $content['content']; 
        $data['id_parent'] = $content['id_parent']; 
        $data['callback'] = base_url('redirect');

        $this->load->view('header');
        $this->load->view('edit', $data);
    }

    public function fork($md5)
    {
        $content = $this->content_model->getWhere("md5='".$md5."'");
        if(count($content) == 0) redirect('/');
        $content = end($content);

        $data['action'] = 'api/fork';
        $data['md5'] = $md5;
        $data['id_parent'] = $content['id'];
        $data['content'] = $content['content'];
        $data['callback'] = base_url('redirect');

        $this->load->view('header');
        $this->load->view('edit', $data);
    }

    public function redirect()
    {
        if($md5 = $this->input->get('token')) {
            redirect(base_url($md5));
        }

        redirect('/');
    }

    public function validEdit($pass)
    {
        return true;
    }
}