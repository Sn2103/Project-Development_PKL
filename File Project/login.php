<?php
// Sertakan file config.php untuk koneksi ke database
include('config.php');
session_start(); // Mulai session untuk menyimpan data pengguna

// Periksa koneksi database
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        table {
            width: 100%;
        }
        td {
            padding: 8px 0;
        }
        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #007BFF;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <?php
    // Periksa apakah form telah disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ambil data dari form dan lakukan sanitasi
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        
        // Periksa apakah username dan password tidak kosong
        if (!empty($username) && !empty($password)) {
            // Hash password dengan MD5 (jika memungkinkan gunakan hashing modern seperti bcrypt di masa depan)
            $hashed_password = md5($password);

            // Gunakan prepared statement untuk keamanan
            $stmt = $conn->prepare("SELECT id, username FROM users WHERE username = ? AND password = ?");
            $stmt->bind_param("ss", $username, $hashed_password);

            // Eksekusi query
            $stmt->execute();
            $result = $stmt->get_result();

            // Periksa apakah ada hasil
            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                $_SESSION['user_id'] = $data['id']; // Simpan ID user di session
                $_SESSION['nama'] = $data['nama']; // Simpan nama user di session
                
                echo '<script>
                    alert("Selamat Datang, ' . htmlspecialchars($data['nama']) . '");
                    location.href = "index.php";
                </script>';
            } else {
                echo '<script>alert("Username atau Password tidak sesuai.");</script>';
            }

            // Tutup statement
            $stmt->close();
        } else {
            echo '<script>alert("Harap masukkan Username dan Password.");</script>';
        }
    }
    ?>

    <!-- Form Login -->
    <form method="post">
        <table>
            <tr>
                <td>Username</td>
                <td><input type="text" name="username" placeholder="Masukkan username" required></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="password" placeholder="Masukkan password" required></td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit">LOGIN</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
