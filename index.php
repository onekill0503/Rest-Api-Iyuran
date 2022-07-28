<?php
include_once "Config.php";

$semua_warga = $conn->query("SELECT * FROM warga");
$semua_warga2 = $conn->query("SELECT * FROM warga");
$semua_kategori = $conn->query("SELECT * FROM kategori");
$semua_kategori2 = $conn->query("SELECT * FROM kategori");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h3>Tambah Warga</h3>
    <form action="" id="form_login">
        <input type="email" name="email" placeholder="Email"><br>
        <input type="password" name="password" placeholder="Password"><br>
        <button type="submit">login</button>
    </form>
    <h3>Tambah Warga</h3>
    <form action="" id="tambah_warga">
        <input type="text" name="nama_warga" placeholder="Nama Warga"><br>
        <input type="email" name="email" placeholder="Email"><br>
        <input type="password" name="password" placeholder="Password"><br>
        <input type="text" name="no_telp" placeholder="Nomor Telepon"><br>
        <textarea type="text" name="alamat" placeholder="Alamat"></textarea><br>
        <button type="submit">submit</button>
        <button type="button" onclick="tampil_warga()">Tampilkan Daftar Warga</button>
    </form>
</body>
<h3>Catat Iyuran Warga</h3>
<form action="" id="catat_iyuran">
    <input type="text" name="nama_petugas" placeholder="Nama Petugas">
    <input type="text" name="jumlah_iyuran" placeholder="Jumlah Iyuran">
    <select name="id_warga">
        <option value="0">-- Pilih Warga --</option>
        <?php while($row = $semua_warga->fetch_assoc()) :?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['nama_warga']; ?></option>
        <?php endwhile; ?>
    </select><br>
    <span>Periode</span><br>
    <input type="date" name="periode-1" value='<?php echo date('Y-m-d'); ?>'> - <input type="date" name="periode-2" value='<?php echo date('Y-m-d'); ?>'><br>
    <input type="checkbox" name="kematian" value="iya"> Bayar Iyuran Kematian Juga ? <br>
    <button type="submit">submit</button>
</form>
<h3>Form Pengeluaran Kas</h3>
<form action="" id="tambah_pengeluaran">
    <input type="text" name="jumlah_pengeluaran" placeholder="Jumlah Pengeluaran">
    <input type="text" name="alasan" placeholder="Alasan">
    <button type="submit">submit</button>
    <button type="button" onclick="tampil_pengeluaran()">Tampilkan Daftar Pengeluaran</button>
</form>
<h3>Form Pengumuman</h3>
<form action="" id="tambah_pengumuman">
    <input type="hidden" name="aksi" value='buat_pengumuman'>
    <input type="text" name="judul" placeholder="Judul"><br>
    <input type="text" name="pembuat" placeholder="Nama Pembuat"><br>
    <select name="id_kategori">
        <option value="">-- Pilih Kategori --</option>
        <?php while($row = $semua_kategori->fetch_assoc()) :?>
            <option value="<?php echo $row['id_kategori']; ?>"><?php echo $row['nama_kategori']; ?></option>
        <?php endwhile; ?>
    </select><br>
    <input type="file" accept="image/x-png,image/gif,image/jpeg" name="file"><br>
    <textarea name="isi"></textarea><br>
    <button type="submit">submit</button>
    <button type="button" onclick="tampil_pengumuman()">Tampilkan Daftar Pengumuman</button>
</form>
<h3>Form pengaduan</h3>
<form action="" id="tambah_pengaduan">
    <input type="hidden" name="aksi" value='buat_pengaduan'>
    <input type="text" name="judul" placeholder="Judul"><br>
    <input type="text" name="pembuat" placeholder="Nama pembuat"><br>
    <select name="id_kategori">
        <option value="">-- Pilih Kategori --</option>
        <?php while($row = $semua_kategori2->fetch_assoc()) :?>
            <option value="<?php echo $row['id_kategori']; ?>"><?php echo $row['nama_kategori']; ?></option>
        <?php endwhile; ?>
    </select><br>
    <input type="file" accept="image/x-png,image/gif,image/jpeg" name="file"><br>
    <textarea name="isi"></textarea><br>
    <button type="submit">submit</button>
    <button type="button" onclick="tampil_pengaduan()">Tampilkan Daftar Pengaduan</button>
</form>
<h3>Button Lainnya</h3>
<select id="pilih_warga">
    <option value="">-- Pilih Warga --</option>
    <?php while($row = $semua_warga2->fetch_assoc()) :?>
        <option value="<?php echo $row['id']; ?>"><?php echo $row['nama_warga']; ?></option>
    <?php endwhile; ?>
