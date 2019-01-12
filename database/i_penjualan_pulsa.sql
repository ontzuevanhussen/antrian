-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2019 at 07:40 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `i_penjualan_pulsa`
--

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `no_hp` varchar(13) NOT NULL,
  `created_user` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_user` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `no_hp`, `created_user`, `created_date`, `updated_user`, `updated_date`) VALUES
(1, 'Danang', '081377783334', 2, '2019-01-01 06:27:37', NULL, NULL),
(2, 'Indra', '082186869898', 2, '2019-01-01 06:27:48', NULL, NULL),
(3, 'Dina', '085780892906', 2, '2019-01-01 06:27:59', NULL, NULL),
(4, 'Hana', '085669919769', 2, '2019-01-01 06:28:12', NULL, NULL),
(5, 'Teguh', '081365657894', 2, '2019-01-01 06:28:26', NULL, NULL),
(6, 'Darma', '082385864123', 2, '2019-01-01 06:28:38', NULL, NULL),
(7, 'Agung', '081299784522', 2, '2019-01-01 06:28:53', NULL, NULL),
(8, 'Feri', '083165894532', 2, '2019-01-01 06:29:13', NULL, NULL),
(9, 'Fajar', '089599751234', 2, '2019-01-01 06:29:25', NULL, NULL),
(10, 'Dedi', '085786875533', 2, '2019-01-01 06:29:37', NULL, NULL),
(11, 'Wuri', '088896887712', 2, '2019-01-01 06:29:48', NULL, NULL);

--
-- Triggers `pelanggan`
--
DELIMITER $$
CREATE TRIGGER `pelanggan_insert` AFTER INSERT ON `pelanggan` FOR EACH ROW BEGIN
INSERT INTO sys_audit_trail (username,aksi,keterangan) VALUES (NEW.created_user,'Insert',CONCAT('<b>Insert</b> data pelanggan pada tabel <b>pelanggan</b>.<br><b>[ID Pelanggan : </b>',NEW.id_pelanggan,'<b>][Nama Pelanggan : </b>',NEW.nama,'<b>][No. HP : </b>',NEW.no_hp,'<b>]</b>' ));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `pelanggan_update` AFTER UPDATE ON `pelanggan` FOR EACH ROW BEGIN
INSERT INTO sys_audit_trail (username,aksi,keterangan) VALUES (NEW.updated_user,'Update',CONCAT('<b>Update</b> data pelanggan pada tabel <b>pelanggan</b>.<br><b>Data Lama = [ID Pelanggan : </b>',OLD.id_pelanggan,'<b>][Nama Pelanggan : </b>',OLD.nama,'<b>][No. HP : </b>',OLD.no_hp,'<b>],<br> Data Baru = [ID Pelanggan : </b>',NEW.id_pelanggan,'<b>][Nama Pelanggan : </b>',NEW.nama,'<b>][No. HP : </b>',NEW.no_hp,'<b>]</b>' ));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `pelanggan` int(11) NOT NULL,
  `pulsa` int(11) NOT NULL,
  `jumlah_bayar` int(11) NOT NULL,
  `created_user` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_user` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`id_penjualan`, `tanggal`, `pelanggan`, `pulsa`, `jumlah_bayar`, `created_user`, `created_date`, `updated_user`, `updated_date`) VALUES
(1, '2019-01-01', 1, 1, 27000, 2, '2019-01-01 06:30:13', NULL, NULL),
(2, '2019-01-01', 3, 6, 12000, 2, '2019-01-01 06:30:31', NULL, NULL),
(3, '2019-01-01', 2, 3, 52000, 2, '2019-01-01 06:30:50', NULL, NULL),
(4, '2019-01-01', 9, 13, 12000, 2, '2019-01-01 06:32:37', NULL, NULL),
(5, '2019-01-01', 7, 1, 27000, 2, '2019-01-01 06:33:04', NULL, NULL),
(6, '2019-01-01', 11, 40, 102000, 2, '2019-01-01 06:36:26', NULL, NULL),
(7, '2019-01-01', 10, 7, 22000, 2, '2019-01-01 06:37:14', NULL, NULL);

