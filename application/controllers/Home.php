<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url'));
        $this->load->library(array('session'));
        $this->load->model('api_model');
    }

    public function index()
    {
        $data = array();
        $this->load->view('header');
        $this->load->view('index', $data);
    }

    public function newp()
    {
        $post = $this->input->post();
        $content = $this->api_model->insert($post);
        $this->session->set_flashdata('password', 'Edit code: ' . $content['password']);
        redirect('/'.$content['md5']);
    }

    public function content($md5)
    {
        $content = $this->api_model->getByMd5($md5);

        $data['md5'] = $content['md5'];
        $data['content'] = nl2br(htmlentities($content['content']));

        $this->load->view('header');
        $this->load->view('head', $data);
        $this->load->view('content', $data);
    }

    public function edit($md5)
    {
        $content = $this->api_model->getByMd5($md5);

        $data['action'] = 'editp';
        $data['md5'] = $md5;
        $data['content'] = $content['content']; 
        $data['id_parent'] = $content['id_parent']; 

        $this->load->view('header');
        $this->load->view('edit', $data);
    }

    public function editp()
    {
        $post = $this->input->post();
        $content = $this->api_model->update($post);
        redirect('/'.$content['md5']);
    }

    public function fork($md5)
    {
        $content = $this->api_model->getByMd5($md5);

        $data['action'] = 'forkp';
        $data['md5'] = $md5;
        $data['id_parent'] = $content['id'];
        $data['content'] = $content['content'];
        $data['callback'] = base_url('redirect');

        $this->load->view('header');
        $this->load->view('edit', $data);
    }

    public function forkp()
    {
        $post = $this->input->post();
        $content = $this->api_model->fork($post);
        $this->session->set_flashdata('password', 'Edit code: ' . $content['password']);
        redirect('/'.$content['md5']);
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