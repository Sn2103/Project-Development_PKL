<?php
// Sertakan file config.php untuk koneksi ke database
include('config.php');

// Cek jika ada parameter 'edit_approval_number' di URL
if (isset($_GET['edit_approval_number'])) {
    $edit_approval_number = $_GET['edit_approval_number'];

    // Ambil data berdasarkan approval_number yang dipilih
    $sql = "SELECT * FROM records WHERE approval_number = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $edit_approval_number);
        $stmt->execute();
        $result = $stmt->get_result();

        // Jika data ditemukan, ambil data untuk ditampilkan di form
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
        } else {
            echo "Data tidak ditemukan!";
            exit;
        }
    } else {
        echo "Query gagal disiapkan: " . $conn->error;
        exit;
    }
} else {
    echo "Approval number tidak ditemukan!";
    exit;
}

// Proses update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $created_date = $_POST['created_date'];
    $expired_date = $_POST['expired_date'];
    $approval_number = $_POST['approval_number'];
    $po_number = $_POST['po_number'];
    $fixed_expense = $_POST['fixed_expense'];
    $pr_number = $_POST['pr_number'];
    $amount_idr = $_POST['amount_idr'];
    $amount_us = $_POST['amount_us'];
    $amount_jp = $_POST['amount_jp'];
    $supplier = $_POST['supplier'];
    $name_approval = $_POST['name_approval'];
    $description = $_POST['description'];

    // Validasi input
    if (empty($created_date) || empty($expired_date) || empty($approval_number)) {
        echo "Beberapa field wajib diisi!";
    } else {
        // Update data di database
        $update_sql = "UPDATE records SET created_date = ?, expired_date = ?, po_number = ?, fixed_expense = ?, pr_number = ?, amount_idr = ?, amount_us = ?, amount_jp = ?, supplier = ?, name_approval = ?, description = ? WHERE approval_number = ?";
        if ($update_stmt = $conn->prepare($update_sql)) {
            $update_stmt->bind_param("sssssdssssss", $created_date, $expired_date, $po_number, $fixed_expense, $pr_number, $amount_idr, $amount_us, $amount_jp, $supplier, $name_approval, $description, $approval_number);
            if ($update_stmt->execute()) {
                header("Location: index.php"); // Redirect ke halaman utama setelah berhasil
                exit;
            } else {
                echo "Terjadi kesalahan saat mengupdate data: " . $conn->error;
            }
        } else {
            echo "Query gagal disiapkan: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .form-container {
            width: 70%;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .cancel-button {
            background-color: #f44336;
            margin-left: 10px;
        }

        .cancel-button:hover {
            background-color: #e53935;
        }

        .button-container {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Data Record</h2>

    <!-- Form untuk mengedit data -->
    <form method="POST">
        <table>
            <tr>
                <th>Created Date</th>
                <td><input type="date" name="created_date" value="<?php echo $row['created_date']; ?>" required></td>
            </tr>
            <tr>
                <th>Expired Date</th>
                <td><input type="date" name="expired_date" value="<?php echo $row['expired_date']; ?>" required></td>
            </tr>
            <tr>
                <th>Approval Number</th>
                <td><input type="text" name="approval_number" value="<?php echo $row['approval_number']; ?>" required readonly></td>
            </tr>
            <tr>
                <th>PO Number</th>
                <td><input type="text" name="po_number" value="<?php echo $row['po_number']; ?>"></td>
            </tr>
            <tr>
                <th>Fixed Expense</th>
                <td><input type="number" name="fixed_expense" value="<?php echo $row['fixed_expense']; ?>" step="0.01"></td>
            </tr>
            <tr>
                <th>PR Number</th>
                <td><input type="text" name="pr_number" value="<?php echo $row['pr_number']; ?>"></td>
            </tr>
            <tr>
                <th>Amount IDR</th>
                <td><input type="number" name="amount_idr" value="<?php echo $row['amount_idr']; ?>" step="0.01"></td>
            </tr>
            <tr>
                <th>Amount USD</th>
                <td><input type="number" name="amount_us" value="<?php echo $row['amount_us']; ?>" step="0.01"></td>
            </tr>
            <tr>
                <th>Amount JPY</th>
                <td><input type="number" name="amount_jp" value="<?php echo $row['amount_jp']; ?>" step="0.01"></td>
            </tr>
            <tr>
                <th>Supplier</th>
                <td><input type="text" name="supplier" value="<?php echo $row['supplier']; ?>"></td>
            </tr>
            <tr>
                <th>Name Approval</th>
                <td><input type="text" name="name_approval" value="<?php echo $row['name_approval']; ?>"></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><textarea name="description"><?php echo $row['description']; ?></textarea></td>
            </tr>
            <tr>
                <td colspan="2" class="button-container">
                    <button type="submit">Update Record</button>
                    <a href="index.php"><button type="button" class="cancel-button">Cancel</button></a>
                </td>
            </tr>
        </table>
    </form>
</div>

</body>
</html>

<?php
// Tutup koneksi setelah query selesai
$conn->close();
?>
