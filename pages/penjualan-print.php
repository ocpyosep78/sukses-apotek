<?php
include_once '../models/transaksi.php';
include_once '../inc/functions.php';
header_surat();
?>
<link rel="stylesheet" href="../themes/theme_default/theme-print.css" />
<script type="text/javascript">
function cetak() {
    window.print();
    setTimeout(function(){ window.close();},300);
}
</script>
<body onload="cetak();">
<h1>
    LAPORAN PENJUALAN <br /> TANGGAL <?= $_GET['awal'] ?> s . d <?= $_GET['akhir'] ?>
</h1>
<table cellspacing="0" width="100%" class="list-data-print">
<thead>
    <tr class="italic">
        <th width="3%">No.</th>
        <th width="5%">Tanggal</th>
        <th width="5%">No. Resep</th>
        <th width="15%">Pasien</th>
        <th width="15%">Dokter</th>
        <th width="5%">Diskon <br/>Rp.</th>
        <th width="5%">Diskon <br/>%</th>
        <th width="5%">PPN %</th>
        <th width="5%">Tuslah <br/>RP.</th>
        <th width="5%">Embalage RP.</th>
        <th width="5%">Total</th>
        <?php if ($_GET['status'] === 'detail') { ?>
        <th width="20%">Nama Barang</th>
        <th width="5%">Kemasan</th>
        <th width="5%">Jumlah</th>
        <th width="5%">Harga</th>
        <th width="10%">Subtotal</th>
        <?php } else { ?>
        <th width="5%">Terbayar</th>
        <?php } ?>
    </tr>
</thead>
<tbody>
    <?php
    
    
    $param = array(
        'id' => '',
        'limit' => '',
        'start' => '',
        'laporan' => '',
        'awal' => date2mysql($_GET['awal']),
        'akhir' => date2mysql($_GET['akhir']),
        'pasien' => $_GET['pasien'],
        'dokter' => $_GET['dokter'],
        'laporan' => $_GET['status']
    );
    $penjualan = penjualan_load_data($param);
    $list_data = $penjualan['data'];
    $id = "";
    $no = 1;
    $alert = "";
    $total_nota = 0;
    $total_terbayar = 0;
    foreach ($list_data as $key => $data) { 
        //$str = $data->id.'#'.$data->id_resep.'#'.$data->customer.'#'.$data->id_customer;
        if ($data->total > $data->terbayar) {
            $alert="warning";
        }
        ?>
        <tr id="<?= $data->id ?>" class="detail <?= ($id !== $data->id)?'odd':NULL ?> <?= $alert ?>">
            <td align="center"><?= ($id !== $data->id)?$no:NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?datetimefmysql($data->waktu):NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?$data->id_resep:NULL ?></td>
            <td><?= ($id !== $data->id)?$data->customer:NULL ?></td>
            <td><?= ($id !== $data->id)?$data->dokter:NULL ?></td>
            <td align="right"><?= ($id !== $data->id)?rupiah($data->diskon_rupiah):NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?$data->diskon_persen:NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?$data->ppn:NULL ?></td>
            <td align="right"><?= ($id !== $data->id)?rupiah($data->tuslah):NULL ?></td>
            <td align="right"><?= ($id !== $data->id)?rupiah($data->embalage):NULL ?></td>
            <td align="right"><?= ($id !== $data->id)?rupiah($data->total):NULL ?></td>
            <?php if ($_GET['status'] === 'detail') { ?>
            <td><?= $data->nama_barang ?></td>
            <td align="center"><?= $data->kemasan ?></td>
            <td align="center"><?= $data->qty ?></td>
            <td align="right"><?= rupiah($data->harga_jual) ?></td>
            <td align="right"><?= rupiah($data->subtotal) ?></td>
            <?php } else { ?>
            <td align="right"><?= ($id !== $data->id)?rupiah($data->terbayar):NULL ?></td>
            <?php } ?>
            
        </tr>
    <?php 
    if ($id !== $data->id) {
        $no++;
        $total_nota = $total_nota+$data->total;
        $total_terbayar = $total_terbayar+$data->terbayar;
    }
    $id = $data->id;
    }
    ?>
        <tr>
            <td colspan="10" align="right">TOTAL</td><td align="right"><b><?= rupiah($total_nota) ?></b></td>
            <?php if ($_GET['status'] !== 'detail') { ?>
            <td align="right"><b><?= rupiah($total_terbayar) ?></b></td>
            <?php } else { ?>
            <td colspan="4"></td><td align="right"><b><?= rupiah($total_nota) ?></b></td>
            <?php } ?>
        </tr>
</tbody>
</table>
</body>