--
-- Triggers `penjualan`
--
DELIMITER $$
CREATE TRIGGER `penjualan_insert` AFTER INSERT ON `penjualan` FOR EACH ROW BEGIN
INSERT INTO sys_audit_trail (username,aksi,keterangan) VALUES (NEW.created_user,'Insert',CONCAT('<b>Insert</b> data penjualan pada tabel <b>penjualan</b>.<br><b>[ID Penjualan : </b>',NEW.id_penjualan,'<b>][Tanggal : </b>',NEW.tanggal,'<b>][ID Pelanggan : </b>',NEW.pelanggan,'<b>][ID Pulsa : </b>',NEW.pulsa,'<b>][Jumlah Bayar : </b>',NEW.jumlah_bayar,'<b>]</b>' ));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `penjualan_update` AFTER UPDATE ON `penjualan` FOR EACH ROW BEGIN
INSERT INTO sys_audit_trail (username,aksi,keterangan) VALUES (NEW.updated_user,'Update',CONCAT('<b>Update</b> data penjualan pada tabel <b>penjualan</b>.<br><b>Data Lama = [ID Penjualan : </b>',OLD.id_penjualan,'<b>][Tanggal : </b>',OLD.tanggal,'<b>][ID Pelanggan : </b>',OLD.pelanggan,'<b>][ID Pulsa : </b>',OLD.pulsa,'<b>][Jumlah Bayar : </b>',OLD.jumlah_bayar,'<b>],<br> Data Baru = [ID Penjualan : </b>',NEW.id_penjualan,'<b>][Tanggal : </b>',NEW.tanggal,'<b>][ID Pelanggan : </b>',NEW.pelanggan,'<b>][ID Pulsa : </b>',NEW.pulsa,'<b>][Jumlah Bayar : </b>',NEW.jumlah_bayar,'<b>]</b>' ));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pulsa`
--

CREATE TABLE `pulsa` (
  `id_pulsa` int(11) NOT NULL,
  `provider` varchar(15) NOT NULL,
  `nominal` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `created_user` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_user` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `pulsa`
--

