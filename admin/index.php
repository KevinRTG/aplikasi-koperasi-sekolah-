<?php
session_start();


//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");
require("../inc/class/paging.php");
$tpl = LoadTpl("../template/login_admin.html");



nocache();

//nilai
$filenya = "index.php";
$filenya_ke = $sumber;
$judul = "Login Admin";
$judulku = $judul;
$pesan = "Username atau Password Salah. Silahkan Ulangi Lagi...!!";






//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika batal
if ($_POST['btnBTL']) {
	//re-direct
	xloc($filenya);
	exit();
}




//jika ok
if ($_POST['btnOK']) {
	//ambil nilai
	$username = cegah($_POST["usernamex"]);
	$password = md5(cegah($_POST["passwordx"]));

	//cek null
	if ((empty($username)) or (empty($password))) {
		//re-direct
		$pesan = "Input Tidak Lengkap. Harap Diulangi...!!";
		pekem($pesan, $filenya);
		exit();
	} else {
		//query
		$q = mysqli_query($koneksi,  "SELECT * FROM adminx " .
			"WHERE usernamex = '$username' " .
			"AND passwordx = '$password'");
		$row = mysqli_fetch_assoc($q);
		$total = mysqli_num_rows($q);

		//cek login
		if ($total != 0) {
			session_start();

			//nilai
			$_SESSION['kd071_session'] = nosql($row['kd']);
			$_SESSION['nip071_session'] = "ADMIN";
			$_SESSION['username071_session'] = $username;
			$_SESSION['pass071_session'] = $password;
			$_SESSION['sek071_session'] = "ADMIN";
			$_SESSION['pos071_session'] = "Admin";
			$_SESSION['cabang071_session'] = "ADMIN";
			$_SESSION['nm071_session'] = "ADMIN";
			$_SESSION['xnm071_session'] = "ADMIN";
			$_SESSION['hajirobe_session'] = $hajirobe;







			//kasi log login ///////////////////////////////////////////////////////////////////////////////////
			$todayx = $today;



			//ketahui ip
			function get_client_ip_env()
			{
				$ipaddress = '';
				if (getenv('HTTP_CLIENT_IP'))
					$ipaddress = getenv('HTTP_CLIENT_IP');
				else if (getenv('HTTP_X_FORWARDED_FOR'))
					$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
				else if (getenv('HTTP_X_FORWARDED'))
					$ipaddress = getenv('HTTP_X_FORWARDED');
				else if (getenv('HTTP_FORWARDED_FOR'))
					$ipaddress = getenv('HTTP_FORWARDED_FOR');
				else if (getenv('HTTP_FORWARDED'))
					$ipaddress = getenv('HTTP_FORWARDED');
				else if (getenv('REMOTE_ADDR'))
					$ipaddress = getenv('REMOTE_ADDR');
				else
					$ipaddress = 'UNKNOWN';

				return $ipaddress;
			}


			$ipku = get_client_ip_env();








			//insert
			mysqli_query($koneksi, "INSERT INTO user_log_login(kd, ipnya, postdate) VALUES " .
				"('$x', '$ipku', '$today')");
			//kasi log login ///////////////////////////////////////////////////////////////////////////////////






			//re-direct
			$ke = "../adm/index.php";
			xloc($ke);
			exit();
		} else {
			//diskonek
			xfree($q);
			xclose($koneksi);

			//re-direct
			pekem($pesan, $filenya);
			exit();
		}
		//...................................................................................................
	}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////








//isi *START
ob_start();



//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////







echo '<form action="' . $filenya . '" method="post" name="formx">

<p>
Username:
<br/>
<input name="usernamex" type="text" class="btn btn-block btn-primary" required>
</p>



<p>
Password:
<br/>
<input name="passwordx" type="password" class="btn btn-block btn-primary" required>
</p>



<p>
<input name="btnOK" type="submit" class="btn btn-block btn-danger" value="MASUK">
</p>


</form>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//isi
$isi = ob_get_contents();
ob_end_clean();



require("../inc/niltpl.php");


//diskonek
xclose($koneksi);
exit();
