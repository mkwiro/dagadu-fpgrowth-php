<?php
//koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$database = "test";
        $con= new mysqli($servername, $username, $password, $database);
        if (mysqli_connect_error()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
         }
//uasort — Sort an array with a user-defined comparison function and maintain index association
// keep the order when two members compare as equal
function stable_uasort(&$array, $cmp_function) { 
    if(count($array) < 2) {
        return;
    }
    $halfway = count($array) / 2;
    $array1 = array_slice($array, 0, $halfway, TRUE);
    $array2 = array_slice($array, $halfway, NULL, TRUE);
  
    stable_uasort($array1, $cmp_function);
    stable_uasort($array2, $cmp_function);
    if(call_user_func($cmp_function, end($array1), reset($array2)) < 1) {
        $array = $array1 + $array2;
        return;
    }
    $array = array();
    reset($array1);
    reset($array2);
    while(current($array1) && current($array2)) { 
        if(call_user_func($cmp_function, current($array1), current($array2)) < 1) {
            $array[key($array1)] = current($array1);
            next($array1);
        } else {
            $array[key($array2)] = current($array2);
            next($array2);
        }
    }
    while(current($array1)) { 
        $array[key($array1)] = current($array1);
        next($array1);
    }
    while(current($array2)) { 
        $array[key($array2)] = current($array2);
        next($array2);
    }
    return;
}
 //cmp function
function cmp($a, $b) {
    if($a[0] == $b[0]) {
        return 0;
    }
    return ($a[0] > $b[0]) ? -1 : 1;
}

#VARIABEL-VARIABEL YANG DIGUNAKAN
$link_list=BASEURL.'analisa';
$minimum_support='';
$minimum_confidence='';
$jumlah_transaksi='';
$error='';
$daftar_tabulasi='';
$daftar_frekuensi='';
$daftar_support='';
$daftar_itemset_priority='';
$daftar_conditional_pattern_base='';
$daftar_conditional_fptree='';
$daftar_frequent_itemset='';
$daftar_rule='';
$disable='';
$daritgl='';
$sampaitgl='';  

#MENGHITUNG JUMLAH DATA
$q=mysqli_query($con, "SELECT count(*) AS jml FROM tb_transaksi_fpgrowth");
$h=mysqli_fetch_array($q);
$jumlah_data=(int)$h['jml'];

//cek sudah ada data yang ada di dalam database? untuk jumbotron; 
$q=mysqli_query($con, "SELECT tgl FROM tb_transaksi_fpgrowth ORDER BY tgl ASC LIMIT 1");
$daritgl=mysqli_fetch_array($q);
$q=mysqli_query($con, "SELECT tgl FROM tb_transaksi_fpgrowth ORDER BY tgl DESC LIMIT 1");
$sampaitgl=mysqli_fetch_array($q);
//cek sudah ada data yang ada di dalam database? kalau ada, dari dan sampainya dimatikan 

#TOMBOL RESET
 //Jika ditekan tombol reset; mereset semua data yang ada dalam tabel
if(isset($_POST['reset'])){
    $disable="";
    $q=mysqli_query($con, "DELETE FROM tb_barang_fpgrowth");
    $q2=mysqli_query($con, "DELETE FROM tb_transaksi_fpgrowth");
    $q3=mysqli_query($con, "DELETE FROM tb_transaksi_detail");
}
#TOMBOL PROSES
 //jika isset PROSES (submit) di klik maka"
