    <?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "user_db";

    $conn = mysqli_connect($host, $user, $pass, $db, 3307);
        
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }
    ?>