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
        $password = trim($this->input->post('password'));

        $content['id'] = null;
        $content['id_parent'] = null;
        $content['md5'] = '';
        $content['password'] = (empty($password)) ? $this->genPass($content['md5'], 10) : $password;
        $content['create_at'] = date('Y/m/d H:i:s');

        // Adicionando código encurtado
        $last_id = $this->content_model->insert($content);
        $md5 = $this->shortUrl($last_id);
        $this->content_model->update($last_id, array('md5' => $md5));

        $content_final = $this->content_model->getLastByWhere("md5 = '{$md5}'");
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

        // Verificando se existe conteudo buscado
        $where = "md5='{$md5}'";
        $content_exists = $this->content_model->getWhere($where);
        if(count($content_exists) == 0) $this->error('Not Found');
        $content_exists = end($content_exists);

        // Verificando se senha é válida
        $where = "md5='{$md5}' AND password='{$password}'";
        $content_db = $this->content_model->getWhere($where);
        if(count($content_db) == 0) {
            $this->error('Password is wrog');
        }
        $content_db = end($content_db);

        $content = array_merge($content_db, $content);

        $content['id'] = null;
        $content['content'] = $content['content'];
        $content['create_at'] = date('Y/m/d H:i:s');

        $this->content_model->insert($content);

        $content_final = $this->content_model->getLastByWhere("md5 = '{$content['md5']}'");
        $this->json($content_final);
    }

    public function fork()
    {
        $content = $this->input->post();
        $password = trim($this->input->post('password'));
        $id_parent = $content['id_parent'];

        $content_db = $this->content_model->getWhere("id='".$id_parent."'");
        if(count($content_db) == 0) $this->error('Not Found');
        $content_db = end($content_db);

        $content = array_merge($content_db, $content);

        $content['id'] = null;
        $content['id_parent'] = $id_parent;
        $content['content'] = $content['content'];
        $content['md5'] = '';
        $content['password'] = (empty($password)) ? $this->genPass($content['md5'], 10) : $password;
        $content['create_at'] = date('Y/m/d H:i:s');

        // Adicionando código encurtado
        $last_id = $this->content_model->insert($content);
        $md5 = $this->shortUrl($last_id);
        $this->content_model->update($last_id, array('md5' => $md5));

        $content_final = $this->content_model->getLastByWhere("md5 = '{$md5}'");
        $this->json($content_final);
    }

    public function get($md5, $datetime = false)
    {
        $where = "md5 = '{$md5}'";
        if($datetime) {
            $datetime = str_replace('%20', ' ', $datetime);
            $where .= " AND create_at like '%{$datetime}%'";
        }
        $content_final = $this->content_model->getLastByWhere($where);
        if(count($content_final) == 0) $this->error('Not Found');
        unset($content_final->password);
        $this->json($content_final);
    } 

    public function log($md5)
    {
        $where = "md5 = '{$md5}'";
        $contents = $this->content_model->getWhere($where);
        if(count($contents) == 0) $this->error('Not Found');

        $return = array();
        foreach($contents as $content) {
            unset($content['password']);
            $return[] = $content;
        }

        $this->json($return);
    }

    public function children($id)
    {
        $where = "id_parent = '{$id}'";
        $contents = $this->content_model->getChildren($where);
        if(count($contents) == 0) $this->error('Not Found');

        $return = array();
        foreach($contents as $content) {
            unset($content['password']);
            $return[] = $content;
        }

        $this->json($return);
    }

    public function id($id)
    {
        $where = "id = '{$id}'";
        $content = $this->content_model->getLastByWhere($where);
        if(count($content) == 0) $this->error('Not Found');

        unset($content->password);

        $this->json($content);
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
        $this->json(array('error' => $msg)); die;
    }

    private function shortUrl($id)
    {
        $chars = '123456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ';

        $length = strlen($chars);

        $code = "";
        while ($id > $length - 1) {
            // determine the value of the next higher character
            // in the short code should be and prepend
            $code = $chars[(int)fmod($id, $length)] . $code;

            // reset $id to remaining value to be converted
            $id = floor($id / $length);
        }

        // remaining value of $id is less than the length of
        // $chars
        $code = $chars[(int)$id] . $code;

        return $code;
    }
}