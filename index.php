<?php 
// Include config file untuk koneksi database
require_once 'config.php';

// Fungsi untuk melihat data dari database
function viewData($conn) {
    // Modify the SQL query to exclude rows with invalid dates ('0000-00-00')
    $sql = "SELECT * FROM records WHERE created_date != '0000-00-00' AND expired_date != '0000-00-00'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Data Records</h2>";
        echo "<table border='1' style='width: 100%; border-collapse: collapse;'>";
        echo "<thead>
                <tr>
                    <th>Created Date</th>
                    <th>Expired Date</th>
                    <th>Approval Number</th>
                    <th>PO Number</th>
                    <th>Fixed Expense</th>
                    <th>PR Number</th>
                    <th>Amount IDR</th>
                    <th>Amount US</th>
                    <th>Amount JP</th>
                    <th>Supplier</th>
                    <th>Name Approval</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
              </thead>";
        echo "<tbody>";

        // Loop through the data and display each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['created_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['expired_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['approval_number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['po_number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['fixed_expense']) . "</td>";
            echo "<td>" . htmlspecialchars($row['pr_number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['amount_idr']) . "</td>";
            echo "<td>" . htmlspecialchars($row['amount_us']) . "</td>";  
            echo "<td>" . htmlspecialchars($row['amount_jp']) . "</td>";
            echo "<td>" . htmlspecialchars($row['supplier']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name_approval']) . "</td>";
            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
            echo "<td>
                    <a href='?edit_approval_number=" . urlencode($row['approval_number']) . "'>Edit</a> | 
                    <a href='?delete_approval_number=" . urlencode($row['approval_number']) . "' onclick='return confirm(\"Are you sure you want to delete?\")'>Delete</a>
                  </td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "No data found!";
    }
}

// Proses Edit
if (isset($_GET['edit_approval_number'])) {
    $edit_approval_number = $_GET['edit_approval_number'];
    $sql = "SELECT * FROM records WHERE approval_number = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $edit_approval_number);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Debugging: Tampilkan data yang dikirim via POST
        echo '<pre>';
        print_r($_POST);
        echo '</pre>';

        // Ambil data dari form
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

        // Update data ke database
        $update_sql = "UPDATE records SET created_date = ?, expired_date = ?, approval_number = ?, po_number = ?, fixed_expense = ?, pr_number = ?, amount_idr = ?, amount_us = ?, amount_jp = ?, supplier = ?, name_approval = ?, description = ? WHERE approval_number = ?";
        if ($update_stmt = $conn->prepare($update_sql)) {
            $update_stmt->bind_param("sssssdssssss", $created_date, $expired_date, $approval_number, $po_number, $fixed_expense, $pr_number, $amount_idr, $amount_us, $amount_jp, $supplier, $name_approval, $description, $edit_approval_number);
            if ($update_stmt->execute()) {
                echo "Record updated successfully!";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                echo "Error updating record!";
            }
        }
    }
}

// Proses Hapus
if (isset($_GET['delete_approval_number'])) {
    $delete_approval_number = $_GET['delete_approval_number'];
    $sql = "DELETE FROM records WHERE approval_number = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $delete_approval_number);
        if ($stmt->execute()) {
            echo "Record deleted successfully!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Error deleting record!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Panel Admin</h2>

<!-- Tampilkan data records -->
<?php
viewData($conn);
?>

<?php if (isset($row)): ?>
    <h2>Edit Record</h2>
    <form method="post">
        <label for="created_date">Created Date:</label>
        <input type="text" name="created_date" value="<?php echo $row['created_date']; ?>" required><br><br>
        <label for="expired_date">Expired Date:</label>
        <input type="text" name="expired_date" value="<?php echo $row['expired_date']; ?>" required><br><br>
        <label for="approval_number">Approval Number:</label>
        <input type="text" name="approval_number" value="<?php echo $row['approval_number']; ?>"><br><br>
        <label for="po_number">PO Number:</label>
        <input type="text" name="po_number" value="<?php echo $row['po_number']; ?>"><br><br>
        <label for="fixed_expense">Fixed Expense:</label>
        <input type="text" name="fixed_expense" value="<?php echo $row['fixed_expense']; ?>"><br><br>
        <label for="pr_number">PR Number:</label>
        <input type="text" name="pr_number" value="<?php echo $row['pr_number']; ?>"><br><br>
        <label for="amount_idr">Amount IDR:</label>
        <input type="text" name="amount_idr" value="<?php echo $row['amount_idr']; ?>"><br><br>
        <label for="amount_us">Amount US:</label>
        <input type="text" name="amount_us" value="<?php echo $row['amount_us']; ?>"><br><br>
        <label for="amount_jp">Amount JP:</label>
        <input type="text" name="amount_jp" value="<?php echo $row['amount_jp']; ?>"><br><br>
        <label for="supplier">Supplier:</label>
        <input type="text" name="supplier" value="<?php echo $row['supplier']; ?>"><br><br>
        <label for="name_approval">Name Approval:</label>
        <input type="text" name="name_approval" value="<?php echo $row['name_approval']; ?>"><br><br>
        <label for="description">Description:</label>
        <textarea name="description" required><?php echo $row['description']; ?></textarea><br><br>
        <input type="submit" value="Update">
    </form>
<?php endif; ?>

</body>
</html>
