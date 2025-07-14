<?php
session_start();

//fungsi - fungsi
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/adm.php");
$tpl = LoadTpl("../../template/window.html");


nocache;

//nilai
$filenya = "bayar_prt.php";
$judulku = "[TABUNGAN]. Entri Data";
$judulku = $judul;
$judulx = $judul;
$nis = nosql($_REQUEST['nis']);
$swkd = nosql($_REQUEST['swkd']);



//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//re-direct print...
$ke = "bayar.php?nis=$nis&swkd=$swkd";
$diload = "window.print();location.href='$ke'";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




//isi *START
ob_start();

//js
require("../../inc/js/swap.js");






//cek
$qcc = mysqli_query($koneksi, "SELECT * FROM m_pelanggan ".
									"WHERE kd = '$swkd'");
$rcc = mysqli_fetch_assoc($qcc);
$tcc = mysqli_num_rows($qcc);
$cc_kode = balikin($rcc['kode']);
$cc_nama = balikin($rcc['nama']);
$cc_jabatan = balikin($rcc['jabatan']);
$cc_telp = balikin($rcc['telp']);




//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form name="formx" method="post" action="'.$filenya.'">
<table width="500" border="1" cellspacing="0" cellpadding="3">
<tr valign="top">
<td valign="top" align="center">


<table width="500" border="0" cellspacing="0" cellpadding="3">
<tr valign="top">
<td valign="top" align="center">
<P>
<big>
<strong><u>BUKTI DEBET/KREDIT TABUNGAN</u></strong>
</big>
</P>
<P>
<big>
<strong><u>'.$sek_nama.'</u></strong>
</big>
</P>

<hr height="1">
</td>
</tr>
</table>
<table width="500" border="0" cellspacing="0" cellpadding="3">
<tr valign="top">
<td valign="top" width="200">
Hari, Tanggal
</td>
<td width="1">:</td>
<td>
<strong>'.$arrhari[$hari].', '.$tanggal.' '.$arrbln1[$bulan].' '.$tahun.'</strong>
</td>
</tr>

<tr valign="top">
<td valign="top" width="200">
Kode
</td>
<td width="1">:</td>
<td>
<strong>'.$cc_kode.'</strong>
</td>
</tr>';



//debet/kredit terakhir
$qswu = mysqli_query($koneksi, "SELECT * FROM pelanggan_tabungan ".
									"WHERE pelanggan_kd = '$swkd' ".
									"ORDER BY postdate DESC");
$rswu = mysqli_fetch_assoc($qswu);
$swu_status = nosql($rswu['debet']);
$swu_nilai = nosql($rswu['nilai']);
$swu_saldo_akhir = nosql($rswu['saldo']);


//jika debet
if ($swu_status == "true")
	{
	$x_status = "DEBET";
	}
else
	{
	$x_status = "KREDIT";
	}






echo '<tr valign="top">
<td valign="top" width="200">
Nama Anggota
</td>
<td width="1">:</td>
<td>
<strong>'.$cc_nama.'</strong>
</td>
</tr>


<tr valign="top">
<td valign="top" width="200">
Jabatan
</td>
<td width="1">:</td>
<td>
<strong>'.$cc_jabatan.'</strong>
</td>
</tr>

<tr valign="top">
<td valign="top" width="200">
Status Entri
</td>
<td width="1">:</td>
<td>
<strong>'.$x_status.'</strong>
</td>
</tr>

<tr valign="top">
<td valign="top" width="200">
Jumlah
</td>
<td width="1">:</td>
<td>
<strong>'.xduit2($swu_nilai).'</strong>
</td>
</tr>

<tr valign="top">
<td valign="top" width="200">
Saldo Akhir
</td>
<td width="1">:</td>
<td>
<strong>'.xduit2($swu_saldo_akhir).'</strong>
</td>
</tr>


</table>
<br>
<br>
<br>

<table width="500" border="0" cellspacing="0" cellpadding="3">
<tr valign="top">
<td valign="top" width="200" align="center">
</td>
<td valign="top" align="center">
<strong>'.$sek_kota.', '.$tanggal.' '.$arrbln1[$bulan].' '.$tahun.'</strong>
<br>
<br>
<br>
<br>
<br>
(<strong>Pengurus Koperasi</strong>)
</td>
</tr>
<table>


<input name="swkd" type="hidden" value="'.$cc_kd.'">
<input name="nis" type="hidden" value="'.$nis.'">
</td>
</tr>
</table>

<br>
<br>

</td>
</tr>
</table>
<i>Postdate : '.$today3.'</i>


</form>
<br>
<br>
<br>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//isi
$isi = ob_get_contents();
ob_end_clean();


require("../../inc/niltpl.php");


//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>