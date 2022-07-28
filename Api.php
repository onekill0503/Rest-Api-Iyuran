<?php 
include_once "Config.php";
header('Content-Type: application/json');

// Receive JSON Data
// $request = json_decode(file_get_contents('php://input') , true);
// Receive Post Data
$request = $_POST;

if (!empty($request)){
    $aksi = mysqli_real_escape_string($conn , $request['aksi']);
    if ($aksi == 'tambah_warga'){
        $support_ext = ['jpg','png','jpeg'];
        $nama_warga = @mysqli_real_escape_string($conn , $request['nama_warga']);
        $email = @mysqli_real_escape_string($conn , $request['email']);
        $nik = @mysqli_real_escape_string($conn , $request['nik']);
        $alamat = @mysqli_real_escape_string($conn , $request['alamat']);
        $no_telp = @mysqli_real_escape_string($conn , $request['no_telp']);
        $level_warga = @mysqli_real_escape_string($conn , $request['id_level']);
        $password = @mysqli_real_escape_string($conn , $request['password']);

        $files = $_FILES;
        $foto = '';
        $upload_status = true;
        $upload_error = false;
        
        if (!empty($nama_warga) && !empty($email) && !empty($alamat) && !empty($no_telp) && !empty($password)){
            if ($conn->query("SELECT * FROM warga WHERE email='$email'")->num_rows > 0){
                $respon = array(
                    'success' => false,
                    'pesan' => "Email Sudah digunakan oleh Warga yang lain",
                );
                echo json_encode($respon);
            }else{
                if (!empty($files['file']['name'])){
                    if ($files['file']['error'] != 0){
                        $upload_error = true;
                        if ($files['file']['error'] == 1){
                            $respon = array(
                                'success' => false,
                                'pesan' => "File Terlalu Besar, Max 2MB",
                            );
                            echo json_encode($respon);
                        }else{
                            $respon = array(
                                'success' => false,
                                'pesan' => $files['file']['error'],
                            );
                            echo json_encode($respon);
                        }
                    }else{
                        $ext = pathinfo($files['file']['name'], PATHINFO_EXTENSION);
                        if (in_array($ext ,$support_ext)){
                            $upload_status = move_uploaded_file($files['file']['tmp_name'] , './images/warga/' . $files['file']['name']);
                            $foto = './images/warga/' . $files['file']['name'];
                        }else{
                            $upload_error = true;
                            $respon = array(
                                'success' => false,
                                'pesan' => "Ekstensi File bukan gambar",
                            );
                            echo json_encode($respon);
                        }
                    }
                }
                if ($upload_error != true){
                    $query = $conn->query("INSERT INTO warga (nama_warga,nik,foto,email,password,alamat,no_telp,id_level) VALUES('$nama_warga' , '$nik' , '$foto' , '$email' , '" . md5($password) . "' , '$alamat','$no_telp',$level_warga)");
                    if ($query && $upload_status){
                        $respon = array(
                            'success' => true,
                            'pesan' => "Berhasil Menambahkan Warga"
                        );
                        echo json_encode($respon);
                    }else{
                        $respon = array(
                            'success' => false,
                            'pesan' => "Gagal Menambahkan Warga"
                        );
                        echo json_encode($respon);
                    }   
                }
            }
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Tolong Isi Bagian Yang Kosong"
            );
            echo json_encode($respon);
        }
    }elseif($aksi == 'login'){
        $email = @mysqli_real_escape_string($conn , $request['email']);
        $password = @mysqli_real_escape_string($conn , $request['password']);
        if (!empty($email) && !empty($password)){
            $query = @$conn->query("SELECT * FROM warga WHERE email='$email' AND password='" . md5($password) . "'");
            if ($query->num_rows > 0){
                $respon = array(
                    'success' => true,
                    'pesan' => "Berhasil Login",
                    'data_warga' => $query->fetch_assoc()
                );
                echo json_encode($respon);
            }else{
                $respon = array(
                    'success' => false,
                    'pesan' => "Email atau Password Salah"
                );
                echo json_encode($respon);
            }
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Tolong Isi Bagian Yang Kosong"
            );
            echo json_encode($respon);
        }
    }elseif($aksi == 'detail_warga'){
        $id = @mysqli_real_escape_string($conn , $request['id']);
        $query = $conn->query("SELECT * FROM warga WHERE id='$id'");
        if ($query->num_rows > 0){
            $respon = array(
                'success' => true,
                'data' => $query->fetch_assoc(),
            );
            echo json_encode($respon);
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Warga Tidak Terdaftar",
            );
            echo json_encode($respon);
        }
    }elseif($aksi == 'hapus_warga'){
        $id = @mysqli_real_escape_string($conn , $request['id']);
        if (!empty($id)){
            if ($conn->query("SELECT * FROM warga WHERE id='$id'")->num_rows > 0){
                $query = $conn->query("DELETE FROM warga WHERE id='$id'");
                if ($query){
                    $respon = array(
                        'success' => true,
                        'pesan' => "Berhasil Menghapus Warga",
                    );
                    echo json_encode($respon);
                }else{
                    $respon = array(
                        'success' => false,
                        'pesan' => "Gagal Menghapus Warga",
                    );
                    echo json_encode($respon);
                }
            }else{
                $respon = array(
                    'success' => false,
                    'pesan' => "Data Warga Tidak ada",
                );
                echo json_encode($respon);
            }
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Id Tidak Boleh Kosong",
            );
            echo json_encode($respon);
        }
    }elseif($aksi == 'edit_warga'){
        $id = @mysqli_real_escape_string($conn , $request['id']);
        $nama_warga = @mysqli_real_escape_string($conn , $request['nama_warga']);
        $nik = @mysqli_real_escape_string($conn , $request['nik']);
        $alamat = @mysqli_real_escape_string($conn , $request['alamat']);
        $no_telp = @mysqli_real_escape_string($conn , $request['no_telp']);
        $email = @mysqli_real_escape_string($conn , $request['email']);
        $password = @mysqli_real_escape_string($conn , $request['password']);

        $data_lama = $conn->query("SELECT * FROM warga WHERE id='$id'")->fetch_assoc();

        $foto = $data_lama['foto'];
        $files = $_FILES;
        $upload_status = true;
        $upload_error = false;
        
        if(empty($files['file']['name']) && empty($id) && empty($nama_warga) && empty($alamat) && empty($no_telp) && empty($email)){
            $respon = array(
                'success' => false,
                'pesan' => "Tidak Ada Data Yang di Ubah",
            );
            echo json_encode($respon);
        }else{
            if(!empty($id)){
                if(empty($nama_warga)){
                    $nama_warga = $data_lama['nama_warga']; 
                 }
                 if(empty($nik)){
                     $nik = $data_lama['nik']; 
                  }
                  if(empty($alamat)){
                     $alamat = $data_lama['alamat']; 
                  }
                  if(empty($no_telp)){
                     $no_telp = $data_lama['no_telp']; 
                  }
                  if(empty($password)){
                     $password = $data_lama['password']; 
                  }else{
                     $password = md5($password);
                  }
                 if (!empty($files['file']['name'])){
                     if ($files['file']['error'] != 0){
                         $upload_error = true;
                         if ($files['file']['error'] == 1){
                             $respon = array(
                                 'success' => false,
                                 'pesan' => "File Terlalu Besar, Max 2MB",
                             );
                             echo json_encode($respon);
                         }else{
                             $respon = array(
                                 'success' => false,
                                 'pesan' => $files['file']['error'],
                             );
                             echo json_encode($respon);
                         }
                     }else{
                         $ext = pathinfo($files['file']['name'], PATHINFO_EXTENSION);
                         if (in_array($ext ,$support_ext)){
                             @unlink($data_lama['foto']);
                             $upload_status = move_uploaded_file($files['file']['tmp_name'] , './images/warga/' . $files['file']['name']);
                             $foto = './images/warga/' . $files['file']['name'];
                         }else{
                             $upload_error = true;
                             $respon = array(
                                 'success' => false,
                                 'pesan' => "Ekstensi File bukan gambar",
                             );
                             echo json_encode($respon);
                         }
                     }
                 }
                 if(empty($email)){
                     $email = $data_lama['email'];
                 }else{
                     if ($email == $data_lama['email']){
                         $upload_error = true;
                         $respon = array(
                             'success' => false,
                             'pesan' => "Email tidak boleh sama seperti yang lama",
                         );
                         echo json_encode($respon);
                     }else{
                         if ($conn->query("SELECT * FROM warga WHERE email='$email'")->num_rows > 0){
                             $upload_error = true;
                             $respon = array(
                                 'success' => false,
                                 'pesan' => "Email Sudah Digunakan Oleh Warga Lain",
                             );
                             echo json_encode($respon);
                         }
                     }
                 }
     
                 if ($upload_error != true){
                     $query = $conn->query("UPDATE warga SET nama_warga='$nama_warga',nik='$nik',foto='$foto',alamat='$alamat',no_telp='$no_telp',email='$email',password='$password' WHERE id='$id'");
                     if ($query){
                         $respon = array(
                             'success' => true,
                             'pesan' => "Berhasil Memperbarui Data Warga",
                         );
                         echo json_encode($respon);
                     }else{
                         $respon = array(
                             'success' => false,
                             'pesan' => "Gagal Memperbarui Data Warga",
                         );
                         echo json_encode($respon);
                     }
                 }
            }else{
                $respon = array(
                    'success' => false,
                    'pesan' => "Id tidak boleh kosong",
                );
                echo json_encode($respon);
            }
        }
    }elseif($aksi == 'detail_pemasukan'){
        $id = @mysqli_real_escape_string($conn , $request['id']);
        $query = $conn->query("SELECT * FROM pemasukan_kas WHERE id='$id'");
        if ($query->num_rows > 0){
            $respon_data = $query->fetch_assoc();
            $respon_data['nama_petugas'] = @$conn->query("SELECT * FROM warga WHERE id='" . $respon_data['id_petugas'] . "'")->fetch_assoc()['nama_warga'];
            $respon = array(
                'success' => true,
                'data' => $respon_data,
            );
            echo json_encode($respon);
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Data Pemasukan Tidak Ada",
            );
            echo json_encode($respon);
        }
    }elseif($aksi == 'detail_pengeluaran'){
        $id = @mysqli_real_escape_string($conn , $request['id']);
        $query = $conn->query("SELECT * FROM pengeluaran_kas WHERE id='$id'");
        if ($query->num_rows > 0){
            $respon_data = $query->fetch_assoc();
            $respon_data['nama_petugas'] = @$conn->query("SELECT * FROM warga WHERE id='" . $respon_data['id_petugas'] . "'")->fetch_assoc()['nama_warga'];
            $respon = array(
                'success' => true,
                'data' => $respon_data,
            );
            echo json_encode($respon);
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Pengeluaran Tidak Ada",
            );
            echo json_encode($respon);
        }
    }elseif($aksi == 'hapus_pemasukan'){
        $id = @mysqli_real_escape_string($conn , $request['id']);
        if (!empty($id)){
            if(@$conn->query("SELECT * FROM pemasukan_kas WHERE id='$id'")->num_rows > 0){
                $query = $conn->query("DELETE FROM pemasukan_kas WHERE id='$id'");
                if ($query){
                    $respon = array(
                        'success' => true,
                        'pesan' => "Berhasil Menghapus Data Pemasukan",
                    );
                    echo json_encode($respon);
                }else{
                    $respon = array(
                        'success' => false,
                        'pesan' => "Gagal Menghapus Data Pemasukan",
                    );
                    echo json_encode($respon);
                }
            }else{
                $respon = array(
                    'success' => false,
                    'pesan' => "Data Pengeluaran Tidak Ada",
                );
                echo json_encode($respon);
            }
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Id Tidak Boleh Kosong",
            );
            echo json_encode($respon);
        }
    }elseif($aksi == 'hapus_pengeluaran'){
        $id = @mysqli_real_escape_string($conn , $request['id']);
        if (!empty($id)){
            if(@$conn->query("SELECT * FROM pengeluaran_kas WHERE id='$id'")->num_rows > 0){
                $query = $conn->query("DELETE FROM pengeluaran_kas WHERE id='$id'");
                if ($query){
                    $respon = array(
                        'success' => true,
                        'pesan' => "Berhasil Menghapus Data Pengeluaran",
                    );
                    echo json_encode($respon);
                }else{
                    $respon = array(
                        'success' => false,
                        'pesan' => "Gagal Menghapus Data Pengeluaran",
                    );
                    echo json_encode($respon);
                }
            }else{
                $respon = array(
                    'success' => false,
                    'pesan' => "Data Pengeluaran Tidak Ada",
                );
                echo json_encode($respon);
            }
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Id Tidak Boleh Kosong",
            );
            echo json_encode($respon);
        }
    }elseif($aksi == 'edit_pemasukan'){
        $id = @mysqli_real_escape_string($conn , $request['id']);
        $jumlah_pemasukan = @mysqli_real_escape_string($conn , $request['jumlah_pemasukan']);
        $alasan = @mysqli_real_escape_string($conn , $request['alasan']);
        $id_petugas = @mysqli_real_escape_string($conn , $request['id_petugas']);
        $data_lama = $conn->query("SELECT * FROM pemasukan_kas WHERE id='$id'")->fetch_assoc();
        if(empty($id) && empty($jumlah_pemasukan) && empty($alasan)){
            $respon = array(
                'success' => false,
                'pesan' => "Tidak Ada Data Yang di Ubah",
            );
            echo json_encode($respon);
        }else{
            if (!empty($id)){
                if (empty($jumlah_pemasukan)){
                    $jumlah_pemasukan = $data_lama['jumlah_pemasukan'];
                }
                if (empty($alasan)){
                    $alasan = $data_lama['alasan'];
                }
                if (empty($id_petugas)){
                    $id_petugas = $data_lama['id_petugas'];
                }
                $query = @$conn->query("UPDATE pemasukan_kas SET jumlah_pemasukan='$jumlah_pemasukan',alasan='$alasan',id_petugas='$id_petugas' WHERE id='$id'");
                if ($query){
                    $respon = array(
                        'success' => true,
                        'pesan' => "Berhasil memperbarui data pemasukan",
                    );
                    echo json_encode($respon);
                }else{
                    $respon = array(
                        'success' => false,
                        'pesan' => "Gagal memperbarui data pemasukan",
                    );
                    echo json_encode($respon);
                }
            }else{
                $respon = array(
                    'success' => false,
                    'pesan' => "Id tidak boleh kosong",
                );
                echo json_encode($respon);
            }
        }
    }elseif($aksi == 'edit_pengeluaran'){
        $id = @mysqli_real_escape_string($conn , $request['id']);
        $jumlah_pengeluaran = @mysqli_real_escape_string($conn , $request['jumlah_pengeluaran']);
        $alasan = @mysqli_real_escape_string($conn , $request['alasan']);
        $id_petugas = @mysqli_real_escape_string($conn , $request['id_petugas']);
        $data_lama = $conn->query("SELECT * FROM pengeluaran_kas WHERE id='$id'")->fetch_assoc();
        if(empty($id) && empty($jumlah_pengeluaran) && empty($alasan)){
            $respon = array(
                'success' => false,
                'pesan' => "Tidak Ada Data Yang di Ubah",
            );
            echo json_encode($respon);
        }else{
            if(!empty($id)){
                if (empty($jumlah_pengeluaran)){
                    $jumlah_pengeluaran = $data_lama['jumlah_pengeluaran'];
                }
                if (empty($alasan)){
                    $alasan = $data_lama['alasan'];
                }
                if (empty($id_petugas)){
                    $id_petugas = $data_lama['id_petugas'];
                }
                $query = @$conn->query("UPDATE pengeluaran_kas SET jumlah_pengeluaran='$jumlah_pengeluaran',alasan='$alasan',id_petugas='$id_petugas' WHERE id='$id'");
                if ($query){
                    $respon = array(
                        'success' => true,
                        'pesan' => "Berhasil memperbarui data pengeluaran",
                    );
                    echo json_encode($respon);
                }else{
                    $respon = array(
                        'success' => false,
                        'pesan' => "Gagal memperbarui data pengeluaran",
                    );
                    echo json_encode($respon);
                }
            }else{
                $respon = array(
                    'success' => false,
                    'pesan' => "Id tidak boleh kosong",
                );
                echo json_encode($respon);
            }
        }
    }elseif($aksi == 'detail_pengumuman'){
        $id = @mysqli_real_escape_string($conn , $request['id']);
        $query = $conn->query("SELECT * FROM pengumuman WHERE id_pengumuman='$id'");
        if ($query->num_rows > 0){
            $respon_data = $query->fetch_assoc();
            $respon_data['pembuat'] = @$conn->query("SELECT * FROM warga WHERE id='" . $respon_data['pembuat'] . "'")->fetch_assoc()['nama_warga'];
            $respon_data['kategori'] = @$conn->query("SELECT * FROM kategori WHERE id_kategori='" . $respon_data['id_kategori'] . "'")->fetch_assoc()['nama_kategori'];
            $respon = array(
                'success' => true,
                'data' => $respon_data,
            );
            echo json_encode($respon);
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Pengumuman Tidak Ada",
            );
            echo json_encode($respon);
        }
    }elseif($aksi == 'hapus_pengumuman'){
        $id = @mysqli_real_escape_string($conn , $request['id']);
        if (!empty($id)){
            if (@$conn->query("SELECT * FROM pengumuman WHERE id_pengumuman='$id'")->num_rows > 0){
                $foto = $conn->query("SELECT * FROM pengumuman WHERE id_pengumuman='$id'")->fetch_assoc()['foto'];
                $query = $conn->query("DELETE FROM pengumuman WHERE id_pengumuman='$id'");
                @unlink($foto);
                if ($query){
                    $respon = array(
                        'success' => true,
                        'pesan' => "Berhasil Menghapus Data Pengumuman",
                    );
                    echo json_encode($respon);
                }else{
                    $respon = array(
                        'success' => false,
                        'pesan' => "Gagal Menghapus Data Pengumuman",
                    );
                    echo json_encode($respon);
                }
            }else{
                $respon = array(
                    'success' => false,
                    'pesan' => "Data Pengumuman Tidak Ada",
                );
                echo json_encode($respon);
            }
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Id Tidak Boleh Kosong",
            );
            echo json_encode($respon);
        }
    }elseif($aksi == 'edit_pengumuman'){
        $support_ext = ['jpg','png','jpeg'];
        $id = @mysqli_real_escape_string($conn , $request['id']);
        $judul = @mysqli_real_escape_string($conn , $request['judul']);
        $pembuat = @mysqli_real_escape_string($conn , $request['pembuat']);
        $id_kategori = @mysqli_real_escape_string($conn , $request['id_kategori']);
        $isi = @mysqli_real_escape_string($conn , $request['isi']);
        $data_lama = $conn->query("SELECT * FROM pengumuman WHERE id_pengumuman='$id'")->fetch_assoc();
        $files = $_FILES;
        $foto_lama = $conn->query("SELECT * FROM pengumuman WHERE id_pengumuman='$id'")->fetch_assoc()['foto'];
        if(empty($id) && empty($judul) && empty($pembuat) && empty($id_kategori) && empty($isi) && empty($files['file']['name'])){
            $respon = array(
                'success' => false,
                'pesan' => "Tidak ada Data yang Diubah"
            );
            echo json_encode($respon);
        }else{
            if(!empty($id)){
                if(empty($judul)){
                    $judul = $data_lama['judul'];
                }
                if(empty($pembuat)){
                    $pembuat = $data_lama['pembuat'];
                }
                if(empty($id_kategori)){
                    $id_kategori = $data_lama['id_kategori'];
                }
                if(empty($isi)){
                    $isi = $data_lama['isi'];
                }
                $foto_update = '';
                if (!empty($files['file']['name'])){
                    $ext = pathinfo($files['file']['name'], PATHINFO_EXTENSION);
                    if (in_array($ext ,$support_ext)){
                        $foto_update = './images/pengumuman/' . $files['file']['name'];
                        @unlink($foto_lama);
                        $upload = move_uploaded_file($files['file']['tmp_name'] , './images/pengumuman/' . $files['file']['name']);
                        $query = $conn->query("UPDATE pengumuman SET judul='$judul',pembuat='$pembuat',id_kategori='$id_kategori',isi='$isi',foto='$foto_update' WHERE id_pengumuman='$id'");
                        if ($query && $upload){
                            $respon = array(
                                'success' => true,
                                'pesan' => "Berhasil Memperbarui Data Pengumuman",
                            );
                            echo json_encode($respon);
                        }else{
                            $respon = array(
                                'success' => false,
                                'pesan' => "Gagal Memperbarui Data Pengumuman",
                            );
                            echo json_encode($respon);
                        }
                    }else{
                        $respon = array(
                            'success' => false,
                            'pesan' => "Ekstensi File bukan gambar",
                        );
                        echo json_encode($respon);
                    }
                }else{
                    $query = $conn->query("UPDATE pengumuman SET judul='$judul',pembuat='$pembuat',id_kategori='$id_kategori',isi='$isi' WHERE id_pengumuman='$id'");
                    if ($query){
                        $respon = array(
                            'success' => true,
                            'pesan' => "Berhasil Memperbarui Data Pengumuman",
                        );
                        echo json_encode($respon);
                    }else{
                        $respon = array(
                            'success' => false,
                            'pesan' => "Gagal Memperbarui Data Pengumuman",
                        );
                        echo json_encode($respon);
                    }
                }
            }else{
                $respon = array(
                    'success' => false,
                    'pesan' => "Id tidak boleh kosong",
                );
                echo json_encode($respon);
            }
        }
        
    }elseif($aksi == 'detail_pengaduan'){
        $id = @mysqli_real_escape_string($conn , $request['id']);
        $query = $conn->query("SELECT * FROM pengaduan WHERE id_pengaduan='$id'");
        if ($query->num_rows > 0){
            $respon_data = $query->fetch_assoc();
            $respon_data['pembuat'] = @$conn->query("SELECT * FROM warga WHERE id='" . $respon_data['pembuat'] . "'")->fetch_assoc()['nama_warga'];
            $respon_data['kategori'] = @$conn->query("SELECT * FROM kategori WHERE id_kategori='" . $respon_data['id_kategori'] . "'")->fetch_assoc()['nama_kategori'];
            $respon = array(
                'success' => true,
                'data' => $respon_data,
            );
            echo json_encode($respon);
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Pengaduan Tidak Ada",
            );
            echo json_encode($respon);
        }
    }elseif($aksi == 'hapus_pengaduan'){
        $id = @mysqli_real_escape_string($conn , $request['id']);
        if(!empty($id)){
            if (@$conn->query("SELECT * FROM pengaduan WHERE id_pengaduan='$id'")->num_rows > 0){
                $foto = $conn->query("SELECT * FROM pengaduan WHERE id_pengaduan='$id'")->fetch_assoc()['foto'];
                $query = $conn->query("DELETE FROM pengaduan WHERE id_pengaduan='$id'");
                @unlink($foto);
                if ($query){
                    $respon = array(
                        'success' => true,
                        'pesan' => "Berhasil Menghapus Data Pengaduan",
                    );
                    echo json_encode($respon);
                }else{
                    $respon = array(
                        'success' => false,
                        'pesan' => "Gagal Menghapus Data Pengaduan",
                    );
                    echo json_encode($respon);
                }
            }else{
                $respon = array(
                    'success' => false,
                    'pesan' => "Data Pengaduan Tidak ada",
                );
                echo json_encode($respon);
            }
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Id tidak boleh kosong",
            );
            echo json_encode($respon);
        }
    }elseif($aksi == 'edit_pengaduan'){
        $support_ext = ['jpg','png','jpeg'];
        $id = @mysqli_real_escape_string($conn , $request['id']);
        $judul = @mysqli_real_escape_string($conn , $request['judul']);
        $pembuat = @mysqli_real_escape_string($conn , $request['pembuat']);
        $id_kategori = @mysqli_real_escape_string($conn , $request['id_kategori']);
        $isi = @mysqli_real_escape_string($conn , $request['isi']);
        $data_lama = $conn->query("SELECT * FROM pengaduan WHERE id_pengaduan='$id'")->fetch_assoc();
        $files = $_FILES;
        $foto_lama = $conn->query("SELECT * FROM pengaduan WHERE id_pengaduan='$id'")->fetch_assoc()['foto'];
        if(empty($id) && empty($judul) && empty($pembuat) && empty($id_kategori) && empty($isi) && empty($files['file']['name'])){
            $respon = array(
                'success' => false,
                'pesan' => "Tidak Ada Data Yang di Ubah",
            );
            echo json_encode($respon);
        }else{
            if(!empty($id)){
                if(empty($judul)){
                    $judul = $data_lama['judul'];
                }
                if(empty($pembuat)){
                    $pembuat = $data_lama['pembuat'];
                }
                if(empty($id_kategori)){
                    $id_kategori = $data_lama['id_kategori'];
                }
                if(empty($isi)){
                    $isi = $data_lama['isi'];
                }
                $foto_update = '';
                if (!empty($files['file']['name'])){
                    $ext = pathinfo($files['file']['name'], PATHINFO_EXTENSION);
                    if (in_array($ext ,$support_ext)){
                        $foto_update = './images/pengaduan/' . $files['file']['name'];
                        @unlink($foto_lama);
                        $upload = move_uploaded_file($files['file']['tmp_name'] , './images/pengaduan/' . $files['file']['name']);
                        $query = $conn->query("UPDATE pengaduan SET judul='$judul',pembuat='$pembuat',id_kategori='$id_kategori',isi='$isi',foto='$foto_update' WHERE id_pengaduan='$id'");
                        if ($query && $upload){
                            $respon = array(
                                'success' => true,
                                'pesan' => "Berhasil Memperbarui Data Pengaduan",
                            );
                            echo json_encode($respon);
                        }else{
                            $respon = array(
                                'success' => false,
                                'pesan' => "Gagal Memperbarui Data Pengaduan",
                            );
                            echo json_encode($respon);
                        }
                    }else{
                        $respon = array(
                            'success' => false,
                            'pesan' => "Ekstensi File bukan gambar",
                        );
                        echo json_encode($respon);
                    }
                }else{
                    $query = $conn->query("UPDATE pengaduan SET judul='$judul',pembuat='$pembuat',id_kategori='$id_kategori',isi='$isi' WHERE id_pengaduan='$id'");
                    if ($query){
                        $respon = array(
                            'success' => true,
                            'pesan' => "Berhasil Memperbarui Data Pengaduan",
                        );
                        echo json_encode($respon);
                    }else{
                        $respon = array(
                            'success' => false,
                            'pesan' => "Gagal Memperbarui Data Pengaduan",
                        );
                        echo json_encode($respon);
                    }
                }
            }else{
                $respon = array(
                    'success' => false,
                    'pesan' => "Id tidak boleh kosong",
                );
                echo json_encode($respon);
            }
        }
        
    }elseif($aksi == 'kirim_invoice'){
        $id_transaksi = @mysqli_real_escape_string($conn , $request['id_transaksi']);
        if (!empty($id_transaksi)){
            $data_transaksi = $conn->query("SELECT * FROM transaksi WHERE id_transaksi='$id_transaksi'");
            if ($data_transaksi->num_rows > 0){
                $fetch_data = $data_transaksi->fetch_assoc();
                $data_warga = $conn->query("SELECT * FROM warga WHERE id='" . $fetch_data['id_warga'] . "'")->fetch_assoc();
                $data_iuran_kematian = $conn->query("SELECT * FROM iuran_kematian WHERE id_iuran='" . $fetch_data['id_iuran_kematian'] . "'")->fetch_assoc();
                $data_iuran_sampah = $conn->query("SELECT * FROM iuran_sampah WHERE id_iuran='" . $fetch_data['id_iuran_sampah'] . "'")->fetch_assoc();
                // Tambah kan bagian ini mas
                if(empty($data_iuran_kematian)){
                    $data_iuran_kematian['jumlah_iuran'] = 0;
                }
                // ------------------------------------
                $from = "example@example.com";
                $to = $data_warga['email'];
                $subject = "Invoice Pembayaran Iyuran";
                $message = file_get_contents('./mail-template/header.html');
                $message .= "<tr style='border:1px solid gray;padding: 15px;'>";
                $message .= "<td style='border:1px solid gray;padding: 15px;font-weight: bold;'>Nama Petugas</td>";
                $message .= "<td style='border:1px solid gray;padding: 15px;'>" . $data_iuran_sampah['id_petugas'] . "</td></tr>";
                $message .= "<tr style='border:1px solid gray;padding: 15px;'>";
                $message .= "<td style='border:1px solid gray;padding: 15px;font-weight: bold;'>Nama Warga</td>";
                $message .= "<td style='border:1px solid gray;padding: 15px;'>" . $data_warga['nama_warga'] . "</td></tr>";
                $message .= "<tr style='border:1px solid gray;padding: 15px;'>";
                $message .= "<td style='border:1px solid gray;padding: 15px;font-weight: bold;'>Jumlah Pembayaran Iyuran Sampah</td>";
                $message .= "<td style='border:1px solid gray;padding: 15px;'>Rp. " . $data_iuran_sampah['jumlah_iuran'] . "</td></tr>";
                $message .= "<tr style='border:1px solid gray;padding: 15px;'>";
                $message .= "<td style='border:1px solid gray;padding: 15px;font-weight: bold;'>Jumlah Pembayaran Iyuran Kematian</td>";
                $message .= "<td style='border:1px solid gray;padding: 15px;'>Rp. " . $data_iuran_kematian['jumlah_iuran'] . "</td></tr>";
                $message .= "<tr style='border:1px solid gray;padding: 15px;'>";
                $message .= "<td style='border:1px solid gray;padding: 15px;font-weight: bold;'>Jumlah Total Pembayaran</td>";
                $message .= "<td style='border:1px solid gray;padding: 15px;'>Rp. " . ($data_iuran_sampah['jumlah_iuran'] + $data_iuran_kematian['jumlah_iuran']) . "</td></tr>";
                $message .= "<tr style='border:1px solid gray;padding: 15px;'>";
                $message .= "<td style='border:1px solid gray;padding: 15px;font-weight: bold;'>Tanggal Pembayaran</td>";
                $message .= "<td style='border:1px solid gray;padding: 15px;'>" . $fetch_data['tanggal'] . "</td></tr>";
                $message .= file_get_contents('./mail-template/footer.html');
                $headers = "From:" . $from . "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                $send = @mail($to,$subject,$message, $headers);
                if ($send){
                    $respon = array(
                        'success' => true,
                        'pesan' => "Berhasil mengirim email invoice",
                    );
                    echo json_encode($respon);
                }else{
                    $respon = array(
                        'success' => false,
                        'pesan' => "Gagal mengirim email invoice",
                    );
                    echo json_encode($respon);
                }
            }else{
                $respon = array(
                    'success' => false,
                    'pesan' => "Data Transaksi tidak ada",
                );
                echo json_encode($respon);
            }
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Id Transaksi tidak boleh kosong",
            );
            echo json_encode($respon);
        }

    }elseif($aksi == 'catat_iuran'){
        $jumlah_iuran = (int)@mysqli_real_escape_string($conn , $request['jumlah_iuran']);
        $id_petugas = @mysqli_real_escape_string($conn ,$request['id_petugas']);
        $tanggal = date('d F Y');
        $periode1 = @mysqli_real_escape_string($conn,$request['periode-1']);
        $periode2 = @mysqli_real_escape_string($conn,$request['periode-2']);
        if (isset($request['kematian'])){
            $iuran_kematian = @mysqli_real_escape_string($conn, $request['kematian']);
        }else{
            $iuran_kematian = '';
        }
        $jumlah_iuran_sampah = 0;
        $isi_periode = '';
        $id_warga = @mysqli_real_escape_string($conn , $request['id_warga']);
        $email_warga = $conn->query("SELECT * FROM warga WHERE id='$id_warga'")->fetch_assoc()['email'];
        $nama_warga = $conn->query("SELECT * FROM warga WHERE id='$id_warga'")->fetch_assoc()['nama_warga'];
        $saldo = (int)$conn->query("SELECT * FROM saldo_kas WHERE id=1")->fetch_assoc()['saldo'];
        $catat = false;
        $tambah_iuran_kematian = true;
        $saldo_sekarang = $saldo + $jumlah_iuran;
        $jumlah_kematian = 1;

        if (!empty($jumlah_iuran) && !empty($id_petugas) && !empty($periode1) && !empty($id_warga)){
            $update_saldo = $conn->query("UPDATE saldo_kas SET saldo='$saldo_sekarang' WHERE id=1");
            $id_sampah = '';
            $id_kematian = '';
            if (empty($periode2) || $periode1 == $periode2){
                $isi_periode = date('F Y' , strtotime($periode1));
            }else{
                $jumlah_kematian = (int)abs((strtotime($periode1) - strtotime($periode2))/(60*60*24*30));
                $isi_periode = date('F Y' , strtotime($periode1)) . "-" . date('F Y' , strtotime($periode2));
            }
            if (!empty($iuran_kematian) && $iuran_kematian == 'iya'){
                $jumlah_iuran_sampah = $jumlah_iuran - ($jumlah_kematian * 4000);
                $tambah_iuran_kematian = $conn->query("INSERT INTO iuran_kematian (id_petugas,tanggal,periode,jumlah_iuran,id_warga) VALUES('$id_petugas' , '$tanggal', '$isi_periode' , '" . $jumlah_kematian * 4000 . "' , '$id_warga')");
                $id_kematian = $conn->insert_id;
            }else{
                $jumlah_iuran_sampah = $jumlah_iuran;
            }
            $catat =$conn->query("INSERT INTO iuran_sampah (id_petugas,tanggal,periode,jumlah_iuran,id_warga) VALUES('$id_petugas' , '$tanggal' , '$isi_periode' , '$jumlah_iuran_sampah' , '$id_warga')");
            $id_sampah = $conn->insert_id;
            $catat_transaksi = $conn->query("INSERT INTO transaksi (tanggal,id_warga,id_iuran_sampah,id_iuran_kematian) VALUES('$tanggal','$id_warga','$id_sampah','$id_kematian')");
            if ($catat && $update_saldo && $tambah_iuran_kematian && $catat_transaksi){
                $respon = array(
                    'success' => true,
                    'pesan' => "Berhasil Mencatat Iyuran Warga"
                );
                echo json_encode($respon);
            }else{
                $respon = array(
                    'success' => false,
                    'pesan' => "Gagal Mencatat Iyuran Warga"
                );
                echo json_encode($respon);
            }
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Tolong Isi Bagian yang Kosong"
            );
            echo json_encode($respon);
        }
    }elseif($aksi == 'daftar_pemasukan'){
        $semua_pemasukan = $conn->query("SELECT * FROM pemasukan_kas");
        $data_pemasukan = array();
        $bulan_ini = array();
        $i_semua = 0;
        $i_bulan = 0;

        while($row = $semua_pemasukan->fetch_assoc()){
            $bulan_apa = date('F' , strtotime($row['tanggal']));
            $nama_petugas = @$conn->query("SELECT * FROM warga WHERE id='" . $row['id_petugas'] . "'")->fetch_assoc()['nama_warga'];
            array_push($data_pemasukan , $row);
            $data_pemasukan[$i_semua]['nama_petugas'] = $nama_petugas;
            $i_semua = $i_semua+1;
            if ($bulan_apa == date('F')){
                array_push($bulan_ini , $row);
                $bulan_ini[$i_bulan]['nama_petugas'] = $nama_petugas;
                $i_bulan = $i_bulan+1;
            }
        }
        $respon_data_pemasukan = "Belum ada data";
        $respon_data_bulan = "Belum ada data";
        if (!empty($data_pemasukan)){
            $respon_data_pemasukan = $data_pemasukan;
            if (!empty($bulan_ini)){
                $respon_data_bulan = $bulan_ini;
            }
        }
        $respon = array(
            'success' => true,
            'semua_pemasukan' => $respon_data_pemasukan,
            'bulan_ini' => $respon_data_bulan,
        );
        echo json_encode($respon);
    }elseif($aksi == 'daftar_pengeluaran'){
        $semua_pengeluaran = $conn->query("SELECT * FROM pengeluaran_kas");
        $data_pengeluaran = array();
        $bulan_ini = array();
        $i_semua = 0;
        $i_bulan = 0;

        while($row = $semua_pengeluaran->fetch_assoc()){
            $bulan_apa = date('F' , strtotime($row['tanggal']));
            $nama_petugas = @$conn->query("SELECT * FROM warga WHERE id='" . $row['id_petugas'] . "'")->fetch_assoc()['nama_warga'];
            array_push($data_pengeluaran , $row);
            $data_pengeluaran[$i_semua]['nama_petugas'] = $nama_petugas;
            $i_semua = $i_semua+1;
            if ($bulan_apa == date('F')){
                array_push($bulan_ini , $row);
                $bulan_ini[$i_bulan]['nama_petugas'] = $nama_petugas;
                $i_bulan = $i_bulan+1;
            }
        }
        $respon_data_pengeluaran = "Belum ada data";
        $respon_data_bulan = "Belum ada data";
        if (!empty($data_pengeluaran)){
            $respon_data_pengeluaran = $data_pengeluaran;
            if (!empty($bulan_ini)){
                $respon_data_bulan = $bulan_ini;
            }
        }
        $respon = array(
            'success' => true,
            'semua_pengeluaran' => $respon_data_pengeluaran,
            'bulan_ini' => $respon_data_bulan,
        );
        echo json_encode($respon);
    }elseif($aksi == 'daftar_pengumuman'){
        $semua_pengumuman = $conn->query("SELECT * FROM pengumuman");
        $data_pengumuman = array();
        $bulan_ini = array();
        $i_semua = 0;
        $i_bulan = 0;

        while($row = $semua_pengumuman->fetch_assoc()){
            $bulan_apa = date('F' , strtotime($row['tanggal']));
            $nama_pembuat = $conn->query("SELECT * FROM warga WHERE id='" . $row['pembuat'] . "'")->fetch_assoc()['nama_warga'];
            $nama_kategori = $conn->query("SELECT * FROM kategori WHERE id_kategori='" . $row['id_kategori'] . "'")->fetch_assoc()['nama_kategori'];
            array_push($data_pengumuman , $row);
            $data_pengumuman[$i_semua]['pembuat'] = $nama_pembuat;
            $data_pengumuman[$i_semua]['kategori'] = $nama_kategori;
            $i_semua = $i_semua+1;
            if ($bulan_apa == date('F')){
                array_push($bulan_ini , $row);
                $bulan_ini[$i_bulan]['pembuat'] = $nama_pembuat;
                $bulan_ini[$i_bulan]['kategori'] = $nama_kategori;
                $i_bulan = $i_bulan+1;
            }
        }
        $respon_data_pengumuman = "Belum ada data";
        $respon_data_bulan = "Belum ada data";
        if (!empty($data_pengumuman)){
            $respon_data_pengumuman = $data_pengumuman;
            if (!empty($bulan_ini)){
                $respon_data_bulan = $bulan_ini;
            }
        }
        $respon = array(
            'success' => true,
            'semua_pengumuman' => $respon_data_pengumuman,
            'bulan_ini' => $respon_data_bulan,
        );
        echo json_encode($respon);
    }elseif($aksi == 'daftar_pengaduan'){
        $semua_pengaduan = $conn->query("SELECT * FROM pengaduan");
        $data_pengaduan = array();
        $bulan_ini = array();
        $i_semua = 0;
        $i_bulan = 0;

        while($row = $semua_pengaduan->fetch_assoc()){
            $bulan_apa = date('F' , strtotime($row['tanggal']));
            $nama_pembuat = $conn->query("SELECT * FROM warga WHERE id='" . $row['pembuat'] . "'")->fetch_assoc()['nama_warga'];
            $nama_kategori = $conn->query("SELECT * FROM kategori WHERE id_kategori='" . $row['id_kategori'] . "'")->fetch_assoc()['nama_kategori'];
            array_push($data_pengaduan , $row);
            $data_pengaduan[$i_semua]['pembuat'] = $nama_pembuat;
            $data_pengaduan[$i_semua]['kategori'] = $nama_kategori;
            $i_semua = $i_semua+1;
            if ($bulan_apa == date('F')){
                array_push($bulan_ini , $row);
                $bulan_ini[$i_bulan]['pembuat'] = $nama_pembuat;
                $bulan_ini[$i_bulan]['kategori'] = $nama_kategori;
                $i_bulan = $i_bulan+1;
            }
        }
        $respon_data_pengaduan = "Belum ada data";
        $respon_data_bulan = "Belum ada data";
        if (!empty($data_pengaduan)){
            $respon_data_pengaduan = $data_pengaduan;
            if (!empty($bulan_ini)){
                $respon_data_bulan = $bulan_ini;
            }
        }
        $respon = array(
            'success' => true,
            'semua_pengumuman' => $respon_data_pengaduan,
            'bulan_ini' => $respon_data_bulan,
        );
        echo json_encode($respon);
    }elseif($aksi == 'riwayat_iuran'){
        $id_warga = @mysqli_real_escape_string($conn , $request['id_warga']);
        $data_iuran_sampah = array();
        $data_iuran_kematian = array();
        
        $record_sampah = $conn->query("SELECT * FROM iuran_sampah WHERE id_warga='$id_warga'");
        $record_kematian = $conn->query("SELECT * FROM iuran_kematian WHERE id_warga='$id_warga'");

        $data_warga = $conn->query("SELECT * FROM warga WHERE id='$id_warga'")->fetch_assoc();

        while($row = $record_sampah->fetch_assoc()){
            if (strpos($row['periode'] , '-') !== false){
                $bulan1 = new DateTime(date('F' , strtotime(explode('-' , $row['periode'])[0])));
                $bulan2 = new DateTime(date('F' , strtotime(explode('-' , $row['periode'])[1])));
                $interval = DateInterval::createFromDateString('1 month');
                $period   = new DatePeriod($bulan1, $interval, $bulan2);
                $row['periode'] = array();
                foreach ($period as $dt) {
                    array_push($row['periode'] , $dt->format('F'));
                }
                array_push($row['periode'] , $bulan2->format('F'));
            }else{
                $tanggal = $row['periode']; 
                $row['periode'] = array();
                array_push($row['periode'] , date('F' , strtotime($tanggal)));
            }
            array_push($data_iuran_sampah , $row);
            
        }
        while($row = $record_kematian->fetch_assoc()){
            if (strpos($row['periode'] , '-') !== false){
                $bulan1 = new DateTime(date('F' , strtotime(explode('-' , $row['periode'])[0])));
                $bulan2 = new DateTime(date('F' , strtotime(explode('-' , $row['periode'])[1])));
                $interval = DateInterval::createFromDateString('1 month');
                $period   = new DatePeriod($bulan1, $interval, $bulan2);
                $row['periode'] = array();
                foreach ($period as $dt) {
                    array_push($row['periode'] , $dt->format('F'));
                }
                array_push($row['periode'] , $bulan2->format('F'));
            }else{
                $tanggal = $row['periode']; 
                $row['periode'] = array();
                array_push($row['periode'] , date('F' , strtotime($tanggal)));
            }
            array_push($data_iuran_kematian,$row);
        }

        $respon = array(
            'success' => true,
            'data_warga' => $data_warga,
            'iuran_sampah' => $data_iuran_sampah,
            'iuran_kematian' => $data_iuran_kematian,
        );
        echo json_encode($respon);
    }elseif($aksi == 'daftar_warga'){
        $semua_record = $conn->query("SELECT * FROM warga");
        $data_warga = array();
        while($row = $semua_record->fetch_assoc()){
            array_push($data_warga , $row);
        }
        $respon = array(
            'success' => true,
            'daftar_warga' => $data_warga,
        );
        echo json_encode($respon);
        
    }elseif ($aksi == 'pemasukan_iuran_sampah'){
        $semua_record = $conn->query("SELECT * FROM iuran_sampah");
        $jumlah_pemasukan = 0;
        $jumlah_bulan_ini = 0;
        while ($row = $semua_record->fetch_assoc()){
            $jumlah_pemasukan = $jumlah_pemasukan + (int)$row['jumlah_iuran'];
            $bulan_apa = date('F' , strtotime($row['tanggal']));
            if ($bulan_apa == date('F')){
                $jumlah_bulan_ini = $jumlah_bulan_ini + (int)$row['jumlah_iuran'];
            }
        }
        $respon = array(
            'success' => true,
            'jumlah_pemasukan_total' => $jumlah_pemasukan,
            'jumlah_bulan_ini' => $jumlah_bulan_ini
        );
        echo json_encode($respon);
    }elseif ($aksi == 'pemasukan_iuran_kematian'){
        $semua_record = $conn->query("SELECT * FROM iuran_kematian");
        $jumlah_pemasukan = 0;
        $jumlah_bulan_ini = 0;
        while ($row = $semua_record->fetch_assoc()){
            $jumlah_pemasukan = $jumlah_pemasukan + (int)$row['jumlah_iuran'];
            $bulan_apa = date('F' , strtotime($row['tanggal']));
            if ($bulan_apa == date('F')){
                $jumlah_bulan_ini = $jumlah_bulan_ini + (int)$row['jumlah_iuran'];
            }
        }
        $respon = array(
            'success' => true,
            'jumlah_pemasukan_total' => $jumlah_pemasukan,
            'jumlah_bulan_ini' => $jumlah_bulan_ini
        );
        echo json_encode($respon);
    }elseif ($aksi == 'pemasukan_iyuran'){
        $semua_record_kematian = $conn->query("SELECT * FROM iuran_kematian");
        $semua_record_sampah = $conn->query("SELECT * FROM iuran_sampah");
        $jumlah_pemasukan = 0;
        $jumlah_bulan_ini = 0;
        while ($row = $semua_record_sampah->fetch_assoc()){
            $jumlah_pemasukan = $jumlah_pemasukan + (int)$row['jumlah_iuran'];
            $bulan_apa = date('F' , strtotime($row['tanggal']));
            if ($bulan_apa == date('F')){
                $jumlah_bulan_ini = $jumlah_bulan_ini + (int)$row['jumlah_iuran'];
            }
        }
        while ($row = $semua_record_kematian->fetch_assoc()){
            $jumlah_pemasukan = $jumlah_pemasukan + (int)$row['jumlah_iuran'];
            $bulan_apa = date('F' , strtotime($row['tanggal']));
            if ($bulan_apa == date('F')){
                $jumlah_bulan_ini = $jumlah_bulan_ini + (int)$row['jumlah_iuran'];
            }
        }
        $respon = array(
            'success' => true,
            'jumlah_pemasukan_total' => $jumlah_pemasukan,
            'jumlah_bulan_ini' => $jumlah_bulan_ini
        );
        echo json_encode($respon);
    }elseif ($aksi == 'tambah_pemasukan'){
        $jumlah_pemasukan = @mysqli_real_escape_string($conn , $request['jumlah_pemasukan']);
        $alasan = @mysqli_real_escape_string($conn , $request['alasan']);
        $tanggal = date('d F Y');
        $id_petugas = @mysqli_real_escape_string($conn , $request['id_petugas']);

        $saldo_sekarang = (int)$conn->query("SELECT * FROM saldo_kas WHERE id=1")->fetch_assoc()['saldo'];
        $saldo_baru = $saldo_sekarang + (int)$jumlah_pemasukan;

        $query = $conn->query("INSERT INTO pemasukan_kas (jumlah_pemasukan,alasan,tanggal,id_petugas) VALUES('$jumlah_pemasukan' , '$alasan','$tanggal','$id_petugas')");
        $tambah_saldo = $conn->query("UPDATE saldo_kas SET saldo='$saldo_baru' WHERE id=1");

        if ($query && $tambah_saldo){
            $respon = array(
                'success' => true,
                'pesan' => "Berhasil Mencatat Pemasukan Kas"
            );
            echo json_encode($respon);
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Gagal Mencatat Pemasukan Kas"
            );
            echo json_encode($respon);
        }
    }elseif ($aksi == 'tambah_pengeluaran'){
        $jumlah_pengeluaran = @mysqli_real_escape_string($conn , $request['jumlah_pengeluaran']);
        $alasan = @mysqli_real_escape_string($conn , $request['alasan']);
        $tanggal = date('d F Y');
        $id_petugas = @mysqli_real_escape_string($conn , $request['id_petugas']);

        $saldo_sekarang = (int)$conn->query("SELECT * FROM saldo_kas WHERE id=1")->fetch_assoc()['saldo'];
        $saldo_baru = $saldo_sekarang - (int)$jumlah_pengeluaran;

        $query = $conn->query("INSERT INTO pengeluaran_kas (jumlah_pengeluaran,alasan,tanggal,id_petugas) VALUES('$jumlah_pengeluaran' , '$alasan','$tanggal','$id_petugas')");
        $kurangi_saldo = $conn->query("UPDATE saldo_kas SET saldo='$saldo_baru' WHERE id=1");

        if ($query && $kurangi_saldo){
            $respon = array(
                'success' => true,
                'pesan' => "Berhasil Mencatat Pengeluaran Kas"
            );
            echo json_encode($respon);
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Gagal Mencatat Pengeluaran Kas"
            );
            echo json_encode($respon);
        }
    }elseif ($aksi == 'buat_laporan'){
        $saldo = $conn->query("SELECT * FROM saldo_kas WHERE id=1")->fetch_assoc()['saldo'];
        $semua_pengeluaran = $conn->query("SELECT * FROM pengeluaran_kas");
        $semua_pemasukan = $conn->query("SELECT * FROM pemasukan_kas");
        $semua_iuran_sampah = $conn->query("SELECT * FROM iuran_sampah");
        $semua_iuran_kematian = $conn->query("SELECT * FROM iuran_kematian");
        $data_iuran_sampah = array();
        $data_iuran_kematian = array();
        $data_pengeluaran = array();
        $data_pemasukan = array();
        $total_pengeluaran = 0;
        $total_pemasukan_kas = 0;
        $total_pemasukan_iuran_sampah = 0;
        $total_pemasukan_iuran_kematian = 0;
        $i_sampah = 0;
        $i_kematian = 0;
        $i_pengeluaran = 0;
        $i_pemasukan = 0;
        while($row = $semua_pengeluaran->fetch_assoc()){
            $bulan_apa = date('F' , strtotime($row['tanggal']));
            if($bulan_apa == date('F')){
                $total_pengeluaran = $total_pengeluaran + $row['jumlah_pengeluaran'];
                $nama_petugas = @$conn->query("SELECT * FROM warga WHERE id='" . $row['id_petugas'] . "'")->fetch_assoc()['nama_warga'];
                array_push($data_pengeluaran , $row);
                $data_pengeluaran[$i_pengeluaran]['nama_petugas'] = $nama_petugas;
                $i_pengeluaran = $i_pengeluaran+1;
            }
        }
        while($row = $semua_pemasukan->fetch_assoc()){
            $bulan_apa = date('F' , strtotime($row['tanggal']));
            if($bulan_apa == date('F')){
                $total_pemasukan_kas = $total_pemasukan_kas + $row['jumlah_pemasukan'];
                $nama_petugas = @$conn->query("SELECT * FROM warga WHERE id='" . $row['id_petugas'] . "'")->fetch_assoc()['nama_warga'];
                array_push($data_pemasukan , $row);
                $data_pemasukan[$i_pemasukan]['nama_petugas'] = $nama_petugas;
                $i_pemasukan = $i_pemasukan+1;
            }
        }
        while($row = $semua_iuran_sampah->fetch_assoc()){
            $bulan_apa = date('F' , strtotime($row['tanggal']));
            if($bulan_apa == date('F')){
                $total_pemasukan_iuran_sampah = $total_pemasukan_iuran_sampah + $row['jumlah_iuran'];
                $data_warga = $conn->query("SELECT * FROM warga WHERE id='" . $row['id_warga'] . "'")->fetch_assoc();
                $data_petugas = $conn->query("SELECT * FROM warga WHERE id='" . $row['id_petugas'] . "'")->fetch_assoc();
                array_push($data_iuran_sampah , $row);
                $data_iuran_sampah[$i_sampah]['nama_warga'] = $data_warga['nama_warga'];
                $data_iuran_sampah[$i_sampah]['nama_petugas'] = $data_petugas['nama_warga'];
                $i_sampah = $i_sampah+1;
            }
        }
        while($row = $semua_iuran_kematian->fetch_assoc()){
            $bulan_apa = date('F' , strtotime($row['tanggal']));
            if($bulan_apa == date('F')){
                $total_pemasukan_iuran_kematian = $total_pemasukan_iuran_kematian + $row['jumlah_iuran'];
                $data_warga = $conn->query("SELECT * FROM warga WHERE id='" . $row['id_warga'] . "'")->fetch_assoc();
                $data_petugas = $conn->query("SELECT * FROM warga WHERE id='" . $row['id_petugas'] . "'")->fetch_assoc();
                array_push($data_iuran_kematian , $row);
                $data_iuran_kematian[$i_kematian]['nama_warga'] = $data_warga['nama_warga'];
                $data_iuran_kematian[$i_kematian]['nama_petugas'] = $data_petugas['nama_warga'];
                $i_kematian = $i_kematian+1;
            }
        }
        $total_pemasukan = $total_pemasukan_iuran_sampah + $total_pemasukan_iuran_kematian + $total_pemasukan_kas;

        $respon = array(
            'success' => true,
            'jumlah_saldo' => $saldo,
            'bulan_ini' => array(
                'jumlah_total_pemasukan' => $total_pemasukan,
                'jumlah_total_pengeluaran' => $total_pengeluaran,
                'total_pemasukan_iuran_sampah' => $total_pemasukan_iuran_sampah,
                'total_pemasukan_iuran_kematian' => $total_pemasukan_iuran_kematian,
                'data_pemasukan_iuran_sampah' => $data_iuran_sampah,
                'data_pemasukan_iuran_kematian' => $data_iuran_kematian,
                'data_pengeluaran' => $data_pengeluaran,
                'data_pemasukan' => $data_pemasukan
            ),
        );
        echo json_encode($respon);
    }elseif ($aksi == 'buat_pengumuman'){
        $support_ext = ['jpg','png','jpeg'];
        $judul = @mysqli_real_escape_string($conn , $request['judul']);
        $isi = @mysqli_real_escape_string($conn , $request['isi']);
        $id_kategori = @mysqli_real_escape_string($conn , $request['id_kategori']);
        $tanggal = date('d F Y');
        $pembuat = @mysqli_real_escape_string($conn , $request['pembuat']);
        $files = $_FILES;
        $foto = '';
        $upload_status = true;
        $upload_error = false;
        if (!empty($judul) && !empty($isi) && !empty($id_kategori) && !empty($pembuat)){
            if (!empty($files['file']['name'])){
                if ($files['file']['error'] != 0){
                    $upload_error = true;
                    if ($files['file']['error'] == 1){
                        $respon = array(
                            'success' => false,
                            'pesan' => "File Terlalu Besar, Max 2MB",
                        );
                        echo json_encode($respon);
                    }else{
                        $respon = array(
                            'success' => false,
                            'pesan' => $files['file']['error'],
                        );
                        echo json_encode($respon);
                    }
                }else{
                    $ext = pathinfo($files['file']['name'], PATHINFO_EXTENSION);
                    if (in_array($ext ,$support_ext)){
                        $upload_status = move_uploaded_file($files['file']['tmp_name'] , './images/pengumuman/' . $files['file']['name']);
                        $foto = './images/pengumuman/' . $files['file']['name'];
                    }else{
                        $upload_error = true;
                        $respon = array(
                            'success' => false,
                            'pesan' => "Ekstensi File bukan gambar",
                        );
                        echo json_encode($respon);
                    }
                }
            }
            $query = $conn->query("INSERT INTO pengumuman (judul,foto,isi,id_kategori,tanggal,pembuat) VALUES('$judul','$foto','$isi','$id_kategori','$tanggal','$pembuat')");
            if($upload_error != true){
                if ($upload_status && $query){
                    $respon = array(
                        'success' => true,
                        'pesan' => 'Berhasil Menambah Pengumuman',
                    );
                    echo json_encode($respon);
                }else{
                    $respon = array(
                        'success' => false,
                        'pesan' => "Gagal Menambah Pengumuman",
                    );
                    echo json_encode($respon);
                }
            }
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Tolong isikan bagian yang kosong",
            );
            echo json_encode($respon);
        }
    }elseif ($aksi == 'buat_pengaduan'){
        $support_ext = ['jpg','png','jpeg'];
        $judul = @mysqli_real_escape_string($conn , $request['judul']);
        $isi = @mysqli_real_escape_string($conn , $request['isi']);
        $id_kategori = @mysqli_real_escape_string($conn , $request['id_kategori']);
        $tanggal = date('d F Y');
        $pembuat = @mysqli_real_escape_string($conn , $request['pembuat']);
        $files = $_FILES;
        $foto = '';
        $upload_status = true;
        $upload_error = false;
        if (!empty($judul) && !empty($isi) && !empty($id_kategori) && !empty($pembuat)){
            if(!empty($files['file']['name'])){
                if ($files['file']['error'] != 0){
                    $upload_error = true;
                    if ($files['file']['error'] == 1){
                        $respon = array(
                            'success' => false,
                            'pesan' => "File Terlalu Besar, Max 2MB",
                        );
                        echo json_encode($respon);
                    }else{
                        $respon = array(
                            'success' => false,
                            'pesan' => $files['file']['error'],
                        );
                        echo json_encode($respon);
                    }
                }else{
                    $ext = pathinfo($files['file']['name'], PATHINFO_EXTENSION);
                    if (in_array($ext ,$support_ext)){
                        $upload_status = move_uploaded_file($files['file']['tmp_name'] , './images/pengaduan/' . $files['file']['name']);
                        $foto = './images/pengumuman/' . $files['file']['name'];
                    }else{
                        $upload_error = true;
                        $respon = array(
                            'success' => false,
                            'pesan' => "Ekstensi File bukan gambar",
                        );
                        echo json_encode($respon);
                    }
                }
            }
            $query = $conn->query("INSERT INTO pengaduan (judul,foto,isi,id_kategori,tanggal,pembuat) VALUES('$judul','$foto','$isi','$id_kategori','$tanggal','$pembuat')");
            if($upload_error != true){
                if ($upload_status && $query){
                    $respon = array(
                        'success' => true,
                        'pesan' => 'Berhasil Menambah Pengaduan',
                    );
                    echo json_encode($respon);
                }else{
                    $respon = array(
                        'success' => false,
                        'pesan' => "Gagal Menambah Pengaduan",
                    );
                    echo json_encode($respon);
                }
            }
        }else{
            $respon = array(
                'success' => false,
                'pesan' => "Tolong isikan bagian yang kosong",
            );
            echo json_encode($respon);
        }
    }
}else{
    $respon = array(
        'success' => false,
        'pesan' => "Tidak ada Aksi"
    );
    echo json_encode($respon);
}

?>