INSERT INTO `pulsa` (`id_pulsa`, `provider`, `nominal`, `harga`, `created_user`, `created_date`, `updated_user`, `updated_date`) VALUES
(1, 'Telkomsel', 25000, 27000, 1, '2018-12-31 17:00:00', NULL, NULL),
(2, 'Telkomsel', 40000, 42000, 1, '2018-12-31 17:00:00', NULL, NULL),
(3, 'Telkomsel', 50000, 52000, 1, '2018-12-31 17:00:00', NULL, NULL),
(4, 'Telkomsel', 100000, 102000, 1, '2018-12-31 17:00:00', NULL, NULL),
(5, 'Indosat Ooredoo', 5000, 7000, 1, '2018-12-31 17:00:00', NULL, NULL),
(6, 'Indosat Ooredoo', 10000, 12000, 1, '2018-12-31 17:00:00', NULL, NULL),
(7, 'Indosat Ooredoo', 20000, 22000, 1, '2018-12-31 17:00:00', NULL, NULL),
(8, 'Indosat Ooredoo', 25000, 27000, 1, '2018-12-31 17:00:00', NULL, NULL),
(9, 'Indosat Ooredoo', 30000, 32000, 1, '2018-12-31 17:00:00', NULL, NULL),
(10, 'Indosat Ooredoo', 50000, 52000, 1, '2018-12-31 17:00:00', NULL, NULL),
(11, 'Indosat Ooredoo', 100000, 102000, 1, '2018-12-31 17:00:00', NULL, NULL),
(12, 'Tri Indonesia', 5000, 7000, 1, '2018-12-31 17:00:00', NULL, NULL),
(13, 'Tri Indonesia', 10000, 12000, 1, '2018-12-31 17:00:00', NULL, NULL),
(14, 'Tri Indonesia', 15000, 17000, 1, '2018-12-31 17:00:00', NULL, NULL),
(15, 'Tri Indonesia', 20000, 22000, 1, '2018-12-31 17:00:00', NULL, NULL),
(16, 'Tri Indonesia', 25000, 27000, 1, '2018-12-31 17:00:00', NULL, NULL),
(17, 'Tri Indonesia', 30000, 32000, 1, '2018-12-31 17:00:00', NULL, NULL),
(18, 'Tri Indonesia', 50000, 52000, 1, '2018-12-31 17:00:00', NULL, NULL),
(19, 'Tri Indonesia', 100000, 102000, 1, '2018-12-31 17:00:00', NULL, NULL),
(20, 'AXIS', 5000, 7000, 1, '2018-12-31 17:00:00', NULL, NULL),
(21, 'AXIS', 10000, 12000, 1, '2018-12-31 17:00:00', NULL, NULL),
(22, 'AXIS', 15000, 17000, 1, '2018-12-31 17:00:00', NULL, NULL),
(23, 'AXIS', 25000, 27000, 1, '2018-12-31 17:00:00', NULL, NULL),
(24, 'AXIS', 30000, 32000, 1, '2018-12-31 17:00:00', NULL, NULL),
(25, 'AXIS', 50000, 52000, 1, '2018-12-31 17:00:00', NULL, NULL),
(26, 'AXIS', 100000, 102000, 1, '2018-12-31 17:00:00', NULL, NULL),
(27, 'XL Axiata', 5000, 7000, 1, '2018-12-31 17:00:00', NULL, NULL),
(28, 'XL Axiata', 10000, 12000, 1, '2018-12-31 17:00:00', NULL, NULL),
(29, 'XL Axiata', 15000, 17000, 1, '2018-12-31 17:00:00', NULL, NULL),
(30, 'XL Axiata', 25000, 27000, 1, '2018-12-31 17:00:00', NULL, NULL),
(31, 'XL Axiata', 30000, 32000, 1, '2018-12-31 17:00:00', NULL, NULL),
(32, 'XL Axiata', 50000, 52000, 1, '2018-12-31 17:00:00', NULL, NULL),
(33, 'XL Axiata', 100000, 102000, 1, '2018-12-31 17:00:00', NULL, NULL),
(34, 'Smartfren', 5000, 7000, 1, '2018-12-31 17:00:00', NULL, NULL),
(35, 'Smartfren', 10000, 12000, 1, '2018-12-31 17:00:00', NULL, NULL),
(36, 'Smartfren', 20000, 22000, 1, '2018-12-31 17:00:00', NULL, NULL),
(37, 'Smartfren', 25000, 27000, 1, '2018-12-31 17:00:00', NULL, NULL),
(38, 'Smartfren', 50000, 52000, 1, '2018-12-31 17:00:00', NULL, NULL),
(39, 'Smartfren', 60000, 62000, 1, '2018-12-31 17:00:00', NULL, NULL),
(40, 'Smartfren', 100000, 102000, 1, '2018-12-31 17:00:00', NULL, NULL);

--
-- Triggers `pulsa`
--
DELIMITER $$
CREATE TRIGGER `pulsa_insert` AFTER INSERT ON `pulsa` FOR EACH ROW BEGIN
INSERT INTO sys_audit_trail (username,aksi,keterangan) VALUES (NEW.created_user,'Insert',CONCAT('<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>',NEW.id_pulsa,'<b>][Provider : </b>',NEW.provider,'<b>][Nominal : </b>',NEW.nominal,'<b>][Harga : </b>',NEW.harga,'<b>]</b>' ));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `pulsa_update` AFTER UPDATE ON `pulsa` FOR EACH ROW BEGIN
INSERT INTO sys_audit_trail (username,aksi,keterangan) VALUES (NEW.updated_user,'Update',CONCAT('<b>Update</b> data pulsa pada tabel <b>pulsa</b>.<br><b>Data Lama = [ID Pulsa : </b>',OLD.id_pulsa,'<b>][Provider : </b>',OLD.provider,'<b>][Nominal : </b>',OLD.nominal,'<b>][Harga : </b>',OLD.harga,'<b>],<br> Data Baru = [ID Pulsa : </b>',NEW.id_pulsa,'<b>][Provider : </b>',NEW.provider,'<b>][Nominal : </b>',NEW.nominal,'<b>][Harga : </b>',NEW.harga,'<b>]</b>' ));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sys_audit_trail`
--

CREATE TABLE `sys_audit_trail` (
  `id` bigint(20) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `username` varchar(30) NOT NULL,
  `aksi` enum('Insert','Update','Delete') NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sys_audit_trail`
