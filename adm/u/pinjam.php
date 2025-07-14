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
$filenya = "pinjam.php";
$judul = "[PEMINJAMAN] Pinjam Uang";
$judulku = "$judul";
$judulx = $judul;
$ikd = nosql($_REQUEST['ikd']);
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











//jika simpan
if ($_POST['btnSMP2'])
	{
	$s = nosql($_POST['s']);
	$page = nosql($_POST['page']);
	$kd = cegah($_POST['kd']);
	$e_item = cegah($_POST['e_item']);



	
	//item 
	$qx = mysqli_query($koneksi, "SELECT * FROM m_item ".
									"WHERE kd = '$e_item'");
	$rowx = mysqli_fetch_assoc($qx);
	$e_i_kode = cegah($rowx['kode']);
	$e_i_nama = cegah($rowx['nama']);
	$e_i_nominal = cegah($rowx['nominal']);
	$e_i_biaya = cegah($rowx['biaya_admin']);




	//subtotal
	$e_subtotal = $e_i_nominal;
	$e_subtotal2 = $e_i_nominal + $e_i_biaya;



		
	//update kan
	mysqli_query($koneksi, "UPDATE pelanggan_pinjam SET item_kd = '$e_item', ".
								"item_nominal = '$e_i_nominal', ".
								"item_biaya_admin = '$e_i_biaya', ".
								"subtotal = '$e_subtotal', ".
								"kredit_nominal_total = '$e_subtotal2' ".
								"WHERE kd = '$kd'");
	
		
		
	//re-direct
	$ke = "$filenya?s=edit&kd=$kd#tiproduk";
	xloc($ke);
	exit();

	}

	











//jika simpan
if ($_POST['btnSMP31'])
	{
	$s = nosql($_POST['s']);
	$page = nosql($_POST['page']);
	$kd = cegah($_POST['kd']);
	$e_tgl_pinjam = cegah($_POST['e_tgl_pinjam']);
	$e_transaksi = cegah($_POST['e_transaksi']);
	$e_pelanggan = cegah($_POST['e_pelanggan']);
	$e_subtotal = cegah($_POST['e_subtotal']);




 
	//pecah pelanggan
	$nipku = explode("KODE.", $e_pelanggan);
	$e_swkode = trim($nipku[1]);
	
		
	//detail pelanggan 
	$qx = mysqli_query($koneksi, "SELECT * FROM m_pelanggan ".
									"WHERE kode = '$e_swkode'");
	$rowx = mysqli_fetch_assoc($qx);
	$e_pel_kd = cegah($rowx['kd']);
	$e_pel_kode = cegah($rowx['kode']);
	$e_pel_nama = cegah($rowx['nama']);
	$e_pel_jabatan = cegah($rowx['jabatan']);
	$e_pel_telp = cegah($rowx['telp']);








	//pecah tanggal
	$tgl2_pecah = balikin($e_tgl_pinjam);
	$tgl2_pecahku = explode("-", $tgl2_pecah);
	$tgl2_tgl = trim($tgl2_pecahku[2]);
	$tgl2_bln = trim($tgl2_pecahku[1]);
	$tgl2_thn = trim($tgl2_pecahku[0]);
	$e_tgl_pinjam = "$tgl2_thn:$tgl2_bln:$tgl2_tgl";





	//jika baru
	if ($s == "baru")
		{
		mysqli_query($koneksi, "INSERT INTO pelanggan_pinjam(kd, tgl_pinjam, kode_transaksi, ".
									"pelanggan_kd, pelanggan_kode, pelanggan_nama, ".
									"pelanggan_jabatan, pelanggan_telp, ".
									"subtotal, postdate) VALUES ".
									"('$kd', '$e_tgl_pinjam', '$e_transaksi', ".
									"'$e_pel_kd', '$e_pel_kode', '$e_pel_nama', ".
									"'$e_pel_jabatan', '$e_pel_telp', ".
									"'$e_subtotal', '$today')");
			
			
		//re-direct
		$ke = "$filenya?s=edit&kd=$kd";
		xloc($ke);
		exit();
		}
		
		
		
	//jika edit
	else if ($s == "edit")
		{
		//update 
		mysqli_query($koneksi, "UPDATE pelanggan_pinjam SET tgl_pinjam = '$e_tgl_pinjam', ".
									"kode_transaksi = '$e_transaksi', ".
									"pelanggan_kd = '$e_pel_kd', ".
									"pelanggan_kode = '$e_pel_kode', ".
									"pelanggan_nama = '$e_pel_nama', ".
									"pelanggan_jabatan = '$e_pel_jabatan', ".
									"pelanggan_telp = '$e_pel_telp', ".
									"subtotal = '$e_subtotal', ".
									"postdate = '$today' ".
									"WHERE kd = '$kd'");
												
			
		//re-direct
		$ke = "$filenya?s=edit&kd=$kd";
		xloc($ke);
		exit();										
		}
	}

	
	
	
	
	
	
	
	
	
	
	
