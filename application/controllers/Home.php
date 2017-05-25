<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url'));
        $this->content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras laoreet, magna non malesuada feugiat, dolor purus suscipit nisi, et lacinia tellus ex non elit. Mauris pellentesque sodales dolor, ut scelerisque lacus imperdiet vel. Sed auctor libero in elementum condimentum. Sed ut placerat orci, id varius diam. Proin dignissim tortor ut scelerisque ornare. Nullam et massa eget lectus convallis tempus. Sed pulvinar nunc ut lacinia luctus. Nullam mollis sit amet leo in consectetur. Sed nec purus est. Pellentesque vitae quam sollicitudin, commodo est et, faucibus ex. Ut dignissim maximus porttitor. Nulla interdum ligula faucibus dui porta dignissim.";
    }

    public function index()
    {
        $this->load->view('header');
        $this->load->view('index');
    }

    public function content($md5)
    {
        // if(!$this->exists($md5)) redirect('/');

        $data['md5'] = $md5;
        $data['content'] = $this->content;
        $this->load->view('header');
        $this->load->view('head', $data);
        $this->load->view('content', $data);
    }

    public function edit($md5)
    {
        // if(!$this->exists($md5)) redirect('/');
        /*if (is_null($this->input->post('pass'))
        OR !empty($this->input->post('pass'))
        OR !$this->validEdit($this->input->post('pass'))) {
            redirect($md5);
        }*/
        $data['action'] = 'api/edit';
        $data['md5'] = $md5;
        $data['content'] = $this->content; 
        $this->load->view('header');
        $this->load->view('edit', $data);
    }

    public function fork($md5)
    {
        // if(!$this->exists($md5)) redirect('/');

        $data['action'] = 'api/fork';
        $data['md5'] = $md5;
        $data['content'] = $this->content; 
        $this->load->view('header');
        $this->load->view('edit', $data);
    }

    public function validEdit($pass)
    {
        return true;
    }
}