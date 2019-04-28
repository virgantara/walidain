 <?php 
if(!empty($export)){
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="laporan_swot.xls"');
            header('Cache-Control: max-age=0');

?>
    <table>
        <tr>
            <td colspan="8" style="text-align: center">
                
                <h1>Laporan SWOT</h1>
            </td>
        </tr>
    </table>
    <?php
}
?>
 <table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Unit</th>
            <th>Stregth</th>
            <th>Weakness</th>
            <th>Opportunity</th>
            <th>Threat</th>
            
        </tr>
    </thead>
    <tbody>
        <?php 
        $i=0;

        foreach($list as $m)
        {
            $i++;
        ?>
        <tr>
            <td><?=($i);?></td>
            <td><?=$m['unit'];?></td>
            <td><?=$m['strength'];?></td>
            <td><?=$m['weakness'];?></td>
            <td><?=$m['opportunity'];?></td>
            <td><?=$m['threat'];?></td>
           
        </tr>
        <?php 
            }
        
        ?>
    </tbody>
</table>