<?php
session_start();

require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/adm.php");
require("../../inc/class/paging.php");
$tpl = LoadTpl("../../template/adm.html");

nocache();

//nilai
$filenya = "lap_lunas.php";
$judul = "[PEMINJAMAN] Lap. Lunas";
$judulku = "$judul";
$judulx = $judul;
$kd = nosql($_REQUEST['kd']);
$s = nosql($_REQUEST['s']);
$kunci = cegah($_REQUEST['kunci']);
$kunci2 = balikin($_REQUEST['kunci']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) or ($page == "0")) {
	$page = "1";
}





//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika export
//nek excel
if ($_POST['btnEX']) {
	//nilai
	$fileku = "lap_lunas.xls";


	//isi *START
	ob_start();

	$sqlcount = "SELECT * FROM pelanggan_pinjam " .
		"WHERE kredit_nominal_belum IS NOT NULL " .
		"AND kredit_nominal_belum = '0' " .
		"ORDER BY pelanggan_nama ASC";

	//query
	$limit = 10000;
	$p = new Pager();
	$start = $p->findStart($limit);

	$sqlresult = $sqlcount;

	$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysqli_query($koneksi, "$sqlresult LIMIT " . $start . ", " . $limit);
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysqli_fetch_array($result);




	//ketahui total bayar angsuran
	$qyuk = mysqli_query($koneksi, "SELECT SUM(kredit_nominal_total) AS totalnya " .
		"FROM pelanggan_pinjam " .
		"WHERE kredit_nominal_belum IS NOT NULL " .
		"AND kredit_nominal_belum = '0' ");
	$ryuk = mysqli_fetch_assoc($qyuk);
	$tot_bayar = balikin($ryuk['totalnya']);


	$totalnya = $tot_bayar;



	echo '<div class="table-responsive">
	
	<h3>LAPORAN LUNAS</h3>          
	Total Nominal Lunas : <font color="red"><b>' . xduit3($totalnya) . '</b></font>
	<table class="table" border="1">
	<thead>
	
	<tr valign="top" bgcolor="' . $warnaheader . '">
	<td align="center"><strong><font color="' . $warnatext . '">PELANGGAN</font></strong></td>
	<td width="50"><strong><font color="' . $warnatext . '">TGL.PINJAM</font></strong></td>
	<td align="center"><strong><font color="' . $warnatext . '">KODE TRANSAKSI</font></strong></td>
	<td align="center"><strong><font color="' . $warnatext . '">SUBTOTAL</font></strong></td>
	<td align="center"><strong><font color="' . $warnatext . '">ANGSURAN TOTAL</font></strong></td>
	<td align="center"><strong><font color="' . $warnatext . '">ANGSURAN SISA</font></strong></td>
	<td align="center"><strong><font color="' . $warnatext . '">ANGSURAN KE</font></strong></td>
	<td align="center"><strong><font color="' . $warnatext . '">BAYAR NOMINAL</font></strong></td>
	<td align="center"><strong><font color="' . $warnatext . '">SISA NOMINAL</font></strong></td>
	<td align="center"><strong><font color="' . $warnatext . '">KET.</font></strong></td>
	</tr>
	</thead>
	<tbody>';

	if ($count != 0) {
		do {
			if ($warna_set == 0) {
				$warna = $warna01;
				$warna_set = 1;
			} else {
				$warna = $warna02;
				$warna_set = 0;
			}

			$nomer = $nomer + 1;
			$i_postdate = balikin($data['postdate']);
			$i_tgl_pinjam = balikin($data['tgl_pinjam']);
			$i_transaksi = balikin($data['kode_transaksi']);
			$i_p_kode = balikin($data['pelanggan_kode']);
			$i_p_nama = balikin($data['pelanggan_nama']);
			$i_p_jabatan = balikin($data['pelanggan_jabatan']);
			$i_p_telp = balikin($data['pelanggan_telp']);
			$i_subtotal = balikin($data['subtotal']);

			$i_a_total = balikin($data['kredit_angsuran_total']);
			$i_a_sisa = xduit3(balikin($data['kredit_nominal_belum']));
			$i_a_ke = balikin($data['kredit_angsuran_ke']);

			$i_b_nominal = xduit3(balikin($data['kredit_nominal_bayar']));
			$i_s_nominal = xduit3(balikin($data['kredit_nominal_belum']));

			$i_p_postdate = balikin($data['kredit_postdate']);
			$i_ket = balikin($data['kredit_ket']);




			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>
			' . $i_p_kode . '.
			<br>
			' . $i_p_nama . '.
			<br>
			' . $i_p_jabatan . '.
			<br>
			' . $i_p_telp . '.
			</td>
			<td>' . $i_tgl_pinjam . '</td>
			<td>' . $i_transaksi . '</td>
			<td align="right">' . xduit3($i_subtotal) . '</td>
			<td align="right">' . $i_a_total . '</td>
			<td align="right">' . $i_a_sisa . '</td>
			<td>' . $i_a_ke . '</td>
			<td align="right">' . $i_b_nominal . '</td>
			<td align="right">' . $i_s_nominal . '</td>
			<td>' . $i_ket . '</td>
	        </tr>';
		} while ($data = mysqli_fetch_assoc($result));
	}


	echo '</tbody>
	  </table>
	  </div>';




	//isi
	$isiku = ob_get_contents();
	ob_end_clean();




	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$fileku");
	echo $isiku;


	exit();
}