//jika simpan kredit
if ($_POST['btnSMP5'])
	{
	$s = nosql($_POST['s']);
	$page = nosql($_POST['page']);
	$kd = cegah($_POST['kd']);
	$e_k_total = cegah($_POST['e_k_total']);
	$e_k_angsuran_total = cegah($_POST['e_k_angsuran_total']);
	$e_k_ket = cegah($_POST['e_k_ket']);

	

						

	//update 
	mysqli_query($koneksi, "UPDATE pelanggan_pinjam SET kredit_angsuran_total = '$e_k_angsuran_total', ".
								"kredit_ket = '$e_k_ket', ".
								"kredit_postdate = '$today', ".
								"kredit_nominal_total = '$e_k_total' ".
								"WHERE kd = '$kd'");

								
								
									
	//hitung nominal per angsuran
	$e_k_per_bayar = round($e_k_total / $e_k_angsuran_total);  
	


	//update kan..
	mysqli_query($koneksi, "UPDATE pelanggan_pinjam SET kredit_angsuran_nominal = '$e_k_per_bayar' ".
								"WHERE kd = '$kd'");


								
								
								
											
		
	//re-direct
	$ke = "$filenya?s=edit&kd=$kd#custom-tabs-one2-home-tab";
	xloc($ke);
	exit();	
	}



	
	
	
	
	
	
						
	
	

	
	
	
	
//jika simpan bayar
if ($_POST['btnSMP6'])
	{
	$s = nosql($_POST['s']);
	$page = nosql($_POST['page']);
	$kd = cegah($_POST['kd']);
	$e_b_tgl = cegah($_POST['e_b_tgl']);
	$e_b_nominal = cegah($_POST['e_b_nominal']);
	$e_b_ket = cegah($_POST['e_b_ket']);

	
	//pecah tanggal
	$tgl2_pecah = balikin($e_b_tgl);
	$tgl2_pecahku = explode("-", $tgl2_pecah);
	$tgl2_tgl = trim($tgl2_pecahku[2]);
	$tgl2_bln = trim($tgl2_pecahku[1]);
	$tgl2_thn = trim($tgl2_pecahku[0]);
	$e_b_tgl = "$tgl2_thn:$tgl2_bln:$tgl2_tgl";




		
	//detail pelanggan 
	$qx = mysqli_query($koneksi, "SELECT * FROM pelanggan_pinjam ".
									"WHERE kd = '$kd'");
	$rowx = mysqli_fetch_assoc($qx);
	$e_pel_kd = cegah($rowx['pelanggan_kd']);
	$e_pel_kode = cegah($rowx['pelanggan_kode']);
	$e_pel_nama = cegah($rowx['pelanggan_nama']);
	$e_pel_telp = cegah($rowx['pelanggan_telp']);




	
	//insert
	mysqli_query($koneksi, "INSERT INTO pelanggan_pinjam_kredit(kd, pelanggan_kd, pelanggan_kode, ".
								"pelanggan_nama, pelanggan_telp, pinjam_kd, tgl_bayar, ".
								"nominal, ket, postdate) VALUES ".
								"('$x', '$e_pel_kd', '$e_pel_kode', ".
								"'$e_pel_nama', '$e_pel_telp', '$kd', '$e_b_tgl', ".
								"'$e_b_nominal', '$e_b_ket', '$today')");



	//re-direct
	$ke = "$filenya?s=edit&kd=$kd";
	xloc($ke);
	exit();	
	}
	
	

	
	






//hapus item
if ($s == "hapusbrg")
	{
	//nilai
	$kd = cegah($_REQUEST['kd']);
	$ikd = cegah($_REQUEST['ikd']);




	//kembalikan ke stock
	$qcc = mysqli_query($koneksi, "SELECT * FROM pelanggan_pinjam_brg ".
										"WHERE pinjam_kd = '$kd' ".
										"AND kd = '$ikd'");
	$rcc = mysqli_fetch_assoc($qcc);
	$cc_qty = balikin($rcc['qty']);
	$cc_ikd = balikin($rcc['item_kd']);


	//item 
	$qx = mysqli_query($koneksi, "SELECT * FROM m_item ".
									"WHERE kd = '$cc_ikd'");
	$rowx = mysqli_fetch_assoc($qx);
	$e_stock_diambil = cegah($rowx['stock_diambil']);
	
	
	
	$stock_akhir = $e_stock_diambil + $cc_qty;	
	
	//update
	mysqli_query($koneksi, "UPDATE m_item SET stock_diambil = '$stock_akhir' ".
								"WHERE kd = '$cc_ikd'");
	
		
	//hapus
	mysqli_query($koneksi, "DELETE FROM pelanggan_pinjam_brg ".
								"WHERE pinjam_kd = '$kd' ".
								"AND kd = '$ikd'");
								

	//re-direct
	$ke = "$filenya?s=edit&kd=$kd";
	xloc($ke);
	exit();
	}
	
	
	
	
	
	
	
	
	
	
	
