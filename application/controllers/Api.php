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
        var_dump($_POST);
        $callback = null;
        if(isset($content['callback'])) { 
            $callback = $content['callback'];
            unset($content['callback']);
        }

        $content['id'] = null;
        $content['id_parent'] = null;
        $content['md5'] = md5(microtime(true));
        $content['password'] = $this->genPass($content['md5'], 10);
        $content['create_at'] = date('Y/m/d H:i:s');

        $this->content_model->insert($content);

        if (!is_null($callback)) {
            redirect($callback.'?token='.$content['md5']);
        }

        $content_final = $this->content_model->getLastByWhere("md5 = '{$content['md5']}'");
        $this->json($content_final);
    }

    public function edit()
    {
        $content = $this->input->post();
        $md5 = $content['md5'];
        unset($content['id_parent']);

        if(!isset($content['password']) OR empty($content['password'])) {
            $this->error('Password is required'); die;
        }
        $password = $content['password'];
        unset($content['password']);

        $callback = null;
        if(isset($content['callback'])) { 
            $callback = $content['callback'];
            unset($content['callback']);
        }

        // Verificando se existe conteudo buscado
        $where = "md5='{$md5}'";
        $content_exists = $this->content_model->getWhere($where);
        if(count($content_exists) == 0) redirect('/');
        $content_exists = end($content_exists);

        // Verificando se senha é válida
        $where = "md5='{$md5}' AND password='{$password}'";
        $content_db = $this->content_model->getWhere($where);
        if(count($content_db) == 0) {
            $this->error('Password is wrog');
            die;
        }
        $content_db = end($content_db);

        $content = array_merge($content_db, $content);

        $content['id'] = null;
        $content['content'] = $content['content'];
        $content['create_at'] = date('Y/m/d H:i:s');

        $this->content_model->insert($content);

        if (!is_null($callback)) {
            redirect($callback.'?token='.$content['md5']);
        }

        $content_final = $this->content_model->getLastByWhere("md5 = '{$content['md5']}'");
        $this->json($content_final);
    }

    public function fork()
    {
        $content = $this->input->post();
        $id_parent = $content['id_parent'];

        $callback = null;
        if(isset($content['callback'])) { 
            $callback = $content['callback'];
            unset($content['callback']);
        }

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

        $this->content_model->insert($content);

        if (!is_null($callback)) {
            redirect($callback.'?token='.$content['md5']);
        }

        $content_final = $this->content_model->getLastByWhere("md5 = '{$content['md5']}'");
        $this->json($content_final);
    }

    public function get($md5)
    {
        $content_final = $this->content_model->getLastByWhere("md5 = '{$md5}'");
        unset($content_final->id);
        unset($content_final->id_parent);
        unset($content_final->password);
        $this->json($content_final);
    } 

    private function json($object)
    {
        header('Content-type:application/json');
        echo json_encode($object);
    }

    private function genPass($seed, $len)
    {
        $pass = substr(md5($seed), 0, $len);
        return $pass;
    }

    private function error($msg)
    {
        $this->json(
            array('error' => $msg)
        );
    }
}