</select><br>
<button type="button" onclick="tampil_riwayat_iyuran()">Tampilkan Riwayat Iyuran Warga</button><br><br>
<button type="button" onclick="tampil_pemasukan_iyuran_sampah()">Tampilkan Pemasukan Iyuran Sampah</button><br>
<button type="button" onclick="tampil_pemasukan_iyuran_kematian()">Tampilkan Pemasukan Iyuran Kematian</button><br>
<button type="button" onclick="tampil_pemasukan_iyuran()">Tampilkan Pemasukan Iyuran</button><br><br>
<button type="button" onclick="tampil_laporan()">Tampilkan Data Laporan</button>
</body>
<script src="./jquery-3.4.1.min.js"></script>
<script>
    function tampil_riwayat_iyuran(){
        var id = $("#pilih_warga").val();
        alert("Silahkan Cek di Console Browser (CTRL+SHIFT+L)")
        $.ajax({
            type: 'POST',
            data: "id_warga="+id+"&aksi=riwayat_iyuran",
            url: './Api.php',
            success: function(res){
                console.log(res)
            },
            error: function(res){
                console.log(res)
            }
        })
    }
    function tampil_pengeluaran(){
        alert("Silahkan Cek di Console Browser (CTRL+SHIFT+L)")
        $.ajax({
            type: 'POST',
            data: "aksi=daftar_pengeluaran",
            url: './Api.php',
            success: function(res){
                console.log(res)
            },
            error: function(res){
                console.log(res)
            }
        })
    }
    function tampil_pengumuman(){
        alert("Silahkan Cek di Console Browser (CTRL+SHIFT+L)")
        $.ajax({
            type: 'POST',
            data: "aksi=daftar_pengumuman",
            url: './Api.php',
            success: function(res){
                console.log(res)
            },
            error: function(res){
                console.log(res)
            }
        })
    }
    function tampil_pengaduan(){
        alert("Silahkan Cek di Console Browser (CTRL+SHIFT+L)")
        $.ajax({
            type: 'POST',
            data: "aksi=daftar_pengaduan",
            url: './Api.php',
            success: function(res){
                console.log(res)
            },
            error: function(res){
                console.log(res)
            }
        })
    }
    function tampil_pemasukan_iyuran_sampah(){
        alert("Silahkan Cek di Console Browser (CTRL+SHIFT+L)")
        $.ajax({
            type: 'POST',
            data: "aksi=pemasukan_iyuran_sampah",
            url: './Api.php',
            success: function(res){
                console.log(res)
            },
            error: function(res){
                console.log(res)
            }
        })
    }
    function tampil_pemasukan_iyuran_kematian(){
        alert("Silahkan Cek di Console Browser (CTRL+SHIFT+L)")
        $.ajax({
            type: 'POST',
            data: "aksi=pemasukan_iyuran_kematian",
            url: './Api.php',
            success: function(res){
                console.log(res)
            },
            error: function(res){
                console.log(res)
            }
        })
    }
    function tampil_pemasukan_iyuran(){
        alert("Silahkan Cek di Console Browser (CTRL+SHIFT+L)")
        $.ajax({
            type: 'POST',
            data: "aksi=pemasukan_iyuran",
            url: './Api.php',
            success: function(res){
                console.log(res)
            },
            error: function(res){
                console.log(res)
            }
        })
    }
    
    function tampil_warga(){
        alert("Silahkan Cek di Console Browser (CTRL+SHIFT+L)")
        $.ajax({
            type: 'POST',
            data: "aksi=daftar_warga",
            url: './Api.php',
            success: function(res){
                console.log(res)
            },
            error: function(res){
                console.log(res)
            }
        })
    }
    function tampil_laporan(){
        alert("Silahkan Cek di Console Browser (CTRL+SHIFT+L)")
        $.ajax({
            type: 'POST',
            data: "aksi=buat_laporan",
            url: './Api.php',
            success: function(res){
                console.log(res)
            },
            error: function(res){
                console.log(res)
            }
        })
    }

    $("#tambah_warga").on('submit' , function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            data: $(this).serialize() + "&aksi=tambah_warga",
            url: './Api.php',
            success: function(res){
                console.log(res)
            },
            error: function(res){
                console.log(res)
            }
        })
    })
    $("#form_login").on('submit' , function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            data: $(this).serialize() + "&aksi=login",
            url: './Api.php',
            success: function(res){
                alert(res.pesan)
                console.log(res)
            },
            error: function(res){
                console.log(res)
            }
        })
    })
    $("#tambah_pengumuman").on('submit' , function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'Api.php',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            success: function(res){ //console.log(response);
                console.log(res)
            },
        });
    })
    $("#tambah_pengaduan").on('submit' , function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'Api.php',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            success: function(res){ //console.log(response);
                console.log(res)
            }
        });
    })

    // Catat Iyuran
    $("#catat_iyuran").on('submit' , function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            data: $(this).serialize() + "&aksi=catat_iyuran",
            url: './Api.php',
            success: function(res){
                console.log(res)
            },
            error: function(res){
                console.log(res)
            }
        })
    })
    $("#tambah_pengeluaran").on('submit' , function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            data: $(this).serialize() + "&aksi=tambah_pengeluaran",
            url: './Api.php',
            success: function(res){
                console.log(res)
            },
            error: function(res){
                console.log(res)
            }
        })
    })

</script>
</html>