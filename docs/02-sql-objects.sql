-- ============================================================
-- 02 — SQL OBJECTS (Procedures, Functions, Triggers)
-- ============================================================
-- STATUS: FINAL. JANGAN DITULIS ULANG ATAU DIGANTI LOGIKANYA.
--
-- Agent: jalankan file ini LANGSUNG ke database MySQL (misal lewat
-- `mysql -u root -p nama_database < docs/02-sql-objects.sql` atau
-- tool database manapun yang tersedia). JANGAN menyalin ulang isi
-- file ini ke migration Laravel dengan gaya berbeda, JANGAN
-- menerjemahkan logikanya ke PHP/Eloquent, dan JANGAN "memperbaiki"
-- bagian mana pun meski terlihat bisa dioptimasi.
--
-- Urutan eksekusi WAJIB seperti urutan file ini: functions dulu,
-- baru procedures, baru triggers — karena trigger dan procedure di
-- bawah memanggil function yang didefinisikan lebih dulu.
--
-- Prasyarat sebelum menjalankan file ini: semua tabel di
-- docs/01-schema.md sudah dibuat lewat migration Laravel dan
-- migration sudah dijalankan (`php artisan migrate`).
-- ============================================================


-- ============================================================
-- BAGIAN 1: FUNCTIONS
-- ============================================================

-- Hitung rata-rata nilai siswa untuk satu mapel/semester
DELIMITER $$
CREATE FUNCTION fn_rata_rata_nilai(p_siswa_id INT, p_mengajar_id INT)
RETURNS DECIMAL(5,2)
DETERMINISTIC
BEGIN
    DECLARE v_rata DECIMAL(5,2);
    SELECT AVG(nilai) INTO v_rata
    FROM nilai
    WHERE siswa_id = p_siswa_id AND mengajar_id = p_mengajar_id;
    RETURN IFNULL(v_rata, 0);
END$$
DELIMITER ;

-- Hitung persentase kehadiran (bisa dipakai langsung tanpa nunggu tabel rekap)
DELIMITER $$
CREATE FUNCTION fn_persentase_hadir(p_siswa_id INT, p_mengajar_id INT)
RETURNS DECIMAL(5,2)
DETERMINISTIC
BEGIN
    DECLARE v_total INT;
    DECLARE v_hadir INT;
    SELECT COUNT(*), SUM(status = 'hadir') INTO v_total, v_hadir
    FROM absensi
    WHERE siswa_id = p_siswa_id AND mengajar_id = p_mengajar_id;
    IF v_total = 0 THEN RETURN 0; END IF;
    RETURN (v_hadir / v_total) * 100;
END$$
DELIMITER ;


-- ============================================================
-- BAGIAN 2: STORED PROCEDURES
-- ============================================================

-- Input/update nilai satu siswa (dipanggil berkali-kali dari
-- controller, satu kali per siswa, saat guru submit form input
-- nilai satu kelas). Validasi rentang nilai (0-100) dan cross-check
-- siswa-kelas TIDAK dilakukan di sini — itu wajib dilakukan di
-- controller SEBELUM memanggil procedure ini (lihat docs/01-schema.md
-- bagian "Validasi yang Tidak Dijamin Skema").
DELIMITER $$
CREATE PROCEDURE sp_input_nilai_kelas(
    IN p_mengajar_id INT,
    IN p_jenis VARCHAR(10),
    IN p_siswa_id INT,
    IN p_nilai DECIMAL(5,2),
    IN p_user_id INT
)
BEGIN
    INSERT INTO nilai (siswa_id, mengajar_id, jenis, nilai, tanggal_input, diinput_oleh)
    VALUES (p_siswa_id, p_mengajar_id, p_jenis, p_nilai, NOW(), p_user_id)
    ON DUPLICATE KEY UPDATE
        nilai = p_nilai,
        tanggal_input = NOW(),
        diinput_oleh = p_user_id;
END$$
DELIMITER ;

-- Hitung ulang rekap absensi satu siswa untuk satu mengajar+semester.
-- JANGAN dipanggil manual dari controller — ini dipanggil OTOMATIS
-- oleh trg_absensi_insert di bawah, setiap kali ada baris baru masuk
-- ke tabel absensi.
DELIMITER $$
CREATE PROCEDURE sp_rekap_absensi(
    IN p_siswa_id INT,
    IN p_mengajar_id INT,
    IN p_semester VARCHAR(10)
)
BEGIN
    DECLARE v_hadir INT DEFAULT 0;
    DECLARE v_izin INT DEFAULT 0;
    DECLARE v_sakit INT DEFAULT 0;
    DECLARE v_alpa INT DEFAULT 0;
    DECLARE v_total INT DEFAULT 0;
    DECLARE v_persentase DECIMAL(5,2) DEFAULT 0;

    SELECT
        SUM(status = 'hadir'), SUM(status = 'izin'),
        SUM(status = 'sakit'), SUM(status = 'alpa'), COUNT(*)
    INTO v_hadir, v_izin, v_sakit, v_alpa, v_total
    FROM absensi
    WHERE siswa_id = p_siswa_id AND mengajar_id = p_mengajar_id;

    IF v_total > 0 THEN
        SET v_persentase = (v_hadir / v_total) * 100;
    END IF;

    INSERT INTO rekap_absensi (siswa_id, mengajar_id, semester, total_hadir, total_izin, total_sakit, total_alpa, persentase_hadir, updated_at)
    VALUES (p_siswa_id, p_mengajar_id, p_semester, v_hadir, v_izin, v_sakit, v_alpa, v_persentase, NOW())
    ON DUPLICATE KEY UPDATE
        total_hadir = v_hadir, total_izin = v_izin, total_sakit = v_sakit, total_alpa = v_alpa,
        persentase_hadir = v_persentase, updated_at = NOW();
