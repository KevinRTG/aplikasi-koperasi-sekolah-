<?php
session_start();

//ambil nilai
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");

nocache();

//nilai
$filenya = "$sumber/adm/u/i_cari_pelanggan.php";
$filenyax = "$sumber/adm/u/i_cari_pelanggan.php";
$judul = "cari nama";
$juduli = $judul;






if (isset($_POST['query'])) {

  $search_query = cegah($_POST["query"]);

  $query = "SELECT * FROM m_pelanggan " .
    "WHERE nama LIKE '%" . $search_query . "%' " .
    "OR kode LIKE '%" . $search_query . "%' " .
    "ORDER BY nama ASC LIMIT 12";
  $result = mysqli_query($koneksi, $query);

  $data = array();

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $i_nama = balikin($row["nama"]);
      $i_kode = balikin($row["kode"]);

      $data[] = "$i_nama KODE.$i_kode";
    }
    echo json_encode($data);
  }


  exit();
}
