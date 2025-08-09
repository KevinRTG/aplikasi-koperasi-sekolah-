<?php
session_start();

//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");
require("../inc/cek/adm.php");
require("../inc/class/paging.php");
$tpl = LoadTpl("../template/adm.html");


nocache();

//nilai
$filenya = "index.php";








//nilai
$judul = "DashBoard Koperasi SMAN 1 Cikarang Timur";
$judulku = $judul;




//postdate entri
$qyuk = mysqli_query($koneksi, "SELECT * FROM user_log_entri ".
									"ORDER BY postdate DESC");
$ryuk = mysqli_fetch_assoc($qyuk);
$yuk_entri_terakhir = balikin($ryuk['postdate']);






//postdate login
$qyuk = mysqli_query($koneksi, "SELECT * FROM user_log_login ".
									"ORDER BY postdate DESC");
$ryuk = mysqli_fetch_assoc($qyuk);
$yuk_login_terakhir = balikin($ryuk['postdate']);








//jatuh tempo
$qyuk = mysqli_query($koneksi, "SELECT * FROM pelanggan_pinjam ".
									"WHERE kredit_nominal_belum IS NOT NULL ".
									"AND kredit_nominal_belum > '0'");
$ryuk = mysqli_fetch_assoc($qyuk);
$yuk_jtempo = mysqli_num_rows($qyuk);























//set peringkat //////////////////////////////////////////////////////////////////////////////////////
$qyuk3 = mysqli_query($koneksi, "SELECT * FROM m_pelanggan ".
									"ORDER BY nama ASC");
$ryuk3 = mysqli_fetch_assoc($qyuk3);

do 
	{
	$i_kd = nosql($ryuk3['kd']);
	
	
	//jumlah
	$qku = mysqli_query($koneksi, "SELECT * FROM pelanggan_pinjam ".
									"WHERE pelanggan_kd = '$i_kd'");
	$tku = mysqli_num_rows($qku);
	
	
	//nominal
	$qku2 = mysqli_query($koneksi, "SELECT SUM(kredit_nominal_total) AS totalnya ".
									"FROM pelanggan_pinjam ".
									"WHERE pelanggan_kd = '$i_kd'");
	$rku2 = mysqli_fetch_assoc($qku2);
	$i_nominal = balikin($rku2['totalnya']);


	//update 
	mysqli_query($koneksi, "UPDATE m_pelanggan SET total_transaksi = '$tku', ".
								"total_nominal = '$i_nominal' ".
								"WHERE kd = '$i_kd'");
	}
while ($ryuk3 = mysqli_fetch_assoc($qyuk3));
//set peringkat //////////////////////////////////////////////////////////////////////////////////////






//isi *START
ob_start();


echo '<div class="row">

  <div class="col-lg-12">
    <div class="info-box mb-3 bg-primary">
      <span class="info-box-icon"><i class="fa fa-user"></i></span>

      <div class="info-box-content">
        <span class="info-box-number">
        		'.$judul.'
			</span>

      </div>
    </div>

	</div>
</div>';




//isi
$judulku = ob_get_contents();
ob_end_clean();
              


























//isi *START
ob_start();

//tanggal sekarang
$m = date("m");
$de = date("d");
$y = date("Y");

//ambil 14hari terakhir
for($i=0; $i<=14; $i++)
	{
	$nilku = date('Ymd',mktime(0,0,0,$m,($de-$i),$y)); 

	echo "$nilku, ";
	}


//isi
$isi_data1 = ob_get_contents();
ob_end_clean();










//isi *START
ob_start();

//tanggal sekarang
$m = date("m");
$de = date("d");
$y = date("Y");