--

INSERT INTO `sys_audit_trail` (`id`, `tanggal`, `username`, `aksi`, `keterangan`) VALUES
(1, '2019-01-01 06:24:44', '1', 'Insert', '<b>Insert</b> data pengguna pada tabel <b>sys_users</b>.<br><b>[ID User : </b>1<b>][Nama User : </b>Indra Styawantoro<b>][Username : </b>indrasatya<b>][Password : </b>2437018d9a925c9fce796b99bfb9591728c5f208<b>][Hak Akses : </b>Administrator<b>][Blokir : </b>Tidak<b>]</b>'),
(2, '2019-01-01 06:24:59', '1', 'Insert', '<b>Insert</b> data pengguna pada tabel <b>sys_users</b>.<br><b>[ID User : </b>2<b>][Nama User : </b>Danang Kesuma<b>][Username : </b>danang<b>][Password : </b>f8966cc671220b4858818afca4a8c9eedbeb6a5d<b>][Hak Akses : </b>Operator<b>][Blokir : </b>Tidak<b>]</b>'),
(3, '2019-01-01 06:26:05', '1', 'Update', '<b>Update</b> data konfigurasi aplikasi pada tabel <b>sys_config</b>.<br><b>Data Lama = [Nama Konter : </b><b>][Alamat : </b><b>][Telepon : </b><b>][Email : </b><b>][Website : </b><b>][Logo : </b><b>],<br> Data Baru = [Nama Konter : </b>NUSANTARA CELL<b>][Alamat : </b>Rajabasa, Bandar Lampung, Lampung<b>][Telepon : </b>081377783334<b>][Email : </b>nusantaracell@gmail.com<b>][Website : </b>www.nusantaracell.com<b>][Logo : </b>logo.png<b>]</b>'),
(4, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>1<b>][Provider : </b>Telkomsel<b>][Nominal : </b>25000<b>][Harga : </b>27000<b>]</b>'),
(5, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>2<b>][Provider : </b>Telkomsel<b>][Nominal : </b>40000<b>][Harga : </b>42000<b>]</b>'),
(6, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>3<b>][Provider : </b>Telkomsel<b>][Nominal : </b>50000<b>][Harga : </b>52000<b>]</b>'),
(7, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>4<b>][Provider : </b>Telkomsel<b>][Nominal : </b>100000<b>][Harga : </b>102000<b>]</b>'),
(8, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>5<b>][Provider : </b>Indosat Ooredoo<b>][Nominal : </b>5000<b>][Harga : </b>7000<b>]</b>'),
(9, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>6<b>][Provider : </b>Indosat Ooredoo<b>][Nominal : </b>10000<b>][Harga : </b>12000<b>]</b>'),
(10, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>7<b>][Provider : </b>Indosat Ooredoo<b>][Nominal : </b>20000<b>][Harga : </b>22000<b>]</b>'),
(11, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>8<b>][Provider : </b>Indosat Ooredoo<b>][Nominal : </b>25000<b>][Harga : </b>27000<b>]</b>'),
(12, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>9<b>][Provider : </b>Indosat Ooredoo<b>][Nominal : </b>30000<b>][Harga : </b>32000<b>]</b>'),
(13, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>10<b>][Provider : </b>Indosat Ooredoo<b>][Nominal : </b>50000<b>][Harga : </b>52000<b>]</b>'),
(14, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>11<b>][Provider : </b>Indosat Ooredoo<b>][Nominal : </b>100000<b>][Harga : </b>102000<b>]</b>'),
(15, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>12<b>][Provider : </b>Tri Indonesia<b>][Nominal : </b>5000<b>][Harga : </b>7000<b>]</b>'),
(16, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>13<b>][Provider : </b>Tri Indonesia<b>][Nominal : </b>10000<b>][Harga : </b>12000<b>]</b>'),
(17, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>14<b>][Provider : </b>Tri Indonesia<b>][Nominal : </b>15000<b>][Harga : </b>17000<b>]</b>'),
(18, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>15<b>][Provider : </b>Tri Indonesia<b>][Nominal : </b>20000<b>][Harga : </b>22000<b>]</b>'),
(19, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>16<b>][Provider : </b>Tri Indonesia<b>][Nominal : </b>25000<b>][Harga : </b>27000<b>]</b>'),
(20, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>17<b>][Provider : </b>Tri Indonesia<b>][Nominal : </b>30000<b>][Harga : </b>32000<b>]</b>'),
(21, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>18<b>][Provider : </b>Tri Indonesia<b>][Nominal : </b>50000<b>][Harga : </b>52000<b>]</b>'),
(22, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>19<b>][Provider : </b>Tri Indonesia<b>][Nominal : </b>100000<b>][Harga : </b>102000<b>]</b>'),
(23, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>20<b>][Provider : </b>AXIS<b>][Nominal : </b>5000<b>][Harga : </b>7000<b>]</b>'),
(24, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>21<b>][Provider : </b>AXIS<b>][Nominal : </b>10000<b>][Harga : </b>12000<b>]</b>'),
(25, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>22<b>][Provider : </b>AXIS<b>][Nominal : </b>15000<b>][Harga : </b>17000<b>]</b>'),
(26, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>23<b>][Provider : </b>AXIS<b>][Nominal : </b>25000<b>][Harga : </b>27000<b>]</b>'),
(27, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>24<b>][Provider : </b>AXIS<b>][Nominal : </b>30000<b>][Harga : </b>32000<b>]</b>'),
(28, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>25<b>][Provider : </b>AXIS<b>][Nominal : </b>50000<b>][Harga : </b>52000<b>]</b>'),
(29, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>26<b>][Provider : </b>AXIS<b>][Nominal : </b>100000<b>][Harga : </b>102000<b>]</b>'),
(30, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>27<b>][Provider : </b>XL Axiata<b>][Nominal : </b>5000<b>][Harga : </b>7000<b>]</b>'),
(31, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>28<b>][Provider : </b>XL Axiata<b>][Nominal : </b>10000<b>][Harga : </b>12000<b>]</b>'),
(32, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>29<b>][Provider : </b>XL Axiata<b>][Nominal : </b>15000<b>][Harga : </b>17000<b>]</b>'),
(33, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>30<b>][Provider : </b>XL Axiata<b>][Nominal : </b>25000<b>][Harga : </b>27000<b>]</b>'),
(34, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>31<b>][Provider : </b>XL Axiata<b>][Nominal : </b>30000<b>][Harga : </b>32000<b>]</b>'),
(35, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>32<b>][Provider : </b>XL Axiata<b>][Nominal : </b>50000<b>][Harga : </b>52000<b>]</b>'),
(36, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>33<b>][Provider : </b>XL Axiata<b>][Nominal : </b>100000<b>][Harga : </b>102000<b>]</b>'),
(37, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>34<b>][Provider : </b>Smartfren<b>][Nominal : </b>5000<b>][Harga : </b>7000<b>]</b>'),
(38, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>35<b>][Provider : </b>Smartfren<b>][Nominal : </b>10000<b>][Harga : </b>12000<b>]</b>'),
(39, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>36<b>][Provider : </b>Smartfren<b>][Nominal : </b>20000<b>][Harga : </b>22000<b>]</b>'),
(40, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>37<b>][Provider : </b>Smartfren<b>][Nominal : </b>25000<b>][Harga : </b>27000<b>]</b>'),
(41, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>38<b>][Provider : </b>Smartfren<b>][Nominal : </b>50000<b>][Harga : </b>52000<b>]</b>'),
(42, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>39<b>][Provider : </b>Smartfren<b>][Nominal : </b>60000<b>][Harga : </b>62000<b>]</b>'),
(43, '2019-01-01 06:26:27', '1', 'Insert', '<b>Insert</b> data pulsa pada tabel <b>pulsa</b>.<br><b>[ID Pulsa : </b>40<b>][Provider : </b>Smartfren<b>][Nominal : </b>100000<b>][Harga : </b>102000<b>]</b>'),
(44, '2019-01-01 06:27:37', '2', 'Insert', '<b>Insert</b> data pelanggan pada tabel <b>pelanggan</b>.<br><b>[ID Pelanggan : </b>1<b>][Nama Pelanggan : </b>Danang<b>][No. HP : </b>081377783334<b>]</b>'),
(45, '2019-01-01 06:27:48', '2', 'Insert', '<b>Insert</b> data pelanggan pada tabel <b>pelanggan</b>.<br><b>[ID Pelanggan : </b>2<b>][Nama Pelanggan : </b>Indra<b>][No. HP : </b>082186869898<b>]</b>'),
(46, '2019-01-01 06:27:59', '2', 'Insert', '<b>Insert</b> data pelanggan pada tabel <b>pelanggan</b>.<br><b>[ID Pelanggan : </b>3<b>][Nama Pelanggan : </b>Dina<b>][No. HP : </b>085780892906<b>]</b>'),
(47, '2019-01-01 06:28:12', '2', 'Insert', '<b>Insert</b> data pelanggan pada tabel <b>pelanggan</b>.<br><b>[ID Pelanggan : </b>4<b>][Nama Pelanggan : </b>Hana<b>][No. HP : </b>085669919769<b>]</b>'),
(48, '2019-01-01 06:28:26', '2', 'Insert', '<b>Insert</b> data pelanggan pada tabel <b>pelanggan</b>.<br><b>[ID Pelanggan : </b>5<b>][Nama Pelanggan : </b>Teguh<b>][No. HP : </b>081365657894<b>]</b>'),
(49, '2019-01-01 06:28:38', '2', 'Insert', '<b>Insert</b> data pelanggan pada tabel <b>pelanggan</b>.<br><b>[ID Pelanggan : </b>6<b>][Nama Pelanggan : </b>Darma<b>][No. HP : </b>082385864123<b>]</b>'),
(50, '2019-01-01 06:28:53', '2', 'Insert', '<b>Insert</b> data pelanggan pada tabel <b>pelanggan</b>.<br><b>[ID Pelanggan : </b>7<b>][Nama Pelanggan : </b>Agung<b>][No. HP : </b>081299784522<b>]</b>'),
(51, '2019-01-01 06:29:13', '2', 'Insert', '<b>Insert</b> data pelanggan pada tabel <b>pelanggan</b>.<br><b>[ID Pelanggan : </b>8<b>][Nama Pelanggan : </b>Feri<b>][No. HP : </b>083165894532<b>]</b>'),
(52, '2019-01-01 06:29:25', '2', 'Insert', '<b>Insert</b> data pelanggan pada tabel <b>pelanggan</b>.<br><b>[ID Pelanggan : </b>9<b>][Nama Pelanggan : </b>Fajar<b>][No. HP : </b>089599751234<b>]</b>'),
(53, '2019-01-01 06:29:37', '2', 'Insert', '<b>Insert</b> data pelanggan pada tabel <b>pelanggan</b>.<br><b>[ID Pelanggan : </b>10<b>][Nama Pelanggan : </b>Dedi<b>][No. HP : </b>085786875533<b>]</b>'),
(54, '2019-01-01 06:29:48', '2', 'Insert', '<b>Insert</b> data pelanggan pada tabel <b>pelanggan</b>.<br><b>[ID Pelanggan : </b>11<b>][Nama Pelanggan : </b>Wuri<b>][No. HP : </b>088896887712<b>]</b>'),
(55, '2019-01-01 06:30:13', '2', 'Insert', '<b>Insert</b> data penjualan pada tabel <b>penjualan</b>.<br><b>[ID Penjualan : </b>1<b>][Tanggal : </b>2019-01-01<b>][ID Pelanggan : </b>1<b>][ID Pulsa : </b>1<b>][Jumlah Bayar : </b>27000<b>]</b>'),
(56, '2019-01-01 06:30:31', '2', 'Insert', '<b>Insert</b> data penjualan pada tabel <b>penjualan</b>.<br><b>[ID Penjualan : </b>2<b>][Tanggal : </b>2019-01-01<b>][ID Pelanggan : </b>3<b>][ID Pulsa : </b>6<b>][Jumlah Bayar : </b>12000<b>]</b>'),
(57, '2019-01-01 06:30:50', '2', 'Insert', '<b>Insert</b> data penjualan pada tabel <b>penjualan</b>.<br><b>[ID Penjualan : </b>3<b>][Tanggal : </b>2019-01-01<b>][ID Pelanggan : </b>2<b>][ID Pulsa : </b>3<b>][Jumlah Bayar : </b>52000<b>]</b>'),
(58, '2019-01-01 06:32:37', '2', 'Insert', '<b>Insert</b> data penjualan pada tabel <b>penjualan</b>.<br><b>[ID Penjualan : </b>4<b>][Tanggal : </b>2019-01-01<b>][ID Pelanggan : </b>9<b>][ID Pulsa : </b>13<b>][Jumlah Bayar : </b>12000<b>]</b>'),
(59, '2019-01-01 06:33:04', '2', 'Insert', '<b>Insert</b> data penjualan pada tabel <b>penjualan</b>.<br><b>[ID Penjualan : </b>5<b>][Tanggal : </b>2019-01-01<b>][ID Pelanggan : </b>7<b>][ID Pulsa : </b>1<b>][Jumlah Bayar : </b>27000<b>]</b>'),
(60, '2019-01-01 06:36:26', '2', 'Insert', '<b>Insert</b> data penjualan pada tabel <b>penjualan</b>.<br><b>[ID Penjualan : </b>6<b>][Tanggal : </b>2019-01-01<b>][ID Pelanggan : </b>11<b>][ID Pulsa : </b>40<b>][Jumlah Bayar : </b>102000<b>]</b>'),
(61, '2019-01-01 06:37:14', '2', 'Insert', '<b>Insert</b> data penjualan pada tabel <b>penjualan</b>.<br><b>[ID Penjualan : </b>7<b>][Tanggal : </b>2019-01-01<b>][ID Pelanggan : </b>10<b>][ID Pulsa : </b>7<b>][Jumlah Bayar : </b>22000<b>]</b>'),
(62, '2019-01-01 06:39:49', '1', 'Insert', '<b>Insert</b> data backup database pada tabel <b>sys_database</b>.<br><b>[ID : </b>1<b>][Nama File : </b>20190101_133949_database.sql.gz<b>][Ukuran File : </b>3 KB<b>]</b>');

