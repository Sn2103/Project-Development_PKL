<?php include 'config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Baru</title>
</head>
<body>

<h2>Tambah Data Baru</h2>
<form method="post" action="create.php">
    Tanggal Dibuat: <input type="date" name="created_date"><br>
    Tanggal Kadaluarsa: <input type="date" name="expired_date"><br>
    Nomor Persetujuan: <input type="text" name="approval_number"><br>
    Nomor PO: <input type="text" name="po_number"><br>
    Jenis: <input type="text" name="fixed_expense"><br>
    Nomor PR: <input type="text" name="pr_number"><br>
    Jumlah IDR: <input type="number" name="amount_idr" step="0.01"><br>
    Jumlah US: <input type="number" name="amount_us" step="0.01"><br>
    Jumlah JP: <input type="number" name="amount_jp" step="0.01"><br>
    Pemasok: <input type="text" name="supplier"><br>
    Nama Persetujuan: <input type="text" name="name_approval"><br>
    Deskripsi: <textarea name="description"></textarea><br>
    <button type="submit" name="submit">Tambah Data</button>
</form>

<?php
if (isset($_POST['submit'])) {
    $stmt = $conn->prepare("INSERT INTO records (created_date, expired_date, approval_number, po_number, fixed_expense, pr_number, amount_idr, amount_us, amount_jp, supplier, name_approval, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssddssss", $_POST['created_date'], $_POST['expired_date'], $_POST['approval_number'], $_POST['po_number'], $_POST['fixed_expense'], $_POST['pr_number'], $_POST['amount_idr'], $_POST['amount_us'], $_POST['amount_jp'], $_POST['supplier'], $_POST['name_approval'], $_POST['description']);
    
    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

</body>
</html>