//ambil 14hari terakhir
for($i=0; $i<=14; $i++)
	{
	$nilku = date('Y-m-d',mktime(0,0,0,$m,($de-$i),$y)); 


	//pecah
	$ipecah = explode("-", $nilku);
	$itahun = trim($ipecah[0]);  
	$ibln = trim($ipecah[1]);
	$itgl = trim($ipecah[2]);    


	//ketahui ordernya...
	$qyuk = mysqli_query($koneksi, "SELECT * FROM user_log_login ".
							"WHERE round(DATE_FORMAT(postdate, '%d')) = '$itgl' ".
							"AND round(DATE_FORMAT(postdate, '%m')) = '$ibln' ".
							"AND round(DATE_FORMAT(postdate, '%Y')) = '$itahun'");
	$tyuk = mysqli_num_rows($qyuk);
									
									
	if (empty($tyuk))
		{
		$tyuk = "0";
		}
		
	echo "$tyuk, ";
	}


//isi
$isi_data2 = ob_get_contents();
ob_end_clean();









//isi *START
ob_start();

//tanggal sekarang
$m = date("m");
$de = date("d");
$y = date("Y");

//ambil 14hari terakhir
for($i=0; $i<=14; $i++)
	{
	$nilku = date('Y-m-d',mktime(0,0,0,$m,($de-$i),$y)); 


	//pecah
	$ipecah = explode("-", $nilku);
	$itahun = trim($ipecah[0]);  
	$ibln = trim($ipecah[1]);
	$itgl = trim($ipecah[2]);    


	//ketahui
	$qyuk = mysqli_query($koneksi, "SELECT * FROM user_log_entri ".
							"WHERE round(DATE_FORMAT(postdate, '%d')) = '$itgl' ".
							"AND round(DATE_FORMAT(postdate, '%m')) = '$ibln' ".
							"AND round(DATE_FORMAT(postdate, '%Y')) = '$itahun'");
	$tyuk = mysqli_num_rows($qyuk);
	
	if (empty($tyuk))
		{
		$tyuk = "0";
		}
		
	echo "$tyuk, ";
	}


//isi
$isi_data3 = ob_get_contents();
ob_end_clean();





//isi *START
ob_start();

//tanggal sekarang
$m = date("m");
$de = date("d");
$y = date("Y");

//ambil 14hari terakhir
for($i=0; $i<=14; $i++)
	{
	$nilku = date('Y-m-d',mktime(0,0,0,$m,($de-$i),$y)); 


	//pecah
	$ipecah = explode("-", $nilku);
	$itahun = trim($ipecah[0]);  
	$ibln = trim($ipecah[1]);
	$itgl = trim($ipecah[2]);    


	//ketahui
	$qyuk = mysqli_query($koneksi, "SELECT * FROM pelanggan_pinjam ".
							"WHERE round(DATE_FORMAT(tgl_beli, '%d')) = '$itgl' ".
							"AND round(DATE_FORMAT(tgl_beli, '%m')) = '$ibln' ".
							"AND round(DATE_FORMAT(tgl_beli, '%Y')) = '$itahun'");
	$tyuk = mysqli_num_rows($qyuk);
	
	if (empty($tyuk))
		{
		$tyuk = "0";
		}
		
	echo "$tyuk, ";
	}


//isi
$isi_data4 = ob_get_contents();
ob_end_clean();



















//isi *START
ob_start();


//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jumlah 
$qx = mysqli_query($koneksi, "SELECT * FROM m_pelanggan");
$rowx = mysqli_fetch_assoc($qx);
$e_total_user = mysqli_num_rows($qx);



//jumlah 
$qx = mysqli_query($koneksi, "SELECT * FROM m_item");
$rowx = mysqli_fetch_assoc($qx);
$e_total_item = mysqli_num_rows($qx);



//transaksi 
$qx = mysqli_query($koneksi, "SELECT * FROM pelanggan_pinjam");
$rowx = mysqli_fetch_assoc($qx);
$e_total_kredit = mysqli_num_rows($qx);







//transaksi : biaya admin
$qx = mysqli_query($koneksi, "SELECT SUM(item_biaya_admin) AS totalnya ".
								"FROM pelanggan_pinjam");
$rowx = mysqli_fetch_assoc($qx);
$e_uang_kredit_dp = balikin($rowx['totalnya']);