-- --------------------------------------------------------

--
-- Table structure for table `sys_config`
--

CREATE TABLE `sys_config` (
  `id` tinyint(1) NOT NULL,
  `nama_konter` varchar(30) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `telepon` varchar(13) NOT NULL,
  `email` varchar(30) NOT NULL,
  `website` varchar(30) NOT NULL,
  `logo` varchar(30) NOT NULL,
  `updated_user` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sys_config`
--

INSERT INTO `sys_config` (`id`, `nama_konter`, `alamat`, `telepon`, `email`, `website`, `logo`, `updated_user`, `updated_date`) VALUES
(1, 'NUSANTARA CELL', 'Rajabasa, Bandar Lampung, Lampung', '081377783334', 'nusantaracell@gmail.com', 'www.nusantaracell.com', 'logo.png', 1, '2019-01-01 13:26:05');

--
-- Triggers `sys_config`
--
DELIMITER $$
CREATE TRIGGER `config_update` AFTER UPDATE ON `sys_config` FOR EACH ROW BEGIN
INSERT INTO sys_audit_trail (username,aksi,keterangan) VALUES (NEW.updated_user,'Update',CONCAT('<b>Update</b> data konfigurasi aplikasi pada tabel <b>sys_config</b>.<br><b>Data Lama = [Nama Konter : </b>',OLD.nama_konter,'<b>][Alamat : </b>',OLD.alamat,'<b>][Telepon : </b>',OLD.telepon,'<b>][Email : </b>',OLD.email,'<b>][Website : </b>',OLD.website,'<b>][Logo : </b>',OLD.logo,'<b>],<br> Data Baru = [Nama Konter : </b>',NEW.nama_konter,'<b>][Alamat : </b>',NEW.alamat,'<b>][Telepon : </b>',NEW.telepon,'<b>][Email : </b>',NEW.email,'<b>][Website : </b>',NEW.website,'<b>][Logo : </b>',NEW.logo,'<b>]</b>' ));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sys_database`
--

CREATE TABLE `sys_database` (
  `id` int(11) NOT NULL,
  `nama_file` varchar(50) NOT NULL,
  `ukuran_file` varchar(10) NOT NULL,
  `created_user` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sys_database`
--

INSERT INTO `sys_database` (`id`, `nama_file`, `ukuran_file`, `created_user`, `created_date`) VALUES
(1, '20190101_133949_database.sql.gz', '3 KB', 1, '2019-01-01 06:39:49');

--
-- Triggers `sys_database`
--
DELIMITER $$
CREATE TRIGGER `database_insert` AFTER INSERT ON `sys_database` FOR EACH ROW BEGIN
INSERT INTO sys_audit_trail (username,aksi,keterangan) VALUES (NEW.created_user,'Insert',CONCAT('<b>Insert</b> data backup database pada tabel <b>sys_database</b>.<br><b>[ID : </b>',NEW.id,'<b>][Nama File : </b>',NEW.nama_file,'<b>][Ukuran File : </b>',NEW.ukuran_file,'<b>]</b>' ));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sys_users`
--

CREATE TABLE `sys_users` (
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(45) NOT NULL,
  `hak_akses` enum('Administrator','Operator') NOT NULL,
  `blokir` enum('Ya','Tidak') NOT NULL,
  `created_user` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_user` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sys_users`
--

INSERT INTO `sys_users` (`id_user`, `nama_user`, `username`, `password`, `hak_akses`, `blokir`, `created_user`, `created_date`, `updated_user`, `updated_date`) VALUES
(1, 'Indra Styawantoro', 'indrasatya', '2437018d9a925c9fce796b99bfb9591728c5f208', 'Administrator', 'Tidak', 1, '2019-01-01 06:24:44', NULL, NULL),
(2, 'Danang Kesuma', 'danang', 'f8966cc671220b4858818afca4a8c9eedbeb6a5d', 'Operator', 'Tidak', 1, '2019-01-01 06:24:59', NULL, NULL);

--
-- Triggers `sys_users`
--
DELIMITER $$
CREATE TRIGGER `users_insert` AFTER INSERT ON `sys_users` FOR EACH ROW BEGIN
INSERT INTO sys_audit_trail (username,aksi,keterangan) VALUES (NEW.created_user,'Insert',CONCAT('<b>Insert</b> data pengguna pada tabel <b>sys_users</b>.<br><b>[ID User : </b>',NEW.id_user,'<b>][Nama User : </b>',NEW.nama_user,'<b>][Username : </b>',NEW.username,'<b>][Password : </b>',NEW.password,'<b>][Hak Akses : </b>',NEW.hak_akses,'<b>][Blokir : </b>',NEW.blokir,'<b>]</b>' ));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `users_update` AFTER UPDATE ON `sys_users` FOR EACH ROW BEGIN
INSERT INTO sys_audit_trail (username,aksi,keterangan) VALUES (NEW.updated_user,'Update',CONCAT('<b>Update</b> data pengguna pada tabel <b>sys_users</b>.<br><b>Data Lama = [ID User : </b>',OLD.id_user,'<b>][Nama User : </b>',OLD.nama_user,'<b>][Username : </b>',OLD.username,'<b>][Password : </b>',OLD.password,'<b>][Hak Akses : </b>',OLD.hak_akses,'<b>][Blokir : </b>',OLD.blokir,'<b>],<br> Data Baru = [ID User : </b>',NEW.id_user,'<b>][Nama User : </b>',NEW.nama_user,'<b>][Username : </b>',NEW.username,'<b>][Password : </b>',NEW.password,'<b>][Hak Akses : </b>',NEW.hak_akses,'<b>][Blokir : </b>',NEW.blokir,'<b>]</b>' ));
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- Indexes for table `pulsa`
--
ALTER TABLE `pulsa`
  ADD PRIMARY KEY (`id_pulsa`);

--
-- Indexes for table `sys_audit_trail`
--
ALTER TABLE `sys_audit_trail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sys_config`
--
ALTER TABLE `sys_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sys_database`
--
ALTER TABLE `sys_database`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sys_users`
--
ALTER TABLE `sys_users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `pulsa`
--
ALTER TABLE `pulsa`
  MODIFY `id_pulsa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `sys_audit_trail`
--
ALTER TABLE `sys_audit_trail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT for table `sys_config`
--
ALTER TABLE `sys_config`
  MODIFY `id` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sys_database`
--
ALTER TABLE `sys_database`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sys_users`
--
ALTER TABLE `sys_users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
