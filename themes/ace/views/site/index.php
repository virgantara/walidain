<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Tahun;
use yii\widgets\ActiveForm;
$this->title = Yii::t('app', Yii::$app->name);

\app\assets\HighchartAsset::register($this);
$tahun = !empty($_GET['tahun']) ? $_GET['tahun'] : '';
$nim = !empty($_GET['nim']) ? $_GET['nim'] : '';
$status_aktivitas = !empty($_GET['status_aktivitas']) ? $_GET['status_aktivitas'] : 'A';

// $list_kampus = ArrayHelper::map(\app\models\SimakKampus::find()->all(),'kode_kampus','nama_kampus');

$list_status = \app\helpers\MyHelper::getStatusAktivitas();

?>


<div class="row">
	<div class="col-sm-12">
		<h1>Selamat datang di Walidain, aplikasi monitoring mahasiswa Universitas Darussalam Gontor</h1>
	</div>
</div>
<?php 
if(!empty($list_anak)){
 ?>

<div class="panel">
  <div class="panel-body">
    <div class="row">
      <div class="col-sm-12 col-md-12 col-lg-3 col-xs-12">
         <?php $form = ActiveForm::begin([
          'method' => 'GET',
          'action' => ['site/index'],
          'options' => [
                'id' => 'form_validation',
          ]
        ]); ?>
        
            <div class="form-group">
              <label class="control-label ">Pilih Data Ananda</label>
             <?= Html::radioList('nim',$nim,\yii\helpers\ArrayHelper::map($list_anak,'nim_mhs','nama_mahasiswa'),['maxlength' => true,'prompt'=>'- Pilih Data Mahasiswa -','separator' => '<br>']) ?>
          </div>

          <div class="form-group clearfix">
            <button type="submit" class="btn btn-primary" name="btn-cari" value="1"><i class="fa fa-search"></i> Tampilkan</button>
            
          </div>
        
         <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</div>  
<?php } 
else{
?>	
<div class="alert alert-danger">
	Mohon maaf, sepertinya Bapak/Ibu perlu klaim data Ananda. Klik di <?=Html::a('sini',['customer/list']);?> untuk klaim
</div>
<?php 
}

?>


