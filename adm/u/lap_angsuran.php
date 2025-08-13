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
$filenya = "lap_angsuran.php";
$judul = "[PEMINJAMAN] Lap. Angsuran";
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
	$fileku = "lap_angsuran.xls";


	//isi *START
	ob_start();

	$sqlcount = "SELECT * FROM pelanggan_pinjam_kredit " .
		"ORDER BY tgl_bayar DESC";

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



	//ketahui total 
	$qyuk = mysqli_query($koneksi, "SELECT SUM(nominal) AS totalnya " .
		"FROM pelanggan_pinjam_kredit");
	$ryuk = mysqli_fetch_assoc($qyuk);
	$tot_dp = balikin($ryuk['totalnya']);



	echo '<div class="table-responsive">
	
	<h3>LAPORAN ANGSURAN</h3>          
	Total Nominal Angsuran : <font color="red"><b>' . xduit3($tot_dp) . '</b></font>
	<table class="table" border="1">
	<thead>
	
	<tr valign="top" bgcolor="' . $warnaheader . '">
	<td width="50" align="center"><strong><font color="' . $warnatext . '">TGL.BAYAR</font></strong></td>
	<td><strong><font color="' . $warnatext . '">PELANGGAN</font></strong></td>
	<td width="150" align="center"><strong><font color="' . $warnatext . '">NOMINAL</font></strong></td>
	<td width="50" align="center"><strong><font color="' . $warnatext . '">ANGSURAN</font></strong></td>
	<td width="200" align="center"><strong><font color="' . $warnatext . '">KETERANGAN</font></strong></td>
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
			$i_kd = nosql($data['kd']);
			$i_postdate = balikin($data['tgl_bayar']);
			$i_nominal = balikin($data['nominal']);
			$i_ket = balikin($data['ket']);
			$i_p_nama = balikin($data['pelanggan_nama']);
			$i_p_telp = balikin($data['pelanggan_telp']);
			$i_nourut = balikin($data['nourut']);


			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>' . $i_postdate . '</td>
			<td>
			' . $i_p_nama . '
			<br>
			Telp:' . $i_p_telp . '
			</td>
			<td align="right">' . xduit3($i_nominal) . '</td>
			<td>Ke-' . $i_nourut . '</td>
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
	$sqlcount = "SELECT * FROM pelanggan_pinjam_kredit " .
		"ORDER BY tgl_bayar DESC";
} else {
	$sqlcount = "SELECT * FROM pelanggan_pinjam_kredit " .
		"WHERE tgl_bayar LIKE '%$kunci%' " .
		"OR nominal LIKE '%$kunci%' " .
		"OR ket LIKE '%$kunci%' " .
		"OR nourut LIKE '%$kunci%' " .
		"OR pelanggan_nama LIKE '%$kunci%' " .
		"OR pelanggan_telp LIKE '%$kunci%' " .
		"ORDER BY tgl_bayar DESC";
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



//ketahui total 
$qyuk = mysqli_query($koneksi, "SELECT SUM(nominal) AS totalnya " .
	"FROM pelanggan_pinjam_kredit");
$ryuk = mysqli_fetch_assoc($qyuk);
$tot_dp = balikin($ryuk['totalnya']);


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
	

Total Nominal Angsuran : <font color="red"><b>' . xduit3($tot_dp) . '</b></font>
<div class="table-responsive">          
<table class="table" border="1">
<thead>
<tr valign="top" bgcolor="' . $warnaheader . '">
<td width="50" align="center"><strong><font color="' . $warnatext . '">TGL.BAYAR</font></strong></td>
<td><strong><font color="' . $warnatext . '">PELANGGAN</font></strong></td>
<td width="150" align="center"><strong><font color="' . $warnatext . '">NOMINAL</font></strong></td>
<td width="50" align="center"><strong><font color="' . $warnatext . '">ANGSURAN</font></strong></td>
<td width="200" align="center"><strong><font color="' . $warnatext . '">KETERANGAN</font></strong></td>
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
		$i_kd = nosql($data['kd']);
		$i_postdate = balikin($data['tgl_bayar']);
		$i_nominal = balikin($data['nominal']);
		$i_ket = balikin($data['ket']);
		$i_p_nama = balikin($data['pelanggan_nama']);
		$i_p_telp = balikin($data['pelanggan_telp']);
		$i_nourut = balikin($data['nourut']);


		echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
		echo '<td>' . $i_postdate . '</td>
		<td>
		' . $i_p_nama . '
		<br>
		Telp:' . $i_p_telp . '
		</td>
		<td align="right">' . xduit3($i_nominal) . '</td>
		<td>Ke-' . $i_nourut . '</td>
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