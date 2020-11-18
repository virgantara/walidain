<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Tahun;
$this->title = Yii::t('app', Yii::$app->name);

\app\assets\HighchartAsset::register($this);
$tahun = !empty($_GET['tahun']) ? $_GET['tahun'] : '';
$status_aktivitas = !empty($_GET['status_aktivitas']) ? $_GET['status_aktivitas'] : 'A';

$list_kampus = ArrayHelper::map(\app\models\SimakKampus::find()->all(),'kode_kampus','nama_kampus');
?>
<div class="alert alert-block alert-success">
    <button type="button" class="close" data-dismiss="alert">
        <i class="ace-icon fa fa-times"></i>
    </button>

    <i class="ace-icon fa fa-check green"></i>

    Welcome to
    <strong class="green">
        <?=Yii::$app->name;?>
        <small>(v1.4)</small>
    </strong>,

</div>
<div class="row">
	<div class="col-sm-12">
		<?=Html::dropDownList('tahun',$tahun,ArrayHelper::map(Tahun::find()->orderBy(['id'=>SORT_DESC])->all(),'id','nama'),['id'=>'tahun','prompt'=>'- Pilih Tahun -']);?>

		<?=Html::dropDownList('tahun',$tahun,$list_kampus,['id'=>'kampus','prompt'=>'- Pilih Kelas -']);?>

		<?=Html::dropDownList('status_aktivitas',$status_aktivitas,['A'=>'Aktif','N'=>'Non-Aktif','K'=>'Keluar','C'=>'Cuti','L'=>'Lulus','D'=>'DO'],['id'=>'status_aktivitas','prompt'=>'- Pilih Status -']);?>

		<button id="btn-filter" type="button" class="btn btn-primary">Show</button>
	</div>
</div>
<div class="row">
	<div class="col-sm-8">
		<div id="containerTagihan"></div>
	</div>
	<div class="col-sm-4">
		<div id="containerPie"></div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		
			<div class="widget-box transparent">
				<div class="widget-header widget-header-flat">
					<h4 class="widget-title lighter">
						<i class="ace-icon fa fa-star orange"></i>
						Data Pembayaran Mahasiswa
					</h4>

					<div class="widget-toolbar">
						<input type="hidden" id="singkatan"/>
						<a href="javascript:void(0)" class="btn btn-sm btn-success" title="download data ini" id="btn-download">
							<i class="ace-icon fa fa-download"></i> Download
						</a>
					</div>
				</div>

				<div class="widget-body">
					<div class="widget-main no-padding">
						<table class="table table-bordered table-striped" id="tabel_rincian">
							<thead class="thin-border-bottom">
								<tr>
									<th>No</th>
									<th>NIM</th>
									<th>Nama / Semester </th>
									<th >Kelas</th>
									<th >Prodi</th>
									<th >Tagihan</th>
									<th >Terbayar</th>
									<th >Sisa Tagihan</th>
								</tr>
							</thead>

							<tbody>
								
							</tbody>
						</table>
					</div><!-- /.widget-main -->
				</div><!-- /.widget-body -->
			</div><!-- /.widget-box -->
									
	</div>
</div>


<?php

$list_kampus = json_encode($list_kampus);

$this->registerJs('

var list_kampus = '.$list_kampus.';

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
		success : function(data){
			
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
		success : function(data){
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
		success : function(data){
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



', \yii\web\View::POS_READY);

?>