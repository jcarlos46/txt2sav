<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url'));
        $this->load->library(array('session'));
        $this->load->model('api_model');
        $this->lang = 'pt-br';
    }

    public function index()
    {
        $i18n = $this->i18n($this->lang);

        $data = array('i18n' => $i18n);
        $data['pagename'] = $i18n['HOME'];
        $data['password'] = $this->genPass(microtime(),10);
        $this->load->view('header');
        $this->load->view('index', $data);
    }

    public function newp()
    {
        $i18n = $this->i18n($this->lang);
        if(empty(trim($this->input->post('content')))) redirect('/');

        $post = $this->input->post();
        $content = $this->api_model->insert($post);
        $this->session->set_flashdata('password', $i18n['CODE'].': '.$content['password']);

        redirect('/'.$content['md5']);
    }

    public function content($md5, $create_at = '')
    {
        $content = $this->api_model->get($md5.'/'.$create_at);
        $i18n = $this->i18n($this->lang);

        $data = array('i18n' => $i18n);
        $data['pagename'] = $content['md5'];
        $data['md5'] = $content['md5'];
        $data['create_at'] = $content['create_at'];
        $content = $this->tab2nbsp(htmlentities($content['content']));
	$content = $this->scp2nbsp($content);
	$data['content'] = nl2br($content);

        $this->load->view('header');
        $this->load->view('head', $data);
        $this->load->view('content', $data);
    }

    public function edit($md5)
    {
        $content = $this->api_model->get($md5);
        $i18n = $this->i18n($this->lang);

        $data = array('i18n' => $i18n);
        $data['pagename'] = $i18n['EDIT'];
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

    public function fork($md5, $create_at = '')
    {
        $content = $this->api_model->get($md5.'/'.$create_at);

        $i18n = $this->i18n($this->lang);

        $data = array('i18n' => $i18n);
        $data['pagename'] = $i18n['FORK'];
        $data['action'] = 'forkp';
        $data['md5'] = $md5;
        $data['id_parent'] = $content['id'];
        $data['content'] = $content['content'];
        $data['password'] = $this->genPass(microtime(),10);

        $this->load->view('header');
        $this->load->view('edit', $data);
    }

    public function forkp()
    {
        $i18n = $this->i18n($this->lang);
        $post = $this->input->post();
        $content = $this->api_model->fork($post);
        $this->session->set_flashdata('password', $i18n['CODE'].': '.$content['password']);
        redirect('/'.$content['md5']);
    }

    public function log($md5, $create_at = '')
    {
        $content = $this->api_model->get($md5.'/'.$create_at);
        $contents = $this->api_model->log($md5);
        $parent = (!is_null($content['id_parent'])) ?
            $this->api_model->id($content['id_parent']) : array();
        $children = $this->api_model->children($content['id']);

        $i18n = $this->i18n($this->lang);

        $data = array('i18n' => $i18n);
        $data['pagename'] = $i18n['LOG'];
        $data['content'] = $content;
        $data['md5'] = $content['md5'];
        $data['create_at'] = $content['create_at'];
        $data['contents'] = $contents;
        $data['parent'] = $parent;
        $data['children'] = (!isset($children['error'])) ? $children : array();

        $this->load->view('header');
        $this->load->view('head', $data);
        $this->load->view('log', $data);
    }

    public function redirect()
    {
        if($md5 = $this->input->get('token')) {
            redirect(base_url($md5));
        }

        redirect('/');
    }

    private function i18n ($lang)
    {
        $i18n = array(
            'pt-br' => array(
                'PUBLISH' => 'Publicar',
                'HOME' => 'página inicial',
                'FORK' => 'clonar',
                'LOG' => 'histórico',
                'EDIT' => 'editar',
                'CODE' => 'Código de Edição'
            )
        );

        return $i18n[$lang];
    }

    private function genPass($seed, $len)
    {
        $pass = substr(md5($seed), 0, $len);
        return $pass;
    }

    private function tab2nbsp($str)
    {
        return str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $str); 
    }

    private function scp2nbsp($str)
    {
        return str_replace(" ", '&nbsp;', $str); 
    }
}
