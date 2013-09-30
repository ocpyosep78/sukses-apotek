<?php
include_once '../models/transaksi.php';
include_once '../inc/functions.php';
?>
<script type="text/javascript">
    function hitung_penyesuaian(i) {
        
        var sisa    = parseInt($('#sisa'+i).html());
        var fisik   = parseInt($('#stok_fisik'+i).val());
        if (!isNaN(fisik)) {
            var penyesuaian = fisik - sisa;
            $('#penyesuaian'+i).val(penyesuaian);
        } else {
            $('#penyesuaian'+i).val('');
        }
    }
    
    
    $(function() {
        $('#sesuaian').click(function() {
            $('#save_koreksi').submit();
        });
        $('#save_koreksi').on('submit', function() {
            $.ajax({
                type: 'POST',
                url: 'models/update-transaksi.php?method=save_koreksi_stok',
                data: $(this).serialize(),
                cache: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status === true) {
                        alert_dinamic('Update stok berhasil dilakukan !');
                        var value = $('#search').val();
                        load_data_koreksi_stok('',value,'');
                    }
                }
            });
            return false;
        });
    });
</script>
<form id="save_koreksi">
<table cellspacing="0" width="100%" class="list-data">
<thead>
<tr class="italic">
    <th width="5%">No.</th>
    <th width="65%">Nama Barang</th>
<!--    <th width="10%">No. Batch</th>
    <th width="10%">ED</th>
    <th width="10%">Masuk</th>
    <th width="10%">Keluar</th>-->
    <th width="10%">Sisa</th>
    <th width="10%">Stok Fisik</th>
    <th width="10%">Penyesuaian</th>
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
        'id' => $_GET['id_koreksi_stok'],
        'limit' => $limit,
        'start' => $offset,
        'search' => $_GET['search']
    );
    $list_data = load_data_stok_opname($param);
    $master_stok_opname = $list_data['data'];
    $total_data = $list_data['total'];
    foreach ($master_stok_opname as $key => $data) { 
        $str = "";
        ?>
    <tr class="<?= ($key%2==0)?'even':'odd' ?>">
        <td align="center"><?= (++$key+$offset) ?></td>
        <td><?= $data->nama.' '.$data->kekuatan.' '.$data->satuan_kekuatan ?></td>
<!--        <td align="center"><?= $data->nobatch ?></td>
        <td align="center"><?= datefmysql($data->ed) ?></td>
        <td align="center"><?= $data->masuk ?></td>
        <td align="center"><?= $data->keluar ?></td>-->
        <td align="center" id="sisa<?= $key ?>"><?= $data->sisa ?></td>
        <td><?= form_input('stok_fisik[]', NULL, 'onkeyup=hitung_penyesuaian("'.$key.'") id=stok_fisik'.$key) ?></td>
        <td><?= form_input('penyesuaian[]', NULL, 'readonly id=penyesuaian'.$key) ?><?= form_hidden('id_barang[]', $data->id_barang, 'id=id_barang'.$key) ?></td>
        <!--<td class='aksi' align='center'>
            <a class='edition' onclick="edit_koreksi_stok('<?= $str ?>');" title="Klik untuk edit stok_opname">&nbsp;</a>
            <a class='deletion' onclick="delete_koreksi_stok('<?= $data->id ?>', '<?= $page ?>');" title="Klik untuk hapus">&nbsp;</a>
        </td>-->
    </tr>
    <?php } ?>
    
</tbody>
</table>
<?= paging_ajax($total_data, $limit, $page, '1', $_GET['search']) ?>
<?= form_button('Sesuaikan', 'id=sesuaian style="float: right;"') ?>
</form>