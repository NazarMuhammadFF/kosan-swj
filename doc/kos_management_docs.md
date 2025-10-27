# 📄 Product Requirements Document (PRD)
## 🏠 Project: Kos Management System

---

## 1. Product Overview
**Kos Management System** adalah platform berbasis web yang dikembangkan untuk membantu pengelolaan kos secara digital — mencakup administrasi internal, promosi publik, serta layanan untuk penghuni (tenant). 

Produk ini bertujuan menggantikan proses manual (pencatatan sewa, tagihan, dan promosi) dengan sistem otomatis yang terintegrasi.

### 🎯 Tujuan Utama
- Otomatisasi pembuatan dan pengiriman tagihan sewa bulanan.
- Menyediakan portal tenant untuk mengelola kontrak dan pembayaran.
- Memudahkan admin/owner dalam memantau okupansi dan keuangan.
- Menyediakan laman publik untuk promosi kos serta pemesanan online.

### 🎯 Sasaran Keberhasilan (Success Metrics)
| KPI | Target |
|-----|---------|
| Efisiensi waktu pembuatan tagihan | ↓ 80% dibanding manual |
| Kesalahan pencatatan pembayaran | < 2% |
| Tingkat keterisian kamar | > 90% |
| Kepuasan tenant | ≥ 4.5/5 |

---

## 2. Target Pengguna & Persona
### 👤 Owner
Mengelola properti, memantau laporan, menerima pembayaran.
- Butuh: laporan real-time, notifikasi keterlambatan, promosi properti.

### 👩‍💼 Admin / Manager
Menangani operasional, booking, kontrak, dan keuangan.
- Butuh: sistem cepat untuk membuat invoice dan reminder otomatis.

### 👨‍🔧 Staff (Maintenance / Frontdesk)
Menangani tiket perbaikan dan okupansi.
- Butuh: daftar tiket yang jelas dan update status mudah.

### 🧍‍♂️ Tenant (Penghuni)
Melihat kontrak, membayar tagihan, dan mengirim keluhan.
- Butuh: portal pribadi, invoice digital, dan reminder otomatis.

### 👤 Calon Penyewa (Visitor)
Mencari kos berdasarkan lokasi, harga, fasilitas.
- Butuh: tampilan menarik, filter cerdas, booking cepat.

---

## 3. Masalah yang Diselesaikan
| Masalah Saat Ini | Solusi dari Sistem |
|------------------|--------------------|
| Tagihan dibuat manual tiap bulan | Sistem auto-generate invoice + reminder otomatis |
| Data penyewa tercecer | Semua data tersimpan di dashboard & database terpusat |
| Promosi kos hanya via media sosial | Website publik dengan fitur pencarian & filter |
| Laporan keuangan lambat & rawan salah | Dashboard real-time untuk owner & admin |

---

## 4. Fitur Utama (Product Features)
### 🧩 1. Property Management
- CRUD properti & kamar.
- Upload foto/video tur.
- Fasilitas, harga, aturan, dan deposit.

### 💰 2. Billing Automation
- Generate invoice otomatis setiap bulan.
- Reminder via WhatsApp & Email.
- Pembayaran manual (transfer, e-wallet, VA bank).
- Dashboard status pembayaran.

### 🧾 3. Booking & Contract Management
- Booking kunjungan & langsung bayar DP 10%.
- Konfirmasi booking & upload kontrak.
- Status booking: pending → confirmed → completed.

### 👨‍💻 4. Tenant Portal
- Profil tenant & dokumen.
- Histori tagihan & pembayaran.
- Download invoice PDF.
- Pengumuman dari admin.

### 🧰 5. Maintenance Ticket System
- Tenant buat tiket keluhan (foto/video wajib).
- Staff update status: open → in progress → done.
- SLA default 48 jam.

### 🌐 6. Public Promotion Page
- Landing page dengan filter lokasi, harga, fasilitas, gender, radius.
- Blog area & artikel panduan.
- Review & rating tenant.
- Voucher promosi.

### 📊 7. Reporting Dashboard
- Laporan okupansi, pendapatan, aging piutang.
- Channel performance & conversion rate.

---

## 5. Prioritas Fitur (MVP → Next)
| Fase | Fokus | Fitur |
|------|--------|--------|
| **MVP (Phase 1)** | Automasi tagihan, portal tenant | Auth, CRUD properti, booking, invoice otomatis, reminder, dashboard dasar |
| **Phase 2** | Interaksi & promosi | Voucher, review, blog area, maintenance lengkap |
| **Phase 3** | Optimalisasi & skalabilitas | PWA, integrasi pembayaran otomatis, multi-owner dashboard |

---

## 6. User Flow (Simplified)
### Booking & Pembayaran
1. Calon penyewa mencari kos → filter lokasi & harga.
2. Klik booking → pilih “kunjungan” atau “langsung bayar DP 10%”.
3. Admin konfirmasi → sistem kirim invoice DP.
4. Setelah pembayaran, status booking jadi *confirmed*.
5. Kontrak dibuat → tenant bisa lihat di portal.

### Tagihan Bulanan
1. Sistem menjadwalkan invoice otomatis tiap bulan.
2. Reminder dikirim 3 hari sebelum jatuh tempo.
3. Tenant bayar via transfer/e-wallet → admin verifikasi.
4. Status invoice: *unpaid → paid → archived*.

### Maintenance
1. Tenant buat tiket (foto/video wajib).
2. Staff terima & update status pekerjaan.
3. Tenant dapat notifikasi jika selesai.

---

## 7. Kebutuhan Teknis Umum
| Komponen | Teknologi |
|-----------|------------|
| Backend | Laravel 11 (PHP 8.3) |
| Frontend | Blade + Livewire + Tailwind CSS |
| Database | PostgreSQL |
| Cache/Queue | Redis |
| Server | Dockerized stack (Nginx + PHP-FPM) |
| Storage | S3-compatible (Wasabi / MinIO) |
| Notifikasi | WhatsApp API + Mailgun SMTP |
| Peta | Mapbox API |

---

## 8. Non-Goals (Bukan Fokus MVP)
- Integrasi pembayaran otomatis (akan hadir di fase 3).
- Multi-tenant penuh dengan subdomain khusus owner.
- Aplikasi mobile native.

---

## 9. Risiko & Mitigasi
| Risiko | Dampak | Mitigasi |
|---------|---------|-----------|
| Down server karena konfigurasi | Tidak bisa akses dashboard | Gunakan Docker dan backup reguler |
| Pembayaran manual lambat diverifikasi | Tagihan tertunda | Tambah log & validasi double-check |
| Pengguna awam kesulitan navigasi | Penurunan adopsi | Desain UI minimalis & jelas |
| Spam booking | Data kotor | Validasi akun & limit booking per user |

---

## 10. Roadmap Singkat
### Q4 2025 – MVP Launch
- Sistem billing otomatis, portal tenant, admin dashboard

### Q1 2026 – Interaksi & Promosi
- Voucher, review, maintenance ticket penuh

### Q2 2026 – Optimalisasi
- Integrasi pembayaran otomatis, laporan lanjutan, PWA offline mode

---

**Dokumen ini berfungsi sebagai acuan kebutuhan produk** — bukan panduan teknis rinci, melainkan landasan agar pengembangan tetap fokus pada kebutuhan nyata pengguna dan tujuan bisnis.

