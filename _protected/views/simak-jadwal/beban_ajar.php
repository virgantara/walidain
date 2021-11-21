<?php

use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use app\models\SimakMasterprogramstudi;
use app\models\SimakMasterdosen;
use app\models\SimakJadwal;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SimakJadwalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rencana Beban Mengajar Dosen';
$this->params['breadcrumbs'][] = $this->title;

$tahun_id = !empty($_GET['tahun_id']) ? $_GET['tahun_id'] : null;
$dosen = !empty($_GET['dosen']) ? $_GET['dosen'] : '';
$nama_dosen = !empty($_GET['nama_dosen']) ? $_GET['nama_dosen'] : '';
// $listDosen = \app\models\SimakMasterdosen::find()->orderBy(['nama_dosen'=>SORT_ASC])->all();

$listProdi =ArrayHelper::map(SimakMasterprogramstudi::find()->all(),'kode_prodi','nama_prodi');

?>
<style type="text/css">
  .ui-autocomplete { z-index:2147483647; }

  .modal-dialog{
      top: 50%;
      margin-top: -250px; 
  }

</style>
<h3 class="page-title"><?=$this->title;?></h3>
<div class="row">
  <div class="col-md-6 col-lg-6 col-xs-12">
     <?php $form = ActiveForm::begin([
      'method' => 'GET',
      'action' => ['simak-jadwal/beban-ajar'],
      'options' => [
            'id' => 'form_validation',
      ]
    ]); ?>
     <div class="form-group">
          <label class="control-label ">Tahun Akademik</label>
          <?= Html::dropDownList('tahun_id',$tahun_id,\yii\helpers\ArrayHelper::map($listTahun,'tahun_id','nama_tahun'),['class'=>'form-control','id'=>'tahun_id']) ?>

          
          
      </div>
      
      <div class="form-group">
          <label class="control-label ">Prodi</label>
           <?= Select2::widget([
            'name' => 'prodi',
            'value' => !empty($_GET['prodi']) ? $_GET['prodi'] : '',
            'data' => $listProdi,
            'options'=>['id'=>'prodi_id','placeholder'=>Yii::t('app','- Pilih Prodi -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]); ?>

      </div>
      <div class="form-group">
          <label class="control-label ">Dosen</label>
          
          <?php
          echo DepDrop::widget([
              'name' => 'dosen',      
              'data' => [],
              'value' => !empty($_GET['dosen']) ? $_GET['dosen'] : '',
              'type'=>DepDrop::TYPE_SELECT2,
              'options'=>['id'=>'dosen'],
              'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
              'pluginOptions'=>[
                  'depends'=>['tahun_id','prodi_id'],
                  'initialize' => true,
                  'placeholder'=>'- Semua Dosen -',
                  'url'=>Url::to(['/simak-masterdosen/subdosen-temp'])
              ]
            ]);
          ?>
          
      </div>
      
      <div class="form-group clearfix">
        <button type="submit" class="btn btn-primary" name="btn-cari" value="1"><i class="fa fa-search"></i> Cari</button>
        
   
     
      </div>
    
     <?php ActiveForm::end(); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel">
     
      <div class="panel-body ">
          
            <?php 
            
            foreach($results as  $q => $res)
            {
              ?>


              <h3><?=$q+1;?>. <?=$res['nama']?></h3>
              <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th width="30%">MK</th>
              <th width="5%">SKS</th>
              <th width="10%">Kampus / Kelas</th>
              <th width="10%">Hari</th>
              <th width="10%">Jam</th>
              <th width="10%">Semester</th>
            </tr>
          </thead>
          <tbody>
              <?php
              $counter = 0;
              $total_sks = 0;

              $color = '';
              foreach($res['items'] as $jd)
              {

                if(empty($jd)) continue;
                $counter++;
                $matkul = \app\models\SimakMatakuliah::find()->where([
                    'kode_mk' => trim($jd->kode_mk),
                    'prodi' => $jd->prodi
                ])->one();
                $sks = !empty($jd->sks) ? $jd->sks : !empty($matkul) ? $matkul->sks_mk : 0;
                $total_sks += $sks;
             ?>
            
            <tr>
              <td><?=$counter;?></td>
              
              <td>[<?=$matkul->kode_mk?>] <?=$matkul->nama_mk;?></td>
              <td><?=$sks;?></td>
              <td><?=!empty($jd->kampus0) ? $jd->kampus0->nama_kampus.' - '.$jd->namaKelas : null;;?></td>
              <td><?=$jd->hari;?></td>

              <td><?=$jd->jam;?></td>
              <td><?=$jd->semester;?></td>
            </tr>
          <?php 
            } 

            // if($total_sks < 12 || $total_sks > 16)
            // {
              
            // }
            ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td><b><?=$total_sks;?></b></td>
                <td colspan="3"></td>
              </tr>
            </tfoot>
        </table>
        <hr>
            <?php
          }
            ?>
          
      </div>
    </div>
  </div>
</div>
<?php 

$this->registerJs(' 

', \yii\web\View::POS_READY);

?>