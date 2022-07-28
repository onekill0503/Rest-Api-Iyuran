-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 23, 2019 at 02:44 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iyuran`
--

-- --------------------------------------------------------

--
-- Table structure for table `iuran_kematian`
--

CREATE TABLE `iuran_kematian` (
  `id_iuran` int(11) NOT NULL,
  `id_petugas` int(11) NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `periode` varchar(255) NOT NULL,
  `jumlah_iuran` int(11) NOT NULL,
  `id_warga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `iuran_sampah`
--

CREATE TABLE `iuran_sampah` (
  `id_iuran` int(11) NOT NULL,
  `id_petugas` int(11) NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `periode` varchar(255) NOT NULL,
  `jumlah_iuran` int(11) NOT NULL,
  `id_warga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'kriminal');

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `id_level` int(11) NOT NULL,
  `level` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`id_level`, `level`) VALUES
(1, 'warga'),
(2, 'petugas');

-- --------------------------------------------------------

--
-- Table structure for table `pemasukan_kas`
--

CREATE TABLE `pemasukan_kas` (
  `id` int(11) NOT NULL,
  `jumlah_pemasukan` int(11) NOT NULL,
  `alasan` varchar(255) NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `id_petugas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pengaduan`
--

CREATE TABLE `pengaduan` (
  `id_pengaduan` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `pembuat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran_kas`
--

CREATE TABLE `pengeluaran_kas` (
  `id` int(11) NOT NULL,
  `jumlah_pengeluaran` int(11) NOT NULL,
  `alasan` varchar(255) NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `id_petugas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id_pengumuman` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `pembuat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saldo_kas`
--

CREATE TABLE `saldo_kas` (
  `id` int(11) NOT NULL,
  `saldo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `saldo_kas`
--

INSERT INTO `saldo_kas` (`id`, `saldo`) VALUES
(1, 798000);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `id_warga` int(11) NOT NULL,
  `id_iuran_sampah` int(11) NOT NULL,
  `id_iuran_kematian` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `warga`
--

CREATE TABLE `warga` (
  `id` int(11) NOT NULL,
  `nama_warga` varchar(50) NOT NULL,
  `nik` varchar(100) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `no_telp` varchar(50) NOT NULL,
  `id_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `iuran_kematian`
--
ALTER TABLE `iuran_kematian`
  ADD PRIMARY KEY (`id_iuran`),
  ADD KEY `id_warga` (`id_warga`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indexes for table `iuran_sampah`
--
ALTER TABLE `iuran_sampah`
  ADD PRIMARY KEY (`id_iuran`),
  ADD KEY `id_warga` (`id_warga`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id_level`);

--
-- Indexes for table `pemasukan_kas`
--
ALTER TABLE `pemasukan_kas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indexes for table `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD PRIMARY KEY (`id_pengaduan`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `pembuat` (`pembuat`);

--
-- Indexes for table `pengeluaran_kas`
--
ALTER TABLE `pengeluaran_kas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id_pengumuman`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `pembuat` (`pembuat`);

--
-- Indexes for table `saldo_kas`
--
ALTER TABLE `saldo_kas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_iyuran_sampah` (`id_iuran_sampah`),
  ADD KEY `id_warga` (`id_warga`),
  ADD KEY `id_iyuran_kematian` (`id_iuran_kematian`),
  ADD KEY `id_warga_2` (`id_warga`);

--
-- Indexes for table `warga`
--
ALTER TABLE `warga`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_level` (`id_level`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `iuran_kematian`
--
ALTER TABLE `iuran_kematian`
  MODIFY `id_iuran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `iuran_sampah`
--
ALTER TABLE `iuran_sampah`
  MODIFY `id_iuran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `id_level` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pemasukan_kas`
--
ALTER TABLE `pemasukan_kas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pengaduan`
--
ALTER TABLE `pengaduan`
  MODIFY `id_pengaduan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengeluaran_kas`
--
ALTER TABLE `pengeluaran_kas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id_pengumuman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `saldo_kas`
--
ALTER TABLE `saldo_kas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `warga`
--
ALTER TABLE `warga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `iuran_kematian`
--
ALTER TABLE `iuran_kematian`
  ADD CONSTRAINT `iuran_kematian_ibfk_1` FOREIGN KEY (`id_warga`) REFERENCES `warga` (`id`),
  ADD CONSTRAINT `iuran_kematian_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `warga` (`id`);

--
-- Constraints for table `iuran_sampah`
--
ALTER TABLE `iuran_sampah`
  ADD CONSTRAINT `iuran_sampah_ibfk_1` FOREIGN KEY (`id_warga`) REFERENCES `warga` (`id`),
  ADD CONSTRAINT `iuran_sampah_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `warga` (`id`);

--
-- Constraints for table `pemasukan_kas`
--
ALTER TABLE `pemasukan_kas`
  ADD CONSTRAINT `pemasukan_kas_ibfk_1` FOREIGN KEY (`id_petugas`) REFERENCES `warga` (`id`);

--
-- Constraints for table `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD CONSTRAINT `pengaduan_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`),
  ADD CONSTRAINT `pengaduan_ibfk_2` FOREIGN KEY (`pembuat`) REFERENCES `warga` (`id`);

--
-- Constraints for table `pengeluaran_kas`
--
ALTER TABLE `pengeluaran_kas`
  ADD CONSTRAINT `pengeluaran_kas_ibfk_1` FOREIGN KEY (`id_petugas`) REFERENCES `warga` (`id`);

--
-- Constraints for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD CONSTRAINT `pengumuman_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`),
  ADD CONSTRAINT `pengumuman_ibfk_2` FOREIGN KEY (`pembuat`) REFERENCES `warga` (`id`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_iuran_sampah`) REFERENCES `iuran_sampah` (`id_iuran`),
  ADD CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`id_warga`) REFERENCES `warga` (`id`);

--
-- Constraints for table `warga`
--
ALTER TABLE `warga`
  ADD CONSTRAINT `warga_ibfk_1` FOREIGN KEY (`id_level`) REFERENCES `level` (`id_level`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
