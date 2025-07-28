<?php
session_start();

require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/adm.php");
require("../../inc/class/paging.php");
$tpl = LoadTpl("../../template/adm.html");

nocache;

//nilai
$filenya = "item.php";
$judul = "[MASTER] Data Produk Pinjaman";
$judulku = "$judul";
$judulx = $judul;
$kd = nosql($_REQUEST['kd']);
$s = nosql($_REQUEST['s']);
$kunci = cegah($_REQUEST['kunci']);
$kunci2 = balikin($_REQUEST['kunci']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}



$limit = 5;


//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//nek batal
if ($_POST['btnBTL'])
	{
	//re-direct
	xloc($filenya);
	exit();
	}





//jika cari
if ($_POST['btnCARI'])
	{
	//nilai
	$kunci = cegah($_POST['kunci']);


	//re-direct
	$ke = "$filenya?kunci=$kunci";
	xloc($ke);
	exit();
	}




//nek entri baru
if ($_POST['btnBARU'])
	{
	//re-direct
	$ke = "$filenya?s=baru&kd=$x";
	xloc($ke);
	exit();
	}







//jika simpan
if ($_POST['btnSMP'])
	{
	$s = nosql($_POST['s']);
	$kd = nosql($_POST['kd']);
	$page = nosql($_POST['page']);
	$e_kode = cegah($_POST['e_kode']);
	$e_nama = cegah($_POST['e_nama']);
	$e_nominal = cegah($_POST['e_nominal']);
	$e_biaya = cegah($_POST['e_biaya']);



	//nek null
	if ((empty($e_nama)) OR (empty($e_kode)))
		{
		//re-direct
		$pesan = "Belum Ditulis. Harap Diulangi...!!";
		$ke = "$filenya?s=$s&kd=$kd";
		pekem($pesan,$ke);
		exit();
		}
	else
		{
		//jika update
		if ($s == "edit")
			{
			mysqli_query($koneksi, "UPDATE m_item SET kode = '$e_kode', ".
										"nama = '$e_nama', ".
										"nominal = '$e_nominal', ".
										"biaya_admin = '$e_biaya', ".
										"postdate = '$today' ".
										"WHERE kd = '$kd'");

			//re-direct
			xloc($filenya);
			exit();
			}



		//jika baru
		if ($s == "baru")
			{
			//cek
			$qcc = mysqli_query($koneksi, "SELECT kode FROM m_item ".
												"WHERE kode = '$e_kode'");
			$rcc = mysqli_fetch_assoc($qcc);
			$tcc = mysqli_num_rows($qcc);

			//nek ada
			if ($tcc != 0)
				{
				//re-direct
				$pesan = "Sudah Ada. Silahkan Ganti Yang Lain...!!";
				$ke = "$filenya?s=baru&kd=$kd";
				pekem($pesan,$ke);
				exit();
				}
			else
				{
				//insert
				mysqli_query($koneksi, "INSERT INTO m_item(kd, kode, nama, ".
										"nominal, biaya_admin, postdate) VALUES ".
										"('$kd', '$e_kode', '$e_nama', ".
										"'$e_nominal', '$e_biaya', '$today')");


				//re-direct
				xloc($filenya);
				exit();
				}
			}
		}
	}




//jika hapus
if ($_POST['btnHPS'])
	{
	//ambil nilai
	$jml = nosql($_POST['jml']);
	$page = nosql($_POST['page']);
	$ke = "$filenya?page=$page";

	//ambil semua
	for ($i=1; $i<=$jml;$i++)
		{
		//ambil nilai
		$yuk = "item";
		$yuhu = "$yuk$i";
		$kd = nosql($_POST["$yuhu"]);

		//del
		mysqli_query($koneksi, "DELETE FROM m_item ".
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
    $('#table-responsive').dataTable( {
        "scrollX": true
    } );
} );
  </script>
  
<?php
//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika edit / baru
if (($s == "baru") OR ($s == "edit"))
	{
	$kdx = nosql($_REQUEST['kd']);

	$qx = mysqli_query($koneksi, "SELECT * FROM m_item ".
						"WHERE kd = '$kdx'");
	$rowx = mysqli_fetch_assoc($qx);
	$e_kode = balikin($rowx['kode']);
	$e_nama = balikin($rowx['nama']);
	$e_nominal = balikin($rowx['nominal']);
	$e_biaya = balikin($rowx['biaya_admin']);

	?>
	
	
	
	
	<!-- Bootstrap core JavaScript -->
	<script src="<?php echo $sumber;?>/template/vendors/jquery/jquery.min.js"></script>
	<script src="<?php echo $sumber;?>/template/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>


	
	<script>
	$(document).ready(function () {
		

	
		$('#e_nominal').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});
			

		$('#e_biaya').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});
			
	});
	</script>		
					

	
	<?php
	echo '<a href="'.$filenya.'" class="btn btn-danger"> DAFTAR PRODUK LAINNYA</a>
	<hr>
	
	<form action="'.$filenya.'" method="post" name="formx2">

	<div class="grid">

		<div class="col-md-3">
			
		<p>
		KODE : 
		<br>
		<input name="e_kode" id="e_kode" type="text" value="'.$e_kode.'" size="5" class="btn-warning" required>
		</p>
	
	
		</div>
		<div class="col-md-3">
		
		<p>
		NAMA : 
		<br>
		<input name="e_nama" id="e_nama" type="text" value="'.$e_nama.'" size="15" class="btn-warning" required>
		</p>
	
	
		</div>
		
		

		<div class="col-md-3">
		
	
		<p>
		Nominal : 
		<br>
		Rp.<input name="e_nominal" id="e_nominal" type="text" value="'.$e_nominal.'" size="15" class="btn-warning" required>,-
		</p>
	
	
		</div>
		<div class="col-md-3">
	
	
		<p>
		Biaya Admin : 
		<br>
		Rp.<input name="e_biaya" id="e_biaya" type="text" value="'.$e_biaya.'" size="15" class="btn-warning" required>,-
		</p>
	
		
		</div>

	
	</div>




	<p>
	<input name="jml" type="hidden" value="'.$count.'">
	<input name="s" type="hidden" value="'.$s.'">
	<input name="kd" type="hidden" value="'.$kdx.'">
	<input name="page" type="hidden" value="'.$page.'">
	
	<input name="btnSMP" type="submit" value="SIMPAN" class="btn btn-m btn-danger">
	<input name="btnBTL" type="submit" value="BATAL" class="btn btn-m btn-info">
	</p>
	
	
	
	</form>';
	}
	








