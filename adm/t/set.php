<?php
session_start();

//fungsi - fungsi
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/adm.php");
require("../../inc/class/paging.php");
$tpl = LoadTpl("../../template/adm.html");


nocache;

//nilai
$filenya = "set.php";
$judul = "Set Debet/Kredit/Saldo";
$judulku = "[TABUNGAN]. $judul";
$judulx = $judul;
$ke = $filenya;






//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//nek simpan
if ($_POST['btnSMP'])
	{
	//nilai
	$min_debet = nosql($_POST['min_debet']);
	$max_kredit = nosql($_POST['max_kredit']);
	$min_saldo = nosql($_POST['min_saldo']);



	//update
	mysqli_query($koneksi, "UPDATE m_tabungan SET min_debet = '$min_debet', ".
								"max_kredit = '$max_kredit', ".
								"min_saldo = '$min_saldo', ".
								"postdate = '$today'");


	//re-direct
	xloc($filenya);
	exit();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////







//isi *START
ob_start();




//js
require("../../inc/js/swap.js");
require("../../inc/js/number.js");



//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//data...
$qdt = mysqli_query($koneksi, "SELECT * FROM m_tabungan");
$rdt = mysqli_fetch_assoc($qdt);
$dt_min_debet = nosql($rdt['min_debet']);
$dt_max_kredit = nosql($rdt['max_kredit']);
$dt_min_saldo = nosql($rdt['min_saldo']);




echo '<form name="formx" method="post" action="'.$filenya.'">
<UL>
<LI>
Minimal Debet (Menabung) :
<br>
Rp.	<input name="min_debet" type="text" size="10" value="'.$dt_min_debet.'" style="text-align:right" onKeyPress="return numbersonly(this, event)" class="btn btn-warning" required>,00
</LI>
<br>

<LI>
Maksimal Kredit (Pengambilan) :
<br>
Rp.	<input name="max_kredit" type="text" size="10" value="'.$dt_max_kredit.'" style="text-align:right" onKeyPress="return numbersonly(this, event)" class="btn btn-warning" required>,00
</LI>
<br>

<LI>
Minimal Saldo :
<br>
Rp.	<input name="min_saldo" type="text" size="10" value="'.$dt_min_saldo.'" style="text-align:right" onKeyPress="return numbersonly(this, event)" class="btn btn-warning" required>,00
</LI>
<br>


<input name="btnSMP" type="submit" value="SIMPAN" class="btn btn-danger">
<input name="btnBTL" type="submit" value="BATAL" class="btn btn-info">

</UL>
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