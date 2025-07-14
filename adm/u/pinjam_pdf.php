<?php
session_start();


//ambil nilai
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/class/paging.php");



nocache;



//nilai
$filenya = "pinjam_pdf.php"; 
$judulku = $judul; 
$bookkd = nosql($_REQUEST['kd']);
$kd = nosql($_REQUEST['kd']);
$judul = "NOTA";







require_once("../../inc/class/dompdf/autoload.inc.php");

use Dompdf\Dompdf;
$dompdf = new Dompdf();









//isi *START
ob_start();

?>


  
  <script>
  	$(document).ready(function() {
    $('#table-responsive').dataTable( {
        "scrollX": true
    } );
} );
  </script>
  



<?php
//detail
$qx = mysqli_query($koneksi, "SELECT * FROM pelanggan_pinjam ".
									"WHERE kd = '$bookkd'");
$rowx = mysqli_fetch_assoc($qx);
$i_postdate = balikin($rowx['postdate']);
$i_tgl_pinjam = balikin($rowx['tgl_pinjam']);
$i_transaksi = balikin($rowx['kode_transaksi']);


$i_p_kd = balikin($rowx['pelanggan_kd']);
$i_p_kode = balikin($rowx['pelanggan_kode']);
$i_p_nama = balikin($rowx['pelanggan_nama']);
$i_p_jabatan = balikin($rowx['pelanggan_jabatan']);


//jika ada
if (!empty($i_p_kd))
	{
	$i_p_ket = "$i_p_nama KODE.$i_p_kode";
	}


$i_p_telp = balikin($rowx['pelanggan_telp']);

$i_subtotal = balikin($rowx['subtotal']);
$i_angsuran_total = balikin($rowx['kredit_angsuran_total']);
$i_angsuran_nominal = balikin($rowx['kredit_angsuran_nominal']);

$i_selisih = $i_subtotal; 



$i_i_nominal = balikin($rowx['item_nominal']);
$i_i_biaya = balikin($rowx['item_biaya_admin']);



echo '<p>
<div class="table-responsive">
<table class="table" border="0" cellspacing="0" cellpadding="5" width="300">
<thead>
<tr>
<td align="center">

<b>'.$sek_nama.'</b>
<br>
TELP : '.$sek_telp.'
<hr>

</td>
</tr>
</thead>
</table>


<div class="table-responsive">
<table class="table" border="0" cellspacing="0" cellpadding="5" width="300">
<thead>
<tr valign="top">
<td align="left">
'.$i_tgl_pinjam.'
<br>
'.$i_p_nama.'
<br>
'.$i_p_jabatan.'
</td>

<td align="right">
TELP.'.$i_p_telp.'
</td>
</tr>
</thead>
</table>





<table class="table" border="0" cellspacing="0" cellpadding="5" width="300">
<thead>
<tr valign="top">
<td align="left">
No. Transaksi
</td>

<td align="right">
'.$i_transaksi.' 
</td>
</tr>



<tr valign="top">
<td align="left">
PINJAM NOMINAL
</td>

<td align="right"> ';

echo xduit3($i_i_nominal);

echo '</td>
</tr>



<tr valign="top">
<td align="left">
BIAYA ADMIN
</td>

<td align="right"> ';

echo xduit3($i_i_biaya);

echo '</td>
</tr>



</thead>
</table>






<table class="table" border="0" cellspacing="0" cellpadding="5" width="300">
<thead>
<tr valign="top">
<td align="left">
Total Nilai
</td>

<td align="right">
&nbsp;
</td>

<td align="right">
'.xduit3($i_subtotal).' 
</td>
</tr>


</thead>
</table>




<table class="table" border="0" cellspacing="0" cellpadding="5" width="300">
<thead>
<tr valign="top">
<td align="center">
<hr>
</td>
</tr>
</thead>
</table>';



echo '<table class="table" border="0" cellspacing="0" cellpadding="5" width="300">
<thead>';

for ($k=1;$k<=$i_angsuran_total;$k++)
	{
	//nilai
	$qku = mysqli_query($koneksi, "SELECT * FROM pelanggan_pinjam_kredit ".
										"WHERE pinjam_kd = '$kd' ".
										"AND nourut = '$k'");
	$rku = mysqli_fetch_assoc($qku);
	$tku = mysqli_num_rows($qku);
	$ku_nominal = balikin($rku['nominal']);
	
	//jika ada
	if (!empty($ku_nominal))
		{
		$ku_nominal2 = xduit3($ku_nominal);
		}
	else
		{
		$ku_nominal2 = "-";
		}
		
	echo '<tr valign="top">
	<td align="left">
	Pembayaran #'.$k.'  
	</td>
	
	
	<td align="right">
	'.$ku_nominal2.' 
	</td>
	</tr>';
	}


echo '</thead>
</table>';




//ketahui yg udah bayar
$qku = mysqli_query($koneksi, "SELECT * FROM pelanggan_pinjam_kredit ".
									"WHERE pinjam_kd = '$kd'");
$rku = mysqli_fetch_assoc($qku);
$tku = mysqli_num_rows($qku);

//sisa yg belum
$sisanya = $i_angsuran_total - $tku;


//nominal kurang
$nil_kurang = $sisanya * $i_angsuran_nominal;




echo '<table class="table" border="0" cellspacing="0" cellpadding="5" width="300">
<thead>
<tr valign="top">
<td align="left">
Sisa Pembayaran
</td>

<td align="right">
'.xduit3($nil_kurang).' 
</td>
</tr>

</thead>
</table>

<table class="table" border="0" cellspacing="0" cellpadding="5" width="300">
<thead>
<tr valign="top">
<td align="left">
<hr> 
</td>
</tr>

</thead>
</table>';


	



echo '<table class="table" border="0" cellspacing="0" cellpadding="5" width="300">
<thead>
<tr valign="top">
<td align="left">
Postdate Cetak : '.$today.'
<hr> 
</td>
</tr>

</thead>
</table>




<table class="table" border="0" cellspacing="0" cellpadding="5" width="300">
<thead>
<tr valign="top">
<td align="center">
BENDAHARA KOPERASI, 
<br> 
<br> 
<br> 
<br> 
<br> 
<br> 
<br>
.................................


 
</td>
</tr>

</thead>
</table>










</div>

</p>';



//isi
$isi = ob_get_contents();
ob_end_clean();








//echo $isi;




$dompdf->loadHtml($isi);

// Setting ukuran dan orientasi kertas
$dompdf->setPaper('A4', 'potrait');
// Rendering dari HTML Ke PDF
$dompdf->render();


$pdf = $dompdf->output();

ob_end_clean();

// Melakukan output file Pdf
//$dompdf->stream('raport-$nis-$ku_nama2.pdf');
$dompdf->stream('nota-'.$i_transaksi.'.pdf');








require("../../inc/niltpl.php");


//diskonek
xclose($koneksi);
exit();
?>