else
	{
	//jika null
	if (empty($kunci))
		{
		$sqlcount = "SELECT * FROM m_item ".
						"ORDER BY kode ASC";
		}
		
	else
		{
		$sqlcount = "SELECT * FROM m_item ".
						"WHERE kode LIKE '%$kunci%' ".
						"OR nama LIKE '%$kunci%' ".
						"OR nominal LIKE '%$kunci%' ".
						"OR biaya_admin LIKE '%$kunci%' ".
						"ORDER BY kode ASC";
		}
		
		
	
	//query
	$p = new Pager();
	$start = $p->findStart($limit);
	
	$sqlresult = $sqlcount;
	
	$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysqli_fetch_array($result);
	
	
	
	echo '<form action="'.$filenya.'" method="post" name="formxx">
	<p>
	<input name="btnBARU" type="submit" value="ENTRI BARU" class="btn btn-danger">
	</p>
	<br>
	
	</form>



	<form action="'.$filenya.'" method="post" name="formx">
	<p>
	<input name="kunci" type="text" value="'.$kunci2.'" size="20" class="btn btn-warning" placeholder="Kata Kunci...">
	<input name="btnCARI" type="submit" value="CARI" class="btn btn-danger">
	<input name="btnBTL" type="submit" value="RESET" class="btn btn-info">
	</p>
		
	
	<div class="table-responsive">          
	<table class="table" border="1">
	<thead>
	
	<tr valign="top" bgcolor="'.$warnaheader.'">
	<td width="20">&nbsp;</td>
	<td width="20">&nbsp;</td>
	<td width="50"><strong><font color="'.$warnatext.'">KODE</font></strong></td>
	<td align="center"><strong><font color="'.$warnatext.'">NAMA</font></strong></td>
	<td width="200" align="center"><strong><font color="'.$warnatext.'">NOMINAL</font></strong></td>
	<td width="200" align="center"><strong><font color="'.$warnatext.'">BIAYA ADMIN</font></strong></td>
	<td width="50"><strong><font color="'.$warnatext.'">POSTDATE</font></strong></td>
	</tr>
	</thead>
	<tbody>';
	
	if ($count != 0)
		{
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
	
			$nomer = $nomer + 1;
			$i_kd = nosql($data['kd']);
			$i_kode = balikin($data['kode']);
			$i_nama = balikin($data['nama']);
			$i_nominal = balikin($data['nominal']);
			$i_biaya = balikin($data['biaya_admin']);
			$i_postdate = balikin($data['postdate']);


			
			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>
			<input type="checkbox" name="item'.$nomer.'" value="'.$i_kd.'">
	        </td>
			<td>
			<a href="'.$filenya.'?s=edit&page='.$page.'&kd='.$i_kd.'"><img src="'.$sumber.'/template/img/edit.gif" width="16" height="16" border="0"></a>
			</td>
			<td>'.$i_kode.'</td>
			<td>'.$i_nama.'</td>
			<td align="right">'.xduit2($i_nominal).'</td>
			<td align="right">'.xduit2($i_biaya).'</td>
			<td>'.$i_postdate.'</td>
	        </tr>';
			}
		while ($data = mysqli_fetch_assoc($result));
		}
	
	
	echo '</tbody>
	  </table>
	  </div>
	
	
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
	<tr>
	<td>
	<strong><font color="#FF0000">'.$count.'</font></strong> Data. '.$pagelist.'
	<br>
	<br>

	<input name="jml" type="hidden" value="'.$count.'">
	<input name="s" type="hidden" value="'.$s.'">
	<input name="kd" type="hidden" value="'.$kdx.'">
	<input name="page" type="hidden" value="'.$page.'">
	
	<input name="btnALL" type="button" value="SEMUA" onClick="checkAll('.$count.')" class="btn btn-primary">
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