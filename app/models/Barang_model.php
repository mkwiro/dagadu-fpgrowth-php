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
        $result = $this->conn->query("SELECT b2_id, sing_b2, nama_b2 FROM b2 order by b2_id");
            while($row=mysqli_fetch_array($result)){
                $AllBarang[]=$row;
                
            }
        return $AllBarang;
        }
        }

?>
