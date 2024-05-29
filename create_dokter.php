<?php
session_start();
include("config.php");

if (isset($_SESSION['login'])){
    $_SESSION['login'] = true;
} else {
    echo "<meta http-equiv='refresh' content='0;url=..'>";
    die();
}

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];
$id_dokter = $_SESSION['id'];

if ($akses != 'obat') {
    echo "<meta http-equiv='refresh' content='0;url=..'>";
    die();
}

$url = $_SERVER['REQUEST_URI'];
$url = explode("/", $url);
$id_poli = $url[count($url) - 1];
$obat = query ("SELECT * FROM obat");

$pasien = query ("SELECT p.nama AS nama_pasien,
                         dp.id AS id_daftar_poli
                         FROM pasien p
                         INNER JOIN daftar_poli dp ON p.id = dp.id_pasien
                         WHERE p.id = '$id'")[0];

$biaya_periksa = 2000000;
$total_biaya_obat=0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    
        <link rel="stylesheet" href="css/style2.css">

    <!--font google-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teachers:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Teachers:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">
</head>

<body>
     <!--sidebar-->
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                <img src="sources/logo.svg" alt="" width="35" height="35" class="d-inline-block align-text-top">
                </button>
                <div class="sidebar-logo">
                    <a href="#">POLIKLINIK UDINUS</a>
                </div>
            </div>
            <ul class="sidebar-nav" >
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                    <img src="sources/profildokter.png" alt="" width="24" height="24" class="d-inline-block align-text-top">
                        <span>dr. Hani</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                    <img src="sources/home.png" alt="" width="24" height="24" class="d-inline-block align-text-top">
                        <span>Home</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                    <img src="sources/jadwal.png" alt="" width="24" height="24" class="d-inline-block align-text-top">
                        <span>Jadwal Periksa</span>
                    </a>
                </li>
                </li>
                    <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                    <img src="sources/stethoscope.png" alt="" width="24" height="24" class="d-inline-block align-text-top">
                        <span>Memeriksa Pasien</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                    <img src="sources/riwayat.png" alt="" width="24" height="24" class="d-inline-block align-text-top">
                        <span>Riwayat pasien</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                    <img src="sources/profil.png" alt="" width="24" height="24" class="d-inline-block align-text-top"> 
                        <span>Profil</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="http://localhost/Projek_BK/index.php" class="sidebar-link">
                <img src="sources/logout.png" alt="" width="24" height="24" class="d-inline-block align-text-top">
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        
         <!--main content-->
        <div class="main">

        <div class="container-fluid py-2" style="background-color:#FFF5E4";>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h3>Dashboard Dokter</h3>
                    </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Periksa Pasien</h3>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <!--menambahkan data -->
                    <div class="form-group">
                        <label for="nama_pasien">Nama Pasien</label>
                        <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" value="<?php echo $nama_pasien ?>">
                    </div>

                    <div class="form-group">
                        <label for="tgl_periksa">Tanggal Periksa</label>
                        <input type="datetime-local" class="form-control" id="tgl_periksa" name="tgl_periksa">
                    </div>

                    <div class="form-group">
                        <label for="catatan">Note</label>
                        <input type="text" class="form-control" id="catatan" name="catatan">
                    </div>

                    <div class="form-group">
                        <label for="nama_pasien">Obat</label>
                        <select class="form-control" name="obat[]" multiple id="id_obat">
                            <?php foreach ($obat as $obats) : ?>
                                <option value="<?= $obats['id']; ?>|<?= $obats['harga']?>"><?= $obats['nama_obat']; ?> - <?= $obats['kemasan']; ?> - Rp. <?= $obats['harga']; ?> </option>
                            <?php endforeach; ?>
                        </select>       
                    </div>

                    <div class="form-group">
                        <label for="total_harga">Total Harga</label>
                        <input type="text" class="form-control" id="harga" name="harga" readonly>
                    </div>
            
                    <!-- Tombol untuk mengirim form -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" id="simpan_periksa" name="simpan_periksa">
                            <i class="fa fa-save"></i> Simpan </button>
                    </div>
                </form>

                <?php
                if (isset($_POST['simpan_periksa'])){
                    $tgl_periksa =$_POST['tgl_periksa'];
                    $catatan =$_POST['catatan'];
                    $obat =$_POST['obat'];
                    $id_daftar_poli =$pasien['id_daftar_poli'];
                    $id_obat =[];
                    for ($i =0; $i < count($obat); $i++){
                        $data_obat = explode ("|", $obat[$i]);
                        $id_obat = $data_obat[0];
                        $total_biaya_obat += $data_obat[1];
                    }
                    $total_biaya = $biaya_periksa + $total_biaya_obat;

                    $query = "INSERT INTO periksa (id_daftar_poli, tgl_periksa, catatan, biaya_periksa) VALUE
                              ($id_daftar_poli, $tgl_periksa, $catatan, $biaya_periksa)";
                    $result = mysqli_query($conn, $query); 
                    
                    $query2 = "INSERT INTO detail_periksa (id_obat, id_periksa) VALUE ";
                    $periksa_id = mysql_insert_id ($conn);
                    for ($i = 0; $i < count($id_obat); $i++){
                        $query2 .= "($id_obat[$i], $periksa_id) ";
                    }
                    $query2 = substr($query2, 0, -1);
                    $result2 = mysqli_query($conn, $query2);

                    $query3 = "UPDATE daftar_poli SET status_periksa = '1' 
                                WHERE id = $id_daftar_poli";
                    $result3 = mysqli_query ($conn, $query3);

                    if ($result && $result2 && $result3){
                        echo "
                    <script>
                        alert('Data Berhasil diubah');
                        document.location.href = '../ ';
                    </script>
                    ";
                    }else {
                        echo "
                    <script>
                        alert('Data Berhasil diubah');
                        alert('$query');
                        document.location.href = '../edit.php/$id';
                    </script>
                    ";
                    }   
                }
                ?>
            </div>
        </div>
        <script>
            $(document).ready(function(){
                $('#id_obat').select2();
                $('#id_obat').on('change.select2', function(e){
                    var selectedvaluesArray = $(this).val();

                    //kalkulasi sum
                    var sum =200000;
                    if(selectedvaluesArray){
                        for (var i = 0; i < selectedvaluesArray.length; i++){
                            //spilt value dan get part kedua setelah "|"
                            var parts = selectedvaluesArray[i].spilt("|");
                            if (parts.length === 2){
                                sum += perseFloat(parts[1]);
                            }
                        }
                    }
                    $('#harga').val(sum);
                });
            });

        </script>
</div>
                    </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>

</html>


