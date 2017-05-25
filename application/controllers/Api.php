<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'form'));
        $this->load->model('content_model');
    }

    public function newp()
    {
        $content = $this->input->post();
        $callback = (isset($content['callback'])) ? $content['callback'] : null;

        $content['id'] = null;
        $content['id_parent'] = null;
        $content['md5'] = md5(microtime(true));
        $content['password'] = $this->genPass($content['md5'], 10);
        $content['create_at'] = date('Y/m/d H:i:s');

        $id = $this->content_model->insert($content);

        if (!is_null($callback)) {
            redirect($content['callback']);
        }

        echo $this->message("SUCCESS。 ".anchor(base_url($content['md5'])));
    }

    public function edit()
    {
        $content = $this->input->post();
        $md5 = $content['md5'];
        unset($content['id_parent']);
        $callback = (isset($content['callback'])) ? $content['callback'] : null;

        $content_db = $this->content_model->getWhere("md5='".$md5."'");
        if(count($content_db) == 0) redirect('/');
        $content_db = end($content_db);

        $content = array_merge($content_db, $content);

        $content['id'] = null;
        $content['content'] = $content['content'];
        $content['create_at'] = date('Y/m/d H:i:s');

        $id = $this->content_model->insert($content);

        if (!is_null($callback)) {
            redirect($content['callback']);
        }

        echo $this->message("SUCCESS。 ".anchor(base_url($content['md5'])));
    }

    public function fork()
    {
        $content = $this->input->post();
        $id_parent = $content['id_parent'];
        $callback = (isset($content['callback'])) ? $content['callback'] : null;

        $content_db = $this->content_model->getWhere("id='".$id_parent."'");
        if(count($content_db) == 0) redirect('/');
        $content_db = end($content_db);

        $content = array_merge($content_db, $content);

        $content['id'] = null;
        $content['id_parent'] = $id_parent;
        $content['content'] = $content['content'];
        $content['md5'] = md5(microtime(true));
        $content['password'] = $this->genPass($content['md5'], 10);
        $content['create_at'] = date('Y/m/d H:i:s');

        $id = $this->content_model->insert($content);

        if (!is_null($callback)) {
            redirect($content['callback']);
        }

        echo $this->message("SUCCESS。 ".anchor(base_url($content['md5'])));
    }

    private function genPass($seed, $len)
    {
        $pass = substr(md5($seed), 0, $len);
        return $pass;
    }

    private function message($msg)
    {
        $return = "<pre>";
        $return .= $msg;
        $return .= "</pre>";
        return $return;
    }
}