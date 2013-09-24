<?php
include_once '../models/transaksi.php';
include_once '../inc/functions.php';
?>
<table cellspacing="0" width="100%" class="list-data">
<thead>
<tr class="italic">
    <thead>
        <tr class="italic">
            <th width="3%">No.</th>
            <th width="40%">Nama Barang</th>
            <th width="5%">Jumlah</th>
            <th width="5%">Percentage</th>
        </tr>
    </thead>
</tr>
</thead>
<tbody>
    <?php 
    $limit = 10;
    $page  = $_GET['page'];
    if ($_GET['page'] === '') {
        $page = 1;
        $offset = 0;
    } else {
        $offset = ($page-1)*$limit;
    }
    
    $param = array(
        'id' => $_GET['id'],
        'limit' => $limit,
        'start' => $offset,
        'awal' => date2mysql($_GET['awal']),
        'akhir' => date2mysql($_GET['akhir']),
        'perundangan' => $_GET['perundangan'],
        'sediaan' => $_GET['sediaan'],
        'golongan' => $_GET['golongan'],
        'formularium' => $_GET['formularium'],
        'admr' => $_GET['admr']
    );
    $list_data = load_data_statistik($param);
    $master_statistik = $list_data['data'];
    $total = $list_data['total'];
    foreach ($total as $ttl);
    $ttl_barang = 0;
    $ttl_percen = 0;
    foreach ($master_statistik as $key => $data) { 
        ?>
        <tr class="<?= ($key%2==0)?'odd':'even' ?>">
            <td align="center"><?= ++$key ?></td> 
            <td><?= $data->nama_barang ?></td> 
            <td align="center"><?= $data->jumlah ?></td>
            <td align="center"><?= (($data->jumlah/$ttl->jumlah)*100) ?> %</td> 
        </tr>
    <?php 
    $ttl_barang = $ttl_barang+$data->jumlah;
    $ttl_percen = $ttl_percen+(($data->jumlah/$ttl->jumlah)*100);
    } ?>
</tbody>
<tfoot>
    <tr>
        <td colspan="2" align="right">TOTAL</td>
        <td align="center"><?= $ttl_barang ?></td>
        <td align="center"><?= $ttl_percen ?></td>
    </tr>
</tfoot>
</table>