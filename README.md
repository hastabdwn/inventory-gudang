# 📦 Inventory Gudang

Sistem manajemen inventory gudang berbasis web yang dibangun dengan **Laravel 12** + **Blade** + **Tailwind CSS**. Dibangun sebagai portfolio project yang mendemonstrasikan kemampuan full-stack development dengan Laravel.

---

## ✨ Fitur Utama

### 🏪 Master Data
- Manajemen gudang & multi-lokasi
- Kategori & satuan barang
- Data supplier
- Data barang dengan generate **Barcode** & **QR Code**
- Upload foto barang
- Auto-generate kode barang

### 📦 Manajemen Stok
- Posisi stok real-time per gudang
- Transfer stok antar gudang
- Penyesuaian stok (stock opname)
- Histori mutasi stok lengkap dengan audit trail

### 🛒 Pembelian
- Purchase Order (PO) dengan alur approval
- Status tracking: Draft → Menunggu Approval → Disetujui → Diterima Sebagian → Selesai
- Penerimaan barang bertahap (partial receipt)
- Stok otomatis bertambah saat barang diterima

### 🚚 Distribusi
- Pengeluaran barang ke divisi/tujuan
- Cetak surat jalan
- Stok otomatis berkurang saat diterbitkan
- Rollback stok jika distribusi dibatalkan

### 🔄 Retur
- Retur barang ke supplier
- Status tracking: Draft → Terkirim → Dikonfirmasi
- Stok otomatis berkurang saat retur dikirim
- Rollback stok jika retur dibatalkan

### 📊 Dashboard & Laporan
- Dashboard dengan summary cards & 3 jenis chart
- Alert stok rendah & habis dengan progress bar
- 5 jenis laporan: Stok, Mutasi, PO, Distribusi, Retur
- Ekspor laporan ke **Excel** (.xlsx)
- Ekspor laporan ke **PDF**
- Filter periode & gudang untuk semua laporan

### 👥 User Management
- Role-based access control (RBAC)
- 3 level akses: Superadmin, Admin Gudang, Viewer
- Manajemen user (tambah, edit, aktif/nonaktif)
- Halaman profil & ganti password
- Auto logout jika akun dinonaktifkan

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|---|---|
| Backend Framework | Laravel 12 |
| Language | PHP 8.2 |
| Frontend | Blade Template + Tailwind CSS |
| Database | MySQL 8 |
| Authentication | Laravel Breeze |
| Authorization | Spatie Laravel Permission |
| Excel Export | Maatwebsite Laravel Excel |
| PDF Export | Barryvdh Laravel DomPDF |
| Barcode Generator | Picqer PHP Barcode Generator |
| QR Code Generator | SimpleSoftwareIO Simple QrCode |
| Charts | Chart.js 4 |
| Build Tool | Vite |

---

## 🗄️ Struktur Database

17 tabel utama:

```
users                   → Data pengguna sistem
warehouses              → Data gudang & lokasi
categories              → Kategori barang
units                   → Satuan barang
suppliers               → Data supplier
items                   → Master data barang
item_stocks             → Stok barang per gudang
purchase_orders         → Header Purchase Order
po_items                → Detail item PO
goods_receipts          → Header penerimaan barang
goods_receipt_items     → Detail penerimaan barang
stock_movements         → Log semua mutasi stok (audit trail)
distributions           → Header distribusi/surat jalan
distribution_items      → Detail distribusi
stock_transfers         → Transfer stok antar gudang
stock_transfer_items    → Detail transfer stok
returns                 → Header retur ke supplier
return_items            → Detail retur
```

---

## 🚀 Instalasi

### Prasyarat
- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL 8

### Langkah Instalasi

**1. Clone repository**
```bash
git clone https://github.com/hastabdwn/inventory-gudang.git
cd inventory-gudang
```

**2. Install dependencies**
```bash
composer install
npm install
```

**3. Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Konfigurasi database di `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_gudang
DB_USERNAME=root
DB_PASSWORD=
```

**5. Buat database**
```sql
CREATE DATABASE inventory_gudang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**6. Jalankan migrasi & seeder**
```bash
php artisan migrate --seed
```

**7. Build assets & storage link**
```bash
npm run build
php artisan storage:link
```

**8. Jalankan aplikasi**
```bash
php artisan serve
```

Buka browser di `http://localhost:8000`

---

## 👤 Akun Demo

| Role | Email | Password |
|---|---|---|
| Superadmin | superadmin@inventory.test | password |
| Admin Gudang | admin@inventory.test | password |
| Viewer | viewer@inventory.test | password |

### Perbedaan Akses

| Fitur | Superadmin | Admin Gudang | Viewer |
|---|:---:|:---:|:---:|
| Master Data (CRUD) | ✅ | ✅ | 👁️ |
| Purchase Order | ✅ | Buat/Terima | 👁️ |
| Approval PO | ✅ | ❌ | ❌ |
| Distribusi | ✅ | ✅ | 👁️ |
| Transfer Stok | ✅ | ✅ | 👁️ |
| Retur | ✅ | ✅ | 👁️ |
| Penyesuaian Stok | ✅ | ✅ | ❌ |
| Laporan & Ekspor | ✅ | ✅ | ✅ |
| Manajemen User | ✅ | ❌ | ❌ |

