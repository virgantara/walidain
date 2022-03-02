<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data Ananda';
$this->params['breadcrumbs'][] = $this->title;
?>
 <h3><?= Html::encode($this->title) ?></h3>

<style>
    
.ui-autocomplete {

    position: absolute;
 top: 0;
 left: 0;

    cursor: default;
    z-index:9050!important;
}
</style>

<div class="customer-index">

    
    <?=Html::a('<i class="fa fa-plus"></i> Klaim Data Ananda',['javascript:void(0)'],['class' => 'btn btn btn-success','id'=>
'btn-add']);?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?php 
    $gridColumns = [
    [
        'class'=>'kartik\grid\SerialColumn',
        'contentOptions'=>['class'=>'kartik-sheet-style'],
        'width'=>'36px',
        'pageSummary'=>'Total',
        'pageSummaryOptions' => ['colspan' => 6],
        'header'=>'',
        'headerOptions'=>['class'=>'kartik-sheet-style']
    ],
        'nim_mhs',
        
        'nama_mahasiswa',
        'tgl_lahir',
        [
            'attribute' => 'jenis_kelamin',
            'format' => 'raw',
            'filter' => ['L'=>'L','P'=>'P'],
            'value' => function($data){
                return $data->jenis_kelamin;
            }
        ],
        [
            'attribute'=>'kampus',
            'filter' => \app\helpers\MyHelper::getKampusList(),
            'value' => function ($data) {
                return !empty($data->kampus0) ? $data->kampus0->nama_kampus : null;
            },
            
        ],
        
        //'kode_prodi',
        [
            'attribute' => 'kode_prodi',
            'label' => 'Prodi',
            'format' => 'raw',
            'filter'=>\yii\helpers\ArrayHelper::map(\app\models\SimakMasterprogramstudi::getProdiList(),'kode_prodi','nama_prodi'),
            'value'=>function($model,$url){

                
                return $model->kodeProdi->nama_prodi;
                
            },
        ],
        
        'semester',
        [
            'header' => 'Nama Wali',
            'contentOptions' => ['width' => '10%'],
            'format' => 'raw',
            'value' => function($data){
                return $data->namaWali;
            }
        ],
        [
            'header' => 'Alamat Wali',
            'contentOptions' => ['width' => '20%'],
            'format' => 'raw',
            'value' => function($data){
                return $data->alamatWali;
            }
        ],
        [
            // 'header' => 'kamar',
            'label' => 'Kamar',
            'format' => 'raw',
            'contentOptions' => ['width' => '10%'],
            'value'=>function($model,$url){
                $kamar = !empty($model->kamar) ? 'Kamar '.$model->kamar->nama : '';    
                $asrama = !empty($model->kamar) && !empty($model->kamar->asrama) ? $model->kamar->asrama->nama : '';

                return !empty($kamar) && !empty($asrama) ? $kamar.' - '.$asrama : '';
            },
        ],
        
        [
            'attribute'=>'status_aktivitas',
            'filter' => \app\helpers\MyHelper::getStatusAktivitas(),
            'value' => function ($data) {
                $tmp = \app\helpers\MyHelper::getStatusAktivitas();
                return $tmp[$data->status_aktivitas];
            },
            
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {hapus}',
            'buttons' => [
            
                'hapus' => function ($url, $model){
                  return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'javascript:void(0)', [
                            'title' => Yii::t('app', 'Batalkan'),
                            'data-pjax' =>0,
                            'data-item' => $model->nim_mhs,
                            'class' => 'link-delete'
                  ]);
                },
                
              ],
            
        ]
    ]
    ?>
<p>
<div class="row">

    <div class="col-md-12">
        
        
        <?php 
        // Renders a export dropdown menu
        echo \kartik\export\ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'clearBuffers' => true, //optional
        ]);
        ?>
        
    </div>
</div>
</p>
     <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'responsiveWrap' => false,
        'summary' => '',
        'containerOptions' => ['style' => 'overflow: auto'], 
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'containerOptions' => ['style'=>'overflow: auto'], 
        'beforeHeader'=>[
            [
                'columns'=>[
                    ['content'=> $this->title, 'options'=>['colspan'=>16, 'class'=>'text-center warning']], //cuma satu 
                ], 
                'options'=>['class'=>'skip-export'] 
            ]
        ],
        
        'toolbar' =>  [
            // '{export}', 

           '{toggleData}' //uncoment untuk menghidupkan button menampilkan semua data..
        ],
        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
    // set export properties
        'export' => [
            'fontAwesome' => true
        ],
        'pjax' => true,
        'pjaxSettings' =>[
            'neverTimeout'=>true,
            'options'=>[
                'id'=>'pjax-container-mahasiswa',
            ]
        ],  
        'bordered' => true,
        'striped' => true,
        // 'condensed' => false,
        // 'responsive' => false,
        'hover' => true,
        // 'floatHeader' => true,
        // 'showPageSummary' => true, //true untuk menjumlahkan nilai di suatu kolom, kebetulan pada contoh tidak ada angka.
        'panel' => [
            'type' => GridView::TYPE_PRIMARY
        ],
    ]); ?>
