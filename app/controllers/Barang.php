<?php 
class Barang extends Controller{
public function index()
{
    $data['judul']= 'DATA BARANG';
    $data['barang']= $this->model('Barang_model')->AllBarang();
    $this->view('templates/header', $data);
    $this->view('barang/index', $data);
    $this->view('templates/footer');
}
}

?>
