<?php
session_start();


//ambil nilai
require("inc/config.php");
require("inc/fungsi.php");
require("inc/koneksi.php");



nocache();



//re-direct
$ke = "admin/index.php";
xloc($ke);
exit();
?>