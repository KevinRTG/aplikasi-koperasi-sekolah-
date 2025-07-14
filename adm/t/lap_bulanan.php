<?php
session_start();

//fungsi - fungsi
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/adm.php");
$tpl = LoadTpl("../../template/adm.html");


nocache;

//nilai
$filenya = "lap_bulanan.php";
$judul = "Lap. Bulanan";
$judulku = "[TABUNGAN]. $judul";
$judulx = $judul;
$ubln = nosql($_REQUEST['ubln']);
$uthn = nosql($_REQUEST['uthn']);

$ke = "$filenya?uthn=$uthn&ubln=$ubln";



//focus...
if (empty($ubln))
	{
	$diload = "document.formx.ublnx.focus();";
	}







//isi *START
ob_start();




//js
require("../../inc/js/jumpmenu.js");
require("../../inc/js/swap.js");


//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form name="formx" method="post" action="'.$filenya.'">
<table bgcolor="'.$warnaover.'" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td>
Bulan : ';
echo "<select name=\"ublnx\" onChange=\"MM_jumpMenu('self',this,0)\" class=\"btn btn-warning\">";
echo '<option value="'.$ubln.''.$tahun.'" selected>'.$arrbln[$ubln].' '.$tahun.'</option>';
for ($i=1;$i<=12;$i++)
	{
	echo '<option value="'.$filenya.'?utgl='.$utgl.'&ubln='.$i.'&uthn='.$tahun.'">'.$arrbln[$i].' '.$tahun.'</option>';
	}

echo '</select>
</td>
</tr>
</table>';


//nek blm dipilih
if (empty($ubln))
	{
	echo '<p>
	<font color="#FF0000"><strong>BULAN Belum Dipilih...!</strong></font>
	</p>';
	}
