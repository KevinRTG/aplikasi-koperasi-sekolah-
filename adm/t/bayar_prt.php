<?php
session_start();

// fungsi - fungsi
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/adm.php");

// NEW: Include Composer's autoloader to access Dompdf
require '../../vendor/autoload.php';

// NEW: Use the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;

nocache();

// nilai (this part remains the same)
$filenya = "bayar_prt.php";
$judulku = "[TABUNGAN]. Entri Data";
$judulku = $judul;
$judulx = $judul;
$nis = nosql($_REQUEST['nis']);
$swkd = nosql($_REQUEST['swkd']);

// DELETED: The old javascript redirect logic is no longer needed.

// isi *START
ob_start();

// DELETED: require("../../inc/js/swap.js"); is not needed for a PDF.

// cek (this part remains the same)
$qcc = mysqli_query($koneksi, "SELECT * FROM m_pelanggan WHERE kd = '$swkd'");
$rcc = mysqli_fetch_assoc($qcc);
$tcc = mysqli_num_rows($qcc);
$cc_kode = balikin($rcc['kode']);
$cc_nama = balikin($rcc['nama']);
$cc_jabatan = balikin($rcc['jabatan']);
$cc_telp = balikin($rcc['telp']);

// debet/kredit terakhir (this part remains the same)
$qswu = mysqli_query($koneksi, "SELECT * FROM pelanggan_tabungan ".
                                 "WHERE pelanggan_kd = '$swkd' ".
                                 "ORDER BY postdate DESC");
$rswu = mysqli_fetch_assoc($qswu);
$swu_status = nosql($rswu['debet']);
$swu_nilai = nosql($rswu['nilai']);
$swu_saldo_akhir = nosql($rswu['saldo']);

if ($swu_status == "true") {
    $x_status = "DEBET";
} else {
    $x_status = "KREDIT";
}

?>

<style>
    body { font-family: sans-serif; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; }
    .no-border-table td { border: 0; }
    .strong { font-weight: bold; }
</style>

<div style="text-align:center;">
    <p><span style="font-size:1.2em;" class="strong"><u>BUKTI DEBET/KREDIT TABUNGAN</u></span></p>
    <p><span style="font-size:1.2em;" class="strong"><u><?php echo $sek_nama; ?></u></span></p>
</div>
<hr>

<table class="no-border-table">
    <tr>
        <td width="35%">Hari, Tanggal</td>
        <td width="1%">:</td>
        <td><span class="strong"><?php echo $arrhari[$hari].', '.$tanggal.' '.$arrbln1[$bulan].' '.$tahun; ?></span></td>
    </tr>
    <tr>
        <td>Kode</td>
        <td>:</td>
        <td><span class="strong"><?php echo $cc_kode; ?></span></td>
    </tr>
    <tr>
        <td>Nama Anggota</td>
        <td>:</td>
        <td><span class="strong"><?php echo $cc_nama; ?></span></td>
    </tr>
    <tr>
        <td>Jabatan</td>
        <td>:</td>
        <td><span class="strong"><?php echo $cc_jabatan; ?></span></td>
    </tr>
    <tr>
        <td>Status Entri</td>
        <td>:</td>
        <td><span class="strong"><?php echo $x_status; ?></span></td>
    </tr>
    <tr>
        <td>Jumlah</td>
        <td>:</td>
        <td><span class="strong"><?php echo xduit2($swu_nilai); ?></span></td>
    </tr>
    <tr>
        <td>Saldo Akhir</td>
        <td>:</td>
        <td><span class="strong"><?php echo xduit2($swu_saldo_akhir); ?></span></td>
    </tr>
</table>

<br><br><br>

<table class="no-border-table">
    <tr>
        <td width="60%"></td>
        <td align="center">
            <span class="strong"><?php echo $sek_kota.', '.$tanggal.' '.$arrbln1[$bulan].' '.$tahun; ?></span>
            <br><br><br><br><br>
            (<span class="strong">Pengurus Koperasi</span>)
        </td>
    </tr>
</table>
<br>
<i>Postdate: <?php echo $today3; ?></i>

<?php
// Get the captured HTML content
$isi = ob_get_contents();
ob_end_clean();

// NEW: Dompdf logic starts here
// -----------------------------------------------------------------------------
// Enable options to load remote images or use modern HTML5/CSS3
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

// Instantiate Dompdf with our options
$dompdf = new Dompdf($options);

// Load the HTML we captured into Dompdf
$dompdf->loadHtml($isi);

// Set paper size and orientation (e.g., A4, portrait)
// 'A5' is a good size for receipts
$dompdf->setPaper('A5', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Generate a dynamic filename
$pdf_filename = "bukti-tabungan-".$cc_kode."-".$tanggal.$bulan.$tahun.".pdf";

// Output the generated PDF to Browser for automatic download
// The key is "Attachment" => true, which forces a download dialog.
$dompdf->stream($pdf_filename, ["Attachment" => true]);
// -----------------------------------------------------------------------------

// diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>