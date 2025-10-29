CREATE DATABASE selaras_hijab;
USE selaras_hijab;

CREATE TABLE produk (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Nama VARCHAR(100) NOT NULL,
    Harga DECIMAL(15,2) NOT NULL,
    Deskripsi_Produk TEXT,
    Foto_Produk VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
