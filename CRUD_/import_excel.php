<?php
// Menyertakan file config.php untuk koneksi database
require_once 'config.php';  // Pastikan file config.php ada di lokasi yang benar

// Memasukkan library PhpSpreadsheet
require 'vendor/autoload.php'; // Pastikan sudah menginstal PhpSpreadsheet via Composer

use PhpOffice\PhpSpreadsheet\IOFactory;

// Pastikan koneksi ke database sudah ada
if ($conn) {
    echo "Koneksi ke database berhasil.\n";
} else {
    die("Koneksi gagal.\n");
}

// Menambahkan pengecekan untuk memastikan file Excel ada
$inputFileName = 'data_dummy.xlsx'; // Ganti dengan nama file Excel Anda
if (!file_exists($inputFileName)) {
    die("File Excel tidak ditemukan: " . $inputFileName . "\n");
}

try {
    // Membaca file Excel
    $spreadsheet = IOFactory::load($inputFileName);
    $sheet = $spreadsheet->getActiveSheet();

    // Mengambil data dari Excel dan memasukkan ke database
    foreach ($sheet->getRowIterator() as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        $data = [];
        foreach ($cellIterator as $cell) {
            $data[] = $cell->getValue(); // Menyimpan setiap nilai dari kolom ke dalam array $data
        }

        // Pastikan bahwa jumlah data sesuai dengan kolom di database (12 kolom)
        if (count($data) == 12) {
            // Misalnya, data dimasukkan ke tabel bernama 'records'
            $sql = "INSERT INTO records (created_date, expired_date, approval_number, po_number, fixed_expense, pr_number, amount_idr, amount_us, amount_jp, supplier, name_approval, description) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            // Bind semua parameter sesuai dengan jumlah kolom dan tipe data
            $stmt->bind_param("ssssssssssss", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11]);

            if ($stmt->execute()) {
                echo "Data berhasil diimpor: " . implode(", ", $data) . "\n";
            } else {
                echo "Gagal mengimpor data: " . implode(", ", $data) . "\n";
            }
        } else {
            echo "Jumlah kolom tidak sesuai di baris: " . implode(", ", $data) . "\n";
        }
    }

    echo "Impor selesai.\n"; // Jika tidak ada error selama proses
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n"; // Jika ada error saat membaca file atau lainnya
}
?>
