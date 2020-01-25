<?php

class App
{
//property class untuk default
  protected $controller ='home';
  protected $method ='index';
  protected $params = [];

  function __construct()
  {
    $url = $this->parseURL();

//controller
    if (file_exists('../app/controllers/' . $url[0] . '.php')) { //cek apakah filenya ada di folder controller
      $this->controller = $url[0]; //array url [0] dijadikan controller
      unset($url[0]); //unset (kebalikan isset) array url [0]
    }

    //panggil file controllernya
    require '../app/controllers/' . $this->controller . '.php';
    $this->controller= new $this->controller;

    //method
    //cek method ada gak di url, kalau gak ada ke method default
    if (isset($url[1])) {
      if (method_exists($this->controller, $url[1])) { //cek didalam controller ada method gak? url[1]
        $this->method=$url[1];//array method di url dimasukkan dalam variable method
        unset($url[1]);
      }
    }

    //kelola Parameter yang diinput
    //kalau semua array yang no 0 dan 1 sudah dikelola trus masih sisa brati sebagai parameter
    if (!empty($url )) {
      $this->params = array_values($url);
    }

//jalankan controller $ method, serta kirimkan parameter jika ada
call_user_func_array([$this->controller, $this->method], $this->params);

  }

//menangkap url yang diinput di url
  public function parseURL()
  {
    if (isset($_GET['url'])) {
      $url = rtrim($_GET['url'], '/'); //menghapus tanda / di akhir url
      $url = filter_var($url, FILTER_SANITIZE_URL);//memfilter url dari karakter aneh (hack)
      $url = explode('/', $url); //memecah url yang diinput dengan delimiter / dan menjadikannya array
      return $url;
    }
  }
}
?>