# Ekspedisi API (Hostinger)

File ini berisi petunjuk cepat deploy API PHP ke Hostinger.

1. Upload folder `api/` ke `public_html/api/` (atau subfolder api pada domain/subdomain).
2. Upload `db/schema.sql` ke hosting dan jalankan menggunakan phpMyAdmin untuk membuat tabel.
3. Edit `api/config.php` dan set `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` sesuai kredensial Hostinger.
4. Pastikan `.htaccess` ada di folder `api/` agar routing bekerja.
5. Contoh request (ambil semua surat jalan):

```bash
curl https://your-domain.com/api/surat-jalan
```

6. Contoh request menambahkan invoice:

```bash
curl -X POST https://your-domain.com/api/invoices \
  -H "Content-Type: application/json" \
  -d '{"nomor":"INV-001","tanggal":"2026-06-01","customer":"ACME","total":1500000}'
```

7. Di aplikasi React, ganti panggilan storage lokal menjadi fetch ke API, misalnya:

```js
const API_BASE = 'https://your-domain.com/api';
async function loadInvoices(){
  const res = await fetch(`${API_BASE}/invoices`);
  return await res.json();
}
```

Jika mau, saya bisa lanjut: saya akan buatkan patch `App.tsx` untuk mengganti `useStorage` dengan fetch ke API, atau saya bisa langsung men-deploy file ke hosting jika kamu berikan akses FTP (opsional).
