CREATE DATABASE jadihacker_db;
USE jadihacker_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'peserta') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE peserta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    no_telp VARCHAR(15) NOT NULL,
    alamat TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE pelatih (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelatih VARCHAR(100) NOT NULL,
    keahlian VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    no_telp VARCHAR(15) NOT NULL
);

CREATE TABLE program_pelatihan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_program VARCHAR(100) NOT NULL,
    deskripsi TEXT NOT NULL,
    durasi VARCHAR(50),
    pelatih_id INT NOT NULL,
    FOREIGN KEY (pelatih_id) REFERENCES pelatih(id)
);

CREATE TABLE peserta_program (
    id INT AUTO_INCREMENT PRIMARY KEY,
    peserta_id INT NOT NULL,
    program_id INT NOT NULL,
    tanggal_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (peserta_id) REFERENCES peserta(id),
    FOREIGN KEY (program_id) REFERENCES program_pelatihan(id)
);

CREATE TABLE berita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    konten TEXT NOT NULL,
    tanggal_post TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, role) 
VALUES ('andreiz', 'hanselandrei123', 'admin');

INSERT INTO users (username, password, role) 
VALUES ('peserta1', 'password123', 'peserta');

SELECT * FROM users;

DROP DATABASE jadihacker_db;