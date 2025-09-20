# PT Aino Logo Setup Instructions

## 📁 Direktori Logo
Logo PT Aino harus ditempatkan di: `public/images/`

## 🎨 Format Logo yang Didukung
1. **PNG**: `public/images/pt-aino-logo.png` (Recommended)
2. **SVG**: `public/images/pt-aino-logo.svg` (Vector format)

## 📐 Spesifikasi Logo
- **Ukuran Minimum**: 256x256 px
- **Format**: PNG dengan background transparan atau SVG
- **Aspect Ratio**: Square (1:1) atau sesuai logo asli
- **Kualitas**: High resolution untuk berbagai ukuran tampilan

## 🔄 Cara Mengganti Logo

### Langkah 1: Upload Logo
```bash
# Copy logo ke direktori yang benar
cp path/to/your/logo.png public/images/pt-aino-logo.png
# atau
cp path/to/your/logo.svg public/images/pt-aino-logo.svg
```

### Langkah 2: Verifikasi
Setelah upload, logo akan otomatis muncul di:
- ✅ Halaman Login
- ✅ Sidebar Admin
- ✅ Header User Dashboard
- ✅ Semua layout aplikasi

## 🎯 Lokasi Komponen Logo
Logo menggunakan komponen reusable: `resources/views/components/logo.blade.php`

### Varian Logo:
1. **Default** - untuk halaman login (background putih)
2. **Light** - untuk sidebar/header gelap (background terang)
3. **Dark** - untuk header terang (background gelap)

### Ukuran Logo:
- **sm** (32x32px) - untuk header compact
- **md** (40x40px) - untuk sidebar
- **lg** (64x64px) - untuk halaman login
- **xl** (80x80px) - untuk halaman khusus

## 🔧 Kustomisasi Tambahan
Jika perlu modifikasi komponen logo, edit file: `resources/views/components/logo.blade.php`

## 📝 Placeholder Saat Ini
Sistem saat ini menampilkan placeholder dengan huruf "A" sampai logo asli PT Aino di-upload.

---

**Setelah logo di-upload, clear cache jika diperlukan:**
```bash
php artisan view:clear
php artisan cache:clear
```