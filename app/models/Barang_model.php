<?php 

class Barang_model{

public $conn;
public $servername = "localhost";
public $username = "root";
public $password = "";
public $database = "test";

        public function __construct(){
        $this->conn= new mysqli($this->servername, $this->username, $this->password, $this->database);
        if (mysqli_connect_error()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
         }
    }
    public function AllBarang()
    {
        $result = $this->conn->query("SELECT * FROM tb_barang_fpgrowth order by id_brg");
            while($row=mysqli_fetch_array($result)){
                $AllBarang[]=$row; 
            }
        return $AllBarang;
        }
        }

?>