</div>
<?php
    yii\bootstrap\Modal::begin([
        'id' =>'pModal',
        'header' => '<h2>Pencarian Data Mahasiswa</h2>'
    ]);

?>
<div class="row">
   <div class="col-md-12">
        <div class="panel">
            
            <div class="panel-body">
                <p >
                    Ketik NIM atau Nama Ananda<br>
                    <?=Html::textInput('nim','',['id'=>'nama_mahasiswa','class'=>'form-control','placeholder'=>'..Ketik NIM / Nama Mahasiswa..']);?>
                    <input type="hidden" id="nim"><br>
                    <button type="button" id="btn-tambah-mahasiswa" class="btn btn-primary"><i class="fa fa-plus"></i> Peserta</button>
                       
                </p>
            </div>
        </div>
    </div>
</div>

<?php
    yii\bootstrap\Modal::end();

?>
<?php
$this->registerJs("
    $('#btn-add').click(function(e){
       e.preventDefault();      
       $('#pModal').modal('show')
                  .find('#pModal');  
    });

"
);
?>



<?php 

$this->registerJs(' 


$(document).on(\'click\',\'.link-delete\',function(e){
    e.preventDefault();
    var nim = $(this).data("item");
    var obj = new Object;
    obj.mahasiswa = nim;
    
    Swal.fire({
      title: \'Konfirmasi Pembatalan Data Ananda! \',
      text: "Anda yakin akan membatalkan klaim data Ananda? ",
      showCancelButton: true,
      confirmButtonColor: \'#d33\',
      cancelButtonColor: \'#3085d6\',
      confirmButtonText: \'Ya, batalkan klaim!\',
      cancelButtonText: \'Batal!\',
    }).then((result) => {
      if (result.value) {
        obj.email = result.value
        $.ajax({
            type : "POST",
            url: "'.\yii\helpers\Url::to(["simak-mahasiswa-ortu/ajax-remove"]).'",
            async : true,
            data: {
                dataPost: obj
            },
            error : function(e){
                Swal.close()
                console.log(e.responseText)
            },
            beforeSend: function(){
                Swal.fire({
                    title : "Please wait",
                    html: "Deleting data...",
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    
                })
            },
            success: function (data) {
                Swal.close()
                var hasil = $.parseJSON(data)
                if(hasil.code == 200){
                    Swal.fire({
                        title: \'Okay!\',
                        icon: \'success\',
                        text: hasil.message
                    });
                    
                    $.pjax.reload({container: "#pjax-container-mahasiswa"})
                }

                else{
                    Swal.fire({
                        title: \'Oops!\',
                        icon: \'error\',
                        text: hasil.message
                    })
                }
            }
        })

      }
    });
    
});


$(document).on("click","#btn-tambah-mahasiswa",function(e){
    var obj = new Object;

    obj.mahasiswa = $("#nim").val();
    var ajax_url= "'.Url::to(['simak-mahasiswa-ortu/ajax-update']).'";
    $.ajax({
        type: "POST",
        url: ajax_url,
        data: {
            dataPost : obj
        },
        async: true,
        error : function(e){
            Swal.close()
            console.log(e.responseText)
        },
        beforeSend: function(){
            Swal.showLoading()
        },
        success: function (data) {
            Swal.close()
            var hasil = $.parseJSON(data)
            if(hasil.code==200){
                Swal.fire({
                  icon: \'success\',
                  title: \'Yeay\',

                  text: hasil.message
                })
                
            }

            else{
                Swal.fire({
                  icon: \'error\',
                  title: \'Oops...\',
                  text: hasil.message,
                })
            }

            $(\'#nim\').val("");
            $(\'#nama_mahasiswa\').val("");
            $.pjax.reload({container: "#pjax-container-mahasiswa"})
        }
    })
    
})

$(document).bind("keyup.autocomplete",function(){
    $(\'#nama_mahasiswa\').autocomplete({
      minLength:1,
      select:function(event, ui){
       
        $(\'#nim\').val(ui.item.nim);
                
      },
      
      focus: function (event, ui) {
        $(\'#nim\').val(ui.item.nim);
       
      },
      source:function(request, response) {
        $.ajax({
                url: "'.Url::to(['customer/ajax-cari-mahasiswa']).'",
                dataType: "json",
                data: {
                    term: request.term,
                    
                },
                error : function(e){
                  $("#loading").hide();
                  console.log(e)
                },
                beforeSend: function(){
                  $("#loading").show();
                },
                success: function (data) {
                  $("#loading").hide();
                    response(data);
                }
            })
        },
       
    }); 

});


', \yii\web\View::POS_READY);

?>