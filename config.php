<?php
$db_host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'poliklinik_bk';

try {
    // Koneksi PDO
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("PDO connection failed: " . $e->getMessage());
}

// Koneksi mysqli
$conn = mysqli_connect($db_host, $username, $password, $db_name);

// Periksa koneksi mysqli
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {

}


// Define the getLatestNoAntrian function
function getLatestNoAntrian($id_jadwal, $pdo) {
    try {
        $query = "SELECT MAX(no_antrian) AS latest_no_antrian FROM daftar_poli WHERE id_jadwal = :id_jadwal";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_jadwal', $id_jadwal);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['latest_no_antrian'] ?? 0; // If no records, return 0
    } catch (Exception $e) {
        var_dump($e->getMessage());
        return 0; // Handle error appropriately, here we just return 0
    }
}

// Define the daftarPoli function
function daftarPoli($data) {
    global $pdo;

    try {
        $id_pasien = $data['id_pasien'];
        $id_jadwal = $data['id_jadwal'];
        $keluhan = $data['keluhan'];
        $no_antrian = getLatestNoAntrian($id_jadwal, $pdo) + 1;
        $status = 0;

        // Explicitly list the columns in the INSERT query
        $query = "INSERT INTO daftar_poli VALUES (NULL, :id_pasien, :id_jadwal, :keluhan, :no_antrian, :status_periksa)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_pasien', $id_pasien);
        $stmt->bindParam(':id_jadwal', $id_jadwal);
        $stmt->bindParam(':keluhan', $keluhan);
        $stmt->bindParam(':no_antrian', $no_antrian);
        $stmt->bindParam(':status_periksa', $status);

        if ($stmt->execute()) {
            return $stmt->rowCount(); // Return the number of affected rows
        } else {
            // Handle the error
            echo "Error updating record: " . $stmt->errorInfo()[2];
            return -1; // Or any other error indicator
        }
    } catch (Exception $e) {
        var_dump($e->getMessage());
    }
}
?>

