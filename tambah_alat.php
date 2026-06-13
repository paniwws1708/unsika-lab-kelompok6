<?php
include '../koneksi.php';

if (isset($_POST['tambah'])) {

    $nama = mysqli_real_escape_string($conn,$_POST['nama_alat']);
    $jumlah = mysqli_real_escape_string($conn,$_POST['jumlah_unit']);
    $status = mysqli_real_escape_string($conn,$_POST['status']);
    $spek1 = mysqli_real_escape_string($conn,$_POST['spesifikasi_1']);
    $spek2 = mysqli_real_escape_string($conn,$_POST['spesifikasi_2']);

    $gambar = "default.png";
    if(isset($_FILES['gambar']) && $_FILES['gambar']['error']==0){

        $folder="../uploads/";

        if(!file_exists($folder)){
            mkdir($folder,0777,true);
        }

        $namaFile=$_FILES['gambar']['name'];
        $tmp=$_FILES['gambar']['tmp_name'];

        $ext=strtolower(pathinfo($namaFile,PATHINFO_EXTENSION));

        $allowed=['jpg','jpeg','png','webp'];

        if(in_array($ext,$allowed)){

            $namaBaru=time()."_".$namaFile;

            move_uploaded_file(
                $tmp,
                $folder.$namaBaru
            );

            $gambar=$namaBaru;
        }
    }

$query="INSERT INTO daftar_alat
(nama_alat,jumlah_unit,status,spesifikasi_1,spesifikasi_2,gambar)
VALUES
('$nama',
'$jumlah',
'$status',
'$spek1',
'$spek2',
'$gambar')";


if(mysqli_query($conn,$query)){

echo "<script>
alert('Alat berhasil ditambahkan!');
window.location='barang_admin.php';
</script>";

}else{

echo mysqli_error($conn);

}

}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Alat - Admin Control Panel</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

body{
font-family:'Inter',sans-serif;
}
</style>

</head>

<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">

<div class="w-full max-w-lg bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">

<div class="bg-[#002B6B] p-8 text-center text-white relative">

<div class="absolute top-4 left-4 opacity-20">
<i class="fas fa-tools text-5xl"></i>
</div>

<h2 class="text-2xl font-bold tracking-tight">
Tambah Alat Baru
</h2>

<p class="text-blue-200 text-xs mt-1 uppercase tracking-widest font-medium">
Inventaris Lab UNSIKA
</p>

</div>


<form action="" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">

<div class="space-y-1">

<label class="text-xs font-bold text-slate-500 uppercase ml-1">
Nama Alat
</label>

<div class="relative">

<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
<i class="fas fa-microscope text-sm"></i>
</span>

<input
type="text"
name="nama_alat"
required
class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500"
placeholder="Contoh: Mikroskop Binokuler">

</div>

</div>


<div>

<label class="text-xs font-bold text-slate-500 uppercase ml-1">
Jumlah Unit
</label>

<input
type="number"
name="jumlah_unit"
required
class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500"
placeholder="0">

</div>


<div>

<label class="text-xs font-bold text-slate-500 uppercase ml-1">
Status Awal
</label>

<select
name="status"
class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500">

<option value="Tersedia">🟢 Tersedia</option>
<option value="Dipinjam">🟡 Dipinjam</option>
<option value="Rusak">🔴 Rusak</option>

</select>

</div>


<div>

<label class="text-xs font-bold text-slate-500 uppercase ml-1">
Spesifikasi 1
</label>

<input
type="text"
name="spesifikasi_1"
class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500">

</div>


<div>

<label class="text-xs font-bold text-slate-500 uppercase ml-1">
Spesifikasi 2
</label>

<textarea
name="spesifikasi_2"
rows="2"
class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500"></textarea>

</div>


<div>

<label class="text-xs font-bold text-slate-500 uppercase ml-1">
Upload Foto Alat
</label>

<input
type="file"
name="gambar"
accept="image/*"
class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500">

</div>


<div class="pt-4 flex flex-col space-y-3">

<button
type="submit"
name="tambah"
class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition duration-200">

<i class="fas fa-save mr-2"></i>
Simpan Alat Baru

</button>

<a href="barang_admin.php"
class="text-center text-slate-400 text-xs hover:text-slate-600 transition">

<i class="fas fa-arrow-left mr-1"></i>
Kembali ke Daftar Alat

</a>

</div>

</form>

</div>

</body>
</html>