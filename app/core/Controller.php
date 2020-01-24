<?php
class Controller  
{
    //menangkap method view
    public function view($view, $data=[])
    {
        //memanggil view yang ditangkap
        require '../app/views/' .$view . '.php';
    }
    public function model($model)
    {
        require '../app/models/' .$model . '.php';
        //instansiasi model sebagai class
        return new $model;
    }
}