//nek batal
if ($_POST['btnBTL']) {
	//re-direct
	xloc($filenya);
	exit();
}





//jika cari
if ($_POST['btnCARI']) {
	//nilai
	$kunci = cegah($_POST['kunci']);


	//re-direct
	$ke = "$filenya?kunci=$kunci";
	xloc($ke);
	exit();
}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//isi *START
ob_start();


//require
require("../../template/js/jumpmenu.js");
require("../../template/js/checkall.js");
require("../../template/js/swap.js");
?>



<script>
	$(document).ready(function() {
		$('#table-responsive').dataTable({
			"scrollX": true
		});
	});
</script>

<?php
//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika null
if (empty($kunci)) {
	$sqlcount = "SELECT * FROM pelanggan_pinjam " .
		"WHERE kredit_nominal_belum IS NOT NULL " .
		"AND kredit_nominal_belum = '0' " .
		"ORDER BY pelanggan_nama ASC";
} else {
	$sqlcount = "SELECT * FROM pelanggan_pinjam " .
		"WHERE kredit_nominal_belum IS NOT NULL " .
		"AND kredit_nominal_belum = '0' " .
		"AND (pelanggan_kode LIKE '%$kunci%' " .
		"OR pelanggan_nama LIKE '%$kunci%' " .
		"OR tgl_pinjam LIKE '%$kunci%' " .
		"OR kode_transaksi LIKE '%$kunci%' " .
		"OR subtotal LIKE '%$kunci%' " .
		"OR kredit_nominal_total LIKE '%$kunci%' " .
		"OR kredit_nominal_belum LIKE '%$kunci%' " .
		"OR kredit_angsuran_ke LIKE '%$kunci%' " .
		"OR kredit_postdate LIKE '%$kunci%' " .
		"OR kredit_ket LIKE '%$kunci%') " .
		"ORDER BY pelanggan_nama ASC";
}



//query
$limit = 5;

$p = new Pager();
$start = $p->findStart($limit);

$sqlresult = $sqlcount;

$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
$pages = $p->findPages($count, $limit);
$result = mysqli_query($koneksi, "$sqlresult LIMIT " . $start . ", " . $limit);
$pagelist = $p->pageList($_GET['page'], $pages, $target);
$data = mysqli_fetch_array($result);







//ketahui total bayar angsuran
$qyuk = mysqli_query($koneksi, "SELECT SUM(kredit_nominal_total) AS totalnya " .
	"FROM pelanggan_pinjam " .
	"WHERE kredit_nominal_belum IS NOT NULL " .
	"AND kredit_nominal_belum = '0' ");
$ryuk = mysqli_fetch_assoc($qyuk);
$tot_bayar = balikin($ryuk['totalnya']);


$totalnya = $tot_bayar;


echo '<form action="' . $filenya . '" method="post" name="formx">
<p>
<input name="btnEX" type="submit" value="EXPORT EXCEL " class="btn btn-danger">
</p>
<hr>