<?php 
if(!empty($mhs)){
 ?>

<div class="row">
	
	<div class="col-md-12">
		<div id="user-profile-2" class="user-profile">
			<div class="tabbable">
				<ul class="nav nav-tabs padding-18">
					<li class="active">
						<a data-toggle="tab" href="#home">
							<i class="green ace-icon fa fa-user bigger-120"></i>
							Profil
						</a>
					</li>

					<li>
						<a data-toggle="tab" href="#feed">
							<i class="orange ace-icon fa fa-users bigger-120"></i>
							Dosen Pembimbing
						</a>
					</li>

					
				</ul>

				<div class="tab-content no-border padding-24">
					<div id="home" class="tab-pane in active">
						<div class="row">
							<div class="col-xs-12 col-sm-3 center">
								<span class="profile-picture">
									<img src="<?=Url::to(['customer/foto','id'=>$mhs->id]);?>" border="1" width="200px" height="300px"></img>
								</span>

								<div class="space space-4"></div>

							</div><!-- /.col -->

							<div class="col-xs-12 col-sm-9">
								<h4 class="blue">
									<span class="middle"><?=$mhs->nama_mahasiswa;?></span>

									
								</h4>

								<div class="profile-user-info">
									<div class="profile-info-row">
										<div class="profile-info-name"> NIM </div>

										<div class="profile-info-value">
											<span><?=$mhs->nim_mhs;?></span>
											<input type="hidden" id="nim" value="<?=$mhs->nim_mhs;?>">
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name"> Alamat </div>
										<?php 
										
										 ?>
										<div class="profile-info-value">
											<i class="fa fa-map-marker light-orange bigger-110"></i>
											<span><?=$mhs->fullAlamat;?></span>
											
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name"> Angkatan </div>

										<div class="profile-info-value">
											<span><?=$mhs->tahun_masuk;?></span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> Semester </div>

										<div class="profile-info-value">
											<span><?=$mhs->semester;?></span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> IPK </div>

										<div class="profile-info-value">
											<span><?=$mhs->getIpk();?></span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> SKS Lulus </div>

										<div class="profile-info-value">
											<span><?=$mhs->SKSLulus;?></span>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name"> Status Aktif </div>

										<div class="profile-info-value">
											<span><?=$list_status[$mhs->status_aktivitas];?></span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> Prodi </div>

										<div class="profile-info-value">
											<span><?=$mhs->kodeProdi->nama_prodi;?></span>
										</div>
									</div>
									
									<div class="profile-info-row">
										<div class="profile-info-name"> Kamar/Asrama </div>

										<div class="profile-info-value">
											<?php 
											$kamar = !empty($mhs->kamar) ? 'Kamar '.$mhs->kamar->nama : '';    
							                $asrama = !empty($mhs->kamar) && !empty($mhs->kamar->asrama) ? $mhs->kamar->asrama->nama : '';
												 ?>
											<span><?=!empty($kamar) && !empty($asrama) ? $kamar.' - '.$asrama : '';?></span>
										</div>
									</div>
									
								</div>

								
							</div><!-- /.col -->
						</div><!-- /.row -->

						<div class="space-20"></div>

						<div class="row">
							<div class="col-xs-12 col-sm-6">
								<div class="widget-box transparent">
									<div class="widget-header widget-header-small">
										<h4 class="widget-title smaller">
											<i class="ace-icon fa fa-check-square-o bigger-110"></i>
											Catatan Penting
										</h4>
									</div>

									<div class="widget-body">
										<div class="widget-main">
											<p>
												<div id="display_tagihan" class="alert alert-danger">
										            <i class="fa fa-money"></i> Total pembayaran yang belum dilunasi: <h2 id="nominal_tagihan"></h2>
										          </div>
											</p>	
											<p>
			<?php 
			switch($mhs->is_eligible)
            {
              // case 0:
              // $label = 'danger';
              // $txt = "Mohon maaf, Anda belum bisa mengikuti wisuda";
              // break;
              case 1:
              $label = 'success';
              $txt = "Selamat, ".$mhs->nama_mahasiswa." sudah bisa mengikuti wisuda";
              ?>
              <div class="alert alert-<?=$label;?>"><?=$txt;?></div>
              <?php
              break;  
            }
            // }
              
              $krs = \app\models\SimakDatakrs::find()->where([
                'tahun_akademik' => $ta->tahun_id,
                'mahasiswa' => $mhs->nim_mhs,

              ])->all();

              $list_tercekal = [];
              $is_tercekal_akademik =false;
              foreach($krs as $k)
              {
                $hadir = \app\models\SimakAbsenHarian::find()->where(['kode_jadwal'=>$k->kode_jadwal,'mhs'=>$mhs->nim_mhs,'status_kehadiran'=>1])->count();

                $sakit = \app\models\SimakAbsenHarian::find()->where(['kode_jadwal'=>$k->kode_jadwal,'mhs'=>$mhs->nim_mhs,'status_kehadiran'=>2])->count();

                $izin = \app\models\SimakAbsenHarian::find()->where(['kode_jadwal'=>$k->kode_jadwal,'mhs'=>$mhs->nim_mhs,'status_kehadiran'=>3])->count();

                $remaining = $sakit + $izin >= 3 ? 3 : $sakit + $izin;

                $absens = $hadir + $remaining;

                $persen = round($absens / 14 * 100,2);

                $is_tercekal_akademik = $persen < 75;

                if($is_tercekal_akademik){

                  $list_tercekal[$k->kode_mk] = 'Kehadiran kelas ['.$k->kode_mk.'] '.$k->nama_mk.' - '.$persen.' % dari 75%';
                  // if($m->nim_mhs == '3920186110301'){
                  //   // print_r($k->kode_mk);
                  //   // echo ' ';
                  //   // print_r($persen);
                  // }
                }
              }

            $tahfidz = \app\models\SimakTahfidzNilai::find()->where([
                  'tahun_id' => $ta->tahun_id,
                  'nim'=>$mhs->nim_mhs
              ])->one();

              $is_tercekal_tahfidz = empty($tahfidz) || (!empty($tahfidz) && $tahfidz->nilai_angka <=2);
              $label_tercekal = [];

              if($is_tercekal_akademik)
                $label_tercekal[] = 'Akademik';

              if($is_tercekal_tahfidz)
                $label_tercekal[] = 'Tahfidz';

              $is_tercekal_adm = \app\models\SimakMastermahasiswa::isTercekalADM($mhs->nim_mhs);
              if($is_tercekal_adm)
                $label_tercekal[] = 'ADM';


              $subtotal = 0;
              foreach($indukKegiatan as $induk)
              {
                  foreach($induk->simakJenisKegiatans as $jk)
                  {
                      $km = \app\models\SimakKegiatanMahasiswa::find()->where([
                          'id_jenis_kegiatan' => $jk->id,
                          'nim' => $mhs->nim_mhs,
                          'tahun_akademik' => $ta->tahun_id,
                          'is_approved' => 1
                      ]);

                      $sub = $km->sum('nilai');
                      if($sub >= $jk->nilai_maximal)
                      {
                        $subtotal += $jk->nilai_maximal;
                      }

                      else{
                        $subtotal += $sub;
                      }
                  }
              }

              $is_tercekal_akpam = $subtotal < $ta->nilai_lulus_akpam;

              if($is_tercekal_akpam)
                $label_tercekal[] = 'AKPAM';

              $is_tercekal_akademik = count($list_tercekal) > 0;
              if($is_tercekal_akademik)
                $label_tercekal[] = '<br>'.implode('<br> ', $list_tercekal).'';

              if(count($label_tercekal) > 0){
                ?>
                <div class="alert alert-danger">
              <i class="fa fa-ban"></i> Oops, mohon maaf. <?=$mhs->nama_mahasiswa?> tercekal <?=implode(', ', $label_tercekal);?>
            </div>

                <?php 
              }

            ?>

											</p>
										</div>
									</div>
								</div>
							</div>

							<div class="col-xs-12 col-sm-6">
								<div class="widget-box transparent">
									<div class="widget-header widget-header-small header-color-blue2">
										<h4 class="widget-title smaller">
											<i class="ace-icon fa fa-lightbulb-o bigger-120"></i>
											Billing
										</h4>
									</div>

									<div class="widget-body">
										<div class="widget-main padding-16">
											<div class="profile-user-info">
												<div class="profile-info-row">
													<div class="profile-info-name"> Saldo </div>

													<div class="profile-info-value">
														<span style="font-size: x-large;font-family: Roboto,Arial,sans-serif;">Rp <?=\app\helpers\MyHelper::formatRupiah($mhs->saldo,2);?></span>
														
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name"> Virtual Account </div>

													<div class="profile-info-value">
														<span id="enrollment_code" style="font-size: x-large;font-family: Roboto,Arial,sans-serif;"><?=$mhs->va_code;?></span>
								<a style="font-size: x-large;font-family: Roboto,Arial,sans-serif;" href="javascript:void(0)" title="Click to Copy" id="btn-copy"><i class="fa fa-copy"></i></a>
														
													</div>
												</div>
											</div>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div><!-- /#home -->

					<div id="feed" class="tab-pane">
						<div class="profile-feed row">
							<div class="col-sm-6">
								<?php 

								$dosen_pa = \app\models\SimakMasterdosen::find()->where(['id'=>$mhs->nip_promotor])->one();
							
							if(!empty($dosen_pa)){
								 ?>
								<div class="profile-activity clearfix">

									<div>
										<a class="user" href="#"><?=$dosen_pa->nama_dosen;?> </a> sebagai dosen wali. Kontak: <?=$dosen_pa->no_hp_dosen;?>
										
									</div>

									
								</div>
							<?php } ?>
								<div class="profile-activity clearfix">

									<div>
										<a class="user" href="#"><?=$mhs->dosenPembimbing1;?> </a> sebagai dosen Pembimbing Utama
										
									</div>

									
								</div>
								<div class="profile-activity clearfix">

									<div>
										<a class="user" href="#"><?=$mhs->dosenPembimbing2;?> </a> sebagai dosen Pembimbing Pendamping
										
									</div>

									
								</div>
							</div><!-- /.col -->

							
						</div><!-- /.row -->

						<div class="space-12"></div>

						
					</div><!-- /#feed -->

					
					
				</div>
			</div>
		</div>
	</div>
	
</div>


<?php } ?>

