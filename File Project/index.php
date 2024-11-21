<?php
session_start(); // Memulai session untuk memeriksa status login

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika pengguna belum login, redirect ke halaman login
    header("Location: login.php");
    exit;
}

// Sertakan file config.php untuk koneksi ke database
include('config.php');

// Proses Hapus
if (isset($_GET['delete_approval_number'])) {
    $delete_approval_number = $_GET['delete_approval_number'];

    // Pastikan approval_number tidak kosong
    if (!empty($delete_approval_number)) {
        $delete_sql = "DELETE FROM records WHERE approval_number = ?";

        if ($delete_stmt = $conn->prepare($delete_sql)) {
            // Binding parameter untuk mencegah SQL Injection
            $delete_stmt->bind_param("s", $delete_approval_number);

            // Eksekusi query untuk hapus data
            if ($delete_stmt->execute()) {
                header("Location: " . $_SERVER['PHP_SELF']); // Redirect setelah berhasil menghapus
                exit;
            } else {
                echo "Terjadi kesalahan saat menghapus record: " . $conn->error;
            }
        } else {
            echo "Query gagal disiapkan: " . $conn->error;
        }
    }
}

// Ambil semua data dari database untuk ditampilkan dalam tabel
$sql = "SELECT * FROM records";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Records</title>
    <style>
        /* Gaya tabel dan tombol */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        button {
            padding: 5px 10px;
            margin: 2px;
            cursor: pointer;
        }

        .edit-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
            border: none;
        }

        .data-created-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .data-created-btn:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .data-created-btn:active {
            background-color: #3e8e41;
            transform: translateY(2px);
        }
    </style>
</head>
<body>

    <!-- Include Header -->
    <?php include('header.php'); ?>

    <!-- Include Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Content Area -->
    <div class="content" style="margin-left: 270px; padding: 20px;">
        <h2>Data Records</h2>

        <!-- Link untuk membuat data baru, tampak seperti tombol -->
        <a href="create.php" role="button" class="data-created-btn">Data Created</a>

        <!-- Tabel untuk menampilkan data -->
        <table>
            <thead>
                <tr>
                    <th>Created Date</th>
                    <th>Expired Date</th>
                    <th>Approval Number</th>
                    <th>PO Number</th>
                    <th>Fixed Expense</th>
                    <th>PR Number</th>
                    <th>Amount IDR</th>
                    <th>Amount USD</th>
                    <th>Amount JPY</th>
                    <th>Supplier</th>
                    <th>Name Approval</th>
                    <th>Description</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['created_date']; ?></td>
                            <td><?php echo $row['expired_date']; ?></td>
                            <td><?php echo $row['approval_number']; ?></td>
                            <td><?php echo $row['po_number']; ?></td>
                            <td><?php echo $row['fixed_expense']; ?></td>
                            <td><?php echo $row['pr_number']; ?></td>
                            <td><?php echo $row['amount_idr']; ?></td>
                            <td><?php echo $row['amount_us']; ?></td>
                            <td><?php echo $row['amount_jp']; ?></td>
                            <td><?php echo $row['supplier']; ?></td>
                            <td><?php echo $row['name_approval']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td>
                                <!-- Tombol Edit -->
                                <a href="edit_record.php?edit_approval_number=<?php echo $row['approval_number']; ?>">
                                    <button class="edit-btn">Edit</button>
                                </a>
                                <!-- Tombol Hapus -->
                                <a href="?delete_approval_number=<?php echo $row['approval_number']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">
                                    <button class="delete-btn">Delete</button>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="13" style="text-align:center;">No records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php
// Tutup koneksi setelah query selesai
$conn->close();
?>
