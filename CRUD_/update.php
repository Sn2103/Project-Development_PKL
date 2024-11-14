<?php
include 'config.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM records WHERE id=$id");
$row = $result->fetch_assoc();

if (isset($_POST['submit'])) {
    $stmt = $conn->prepare("UPDATE records SET created_date=?, expired_date=?, approval_number=?, po_number=?, fixed_expense=?, pr_number=?, amount_idr=?, amount_us=?, amount_jp=?, supplier=?, name_approval=?, description=? WHERE id=$id");
    $stmt->bind_param("ssssssddssss", $_POST['created_date'], $_POST['expired_date'], $_POST['approval_number'], $_POST['po_number'], $_POST['fixed_expense'], $_POST['pr_number'], $_POST['amount_idr'], $_POST['amount_us'], $_POST['amount_jp'], $_POST['supplier'], $_POST['name_approval'], $_POST['description']);
    
    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data</title>
</head>
<body>

<h2>Edit Data</h2>
<form method="post">
    Tanggal Dibuat: <input type="date" name="created_date" value="<?php echo $row['created_date']; ?>"><br>
    Tanggal Kadaluarsa: <input type="date" name="expired_date" value="<?php echo $row['expired_date']; ?>"><br>
    Nomor Persetujuan: <input type="text" name="approval_number" value="<?php echo $row['approval_number']; ?>"><br>
    Nomor PO: <input type="text" name="po_number" value="<?php echo $row['po_number']; ?>"><br>
    Jenis: <input type="text" name="fixed_expense" value="<?php echo $row['fixed_expense']; ?>"><br>
    Nomor PR: <input type="text" name="pr_number" value="<?php echo $row['pr_number']; ?>"><br>
    Jumlah IDR: <input type="number" name="amount_idr" step="0.01" value="<?php echo $row['amount_idr']; ?>"><br>
    Jumlah US: <input type="number" name="amount_us" step="0.01" value="<?php echo $row['amount_us']; ?>"><br>
    Jumlah JP: <input type="number" name="amount_jp" step="0.01" value="<?php echo $row['amount_jp']; ?>"><br>
    Pemasok: <input type="text" name="supplier" value="<?php echo $row['supplier']; ?>"><br>
    Nama Persetujuan: <input type="text" name="name_approval" value="<?php echo $row['name_approval']; ?>"><br>
    Deskripsi: <textarea name="description"><?php echo $row['description']; ?></textarea><br>
    <button type
