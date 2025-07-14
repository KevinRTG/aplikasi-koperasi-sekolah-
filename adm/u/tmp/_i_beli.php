<?php
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
	
nocache;




//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//lihat gambar
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'lihat1'))
	{
	//ambil nilai
	$kd = nosql($_GET['kd']);
	

	$e_filex1 = "$kd-1.jpg";

	$nil_foto = "$sumber/filebox/beli/$kd/$e_filex1";

	echo '<img src="'.$nil_foto.'" width="400">';
	}





//lihat kode
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'kodenya'))
	{
	//ambil nilai
	$e_nil1 = cegah($_GET['enil1']);
	$e_nil2 = cegah($_GET['enil2']);

	

	$e_nil1x = balikin($e_nil1);
	$e_nil2x = balikin($e_nil2);


	//pecah tanggal
	$pecahku = explode("-", $e_nil2x);
	$f_thn = trim($pecahku[0]);
	$f_bln = trim($pecahku[1]);
	$f_tgl = trim($pecahku[2]);
	
	
	//ambil dua digit akhir
	$f_thny = substr($f_thn,-2);
	
	
	//
	$f_bln1 = round($f_bln);
	
	
	
	//ketahui total barang, yang sama
	$qyuk = mysqli_query($koneksi, "SELECT * FROM pelanggan_beli ".
										"WHERE gudang = '$e_nil1x' ".
										"AND tgl_beli = '$e_nil2x'");
	$tyuk = mysqli_num_rows($qyuk);
	$yuk_kodeu = $tyuk + 1;
	
	
	//jika satu digit
	if (strlen($yuk_kodeu) == 1)
		{
		$yuk_kodeux = "00$yuk_kodeu";
		} 
	
	
	//jika dua digit
	else if (strlen($yuk_kodeu) == 2)
		{
		$yuk_kodeux = "0$yuk_kodeu";
		} 
		
	
	//jika dua digit
	else
		{
		$yuk_kodeux = "$yuk_kodeu";
		}
	
	
	
	
	
	$e_kodenya = "$e_nil1$f_thny$f_bln1$f_tgl$yuk_kodeux";

	echo "$e_kodenya";
	}
	



	

?>