<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
</head>
<title>
    MUHAMMAD ICHSAN FIKRI</title>
<body>
    <nav class="navbar navbar-dark bg-dark">
            <span class="navbar-brand mb-0 h1">Sharp</span>
        </div>
    </nav>
<div class="container">
    <br>
    <h4><center>DATA INPUT</center></h4>
<?php

    include "koneksi.php";

    //Cek apakah ada kiriman form dari method post
    if (isset($_GET['approval_number'])) {
        $id_peserta=htmlspecialchars($_GET["approval_number"]);

        $sql="delete from peserta where approval_number='$approval_number' ";
        $hasil=mysqli_query($kon,$sql);

        //Kondisi apakah berhasil atau tidak
            if ($hasil) {
                header("Location:index.php");

            }
            else {
                echo "<div class='alert alert-danger'> Data Gagal dihapus.</div>";

            }
        }
?>


     <tr class="table-danger">
            <br>
        <thead>
        <tr>
       <table class="my-3 table table-bordered">
            <tr class="table-primary">
            <th>No</th>           
            <th>Created Date</th>
            <th>Expired Date</th>
            <th>Approval Number</th>
            <th>PO Number</th>
            <th>Fixed/Expense</th>
            <th>PR Number</th>
            <th>Amount</th>
            <th>Supplier</th>
            <th>Name Approval</th>
            <th>Description</th>
            <th colspan='2'>Action</th>

        </tr>
        </thead>

        <?php
        include "koneksi.php";
        $sql="select * from project order by approval_number desc";

        $hasil=mysqli_query($kon,$sql);
        $no=0;
        while ($data = mysqli_fetch_array($hasil)) {
            $no++;

            ?>
            <tbody>
            <tr>
                <td><?php echo $no;?></td>
                <td><?php echo $data["created_date"]; ?></td>
                <td><?php echo $data["expired_date"];   ?></td>
                <td><?php echo $data["approval_number"];   ?></td>
                <td><?php echo $data["po_number"];   ?></td>
                <td><?php echo $data["fixed_expense"];   ?></td>
                <td><?php echo $data["pr_number"];   ?></td>
                <td><?php echo $data["amount"];   ?></td>
                <td><?php echo $data["supplier"];   ?></td>
                <td><?php echo $data["name_approval"];   ?></td>
                <td><?php echo $data["description"];   ?></td>
                
                
                
                <td>
                    <a href="update.php?approval_number=<?php echo htmlspecialchars($data['approval_number']); ?>" class="btn btn-warning" role="button">Update</a>
                    <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?approval_number=<?php echo $data['approval_number']; ?>" class="btn btn-danger" role="button">Delete</a>
                </td>
            </tr>
            </tbody>
            <?php
        }
        ?>
    </table>
    <a href="create.php" class="btn btn-primary" role="button">Data Created</a>
</div>
</body>
</html>
