<?php
include_once '../models/transaksi.php';
include_once '../inc/functions.php';
?>
<script type="text/javascript">
$(function() {
    /*$('.detail').on('mouseover',function() {
        $.ajax({
            url: 'pages/tooltip-detail-transaksi.php',
            data: 'id='+$(this).attr('id'),
            cache: false,
            success: function(msg) {
                $('.list-data tbody tr.detail').attr('title',msg);
                $( document ).tooltip();
            }
        });
    });*/
});
</script>
<table cellspacing="0" width="100%" class="list-data">
<thead>
    <tr class="italic">
        <th width="3%">No.</th>
        <th width="4%">Tanggal</th>
        <th width="10%">Pasien</th>
        <th width="10%">Subjektif</th>
        <th width="5%">Suhu <br/>( <sup style="font-size: 9px; text-transform: none;">o</sup> C )</th>
        <th width="5%">Tek. <br/>Darah (mmHg)</th>
        <th width="5%">Resp. Rate <br/>(x/menit)</th>
        <th width="5%">Nadi <br/>(x/menit)</th>
        <th width="5%">GDS (mg/dL)</th>
        <th width="5%">Angka<br/>Kolosterol (mg/dL)</th>
        <th width="5%">Asam Urat (mg/dL)</th>
<!--        <th width="5%">Assesment</th>
        <th width="5%">Goal<br/>Terapi</th>
        <th width="5%">Saran Non<br/>Farmakoterapi</th>-->
        <th width="10%">Saran <br/>Pengobatan</th>
        <th width="3%">Jml</th>
        <th width="10%">Keterangan</th>
        <th width="4%">#</th>
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
        'id' => $_GET['id_pemeriksaan'],
        'limit' => $limit,
        'start' => $offset,
        'search' => $_GET['search']
    );
    $pemeriksaan = pemeriksaan_load_data($param);
    $list_data = $pemeriksaan['data'];
    $total_data= $pemeriksaan['total'];
    $id = "";
    $no = 1;
    foreach ($list_data as $key => $data) { 
        
        ?>
        <tr valign="top" id="<?= $data->id ?>" class="detail <?= ($id !== $data->id)?'odd':NULL ?>">
            <td align="center"><?= ($id !== $data->id)?($no+$offset):NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?datefmysql($data->tanggal):NULL ?></td>
            <td title="<img src='img/pemeriksaan/' width='200px' />"><?= ($id !== $data->id)?$data->pasien:NULL ?></td>
            <td><?= ($id !== $data->id)?$data->subjektif:NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?$data->suhu_badan:NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?$data->tek_darah:NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?$data->res_rate:NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?$data->nadi:NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?$data->gds:NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?$data->angka_kolesterol:NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?$data->asam_urat:NULL ?></td>
            <!--<td><?= ($id !== $data->id)?$data->assesment:NULL ?></td>
            <td><?= ($id !== $data->id)?$data->goal:NULL ?></td>
            <td><?= ($id !== $data->id)?$data->saran_non_farmakoterapi:NULL ?></td>-->
            <td><?= $data->nama_barang ?></td>
            <td align="center"><?= $data->jumlah ?></td>
            <td><?= $data->keterangan ?></td>
            <td class='aksi' align='center'>
                <?php if ($id !== $data->id) { ?>
                    <a class='jual-link' onclick="penjualan('<?= $data->id ?>','<?= $data->id_pasien ?>','<?= $data->pasien ?>');" title="Klik untuk penjualan">&nbsp;</a>
                    <a class='edition' onclick="edit_pemeriksaan('<?= $data->id ?>');" title="Klik untuk edit">&nbsp;</a>
                    <a class='deletion' onclick="delete_pemeriksaan('<?= $data->id ?>','<?= $page ?>');" title="Klik untuk hapus">&nbsp;</a>
                <?php } ?>
            </td>
        </tr>
    <?php 
    if ($id !== $data->id) {
        $no++;
    }
    $id = $data->id;
    }
    ?>
</tbody>
</table>
<?= paging_ajax($total_data, $limit, $page, '1', $_GET['search']) ?>