//hapus angsuran
if ($s == "hapusang")
	{
	//nilai
	$kd = cegah($_REQUEST['kd']);
	$kkd = cegah($_REQUEST['kkd']);




		
	//hapus
	mysqli_query($koneksi, "DELETE FROM pelanggan_pinjam_kredit ".
								"WHERE pinjam_kd = '$kd' ".
								"AND kd = '$kkd'");
								

	//re-direct
	$ke = "$filenya?s=edit&kd=$kd";
	xloc($ke);
	exit();
	}
	
	
	
	
		
//hapus transaksi
if ($s == "hapusnih")
	{
	//nilai
	$kd = cegah($_REQUEST['kd']);

	//hapus
	mysqli_query($koneksi, "DELETE FROM pelanggan_pinjam_brg ".
								"WHERE pinjam_kd = '$kd'");
								
		
	//hapus
	mysqli_query($koneksi, "DELETE FROM pelanggan_pinjam_kredit ".
								"WHERE pinjam_kd = '$kd'");
								

	//hapus
	mysqli_query($koneksi, "DELETE FROM pelanggan_pinjam ".
								"WHERE kd = '$kd'");
								
								
								
	//re-direct
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
//jika baru/edit
if (($s == "baru") OR ($s == "edit"))
	{
	$kdx = nosql($_REQUEST['kd']);

	
	//detail
	$qx = mysqli_query($koneksi, "SELECT * FROM pelanggan_pinjam ".
										"WHERE kd = '$kdx'");
	$rowx = mysqli_fetch_assoc($qx);
	$i_postdate = balikin($rowx['postdate']);
	$i_tgl_pinjam = balikin($rowx['tgl_pinjam']);
	$i_transaksi = balikin($rowx['kode_transaksi']);
	
	$i_p_kd = balikin($rowx['pelanggan_kd']);
	$i_p_kode = balikin($rowx['pelanggan_kode']);
	$i_p_nama = balikin($rowx['pelanggan_nama']);
	
	
	//jika ada
	if (!empty($i_p_kd))
		{
		$i_p_ket = "$i_p_nama KODE.$i_p_kode";
		}
	
	
	
	$i_p_jabatan = balikin($rowx['pelanggan_jabatan']);
	$i_p_telp = balikin($rowx['pelanggan_telp']);
	
	
	$i_i_kd = balikin($rowx['item_kd']);
	$i_i_nominal = balikin($rowx['item_nominal']);
	$i_i_nominalx = xduit3(balikin($rowx['item_nominal']));
	$i_i_biaya = balikin($rowx['item_biaya_admin']);
	$i_i_biayax = xduit3(balikin($rowx['item_biaya_admin']));
	$i_subtotal = balikin($rowx['subtotal']);
	

	//jika ada
	if (!empty($i_k_kd))
		{
		$i_k_ket = "$i_k_nama KODE.$i_k_kode";
		}



	//jika empty
	if (empty($i_i_kd))
		{
		$i_i_ket = "-PRODUK PINJAMAN-";
		}
	else
		{
		$i_i_ket = "$i_i_nominalx. [Biaya Admin : $i_i_biayax]";
		}
		
		

	
	
	//jika null, kasi tanggal hari ini
	if (empty($i_tgl_pinjam))
		{
		$i_tgl_pinjam = "$tahun-$bulan-$tanggal";
		}
		
	
	
	//jika null, kasi kode postdate
	if (empty($i_transaksi))
		{
		$i_transaksi = $today3;
		}	
	?>
	



	
	<script>
	$(document).ready(function () {
	  	
	  	$.noConflict();


		$('#e_pelanggan').on('click', function(){
			$('#e_pelanggan').val("");
			});





	
		$('#e_c_nominal').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});
			

	
		$('#e_k_dp_nominal').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});
			

	
		$('#e_b_nominal').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});




		$.ajax({
				url: "i_pelanggan.php?aksi=kodenya&enil1=<?php echo $i_p_kode;?>",
				type:$(this).attr("method"),
				data:$(this).serialize(),
				success:function(data){					
					$("#d_pembeli").val(data);
					}
				});
				



		$('#e_pelanggan').on('keyup', function(){
        	var e_nil1 = $('#e_pelanggan').val();
        	
        	
			$.ajax({
					url: "i_pelanggan.php?aksi=kodenya&enil1="+e_nil1,
					type:$(this).attr("method"),
					data:$(this).serialize(),
					success:function(data){					
						$("#d_pembeli").val(data);
						}
					});
					
					
			return false;
			});





			
	});
	</script>		
					


	
	
	<div class="row">
		
		<?php
		echo '<div class="col-md-12">
		
			<p>
			<a href="'.$filenya.'" class="btn btn-danger"> DAFTAR PEMINJAMAN LAINNYA</a> 
			</p>
			<hr>
	
		
		</div>
		
	</div>
	
	
	<form action="'.$filenya.'" method="post" name="formx3">
	
	<div class="row">
		
		<div class="col-md-2">
			
			<p>
			Tgl.Pinjam : 
			<br>
			<input name="e_tgl_pinjam" id="e_tgl_pinjam" type="date" value="'.$i_tgl_pinjam.'" size="10" class="btn btn-block btn-default" readonly>
			</p>	
		</div>
		

		<div class="col-md-2">
			<p>
			Kode Transaksi : 
			<br>
			<input name="e_transaksi" id="e_transaksi" type="text" value="'.$i_transaksi.'" size="10" class="btn btn-block btn-default" readonly>
			</p>
			
				
		</div>

		<div class="col-md-2">
		
		
			<p>
			Subtotal : 
			<br>
			Rp.<input name="e_subtotal" type="text" value="'.$i_subtotal.'" size="10" class="btn-default" readonly>,-
			</p>
			
		</div>		
	</div>

	
	<div class="row">

		<div class="col-md-2">
					
			<p>
			Pelanggan : 
			<br>
			<input name="e_pelanggan" id="e_pelanggan" type="text" value="'.$i_p_ket.'" size="10" class="btn btn-block btn-warning" required>
			<input name="e_pelanggan_kd" id="e_pelanggan_kd" type="hidden" value="'.$i_p_kd.'">
			<input name="e_pelanggan_kode" id="e_pelanggan_kode" type="hidden" value="'.$i_p_kode.'">
			</p>
			
		</div>
		
		
		<div class="col-md-8">
			
			<p>
			Detail Pelanggan :
			<br>
			<input name="d_pembeli" id="d_pembeli" type="text" value="'.$i_p_detail.'" size="1" class="btn btn-block btn-default" readonly>
			</p>
			
		</div>
	</div>
		
		
	
	<input name="s" type="hidden" value="'.$s.'">
	<input name="kd" type="hidden" value="'.$kd.'">
	<input name="btnSMP31" type="submit" value="SIMPAN" class="btn btn-md btn btn-outline-success float-left"> </br>
	
	</form>
	
	<hr>';
	?>
	
	

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>





  <script>
    $(document).ready(function() {
    	
      $('#e_pelanggan').typeahead({
        source: function(query, result) {
          $.ajax({
            url: "i_cari_pelanggan.php", // send request to a separate file
            method: "POST",
            data: {
              query: query
            },
            dataType: "json",
            success: function(data) {
              result($.map(data, function(item) {
                return item;
              }));

            }
          })
        }
      });


    });
  </script>


		
	<?php
	//jika edit
	if ($s == "edit")
		{
		echo '<div class="row">

					
			<div class="col-md-6">
		
		
				<div class="card card-primary card-tabs">
				  <div class="card-header p-0 pt-1">
				    <ul class="nav nav-tabs" id="custom-tabs-one1-tab" role="tablist">
				      <li class="nav-item">
				        <a class="nav-link active" id="custom-tabs-one1-home-tab" data-toggle="pill" href="#custom-tabs-one1-home" role="tab" aria-controls="custom-tabs-one1-home" aria-selected="true">PILIH PRODUK PINJAMAN</a>
				      </li>
				    </ul>
				  </div>
				  <div class="card-body">
				    <div class="tab-content" id="custom-tabs-one1-tabContent">
				      <div class="tab-pane show active" id="custom-tabs-one1-home" role="tabpanel" aria-labelledby="custom-tabs-one1-home-tab">
				
	
	
							<form action="'.$filenya.'" method="post" name="formx21">

							<a name="tiproduk"></a>						
						    <div class="info-box mb-3 bg-primary">
						      <span class="info-box-icon"><i class="fa fa-money"></i></span>
						
						      <div class="info-box-content">
						        <span class="info-box-number">
										
									<select name="e_item" id="e_item" class="btn btn-block btn-warning" required>
									<option value="'.$i_i_kd.'" selected>'.$i_i_ket.'</option>';
									
									//list
									$qst = mysqli_query($koneksi, "SELECT * FROM m_item ".
																		"ORDER BY nama ASC");
									$rowst = mysqli_fetch_assoc($qst);
									
									do
										{
										$st_kd = nosql($rowst['kd']);
										$st_nama = balikin($rowst['nama']);
										$st_nominal = balikin($rowst['nominal']);
										$st_biaya = balikin($rowst['biaya_admin']);
									
							
										echo '<option value="'.$st_kd.'">'.$st_nama.' ['.xduit3($st_nominal).']. ['.xduit3($st_biaya).'].</option>';
										}
									while ($rowst = mysqli_fetch_assoc($qst));
									
									echo '</select>
						
									</span>
						
						      </div>
						    </div>
						    
							
						
							<input name="jml" type="hidden" value="'.$count.'">
							<input name="s" type="hidden" value="'.$s.'">
							<input name="kd" type="hidden" value="'.$kdx.'">
							<input name="page" type="hidden" value="'.$page.'">
							
							
							<input name="btnSMP2" type="submit" value="SIMPAN" class="btn btn-float btn btn-outline-success">
		
							
							
							</form>
							
							<hr>';
							
							
							//list
							$qku = mysqli_query($koneksi, "SELECT * FROM pelanggan_pinjam_brg ".
															"WHERE pinjam_kd = '$kd' ".
															"ORDER BY item_nama ASC");
							$rku = mysqli_fetch_assoc($qku);
							$tku = mysqli_num_rows($qku);
							
							//jika ada
							if (!empty($tku))
								{	
								echo '<div class="table-responsive">          
								<table class="table" border="1">
								<thead>
								
								<tr valign="top" bgcolor="'.$warnaheader.'">
								<td width="50"><strong><font color="'.$warnatext.'">NO.</font></strong></td>
								<td align="center"><strong><font color="'.$warnatext.'">NAMA</font></strong></td>
								<td align="center"><strong><font color="'.$warnatext.'">SUBTOTAL</font></strong></td>
								</tr>
								</thead>
								<tbody>';
							
		
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
										$i_kd = nosql($rku['kd']);
										$e_ikd = balikin($rku['item_kd']);
										$e_nama = balikin($rku['item_nama']);
										$e_harga = balikin($rku['item_harga']);
										$e_subtotal = balikin($rku['subtotal']);
										

										
										echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
										echo '<td>'.$nomer.'.</td>
										<td>
										<b>'.$e_nama.'</b>
										<br>
										<b>@'.xduit3($e_harga).'</b>
										<hr>
										
										<a href="'.$filenya.'?s=hapusbrg&page='.$page.'&kd='.$kd.'&ikd='.$e_kd.'" title="HAPUS" class="btn btn-block btn-danger">HAPUS >></a>
										
										</td>
										<td align="right">'.xduit3($e_subtotal).'</td>
								        </tr>';
										}
									while ($rku = mysqli_fetch_assoc($qku));

								echo '</tbody>
								  </table>
								  </div>
								
								
								<table width="100%" border="0" cellspacing="0" cellpadding="3">
								<tr>
								<td>
								<strong><font color="#FF0000">'.$tku.'</font></strong> Data. 
								
								</td>
								</tr>
								</table>';
								}


						
				
				      echo '</div>
	
	

	
				
				    </div>
				  </div>
				</div>
				
	
			</div>
					
		<div class="col-md-6">';
		
		

			echo '<div class="card card-primary card-tabs">
				  <div class="card-header p-0 pt-1">
				    <ul class="nav nav-tabs" id="custom-tabs-one2-tab" role="tablist">
				      <li class="nav-item">
				        <a class="nav-link active" id="custom-tabs-one2-home-tab" data-toggle="pill" href="#custom-tabs-one2-home" role="tab" aria-controls="custom-tabs-one2-home" aria-selected="true">KREDIT</a>
				      </li>

				      <li class="nav-item">
				        <a class="nav-link" id="custom-tabs-one2-profile-tab" data-toggle="pill" href="#custom-tabs-one2-profile" role="tab" aria-controls="custom-tabs-one2-profile" aria-selected="false">BAYAR ANGSURAN</a>
				      </li>
				      <li class="nav-item">
				        <a class="nav-link" id="custom-tabs-one4-profile-tab" data-toggle="pill" href="#custom-tabs-one4-profile" role="tab" aria-controls="custom-tabs-one4-profile" aria-selected="false">HISTORY ANGSURAN</a>
				      </li>
				    </ul>
				  </div>
				  <div class="card-body">
				    <div class="tab-content" id="custom-tabs-one2-tabContent">
				      <div class="tab-pane show active" id="custom-tabs-one2-home" role="tabpanel" aria-labelledby="custom-tabs-one2-home-tab">';
				
	

						$e_k_ket = balikin($rowx['kredit_ket']);
						$e_k_postdate = balikin($rowx['kredit_postdate']);
						$e_k_angsuran_total = balikin($rowx['kredit_angsuran_total']);
						$e_k_nominal_total = balikin($rowx['kredit_nominal_total']);
						$e_k_angsuran_nominal = balikin($rowx['kredit_angsuran_nominal']);
			
			
			
						echo '<form action="'.$filenya.'" method="post" name="formx5">
						
						<p>
						Total Nominal : 
						<br>
						Rp. <input name="e_k_total" type="text" value="'.$e_k_nominal_total.'" size="10" class="btn-default" readonly>,-
						</p>
						<hr>
						
						<p>
						Jumlah Angsuran Yang Diambil : 
						<br>
						<select name="e_k_angsuran_total" class="btn-warning" required>
						<option value="'.$e_k_angsuran_total.'" selected>'.$e_k_angsuran_total.'</option>';
						
						
						for ($k=1;$k<=10;$k++)
							{
							echo '<option value="'.$k.'">'.$k.'</option>';
							}
						
						echo '</select>
						</p>
						<hr>
						
						
						<p>
						Keterangan : 
						<br>
						<input name="e_k_ket" type="text" value="'.$e_k_ket.'" size="10" class="btn-block btn-warning" required>
						</p>
						
						
						
						<input name="s" type="hidden" value="'.$s.'">
						<input name="kd" type="hidden" value="'.$kd.'">
						<input name="btnSMP5" type="submit" value="SIMPAN" class="btn btn-block btn-danger">
						
						<a href="pinjam_pdf.php?kd='.$kd.'" target="_blank" class="btn btn-block btn-danger">CETAK NOTA </a>
						
						<hr>
						<i>[update terakhir : '.$e_k_postdate.'].</i>
						
						<hr>
						<a href="'.$filenya.'?s=hapusnih&kd='.$kd.'" class="btn btn-danger">HAPUS TRANSAKSI INI...!!</a>
						
						
						
						</form>
			
					 
				      </div>
				      
					  

				
				      <div class="tab-pane fade" id="custom-tabs-one2-profile" role="tabpanel" aria-labelledby="custom-tabs-one2-profile-tab">';
					  
					  
					  	//total bayar angsuran
					  	$qku = mysqli_query($koneksi, "SELECT SUM(nominal) AS totalnya ".
					  										"FROM pelanggan_pinjam_kredit ".
															"WHERE pinjam_kd = '$kd' ".
															"ORDER BY postdate DESC");
						$rku = mysqli_fetch_assoc($qku);
						$tku = mysqli_num_rows($qku);
						$ku_a_totalnya = balikin($rku['totalnya']);
					  	
						//jika null
						if (empty($ku_a_totalnya))
							{
							$ku_a_totalnya = 0;
							}
						
						$e_k_nominal_bayar = $ku_a_totalnya; 
						$e_k_angsuran_sisa = $e_k_nominal_total - $ku_a_totalnya;					



						


						//nomor urut terakhir angsuran
						$qku = mysqli_query($koneksi, "SELECT * FROM pelanggan_pinjam_kredit ".
															"WHERE pinjam_kd = '$kd' ".
															"ORDER BY tgl_bayar ASC");
						$rku = mysqli_fetch_assoc($qku);
						$e_k_angsuran_ke = mysqli_num_rows($qku);
						
						
						
						//update kan
						mysqli_query($koneksi, "UPDATE pelanggan_pinjam SET kredit_nominal_bayar = '$e_k_nominal_bayar', ".
													"kredit_nominal_belum = '$e_k_angsuran_sisa', ".
													"kredit_angsuran_ke = '$e_k_angsuran_ke' ".
													"WHERE kd = '$kd'");
						
						
						
						
						//postdate akhir
						$qku = mysqli_query($koneksi, "SELECT * FROM pelanggan_pinjam_kredit ".
															"WHERE pinjam_kd = '$kd' ".
															"ORDER BY postdate DESC");
						$rku = mysqli_fetch_assoc($qku);
						$tku = mysqli_num_rows($qku);
						$ku_a_postdate = balikin($rku['postdate']);
						
						
					  
						echo '<form action="'.$filenya.'" method="post" name="formx6">
					

							<div class="row">
								<div class="col-md-6">
										
									<p>
									Total Angsuran Terbayar : 
									<br>
									Rp. <input name="e_k_angsuran_terbayar" type="text" value="'.$e_k_nominal_bayar.'" size="10" class="btn-default" readonly>,- 
									</p>
									
								</div>
								
								<div class="col-md-6">
									<p>
									Total Angsuran Sisa : 
									<br>
									Rp. <input name="e_k_angsuran_sisa" type="text" value="'.$e_k_angsuran_sisa.'" size="10" class="btn-default" readonly>,- 
									</p>
								</div>
							</div>
						
						<hr>';


						//jika belum lunas
						if (!empty($e_k_angsuran_sisa))
							{
							echo '<div class="row">
								<div class="col-md-6">
					
									<p>
									Tanggal : 
									<br>
									<input name="e_b_tgl" type="date" value="'.$e_b_tgl.'" size="10" class="btn-warning" required>
									</p>
									
								</div>
								
								<div class="col-md-6">
									
									<p>
									Bayar Nominal Per Angsuran : 
									<br>
									Rp.<input name="e_b_nominal" id="e_b_nominal" type="text" value="'.$e_k_angsuran_nominal.'" size="10" class="btn-warning" required>,-
									</p>
								</div>
							</div>
					
				 			
							<p>
							Ket. : 
							<br>
							<input name="e_b_ket" type="text" value="'.$e_b_ket.'" size="10" class="btn-block btn-warning" required>
							</p>
	
							<input name="s" type="hidden" value="'.$s.'">
							<input name="kd" type="hidden" value="'.$kd.'">
							<input name="btnSMP6" type="submit" value="BAYAR" class="btn btn-block btn-danger">
							<hr>
							<i>[update terakhir : '.$ku_a_postdate.'].</i>';
							}
								
						else
							{
							echo '<div class="alert alert-success alert-dismissible">
			                  <h5><i class="icon fas fa-check"></i> SUDAH LUNAS</h5>
			                  Silahkan Cek History Angsuran.
			                </div>';							
							}	
						
						echo '</form>
						<hr>';
				
				      echo '</div>
				




				      <div class="tab-pane fade" id="custom-tabs-one4-profile" role="tabpanel" aria-labelledby="custom-tabs-one4-profile-tab">';
				

						//daftar bayar angsuran kredit
						$qku = mysqli_query($koneksi, "SELECT * FROM pelanggan_pinjam_kredit ".
															"WHERE pinjam_kd = '$kd' ".
															"ORDER BY tgl_bayar ASC");
						$rku = mysqli_fetch_assoc($qku);
						$tku = mysqli_num_rows($qku);
						
						//jika ada
						if (!empty($tku))
							{
							do
								{
								//nilai
								$ku_no = $ku_no + 1;
								$ku_kd = balikin($rku['kd']);
								$ku_postdate = balikin($rku['postdate']);
								$ku_tgl_bayar = balikin($rku['tgl_bayar']);
								$ku_nominal = balikin($rku['nominal']);
								$ku_ket = balikin($rku['ket']);
								
								
								//update nomor urut
								mysqli_query($koneksi, "UPDATE pelanggan_pinjam_kredit SET nourut = '$ku_no' ".
															"WHERE kd = '$ku_kd'");
								
								
								echo '<div class="callout callout-warning">
				                  <h5>Angsuran #'.$ku_no.'</h5>
				
				                  <p>Tanggal Bayar : <br><b>'.$ku_tgl_bayar.'</b></p>
				                  <p>Nominal : <br><b>'.xduit3($ku_nominal).'</b></p>
				                  <p>Keterangan : <br><i>'.$ku_ket.'</i></p>
				                  <hr>
				                  <p><i>Postdate : <b>'.$ku_postdate.'</b></i></p>
				                  <a href="'.$filenya.'?s=hapusang&kd='.$kd.'&kkd='.$ku_kd.'" class="btn btn-block btn-danger" title="Hapus Angsuran">HAPUS</a>
				                </div>
				                <br>';
								}
							while ($rku = mysqli_fetch_assoc($qku));
							}
						
						

				
				      echo '</div>
				


				
				
				    </div>
				    
					
					
				  </div>
				</div>';


				
			
			
			echo '</div>
			
			
			
			
		
		</div>';
		}
	?>


	
	</div>
	<?php
	}
	