if(isset($_POST['submit'])){
    $disable="disabled";
  $q=mysqli_query($con, "INSERT INTO tb_barang_fpgrowth(id_brg, kode_barang, nama_barang) SELECT b2_id, sing_b2, nama_b2 FROM b2");
  $q2=mysqli_query($con, "INSERT INTO tb_transaksi_fpgrowth(kode_transaksi, tgl) SELECT DISTINCT transaksi_id, tgl FROM tb_stok WHERE tb_stok.tgl BETWEEN     CAST('".$_POST['dari']."' AS DATE) AND CAST('".$_POST['sampai']."' AS DATE)");
  $q3=mysqli_query($con, "INSERT INTO tb_transaksi_detail(id_transaksi, id_brg)
      SELECT tb_transaksi_fpgrowth.id_transaksi, LPAD(b2.b2_id, 2, 0)
      FROM tb_stok INNER JOIN tb_transaksi_fpgrowth ON tb_stok.transaksi_id = tb_transaksi_fpgrowth.kode_transaksi
      JOIN tb_barang ON tb_stok.kode_barang=tb_barang.kode_barang 
      JOIN b2 ON b2.b2_id=tb_barang.b2");
    //menghitung jumlah transaksi, disimpan pada variabel $jumlah_transaksi
    $q=mysqli_query($con, "SELECT count(*) AS jml FROM tb_transaksi_fpgrowth");
    $h=mysqli_fetch_array($q);
    $jumlah_transaksi=(int)$h['jml'];


    $daritgl=$_POST['dari'];
    $sampaitgl=$_POST['sampai'];
	$time_before = microtime(true); //untuk menghitung waktu
	$minimum_support=$_POST['minimum_support']; //mengambil nilai minimum support yang sudah diinputkan 
	$nilai_minimum_support=round(($minimum_support/100)*$jumlah_transaksi, 2); //round berfungsi untuk pembulatan desimal dengan parameternya 2 digit dibelakang koma
	$minimum_confidence=$_POST['minimum_confidence']; //mengambil nilai minimum confidence yang sudah diinputkan 
	$nilai_minimum_confidence=round($minimum_confidence/100, 2); //round berfungsi untuk pembulatan desimal dengan parameternya 2 digit dibelakang koma
    
    //jika pada form inputan terdapat yang kosong atau tidak diisi maka akan muncul pesan error.
	if(empty($minimum_support) or empty($minimum_confidence)){
		$error='Lengkapi form di bawah ini.';
	} else {
         //menghitung jumlah dari tb_transaksi_fpgrowth disimpan dalam variabel $total_transaksi
		$q=mysqli_query($con, "SELECT count(*) AS jml FROM tb_transaksi_fpgrowth");
        $h=mysqli_fetch_array($q);
        $total_transaksi=(int)$h['jml'];

        //apabila $jumlah_transaksi > $total_transaksi maka menampilkan pesan error
		if($jumlah_transaksi > $total_transaksi){
			$error='Jumlah transaksi berbeda';
		} else {
            #MEMBUAT DAFTAR TRANSAKSI
            $transaksi=array();
            $kode_transaksi=array();

            $q="SELECT * FROM tb_transaksi_fpgrowth ORDER BY id_transaksi LIMIT 0,".$jumlah_transaksi;
            $q=mysqli_query($con, $q);
            while($h=mysqli_fetch_array($q)){
                //id_transaksi dimasukkan ke array $transaksi
                //kode_transaksi dimasukkan ke array $kode_transaksi beracuan pada id_transaksi
                $transaksi[]=$h['id_transaksi'];
                $kode_transaksi[$h['id_transaksi']]=$h['kode_transaksi'];
                
			}
            #Penghapusan Duplikasi Itemset dalam 1 transaksi
			$transaksi_barang=array();
			for($i=0;$i<count($transaksi);$i++){
                $transaksi_barang[$transaksi[$i]]=array();
                //menampilkan distinct id barang dari tabel transaksi_detail, dengan id dari perulangan for, diurutkan dengan nama barang di tabel tb_barang
				$q=mysqli_query($con, "SELECT DISTINCT tb_barang_fpgrowth.id_brg FROM tb_transaksi_detail INNER JOIN tb_barang_fpgrowth ON 
                tb_transaksi_detail.id_brg=tb_barang_fpgrowth.id_brg WHERE tb_transaksi_detail.id_transaksi='".$transaksi[$i]."' ORDER BY tb_barang_fpgrowth.nama_barang");
                //id-barnag dimasukkan dalam array $transaksi_barang di group oleh id_transaksi
				while($h=mysqli_fetch_array($q)){
                    $transaksi_barang[$transaksi[$i]][]=$h['id_brg'];
				}
            }
            
            #membuat header itemset pada tabulasi databarang dan transaksi
            $barang=array();
            $kode_barang=array();
            $nama_barang=array();
            //menampilkan distinct seluruh data tb_barang_fpgrowth yang ditemukan dalam transaksi dengan acuan dimana (IN) id-id transaksi yang ada dalam tabel
            //diurutkan oleh nama_barang 
            $q="SELECT DISTINCT tb_barang_fpgrowth.* FROM tb_transaksi_detail INNER JOIN tb_barang_fpgrowth ON 
            tb_transaksi_detail.id_brg=tb_barang_fpgrowth.id_brg WHERE tb_transaksi_detail.id_transaksi IN (".implode(',', $transaksi).") ORDER BY tb_barang_fpgrowth.nama_barang";
            $q=mysqli_query($con, $q);
            while($h=mysqli_fetch_array($q)){
                $barang[]=$h['id_brg'];
                $kode_barang[$h['id_brg']]=$h['kode_barang'];
                $nama_barang[$h['id_brg']]=$h['nama_barang'];
            }

            //Tampilan Tabulasi data barang dan transaksi
            //dan Penghitungan Frekuensi Barang
            $frekuensi=array();
            for($i=0;$i<count($transaksi);$i++){
                $id_transaksi=$transaksi[$i];
                $daftar_tabulasi.='
                <tr>
                <td>'.htmlspecialchars($kode_transaksi[$id_transaksi]).'</td>';
                 //htmlspesial  fungsi mengkonversi beberapa karakter yang telah ditetapkan untuk entitas HTML
                //fetch data barang dengan perulangan dalam setiap transaksi melalui header itemset tabulasi,
                for($ii=0;$ii<count($barang);$ii++){
                //if cek jika ada $barang[$ii] di dalam $transaksi_barang[$id_transaksi] maka
                    if(in_array($barang[$ii], $transaksi_barang[$id_transaksi])){
                        //apabila barang sudah ditemukan dengan if diatas maka 
                        if(!isset($frekuensi[$barang[$ii]])){$frekuensi[$barang[$ii]]=0;}
                        //FREKUENSI BARANG ditambah +1
                        $frekuensi[$barang[$ii]]=$frekuensi[$barang[$ii]]+1;
                        //status tanda 1 terbeli
                        $sts='1';
                    }else{
                        //tidak ada tanda dalam tabel
                        $sts='';
                    }
                    $daftar_tabulasi.='<td class="text-center">'.$sts.'</td>';
                }
                $daftar_tabulasi.='</tr>';
            }
            #ITEMSET FREQUENT
            //variabel $frekuensi_sort_all array untuk menampung frekuensi setiap itemset
            $frekuensi_sort_all=array(); 
            for($i=0;$i<count($barang);$i++){
                //array $frekuensi_sort_all diisi frekuensi barang dan id barang
                $frekuensi_sort_all[]=array($frekuensi[$barang[$i]], $barang[$i]);
            }
            //pemanggilan fungsi stable_uasort sebagai pengurutan asosiatif array 
            stable_uasort($frekuensi_sort_all, 'cmp'); 
            //array_values() returns all the values from the array and indexes the array numerically.
            //ekstrak value dari array asosiatif $frekuensi_sort_all menjadi array numerik
            $frekuensi_sort_all = array_values($frekuensi_sort_all);

            #ITEMSET SUPPORT 
            //variabel $frekuensi_sort sebagai variabel ITEMSET SUPPORT
            $frekuensi_sort=array();
            for($i=0;$i<count($frekuensi_sort_all);$i++){
                //masih inget kan isinya index-index $frekuensi_sort_all?
                //index 0 berisi jumlah frequensi
                //index 1 berisi id_barang
                //nah, if index 0 (jumlah frequensi) pada array $frekuensi_sort_all >= $nilai_minimum_support
                if($frekuensi_sort_all[$i][0] >= $nilai_minimum_support){
                    //ITEMSET FREQUENT dimasukkan ke dalam array ITEMSET SUPPORT, $frekuensi_sort
                    $frekuensi_sort[]=$frekuensi_sort_all[$i];
                }
            }

            //ITEMSET PRIORITY
            //variabel $transaksi_barang_priority sebagai variabel ITEMSET PRIORITY
            $transaksi_barang_priority=array();
            for($i=0; $i<count($transaksi); $i++){
                //membuat array dari $transaksi_barang_priority[$transaksi[$i]], paham gak?
                //jadi jumlah array $transaksi_barang_priority sesuai dengan jumlah transaksi
                $transaksi_barang_priority[$transaksi[$i]]=array();
                for($ii=0; $ii<count($frekuensi_sort); $ii++){
                    //masih inget variabel $transaksi_barang? isinya distinct barang dalam setiap transaksi
                    //dicek if in_array $transaksi_barang[$transaksi[$i]] terdapat $frekuensi_sort[$ii][1], index [1] isinya barang ITEMSET SUPPORT
                    //maka barang tersebut dimasukkan dalam variabel ITEMSET SUPPORT
                    if(in_array($frekuensi_sort[$ii][1], $transaksi_barang[$transaksi[$i]])){
                        $transaksi_barang_priority[$transaksi[$i]][]=$frekuensi_sort[$ii][1];
                    }
                }
            }
            #TAMPILAN ITEMSET FREQUENT
            $no=0;
            for($i=0;$i<count($frekuensi_sort_all);$i++){
                $id_brg=$frekuensi_sort_all[$i][1];
                $no++;
                $daftar_frekuensi.='
                <tr>
                <td class="text-center">'.$no.'</td>
                <td>'.htmlspecialchars($nama_barang[$id_brg]).'</td>
                <td class="text-center">'.$frekuensi_sort_all[$i][0].'</td>
                </tr>
                ';
            }
            #TAMPILAN ITEMSET SUPPORT
            $no=0;
            for($i=0;$i<count($frekuensi_sort);$i++){
                $id_brg=$frekuensi_sort[$i][1];
                $no++;
                $daftar_support.='
                <tr>
                <td class="text-center">'.$no.'</td>
                <td>'.htmlspecialchars($nama_barang[$id_brg]).'</td>
                <td class="text-center">'.$frekuensi_sort[$i][0].'</td>
                </tr>
                ';
            }
            #TAMPILAN ITEMSET PRIORITY
            $itemset_priority=array();
            for($i=0;$i<count($transaksi);$i++){
                $tmp=array();
                $tmp2=array();
                for($ii=0;$ii<count($transaksi_barang_priority[$transaksi[$i]]);$ii++){
                    //variabel $nama_barang berisi barang yang masuk dalam transaksi
                    //mengisi array tmp[] dengan nama barang ITEMSET SUPPORT pada setiap transaksi
                    //mengisi array tmp2[] dengan id barang ITEMSET SUPPORT pada setiap transaksi
                    $tmp[]=$nama_barang[$transaksi_barang_priority[$transaksi[$i]][$ii]];
                    $tmp2[]=$transaksi_barang_priority[$transaksi[$i]][$ii];
                }
                //array muldimens $itemset_priority berisi seluruh id_transaksi yang masing-masing berisi $tmp2
                $itemset_priority[$transaksi[$i]]=$tmp2;
                $daftar_itemset_priority.='
                <tr>
                <td>'.htmlspecialchars($kode_transaksi[$transaksi[$i]]).'</td>
                <td>'.implode(', ', $tmp).'</td>
                </tr>
                ';
            }
            #FP TREE
            //variabel $pattern_base punya CONDITIONAL PATTERN BASE
            //variabel $pattern_fptree punya FP TREE
            $pattern_base=array();
            $pattern_fptree=array();
            for($i=0;$i<count($frekuensi_sort);$i++){
                //id barang pada index [1] pada array ITEMSET SUPPORT dimasukkan ke variabel $id_brg
                $id_brg=$frekuensi_sort[$i][1];
                $pattern_base[$id_brg]=array();
                $pattern_fptree[$id_brg]=array();
                $pattern=array();
                $pattern2=array();
            //perulangan for untuk cek seluruh transaksi  
            for($ii=0;$ii<count($transaksi);$ii++){
                    $pattern_tmp=array();
                    //cek if in array ITEMSET PRIORITY pada seluruh transaksi terdapat id_brg, true.., else cek count $pattern_tmp>0?
                    if(in_array($id_brg, $itemset_priority[$transaksi[$ii]])){ 
                        //if true, lakukan perulangan lagi, untuk cek didalam array ITEMSET PRIORITY                    
                        for($iii=0; $iii<count($itemset_priority[$transaksi[$ii]]); $iii++){
                            //if di dalam ITEM PRIORITY ketemu id_brg, true, break lompat ke bawah
                            if($itemset_priority[$transaksi[$ii]][$iii]==$id_brg){
                                break;
                            }else{
                                //FPTREE
                                //else, mengisi variabel $pattern_tmp dengan id barang tadi
                                $pattern_tmp[]=$itemset_priority[$transaksi[$ii]][$iii];
                                //mengecek key menggunakan array key, mengembalikan jika nilai true ada dan false jika kunci tidak ada
                                //cek apakah dalam $pattern_fptree[$id_brg] ada id barang pada ITEM PRIORITY tadi?
                                if(array_key_exists($itemset_priority[$transaksi[$ii]][$iii], $pattern_fptree[$id_brg])){
                                    //true, array $pattern_fptree yang mempunyai id_brg ITEMSET SUPPORT, id barang ITEMSET PRIORITY dan count..
                                    //value count-nya +1
                                    $pattern_fptree[$id_brg][$itemset_priority[$transaksi[$ii]][$iii]]['count']=$pattern_fptree[$id_brg][$itemset_priority[$transaksi[$ii]][$iii]]['count']+1;
                                    //else, $pattern_fptree yang mempunyai id_brg ITEMSET SUPPORT, id barang ITEMSET PRIORITY ditambah array count dengan value 1
                                }else{
                                    $pattern_fptree[$id_brg][$itemset_priority[$transaksi[$ii]][$iii]]=array('count'=>1);
                                }
                            }
                        }
                    }
                    #CONDITIONAL PATTERN BASE
                    //if count pattern_tmp atau node-nodenya suffixnya >0
                    if(count($pattern_tmp)>0){
                        //implode value (node suffixnya) dengan _
                        $id=implode('_', $pattern_tmp);
                        //if key pada $id yang berisi node tadi ada di $pattern
                        if(array_key_exists($id, $pattern)){
                            //true, count pada pattern +1
                            $pattern[$id]['count']=$pattern[$id]['count']+1;
                        }else{
                            //else pattern ditambahkan dengan count bernilai 1
                            $pattern[$id]=array('pattern'=>$pattern_tmp, 'count'=>1);
                        }
                    }
                }
                //cek if count pattern > 0
                //pattern dimasukkan ke pattern base
                if(count($pattern)>0){
                    $pattern_base[$id_brg]=$pattern;
                }
                //cek if count pattern2 > 0
                //pattern dimasukkan ke pattern fptree
                if(count($pattern2)>0){
                    $pattern_fptree[$id_brg]=$pattern2;
                }
            }
            #FREQUENT PATTERN
            //variabel $pattern_fptree yang berisi fp-tree akan diubah menjadi CONDITIONAL PATTERN BASE
            //variabel $pattern_fptree_all disiapkan untuk menampung detail FREQUENT PATTERN
            $pattern_fptree_all=array();
            $arr=$pattern_fptree;
            for($i=0;$i<count($frekuensi_sort);$i++){
                $id_brg=$frekuensi_sort[$i][1];
                //if isset $arr ada $id_brg dan countnya >0
                if(isset($arr[$id_brg]) and count($arr[$id_brg])>0){
                    $p=$arr[$id_brg];
                    $arr2=array();
                    foreach($p as $id => $value) {
                        //$arr2 berisi CONDITIONAL PATTERN BASE
                        $arr2[]=array('item1'=>$id, 'item2'=>$id_brg, 'count'=>$value['count']);
                        //$pattern_fptree_all berisi DETAIL CONDITIONAL PATTERN BASE
                        $pattern_fptree_all[]=array('item1'=>$id, 'item2'=>$id_brg, 'count'=>$value['count']);
                    }
                    //variabel $arr2 dimasukkan ke $pattern_fptree per $id_brg, sehingga
                    //variabel $pattern_fptree berisi CONDITIONAL PATTERN BASE per suffix
                    $pattern_fptree[$id_brg]=$arr2;
                }
            }
            #TAMPILAN CONDITIONAL PATTERN BASE
            for($i=0;$i<count($frekuensi_sort);$i++){
                $id_brg=$frekuensi_sort[$i][1];
                //if isset $id_brg dalam pattern base dan >0
                if(isset($pattern_base[$id_brg]) and count($pattern_base[$id_brg])>0){
                    $p=$pattern_base[$id_brg];
                    $pattern_nama=array();
                    //nested foreach untuk mengambil node-node suffix
                    //nama_barang disesuaikan dengan node suffixnya dimasukkan ke array v
                    foreach($p as $id => $value) {
                        $v=array();
                        foreach ($value['pattern'] as $value2) {
                            $v[]=$nama_barang[$value2];
                        }
                        //array v yang berisi nama node-node dari suffix diimplode dan
                        //ditambakan countnya lalu disimpan pada $pattern_nama
                        $pattern_nama[]='{'.implode(', ', $v).' : '.$value['count'].'}';
                        
                    }
                    //keseluruhan $pattern_nama diimplode dan dimasukkan dalam $pattern_label
                    $pattern_label='{'.implode(', ', $pattern_nama).'}';
                    //implode berfungsi menggabungkan kembali string yang telah dipecahkan
                    $daftar_conditional_pattern_base.='
                    <tr>
                    <td>'.htmlspecialchars($nama_barang[$id_brg]).'</td>
                    <td>'.$pattern_label.'</td>
                    </tr>
                    ';

                }
            }
            #TAMPILAN CONDITIONAL FP TREE
            for($i=0;$i<count($frekuensi_sort);$i++){
                $id_brg=$frekuensi_sort[$i][1];
                //if isset $id_brg dalam $pattern_fptree dan >0
                if(isset($pattern_fptree[$id_brg]) and count($pattern_fptree[$id_brg])>0){
                    $p=$pattern_fptree[$id_brg];
                    $pattern_nama=array();
                    for($ii=0;$ii<count($p);$ii++){
                        //cek if value count lebihdari sama dengan $nilai_minimum_support
                        if($p[$ii]['count'] >= $nilai_minimum_support){
                            //true, memasukkan $nama_barang[$p[$ii]['item1']].' : '.$p[$ii]['count'] ke dalam $pattern_nama;
                            $pattern_nama[]=$nama_barang[$p[$ii]['item1']].' : '.$p[$ii]['count'];
                        }
                    }
                    //pattern label untuk menggabungkan semuanya
                    $pattern_label='';
                    //if $pattern_nama > 0
                    if(count($pattern_nama) > 0){
                        $pattern_label='{'.implode(', ', $pattern_nama).'}';
                    }
                    $daftar_conditional_fptree.='
                    <tr>
                    <td>'.htmlspecialchars($nama_barang[$id_brg]).'</td>
                    <td>'.$pattern_label.'</td>
                    </tr>
                    ';
                }
            }
            #FREQUENT PATTERN
            for($i=0;$i<count($frekuensi_sort);$i++){
                $id_brg=$frekuensi_sort[$i][1];
                //if isset $id_brg dalam $pattern_fptree dan >0
                if(isset($pattern_fptree[$id_brg]) and count($pattern_fptree[$id_brg])>0){
                    $p=$pattern_fptree[$id_brg];
                    $pattern_nama=array();
                    for($ii=0; $ii<count($p); $ii++){
                        //if count pada $pattern_fptree[$id_brg] lebihdari samadengan $nilai_minimum_support
                        if($p[$ii]['count'] >= $nilai_minimum_support){
                            $pattern_nama[]='{'.$nama_barang[$p[$ii]['item1']].', '.$nama_barang[$p[$ii]['item2']].' : '.$p[$ii]['count'].'}';
                        }
                    }
                    $pattern_label='';
                    if(count($pattern_nama) > 0){
                        $pattern_label=implode(', ', $pattern_nama);
                    }
                    $daftar_frequent_itemset.='
                    <tr>
                    <td>'.htmlspecialchars($nama_barang[$id_brg]).'</td>
                    <td>'.$pattern_label.'</td>
                    </tr>
                    ';

                }
            }
            #TAMPILAN ASSOCIATION RULE
            $no=0;
            for($i=0;$i<count($pattern_fptree_all);$i++){
                $id_brg1=$pattern_fptree_all[$i]['item2'];
                $id_brg2=$pattern_fptree_all[$i]['item1'];
 
                #SUPPORT
                //nilai support = transaksi yang mengandung A dan B / jumlah keseluruhan transaksi
                $nilai_support=round($pattern_fptree_all[$i]['count'] / $jumlah_transaksi, 2);

                //CONFIDENCE
                //nilai support = transaksi yang mengandung A dan B / transaksi yang mengandung A
                $nilai_confidence=round($pattern_fptree_all[$i]['count'] / $frekuensi[$id_brg1], 2);

                #LIFT RATIO
                //nilai expected confidence = transaksi yang mengandung B / keseluruhan transaksi
                // lift ratio = nilai confidence/nilai expected confidence
                $nilai_expected_confidence=$frekuensi[$id_brg2]/$jumlah_transaksi;
                $nilai_lift_ratio=round($nilai_confidence/$nilai_expected_confidence, 2);
                if($nilai_confidence >= $nilai_minimum_confidence and $pattern_fptree_all[$i]['count'] >= $nilai_minimum_support){
                    $no++;
                    $daftar_rule.='
                    <tr>
                    <td class="text-center">'.$no.'</td>
                    <td>Jika Konsumen membeli '.htmlspecialchars($nama_barang[$id_brg1]).', maka membeli '.htmlspecialchars($nama_barang[$id_brg2]).'</td>
                    <td class="text-center">'.$nilai_support.'</td>
                    <td class="text-center">'.$nilai_confidence.'</td>
                    <td class="text-center">'.$nilai_lift_ratio.'</td>
                    </tr>
                    ';
                }
            }

        }

    }

    //menghitung waktu proses analisa
    $time_after = microtime(true);
    $time_speed = number_format(( $time_after - $time_before), 8);

}
?>
<script language="javascript">
$(document).ready(function() {
    var t = $('#table_browse').DataTable( {
        "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": [0]
        } ],
        "order": [[ 3, 'desc' ]]
    } );
 
    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
} );
</script>
<!-- FORM INPUT MIN SUPPORT, MIN CONFIDENCE, DAN JUMLAH DATA -->
<div class="jumbotron" style=" margin-left: 200px">
<!-- JUMBOTRON -->
  <h1 class="display-4">Sugeng Rawuh!!</h1>
  <p class="lead">dateng fitur analisis pola pembelian konsumen PT Aseli Dagadu Djokdja.</p>
  <hr class="my-2">
