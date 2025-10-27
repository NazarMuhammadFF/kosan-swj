# Git Workflow - Kosan Management System

## 🌿 Struktur Branch

```
main (production-ready)
  └── dev (development)
       ├── feature/nama-fitur-1
       ├── feature/nama-fitur-2
       └── feature/nama-fitur-3
```

## 📋 Workflow Standard

### 1️⃣ Memulai Fitur Baru

```bash
# Pastikan berada di branch dev dan update
git checkout dev
git pull origin dev

# Buat branch baru untuk fitur
git checkout -b feature/nama-fitur

# Contoh:
# git checkout -b feature/room-crud
# git checkout -b feature/tenant-management
# git checkout -b feature/invoice-system
```

### 2️⃣ Bekerja pada Fitur

```bash
# Lakukan perubahan pada kode
# ...

# Add dan commit perubahan
git add .
git commit -m "feat: deskripsi fitur yang dikerjakan"

# Push ke branch feature
git push -u origin feature/nama-fitur
```

### 3️⃣ Menyelesaikan Fitur (Merge ke Dev)

```bash
# Pastikan semua perubahan sudah di-commit
git add .
git commit -m "feat: selesai implementasi nama-fitur"

# Push ke remote
git push origin feature/nama-fitur

# Pindah ke branch dev
git checkout dev

# Merge feature ke dev
git merge feature/nama-fitur

# Push dev ke remote
git push origin dev

# Hapus branch feature (opsional)
git branch -d feature/nama-fitur
git push origin --delete feature/nama-fitur
```

### 4️⃣ Release ke Production (Dev → Main)

```bash
# Ketika dev sudah stabil dan siap production
git checkout main
git pull origin main

# Merge dev ke main
git merge dev

# Push ke main
git push origin main

# Kembali ke dev untuk development selanjutnya
git checkout dev
```

## 🎯 Konvensi Penamaan Branch

- **Feature**: `feature/nama-fitur`
  - Contoh: `feature/room-management`, `feature/payment-integration`
  
- **Bugfix**: `bugfix/nama-bug`
  - Contoh: `bugfix/login-error`, `bugfix/invoice-calculation`
  
- **Hotfix**: `hotfix/nama-issue`
  - Contoh: `hotfix/security-patch`

## 📝 Konvensi Commit Message

```
feat: menambahkan fitur baru
fix: memperbaiki bug
docs: update dokumentasi
style: perubahan formatting, semicolon, dll
refactor: refactoring kode
test: menambahkan test
chore: update dependencies, konfigurasi, dll
```

### Contoh:
```bash
git commit -m "feat: implementasi CRUD kamar kos"
git commit -m "fix: perbaiki validasi form tenant"
git commit -m "docs: update README dengan cara instalasi"
git commit -m "refactor: ubah struktur controller invoice"
```

## 🔄 Siklus Development

1. **Mulai fitur baru** → Buat branch `feature/nama-fitur` dari `dev`
2. **Kerjakan fitur** → Commit secara berkala
3. **Fitur selesai** → Merge `feature/nama-fitur` → `dev`
4. **Testing di dev** → Pastikan semua berjalan baik
5. **Ulangi 1-4** untuk fitur berikutnya
6. **Siap production** → Merge `dev` → `main`

## 🚀 Quick Commands

### Cek status dan branch saat ini
```bash
git status
git branch
```

### Update branch saat ini
```bash
git pull origin nama-branch
```

### Lihat history commit
```bash
git log --oneline --graph --all
```

### Batalkan perubahan yang belum di-commit
```bash
git checkout -- nama-file
# atau
git restore nama-file
```

### Lihat perbedaan
```bash
git diff
```

## 📌 Current Status

- **Repository**: https://github.com/NazarMuhammadFF/kosan-swj.git
- **Branch Main**: `main` (production)
- **Branch Dev**: `dev` (development)
- **Current Branch**: `dev`

## ⚠️ Catatan Penting

1. **JANGAN** push langsung ke `main`
2. **SELALU** buat branch feature untuk setiap fitur baru
3. **SELALU** merge feature ke `dev` terlebih dahulu
4. **SELALU** test di `dev` sebelum merge ke `main`
5. **SELALU** pull sebelum mulai mengerjakan sesuatu

---

**Update terakhir**: 27 Oktober 2025