---

## 📁 Struktur Folder

```
app/
├── Http/Controllers/
│   ├── Master/          → CRUD master data
│   ├── Stock/           → Manajemen stok
│   ├── Purchasing/      → PO & penerimaan
│   ├── Distribution/    → Distribusi & surat jalan
│   ├── ItemReturn/      → Retur supplier
│   ├── Report/          → Laporan & ekspor
│   └── UserManagement/  → User & profil
├── Models/              → 18 Eloquent models
├── Services/            → Business logic layer
│   ├── StockService.php
│   ├── PurchaseOrderService.php
│   ├── DistributionService.php
│   ├── ReturnService.php
│   ├── ReportService.php
│   └── DocumentNumberService.php
└── Exports/             → Excel export classes

resources/views/
├── layouts/             → Layout utama
├── auth/                → Halaman login
├── master/              → Views master data
│   ├── warehouses/
│   ├── categories/
│   ├── units/
│   ├── suppliers/
│   └── items/
├── stock/               → Views stok
│   ├── adjustment/
│   ├── movements/
│   └── transfer/
├── purchasing/          → Views PO & penerimaan
│   ├── orders/
│   └── receipts/
├── distribution/        → Views distribusi & surat jalan
├── returns/             → Views retur
├── reports/             → Views laporan & PDF
│   └── pdf/
├── users/               → Views user management
└── errors/              → Halaman 403, 404
```

---

## 🔑 Arsitektur & Design Pattern

### Service Layer
Business logic dipisahkan dari controller ke dedicated service class:

```
StockService           → stockIn(), stockOut(), transfer(), adjust()
PurchaseOrderService   → create(), approve(), receiveGoods()
DistributionService    → create(), issue(), cancel()
ReturnService          → create(), send(), confirm(), cancel()
ReportService          → stockReport(), movementReport(), dll
DocumentNumberService  → po(), receipt(), distribution(), dll
```

### Prinsip yang Diterapkan
- **Service Layer Pattern** — Business logic dipisah dari controller
- **Database Transaction** — Operasi stok dibungkus DB::transaction() untuk konsistensi data
- **Audit Trail** — Setiap perubahan stok dicatat di `stock_movements` dengan snapshot stock_before & stock_after
- **Soft Deletes** — Master data tidak benar-benar dihapus
- **RBAC** — Spatie Permission dengan granular permissions per fitur
- **Guard Hapus** — Tidak bisa hapus data yang masih berelasi

### Alur Stok
```
Barang Masuk  → PO → Penerimaan Barang → stock_movements (type: in)
Barang Keluar → Distribusi → stock_movements (type: out)
Transfer      → Stock Transfer → stock_movements (type: transfer_in & transfer_out)
Retur         → Return → stock_movements (type: retur)
Koreksi       → Adjustment → stock_movements (type: adjustment)
```

---

## 🔐 Permission List

```
view master-data        manage master-data
view purchase-order     create purchase-order     approve purchase-order    receive goods
view distribution       create distribution
view transfer           create transfer
view return             create return
view stock              adjust stock
view report             export report
manage users
```

---

## 📦 Package yang Digunakan

```json
{
    "laravel/framework": "^12.0",
    "laravel/breeze": "^2.0",
    "spatie/laravel-permission": "^6.0",
    "maatwebsite/excel": "^3.1",
    "barryvdh/laravel-dompdf": "^3.0",
    "picqer/php-barcode-generator": "^2.0",
    "simplesoftwareio/simple-qrcode": "^4.0"
}
```

---

## ⚙️ Environment Variables Penting

```env
APP_NAME="Inventory Gudang"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_gudang
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
```

---

## 🧪 Seeder Data

Setelah `php artisan migrate --seed`, data berikut akan tersedia:

**Roles & Permissions**
- 3 roles: superadmin, admin_gudang, viewer
- 17 granular permissions

**Users**
- superadmin@inventory.test (password: password)
- admin@inventory.test (password: password)
- viewer@inventory.test (password: password)

**Master Data**
- 3 gudang (Gudang Utama, Cadangan, Transit)
- 6 kategori (Elektronik, Alat Tulis, Peralatan Kebersihan, Spare Part, Bahan Baku, Kemasan)
- 7 satuan (pcs, kg, L, box, ktn, m, roll)
- 2 supplier contoh

---

## 📸 Screenshot

> Tambahkan screenshot di sini setelah project selesai

```
screenshots/
├── login.png
├── dashboard.png
├── master-barang.png
├── purchase-order.png
├── distribusi.png
├── laporan.png
└── user-management.png
```

---

## 🤝 Kontribusi

Project ini dibuat untuk portfolio. Jika ada bug atau saran, silakan buka issue.

---

## 📝 Lisensi

Project ini dibuat untuk keperluan **portfolio**. Bebas digunakan sebagai referensi belajar.

---

## 👨‍💻 Author

**Nama Kamu**
- GitHub: [@hastabdwn](https://github.com/hastabdwn)
- LinkedIn: [linkedin.com/in/hasta-budiawan/](https://www.linkedin.com/in/hasta-budiawan/)
- Email: hastabudiawan9@gmail.com

---

> ⭐ Jika project ini bermanfaat, jangan lupa kasih **star**!
