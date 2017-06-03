<?php
class Api_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->client = new GuzzleHttp\Client();
    }

    public function insert($post)
    {
        $response = $this->client->post('http://txt2sav.herokuapp.com/api/newp', array('form_params' => $post));
        $string = (string) $response->getBody();
        $content = (array) json_decode($string);
        return $content;
    }

    public function update($post)
    {
        $response = $this->client->post('http://txt2sav.herokuapp.com/api/edit', array('form_params' => $post));
        $string = (string) $response->getBody();
        $content = (array) json_decode($string);
        return $content;
    }

    public function getByMd5($md5)
    {
        $response = $this->client->request('GET', 'http://txt2sav.herokuapp.com/api/get/'.$md5);
        $string = (string) $response->getBody();
        return $content = (array) json_decode($string);
    }
}