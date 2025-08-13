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
$filenya = "user.php";
$judul = "[MASTER] Data Anggota";
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




//nek entri baru
if ($_POST['btnBARU']) {
	//re-direct
	$ke = "$filenya?s=baru&kd=$x";
	xloc($ke);
	exit();
}
















//jika simpan
if ($_POST['btnSMP']) {
	$s = nosql($_POST['s']);
	$kd = nosql($_POST['kd']);
	$page = nosql($_POST['page']);
	$e_kode = cegah($_POST['e_kode']);
	$e_nama = cegah($_POST['e_nama']);
	$e_jabatan = cegah($_POST['e_jabatan']);
	$e_telp = cegah($_POST['e_telp']);





	//nek null
	if ((empty($e_kode)) or (empty($e_nama)) or (empty($e_telp))) {
		//re-direct
		$pesan = "Belum Ditulis. Harap Diulangi...!!";
		$ke = "$filenya?s=$s&kd=$kd";
		pekem($pesan, $ke);
		exit();
	} else {
		//set akses 
		$aksesnya = $e_kode;
		$passx = md5($aksesnya);



		//jika update
		if ($s == "edit") {
			mysqli_query($koneksi, "UPDATE m_pelanggan SET kode = '$e_kode', " .
				"nama = '$e_nama', " .
				"jabatan = '$e_jabatan', " .
				"telp = '$e_telp', " .
				"postdate = '$today' " .
				"WHERE kd = '$kd'");

			//re-direct
			xloc($filenya);
			exit();
		}



		//jika baru
		if ($s == "baru") {
			//cek
			$qcc = mysqli_query($koneksi, "SELECT * FROM m_pelanggan " .
				"WHERE kode = '$e_kode'");
			$rcc = mysqli_fetch_assoc($qcc);
			$tcc = mysqli_num_rows($qcc);

			//nek ada
			if ($tcc != 0) {
				//re-direct
				$pesan = "Sudah Ada. Silahkan Ganti Yang Lain...!!";
				$ke = "$filenya?s=baru&kd=$kd";
				pekem($pesan, $ke);
				exit();
			} else {
				mysqli_query($koneksi, "INSERT INTO m_pelanggan(kd, kode, nama, " .
					"jabatan, telp, postdate) VALUES " .
					"('$kd', '$e_kode', '$e_nama', " .
					"'$e_jabatan', '$e_telp', '$today')");



				//re-direct
				xloc($filenya);
				exit();
			}
		}
	}
}