<?php

// $list_kampus = json_encode($list_kampus);

$this->registerJs('


function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
}

$(document).on("click","#btn-copy",function(e){
    var copyText = $(this).prev();

    copyToClipboard(copyText)

    alert("Copied to clipboard");
})

$(document).on("click","#btn-download",function(e){
	e.preventDefault();
	var obj = new Object;
	obj.tahun = $("#tahun").val();
	obj.kampus = $("#kampus").val();
	obj.status_aktivitas = $("#status_aktivitas").val();
	obj.singkatan = $("#singkatan").val();
	$.ajax({
		type : \'POST\',
		url : \''.Url::to(['laporan/ajax-rincian-tagihan-export']).'\',
		data : {
			dataItem : obj
		},
		dataType: "json",
		beforeSend: function(){
			Swal.showLoading()
		},
		success : function(data){
			Swal.close()
			var $a = $("<a>");
		    $a.attr("href",data.file);
		    $("body").append($a);
		    $a.attr("download","file_pembayaran.xlsx");
		    $a[0].click();
		    $a.remove();
		},
	})
	
});

$(document).on("click","#btn-filter",function(e){
	e.preventDefault();

	fetchTahun($("#tahun").val(),$("#status_aktivitas").val(), $("#kampus").val(), function(err, res){
		generateChart(res);
		generatePie(res.total_terbayar,res.total_piutang);

	})
});