<?php
if($jumlah_data>0){?>
    <p>status memori: <span class="badge badge-danger">terisi</span></p>
    <p class="border border-danger p-2">Tekan <span class="badge badge-danger" name="reset" id="reset" type="submit"> Reset </span> untuk mengosongkan memori dan reinput data.</p>
<?php }elseif($minimum_support=='' and $minimum_confidence==''){ ?>
    <p>status memori: <span class="badge badge-success">kosong</span></p>
    <p class="border border-primary p-2"><span class="badge badge-primary">masukkan range data yang akan dianalisa</span> diikuti dengan <span  class="badge badge-primary">support dan confidencenya.</span></p>
<?php }else{ ?>
    <p>status memori: <span class="badge badge-danger">terisi</span></p>
    <p class="border border-dark p-2">Tuan/Puan sedang menganalisa <span class="badge badge-secondary"><?=$jumlah_transaksi;?></span> transaksi dari <span class="badge badge-secondary"><?=$daritgl;?></span> sampai <span class="badge badge-secondary"><?=$sampaitgl;?></span> dengan minimum support sebesar <span class="badge badge-secondary"><?=$_POST['minimum_support'];?>%</span> dan minimum confidence sebesar <span class="badge badge-secondary"><?=$_POST['minimum_confidence']?>%</span>.</p>
    <p class="border border-danger p-2">Tekan <span class="badge badge-danger" name="reset" id="reset" type="submit"> Reset </span> untuk mengosongkan memori dan reinput data</p>
<?php };?>
</div>
<?php 
if(!empty($error)){ 
    echo '
       <div class="alert alert-danger " style=" margin-left: 200px">
          <strong>ERROR -</strong> '.$error.'
       </div>
    ';
}
?>
<!-- INPUT DATA BASE dan MIN SUP DAN MIN CON -->
<div class="mt-2" style=" margin-left: 200px">
<form action="<?php echo $link_list;?>"method="post" class="form-horizontal">
<div class="row">
    <div class="col-lg-12">
    <div class="form-group">
        <label for="dari" class="col-sm-3 control-label">dari:<span class="text-danger">*</span></label>
        <div class="col-sm-7">
            <input type="date" class="form-control" id="dari" name="dari" value="<?=date($daritgl) ?>" <?= $disable;?>>
        </div>
    </div>
    <div class="form-group">
        <label for="sampai" class="col-sm-3 control-label">sampai:<span class="text-danger">*</span></label>
        <div class="col-sm-7">
            <input type="date" class="form-control" id="sampai" name="sampai" value="<?=date($sampaitgl) ?>" <?= $disable;?>>
        </div>
    </div>
            <div class="form-group">
                <label for="minimum_support" class="col-sm-3 control-label">Min Support <span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <input type="text" class="form-control" id="minimum_support" name="minimum_support" value="<?= htmlspecialchars($minimum_support);?>"<?= $disable;?>>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="minimum_confidence" class="col-sm-3 control-label">Min Confidence <span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <input type="text" class="form-control" id="minimum_confidence" name="minimum_confidence" value="<?= htmlspecialchars($minimum_confidence);?>" <?= $disable;?>>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">%</span>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
    <div class="col-sm-7">
        <button name="submit" id="submit" type="submit" class="btn btn-primary"<?= $disable;?>>Proses</button>
        <button name="reset" id="reset" type="submit" class="btn btn-danger">Reset</button>
    </div>