END$$
DELIMITER ;


-- ============================================================
-- BAGIAN 3: TRIGGERS
-- ============================================================
--
-- Catatan penting: rekap harus tetap sinkron baik saat nilai BARU
-- diinput (INSERT) maupun saat nilai yang sudah ada DIEDIT (UPDATE,
-- terjadi lewat ON DUPLICATE KEY UPDATE di sp_input_nilai_kelas).
-- MySQL tidak mendukung satu trigger untuk dua event sekaligus,
-- makanya ada DUA trigger terpisah dengan isi mirip untuk nilai.
-- JANGAN digabung jadi satu trigger — itu tidak akan berfungsi.

-- Update rekap_nilai otomatis saat nilai BARU diinput
DELIMITER $$
CREATE TRIGGER trg_rekap_nilai_insert
AFTER INSERT ON nilai
FOR EACH ROW
BEGIN
    DECLARE v_semester VARCHAR(10);
    SELECT semester INTO v_semester FROM mengajar WHERE id = NEW.mengajar_id;

    INSERT INTO rekap_nilai (siswa_id, mengajar_id, semester, rata_rata, updated_at)
    VALUES (NEW.siswa_id, NEW.mengajar_id, v_semester,
            fn_rata_rata_nilai(NEW.siswa_id, NEW.mengajar_id), NOW())
    ON DUPLICATE KEY UPDATE
        rata_rata = fn_rata_rata_nilai(NEW.siswa_id, NEW.mengajar_id),
        updated_at = NOW();
END$$
DELIMITER ;

-- Update rekap_nilai otomatis saat nilai yang SUDAH ADA diedit.
-- INI YANG PALING SERING TERLEWAT — jangan pernah hapus trigger ini
-- meski trg_rekap_nilai_insert di atas sudah ada. Tanpa trigger ini,
-- rekap_nilai tidak akan berubah saat guru mengedit nilai yang sudah
-- diinput sebelumnya, dan ini TIDAK akan terlihat sebagai error —
-- rekap akan diam-diam salah/basi.
DELIMITER $$
CREATE TRIGGER trg_rekap_nilai_update
AFTER UPDATE ON nilai
FOR EACH ROW
BEGIN
    DECLARE v_semester VARCHAR(10);
    SELECT semester INTO v_semester FROM mengajar WHERE id = NEW.mengajar_id;

    INSERT INTO rekap_nilai (siswa_id, mengajar_id, semester, rata_rata, updated_at)
    VALUES (NEW.siswa_id, NEW.mengajar_id, v_semester,
            fn_rata_rata_nilai(NEW.siswa_id, NEW.mengajar_id), NOW())
    ON DUPLICATE KEY UPDATE
        rata_rata = fn_rata_rata_nilai(NEW.siswa_id, NEW.mengajar_id),
        updated_at = NOW();
END$$
DELIMITER ;

-- Log setiap perubahan nilai (audit trail sederhana)
DELIMITER $$
CREATE TRIGGER trg_log_nilai_update
AFTER UPDATE ON nilai
FOR EACH ROW
BEGIN
    INSERT INTO log_perubahan (tabel, record_id, aksi, user_id, waktu, data_lama, data_baru)
    VALUES ('nilai', NEW.id, 'update', NEW.diinput_oleh, NOW(),
            JSON_OBJECT('nilai', OLD.nilai), JSON_OBJECT('nilai', NEW.nilai));
END$$
DELIMITER ;

-- Pemicu rekap_absensi otomatis tiap kali absensi baru diinput.
-- Semester diambil DINAMIS dari mengajar.semester — JANGAN
-- di-hardcode ke string tetap seperti 'ganjil'. Ini pola yang sama
-- dengan trigger nilai di atas, untuk mencegah rekap salah catat
-- semester kalau input terjadi di semester genap.
DELIMITER $$
CREATE TRIGGER trg_absensi_insert
AFTER INSERT ON absensi
FOR EACH ROW
BEGIN
    DECLARE v_semester VARCHAR(10);
    SELECT semester INTO v_semester FROM mengajar WHERE id = NEW.mengajar_id;

    CALL sp_rekap_absensi(NEW.siswa_id, NEW.mengajar_id, v_semester);
END$$
DELIMITER ;


-- ============================================================
-- VERIFIKASI SETELAH EKSEKUSI
-- ============================================================
-- Setelah menjalankan file ini, verifikasi dengan query berikut
-- (jalankan manual, bukan lewat migration):
--
-- SHOW FUNCTION STATUS WHERE Db = DATABASE();
--   -> harus muncul: fn_rata_rata_nilai, fn_persentase_hadir
--
-- SHOW PROCEDURE STATUS WHERE Db = DATABASE();
--   -> harus muncul: sp_input_nilai_kelas, sp_rekap_absensi
--
-- SHOW TRIGGERS;
--   -> harus muncul: trg_rekap_nilai_insert, trg_rekap_nilai_update,
--      trg_log_nilai_update, trg_absensi_insert
--
-- Kalau ada yang tidak muncul, JANGAN lanjut ke tahap berikutnya di
-- docs/06-build-sequence.md — cari tahu kenapa gagal (biasanya
-- karena tabel yang di-reference belum ada, urutan CREATE salah,
-- atau DELIMITER tidak ter-reset dengan benar).