function fetchRincian(param){
	var obj = new Object;
	obj.tahun = $("#tahun").val();
	obj.kampus = $("#kampus").val();
	obj.status_aktivitas = $("#status_aktivitas").val();
	obj.singkatan = param.category;
	$("#singkatan").val(param.category);
	$.ajax({
		type : \'POST\',
		url : \''.Url::to(['site/ajax-rincian-tagihan']).'\',
		data : {
			dataItem : obj
		},
		beforeSend: function(){
			Swal.showLoading()
		},
		success : function(data){
			Swal.close()
			var hsl = $.parseJSON(data);

			$("#tabel_rincian > tbody").empty();

			var row = "";
			var total_terbayar = 0;
			var total_piutang = 0;
			var total_tagihan = 0;
			$.each(hsl, function(i, obj){
				row += "<tr>";
				row += "<td>"+eval(i+1)+"</td>";
				row += "<td>"+obj.nim_mhs+"</td>";
				row += "<td>"+obj.nama_mahasiswa+" / "+obj.semester+"</td>";
				row += "<td>"+obj.nama_kampus+"</td>";
				row += "<td>"+obj.singkatan+"</td>";
				row += "<td class=\'text-right\'>"+obj.tagihan.toLocaleString()+"</td>";
				row += "<td class=\'text-right\'>"+obj.terbayar.toLocaleString()+"</td>";
				row += "<td class=\'text-right\'>"+obj.piutang.toLocaleString()+"</td>";
				row += "</tr>";

				total_piutang += obj.piutang;
				total_terbayar += obj.terbayar;
				total_tagihan += obj.tagihan;
			});

			row += "<tr>";
			row += "<td class=\'text-right\' colspan=\'5\'> Total</td>";
			row += "<td class=\'text-right\'>"+total_tagihan.toLocaleString()+"</td>";
			row += "<td class=\'text-right\'>"+total_terbayar.toLocaleString()+"</td>";
			row += "<td class=\'text-right\'>"+total_piutang.toLocaleString()+"</td>";
			row += "</tr>";

			$("#tabel_rincian > tbody").append(row)
		},
	})
}

