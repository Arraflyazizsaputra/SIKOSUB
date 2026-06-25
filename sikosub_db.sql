-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Jun 2026 pada 10.52
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

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
-- Struktur dari tabel `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `kost_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `instansis`
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
-- Struktur dari tabel `jobs`
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
-- Struktur dari tabel `job_batches`
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
-- Struktur dari tabel `kosts`
--

CREATE TABLE `kosts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mitra_id` bigint(20) UNSIGNED DEFAULT NULL,
  `instansi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_kost` varchar(255) NOT NULL,
  `tipe_kost` enum('putra','putri','campur','unsub','pemda') NOT NULL DEFAULT 'campur',
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
-- Dumping data untuk tabel `kosts`
--

INSERT INTO `kosts` (`id`, `mitra_id`, `instansi_id`, `nama_kost`, `tipe_kost`, `harga_per_bulan`, `harga_diskon`, `jumlah_kamar`, `sisa_kamar`, `fasilitas`, `alamat`, `alamat_lengkap`, `latitude`, `longitude`, `jarak_km`, `rating`, `gambar_utama`, `foto_kost`, `gallery_images`, `no_wa`, `maps`, `kategori_wilayah`, `detail_wilayah`, `created_at`, `updated_at`, `disewakan_oleh`, `info_kamar`, `fasilitas_umum`, `spesifikasi_kamar`, `fasilitas_kamar`, `fasilitas_km`, `pemilik`, `fasilitas_parkir`, `peraturan`, `ketentuan`, `tempat_terdekat`, `review_admin`, `kontak_pemilik`) VALUES
(3, NULL, NULL, 'Keiysa Kost', 'campur', 500000, 0, 1, 1, NULL, 'jln rame', NULL, NULL, NULL, 500.00, 5.0, '1781943933.jpg', NULL, NULL, '085214789653', 'https://maps.app.goo.gl/DVTWnzgRsBUjdWZ3A', 'unsub', 'depan kampus', '2026-06-20 01:25:33', '2026-06-20 01:35:58', NULL, 'Tersedia', 'Dapur, Jemuran, WiFi', '5x5', 'Lemari, Kasur, Pintu', NULL, 'Yadi Mulyadi', 'Parkir Motor Luas', 'bertamu bebas nginap', '5', 'djhon', 'Sangat Direkomendasikan', '083827914570');

-- --------------------------------------------------------

--
-- Struktur dari tabel `landings`
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
  `filter_pendidikan` varchar(255) DEFAULT NULL,
  `image_pendidikan` varchar(255) DEFAULT NULL,
  `filter_pemerintah` varchar(255) DEFAULT NULL,
  `image_pemerintah` varchar(255) DEFAULT NULL,
  `filter_perusahaan` varchar(255) DEFAULT NULL,
  `image_perusahaan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `landings`
--

INSERT INTO `landings` (`id`, `title`, `subtitle`, `banner_image`, `content`, `tentang`, `visi`, `misi`, `filter_pendidikan`, `image_pendidikan`, `filter_pemerintah`, `image_pemerintah`, `filter_perusahaan`, `image_perusahaan`, `created_at`, `updated_at`) VALUES
(1, 'SIKOSUB', NULL, '[\"1781857675_banner_0.png\",\"1781857685_banner_1.jpg\",\"1781857696_banner_2.jpg\",\"1781857709_banner_3.png\"]', NULL, 'Kota Subang terus mengalami perkembangan yang pesat, baik sebagai pusat pendidikan...', NULL, '1. Menyediakan fitur pencarian kos...\r\n\r\n2. Menyediakan platform digital...\r\n\r\n3. Memfasilitasi proses penyewaan...', 'STIESA, Unsub, Polsub, UM, Unair', 'pendidikan_1781934430_6a36295ed4074.jpg,pendidikan_1781934430_6a36295ed50c2.jpeg,pendidikan_1781934430_6a36295ed5dd1.jpg,pendidikan_1781934430_6a36295ed6aa0.jpg,pendidikan_1781934430_6a36295ed78b6.jpg', NULL, '1781930012_6a36181cdd202.webp,1781930012_6a36181cde095.jpg,1781930012_6a36181cded18.jpg', 'TAEKWANG, GDA, BRI', '1781929860_6a3617847aad9.jpg,1781929860_6a3617847ba35.jpg,1781929860_6a3617847c744.jpg', '2026-06-19 01:27:55', '2026-06-19 22:47:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mitra`
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
-- Dumping data untuk tabel `mitra`
--