//transaksi : kredit ANGSURAN 
$qx = mysqli_query($koneksi, "SELECT SUM(kredit_nominal_bayar) AS totalnya ".
								"FROM pelanggan_pinjam");
$rowx = mysqli_fetch_assoc($qx);
$e_uang_kredit_angsuran = balikin($rowx['totalnya']);






//transaksi : kredit ANGSURAN belum bayar 
$qx = mysqli_query($koneksi, "SELECT SUM(kredit_nominal_belum) AS totalnya ".
								"FROM pelanggan_pinjam");
$rowx = mysqli_fetch_assoc($qx);
$e_uang_kredit_belum = balikin($rowx['totalnya']);


















//dapatkan peringkat pelanggan
$qyuk = mysqli_query($koneksi, "SELECT * FROM m_pelanggan ".
									"ORDER BY nama ASC");
$ryuk = mysqli_fetch_assoc($qyuk);

do
	{
	//nilai
	$yuk_kd = balikin($ryuk['kd']);
	
	
	//detail
	$qyuk2 = mysqli_query($koneksi, "SELECT kd FROM pelanggan_pinjam ".
										"WHERE pelanggan_kd = '$yuk_kd'");
	$ryuk2 = mysqli_fetch_assoc($qyuk2);
	$yuk2_jml = mysqli_num_rows($qyuk2);
	

	
	//nominal
	$qyuk21 = mysqli_query($koneksi, "SELECT SUM(subtotal) AS totalnya ".
										"FROM pelanggan_pinjam ".
										"WHERE pelanggan_kd = '$yuk_kd'");
	$ryuk21 = mysqli_fetch_assoc($qyuk21);
	$yuk21_totalnya = balikin($ryuk21['totalnya']);
	

	
	
	
	//update kan
	mysqli_query($koneksi, "UPDATE m_pelanggan SET jml_transaksi = '$yuk2_jml', ".
								"jml_nominal = '$yuk21_totalnya' ".
								"WHERE kd = '$yuk_kd'");
	}