function fetchTahun(tahun, status_aktivitas, kampus, callback){
	var obj = new Object;
	obj.tahun = tahun;
	obj.kampus = kampus;
	obj.status_aktivitas = status_aktivitas;
	$.ajax({
		type : \'POST\',
		url : \''.Url::to(['site/ajax-data-tagihan']).'\',
		data : {
			dataItem : obj
		},
		beforeSend: function(){
			Swal.showLoading()
		},
		success : function(data){
			Swal.close()
			var hsl = $.parseJSON(data);

			callback(null, hsl)
		},
	})
}

function generatePie(terbayar, piutang){
	Highcharts.chart(\'containerPie\', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: \'pie\'
    },
    title: {
        text: \'Perbandingan Piutang Tagihan Mahasiswa\'
    },
    tooltip: {
        pointFormat: \'{series.name}: <b>{point.percentage:.1f}%</b>\'
    },
    accessibility: {
        point: {
            valueSuffix: \'%\'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: \'pointer\',
            dataLabels: {
                enabled: true,
                format: \'<b>{point.name}</b>: {point.percentage:.1f} %\'
            }
        }
    },
    series: [{
        name: \'Brands\',
        colorByPoint: true,
        data: [{
            name: \'Terbayar\',
            y: terbayar
        }, {
            name: \'Piutang\',
            y: piutang
        }]
    }]
});
              
}

function generateChart(tagihan){

    
    var dataLabels = [];
    var dataTagihan = [];
    var dataTerbayar = [];
    var dataPiutang = [];
    $.each(tagihan.rincian, function(i,obj){
        dataLabels[i] = obj.singkatan;
        dataTagihan[i] = obj.tagihan;
        dataTerbayar[i] = obj.terbayar;
        dataPiutang[i] = obj.piutang;
    });


    Highcharts.chart(\'containerTagihan\', {
    	chart: {
    		type : \'column\'
    	},
        title: {
            text: \'Perbandingan Tagihan, Piutang dan Pembayaran \'
        },


        yAxis: {
            title: {
                text: \'Nominal\'
            }
        },

        xAxis: {
            categories: dataLabels,
        },

        legend: {
            layout: \'vertical\',
            align: \'right\',
            verticalAlign: \'middle\'
        },

        plotOptions: {
            column: {
	            pointPadding: 0,
	            borderWidth: 0
	        },
	        series: {
	            cursor: \'pointer\',
	            point: {
	                events: {
	                    click: function () {
	                    	fetchRincian(this);

	                    }
	                }
	            }
	        }
        },

        series: [{
            name: \'Tagihan\',
            data: dataTagihan
        }, {
            name: \'Terbayar\',
            data: dataTerbayar
        }, {
            name: \'Piutang\',
            data: dataPiutang
        }],

        

    });
}


function getTagihanMahasiswa(callback){
  
  let nim = $("#nim").val()	
  var obj = new Object;
  obj.nim = nim;
  $.ajax({
    type : \'POST\',
    url : \''.Url::to(['api/ajax-data-tagihan']).'\',
    data : {
      dataItem : obj
    },
    success : function(data){
      var hsl = $.parseJSON(data);

      callback(null, hsl)
    },
  })
}


getTagihanMahasiswa(function(err, res){
    var total = res.total_piutang

    
    $("#nominal_tagihan").html("Rp " +total.toLocaleString("id-ID"))
  })

', \yii\web\View::POS_READY);

?>