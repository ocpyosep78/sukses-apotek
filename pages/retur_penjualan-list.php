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
        <th width="5%">No. Retur</th>
        <th width="20%">Nama Supplier</th>
        <th width="10%">Tanggal</th>
        <th width="20%">Nama Barang</th>
        <th width="5%">Kemasan</th>
        <th width="5%">Expired</th>
        <th width="5%">Jumlah</th>
        <th width="5%">#</th>
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
        'id' => $_GET['id_retur_penjualan'],
        'limit' => $limit,
        'start' => $offset,
        'search' => $_GET['search']
    );
    $retur_penjualan = retur_penjualan_load_data($param);
    $list_data = $retur_penjualan['data'];
    $total_data= $retur_penjualan['total'];
    $nama = "";
    $no = 1;
    foreach ($list_data as $key => $data) { ?>
        <tr class="<?= ($key%2==0)?'even':'odd' ?>">
            <td align="center"><?= ($data->id_retur_penjualan !== $nama)?($no+$offset):NULL ?></td>
            <td align="center"><?= ($data->id_retur_penjualan !== $nama)?$data->id_retur_penjualan:NULL ?></td>
            <td><?= ($data->id_retur_penjualan !== $nama)?$data->supplier:NULL ?></td>
            <td align="center"><?= datefmysql($data->tanggal) ?></td>
            <td><?= $data->barang.' '.$data->kekuatan.' '.$data->satuan ?></td>
            <td align="center"><?= $data->kemasan ?></td>
            <td align="center"><?= datefmysql($data->expired) ?></td>
            <td align="center"><?= $data->jumlah ?></td>
            <td class='aksi' align='center'>
                <!--<a class='edition' onclick="edit_retur_penjualan('<?= $str ?>');" title="Klik untuk edit retur_penjualan">&nbsp;</a>-->
                <a class='deletion' onclick="delete_retur_penjualan('<?= $data->id ?>','<?= $page ?>');" title="Klik untuk hapus">&nbsp;</a>
            </td>
        </tr>
    <?php 
    if ($data->id_retur_penjualan !== $nama) {
        $no++;
    }
    $nama = $data->id_retur_penjualan;
    }
    ?>
</tbody>
</table>