else
	{
	//jika null
	if (empty($kunci))
		{
		$sqlcount = "SELECT * FROM pelanggan_pinjam ".
						"ORDER BY postdate DESC";
		}
		
	else
		{
		$sqlcount = "SELECT * FROM pelanggan_pinjam ".
						"WHERE pelanggan_kode LIKE '%$kunci%' ".
						"OR pelanggan_nama LIKE '%$kunci%' ".
						"OR tgl_pinjam LIKE '%$kunci%' ".
						"OR kode_transaksi LIKE '%$kunci%' ".
						"OR subtotal LIKE '%$kunci%' ".
						"OR kredit_nominal_total LIKE '%$kunci%' ".
						"OR kredit_nominal_belum LIKE '%$kunci%' ".
						"OR kredit_angsuran_ke LIKE '%$kunci%' ".
						"OR kredit_dp_tgl LIKE '%$kunci%' ".
						"OR kredit_dp_nominal LIKE '%$kunci%' ".
						"OR kredit_postdate LIKE '%$kunci%' ".
						"OR kredit_ket LIKE '%$kunci%' ".
						"ORDER BY postdate DESC";
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
	
	
	
	echo '<form action="'.$filenya.'" method="post" name="formx">
	<p>
	<input name="kunci" type="text" value="'.$kunci2.'" size="20" class="btn btn-warning" placeholder="Kata Kunci...">
	<input name="btnCARI" type="submit" value="CARI" class="btn btn-danger">
	<input name="btnBTL" type="submit" value="RESET" class="btn btn-info">
	</p>
	<hr>
	
	
	<a href="'.$filenya.'?s=baru&kd='.$x.'" class="btn btn-danger">ENTRI BARU</a>
		
	<div class="table-responsive">          
    <table class="table" border="1">
    <thead>
    <tr valign="top" bgcolor="'.$warnaheader.'">
    <td width="50"><strong><font color="'.$warnatext.'">POSTDATE</font></strong></td>
    <td width="50"><strong><font color="'.$warnatext.'">TGL.PINJAM</font></strong></td>
    <td align="center"><strong><font color="'.$warnatext.'">KODE TRANSAKSI</font></strong></td>
    <td align="center"><strong><font color="'.$warnatext.'">PELANGGAN</font></strong></td>
    <td align="center"><strong><font color="'.$warnatext.'">SUBTOTAL</font></strong></td>
    <td align="center"><strong><font color="'.$warnatext.'">ANGSURAN TOTAL</font></strong></td>
    <td align="center"><strong><font color="'.$warnatext.'">ANGSURAN SISA</font></strong></td>
    <td align="center"><strong><font color="'.$warnatext.'">ANGSURAN KE</font></strong></td>
    <td align="center"><strong><font color="'.$warnatext.'">TGL.BIAYA ADMIN</font></strong></td>
    <td align="center"><strong><font color="'.$warnatext.'">NOMINAL BIAYA ADMIN</font></strong></td>
    <td align="center"><strong><font color="'.$warnatext.'">BAYAR NOMINAL</font></strong></td>
    <td align="center"><strong><font color="'.$warnatext.'">SISA NOMINAL</font></strong></td>
    <td align="center"><strong><font color="'.$warnatext.'">KET.</font></strong></td>
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
            $i_postdate = balikin($data['postdate']);
            $i_tgl_pinjam = balikin($data['tgl_pinjam']);
            $i_transaksi = balikin($data['kode_transaksi']);
            $i_p_kode = balikin($data['pelanggan_kode']);
            $i_p_nama = balikin($data['pelanggan_nama']);
            $i_subtotal = balikin($data['subtotal']);
            $i_a_total = balikin($data['kredit_angsuran_total']);
            $i_a_sisa = xduit3(balikin($data['kredit_nominal_belum']));
            $i_a_ke = balikin($data['kredit_angsuran_ke']);
            $i_dp_tgl = balikin($data['kredit_dp_tgl']);
            $i_dp_nominal = balikin($data['kredit_dp_nominal']); // This should be balikin instead of xduit3 for consistency
            $i_b_nominal = xduit3(balikin($data['kredit_nominal_bayar']));
            $i_s_nominal = xduit3(balikin($data['kredit_nominal_belum']));
            $i_p_postdate = balikin($data['kredit_postdate']);
            $i_ket = balikin($data['kredit_ket']);

            echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
            echo '<td>'.$i_postdate.'</td>
            <td>'.$i_tgl_pinjam.'</td>
            <td>
            '.$i_transaksi.'
            <br>
            <a href="'.$filenya.'?s=edit&kd='.$i_kd.'" class="btn btn-block btn-danger">EDIT </a>
            </td>
            <td>
            '.$i_p_kode.'.
            <br>
            '.$i_p_nama.'.
            <br>
            </td>
            <td align="right">'.xduit3($i_subtotal).'</td>
            <td align="right">'.$i_a_total.'</td>
            <td align="right">'.$i_a_sisa.'</td>
            <td>'.$i_a_ke.'</td>
            <td>'.$i_dp_tgl.'</td>
            <td align="right">'.xduit3($i_dp_nominal).'</td>
            <td align="right">'.$i_b_nominal.'</td>
            <td align="right">'.$i_s_nominal.'</td>
            <td>'.$i_ket.'</td>
             </tr>';
			 
        	'</tbody>
			</table>
			</div>';
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