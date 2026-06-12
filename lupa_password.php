<?php
include 'koneksi.php'; 

if (isset($_POST['reset_password'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $npm   = mysqli_real_escape_string($conn, $_POST['npm']);
    
    $pure_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $new_password  = password_hash($pure_password, PASSWORD_DEFAULT);
    $query = "SELECT * FROM users WHERE username='$email' AND npm='$npm'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $update_query = "UPDATE users SET password='$new_password' WHERE username='$email' AND npm='$npm'";
        if (mysqli_query($conn, $update_query)) {
            echo "<script>alert('Password berhasil direset! Silakan login kembali.'); window.location.href='index.php';</script>";
            exit();
        } else {
            echo "<script>alert('Gagal mereset password: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Data Email dan NPM tidak cocok!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Unsika Lab</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body {
            display: flex; justify-content: center; align-items: center; min-height: 100vh;
            background: url('image/unsika_1.jpg') no-repeat center center/cover;
            background-color: #1a1a1a;
        }

        .container {
            position: relative; width: 400px; max-width: 100%; min-height: 520px;
            background: rgba(255, 255, 255, 0.15); 
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 30px; 
            border: 1px solid rgba(255, 255, 255, 0.3);
            overflow: hidden; 
            box-shadow: 0 25px 45px rgba(0,0,0,0.2);
            padding: 40px;
            text-align: center;
        }

        .logo-kampus {
            width: 80px; 
            margin-bottom: 20px;
            filter: drop-shadow(0 5px 15px rgba(0,0,0,0.2));
        }

        h1 { color: #ffffff; font-weight: 700; font-size: 24px; margin-bottom: 10px; }
        p { color: rgba(255,255,255,0.8); font-size: 13px; margin-bottom: 20px; }

        form {
            display: flex;
            flex-direction: column;
        }

        input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 12px 18px; 
            margin: 8px 0;
            width: 100%; border-radius: 12px; outline: none;
            color: #fff; font-size: 14px;
        }
        input::placeholder { color: rgba(255,255,255,0.5); }

        button {
            border-radius: 15px; 
            border: none;
            background: linear-gradient(135deg, #800020, #5C0011);
            color: #FFFFFF; font-size: 14px; font-weight: 600;
            padding: 12px 50px; margin-top: 15px; width: 100%;
            cursor: pointer; 
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(128, 0, 32, 0.3);
            text-transform: uppercase; letter-spacing: 1px;
        }
        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(128, 0, 32, 0.4);
            filter: brightness(1.1);
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: rgba(255,255,255,0.7);
            font-size: 12px;
            text-decoration: none;
            transition: color 0.3s;
        }
        .back-link:hover { color: #fff; }
    </style>
</head>
<body>

<div class="container">
    <img src="image/logo.png" alt="Logo Kampus" class="logo-kampus">
    <h1>Lupa Password</h1>
    <p>Masukkan Email dan NPM Anda untuk mereset password.</p>
    
    <form action="" method="post">
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="text" name="npm" placeholder="NPM" required>
        <input type="password" name="new_password" placeholder="Password Baru" required>
        <button type="submit" name="reset_password">Reset Password</button>
    </form>

    <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Login</a>
</div>

</body>
</html>