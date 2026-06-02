-- SQL schema for Ekspedisi app
-- Run this on your Hostinger MySQL database (e.g. via phpMyAdmin)

CREATE TABLE IF NOT EXISTS surat_jalan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  noSJ VARCHAR(100) NOT NULL,
  tanggal DATE NOT NULL,
  penerima VARCHAR(255),
  pemasukanKantor DECIMAL(14,2) DEFAULT 0,
  biayaSopir DECIMAL(14,2) DEFAULT 0,
  statusPemasukan VARCHAR(50) DEFAULT 'Belum Lunas',
  statusSopir VARCHAR(50) DEFAULT 'Belum Dibayar',
  sopir VARCHAR(200),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS invoices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nomor VARCHAR(100) NOT NULL,
  tanggal DATE NOT NULL,
  customer VARCHAR(255),
  total DECIMAL(14,2) DEFAULT 0,
  status VARCHAR(50) DEFAULT 'Belum Lunas',
  data JSON DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS barang_masuk (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(255) NOT NULL,
  qty INT DEFAULT 0,
  harga DECIMAL(14,2) DEFAULT 0,
  tanggal DATE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS rekening (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(255),
  nomor VARCHAR(100),
  bank VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Optional: seed minimal data
INSERT INTO rekening (nama, nomor, bank) VALUES ('Kas Utama', '000-111-222', 'BCA')
ON DUPLICATE KEY UPDATE nama=nama;
