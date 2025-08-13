<?php
session_start();

//fungsi - fungsi
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/adm.php");
require '../../vendor/autoload.php';

// BARU: Gunakan namespace Dompdf
use Dompdf\Dompdf;
use Dompdf\Options;

nocache();

//nilai (Bagian ini tetap sama)
$filenya = "bayar_history_prt.php";
$swkd = nosql($_REQUEST['swkd']);
$nis = nosql($_REQUEST['nis']);

//judul (Bagian ini tetap sama)
$judul = "History Tabungan Siswa";
$judulku = "[TABUNGAN]. $judul";
$judulx = $judul;

//siswa-nya (Bagian ini tetap sama)
$qcc = mysqli_query($koneksi, "SELECT * FROM m_pelanggan WHERE kode = '$nis'");
$rcc = mysqli_fetch_assoc($qcc);
$tcc = mysqli_num_rows($qcc);
$cc_nama = balikin($rcc['nama']);


ob_start();

//data tabungan (Bagian ini tetap sama)
$qdata = mysqli_query($koneksi, "SELECT * FROM pelanggan_tabungan ".
                                     "WHERE pelanggan_kd = '$swkd' ".
                                     "ORDER BY postdate DESC");
$rdata = mysqli_fetch_assoc($qdata);
$tdata = mysqli_num_rows($qdata);

?>

<style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    .header-table, .signature-table { width: 100%; border-collapse: collapse; border: 0; }
    .data-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .data-table th, .data-table td { border: 1px solid #333; padding: 8px; }
    .data-table th { background-color: <?php echo $warnaheader; ?>; color: <?php echo $warnatext; ?>; text-align: center; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .strong { font-weight: bold; }
</style>

<div class="text-center">
    <p><span style="font-size:1.2em;" class="strong">HISTORY TABUNGAN ANGGOTA</span></p>
    <p><span style="font-size:1.1em;" class="strong"><?php echo $nis; ?>. <?php echo $cc_nama; ?></span></p>
</div>

<?php if ($tdata != 0): ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Debet</th>
                <th>Kredit</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            <?php
            do {
                //nilai
                $d_debet = nosql($rdata['debet']);
                $d_nilai = nosql($rdata['nilai']);
                $d_saldo = nosql($rdata['saldo']);
                $d_postdate = $rdata['postdate'];
            ?>
                <tr>
                    <td><?php echo $d_postdate; ?></td>
                    <?php if ($d_debet == "true"): ?>
                        <td class="text-right"><?php echo xduit2($d_nilai); ?></td>
                        <td class="text-center">-</td>
                    <?php else: ?>
                        <td class="text-center">-</td>
                        <td class="text-right"><?php echo xduit2($d_nilai); ?></td>
                    <?php endif; ?>
                    <td class="text-right"><?php echo xduit2($d_saldo); ?></td>
                </tr>
            <?php
            } while ($rdata = mysqli_fetch_assoc($qdata));
            ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="text-center">Tidak ada data transaksi untuk ditampilkan.</p>
<?php endif; ?>

<br><br><br>

<table class="signature-table">
    <tr>
        <td width="65%"></td>
        <td class="text-center">
            <p><?php echo $sek_kota; ?>, <?php echo $tanggal; ?> <?php echo $arrbln1[$bulan]; ?> <?php echo $tahun; ?></p>
            <p class="strong">Pengurus Koperasi</p>
            <br><br><br><br>
            <p class="strong">(.........................)</p>
        </td>
    </tr>
</table>

<?php
// Ambil konten HTML yang sudah dibuat
$isi = ob_get_contents();
ob_end_clean();

// BARU: Logika Dompdf dimulai di sini
// -----------------------------------------------------------------------------
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

// Inisialisasi Dompdf
$dompdf = new Dompdf($options);

// Muat konten HTML ke Dompdf
$dompdf->loadHtml($isi);

// Atur ukuran kertas dan orientasi. 'A4' lebih cocok untuk laporan histori.
$dompdf->setPaper('A4', 'portrait');

// Render HTML menjadi PDF
$dompdf->render();

// Buat nama file yang dinamis
$pdf_filename = "History-Tabungan-".$cc_nama."-".date("Ymd").".pdf";

// Kirimkan PDF ke browser untuk diunduh secara otomatis
$dompdf->stream($pdf_filename, ["Attachment" => true]);
// -----------------------------------------------------------------------------

//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>