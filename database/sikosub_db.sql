-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2026 at 02:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sikosub_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `kost_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookmarks`
--

INSERT INTO `bookmarks` (`id`, `user_id`, `kost_id`, `created_at`, `updated_at`) VALUES
(26, 3, 4, '2026-06-25 03:30:39', '2026-06-25 03:30:39');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instansis`
--

CREATE TABLE `instansis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_instansi` varchar(255) NOT NULL,
  `kategori` enum('Pemerintahan','Pendidikan','Perusahaan') NOT NULL,
  `alamat` text DEFAULT NULL,
  `kontak` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kosts`
--

CREATE TABLE `kosts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `visit_count` int(11) DEFAULT 0,
  `mitra_id` bigint(20) UNSIGNED DEFAULT NULL,
  `instansi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_kost` varchar(255) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `tipe_kost` enum('putra','putri','campur') NOT NULL DEFAULT 'campur',
  `harga_per_bulan` int(11) NOT NULL,
  `harga_diskon` int(11) DEFAULT 0,
  `jumlah_kamar` int(11) NOT NULL DEFAULT 1,
  `sisa_kamar` int(11) NOT NULL DEFAULT 1,
  `fasilitas` text DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `alamat_lengkap` text DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `jarak_km` double(8,2) DEFAULT NULL,
  `rating` double(8,1) DEFAULT NULL,
  `gambar_utama` varchar(255) DEFAULT NULL,
  `foto_kost` varchar(255) DEFAULT NULL,
  `gallery_images` text DEFAULT NULL,
  `no_wa` varchar(20) DEFAULT NULL,
  `maps` text DEFAULT NULL,
  `kategori_wilayah` varchar(100) DEFAULT NULL,
  `detail_wilayah` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `disewakan_oleh` varchar(255) DEFAULT NULL,
  `info_kamar` varchar(255) DEFAULT NULL,
  `fasilitas_umum` text DEFAULT NULL,
  `spesifikasi_kamar` text DEFAULT NULL,
  `fasilitas_kamar` text DEFAULT NULL,
  `fasilitas_km` text DEFAULT NULL,
  `pemilik` varchar(255) DEFAULT NULL,
  `fasilitas_parkir` text DEFAULT NULL,
  `peraturan` text DEFAULT NULL,
  `ketentuan` text DEFAULT NULL,
  `tempat_terdekat` text DEFAULT NULL,
  `review_admin` text DEFAULT NULL,
  `kontak_pemilik` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kosts`
--

