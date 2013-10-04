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
        <th width="5%">Tanggal</th>
        <th width="2%">Shift</th>
        <th width="15%">Karyawan</th>
        <th width="5%">Uang Awal</th>
        <th width="7%">Pendapatan <br/>Resep</th>
        <th width="7%">Pendapatan <br/>Non. Resep</th>
        <th width="5%">Total</th>
        <th width="7%">Uang Fisik</th>
        <th width="5%">Selisih</th>
        <th width="5%">Tutup Shift</th>
        <th width="15%">Keterangan</th>
        <th width="2%">#</th>
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
        'limit' => $limit,
        'start' => $offset,
        'id' => $_GET['id_set_kas_awal']
    );
    $arus_kas = set_kas_awal_load_data($param);
    $list_data = $arus_kas['data'];
    $total_data= $arus_kas['total'];
    $p_resep=0;
    $p_nresep = 0;
    foreach ($list_data as $key => $data) {
        $str = $data->id."#".$data->uang_awal."#".$data->total_real;
        $uang_masuk = $data->uang_awal+$data->pendapatan_resep+$data->pendapatan_non_resep;
        ?>
        <tr class="<?= ($key%2==0)?'even':'odd' ?>">
            <td align="center"><?= (++$key+$offset) ?></td>
            <td align="center"><?= datefmysql($data->tanggal) ?></td>
            <td align="center"><?= $data->shift ?></td>
            <td><?= $data->karyawan ?></td>
            <td align="right"><?= rupiah($data->uang_awal) ?></td>
            <td align="right"><?= rupiah($data->pendapatan_resep) ?></td>
            <td align="right"><?= rupiah($data->pendapatan_non_resep) ?></td>
            <td align="right"><?= rupiah($uang_masuk) ?></td>
            <td align="right"><?= rupiah($data->total_real) ?></td>
            <td align="right"><?= rupiah($data->total_real-$uang_masuk) ?></td>
            <td align="center"><?= ($data->is_closed === '0')?'BELUM':'SUDAH' ?></td>
            <td><?= $data->keterangan ?></td>
            <td class="aksi" align="center">
                <a class='printing' onclick="cetak_kas_awal('<?= $data->id ?>','<?= $data->shift ?>','<?= $data->tanggal ?>');" title="Klik untuk cetak">&nbsp;</a>
                <a class='edition' onclick="edit_kas_awal('<?= $str ?>');" title="Klik untuk edit">&nbsp;</a>
                <a class='closing' onclick="tutup_kas_awal('<?= $str ?>');" title="Klik untuk tutup kas shift">&nbsp;</a>
            </td>
        </tr>
    <?php 
    $p_resep = $p_resep+$data->pendapatan_resep;
    $p_nresep= $p_nresep+$data->pendapatan_non_resep;
    }
    ?>
</tbody>
<!--<tfoot>
    <tr>
        <td colspan="5" align="right"><b>TOTAL</b></td>
        <td align="right"><b><?= rupiah($p_resep) ?></b></td>
        <td align="right"><b><?= rupiah($p_nresep) ?></b></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
    </tr>
</tfoot>-->
</table>
<?= paging_ajax($total_data, $limit, $page, '1', $_GET['search']) ?>