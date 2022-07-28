<?php
include_once "Config.php";

$semua_warga = $conn->query("SELECT * FROM warga");
$semua_pengeluaran = $conn->query("SELECT * FROM pengeluaran_kas");
$semua_pengumuman = $conn->query("SELECT * FROM pengumuman");
$semua_pengaduan = $conn->query("SELECT * FROM pengaduan");
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
    <h3>CRUD Warga</h3>
    <select id="daftar_warga">
        <option value="">-- Pilih Warga --</option>
        <?php while($row = $semua_warga->fetch_assoc()) :?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['nama_warga']; ?></option>
        <?php endwhile; ?>
    </select><br><br>
    <form action="" id="form_warga">
        <input type="hidden" name='id' id="id_warga" value=''>
        <input type="text" name="nama_warga" id='nama_warga' placeholder="Nama Warga"><br>
        <input type="text" name="no_telp" id='no_telp_warga' placeholder="Nomor Telepon"><br>
        <textarea type="text" name="alamat" id='alamat_warga' placeholder="Alamat"></textarea><br>
        <button type="submit">Edit</button>
        <button type="button" onclick="hapus_warga()">Hapus</button>
    </form>
    <h3>CRUD Data Pengeluaran</h3>
    <select id="daftar_pengeluaran">
        <option value="">-- Pilih Pengeluaran --</option>
        <?php while($row = $semua_pengeluaran->fetch_assoc()) :?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['alasan']; ?></option>
        <?php endwhile; ?>
    </select><br><br>
    <form action="" id="form_pengeluaran">
        <input type="hidden" name='id' id='id_pengeluaran' value=''>
        <input type="text" name="jumlah_pengeluaran" id="jumlah_pengeluaran" placeholder="Jumlah Pengeluaran">
        <input type="text" name="alasan" id="alasan_pengeluaran" placeholder="Alasan">
        <button type="submit">Edit</button>
        <button type="button" onclick="hapus_pengeluaran()">Hapus</button>
    </form>
    <h3>CRUD Data Pengumuman</h3>
    <select id="daftar_pengumuman">
        <option value="">-- Pilih Penguman --</option>
        <?php while($row = $semua_pengumuman->fetch_assoc()) :?>
            <option value="<?php echo $row['id_pengumuman']; ?>"><?php echo $row['judul']; ?></option>
        <?php endwhile; ?>
    </select><br><br>
    <form action="" id="form_pengumuman">
        <input type="hidden" name='id' id='id_pengumuman' value=''>
        <input type="hidden" name="aksi" value='edit_pengumuman'>
        <input type="text" name="judul" id="judul_pengumuman" placeholder="Judul"><br>
        <input type="text" name="pembuat" id="pembuat_pengumuman" placeholder="Nama Pembuat"><br>
        <select name="id_kategori" id="id_kategori_pengumuman">
            <option value="">-- Pilih Kategori --</option>
            <?php while($row = $semua_kategori->fetch_assoc()) :?>
                <option value="<?php echo $row['id_kategori']; ?>"><?php echo $row['nama_kategori']; ?></option>
            <?php endwhile; ?>
        </select>
        <img id="foto_pengumuman" src="" alt="" style="width:100px;"><br>
        <input type="file" accept="image/x-png,image/gif,image/jpeg" name="file"><br>
        <textarea name="isi" id="isi_pengumuman"></textarea><br>
        <button type="submit">Edit</button>
        <button type="button" onclick="hapus_pengumuman()">Hapus</button>
    </form>
    <h3>CRUD Data Pengaduan</h3>
    <select id="daftar_pengaduan">
        <option value="">-- Pilih Pengaduan --</option>
        <?php while($row = $semua_pengaduan->fetch_assoc()) :?>
            <option value="<?php echo $row['id_pengaduan']; ?>"><?php echo $row['judul']; ?></option>
        <?php endwhile; ?>
    </select><br><br>
    <form action="" id="form_pengaduan">
        <input type="hidden" name='id' id='id_pengaduan' value=''>
        <input type="hidden" name="aksi" value='edit_pengaduan'>
        <input type="text" name="judul" id="judul_pengaduan" placeholder="Judul"><br>
        <input type="text" name="pembuat" id="pembuat_pengaduan" placeholder="Nama Pembuat"><br>
        <select name="id_kategori" id='id_kategori_pengaduan'>
            <option value="">-- Pilih Kategori --</option>
            <?php while($row = $semua_kategori2->fetch_assoc()) :?>
                <option value="<?php echo $row['id_kategori']; ?>"><?php echo $row['nama_kategori']; ?></option>
            <?php endwhile; ?>
        </select><br>
        <img id="foto_pengaduan" src="" alt="" style="width:100px;"><br>
        <input type="file" accept="image/x-png,image/gif,image/jpeg" name="file"><br>
        <textarea name="isi" id="isi_pengaduan"></textarea><br>
        <button type="submit">Edit</button>
        <button type="button" onclick="hapus_pengaduan()">Hapus</button>
    </form>
