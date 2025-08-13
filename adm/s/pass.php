<?php
session_start();

//ambil nilai
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/adm.php");
$tpl = LoadTpl("../../template/adm.html");

nocache();

//nilai
$filenya = "pass.php";
$diload = "document.formx.passlama.focus();";
$judul = "[SETTING] Ganti Password";
$judulku = "[SETTING] Ganti Password";
$juduli = $judul;


//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//kasi log entri ///////////////////////////////////////////////////////////////////////////////////
$ku_ket = cegah("[MENU : $judul].");


//insert
mysqli_query($koneksi, "INSERT INTO user_log_entri(kd, ket, postdate) VALUES " .
	"('$x', '$ku_ket', '$today')");
//kasi log login ///////////////////////////////////////////////////////////////////////////////////





//simpan
if ($_POST['btnSMP']) {
	//ambil nilai
	$passlama = md5(cegah($_POST["passlama"]));
	$passbaru = md5(cegah($_POST["passbaru"]));
	$passbaru2 = md5(cegah($_POST["passbaru2"]));

	//cek
	//nek null
	if ((empty($passlama)) or (empty($passbaru)) or (empty($passbaru2))) {
		//re-direct
		$pesan = "Input Tidak Lengkap. Harap Diulangi...!!";
		pekem($pesan, $filenya);
		exit();
	}

	//nek pass baru gak sama
	else if ($passbaru != $passbaru2) {
		//re-direct
		$pesan = "Password Baru Tidak Sama. Harap Diulangi...!!";
		pekem($pesan, $filenya);
		exit();
	} else {
		//query
		$q = mysqli_query($koneksi, "SELECT * FROM adminx " .
			"WHERE kd = '$kd071_session' " .
			"AND usernamex = '$username071_session' " .
			"AND passwordx = '$passlama'");
		$row = mysqli_fetch_assoc($q);
		$total = mysqli_num_rows($q);

		//cek
		if ($total != 0) {
			//perintah SQL
			mysqli_query($koneksi, "UPDATE adminx SET passwordx = '$passbaru' " .
				"WHERE kd = '$kd071_session' " .
				"AND usernamex = '$username071_session'");

			//diskonek
			xfree($q);
			xfree($qbw);
			xclose($koneksi);

			//auto-kembali
			$pesan = "PASSWORD BERHASIL DIGANTI.";
			$ke = "../index.php";
			pekem($pesan, $ke);
			exit();
		} else {
			//diskonek
			xfree($q);
			xfree($qbw);
			xclose($koneksi);

			//re-direct
			$pesan = "PASSWORD LAMA TIDAK COCOK. HARAP DIULANGI...!!!";
			pekem($pesan, $filenya);
			exit();
		}
	}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////







//isi *START
ob_start();




//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form action="' . $filenya . '" method="post" name="formx">
<p>Password Lama : <br>
<input name="passlama" type="password" size="15" class="btn btn-warning" required>
</p>
<br>

<p>Password Baru : <br>
<input name="passbaru" type="password" size="15" class="btn btn-warning" required>
</p>
<br>

<p>RE-Password Baru : <br>
<input name="passbaru2" type="password" size="15" class="btn btn-warning" required>
</p>
<br>

<p>
<input name="btnSMP" type="submit" class="btn btn-danger" value="SIMPAN">
</p>
</form>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../inc/niltpl.php");



//diskonek
xfree($qbw);
xclose($koneksi);
exit();