//jika hapus
if ($_POST['btnHPS']) {
	//ambil nilai
	$jml = nosql($_POST['jml']);
	$page = nosql($_POST['page']);
	$ke = "$filenya?page=$page";

	//ambil semua
	for ($i = 1; $i <= $jml; $i++) {
		//ambil nilai
		$yuk = "item";
		$yuhu = "$yuk$i";
		$kd = nosql($_POST["$yuhu"]);

		//del
		mysqli_query($koneksi, "DELETE FROM m_pelanggan " .
			"WHERE kd = '$kd'");
	}

	//auto-kembali
	xloc($filenya);
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
//jika edit / baru
if (($s == "baru") or ($s == "edit")) {
	$kdx = nosql($_REQUEST['kd']);

	$qx = mysqli_query($koneksi, "SELECT * FROM m_pelanggan " .
		"WHERE kd = '$kdx'");
	$rowx = mysqli_fetch_assoc($qx);
	$e_kode = balikin($rowx['kode']);
	$e_nama = balikin($rowx['nama']);
	$e_jabatan = balikin($rowx['jabatan']);
	$e_telp = balikin($rowx['telp']);


	echo '<a href="' . $filenya . '" class="btn btn-danger"> DAFTAR PELANGGAN</a>
	<hr>
	
	<form action="' . $filenya . '" method="post" name="formx2">';
?>



	<div class="row">


		<?php
		echo '<div class="col-md-4">
	
		<p>
		Kode : 
		<br>
		<input name="e_kode" type="text" value="' . $e_kode . '" size="10" class="btn-warning" required>
		</p>
		
		
		
		<p>
		Nama : 
		<br>
		<input name="e_nama" type="text" value="' . $e_nama . '" size="30" class="btn-warning" required>
		</p>
	
	</div>
	
	
	<div class="col-md-4">
		
		<p>
		Jabatan : 
		<br>
		<input name="e_jabatan" type="text" value="' . $e_jabatan . '" size="20" class="btn-warning">
		</p>

		
		<p>
		Telp./WA : 
		<br>
		<input name="e_telp" type="text" value="' . $e_telp . '" size="10" class="btn-warning">
		</p>
	
		
	</div>';
		?>


	</div>


<?php
	echo '<hr>
	<input name="jml" type="hidden" value="' . $count . '">
	<input name="s" type="hidden" value="' . $s . '">
	<input name="kd" type="hidden" value="' . $kdx . '">
	<input name="page" type="hidden" value="' . $page . '">
	
	<input name="btnSMP" type="submit" value="SIMPAN" class="btn btn-danger">
	<input name="btnBTL" type="submit" value="BATAL" class="btn btn-info">
	
	
	</form>';
} else {
	//jika null
	if (empty($kunci)) {
		$sqlcount = "SELECT * FROM m_pelanggan " .
			"ORDER BY jabatan ASC, " .
			"nama ASC";
	} else {
		$sqlcount = "SELECT * FROM m_pelanggan " .
			"WHERE kode LIKE '%$kunci%' " .
			"OR nama LIKE '%$kunci%' " .
			"OR telp LIKE '%$kunci%' " .
			"OR jabatan LIKE '%$kunci%' " .
			"ORDER BY jabatan ASC, " .
			"nama ASC";
	}



	//query
	$p = new Pager();
	$start = $p->findStart($limit);

	$sqlresult = $sqlcount;

	$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysqli_query($koneksi, "$sqlresult LIMIT " . $start . ", " . $limit);
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysqli_fetch_array($result);



	echo '<form action="' . $filenya . '" method="post" name="formxx">
	<p>
	<input name="btnBARU" type="submit" value="ENTRI BARU" class="btn btn-danger">
	</p>
	<br>
	
	</form>



	<form action="' . $filenya . '" method="post" name="formx">
	<p>
	<input name="kunci" type="text" value="' . $kunci2 . '" size="20" class="btn btn-warning" placeholder="Kata Kunci...">
	<input name="btnCARI" type="submit" value="CARI" class="btn btn-danger">
	<input name="btnBTL" type="submit" value="RESET" class="btn btn-info">
	</p>
		
	
	<div class="table-responsive">          
	<table class="table" border="1">
	<thead>
	
	<tr valign="top" bgcolor="' . $warnaheader . '">
	<td width="20">&nbsp;</td>
	<td width="20">&nbsp;</td>
	<td width="50"><strong><font color="' . $warnatext . '">KODE</font></strong></td>
	<td><strong><font color="' . $warnatext . '">NAMA</font></strong></td>
	<td><strong><font color="' . $warnatext . '">JABATAN</font></strong></td>
	<td><strong><font color="' . $warnatext . '">TELP.</font></strong></td>
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
			$i_kode = balikin($data['kode']);
			$i_nama = balikin($data['nama']);
			$i_jabatan = balikin($data['jabatan']);
			$i_telp = balikin($data['telp']);



			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>
			<input type="checkbox" name="item' . $nomer . '" value="' . $i_kd . '">
	        </td>
			<td>
			<a href="' . $filenya . '?s=edit&page=' . $page . '&kd=' . $i_kd . '"><img src="' . $sumber . '/template/img/edit.gif" width="16" height="16" border="0"></a>
			</td>
			<td>' . $i_kode . '</td>
			<td>' . $i_nama . '</td>
			<td>' . $i_jabatan . '</td>
			<td>' . $i_telp . '</td>
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

	<input name="jml" type="hidden" value="' . $count . '">
	<input name="s" type="hidden" value="' . $s . '">
	<input name="kd" type="hidden" value="' . $kdx . '">
	<input name="page" type="hidden" value="' . $page . '">
	
	<input name="btnALL" type="button" value="SEMUA" onClick="checkAll(' . $count . ')" class="btn btn-primary">
	<input name="btnBTL" type="reset" value="BATAL" class="btn btn-warning">
	<input name="btnHPS" type="submit" value="HAPUS" class="btn btn-danger">
	</td>
	</tr>
	</table>
	</form>';
}








//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../inc/niltpl.php");


//null-kan
xclose($koneksi);
exit();
?>