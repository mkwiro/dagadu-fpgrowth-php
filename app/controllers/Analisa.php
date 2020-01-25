<?php 

class Analisa extends Controller{
    public function index()
    {
       $data['judul']= 'ANALISA FP-GROWTH';
       $this->view('templates/header', $data);
       $this->view('analisa/index');
       $this->view('templates/footer');
    }
}

?>
