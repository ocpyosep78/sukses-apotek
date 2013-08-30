-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2013 at 03:51 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `apotek_integrated`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE IF NOT EXISTS `akun` (
  `kode` varchar(5) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `kelompok` enum('CC','HPP','Kas/Bank','Kewajiban','Modal','Pendapatan Lain-lain','Pendapatan Penjualan','Prive') NOT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`kode`, `keterangan`, `kelompok`) VALUES
('1.1', '-', 'Pendapatan Penjualan');

-- --------------------------------------------------------

--
-- Table structure for table `apotek`
--

CREATE TABLE IF NOT EXISTS `apotek` (
  `sia` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `telp` varchar(20) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `logo_file_nama` varchar(255) NOT NULL,
  PRIMARY KEY (`sia`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `apotek`
--

INSERT INTO `apotek` (`sia`, `nama`, `alamat`, `telp`, `fax`, `email`, `logo_file_nama`) VALUES
('503/2254/DKS/2008', 'APOTEK SARI DEWI', 'Jl. Palagan Tentara Pelajar No.33', '(0274) 4463900', '', '', 'apotek.png');

-- --------------------------------------------------------

--
-- Table structure for table `arus_kas`
--

CREATE TABLE IF NOT EXISTS `arus_kas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_users` int(11) DEFAULT NULL,
  `waktu` datetime NOT NULL,
  `transaksi` int(11) NOT NULL,
  `masuk` double NOT NULL,
  `keluar` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_users` (`id_users`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `asuransi`
--

CREATE TABLE IF NOT EXISTS `asuransi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(30) NOT NULL,
  `diskon` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `asuransi`
--

INSERT INTO `asuransi` (`id`, `nama`, `diskon`) VALUES
(1, 'ASKES GOLD', 100),
(2, 'JAMKESMAS', 50),
(3, 'AXA PRUDENT', 50),
(4, 'PRU-LIFE', 50);

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE IF NOT EXISTS `bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `charge` double NOT NULL,
  `kode_akun` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_akun` (`kode_akun`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`id`, `nama`, `charge`, `kode_akun`) VALUES
(1, 'BCA', 5, '1.1'),
(2, 'BNI', 2.5, '1.1');

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE IF NOT EXISTS `barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barcode` varchar(30) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `id_pabrik` int(11) DEFAULT NULL,
  `rak` varchar(30) NOT NULL,
  `kekuatan` double NOT NULL,
  `id_golongan` int(11) DEFAULT NULL,
  `satuan_kekuatan` int(3) DEFAULT NULL,
  `id_sediaan` int(11) DEFAULT NULL,
  `adm_r` enum('Oral','Rektal','Infus','Topikal','Sublingual','Transdermal','Intrakutan','Subkutan','Intravena','Intramuskuler','Vagina','Injeksi','Intranasal','Intraokuler','Intraaurikuler','Intrapulmonal','Implantasi','Subkutan','Intralumbal','Intrarteri') NOT NULL,
  `generik` tinyint(1) NOT NULL,
  `indikasi` text NOT NULL,
  `dosis` text NOT NULL,
  `kandungan` text NOT NULL,
  `perhatian` text NOT NULL,
  `kontra_indikasi` text NOT NULL,
  `efek_samping` text NOT NULL,
  `formularium` enum('Ya','Tidak') NOT NULL,
  `perundangan` enum('Bebas','Bebas Terbatas','OWA','Keras','Psikotropika','Narkotika') NOT NULL,
  `aturan_pakai` text NOT NULL,
  `id_kelas_terapi` int(11) DEFAULT NULL,
  `fda_pregnancy` enum('A','B','C','D','X') DEFAULT NULL,
  `fda_lactacy` enum('A','B','C','D','X') DEFAULT NULL,
  `stok_minimal` double NOT NULL,
  `margin_non_resep` double NOT NULL,
  `margin_resep` double NOT NULL,
  `plus_ppn` double NOT NULL,
  `hna` double NOT NULL,
  `aktif` tinyint(1) NOT NULL,
  `image` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pabrik` (`id_pabrik`),
  KEY `satuan_kekuatan` (`satuan_kekuatan`),
  KEY `id_golongan` (`id_golongan`),
  KEY `id_sediaan` (`id_sediaan`),
  KEY `id_kelas_terapi` (`id_kelas_terapi`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8145 ;

-- --------------------------------------------------------

--
-- Table structure for table `config_autonumber`
--

CREATE TABLE IF NOT EXISTS `config_autonumber` (
  `pemesanan` varchar(10) NOT NULL,
  `penerimaan` varchar(10) NOT NULL,
  `penjualan` varchar(10) NOT NULL,
  `retur_penerimaan` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config_autonumber`
--

INSERT INTO `config_autonumber` (`pemesanan`, `penerimaan`, `penjualan`, `retur_penerimaan`) VALUES
('SP-', 'FP-', 'PJ-', 'RP-');

-- --------------------------------------------------------

--
-- Table structure for table `detail_bayar_penjualan`
--

CREATE TABLE IF NOT EXISTS `detail_bayar_penjualan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `waktu` datetime NOT NULL,
  `id_penjualan` int(11) NOT NULL,
  `bayar` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_penjualan` (`id_penjualan`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `detail_pemesanan`
--

CREATE TABLE IF NOT EXISTS `detail_pemesanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pemesanan` int(6) unsigned zerofill NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `jumlah` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pemesanan` (`id_pemesanan`),
  KEY `id_barang` (`id_kemasan`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `detail_penerimaan`
--

CREATE TABLE IF NOT EXISTS `detail_penerimaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_penerimaan` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `nobatch` varchar(15) NOT NULL,
  `expired` date NOT NULL,
  `harga` double NOT NULL,
  `jumlah` double NOT NULL,
  `disc_pr` double NOT NULL,
  `disc_rp` double NOT NULL,
  `is_bonus` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pembelian` (`id_penerimaan`),
  KEY `id_barang` (`id_kemasan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `detail_penjualan`
--

CREATE TABLE IF NOT EXISTS `detail_penjualan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_penjualan` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `qty` double NOT NULL,
  `harga_jual` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_penjualan` (`id_penjualan`),
  KEY `id_barang` (`id_kemasan`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=76 ;

-- --------------------------------------------------------

--
-- Table structure for table `detail_retur_penerimaan`
--

CREATE TABLE IF NOT EXISTS `detail_retur_penerimaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_retur_penerimaan` int(6) unsigned zerofill NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `expired` date NOT NULL,
  `jumlah` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_retur_penerimaan` (`id_retur_penerimaan`),
  KEY `id_barang` (`id_kemasan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `detail_retur_penjualan`
--

CREATE TABLE IF NOT EXISTS `detail_retur_penjualan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_penjualan` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `expired` date NOT NULL,
  `qty` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_penjualan` (`id_penjualan`),
  KEY `id_barang` (`id_kemasan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `diagnosis`
--

CREATE TABLE IF NOT EXISTS `diagnosis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pemeriksaan` varchar(14) NOT NULL,
  `waktu` datetime DEFAULT NULL,
  `id_penyakit` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_penyakit` (`id_penyakit`),
  KEY `id_pemeriksaan` (`id_pemeriksaan`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `diagnosis`
--

INSERT INTO `diagnosis` (`id`, `id_pemeriksaan`, `waktu`, `id_penyakit`) VALUES
(8, 'PR.001-08/2013', '2013-08-29 18:01:35', 2),
(9, 'PR.001-08/2013', '2013-08-29 18:01:35', 3),
(10, 'PR.001-08/2013', '2013-08-29 18:01:35', 4);

-- --------------------------------------------------------

--
-- Table structure for table `dinamic_harga_jual`
--

CREATE TABLE IF NOT EXISTS `dinamic_harga_jual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_kemasan` int(11) NOT NULL,
  `jual_min` double NOT NULL,
  `jual_max` double NOT NULL,
  `margin_non_resep` double NOT NULL,
  `margin_resep` double NOT NULL,
  `diskon_persen` double NOT NULL,
  `diskon_rupiah` double NOT NULL,
  `hj_non_resep` double NOT NULL,
  `hj_resep` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_barang` (`id_kemasan`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

-- --------------------------------------------------------

--
-- Table structure for table `dokter`
--

CREATE TABLE IF NOT EXISTS `dokter` (
  `id` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `kelamin` enum('L','P') NOT NULL,
  `alamat` text NOT NULL,
  `telp` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `no_str` varchar(30) NOT NULL,
  `spesialis` varchar(50) NOT NULL,
  `tgl_mulai_praktek` date NOT NULL,
  `fee` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `dokter`
--

INSERT INTO `dokter` (`id`, `nama`, `kelamin`, `alamat`, `telp`, `email`, `no_str`, `spesialis`, `tgl_mulai_praktek`, `fee`) VALUES
(000001, 'DR. ACHIRUDIN TIMORA', 'P', 'JL. MAGELANG KM 2', '-', '-', '01991001000', 'UMUM', '2013-07-27', 50),
(000002, 'DR. BAMBANG GENTHOLET', 'L', 'JL. BRIGJEN KATAMSO', '0286-29188980', '-', '-', 'ANAK', '2011-07-01', 0),
(000003, 'DR. MARIA FAHDA', 'P', 'JL. S. PARMAN', '0286-5900190', '-', 'NO.12134', 'UMUM', '2013-07-27', 0);

-- --------------------------------------------------------

--
-- Table structure for table `farmako_terapi`
--

CREATE TABLE IF NOT EXISTS `farmako_terapi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `farmako_terapi`
--

INSERT INTO `farmako_terapi` (`id`, `nama`) VALUES
(1, 'Alergi & Sistem Imun'),
(2, 'Antiinfeksi (Sistemik)'),
(3, 'Produk ASKES'),
(6, 'Sistem Gastrointestinal & Hepatobilier'),
(7, 'Sistem Pernafasan'),
(8, 'Sistem Saraf Pusat'),
(9, 'Sistem Muskoloskeletal'),
(10, 'Hormon'),
(11, 'Kontrasepsi'),
(12, 'Antiinfeksi'),
(13, 'Onkologi'),
(14, 'Sistem Kemih Kelamin'),
(15, 'Sistem Endokrin & Metabolik'),
(16, 'Vitamin & Mineral'),
(17, 'Nutrisi'),
(18, 'Mata'),
(19, 'Telinga & Mulut/Tenggorokan'),
(20, 'Kulit'),
(21, 'Anestesi Lokal & Umum'),
(22, 'Antidotum & Zat Detoksifikasi Untuk Terapi Ketergantungan Zat'),
(23, 'Larutan Intravena & Larutan Steril Lain'),
(24, 'Lain-lain'),
(25, 'Sistem Kardiovaskular & Hepatopoietik');

-- --------------------------------------------------------

--
-- Table structure for table `free_on_sale`
--

CREATE TABLE IF NOT EXISTS `free_on_sale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `id_barang` int(11) NOT NULL,
  `id_barang_gratis` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_barang` (`id_barang`),
  KEY `id_barang_gratis` (`id_barang_gratis`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `golongan`
--

CREATE TABLE IF NOT EXISTS `golongan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(30) NOT NULL,
  `margin_non_resep` double NOT NULL,
  `margin_resep` double NOT NULL,
  `diskon` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `golongan`
--

INSERT INTO `golongan` (`id`, `nama`, `margin_non_resep`, `margin_resep`, `diskon`) VALUES
(1, 'HV', 20, 25, 0),
(2, 'Obat Resep', 20, 35, 0);

-- --------------------------------------------------------

--
-- Table structure for table `grant_privileges`
--

CREATE TABLE IF NOT EXISTS `grant_privileges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `privileges_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `penduduk_id` (`user_id`,`privileges_id`),
  KEY `privileges_id` (`privileges_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `grant_privileges`
--

INSERT INTO `grant_privileges` (`id`, `user_id`, `privileges_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11);

-- --------------------------------------------------------

--
-- Table structure for table `inkaso`
--

CREATE TABLE IF NOT EXISTS `inkaso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_ref` varchar(15) NOT NULL,
  `tanggal` date NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `no_kuitansi` varchar(30) NOT NULL,
  `cara_bayar` enum('Uang','Cek','Giro','Transfer') NOT NULL,
  `id_bank` int(11) DEFAULT NULL,
  `no_transaksi` varchar(30) NOT NULL,
  `keterangan` text NOT NULL,
  `tgl_cair` date DEFAULT NULL COMMENT 'Jika pembayaran menggunakan giro',
  `is_lunas` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_supplier` (`id_supplier`),
  KEY `id_bank` (`id_bank`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `inkaso_detail`
--

CREATE TABLE IF NOT EXISTS `inkaso_detail` (
  `id_inkaso` int(11) NOT NULL,
  `id_penerimaan` int(11) NOT NULL,
  `pembayaran` double NOT NULL,
  `potongan` double NOT NULL,
  KEY `id_inkaso` (`id_inkaso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instansi`
--

CREATE TABLE IF NOT EXISTS `instansi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `email` varchar(30) NOT NULL,
  `telp` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `instansi`
--

INSERT INTO `instansi` (`id`, `nama`, `alamat`, `email`, `telp`) VALUES
(1, 'Badan pemeriksaan obat dan makanan', 'JL.Tompeyan (Pingit). Tegalrejo, Yogyakarta', '-', '0274-5512345');

-- --------------------------------------------------------

--
-- Table structure for table `item_kit`
--

CREATE TABLE IF NOT EXISTS `item_kit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `margin_persen` double NOT NULL,
  `margin_rupiah` double NOT NULL,
  `diskon_persen` double NOT NULL,
  `diskon_rupiah` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_kit_detail`
--

CREATE TABLE IF NOT EXISTS `item_kit_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_item_kit` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `jumlah` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_item_kit` (`id_item_kit`),
  KEY `id_barang` (`id_kemasan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_dokter`
--

CREATE TABLE IF NOT EXISTS `jadwal_dokter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_dokter` int(6) unsigned zerofill NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `jam` time NOT NULL,
  `akhir` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_dokter` (`id_dokter`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `jadwal_dokter`
--

INSERT INTO `jadwal_dokter` (`id`, `id_dokter`, `hari`, `jam`, `akhir`) VALUES
(1, 000001, 'Senin', '15:30:00', '20:00:00'),
(2, 000001, 'Selasa', '15:30:00', '20:00:00'),
(3, 000002, 'Senin', '12:00:00', '15:00:00'),
(4, 000001, 'Rabu', '15:30:00', '20:00:00'),
(5, 000003, 'Senin', '08:00:00', '11:00:00'),
(6, 000003, 'Selasa', '08:00:00', '11:00:00'),
(7, 000003, 'Rabu', '08:00:00', '11:00:00'),
(9, 000002, 'Selasa', '12:00:00', '15:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE IF NOT EXISTS `karyawan` (
  `id` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `kelamin` enum('L','P') NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `kabupaten` varchar(30) NOT NULL,
  `propinsi` varchar(30) NOT NULL,
  `telp` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `jabatan` enum('APA','Kasir','Staff') NOT NULL,
  `no_sipa` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id`, `nama`, `kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `kabupaten`, `propinsi`, `telp`, `email`, `jabatan`, `no_sipa`) VALUES
(000001, 'WULAN SUCI', 'P', 'SLEMAN', '1991-04-20', 'JL. DR WAHDIN', 'SLEMAN', 'YOGYAKARTA', '-', '-', 'APA', 'NNO.1023919910'),
(000002, 'ELNA LIANITAS', 'P', 'PALEMBANG', '1988-06-16', 'JL. SURONATAN NO.22', 'YOGYAKARTA', 'YOGYAKARTA', '085228024202', 'ELNALIANITA@YAHOO.COM', 'Staff', '-'),
(000003, 'WELAS', 'P', '', '0000-00-00', '', '', '', '', '', 'APA', '');

-- --------------------------------------------------------

--
-- Table structure for table `kelas_terapi`
--

CREATE TABLE IF NOT EXISTS `kelas_terapi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_farmako_terapi` int(11) NOT NULL,
  `nama` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_farmako_terapi` (`id_farmako_terapi`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `kelas_terapi`
--

INSERT INTO `kelas_terapi` (`id`, `id_farmako_terapi`, `nama`) VALUES
(1, 1, 'Antihistamin & Antialergi'),
(2, 1, 'Imunosupresan'),
(3, 1, 'Vaksin, Antiserum & Imunologik'),
(4, 2, 'Anestesi Lokal & Umum'),
(5, 6, 'Antasid, Obat Antirefluks & An'),
(6, 6, 'Regulator GIT, Antiflatulen & '),
(7, 6, 'Antispasmodik'),
(8, 6, 'Antidiare'),
(9, 6, 'Laksatif, Pencahar'),
(10, 6, 'Digestan'),
(11, 6, 'Kolagogum, Kolelitolitik & Hep'),
(12, 6, 'Preparat Anorektal'),
(13, 6, 'Antiemetik'),
(14, 6, 'Obat Gastrointestinal Lain'),
(15, 25, 'Obat Jantung'),
(16, 25, 'Obat Antiangina'),
(17, 25, 'ACE Inhibitor (ACEI)'),
(18, 25, 'Penyekat Beta (Beta Blocker)'),
(19, 25, 'Antagonis Kalsium'),
(20, 25, 'Antagonis Angiotensin II'),
(21, 25, 'Antihipertensi Golongan Lain'),
(22, 25, 'Diuretik'),
(23, 25, 'Vasodilator Perifer & Aktivato'),
(24, 25, 'Vasokonstriktor'),
(25, 25, 'Obat Dislipidemia');

-- --------------------------------------------------------

--
-- Table structure for table `kemasan`
--

CREATE TABLE IF NOT EXISTS `kemasan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_barang` int(11) NOT NULL,
  `barcode` varchar(30) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi` double NOT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `isi_satuan` double NOT NULL,
  `is_harga_bertingkat` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 = Tidak, 1 = Ya',
  `default_kemasan` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_barang` (`id_barang`),
  KEY `id_kemasan` (`id_kemasan`),
  KEY `id_satuan` (`id_satuan`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8060 ;

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE IF NOT EXISTS `module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `icons` varchar(255) NOT NULL,
  `show_desktop` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`id`, `nama`, `icons`, `show_desktop`) VALUES
(1, 'Pelayanan', '', 1),
(2, 'Inventory', '', 1),
(3, 'Referensi Data', '', 1),
(4, 'Laporan', '', 1),
(5, 'Setting', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pabrik`
--

CREATE TABLE IF NOT EXISTS `pabrik` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `email` varchar(30) NOT NULL,
  `telp` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `pabrik`
--

INSERT INTO `pabrik` (`id`, `nama`, `alamat`, `email`, `telp`) VALUES
(2, 'Trackindo PT', 'Jl. Kusuma Negara Jakarta Pusat', 'trackindofarma@yahoo.com', '021-555060'),
(5, 'Kimia Farma, PT', 'Bandung', 'kimiafarma@yahoo.com', '-'),
(9, 'kalbe farma pt,', 'Jakarta', '-', '-');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `jenis` enum('Personal','Perusahaan') NOT NULL,
  `kelamin` enum('L','P') NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `kabupaten` varchar(30) NOT NULL,
  `provinsi` varchar(30) NOT NULL,
  `telp` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `diskon` double NOT NULL,
  `catatan` text NOT NULL,
  `id_asuransi` int(11) DEFAULT NULL,
  `nopolish` varchar(20) NOT NULL,
  `foto` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_asuransi` (`id_asuransi`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `jenis`, `kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `kabupaten`, `provinsi`, `telp`, `email`, `diskon`, `catatan`, `id_asuransi`, `nopolish`, `foto`) VALUES
(000001, 'FERLI APRIANINGRUM', 'Personal', 'P', 'BANJARNEGARA', '0000-00-00', 'JL. PURWANEGARA', '', '', '-', '-', 0, '-', 1, '01920391000199100', ''),
(000002, 'ARVIN NIZAR', 'Personal', 'L', 'BANJARNEGARA', '1987-04-20', 'JL. MAGELANG', '', '', '085236688999', '-', 0, '-', 2, '01023901910290', 'arvin_nizar_1396381301.jpg'),
(000003, 'APOTEK SARIDEWI', 'Perusahaan', 'P', 'BANJARNEGARA', '0000-00-00', 'JL. KIJAGAPATI NO.15', '', '', '-', 'FAHDA_VEGASHA@YAHOO.CO.ID', 0, '-', NULL, '', ''),
(000005, 'SHAFIRA WILDA PUSPARANI', 'Personal', 'P', 'BANJARNEGARA', '1999-04-15', 'JL. KIJAGAPATI NO 15', '', '', '085236688123', '-', 5, 'PELANGGAN TETAP', NULL, '', 'shafira_wilda_pusparani_793850300.jpg'),
(000006, 'AXL FABIANSKI', 'Personal', 'P', 'BANJARNEGARA', '2013-09-17', 'JL. RAYA PURWANEGARA', '', '', '-', '-', 0, '-', 3, '0129301920319', 'axl_fabianski_758176953.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pemeriksaan`
--

CREATE TABLE IF NOT EXISTS `pemeriksaan` (
  `id` varchar(14) NOT NULL,
  `tanggal` date NOT NULL,
  `anamnesis` text NOT NULL,
  `pemeriksaan` text NOT NULL,
  `id_pelanggan` int(6) unsigned zerofill NOT NULL,
  `id_dokter` int(6) unsigned zerofill DEFAULT NULL,
  `foto` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pelanggan` (`id_pelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pemeriksaan`
--

INSERT INTO `pemeriksaan` (`id`, `tanggal`, `anamnesis`, `pemeriksaan`, `id_pelanggan`, `id_dokter`, `foto`) VALUES
('PR.001-08/2013', '2013-08-29', 'anamnesis fuck', 'pameriksaan you', 000002, 000002, 'arvin_nizar_691176470.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE IF NOT EXISTS `pemesanan` (
  `id` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `tgl_datang` date DEFAULT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_users` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplier` (`id_supplier`),
  KEY `id_users` (`id_users`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `penerimaan`
--

CREATE TABLE IF NOT EXISTS `penerimaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `faktur` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_pemesanan` int(6) unsigned zerofill DEFAULT NULL,
  `ppn` double NOT NULL,
  `materai` double NOT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `id_users` int(11) DEFAULT NULL,
  `diskon_persen` double NOT NULL,
  `diskon_rupiah` double NOT NULL,
  `total` double NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `faktur` (`faktur`),
  KEY `id_pemesanan` (`id_pemesanan`),
  KEY `id_users` (`id_users`),
  KEY `id_supplier` (`id_supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE IF NOT EXISTS `penjualan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `waktu` datetime NOT NULL,
  `id_resep` varchar(11) DEFAULT NULL,
  `id_pelanggan` int(6) unsigned zerofill DEFAULT NULL,
  `diskon_persen` double NOT NULL,
  `diskon_rupiah` double NOT NULL,
  `ppn` double NOT NULL,
  `total` double NOT NULL,
  `tuslah` double NOT NULL,
  `embalage` double NOT NULL,
  `id_asuransi` int(11) DEFAULT NULL,
  `reimburse` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_customer` (`id_pelanggan`),
  KEY `id_asuransi` (`id_asuransi`),
  KEY `id_resep` (`id_resep`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Table structure for table `penyakit`
--

CREATE TABLE IF NOT EXISTS `penyakit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topik` varchar(100) NOT NULL,
  `sub_kode` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `penyakit`
--

INSERT INTO `penyakit` (`id`, `topik`, `sub_kode`) VALUES
(1, 'Rotaviral enteritis', 'A08.0'),
(2, 'Acute gastroenteropathy due to Norwalk agent', 'A08.1'),
(3, 'Adenoviral enteritis', 'A08.2'),
(4, 'Other viral enteritis', 'A08.3'),
(5, 'Viral intestinal infection, unspecified', 'A08.4'),
(6, 'Other specified intestinal infections', 'A08.5'),
(7, 'Tuberculosis of lung, confirmed by sputum microscopy with or withoutculture', 'A15.0'),
(8, 'Tuberculosis of lung, confirmed by culture only', 'A15.1'),
(9, 'Tuberculosis of lung, confirmed histologically', 'A15.2'),
(10, 'Tuberculosis of lung, confirmed by unspecified means', 'A15.3'),
(11, 'Tuberculosis of intrathoracic lymph nodes, confirmedbacteriologically and histologically', 'A15.4'),
(13, 'Tuberculous pleurisy, confirmed bacteriologically andhistologically', 'A15.6');

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE IF NOT EXISTS `privileges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) DEFAULT NULL,
  `form_nama` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `show_desktop` int(1) NOT NULL,
  `sort` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `modul_id` (`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`id`, `module_id`, `form_nama`, `url`, `icon`, `show_desktop`, `sort`) VALUES
(1, 1, 'Penjualan', 'penjualan/', 'cart.png', 1, 1),
(2, 2, 'Pemesanan', 'inventory/pemesanan', 'order.png', 1, 1),
(3, 2, 'Penerimaan', 'inventory/penerimaan', 'buy.png', 1, 2),
(4, 3, 'Barang', 'inventory/barang', 'barang.png', 1, 1),
(5, 3, 'Customer', 'masterdata/customer', 'anggota.png', 1, 4),
(6, 3, 'Supplier', 'masterdata/supplier', '', 1, 2),
(7, 2, 'Retur Penerimaan', 'inventory/retur_penerimaan', '', 1, 2),
(8, 2, 'Stok Opname', 'inventory/stok_opname', '', 1, 4),
(9, 4, 'Stok', 'laporan/stok', '', 1, 1),
(10, 3, 'Pabrik', 'masterdata/pabrik', '', 1, 3),
(11, 5, 'Set Harga', 'setting/harga', '', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `resep`
--

CREATE TABLE IF NOT EXISTS `resep` (
  `id` varchar(11) NOT NULL,
  `waktu` datetime NOT NULL,
  `id_dokter` int(6) unsigned zerofill DEFAULT NULL,
  `id_pasien` int(6) unsigned zerofill DEFAULT NULL,
  `keterangan` text NOT NULL,
  `id_users` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dokter_penduduk_id` (`id_dokter`),
  KEY `pasien_penduduk_id` (`id_pasien`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `resep_r`
--

CREATE TABLE IF NOT EXISTS `resep_r` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_resep` varchar(11) NOT NULL,
  `r_no` smallint(3) NOT NULL,
  `resep_r_jumlah` smallint(3) NOT NULL,
  `tebus_r_jumlah` smallint(3) NOT NULL,
  `aturan` int(2) NOT NULL,
  `pakai` int(2) NOT NULL,
  `iter` tinyint(3) NOT NULL,
  `id_tarif` int(11) DEFAULT NULL,
  `nominal` double NOT NULL,
  `id_karyawan` int(6) unsigned zerofill DEFAULT NULL,
  `id_barang` int(11) NOT NULL,
  `jual_harga` double NOT NULL,
  `dosis_racik` double NOT NULL,
  `jumlah_pakai` double NOT NULL,
  `formularium` enum('Tidak','Ya') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resep_id` (`id_resep`),
  KEY `tarif_id` (`id_tarif`),
  KEY `id_karyawan` (`id_karyawan`),
  KEY `id_barang` (`id_barang`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Table structure for table `retur_penerimaan`
--

CREATE TABLE IF NOT EXISTS `retur_penerimaan` (
  `id` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `id_supplier` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_supplier` (`id_supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `retur_penjualan`
--

CREATE TABLE IF NOT EXISTS `retur_penjualan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_ref` varchar(15) NOT NULL,
  `waktu` date NOT NULL,
  `id_penjualan` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_ref` (`no_ref`),
  KEY `id_penjualan` (`id_penjualan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `satuan`
--

CREATE TABLE IF NOT EXISTS `satuan` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `is_satuan_kemasan` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=42 ;

--
-- Dumping data for table `satuan`
--

INSERT INTO `satuan` (`id`, `nama`, `is_satuan_kemasan`) VALUES
(1, 'Strip', 0),
(2, 'Butir', 0),
(3, 'Mg', 1),
(4, 'Ml', 0),
(5, 'Pcs', 0),
(6, 'Kg', 0),
(7, 'Cm', 0),
(8, 'M', 0),
(9, 'L', 0),
(10, 'Gallon', 0),
(11, 'Lb', 0),
(12, 'Pound', 0),
(13, 'Buah', 0),
(14, 'Lembar', 0),
(15, 'Ikat', 0),
(16, 'Dosin', 0),
(17, 'Botol', 0),
(18, 'Ampul', 0),
(19, 'Tube', 0),
(20, 'Flakon', 0),
(21, 'Plabot', 0),
(22, 'Pot', 0),
(23, 'Biji', 0),
(24, 'Bungkus', 0),
(25, 'Batang', 0),
(26, 'Iris', 0),
(27, 'Kotak', 0),
(28, 'Karton', 0),
(29, 'Box', 0),
(30, 'mcg', 0),
(31, 'Satuan', 0),
(32, 'Mdi', 0),
(33, 'Supp', 0),
(35, 'Fls', 0),
(36, 'Kaleng', 0),
(37, 'Unit', 0),
(38, 'Karung', 0),
(39, 'Sachet', 0),
(40, 'Tablet', 0),
(41, 'mg/5ml', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sediaan`
--

CREATE TABLE IF NOT EXISTS `sediaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `sediaan`
--

INSERT INTO `sediaan` (`id`, `nama`) VALUES
(1, 'Drop'),
(2, 'dragee'),
(3, 'Enteric coated tablet'),
(4, 'Film coated caplet'),
(5, 'Film coated tablet'),
(6, 'Film coated talet'),
(7, 'Kaplet'),
(8, 'Kaplet coated'),
(9, 'Kaplet salut selaput'),
(10, 'Kapsul'),
(11, 'Kapsul lunak'),
(12, 'Tablet'),
(13, 'Tablet salut gula'),
(14, 'Tablet salut selaput'),
(15, 'Kapsul'),
(16, 'Suppositoria'),
(17, 'Injeksi'),
(18, 'Tetes mata'),
(19, 'Tetes telinga'),
(20, 'Tetes hidung'),
(21, 'Tetes untuk diminum'),
(22, 'Sirup'),
(23, 'Solutio'),
(24, 'Mixtur'),
(25, 'Puyer tidak terbagi'),
(26, 'Infus'),
(27, 'Emulsi');

-- --------------------------------------------------------

--
-- Table structure for table `stok`
--

CREATE TABLE IF NOT EXISTS `stok` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `waktu` datetime NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `transaksi` enum('Stok Opname','Pemesanan','Penerimaan','Retur Penerimaan','Penjualan','Retur Penjualan') DEFAULT NULL,
  `nobatch` varchar(15) NOT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `ed` date DEFAULT NULL,
  `masuk` double NOT NULL,
  `keluar` double NOT NULL,
  `Keterangan` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_barang` (`id_barang`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9506 ;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE IF NOT EXISTS `supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `telp` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `nama`, `alamat`, `email`, `telp`) VALUES
(1, 'PT. Naufalindo Jaya', 'Jl. Affandi Gg. Surya No. 3B Yogyakarta 54595', '', '0274-5556660'),
(2, 'PT. Argon Anugrah Medika', 'Jl. Yogyakarta - Solo Km 3', '', '085236688999'),
(3, 'Sedico pharma ceutical', 'Jl. Magelang Km 9', '', '0274-5555677');

-- --------------------------------------------------------

--
-- Table structure for table `tarif`
--

CREATE TABLE IF NOT EXISTS `tarif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(254) NOT NULL,
  `nominal` bigint(12) NOT NULL,
  `kode_akun` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `layanan_id` (`nama`),
  KEY `kode_akun` (`kode_akun`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `tarif`
--

INSERT INTO `tarif` (`id`, `nama`, `nominal`, `kode_akun`) VALUES
(5, 'ASKES TUSLAH DAN EMBALAGE', 800, '1.1'),
(6, 'Jasa Apoteker', 3500, '1.1'),
(7, 'TUSLAH DAN EMBALAGE', 1500, '1.1'),
(8, 'Chemical Pilling', 35000, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tindakan`
--

CREATE TABLE IF NOT EXISTS `tindakan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `waktu` datetime NOT NULL,
  `id_pemeriksaan` varchar(14) NOT NULL,
  `id_tarif` int(11) NOT NULL,
  `nominal` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pemeriksaan` (`id_pemeriksaan`),
  KEY `id_tarif` (`id_tarif`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tindakan`
--

INSERT INTO `tindakan` (`id`, `waktu`, `id_pemeriksaan`, `id_tarif`, `nominal`) VALUES
(6, '2013-08-29 18:01:35', 'PR.001-08/2013', 8, 35000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(6) unsigned zerofill DEFAULT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `level` enum('Staff','Admin') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `id_karyawan` (`id_karyawan`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `id_karyawan`, `username`, `password`, `level`) VALUES
(1, 000001, 'admin', '81dc9bdb52d04dc20036dbd8313ed055', 'Admin');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bank`
--
ALTER TABLE `bank`
  ADD CONSTRAINT `bank_ibfk_1` FOREIGN KEY (`kode_akun`) REFERENCES `akun` (`kode`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_pabrik`) REFERENCES `pabrik` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `barang_ibfk_3` FOREIGN KEY (`id_golongan`) REFERENCES `golongan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `barang_ibfk_4` FOREIGN KEY (`id_sediaan`) REFERENCES `sediaan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `barang_ibfk_5` FOREIGN KEY (`satuan_kekuatan`) REFERENCES `satuan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `barang_ibfk_6` FOREIGN KEY (`id_kelas_terapi`) REFERENCES `kelas_terapi` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `detail_bayar_penjualan`
--
ALTER TABLE `detail_bayar_penjualan`
  ADD CONSTRAINT `detail_bayar_penjualan_ibfk_1` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  ADD CONSTRAINT `detail_pemesanan_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pemesanan_ibfk_2` FOREIGN KEY (`id_kemasan`) REFERENCES `kemasan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_penerimaan`
--
ALTER TABLE `detail_penerimaan`
  ADD CONSTRAINT `detail_penerimaan_ibfk_1` FOREIGN KEY (`id_penerimaan`) REFERENCES `penerimaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_penerimaan_ibfk_2` FOREIGN KEY (`id_kemasan`) REFERENCES `kemasan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD CONSTRAINT `detail_penjualan_ibfk_1` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_penjualan_ibfk_2` FOREIGN KEY (`id_kemasan`) REFERENCES `kemasan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_retur_penerimaan`
--
ALTER TABLE `detail_retur_penerimaan`
  ADD CONSTRAINT `detail_retur_penerimaan_ibfk_1` FOREIGN KEY (`id_kemasan`) REFERENCES `kemasan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_retur_penerimaan_ibfk_2` FOREIGN KEY (`id_retur_penerimaan`) REFERENCES `retur_penerimaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `diagnosis`
--
ALTER TABLE `diagnosis`
  ADD CONSTRAINT `diagnosis_ibfk_3` FOREIGN KEY (`id_pemeriksaan`) REFERENCES `pemeriksaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `diagnosis_ibfk_2` FOREIGN KEY (`id_penyakit`) REFERENCES `penyakit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dinamic_harga_jual`
--
ALTER TABLE `dinamic_harga_jual`
  ADD CONSTRAINT `dinamic_harga_jual_ibfk_1` FOREIGN KEY (`id_kemasan`) REFERENCES `kemasan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inkaso`
--
ALTER TABLE `inkaso`
  ADD CONSTRAINT `inkaso_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inkaso_ibfk_2` FOREIGN KEY (`id_bank`) REFERENCES `bank` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `item_kit_detail`
--
ALTER TABLE `item_kit_detail`
  ADD CONSTRAINT `item_kit_detail_ibfk_1` FOREIGN KEY (`id_item_kit`) REFERENCES `item_kit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `item_kit_detail_ibfk_2` FOREIGN KEY (`id_kemasan`) REFERENCES `kemasan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jadwal_dokter`
--
ALTER TABLE `jadwal_dokter`
  ADD CONSTRAINT `jadwal_dokter_ibfk_1` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kelas_terapi`
--
ALTER TABLE `kelas_terapi`
  ADD CONSTRAINT `kelas_terapi_ibfk_1` FOREIGN KEY (`id_farmako_terapi`) REFERENCES `farmako_terapi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kemasan`
--
ALTER TABLE `kemasan`
  ADD CONSTRAINT `kemasan_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kemasan_ibfk_3` FOREIGN KEY (`id_kemasan`) REFERENCES `satuan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kemasan_ibfk_4` FOREIGN KEY (`id_satuan`) REFERENCES `satuan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD CONSTRAINT `pelanggan_ibfk_1` FOREIGN KEY (`id_asuransi`) REFERENCES `asuransi` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pemeriksaan`
--
ALTER TABLE `pemeriksaan`
  ADD CONSTRAINT `pemeriksaan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penerimaan`
--
ALTER TABLE `penerimaan`
  ADD CONSTRAINT `penerimaan_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penerimaan_ibfk_2` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penerimaan_ibfk_3` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_ibfk_1` FOREIGN KEY (`id_asuransi`) REFERENCES `asuransi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penjualan_ibfk_2` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `penjualan_ibfk_3` FOREIGN KEY (`id_resep`) REFERENCES `resep` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `resep`
--
ALTER TABLE `resep`
  ADD CONSTRAINT `resep_ibfk_1` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `resep_ibfk_2` FOREIGN KEY (`id_pasien`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `resep_r`
--
ALTER TABLE `resep_r`
  ADD CONSTRAINT `resep_r_ibfk_2` FOREIGN KEY (`id_tarif`) REFERENCES `tarif` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `resep_r_ibfk_3` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `resep_r_ibfk_4` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `resep_r_ibfk_5` FOREIGN KEY (`id_resep`) REFERENCES `resep` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `retur_penerimaan`
--
ALTER TABLE `retur_penerimaan`
  ADD CONSTRAINT `retur_penerimaan_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stok`
--
ALTER TABLE `stok`
  ADD CONSTRAINT `stok_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tarif`
--
ALTER TABLE `tarif`
  ADD CONSTRAINT `tarif_ibfk_1` FOREIGN KEY (`kode_akun`) REFERENCES `akun` (`kode`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tindakan`
--
ALTER TABLE `tindakan`
  ADD CONSTRAINT `tindakan_ibfk_3` FOREIGN KEY (`id_pemeriksaan`) REFERENCES `pemeriksaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tindakan_ibfk_2` FOREIGN KEY (`id_tarif`) REFERENCES `tarif` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