</body>
<script src="./jquery-3.4.1.min.js"></script>
<script>
    $("#form_warga").on("submit" , function(e){
        e.preventDefault()
        var data = $(this).serialize();
        $.ajax({
                type: 'POST',
                url: 'Api.php',
                data: data + "&aksi=edit_warga",
                success: function(res){
                    console.log(res);
                    alert(res.pesan);
                }
            })
    })
    function hapus_warga(){
        var id = $("#daftar_warga").val();
        if (id != ''){
            $.ajax({
                type: 'POST',
                url: 'Api.php',
                data: "id=" + id + "&aksi=hapus_warga",
                success: function(res){
                    console.log(res);
                    alert(res.pesan);
                    alert("Refresh halaman untuk melihat hasil")
                }
            })
        }else{
            alert("Tolong Pilih Warga Dulu");
        }
    }
    $("#daftar_warga").on('change' , function(){
        var id = $(this).val();
        if (id != ''){
            $.ajax({
                type: 'POST',
                url: 'Api.php',
                data: "id=" + id + "&aksi=detail_warga",
                success: function(res){
                    console.log(res);
                    if(res.success){
                        $("#id_warga").val(res.data.id);
                        $("#nama_warga").val(res.data.nama_warga);
                        $("#no_telp_warga").val(res.data.no_telp);
                        $("#alamat_warga").val(res.data.alamat);
                    }else{
                        alert(res.pesan);
                    }
                }
            })
        }else{
            alert("Tolong Pilih Warga Dulu");
        }
    })
    $("#daftar_pengeluaran").on('change' , function(){
        var id = $(this).val();
        if (id != ''){
            $.ajax({
                type: 'POST',
                url: 'Api.php',
                data: "id=" + id + "&aksi=detail_pengeluaran",
                success: function(res){
                    console.log(res);
                    if(res.success){
                        $("#id_pengeluaran").val(res.data.id);
                        $("#jumlah_pengeluaran").val(res.data.jumlah_pengeluaran);
                        $("#alasan_pengeluaran").val(res.data.alasan);
                    }else{
                        alert(res.pesan);
                    }
                }
            })
        }else{
            alert("Tolong Pilih Warga Dulu");
        }
    })
    $("#form_pengeluaran").on("submit" , function(e){
        e.preventDefault()
        var data = $(this).serialize();
        $.ajax({
                type: 'POST',
                url: 'Api.php',
                data: data + "&aksi=edit_pengeluaran",
                success: function(res){
                    console.log(res);
                    alert(res.pesan);
                }
            })
    })
    function hapus_pengeluaran(){
        var id = $("#daftar_pengeluaran").val();
        if (id != ''){
            $.ajax({
                type: 'POST',
                url: 'Api.php',
                data: "id=" + id + "&aksi=hapus_pengeluaran",
                success: function(res){
                    console.log(res);
                    alert(res.pesan);
                    alert("Refresh halaman untuk melihat hasil")
                }
            })
        }else{
            alert("Tolong Pilih Pengeluaran Dulu");
        }
    }
    $("#daftar_pengumuman").on('change' , function(){
        var id = $(this).val();
        if (id != ''){
            $.ajax({
                type: 'POST',
                url: 'Api.php',
                data: "id=" + id + "&aksi=detail_pengumuman",
                success: function(res){
                    console.log(res);
                    if(res.success){
                        $("#id_pengumuman").val(res.data.id_pengumuman);
                        $("#judul_pengumuman").val(res.data.judul);
                        $("#pembuat_pengumuman").val(res.data.pembuat);
                        $("#id_kategori_pengumuman option")
                            .removeAttr('selected')
                            .filter('[value=' + res.data.id_kategori + ']')
                                .attr('selected' , true)
                        $("#isi_pengumuman").val(res.data.isi);
                        $("#foto_pengumuman").attr('src',res.data.foto);
                    }else{
                        alert(res.pesan);
                    }
                }
            })
        }else{
            alert("Tolong Pilih Warga Dulu");
        }
    })
    $("#form_pengumuman").on("submit" , function(e){
        e.preventDefault()
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
    function hapus_pengumuman(){
        var id = $("#daftar_pengumuman").val();
        if (id != ''){
            $.ajax({
                type: 'POST',
                url: 'Api.php',
                data: "id=" + id + "&aksi=hapus_pengumuman",
                success: function(res){
                    console.log(res);
                    alert(res.pesan);
                    alert("Refresh halaman untuk melihat hasil")
                }
            })
        }else{
            alert("Tolong Pilih Pengumuman Dulu");
        }
    }
    $("#daftar_pengaduan").on('change' , function(){
        var id = $(this).val();
        if (id != ''){
            $.ajax({
                type: 'POST',
                url: 'Api.php',
                data: "id=" + id + "&aksi=detail_pengaduan",
                success: function(res){
                    console.log(res);
                    if(res.success){
                        $("#id_pengaduan").val(res.data.id_pengaduan);
                        $("#judul_pengaduan").val(res.data.judul);
                        $("#pembuat_pengaduan").val(res.data.pembuat);
                        $("#id_kategori_pengaduan option")
                            .removeAttr("selected")
                            .filter('[value=' + res.data.id_kategori + "]")
                                .attr('selected' , true)
                        $("#isi_pengaduan").val(res.data.isi);
                        $("#foto_pengaduan").attr('src',res.data.foto);
                    }else{
                        alert(res.pesan);
                    }
                }
            })
        }else{
            alert("Tolong Pilih Pengaduan Dulu");
        }
    })
    $("#form_pengaduan").on("submit" , function(e){
        e.preventDefault()
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
                alert(res.pesan)
            }
        });
    })
    function hapus_pengaduan(){
        var id = $("#daftar_pengaduan").val();
        if (id != ''){
            $.ajax({
                type: 'POST',
                url: 'Api.php',
                data: "id=" + id + "&aksi=hapus_pengaduan",
                success: function(res){
                    console.log(res);
                    alert(res.pesan);
                    alert("Refresh halaman untuk melihat hasil")
                }
            })
        }else{
            alert("Tolong Pilih Pengaduan Dulu");
        }
    }
</script>
</html>