while ($ryuk = mysqli_fetch_assoc($qyuk));
?>







		<!-- Info boxes -->
      <div class="row">


        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fa fa-users"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">ANGGOTA</span>
              <span class="info-box-number"><?php echo $e_total_user;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->



        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fa fa-briefcase"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">ITEM PRODUK</span>
              <span class="info-box-number"><?php echo $e_total_item;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->





        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fa fa-money"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TABUNGAN : DEBET</span>
              <span class="info-box-number"><?php echo $e_tab_debet;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->






        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fa fa-money"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TABUNGAN : KREDIT</span>
              <span class="info-box-number"><?php echo $e_tab_kredit;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->







        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fa fa-tasks"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TRANSAKSI KREDIT</span>
              <span class="info-box-number"><?php echo $e_total_kredit;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->




        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fa fa-money"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">UANG KREDIT : BIAYA ADMIN</span>
              <span class="info-box-number"><?php echo xduit3($e_uang_kredit_dp);?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->



        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fa fa-money"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">UANG KREDIT : ANGSURAN</span>
              <span class="info-box-number"><?php echo xduit3($e_uang_kredit_angsuran);?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->



        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fa fa-money"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">UANG KREDIT : BELUM BAYAR</span>
              <span class="info-box-number"><?php echo xduit3($e_uang_kredit_belum);?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->




        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fa fa-calendar"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">LOGIN TERAKHIR</span>
              <span class="info-box-number"><?php echo $yuk_login_terakhir;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        





        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fa fa-calendar-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">ENTRI TERAKHIR</span>
              <span class="info-box-number"><?php echo $yuk_entri_terakhir;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        







                
      </div>
      <!-- /.row -->







				<script>
					$(function () {
					  'use strict'
					
					  var ticksStyle = {
					    fontColor: '#495057',
					    fontStyle: 'bold'
					  }
					
					  var mode      = 'index'
					  var intersect = true
					
					
					  var $visitorsChart = $('#visitors-chart')
					  var visitorsChart  = new Chart($visitorsChart, {
					    data   : {
					      labels  : [<?php echo $isi_data1;?>],
					      datasets: [{
					        type                : 'line',
					        data                : [<?php echo $isi_data2;?>],
					        backgroundColor     : 'transparent',
					        borderColor         : 'blue',
					        pointBorderColor    : 'blue',
					        pointBackgroundColor: 'blue',
					        fill                : false
					        // pointHoverBackgroundColor: '#007bff',
					        // pointHoverBorderColor    : '#007bff'
					      },
					        {
					          type                : 'line',
					          data                : [<?php echo $isi_data3;?>],
					          backgroundColor     : 'tansparent',
					          borderColor         : 'orange',
					          pointBorderColor    : 'orange',
					          pointBackgroundColor: 'orange',
					          fill                : false
					          // pointHoverBackgroundColor: '#ced4da',
					          // pointHoverBorderColor    : '#ced4da'
					        },
					        {
					          type                : 'line',
					          data                : [<?php echo $isi_data4;?>],
					          backgroundColor     : 'tansparent',
					          borderColor         : 'green',
					          pointBorderColor    : 'green',
					          pointBackgroundColor: 'green',
					          fill                : false
					          // pointHoverBackgroundColor: '#ced4da',
					          // pointHoverBorderColor    : '#ced4da'
					        }]
					    },
					    options: {
					      maintainAspectRatio: false,
					      tooltips           : {
					        mode     : mode,
					        intersect: intersect
					      },
					      hover              : {
					        mode     : mode,
					        intersect: intersect
					      },
					      legend             : {
					        display: false
					      },
					      scales             : {
					        yAxes: [{
					          // display: false,
					          gridLines: {
					            display      : true,
					            lineWidth    : '4px',
					            color        : 'rgba(0, 0, 0, .2)',
					            zeroLineColor: 'transparent'
					          },
					          ticks    : $.extend({
					            beginAtZero : true,
					            suggestedMax: 200
					          }, ticksStyle)
					        }],
					        xAxes: [{
					          display  : true,
					          gridLines: {
					            display: false
					          },
					          ticks    : ticksStyle
					        }]
					      }
					    }
					  })
					})
	
				</script>
	
	
	
	
	
	

		<!-- Info boxes -->
      <div class="row">
	
        <!-- /.col -->
        <div class="col-md-12">
	
	

	            <div class="card">
	              <div class="card-header border-transparent">
	                <h3 class="card-title">Grafik : Login, Entri, Transaksi</h3>
	
	                <div class="card-tools">
	                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
	                    <i class="fas fa-minus"></i>
	                  </button>
	                </div>
	              </div>
	              <div class="card-body">
	
	
	
	                <div class="position-relative mb-4">
	                  <canvas id="visitors-chart" height="200"></canvas>
	                </div>
	
	                <div class="d-flex flex-row justify-content-end">
	                  <span class="mr-2">
	                    <i class="fas fa-square text-blue"></i> Login
	                  </span>
	                  &nbsp;
	
	                  <span>
	                    <i class="fas fa-square text-orange"></i> Entri
	                  </span>
	                  &nbsp;
	
	                  <span>
	                    <i class="fas fa-square text-green"></i> Transaksi
	                  </span>
	                  

	                </div>
	
	
	                
	                
	              </div>
	            </div>
	
			</div>
			
		</div>
			            
	          

	





	
            
		<!-- Info boxes -->
      <div class="row">
	
        <!-- /.col -->
        <div class="col-md-6">
            
			<?php
			$limit = 10;
			$sqlcount = "SELECT * FROM pelanggan_pinjam ".
							"ORDER BY postdate DESC";

			//query
			$p = new Pager();
			$start = $p->findStart($limit);
			
			$sqlresult = $sqlcount;
			
			$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
			$pages = $p->findPages($count, $limit);
			$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
			$pagelist = $p->pageList($_GET['page'], $pages, $target);
			$data = mysqli_fetch_array($result);
			?>
			
			
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">PEMINJAMAN</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                    <tr>
                      <td align="center">POSTDATE</td>
                      <td align="center">ANGGOTA</td>
                      <td align="center">NOMINAL</td>
                    </tr>
                    </thead>
                    <tbody>
                    	
                    <?php
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
							$i_pel_nama = balikin($data['pelanggan_nama']);
							$i_subtotal = balikin($data['subtotal']);
							$i_postdate = balikin($data['postdate']);
							$i_jbayar = balikin($data['jenis_bayar']);

						
						

						
							echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
							echo '<td>'.$i_postdate.'</td>
							<td>'.$i_pel_nama.'</td>
							<td align="right">'.xduit3($i_subtotal).'</td>
							<td>'.$i_jbayar.'</td>
					        </tr>';
							}
						while ($data = mysqli_fetch_assoc($result));
						?>
						
						
                    </tbody>
                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>



              <div class="card-footer border-transparent">
					<a href="u/pinjam.php" class="btn btn-block btn-danger">SELENGKAPNYA >></a>
              </div>
              
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->






			<?php
			$limit = 10;
			$sqlcount = "SELECT * FROM pelanggan_pinjam_kredit ".
							"ORDER BY postdate DESC";

			//query
			$p = new Pager();
			$start = $p->findStart($limit);
			
			$sqlresult = $sqlcount;
			
			$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
			$pages = $p->findPages($count, $limit);
			$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
			$pagelist = $p->pageList($_GET['page'], $pages, $target);
			$data = mysqli_fetch_array($result);
			?>
			
			
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">HISTORY BAYAR ANGSURAN</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                    <tr>
                      <td align="center">POSTDATE</td>
                      <td align="center">ANGGOTA</td>
                      <td align="center">ANGSURAN KE-</td>
                      <td align="center">NOMINAL</td>
                    </tr>
                    </thead>
                    <tbody>
                    	
                    <?php
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
							$i_pel_nama = balikin($data['pelanggan_nama']);
							$i_subtotal = balikin($data['nominal']);
							$i_postdate = balikin($data['postdate']);
							$i_nourut = balikin($data['nourut']);
						
						

						
							echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
							echo '<td>'.$i_postdate.'</td>
							<td>'.$i_pel_nama.'</td>
							<td>'.$i_nourut.'</td>
							<td align="right">'.xduit3($i_subtotal).'</td>
					        </tr>';
							}
						while ($data = mysqli_fetch_assoc($result));
						?>
						
						
                    </tbody>
                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>



              <div class="card-footer border-transparent">
					<a href="u/lap_angsuran.php" class="btn btn-block btn-danger">SELENGKAPNYA >></a>
              </div>
              
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->




            
			<?php
			$limit = 10;
			$sqlcount = "SELECT * FROM pelanggan_pinjam ".
							"WHERE kredit_nominal_belum IS NOT NULL ".
							"AND kredit_nominal_belum > '0' ".
							"ORDER BY jabatan ASC, ".
							"nama ASC";

			//query
			$p = new Pager();
			$start = $p->findStart($limit);
			
			$sqlresult = $sqlcount;
			
			$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
			$pages = $p->findPages($count, $limit);
			$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
			$pagelist = $p->pageList($_GET['page'], $pages, $target);
			$data = mysqli_fetch_array($result);
			?>
			
			
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">BELUM LUNAS</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                    <tr>
                      <td align="center">ANGGOTA</td>
                      <td align="center">NOMINAL</td>
                    </tr>
                    </thead>
                    <tbody>
                    	
                    <?php
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
							$i_pel_nama = balikin($data['pelanggan_nama']);
							$i_subtotal = balikin($data['kredit_nominal_belum']);


						
							echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
							echo '<td>'.$i_pel_nama.'</td>
							<td align="right">'.xduit3($i_subtotal).'</td>
					        </tr>';
							}
						while ($data = mysqli_fetch_assoc($result));
						?>
						
						
                    </tbody>
                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>



              <div class="card-footer border-transparent">
					<a href="u/lap_belum_lunas.php" class="btn btn-block btn-danger">SELENGKAPNYA >></a>
              </div>
              
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->





		</div>
		
		

		        <!-- /.col -->
        <div class="col-md-6">
   
			<?php
			$limit = 10;
			$sqlcount = "SELECT * FROM m_pelanggan ".
							"ORDER BY postdate DESC";

			//query
			$p = new Pager();
			$start = $p->findStart($limit);
			
			$sqlresult = $sqlcount;
			
			$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
			$pages = $p->findPages($count, $limit);
			$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
			$pagelist = $p->pageList($_GET['page'], $pages, $target);
			$data = mysqli_fetch_array($result);
			?>
			
			
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">ANGGOTA BARU</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                    <tr>
                      <th>POSTDATE</th>
                      <th>NAMA</th>
                      <th>JABATAN</th>
                      <th>TELP.</th>
                    </tr>
                    </thead>
                    <tbody>
                    	
                    <?php
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
							$i_nama = balikin($data['nama']);
							$i_jabatan = balikin($data['jabatan']);
							$i_telp = balikin($data['telp']);
							$i_postdate = balikin($data['postdate']);
							
						


						
							echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
							echo '<td>'.$i_postdate.'</td>
							<td>'.$i_nama.'</td>
							<td>'.$i_jabatan.'</td>
							<td>'.$i_telp.'</td>
					        </tr>';
							}
						while ($data = mysqli_fetch_assoc($result));
						?>
						
						
                    </tbody>
                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>

              <div class="card-footer border-transparent">
					<a href="u/user.php" class="btn btn-block btn-danger">SELENGKAPNYA >></a>
              </div>
              
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->







   
			<?php
			$limit = 10;
			$sqlcount = "SELECT * FROM m_pelanggan ".
							"ORDER BY round(total_transaksi) DESC";

			//query
			$p = new Pager();
			$start = $p->findStart($limit);
			
			$sqlresult = $sqlcount;
			
			$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
			$pages = $p->findPages($count, $limit);
			$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
			$pagelist = $p->pageList($_GET['page'], $pages, $target);
			$data = mysqli_fetch_array($result);
			?>
			
			
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">PERINGKAT ANGGOTA</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                    <tr>
                      <th>NO</th>
                      <th>TRANSAKSI</th>
                      <th>ANGGOTA</th>
                      <th>NOMINAL</th>
                    </tr>
                    </thead>
                    <tbody>
                    	
                    <?php
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
					
							$i_no = $i_no + 1;
							$i_kd = nosql($data['kd']);
							$i_nama = nosql($data['nama']);
							$i_jabatan = nosql($data['jabatan']);
							$i_telp = nosql($data['telp']);
							$i_jml = nosql($data['total_transaksi']);
							$i_nominal = nosql($data['total_nominal']);


						
							echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
							echo '<td>'.$i_no.'.</td>
							<td>'.$i_jml.'</td>
							<td>
							'.$i_nama.'
							<br>
							Jabatan:'.$i_jabatan.'
							<br>
							Telp:'.$i_telp.'
							</td>
							<td align="right">'.xduit3($i_nominal).'</td>
					        </tr>';
							}
						while ($data = mysqli_fetch_assoc($result));
						?>
						
						
                    </tbody>
                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>


              <div class="card-footer border-transparent">
					<a href="u/lap_peringkat.php" class="btn btn-block btn-danger">SELENGKAPNYA >></a>
              </div>
              
              
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->



		</div>
		
		




		
		
		

		</div>
	</div>



            


		<!-- OPTIONAL SCRIPTS -->
		<script src="../template/adminlte3/plugins/chart.js/Chart.min.js"></script>
		




	
	<script language='javascript'>
	//membuat document jquery
	$(document).ready(function(){
	
	$.noConflict();

	});
	
	</script>
	






<?php
//isi
$isi = ob_get_contents();
ob_end_clean();

require("../inc/niltpl.php");

//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>