<?php
session_start();

//fungsi - fungsi
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/adm.php");
$tpl = LoadTpl("../../template/adm.html");


nocache();

//nilai
$filenya = "lap_harian.php";
$judul = "Lap. Harian";
$judulku = "[TABUNGAN]. $judul";
$judulx = $judul;
$utgl = nosql($_REQUEST['utgl']);
$ubln = nosql($_REQUEST['ubln']);
$uthn = nosql($_REQUEST['uthn']);

$ke = "$filenya?uthn=$uthn&ubln=$ubln&utgl=$utgl";



//focus...
if (empty($utgl))
	{
	$diload = "document.formx.utglx.focus();";
	}
else if (empty($ubln))
	{
	$diload = "document.formx.ublnx.focus();";
	}








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
//js
require("../../inc/js/jumpmenu.js");
require("../../inc/js/swap.js");


//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form name="formx" method="post" action="'.$filenya.'">
<table bgcolor="'.$warnaover.'" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td>
Tanggal : ';
echo "<select name=\"utglx\" onChange=\"MM_jumpMenu('self',this,0)\" class=\"btn btn-warning\">";
echo '<option value="'.$utgl.'">'.$utgl.'</option>';
for ($itgl=1;$itgl<=31;$itgl++)
	{
	echo '<option value="'.$filenya.'?tapelkd='.$tapelkd.'&utgl='.$itgl.'">'.$itgl.'</option>';
	}
echo '</select>';

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
if (empty($utgl))
	{
	echo '<p>
	<font color="#FF0000"><strong>TANGGAL Belum Dipilih...!</strong></font>
	</p>';
	}
else if (empty($ubln))
	{
	echo '<p>
	<font color="#FF0000"><strong>BULAN Belum Dipilih...!</strong></font>
	</p>';
	}
else
	{
	//query
	$qcc = mysqli_query($koneksi, "SELECT * FROM pelanggan_tabungan ".
										"WHERE round(DATE_FORMAT(tgl, '%d')) = '$utgl' ".
										"AND round(DATE_FORMAT(tgl, '%m')) = '$ubln' ".
										"AND round(DATE_FORMAT(tgl, '%Y')) = '$tahun' ".
										"ORDER BY postdate DESC");
	$rcc = mysqli_fetch_assoc($qcc);
	$tcc = mysqli_num_rows($qcc);
	
	
	//jika ada
	if ($tcc != 0)
		{
		echo '<br>
		<div class="table-responsive">          
		<table class="table" border="1">
		<thead>

		<tr valign="top" bgcolor="'.$warnaheader.'">
		<td width="100"><strong><font color="'.$warnatext.'">Waktu</font></strong></td>
		<td width="100"><strong><font color="'.$warnatext.'">KODE</font></strong></td>
		<td><strong><font color="'.$warnatext.'">Nama</font></strong></td>
		<td width="150" align="center"><strong><font color="'.$warnatext.'">DEBET</font></strong></td>
		<td width="150" align="center"><strong><font color="'.$warnatext.'">KREDIT</font></strong></td>
		</tr>
		</thead>
		<tbody>';
		
		do
			{
			if ($warna_set ==0)
				{
				$warna = $warna01;
				$warna_set = 1;
				}
			else
				{
				$warna = $warna02;
				$warna_set = 0;
				}
		
			$i_nomer = $i_nomer + 1;
			$i_kd = nosql($rcc['kd']);
			$i_swkd = nosql($rcc['pelanggan_kd']);
			$i_nis = nosql($rcc['pelanggan_kode']);
			$i_nama = balikin($rcc['pelanggan_nama']);
			$i_nilai = nosql($rcc['nilai']);
			$i_status = nosql($rcc['debet']);
			$i_postdate = balikin($rcc['postdate']);
		
		
		
			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>'.$i_postdate.'</td>
			<td>'.$i_nis.'</td>
			<td>'.$i_nama.'</td>';
		
			//jika debet
			if ($i_status == "true")
				{
				echo '<td align="right">'.xduit2($i_nilai).'</td>
				<td>-</td>';
				}
			else
				{
				echo '<td>-</td>
				<td align="right">'.xduit2($i_nilai).'</td>';
				}
		
			echo '</tr>';
			}
		while ($rcc = mysqli_fetch_assoc($qcc));
		
		
		//ketahui jumlah uang nya... [DEBET]
		$qjmx1 = mysqli_query($koneksi, "SELECT SUM(nilai) AS total ".
											"FROM pelanggan_tabungan ".
											"WHERE round(DATE_FORMAT(tgl, '%d')) = '$utgl' ".
											"AND round(DATE_FORMAT(tgl, '%m')) = '$ubln' ".
											"AND round(DATE_FORMAT(tgl, '%Y')) = '$tahun' ".
											"AND debet = 'true'");
		$rjmx1 = mysqli_fetch_assoc($qjmx1);
		$tjmx1 = mysqli_num_rows($qjmx1);
		$jmx1_total = nosql($rjmx1['total']);
		
		
		
		//ketahui jumlah uang nya... [KREDIT]
		$qjmx2 = mysqli_query($koneksi, "SELECT SUM(nilai) AS total ".
											"FROM pelanggan_tabungan ".
											"WHERE round(DATE_FORMAT(tgl, '%d')) = '$utgl' ".
											"AND round(DATE_FORMAT(tgl, '%m')) = '$ubln' ".
											"AND round(DATE_FORMAT(tgl, '%Y')) = '$tahun' ".
											"AND debet = 'false'");
		$rjmx2 = mysqli_fetch_assoc($qjmx2);
		$tjmx2 = mysqli_num_rows($qjmx2);
		$jmx2_total = nosql($rjmx2['total']);
		
		
		//uang yang ada
		$uang_ada = round($jmx1_total - $jmx2_total);
		
		
		echo '<tr bgcolor="'.$warnaover.'">
		<td></td>
		<td></td>
		<td></td>
		<td align="right"><strong>'.xduit2($jmx1_total).'</strong></td>
		<td align="right"><strong>'.xduit2($jmx2_total).'</strong></td>
		</tr>
		</tbody>
		</table>
		</div>
	
		<br>
		
		<p>
		Jumlah Uang Yang Ada :
		<br>
		<big>
		<strong>'.xduit2($uang_ada).'</strong>
		</big>
		</p>';
		}
	else
		{
		echo '<p>
		<font color="red">
		<strong>Tidak Ada Data</strong>
		</font>
		</p>';
		}
		
	
	
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