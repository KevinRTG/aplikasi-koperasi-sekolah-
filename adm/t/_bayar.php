<?php
session_start();

//fungsi - fungsi
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/class/paging.php");
require("../../inc/cek/adm.php");
$tpl = LoadTpl("../../template/adm.html");


nocache();

//nilai
$filenya = "bayar.php";
$judul = "Debet/Kredit Anggota";
$judulku = "[TABUNGAN]. $judul";
$judulx = $judul;
$s = nosql($_REQUEST['s']);
$nis = nosql($_REQUEST['nis']);
$swkd = nosql($_REQUEST['swkd']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) or ($page == "0")) {
	$page = "1";
}

$ke = "$filenya?swkd=$swkd&nis=$nis&page=$page";





//focus...
if (empty($nis)) {
	$diload = "document.formx.nis.focus();isodatetime();";
} else {
	$diload = "isodatetime();document.formx.status.focus();";
}




//keydown.
//tombol "CTRL"=17,
//tombol "HOME"=36,
//tombol "END"=35, utk. entri baru...
//tombol "ESC"=27,
$dikeydown = "var keyCode = event.keyCode;
				if (keyCode == 35)
					{
					var nyakin = window.confirm('Yakin Akan Memulai Tabungan pelanggan Lainnya...?');

					if (nyakin)
						{
						location.href='$filenya';
						}
					else
						{
						return false
						}
					}";




//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//nek batal
if ($_POST['btnBTL']) {
	//nilai
	$nis = nosql($_POST['nis']);
	$swkd = nosql($_POST['swkd']);

	//hapus
	mysqli_query($koneksi, "DELETE FROM pelanggan_tabungan " .
		"WHERE pelanggan_kd = '$swkd' " .
		"AND DATE_FORMAT(tgl, '%d') = '$tanggal' " .
		"AND DATE_FORMAT(tgl, '%m') = '$bulan' " .
		"AND DATE_FORMAT(tgl, '%Y') = '$tahun'");

	//re-direct
	$ke = "$filenya?swkd=$swkd&nis=$nis";
	xloc($ke);
	exit();
}





//nek baru
if ($_POST['btnBR']) {
	//re-direct
	xloc($filenya);
	exit();
}




//nek ok
if ($_POST['btnOK']) {
	//nilai
	$nis = nosql($_POST['nis']);

	//jika null
	if (empty($nis)) {
		//re-direct
		$pesan = "Input Tidak Lengkap. Harap Diperhatikan...!!";
		pekem($pesan, $filenya);
		exit();
	} else {
		//cek
		$qcc = mysqli_query($koneksi, "SELECT * FROM m_pelanggan " .
			"WHERE kode = '$nis'");
		$rcc = mysqli_fetch_assoc($qcc);
		$tcc = mysqli_num_rows($qcc);
		$cc_kd = nosql($rcc['kd']);
		$cc_nama = balikin($rcc['nama']);

		//jika ada
		if ($tcc != 0) {
			//re-direct
			$ke = "$filenya?swkd=$cc_kd&nis=$nis";
			xloc($ke);
			exit();
		} else {
			//re-direct
			$pesan = "Tidak Ada pelanggan dengan NIS : $nis. Harap Diperhatikan.";
			pekem($pesan, $filenya);
			exit();
		}
	}
}