else
	{
	//mendapatkan jumlah tanggal maksimum dalam suatu bulan
	$tgl = 0;
	$bulan = $ubln;
	$bln = $bulan + 1;
	$thn = $tahun;
	
	$lastday = mktime (0,0,0,$bln,$tgl,$thn);
	
	//total tanggal dalam sebulan
	$tkhir = strftime ("%d", $lastday);
	
	
	
	echo '<br>
	<table width="800" border="1" cellspacing="0" cellpadding="3">
	<tr valign="top" bgcolor="'.$warnaheader.'">
	<td width="200" align="center"><strong><font color="'.$warnatext.'">TANGGAL</font></strong></td>
	<td width="200" align="center"><strong><font color="'.$warnatext.'">DEBET</font></strong></td>
	<td width="200" align="center"><strong><font color="'.$warnatext.'">KREDIT</font></strong></td>
	<td align="center"><strong><font color="'.$warnatext.'">SALDO</font></strong></td>
	</tr>';
	
	
	
	//lopping tgl
	for ($i=1;$i<=$tkhir;$i++)
		{
		//ketahui harinya
		$day = $i;
		$month = $bulan;
		$year = $thn;
	
	
		//mencari hari
		$a = substr($year, 2);
			//mengambil dua digit terakhir tahun
	
		$b = (int)($a/4);
			//membagi tahun dengan 4 tanpa memperhitungkan sisa
	
		$c = $month;
			//mengambil angka bulan
	
		$d = $day;
			//mengambil tanggal
	
		$tot1 = $a + $b + $c + $d;
			//jumlah sementara, sebelum dikurangani dengan angka kunci bulan
	
		//kunci bulanan
		if ($c == 1)
			{
			$kunci = "2";
			}
	
		else if ($c == 2)
			{
			$kunci = "7";
			}
	
		else if ($c == 3)
			{
			$kunci = "1";
			}
	
		else if ($c == 4)
			{
			$kunci = "6";
			}
	
		else if ($c == 5)
			{
			$kunci = "5";
			}
	
		else if ($c == 6)
			{
			$kunci = "3";
			}
	
		else if ($c == 7)
			{
			$kunci = "2";
			}
	
		else if ($c == 8)
			{
			$kunci = "7";
			}
	
		else if ($c == 9)
			{
			$kunci = "5";
			}
	
		else if ($c == 10)
			{
			$kunci = "4";
			}
	
		else if ($c == 11)
			{
			$kunci = "2";
			}
	
		else if ($c == 12)
			{
			$kunci = "1";
			}
	
		$total = $tot1 - $kunci;
	
		//angka hari
		$hari = $total%7;
	
		//jika angka hari == 0, sebenarnya adalah 7.
		if ($hari == 0)
			{
			$hari = ($hari +7);
			}
	
		//kabisat, tahun habis dibagi empat alias tanpa sisa
		$kabisat = (int)$year % 4;
	
		if ($kabisat ==0)
			{
			$hri = $hri-1;
			}
	
	
	
		//hari ke-n
		if ($hari == 3)
			{
			$hri = 4;
			$dino = "Rabu";
			}
	
		else if ($hari == 4)
			{
			$hri = 5;
			$dino = "Kamis";
			}
	
		else if ($hari == 5)
			{
			$hri = 6;
			$dino = "Jum'at";
			}
	
		else if ($hari == 6)
			{
			$hri = 7;
			$dino = "Sabtu";
			}
	
		else if ($hari == 7)
			{
			$hri = 1;
			$dino = "Minggu";
			}
	
		else if ($hari == 1)
			{
			$hri = 2;
			$dino = "Senin";
			}
	
		else if ($hari == 2)
			{
			$hri = 3;
			$dino = "Selasa";
			}
	
	
		//nek minggu, abang ngi wae
		if ($hri == 1)
			{
			$warna = "red";
			$mggu_attr = "disabled";
			}
		else
			{
			if ($warna_set ==0)
				{
				$warna = $warna01;
				$warna_set = 1;
				$mggu_attr = "";
				}
			else
				{
				$warna = $warna02;
				$warna_set = 0;
				$mggu_attr = "";
				}
			}
	
		//nilai tanggal
		$i_tgl_bayar = "$dino, $i $arrbln[$ubln] $uthn";
	
	
		//jumlah uang... [DEBET]
		$qjmx = mysqli_query($koneksi, "SELECT SUM(nilai) AS nilai ".
											"FROM pelanggan_tabungan ".
											"WHERE round(DATE_FORMAT(tgl, '%d')) = '$i' ".
											"AND round(DATE_FORMAT(tgl, '%m')) = '$ubln' ".
											"AND round(DATE_FORMAT(tgl, '%Y')) = '$uthn' ".
											"AND debet = 'true'");
		$rjmx = mysqli_fetch_assoc($qjmx);
		$tjmx = mysqli_num_rows($qjmx);
		$jmx_nilai = nosql($rjmx['nilai']);
	
	
	
		//jumlah uang... [KREDIT]
		$qjmx2 = mysqli_query($koneksi, "SELECT SUM(nilai) AS nilai ".
											"FROM pelanggan_tabungan ".
											"WHERE round(DATE_FORMAT(tgl, '%d')) = '$i' ".
											"AND round(DATE_FORMAT(tgl, '%m')) = '$ubln' ".
											"AND round(DATE_FORMAT(tgl, '%Y')) = '$uthn' ".
											"AND debet = 'false'");
		$rjmx2 = mysqli_fetch_assoc($qjmx2);
		$tjmx2 = mysqli_num_rows($qjmx2);
		$jmx2_nilai = nosql($rjmx2['nilai']);
	
	
		//saldo
		$i_saldo = round($jmx_nilai - $jmx2_nilai);
	
		echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
		echo '<td align="right">'.$i_tgl_bayar.'</td>
		<td align="right">'.xduit2($jmx_nilai).'</td>
		<td align="right">'.xduit2($jmx2_nilai).'</td>
		<td align="right">'.xduit2($i_saldo).'</td>
		</tr>';
		}
	
	
	//total debet sebulan
	$qdbu = mysqli_query($koneksi, "SELECT SUM(nilai) AS total ".
										"FROM pelanggan_tabungan ".
										"WHERE round(DATE_FORMAT(tgl, '%m')) = '$ubln' ".
										"AND round(DATE_FORMAT(tgl, '%Y')) = '$uthn' ".
										"AND debet = 'true'");
	$rdbu = mysqli_fetch_assoc($qdbu);
	$dbu_nilai = nosql($rdbu['total']);
	
	
	//total kredit sebulan
	$qdbu2 = mysqli_query($koneksi, "SELECT SUM(nilai) AS total ".
										"FROM pelanggan_tabungan ".
										"WHERE round(DATE_FORMAT(tgl, '%m')) = '$ubln' ".
										"AND round(DATE_FORMAT(tgl, '%Y')) = '$uthn' ".
										"AND debet = 'false'");
	$rdbu2 = mysqli_fetch_assoc($qdbu2);
	$dbu2_nilai = nosql($rdbu2['total']);
	
	
	//saldo akhir bulan
	$saldo_akhir = round($dbu_nilai - $dbu2_nilai);
	
	echo '<tr bgcolor="'.$warnaheader.'">
	<td align="right">&nbsp;</td>
	<td align="right"><strong>'.xduit2($dbu_nilai).'</strong></td>
	<td align="right"><strong>'.xduit2($dbu2_nilai).'</strong></td>
	<td align="right"><strong>'.xduit2($saldo_akhir).'</strong></td>
	</tr>
	</table>
	<br>
	<br>
	<br>';
	}

echo '</form>
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