</form>
</div>
<?php 
if(isset($_POST['submit']) and $error==''){
?>

<!-- TAMPILAN -->

<div class="mt-5" style=" margin-left: 200px">
<div class="row">
        <div class="col-xl-12">
            <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Itemset</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
            <div style="overflow-x:auto;width:100%;">
            <table class="table table-striped table-hover table-bordered" style="table-layout: fixed; width: 100%;">
                <thead>
                    <tr>
                        <th class="text-center" width="100">TRANSAKSI</th>
                        <?php 
                        for($i=0;$i<count($barang);$i++){
                            echo '<th class="text-center" width="50">'.$kode_barang[$barang[$i]].'</th>';
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $daftar_tabulasi;?>
                    <tr>
                        <td>Frekuensi</td>
                        <?php 
                        for($i=0;$i<count($barang);$i++){
                            echo '<th class="text-center">'.$frekuensi[$barang[$i]].'</th>';
                        }
                        ?>
                    </tr>
                </tbody>
            </table>
            </div>
            </div>
            </div>
        </div>
        </div>
        </div>

<div class="mt-5" style=" margin-left: 200px">
        <div class="col-xl-12 col-lg-7">
            <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Itemset Frequent</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
               <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" width="40">NO</th>
                        <th class="text-center">ITEM</th>
                        <th class="text-center" width="100">FREKUENSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $daftar_frekuensi;?>
                </tbody>
            </table>
            </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-7">
            <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Itemset Support</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" width="40">NO</th>
                        <th class="text-center">ITEM</th>
                        <th class="text-center" width="100">FREKUENSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $daftar_support;?>
                </tbody>
            </table>
            </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-7">
            <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Conditional Pattern Base</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" width="100">ITEM</th>
                        <th class="text-center">CONDITIONAL PATTERN BASE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $daftar_conditional_pattern_base;?>
                </tbody>
            </table>
            </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-7">
            <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Conditional FP-Tree</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                 <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" width="100">ITEM</th>
                        <th class="text-center">CONDITIONAL FP-TREE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $daftar_conditional_fptree;?>
                </tbody>
            </table>
            </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-7">
            <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Frequent Pattern</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
              <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" width="100">ITEM</th>
                        <th class="text-center">FREQUENT PATTERN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $daftar_frequent_itemset;?>
                </tbody>
            </table>
            </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-7">
            <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Association Rule</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
              <table id="table_browse" class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" width="40">NO</th>
                        <th class="text-center">RULE</th>
                        <th class="text-center" width="100">SUPPORT</th>
                        <th class="text-center" width="100">CONFIDENCE</th>
                        <th class="text-center" width="150">LIFT RATIO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $daftar_rule;?>
                </tbody>
            </table>
            </div>
            </div>
            <p style="text-align: center;">Lama proses <?php echo $time_speed;?> detik</p>
        </div>
</div>

<?php } ?>