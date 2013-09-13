<?php
include_once '../models/transaksi.php';
include_once '../inc/functions.php';
?>
<script type="text/javascript">
$(function() {
    $( document ).tooltip();
});
</script>
<table cellspacing="0" width="100%" class="list-data">
<thead>
    <tr class="italic">
        <th width="5%">No.</th>
        <th width="10%">Waktu</th>
        <th width="10%">Transaksi</th>
        <th width="10%">Awal</th>
        <th width="20%">Masuk</th>
        <th width="10%">Keluar</th>
        <th width="10%">Sisa</th>
    </tr>
</thead>
<tbody>
    <?php
    $param = array(
        'awal' => date2mysql($_GET['awal']),
        'akhir' => date2mysql($_GET['akhir']),
        'faktur' => $_GET['faktur'],
        'id_supplier' => $_GET['id_supplier']
    );
    $arus_kas = arus_kas_load_data($param);
    $list_data = $arus_kas['data'];
    //$total_data= $penerimaan['total'];
    foreach ($list_data as $key => $data) { ?>
        <tr class="<?= ($key%2==0)?'even':'odd' ?>">
            <td align="center"><?= (++$key) ?></td>
            <td align="center"><?= datefmysql($data->waktu) ?></td>
            <td align="center"><?= $data->transaksi ?></td>
            <td></td>
            <td align="center"><?= rupiah($data->masuk) ?></td>
            <td align="center"><?= rupiah($data->keluar) ?></td>
            <td align="center"></td>
            
        </tr>
    <?php }
    ?>
</tbody>
</table>