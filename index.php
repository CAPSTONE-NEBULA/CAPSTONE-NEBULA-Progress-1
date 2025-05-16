<?php
$title = "Login SDN 027 Loa Kulu";
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <!-- Menambahkan favicon -->
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
    
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            position: relative;
            height: 100vh;
            overflow: hidden;
        }

        .background {
            background: url('img/foto.jpg') no-repeat center center fixed;
            background-size: cover;
            filter: blur(4px);
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -2;
        }

        .overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            z-index: -1;
        }

        .wrapper {
            height: 100vh;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 0;
        }

        .card {
            display: flex;
            background-color: #FFF8E1;
            width: 1000px;
            height: 600px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.01);
        }

        .left-side, .right-side {
            height: 100%;
        }

        .left-side {
            background-color: #DFF8DC;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 30px;
            text-align: center;
        }

        .logo-img {
            width: 200px;
            max-width: 130%;
            margin-bottom: 20px;
        }

        .left-side h1 {
            font-size: 24px;
            font-style: italic;
            color: #2E7D32;
            margin: 0 0 8px 0;
        }

        .left-side p {
            font-size: 14px;
            color: #2E7D32;
            margin: 0;
        }

        .right-side {
            width: 50%;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right-side h2 {
            margin: 0;
            font-size: 50px;
        }

        .right-side small {
            color: #666;
            font-size: 25px;
        }

        form {
            margin-top: 30px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 15px 18px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 18px;
            background-color: rgba(247, 247, 247, 0.65);
        }

        .radio-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            font-size: 20px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
        }

        .radio-group input {
            margin-right: 8px;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background-color: #388E3C;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-login:hover {
            background-color: #2E7D32;
        }

        @media (max-width: 768px) {
            .card {
                flex-direction: column;
                width: 90%;
                height: auto;
            }

            .left-side,
            .right-side {
                width: 100%;
            }

            .left-side {
                padding: 60px 40px;
            }

            .right-side {
                padding: 60px 50px;
            }

            .logo-img {
                width: 100px;
            }
        }
    </style>
</head>
<body>
    <div class="background"></div>
    <div class="overlay"></div>

    <div class="wrapper">
        <div class="card">
            <div class="left-side">
                <img src="img/logo.png" alt="Logo SDN 027 Loa Kulu" class="logo-img">
                <h1><i>SISD</i></h1>
                <p>SDN 027 Loa Kulu</p>
            </div>
            <div class="right-side">
                <h2>Login</h2>
                <small>Sekolah Adiwiyata Provinsi</small>

                <form action="proses_login.php" method="POST">
                    <input type="text" name="user" placeholder="Masukkan user Anda" required>
                    <input type="password" name="password" placeholder="Masukkan Password Anda" required>
                    <div class="radio-group">
                        <label><input type="radio" name="role" value="siswa" required> siswa</label>
                        <label><input type="radio" name="role" value="admin" required> admin</label>
                    </div>
                    <button type="submit" class="btn-login">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
