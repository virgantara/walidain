<?php 
$this->title = 'Rekap Hitung Pembayaran';

use app\helpers\MyHelper;
 ?>
Tanggal : <?=date('d M Y');?>
<div class="row">
    <div class="col-md-12">
        <h3>Daftar Mahasiswa </h3>
         <table class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Mahasiswa</th>
                    <th>NIM</th>
                    <th>Prodi</th>
                    <th>Status Aktif</th>
                    <th>Semester</th>
                    <th>Nilai DU</th>
                    <th>Nilai Minimal</th>
                    <th>Terbayar</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                
                foreach($results as $q => $m)
                {
                    
                ?>
                <tr>
                    <td><?=$q+1;?></td>
                    <td><?=$m->nim0->nama_mahasiswa;?></td>
                    <td><?=$m->nim0->nim_mhs;?></td>
                    <td><?=$m->nim0->kodeProdi->nama_prodi;?></td>
                    <td><?=$m->nim0->status_aktivitas;?></td>
                    <td><?=$m->nim0->semester;?></td>
                    <td class="text-right"><?=MyHelper::formatRupiah($m->nilai);?></td>
                    <td class="text-right"><?=MyHelper::formatRupiah($m->nilai_minimal);?></td>
                    <td class="text-right"><?=MyHelper::formatRupiah($m->terbayar);?></td>
                </tr>
                <?php 
                }
                
                ?>
            </tbody>

        </table>
    </div>
</div>