INSERT INTO `kosts` (`id`, `visit_count`, `mitra_id`, `instansi_id`, `nama_kost`, `kategori`, `tipe_kost`, `harga_per_bulan`, `harga_diskon`, `jumlah_kamar`, `sisa_kamar`, `fasilitas`, `alamat`, `alamat_lengkap`, `latitude`, `longitude`, `jarak_km`, `rating`, `gambar_utama`, `foto_kost`, `gallery_images`, `no_wa`, `maps`, `kategori_wilayah`, `detail_wilayah`, `created_at`, `updated_at`, `disewakan_oleh`, `info_kamar`, `fasilitas_umum`, `spesifikasi_kamar`, `fasilitas_kamar`, `fasilitas_km`, `pemilik`, `fasilitas_parkir`, `peraturan`, `ketentuan`, `tempat_terdekat`, `review_admin`, `kontak_pemilik`) VALUES
(4, 46, NULL, NULL, 'AZIZ KOST', NULL, 'putri', 550000, 500000, 1, 1, NULL, 'Pasirkareumbi, Kec. Subang, Kabupaten Subang, Jawa Barat 41211', NULL, NULL, NULL, 10.00, 3.0, '1782377313.avif', NULL, '\"[\\\"1782377268_galeri1_6a3ceb34ce46e.webp\\\",\\\"1782377224_galeri2_6a3ceb08c810a.webp\\\",\\\"1782377224_galeri3_6a3ceb08c947f.avif\\\",\\\"1782377224_galeri4_6a3ceb08ca31a.avif\\\",\\\"1782287652_galeri_extra_6a3b8d2445e70.png\\\",\\\"1782338462_6a3c539ec4eb5.png\\\",\\\"1782377224_galeri_extra_6a3ceb08cd0d5.avif\\\",\\\"1782377383_galeri_extra_6a3ceba79268f.avif\\\",\\\"1782377390_galeri_extra_6a3cebaeeaa81.avif\\\",\\\"1782377409_galeri_extra_6a3cebc15795f.webp\\\"]\"', '085221219161', 'https://maps.app.goo.gl/sUJ1cskm42ViW5Fc9', 'unsub', 'Di dekat Universitas SUBANG', '2026-06-23 16:06:09', '2026-06-25 04:49:33', 'ARRAFLY AZIZ SAPUTRA', 'Tersedia', 'Dapur, Jemuran, WiFi', '6x6', 'Kasur, Lemari,', 'Kamar Mandi dalam, dan luas', 'ARRAFLY AZIZ SAPUTRA', 'Parkir Motor/Mobil Luas', 'Akses 20 jam', 'Minimal sewa 1 tahun', 'Fotocopy', 'Sangat Direkomendasikan', '082121291219'),
(5, 0, NULL, NULL, 'ARIF KOST', NULL, 'campur', 700000, 500000, 1, 1, NULL, 'Jln. Subang Subang Jawa Barat', NULL, NULL, NULL, NULL, 5.0, '1782377336.avif', NULL, '\"[\\\"1782377361_galeri1_6a3ceb91a25e0.webp\\\",\\\"1782377361_galeri2_6a3ceb91a3ff0.webp\\\",\\\"1782377361_galeri3_6a3ceb91a5228.jpg\\\",\\\"1782377361_galeri4_6a3ceb91a6327.webp\\\",\\\"1782377361_galeri_extra_6a3ceb91a791e.webp\\\",\\\"1782377368_galeri_extra_6a3ceb98c6ab1.avif\\\",\\\"1782377376_galeri_extra_6a3ceba0c72d0.avif\\\"]\"', '083827914570', 'https://maps.app.goo.gl/bm9eBpen8GZG2Zfp7', 'perusahaan', 'PT Taekwang', '2026-06-23 20:35:40', '2026-06-25 04:36:54', NULL, 'Tersedia', 'Dapur, WiFi', '6x6', 'Kasur', 'Kloset Duduk', NULL, 'Parkir Mobil', 'Akses 20 jam', 'Minimal sewa 1 tahun', 'Eraphone', NULL, NULL),
(6, 0, NULL, NULL, 'Cahaya Kost', NULL, 'campur', 1500000, 0, 1, 1, NULL, 'Jalan Otto Iskandardinata Subang Jawa Barat', NULL, NULL, NULL, NULL, 5.0, '1782386665.jpg', NULL, NULL, '085907968857', 'https://maps.app.goo.gl/aCQ8SyHtrR9pZjo29', 'perusahaan', 'Depan Bank BCA', '2026-06-25 04:24:25', '2026-06-25 04:24:25', NULL, NULL, 'Parkir, Jemuran, Laundry, WiFi', '7x7', 'AC, Kulkas, Lemari', 'KM Dalam, Kloset Duduk, Water Heater', NULL, 'Parkir Motor dan Mobil luas dan aman', 'Jangan Merokok dalam Kamar', 'Minimal sewa 2 bulan', 'Yogya Toserba, Point Coffee, Richese', NULL, NULL),
(7, 4, 4, NULL, 'Mentari Kost', NULL, 'putri', 500000, 0, 1, 1, NULL, 'jalan wanareja subang jawa barat', NULL, NULL, NULL, NULL, 5.0, '1782388486.jpg', NULL, '\"[\\\"1782388598_6a3d177667cc7.jpg\\\",\\\"1782388620_6a3d178c63a62.jpg\\\",\\\"1782388632_6a3d17983ed0e.jpg\\\",\\\"1782388632_6a3d17983f4a0.jpg\\\"]\"', '6285091357432', 'https://maps.app.goo.gl/bm9eBpen8GZG2Zfp7', 'unsub', 'Belakang Kampus Universitas Subang', '2026-06-25 04:54:46', '2026-06-25 04:58:13', NULL, 'Tersedia', 'Dapur Bersama, Jemuran, Parkir, WiFi', '3x5', 'Kasur, Lemari, Kipas', 'Kamar Mandi dalam, bersih dan luas', NULL, 'Parkir luas dan aman', 'Maksimal bertamu jam 22.00, dan jangan berisik', NULL, 'Masjid, Universitas Subang, Indomart', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `landings`
--

CREATE TABLE `landings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `tentang` text DEFAULT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL,
  `filter_pendidikan` text DEFAULT NULL,
  `image_pendidikan` text DEFAULT NULL,
  `filter_pemerintah` text DEFAULT NULL,
  `image_pemerintah` text DEFAULT NULL,
  `filter_perusahaan` text DEFAULT NULL,
  `image_perusahaan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `landings`
--

INSERT INTO `landings` (`id`, `title`, `subtitle`, `banner_image`, `content`, `tentang`, `visi`, `misi`, `filter_pendidikan`, `image_pendidikan`, `filter_pemerintah`, `image_pemerintah`, `filter_perusahaan`, `image_perusahaan`, `created_at`, `updated_at`) VALUES
(1, 'SIKOSUB', NULL, '[\"1782372110_banner_0.jpg\",\"1782372113_banner_1.jpg\",\"1782372116_banner_2.jpg\",\"1782372119_banner_3.jpg\"]', NULL, 'Kota Subang terus mengalami perkembangan yang pesat, baik sebagai pusat pendidikan dengan hadirnya berbagai institusi seperti Universitas Subang, maupun sebagai kawasan pertumbuhan industri. Hal ini menarik banyak pendatang baru setiap tahunnya, yang didominasi oleh mahasiswa dan pekerja profesional. Kebutuhan primer akan tempat tinggal sementara (kost) sangat tinggi, namun proses pencariannya masih sangat konvensional. Pencarian yang mengandalkan survei fisik dan informasi mulut ke mulut ini sering kali tidak efisien, memakan waktu, dan menyulitkan pendatang dalam membandingkan spesifikasi, kesesuaian anggaran (budget), dan jarak ke lokasi aktivitas.', 'Menjadi platform direktori terpusat yang memudahkan pemetaan dan pencarian hunian sementara yang layak dan strategis di Kota Subang.', '1.Menyediakan fitur pemfilteran berbasis rentang harga, fasilitas, dan penyesuaian jarak lokasi.\r\n2. Menyediakan platform digital bagi pemilik kost untuk mempromosikan properti dan mengelola data operasional secara efisien.\r\n3. Memfasilitasi proses pengajuan sewa secara digital dan menyediakan informasi properti yang tervalidasi keakuratannya.', 'UNIVERSITAS MANDIRI, UNIVERSITAS SUBANG, POLITEKNIK NEGERI SUBANG, SMK Negeri 2 SUBANG, STIESA, SMK Negeri 1 SUBANG, SMA NEGER 1 SUBANG', 'pendidikan_1782372906_6a3cda2a99290.png,pendidikan_1782372925_6a3cda3d01db2.png,pendidikan_1782373149_6a3cdb1d37c08.png,pendidikan_1782384585_6a3d07c9a4448.png,pendidikan_1782384616_6a3d07e8e1ce6.jpeg,pendidikan_1782384725_6a3d0855e1002.jpg,pendidikan_1782384740_6a3d086465ca3.png', 'PEMDA SUBANG, Badan Pusat Statistik Subang, Dinas Perhubungan Kabupaten Subang, Dinas Pertanian, Dinas Lingkungan Hidup, DISKOMINFO, BRIN', 'pemerintah_1782373363_6a3cdbf327f2e.jpg,pemerintah_1782373517_6a3cdc8d4482f.png,pemerintah_1782374215_6a3cdf479242a.png,pemerintah_1782384867_6a3d08e3763ea.png,pemerintah_1782384881_6a3d08f176b27.jpg,pemerintah_1782384899_6a3d09030e5b7.jpg,pemerintah_1782385736_6a3d0c48b2080.png', 'PT Taekwang, MIE GACOAN SUBANG, Hotel NALENDRA SUBANG, IDX, VINFAST, Bank BCA, Bank BRI', 'perusahaan_1782374428_6a3ce01c5d36e.png,perusahaan_1782374442_6a3ce02a9e1e9.png,perusahaan_1782374496_6a3ce060b3dd4.png,perusahaan_1782385924_6a3d0d04ef67d.png,perusahaan_1782385940_6a3d0d142cf1d.png,perusahaan_1782385954_6a3d0d227cd63.jpg,perusahaan_1782385965_6a3d0d2d88e5f.png', '2026-06-19 01:27:55', '2026-06-25 04:13:20');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mitra`
--

CREATE TABLE `mitra` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mitra`
--

INSERT INTO `mitra` (`id`, `name`, `email`, `phone`, `password`, `created_at`, `updated_at`) VALUES
(1, 'M Husni', 'husni@sikosub.com', '083395434567', '$2y$12$/eLJxb4jE3VamRbAIjkTOON37NG3eNcqmY3BCZVGAKJyWKARToZ02', '2026-06-18 06:31:06', '2026-06-18 06:31:06');

-- --------------------------------------------------------

--
-- Table structure for table `mitras`
--

CREATE TABLE `mitras` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_pemilik` varchar(255) DEFAULT NULL,
  `nama_bisnis` varchar(255) DEFAULT NULL,
  `no_rekening` varchar(50) DEFAULT NULL,
  `status_verifikasi` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mitras`
--

INSERT INTO `mitras` (`id`, `user_id`, `nama_pemilik`, `nama_bisnis`, `no_rekening`, `status_verifikasi`, `email`, `phone`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(4, NULL, 'Amrullah Arif', NULL, NULL, 'pending', 'arifkost@gmail.com', '085812904578', '$2y$12$SRw9HprfDiBEQ6wb/LYG8.tEYglTebMehjTqeyTPBpmz8i7y9Uwoa', NULL, '2026-06-24 14:59:01', '2026-06-24 15:22:07'),
(5, NULL, 'ARRAFLY AZIZ SAPUTRA', NULL, NULL, 'pending', 'arraflyaziz@gmail.com', '082121291219', '$2y$12$Uk0cQixwhI9db2CjD2crDutBqUXgizp4mrk7xQCO..kLQzOd7BOf6', NULL, '2026-06-25 01:24:16', '2026-06-25 01:24:16'),
(6, NULL, 'ARRAFLY AZIZ SAPUTRA', NULL, NULL, 'pending', 'arraflyaziz@gmail.com', '082121291219', '$2y$12$sAXQDmg/.C78h3.fkyAugOS.LC.tCyvfwnIePXMGOokQHgCOK3CeW', NULL, '2026-06-25 02:37:15', '2026-06-25 02:37:15');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `kost_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `komentar` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `kost_id`, `rating`, `komentar`, `created_at`, `updated_at`) VALUES
(1, 3, 4, 5, 'Kost nya bagus banget', '2026-06-24 01:20:47', '2026-06-24 01:20:47'),
(3, 3, 4, 3, 'Kost nya adem tapi rawan', '2026-06-24 01:23:51', '2026-06-24 01:23:51'),
(4, 3, 4, 1, 'kost buruk', '2026-06-24 21:21:28', '2026-06-24 21:21:28');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `super_admin`
--

CREATE TABLE `super_admin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `super_admin`
--

INSERT INTO `super_admin` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin Sikosub', 'superadmin@sikosub.com', NULL, NULL, '$2y$12$.CuEu4WjGSi6VuRqPT4Q7uw2FYxO5rP6OReHGgcXe0bj/38wit1dG', NULL, '2026-06-18 14:06:26', '2026-06-18 07:07:52');

-- --------------------------------------------------------

--
-- Table structure for table `super_admins`
--

CREATE TABLE `super_admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `name`, `email`, `phone`, `foto_profil`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Arif Amrullah', 'Arif@sikosub.com', '089532456127', NULL, NULL, '$2y$12$pfN3GQqBnFz1fKUe.XlOGe1dFMcmksiWePsbgT60jCAmzpdz7uA9K', NULL, '2026-06-18 06:45:02', '2026-06-18 06:45:02'),
(2, 'Hildan', 'hildafr@gmail.com', '08909001093032', NULL, NULL, '$2y$12$8EkYdaFGU89Wc6gZ2ULgMuefQB94a7I222OPBLZCOW20cT..9DNhO', NULL, '2026-06-19 00:22:58', '2026-06-19 00:22:58'),
(3, 'Ardi Ilahi Roby', 'ardy@gmail.com', '088598764568', '1782275231_6a3b5c9fefb04.png', NULL, '$2y$12$vXr/ADuQEa9/H4YXgcvCG.ZymQRiMvVzNGg.3.1FyRKE7fgfz./nW', NULL, '2026-06-23 03:37:01', '2026-06-23 21:27:11'),
(4, 'ARRAFLY AZIZ SAPUTRA', 'arraflyaziz@gmail.com', '082121291219', NULL, NULL, '$2y$12$Ok8j5/puaSAPfUU030FpueWrcqIpwFTWHWOn7ViAeR9t9K6cKtYS.', NULL, '2026-06-25 01:18:59', '2026-06-25 01:18:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `kost_id` (`kost_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `instansis`
--
ALTER TABLE `instansis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kosts`
--
ALTER TABLE `kosts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mitra_id` (`mitra_id`),
  ADD KEY `kosts_instansi_id_index` (`instansi_id`);

--
-- Indexes for table `landings`
--
ALTER TABLE `landings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mitra`
--
ALTER TABLE `mitra`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `mitras`
--
ALTER TABLE `mitras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `kost_id` (`kost_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `super_admin`
--
ALTER TABLE `super_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `super_admins`
--
ALTER TABLE `super_admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instansis`
--
ALTER TABLE `instansis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kosts`
--
ALTER TABLE `kosts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `landings`
--
ALTER TABLE `landings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mitra`
--
ALTER TABLE `mitra`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mitras`
--
ALTER TABLE `mitras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `super_admin`
--
ALTER TABLE `super_admin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `super_admins`
--
ALTER TABLE `super_admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookmarks_ibfk_2` FOREIGN KEY (`kost_id`) REFERENCES `kosts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kosts`
--
ALTER TABLE `kosts`
  ADD CONSTRAINT `kosts_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `mitras`
--
ALTER TABLE `mitras`
  ADD CONSTRAINT `mitras_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`kost_id`) REFERENCES `kosts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
