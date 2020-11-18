<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\helpers\MyHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model app\models\SyaratPencekalan */
/* @var $form yii\widgets\ActiveForm */

$kampus = !empty($_GET['kampus']) ? $_GET['kampus'] : '';
$tahun = !empty($_GET['SyaratPencekalan']['tahun_id']) ? $_GET['SyaratPencekalan']['tahun_id'] : '';
?>

<div class="syarat-pencekalan-form">
	<div class="row">
		<div class="col-lg-6 col-sm-12 col-lg-offset-3">
    <?php $form = ActiveForm::begin([
		'action' => ['syarat-pencekalan/create'],
        'method' => 'GET',
    ]); ?>

    <?= $form->field($model, 'tahun_id')->dropDownList(ArrayHelper::map(\app\models\Tahun::find()->orderBy(['id'=>SORT_DESC])->limit(10)->all(), 'id', function($data){
        return $data->id.' - '.$data->nama;
      }),['id'=>'tahun_id','prompt'=>'- Pilih Tahun -']) ?>

	<div class="form-group">
        <label class="control-label no-padding-right" for="form-field-1"> Kelas</label>
          <?=Html::dropDownList('kampus',$kampus,[],['id'=>'kampus','class'=>'form-control']);?>
        
    </div>
    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-search"></i> Search', ['class' => 'btn btn-primary','name'=>'btn-search','value'=>'1']) ?>
    </div>

    <?php ActiveForm::end(); ?>
		</div>
	</div>
	<?php $form = ActiveForm::begin([
		'action' => ['syarat-pencekalan/create'],
    ]); ?>	

    <?=$form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);?>

    <?= $form->field($model, 'tahun_id')->hiddenInput()->label(false); ?>
    <?=Html::hiddenInput('kampus',$kampus,['id'=>'kampus','class'=>'form-control']);?>
	<div class="row">
		<div class="col-lg-6 col-sm-12 col-lg-offset-3">
			<div class="table-responsive">
		  		<table class="table table-bordered">
		  			<thead>
		  				<tr>
		  					<th>No</th>
		  					<th>Nama Komponen</th>
		  					<th>Nilai Tagihan</th>
                            <th>Minimal Pembayaran</th>
		  					<th>Nilai Minimal Syarat</th>
		  				</tr>
		  			</thead>
		  			<tbody>
		  				<?php 
		  				foreach($results as $q => $v)
		  				{
		  					$nilai_minimal = $v->biaya_awal;
		  				?>
		  				<tr>
		  					<td><?=$q+1;?></td>
		  					<td><?=$v->nama;?></td>
		  					<td class="text-right"><?=MyHelper::formatRupiah($v->biaya_awal);?>
                            <td class="text-right"><?=MyHelper::formatRupiah($v->biaya_minimal);?>
		  					<td class="text-right"><?=Html::textInput('nilai_minimal_'.$v->id.'_'.$tahun,$nilai_minimal,[]);?></td>
		  				</tr>
		  				<?php 
		  				}
		  				?>
		  			</tbody>
		  		</table>
		  		<?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success','name'=>'btn-simpan','value'=>'1']) ?>
		  	</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>


<?php

$script = "


function getKomponen(id){
    $.ajax({
        type : 'POST',
        url : '".Url::to(['komponen-biaya/ajax-get-komponen'])."',
        data : 'id='+id,
        beforeSend : function(){
            $('#loading').show();
        },
        error : function(err){
            console.log(err);
            $('#loading').hide();
        },
        success : function(data){

            var data = $.parseJSON(data);
            
            $('#loading').hide();  
            $('#nilai_tagihan').val(data.b);
            $('#nilai_minimal').val(data.m);
        }

    });
}


function getListKampus(){
    $.ajax({
        type : 'POST',
        url : '".Url::to(['/api/list-kampus'])."',
        beforeSend : function(){
            $('#loading').show();
        },
        error : function(err){
            console.log(err);
            $('#loading').hide();
        },
        success : function(data){

            var data = $.parseJSON(data);
            
            $('#loading').hide();  
            $('#kampus').empty();
             var row = '<option value=\"\">- Semua Kelas -</option>';
                   
            $.each(data.values,function(i, obj){
                row += '<option value=\"'+obj.kode_kampus+'\">'+obj.kode_kampus+' - '+obj.nama_kampus+'</option>';
                
            });

           
            $('#kampus').append(row);
            $('#kampus').val('".$kampus."');
        }

    });
}
getListKampus();


	$('#tahun_id').change(function(){
        var id = $(this).val();

        getKomponen(id);
    });

    $('#komponen').change(function(){
        var id = $(this).val();

        getKomponen(id);
    });

";
$this->registerJs(
    $script,
    \yii\web\View::POS_READY
);
// $this->registerJs($script);
?>