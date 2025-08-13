<?php
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");

nocache();




//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//lihat kode
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'kodenya')) {
	//ambil nilai
	$e_nil1 = cegah($_GET['enil1']);



	//pecah pelanggan
	$nipku = explode("KODE.", $e_nil1);
	$e_swkode = trim($nipku[0]);




	//detail e
	$qyuk = mysqli_query($koneksi, "SELECT * FROM m_pelanggan " .
		"WHERE kode LIKE '$e_swkode%' " .
		"OR nama LIKE '$e_swkode%' " .
		"ORDER BY nama ASC");
	$ryuk = mysqli_fetch_assoc($qyuk);
	$tyuk = mysqli_num_rows($qyuk);
	$yuk_nama = balikin($ryuk['nama']);
	$yuk_jabatan = balikin($ryuk['jabatan']);
	$yuk_telp = balikin($ryuk['telp']);


	$e_kodenya = "Jabatan:$yuk_jabatan. Telp:$yuk_telp";

	echo "$e_kodenya";
}