<p>
<input name="kunci" type="text" value="' . $kunci2 . '" size="20" class="btn btn-warning" placeholder="Kata Kunci...">
<input name="btnCARI" type="submit" value="CARI" class="btn btn-danger">
<input name="btnBTL" type="submit" value="RESET" class="btn btn-info">
</p>
	

Total Nominal Lunas : <font color="red"><b>' . xduit3($totalnya) . '</b></font>
<div class="table-responsive">          
<table class="table" border="1">
<thead>
<tr valign="top" bgcolor="' . $warnaheader . '">
<td align="center"><strong><font color="' . $warnatext . '">PELANGGAN</font></strong></td>
<td width="50"><strong><font color="' . $warnatext . '">TGL.PINJAM</font></strong></td>
<td align="center"><strong><font color="' . $warnatext . '">KODE TRANSAKSI</font></strong></td>
<td align="center"><strong><font color="' . $warnatext . '">SUBTOTAL</font></strong></td>
<td align="center"><strong><font color="' . $warnatext . '">ANGSURAN TOTAL</font></strong></td>
<td align="center"><strong><font color="' . $warnatext . '">ANGSURAN SISA</font></strong></td>
<td align="center"><strong><font color="' . $warnatext . '">ANGSURAN KE</font></strong></td>
<td align="center"><strong><font color="' . $warnatext . '">BAYAR NOMINAL</font></strong></td>
<td align="center"><strong><font color="' . $warnatext . '">SISA NOMINAL</font></strong></td>
<td align="center"><strong><font color="' . $warnatext . '">KET.</font></strong></td>

</tr>
</thead>
<tbody>';

if ($count != 0) {
	do {
		if ($warna_set == 0) {
			$warna = $warna01;
			$warna_set = 1;
		} else {
			$warna = $warna02;
			$warna_set = 0;
		}

		$nomer = $nomer + 1;
		$i_postdate = balikin($data['postdate']);
		$i_tgl_pinjam = balikin($data['tgl_pinjam']);
		$i_transaksi = balikin($data['kode_transaksi']);
		$i_p_kode = balikin($data['pelanggan_kode']);
		$i_p_nama = balikin($data['pelanggan_nama']);
		$i_p_jabatan = balikin($data['pelanggan_jabatan']);
		$i_p_nowa = balikin($data['pelanggan_telp']);
		$i_subtotal = balikin($data['subtotal']);


		$i_a_total = balikin($data['kredit_angsuran_total']);
		$i_a_sisa = xduit3(balikin($data['kredit_nominal_belum']));
		$i_a_ke = balikin($data['kredit_angsuran_ke']);

		$i_b_nominal = xduit3(balikin($data['kredit_nominal_bayar']));
		$i_s_nominal = xduit3(balikin($data['kredit_nominal_belum']));

		$i_p_postdate = balikin($data['kredit_postdate']);
		$i_ket = balikin($data['kredit_ket']);



		echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
		echo '<td>
		' . $i_p_kode . '.
		<br>
		' . $i_p_nama . '.
		<br>
		' . $i_p_jabatan . '.
		<br>
		' . $i_p_telp . '.
		</td>
		<td>' . $i_tgl_pinjam . '</td>
		<td>' . $i_transaksi . '</td>
		<td align="right">' . xduit3($i_subtotal) . '</td>

		<td align="right">' . $i_a_total . '</td>
		<td align="right">' . $i_a_sisa . '</td>
		<td>' . $i_a_ke . '</td>

		<td align="right">' . $i_b_nominal . '</td>
		<td align="right">' . $i_s_nominal . '</td>
		<td>' . $i_ket . '</td>
        </tr>';
	} while ($data = mysqli_fetch_assoc($result));
}


echo '</tbody>
  </table>
  </div>


<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td>
<strong><font color="#FF0000">' . $count . '</font></strong> Data. ' . $pagelist . '
<br>
<br>

<input name="jml" type="hidden" value="' . $count . '">
<input name="s" type="hidden" value="' . $s . '">
<input name="kd" type="hidden" value="' . $kdx . '">
<input name="page" type="hidden" value="' . $page . '">
</td>
</tr>
</table>
</form>';








//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../inc/niltpl.php");


//null-kan
xclose($koneksi);
exit();
?>