//jika simpan
if ($_POST['btnSMP']) {
	//nilai
	$nis = nosql($_POST['nis']);
	$swkd = nosql($_POST['swkd']);
	$status = nosql($_POST['status']);
	$jml_uang = nosql($_POST['jml_uang']);



	//detail pelanggan 
	$qx = mysqli_query($koneksi, "SELECT * FROM m_pelanggan " .
		"WHERE kd = '$swkd'");
	$rowx = mysqli_fetch_assoc($qx);
	$e_pel_kode = cegah($rowx['kode']);
	$e_pel_nama = cegah($rowx['nama']);
	$e_pel_jabatan = cegah($rowx['jabatan']);
	$e_pel_telp = cegah($rowx['telp']);







	//cek
	if (empty($jml_uang)) {
		//re-direct
		$pesan = "Input Tidak Lengkap. Harap Diperhatikan...!!";
		$ke = "$filenya?swkd=$swkd&nis=$nis";
		pekem($pesan, $ke);
		exit();
	} else {
		//cek
		$qcc = mysqli_query($koneksi, "SELECT * FROM m_tabungan");
		$rcc = mysqli_fetch_assoc($qcc);
		$tcc = mysqli_num_rows($qcc);
		$cc_min_debet = nosql($rcc['min_debet']);
		$cc_max_kredit = nosql($rcc['max_kredit']);
		$cc_min_saldo = nosql($rcc['min_saldo']);


		//jika debet
		if ($status == "debet") {
			//ketahui total debet
			$qswu = mysqli_query($koneksi, "SELECT SUM(nilai) AS nilai " .
				"FROM pelanggan_tabungan " .
				"WHERE pelanggan_kd = '$swkd' " .
				"AND debet = 'true'");
			$rswu = mysqli_fetch_assoc($qswu);
			$swu_nilai = nosql($rswu['nilai']);


			//ketahui total kredit
			$qswu2 = mysqli_query($koneksi, "SELECT SUM(nilai) AS nilai " .
				"FROM pelanggan_tabungan " .
				"WHERE pelanggan_kd = '$swkd' " .
				"AND debet = 'false'");
			$rswu2 = mysqli_fetch_assoc($qswu2);
			$swu2_nilai = nosql($rswu2['nilai']);



			//jika null, kasi 0
			if (empty($swu_nilai)) {
				$swu_nilai = 0;

				//saldo awal
				$saldo_awal = 0;
			}



			//jika null, kasi 0
			else if (empty($swu2_nilai)) {
				$swu2_nilai = 0;

				//saldo awal
				$saldo_awal = 0;
			} else {
				//saldo awal
				$saldo_awal = round($swu_nilai - $swu2_nilai);
			}




			//saldo akhir
			$saldo_akhir = round($jml_uang + $saldo_awal);


			//cek minimal debet
			if ($jml_uang <= $cc_min_debet) {
				//re-direct
				$pesan = "Jumlah Debet Uang untuk Menabung, Tidak Mencukupi. Harap Diperhatikan.";
				$ke = "$filenya?swkd=$swkd&nis=$nis";
				pekem($pesan, $ke);
				exit();
			} else {
				//masukkan
				mysqli_query($koneksi, "INSERT INTO pelanggan_tabungan(kd, pelanggan_kd, pelanggan_kode, " .
					"pelanggan_nama, pelanggan_jabatan, pelanggan_telp, " .
					"tgl, debet, nilai, saldo, postdate) VALUES " .
					"('$x', '$swkd', '$e_pel_kode', " .
					"'$e_pel_nama', '$e_pel_jabatan', '$e_pel_telp', " .
					"'$today', 'true', '$jml_uang', '$saldo_akhir', '$today')");



				//re-direct printing...
				$ke = "pelanggan_prt.php?swkd=$swkd&nis=$nis";
				xloc($ke);
				exit();
			}
		}

		//jika kredit
		else if ($status == "kredit") {
			//ketahui total debet
			$qswu = mysqli_query($koneksi, "SELECT SUM(nilai) AS nilainya " .
				"FROM pelanggan_tabungan " .
				"WHERE pelanggan_kd = '$swkd' " .
				"AND debet = 'true'");
			$rswu = mysqli_fetch_assoc($qswu);
			$swu_nilai = nosql($rswu['nilainya']);

			//ketahui total kredit
			$qswu2 = mysqli_query($koneksi, "SELECT SUM(nilai) AS nilainya " .
				"FROM pelanggan_tabungan " .
				"WHERE pelanggan_kd = '$swkd' " .
				"AND debet = 'false'");
			$rswu2 = mysqli_fetch_assoc($qswu2);
			$swu2_nilai = nosql($rswu2['nilainya']);



			//jika null, kasi 0
			if (empty($swu_nilai)) {
				$swu_nilai = 0;

				//saldo awal
				$saldo_awal = 0;
			}



			//jika null, kasi 0
			else if (empty($swu2_nilai)) {
				$swu2_nilai = 0;

				//saldo awal
				$saldo_awal = 0;
			} else {
				//saldo awal
				$saldo_awal = round($swu2_nilai - $swu_nilai);
			}




			//saldo akhir
			$saldo_akhir = round($saldo_awal - $jml_uang);



			//cek
			if ($jml_uang > $cc_max_kredit) {
				//re-direct
				$pesan = "Jumlah Kredit (Pengambilan), Melebihi Batas. Harap Diperhatikan.";
				$ke = "$filenya?swkd=$swkd&nis=$nis";
				pekem($pesan, $ke);
				exit();
			} else if ($saldo_awal < $jml_uang) {
				//re-direct
				$pesan = "Sisa Saldo Tidak Mencukupi untuk Kredit (Pengambilan). Harap Diperhatikan.";
				$ke = "$filenya?swkd=$swkd&nis=$nis";
				pekem($pesan, $ke);
				exit();
			} else {
				//masukkan
				mysqli_query($koneksi, "INSERT INTO pelanggan_tabungan(kd, pelanggan_kd, pelanggan_kode, " .
					"pelanggan_nama, pelanggan_jabatan, pelanggan_telp, " .
					"tgl, debet, nilai, saldo, postdate) VALUES " .
					"('$x', '$swkd', '$e_pel_kode', " .
					"'$e_pel_nama', '$e_pel_jabatan', '$e_pel_telp', " .
					"'$today', 'false', '$jml_uang', '$saldo_akhir', '$today')");



				//re-direct printing...
				$ke = "pelanggan_prt.php?swkd=$swkd&nis=$nis";
				xloc($ke);
				exit();
			}
		}
	}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////






//isi *START
ob_start();






//js
require("../../inc/js/jumpmenu.js");
require("../../inc/js/swap.js");
require("../../inc/js/number.js");
require("../../inc/js/jam.js");
require("../../inc/js/down_enter.js");


//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>

<script type="text/javascript">
	$(document).ready(function() {

		var config = {
			source: "i_pelanggan.php",
			select: function(event, ui) {
				$("#nis").val(ui.item.p_nama);
				$("#swkd").val(ui.item.p_kd);
				$("#swnama").val(ui.item.description);
				$("#p_nama").val(ui.item.description);
			},
			minLength: 1 //cari setelah jumlah karakter
		};
		$("#nis").autocomplete(config);




	});
</script>


<?php
echo '<form name="formx" method="post" action="' . $filenya . '">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr valign="top">
<td valign="top" width="250">
<p>
KODE/Nama Anggota :
<br>
<input type="text" name="nis" id="nis" value="' . $nis . '" size="10">
<input name="btnOK" type="submit" value=">>">
</p>';

if (!empty($nis)) {
	//pelanggan
	$qcc = mysqli_query($koneksi, "SELECT * FROM m_pelanggan " .
		"WHERE kode = '$nis'");
	$rcc = mysqli_fetch_assoc($qcc);
	$tcc = mysqli_num_rows($qcc);
	$cc_kd = nosql($rcc['kd']);
	$cc_nama = balikin($rcc['nama']);








	echo '<p>
	Nama Pelanggan :
	<br>
	<input name="nama" type="text" value="' . $cc_nama . '" size="30" class="input" readonly>
	</p>
	

	<p>
	<select name="status" onKeyDown="var keyCode = event.keyCode;
	if (keyCode == 13)
		{
		document.formx.jml_uang.focus();
		return handleEnter(this, event);
		}" required>
	<option value="debet" selected>Debet (Menabung)</option>
	<option value="kredit">Kredit (Pengambilan)</option>
	</select>
	</p>
	
	
	<p>
	Jumlah Uang :
	<br>
	Rp.
	<input name="jml_uang"
	type="text"
	size="10"
	value=""
	style="text-align:right"
	onKeyDown="var keyCode = event.keyCode;
	if (keyCode == 13)
		{
		document.formx.btnSMP.focus();
		document.formx.btnSMP.submit();
		}"
	onKeyPress="return numbersonly(this, event)" required>,00
	</p>
	
	<p>
	<input name="swkd" type="hidden" value="' . $cc_kd . '">
	<input name="btnSMP" type="submit" value="SIMPAN dan CETAK" class="btn btn-danger">
	<input name="btnBTL" type="submit" value="BATAL">
	<br>
	<input name="btnBR" type="submit" value="ENTRI ANGGOTA LAIN">
	</p>';



	echo '</td>
	<td width="10">&nbsp;</td>
	<td valign="top">';

	//nek ada
	if ($tcc != 0) {
		echo '<p>
		<table border="1" cellspacing="0" cellpadding="3">
		<tr valign="top">
		<td valign="top">
		<strong>HISTORY TABUNGAN</strong>
		[<a href="pelanggan_history_prt.php?swkd=' . $swkd . '&nis=' . $nis . '"><img src="' . $sumber . '/img/print.gif" border="0" width="16" height="16"></a>]
		<p>';

		//data tabungan
		$p = new Pager();
		$start = $p->findStart($limit);

		$sqlcount = "SELECT * FROM pelanggan_tabungan " .
			"WHERE pelanggan_kd = '$swkd' " .
			"ORDER BY postdate DESC";
		$sqlresult = $sqlcount;

		$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
		$pages = $p->findPages($count, $limit);
		$result = mysqli_query($koneksi, "$sqlresult LIMIT " . $start . ", " . $limit);
		$target = "$filenya?swkd=$swkd&nis=$nis";
		$pagelist = $p->pageList($_GET['page'], $pages, $target);
		$data = mysqli_fetch_array($result);



		echo '<table width="100%" border="1" cellspacing="0" cellpadding="3">
		<tr valign="top" bgcolor="' . $warnaheader . '">
		<td width="200" align="center"><strong><font color="' . $warnatext . '">Tanggal</font></strong></td>
		<td width="150" align="center"><strong><font color="' . $warnatext . '">Debet</font></strong></td>
		<td width="150" align="center"><strong><font color="' . $warnatext . '">Kredit</font></strong></td>
		<td width="200" align="center"><strong><font color="' . $warnatext . '">Saldo</font></strong></td>
		</tr>';

		do {
			if ($warna_set == 0) {
				$warna = $warna01;
				$warna_set = 1;
			} else {
				$warna = $warna02;
				$warna_set = 0;
			}

			//nilai
			$d_tgl = balikin($data['tglnya']);
			$d_debet = nosql($data['debet']);
			$d_nilai = nosql($data['nilai']);
			$d_saldo = nosql($data['saldo']);
			$d_postdate = $data['postdate'];


			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>' . $d_postdate . '</td>';

			//jika debet
			if ($d_debet == "true") {
				echo '<td align="right">' . xduit2($d_nilai) . '</td>
				<td>-</td>';
			}

			//kredit
			else {
				echo '<td>-</td>
				<td align="right">' . xduit2($d_nilai) . '</td>';
			}

			echo '<td align="right">' . xduit2($d_saldo) . '</td>
			</tr>';
		} while ($data = mysqli_fetch_assoc($result));

		echo '</table>
		
		
		<table width="100%" cellspacing="0" cellpadding="3">
		<tr valign="top">
		<td align="right">
		<font color="red"><strong>' . $count . '</strong></font> Transaksi. ' . $pagelist . '
		</td>
		</tr>
		</table>
		</p>
		
		
		
		
		</td>
		</tr>
		</table>
		</p>';
	}
}

echo '</td>
</tr>
</table>';

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