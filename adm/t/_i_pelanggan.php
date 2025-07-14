<?php
sleep(1);

require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/class/paging.php");


//nilai
$limit = "15";
$return_arr = array();
$term = cegah($_GET['term']);

//query
$p = new Pager();
$start = $p->findStart($limit);

$sqlcount = "SELECT * FROM m_pelanggan ".
				"WHERE kode LIKE '%$term%' ".
				"OR nama LIKE '%$term%'";
$sqlresult = $sqlcount;
$target = $filenya;
$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
$pages = $p->findPages($count, $limit);
$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
$pagelist = $p->pageList($_GET['page'], $pages, $target);
$data = mysqli_fetch_array($result);



do
	{
	$row_array["p_kd"] = nosql($data["kd"]);
	$row_array["p_nis"] = nosql($data["kode"]);
	$row_array["p_nama"] = balikin($data["nama"]);

	$row_array["swkd"] = nosql($data["kd"]);
	$row_array["swnama"] = balikin($data["nama"]);

	$i_nama = balikin($data["nama"]);
	$i_nis = nosql($data["kode"]);


	$row_array["value"] = balikin($data["kode"]);
	$row_array["label"] = "$i_nis  [$i_nama]";
	$row_array["description"] = balikin($data["nama"]);

	array_push($return_arr, $row_array);
	}
while ($data = mysqli_fetch_assoc($result));



header("Content-Type: text/json");
echo json_encode($return_arr);

//null-kan
xclose($koneksi);
exit();
?>