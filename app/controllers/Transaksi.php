<?php 

class Transaksi extends Controller{
    
    public function index(){
        $data['judul']= 'TRANSAKSI';
        $this->view('templates/header', $data);
        $this->view('transaksi/index');
        $this->view('templates/footer');

    }
}

?>
