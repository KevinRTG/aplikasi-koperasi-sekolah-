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
$filenya = "lap_item.php";
$judul = "[PELANGGAN] Lap. Item Terjual";
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
	$fileku = "lap_item_terjual.xls";


	//isi *START
	ob_start();


	$sqlcount = "SELECT DISTINCT(item_kd) AS ikd " .
		"FROM pelanggan_beli_brg " .
		"ORDER BY item_nama ASC";


	//query
	$limit = 1000;
	$p = new Pager();
	$start = $p->findStart($limit);

	$sqlresult = $sqlcount;

	$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysqli_query($koneksi, "$sqlresult LIMIT " . $start . ", " . $limit);
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysqli_fetch_array($result);



	echo '<div class="table-responsive">
	
	<h3>LAPORAN ITEM PRODUK TERJUAL</h3>
	<table class="table" border="1">
	<thead>
	
	<tr valign="top" bgcolor="' . $warnaheader . '">
	<td align="center"><strong><font color="' . $warnatext . '">ITEM PRODUK</font></strong></td>
	<td width="50" align="center"><strong><font color="' . $warnatext . '">QTY</font></strong></td>
	<td width="150" align="center"><strong><font color="' . $warnatext . '">SUBTOTAL</font></strong></td>
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
			$i_kd = nosql($data['ikd']);


			//detail nama ne
			$qyuk = mysqli_query($koneksi, "SELECT * FROM m_item " .
				"WHERE kd = '$i_kd'");
			$ryuk = mysqli_fetch_assoc($qyuk);
			$yuk_kode = balikin($ryuk['kode']);
			$yuk_nama = balikin($ryuk['nama']);



			//detail qty
			$qyuk = mysqli_query($koneksi, "SELECT SUM(qty) AS totalnya " .
				"FROM pelanggan_beli_brg " .
				"WHERE item_kd = '$i_kd'");
			$ryuk = mysqli_fetch_assoc($qyuk);
			$yuk_qty = balikin($ryuk['totalnya']);


			//detail subtotal
			$qyuk = mysqli_query($koneksi, "SELECT SUM(subtotal) AS totalnya " .
				"FROM pelanggan_beli_brg " .
				"WHERE item_kd = '$i_kd'");
			$ryuk = mysqli_fetch_assoc($qyuk);
			$yuk_subtotal = balikin($ryuk['totalnya']);




			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>
			' . $yuk_nama . '
			<br>
			' . $yuk_kode . '
			</td>
			<td align="right">' . $yuk_qty . '</td>
			<td align="right">' . xduit3($yuk_subtotal) . '</td>
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
	$sqlcount = "SELECT DISTINCT(item_kd) AS ikd " .
		"FROM pelanggan_beli_brg " .
		"ORDER BY item_nama ASC";
} else {
	$sqlcount = "SELECT DISTINCT(item_kd) AS ikd " .
		"WHERE item_kode LIKE '%$kunci%' " .
		"OR item_nama LIKE '%$kunci%' " .
		"ORDER BY item_nama ASC";
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
	

<div class="table-responsive">          
<table class="table" border="1">
<thead>
<tr valign="top" bgcolor="' . $warnaheader . '">
<td align="center"><strong><font color="' . $warnatext . '">ITEM PRODUK</font></strong></td>
<td width="50" align="center"><strong><font color="' . $warnatext . '">QTY</font></strong></td>
<td width="150" align="center"><strong><font color="' . $warnatext . '">SUBTOTAL</font></strong></td>
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
		$i_kd = nosql($data['ikd']);


		//detail nama ne
		$qyuk = mysqli_query($koneksi, "SELECT * FROM m_item " .
			"WHERE kd = '$i_kd'");
		$ryuk = mysqli_fetch_assoc($qyuk);
		$yuk_kode = balikin($ryuk['kode']);
		$yuk_nama = balikin($ryuk['nama']);



		//detail qty
		$qyuk = mysqli_query($koneksi, "SELECT SUM(qty) AS totalnya " .
			"FROM pelanggan_beli_brg " .
			"WHERE item_kd = '$i_kd'");
		$ryuk = mysqli_fetch_assoc($qyuk);
		$yuk_qty = balikin($ryuk['totalnya']);


		//detail subtotal
		$qyuk = mysqli_query($koneksi, "SELECT SUM(subtotal) AS totalnya " .
			"FROM pelanggan_beli_brg " .
			"WHERE item_kd = '$i_kd'");
		$ryuk = mysqli_fetch_assoc($qyuk);
		$yuk_subtotal = balikin($ryuk['totalnya']);




		echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
		echo '<td>
		' . $yuk_nama . '
		<br>
		' . $yuk_kode . '
		</td>
		<td align="right">' . $yuk_qty . '</td>
		<td align="right">' . xduit3($yuk_subtotal) . '</td>
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