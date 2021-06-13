<?php 

use yii\helpers\Html;

$this->title = 'Rekap Hitung Pembayaran Daftar Ulang (DU)';
?>
Tanggal : <?=date('d M Y');?>
<h3><?=$this->title;?></h3>
<?php


foreach($list_jenjang as $q=> $v):

 ?>


<div class="row">
    <div class="col-md-12">

        <h3><?=$q;?>. Program <?=$v;?></h3>
         <table class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <th width="5%" >No</th>
                    <th width="45%" >Prodi</th>
                    <th width="10%" class="text-center">Lunas</th>
                    <th width="10%" class="text-center">Cicilan < 50%</th>
                    <th width="10%" class="text-center">Cicilan 50%</th>
                    <th width="10%" class="text-center">Belum Bayar</th>
                    <th width="10%" class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i=0;
                $total_all= 0;
                $total_lunas = 0;
                $total_kurang = 0;
                $total_minimal = 0;
                $total_belum = 0;
                foreach($results[$q] as $m)
                {
                    $i++;
                    $total = $m['total_lunas'] + $m['total_kurang_50'] + $m['total_minimal'] + $m['total_belum_bayar'];

                    $total_lunas += $m['total_lunas'];
                    $total_kurang += $m['total_kurang_50'];
                    $total_minimal += $m['total_minimal'];
                    $total_belum += $m['total_belum_bayar'];
                    $total_all+= $total;
                ?>
                <tr>
                    <td><?=($i);?></td>
                    <td><?=$m['prodi'];?></td>
                    <td class="text-center"><?=Html::a($m['total_lunas'],['tagihan/list-mhs','prodi'=>$m['kode_prodi'],'status'=>1],['target'=>'_blank']);?></td>
                    <td class="text-center"><?=Html::a($m['total_kurang_50'],['tagihan/list-mhs','prodi'=>$m['kode_prodi'],'status'=>2],['target'=>'_blank']);?></td>
                    <td class="text-center"><?=Html::a($m['total_minimal'],['tagihan/list-mhs','prodi'=>$m['kode_prodi'],'status'=>3],['target'=>'_blank']);?></td>
                    <td class="text-center"><?=Html::a($m['total_belum_bayar'],['tagihan/list-mhs','prodi'=>$m['kode_prodi'],'status'=>4],['target'=>'_blank']);?></td>
                    <td class="text-center"><?=Html::a($total,['tagihan/list-mhs','prodi'=>$m['kode_prodi']],['target'=>'_blank']);?></td>
                </tr>
                <?php 
                    }
                
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-right" colspan="2">Total</th>
                    <th class="text-center"><?=$total_lunas;?></th>
                    <th class="text-center"><?=$total_kurang;?></th>
                    <th class="text-center"><?=$total_minimal;?></th>
                    <th class="text-center"><?=$total_belum;?></th>
                    <th class="text-center"><?=$total_all;?></th>
                </tr>
                <tr>
                    <th class="text-right" colspan="2">Persentase</th>
                    <th class="text-center"><?=round($total_lunas / $total_all * 100,2);?> %</th>
                    <th class="text-center"><?=round($total_kurang / $total_all * 100,2);?> %</th>
                    <th class="text-center"><?=round($total_minimal / $total_all * 100,2);?> %</th>
                    <th class="text-center"><?=round($total_belum / $total_all * 100,2);?> %</th>
                    <th class="text-center"><?=round($total_all / $total_all * 100,2);?> %</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php 
endforeach;
 ?>