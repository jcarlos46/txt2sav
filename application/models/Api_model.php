<?php
class Api_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->client = new GuzzleHttp\Client();
        $this->api = 'http://txt2sav.herokuapp.com/api/';
    }

    public function insert($post)
    {
        $response = $this->client->post($this->api.'newp', array('form_params' => $post));
        return $this->json($response);
    }

    public function update($post)
    {
        $response = $this->client->post($this->api.'edit', array('form_params' => $post));
        return $this->json($response);
    }

    public function fork($post)
    {
        $response = $this->client->post($this->api.'fork', array('form_params' => $post));
        return $this->json($response);
    }

    public function log($md5)
    {
        $response = $this->client->get($this->api.'log/'.$md5);
        return $this->json($response);
    }

    public function id($id)
    {
        $response = $this->client->get($this->api.'id/'.$id);
        return $this->json($response);
    }

    public function children($id)
    {
        $response = $this->client->get($this->api.'children/'.$id);
        return $this->json($response);
    }

    private function json(GuzzleHttp\Psr7\Response $response)
    {
        $string = (string) $response->getBody();
        $content = (array) json_decode($string);
        return $content;
    }

    public function get($md5)
    {
        $response = $this->client->get($this->api.'get/'.$md5);
        $string = (string) $response->getBody();
        return $content = (array) json_decode($string);
    }
}