INSERT INTO `mitra` (`id`, `name`, `email`, `phone`, `password`, `created_at`, `updated_at`) VALUES
(1, 'M Husni', 'husni@sikosub.com', '083395434567', '$2y$12$/eLJxb4jE3VamRbAIjkTOON37NG3eNcqmY3BCZVGAKJyWKARToZ02', '2026-06-18 06:31:06', '2026-06-18 06:31:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mitras`
--

CREATE TABLE `mitras` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_pemilik` varchar(255) DEFAULT NULL,
  `nama_bisnis` varchar(255) DEFAULT NULL,
  `no_rekening` varchar(50) DEFAULT NULL,
  `status_verifikasi` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `mitras`
--

INSERT INTO `mitras` (`id`, `user_id`, `nama_pemilik`, `nama_bisnis`, `no_rekening`, `status_verifikasi`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Uned', NULL, NULL, 'pending', 'uned@gmail.com', '$2y$12$KflxxNZCgilpU.vt2snvP.Es2GG6hvGmZt34oMJoHxxDAV1yf9Rl2', NULL, '2026-06-19 00:40:27', '2026-06-19 00:40:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `reviews`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
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
-- Struktur dari tabel `super_admin`
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
-- Dumping data untuk tabel `super_admin`
--

INSERT INTO `super_admin` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin Sikosub', 'superadmin@sikosub.com', NULL, NULL, '$2y$12$.CuEu4WjGSi6VuRqPT4Q7uw2FYxO5rP6OReHGgcXe0bj/38wit1dG', NULL, '2026-06-18 14:06:26', '2026-06-18 07:07:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `super_admins`
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
-- Struktur dari tabel `tbl_user`
--

CREATE TABLE `tbl_user` (
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
-- Dumping data untuk tabel `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Arif Amrullah', 'Arif@sikosub.com', '089532456127', NULL, '$2y$12$pfN3GQqBnFz1fKUe.XlOGe1dFMcmksiWePsbgT60jCAmzpdz7uA9K', NULL, '2026-06-18 06:45:02', '2026-06-18 06:45:02'),
(2, 'Hildan', 'hildafr@gmail.com', '08909001093032', NULL, '$2y$12$8EkYdaFGU89Wc6gZ2ULgMuefQB94a7I222OPBLZCOW20cT..9DNhO', NULL, '2026-06-19 00:22:58', '2026-06-19 00:22:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
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
-- Indeks untuk tabel `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `kost_id` (`kost_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indeks untuk tabel `instansis`
--
ALTER TABLE `instansis`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kosts`
--
ALTER TABLE `kosts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mitra_id` (`mitra_id`),
  ADD KEY `kosts_instansi_id_index` (`instansi_id`);

--
-- Indeks untuk tabel `landings`
--
ALTER TABLE `landings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mitra`
--
ALTER TABLE `mitra`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `mitras`
--
ALTER TABLE `mitras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `kost_id` (`kost_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `super_admin`
--
ALTER TABLE `super_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `super_admins`
--
ALTER TABLE `super_admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `instansis`
--
ALTER TABLE `instansis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kosts`
--
ALTER TABLE `kosts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `landings`
--
ALTER TABLE `landings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `mitra`
--
ALTER TABLE `mitra`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `mitras`
--
ALTER TABLE `mitras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `super_admin`
--
ALTER TABLE `super_admin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `super_admins`
--
ALTER TABLE `super_admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookmarks_ibfk_2` FOREIGN KEY (`kost_id`) REFERENCES `kosts` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kosts`
--
ALTER TABLE `kosts`
  ADD CONSTRAINT `kosts_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `mitras`
--
ALTER TABLE `mitras`
  ADD CONSTRAINT `mitras_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`kost_id`) REFERENCES `kosts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
