<!DOCTYPE html>
<html>
<head>
    <title>Form Pendaftaran Peserta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <?php
    //Include file koneksi, untuk koneksikan ke database
    include "koneksi.php";

    //Fungsi untuk mencegah inputan karakter yang tidak sesuai
    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    //Cek apakah ada kiriman form dari method post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $created_date=input($_POST["created_date"]);
        $expired_date=input($_POST["expired_date"]);
        $approval_number=input($_POST["approval_number"]);
        $po_number=input($_POST["po_number"]);
        $fixed_expense=input($_POST["fixed_expense"]);
        $pr_number=input($_POST["pr_number"]);
        $amount=input($_POST["amount"]);
        $supplier=input($_POST["supplier"]);
        $name_approval=input($_POST["name_approval"]);
        $description=input($_POST["description"]);

        //Query input menginput data kedalam tabel anggota
        $sql="insert into peserta (nama,sekolah,jurusan,no_hp,alamat) values
		('$created_date','$expired_date','$approval_number','$po_number','$fixed_expense','$pr_number','$amount','$supplier','$name_approval','$description')";

        //Mengeksekusi/menjalankan query diatas
        $hasil=mysqli_query($kon,$sql);

        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($hasil) {
            header("Location:index.php");
        }
        else {
            echo "<div class='alert alert-danger'> Data Gagal disimpan.</div>";

        }

    }
    ?>
    <h2>Data Input</h2>
    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
        <div class="form-group">    
            <label for="created_date">Created Date:</label>
        <input type="date" id="created_date" name="created_date" class="form-control" required/>
       
        <div class="form-group">    
            <label for="expired_date">expired_date:</label>
        <input type="date" id="expired_date" name="expired_date" class="form-control" required/>
        
        <div class="form-group">
            <label>Approval Number:</label>
            <input type="text" name="approval_number" class="form-control" required />
        </div>

        <div class="form-group">
            <label>PO Number:</label>
            <input type="text" name="po_number" class="form-control" required/>
        </div>

        <div class="form-group">
            <label>Fixed/Expense:</label>
            <input type="text" name="jurusan" class="form-control"required/>
        </div>

        <div class="form-group">
            <label>PR Number:</label>
            <input type="text" name="no_hp" class="form-control" required/>
        </div>

        <div class="form-group">
            <label>Amount:</label>
            <input type="text" name="amount" class="form-control" required/>

        <div class="form-group">
            <label>Supplier:</label>
            <input type="text" name="supplier" class="form-control" required/>

        <div class="form-group">
            <label>Name Approval:</label>
            <input type="text" name="name_approval" class="form-control" required/>
        <div class="form-group">
            <label>Description:</label>
            <textarea name="Description" class="form-control" rows="5" required></textarea>
        </div>       

        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body>
</html>