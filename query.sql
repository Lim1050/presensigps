    SELECT
    karyawan.nik,
    karyawan.nama_lengkap,
    karyawan.jabatan,
    presensi.tgl_1,
    presensi.tgl_2,
    presensi.tgl_3,
    presensi.tgl_4,
    presensi.tgl_5,
    presensi.tgl_6,
    presensi.tgl_7,
    presensi.tgl_8,
    presensi.tgl_9,
    presensi.tgl_10,
    presensi.tgl_11,
    presensi.tgl_12,
    presensi.tgl_13,
    presensi.tgl_14,
    presensi.tgl_15,
    presensi.tgl_16,
    presensi.tgl_17,
    presensi.tgl_18,
    presensi.tgl_19,
    presensi.tgl_20,
    presensi.tgl_21,
    presensi.tgl_22,
    presensi.tgl_23,
    presensi.tgl_24,
    presensi.tgl_25,
    presensi.tgl_26,
    presensi.tgl_27,
    presensi.tgl_28,
    presensi.tgl_29,
    presensi.tgl_30,
    presensi.tgl_31
FROM
    karyawan
LEFT JOIN (
    SELECT
        presensi.nik,
        MAX(IF(tanggal_presensi = '2024-07-01', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_1,
        MAX(IF(tanggal_presensi = '2024-07-02', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_2,
        MAX(IF(tanggal_presensi = '2024-07-03', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_3,
        MAX(IF(tanggal_presensi = '2024-07-04', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_4,
        MAX(IF(tanggal_presensi = '2024-07-05', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_5,
        MAX(IF(tanggal_presensi = '2024-07-06', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_6,
        -- Teruskan untuk setiap tanggal hingga tanggal 31
        MAX(IF(tanggal_presensi = '2024-07-07', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_7,
        MAX(IF(tanggal_presensi = '2024-07-08', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_8,
        MAX(IF(tanggal_presensi = '2024-07-09', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_9,
        MAX(IF(tanggal_presensi = '2024-07-10', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_10,
        MAX(IF(tanggal_presensi = '2024-07-11', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_11,
        MAX(IF(tanggal_presensi = '2024-07-12', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_12,
        MAX(IF(tanggal_presensi = '2024-07-13', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_13,
        MAX(IF(tanggal_presensi = '2024-07-14', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_14,
        MAX(IF(tanggal_presensi = '2024-07-15', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_15,
        MAX(IF(tanggal_presensi = '2024-07-16', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_16,
        MAX(IF(tanggal_presensi = '2024-07-17', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_17,
        MAX(IF(tanggal_presensi = '2024-07-18', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_18,
        MAX(IF(tanggal_presensi = '2024-07-19', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_19,
        MAX(IF(tanggal_presensi = '2024-07-20', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_20,
        MAX(IF(tanggal_presensi = '2024-07-21', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_21,
        MAX(IF(tanggal_presensi = '2024-07-22', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_22,
        MAX(IF(tanggal_presensi = '2024-07-23', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_23,
        MAX(IF(tanggal_presensi = '2024-07-24', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_24,
        MAX(IF(tanggal_presensi = '2024-07-25', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_25,
        MAX(IF(tanggal_presensi = '2024-07-26', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_26,
        MAX(IF(tanggal_presensi = '2024-07-27', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_27,
        MAX(IF(tanggal_presensi = '2024-07-28', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_28,
        MAX(IF(tanggal_presensi = '2024-07-29', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_29,
        MAX(IF(tanggal_presensi = '2024-07-30', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_30,
        MAX(IF(tanggal_presensi = '2024-07-31', CONCAT(
            IFNULL(presensi.jam_masuk, 'NA'), '|',
            IFNULL(presensi.jam_keluar, 'NA'), '|',
            IFNULL(presensi.status, 'NA') , '|',
            IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
            IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
            IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
            IFNULL(presensi.kode_izin, 'NA') , '|',
            IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
        ), NULL)) AS tgl_31
    FROM
        presensi
    LEFT JOIN
        jam_kerja ON presensi.kode_jam_kerja = jam_kerja.kode_jam_kerja
    LEFT JOIN
        pengajuan_izin ON presensi.kode_izin = pengajuan_izin.kode_izin
    GROUP BY
        presensi.nik
) presensi
ON karyawan.nik = presensi.nik;
