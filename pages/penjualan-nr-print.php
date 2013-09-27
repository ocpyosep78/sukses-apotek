<?php
include_once '../models/transaksi.php';
include_once '../inc/functions.php';

?>
<link rel="stylesheet" href="../themes/theme_default/theme-print.css" />
<script type="text/javascript">
function cetak() {
    window.print();
    setTimeout(function(){ window.close();},300);
}
</script>
<body onload="cetak()">
<?php header_surat(); ?>
<h1>
    LAPORAN PENJUALAN NON RESEP <br /> TANGGAL <?= $_GET['awal'] ?> s . d <?= $_GET['akhir'] ?>
</h1>
<table cellspacing="0" width="100%" class="list-data-print">
<thead>
    <tr class="italic">
        <th width="3%">No.</th>
        <th width="5%">Tanggal</th>
        <th width="15%">Customer</th>
        <th width="5%">Diskon Rp.</th>
        <th width="5%">Diskon %</th>
        <th width="5%">PPN %</th>
        <th width="5%">Tuslah RP.</th>
        <th width="5%">Embalage RP.</th>
        <th width="5%">Total</th>
        <th width="5%">Terbayar</th>
        <?php if ($_GET['status'] === 'detail') { ?>
        <th width="20%">Nama Barang</th>
        <th width="5%">Kemasan</th>
        <th width="5%">Jumlah</th>
        <th width="5%">Harga</th>
        <th width="10%">Subtotal</th>
        <?php } ?>
    </tr>
</thead>
<tbody>
    <?php
    $param = array(
        'id' => '',
        'awal' => date2mysql($_GET['awal']),
        'akhir' => date2mysql($_GET['akhir']),
        'laporan' => '',
        'pasien' => $_GET['pasien'],
        'dokter' => $_GET['dokter'],
        'status' => $_GET['status']
    );
    $penjualan = penjualan_nr_load_data($param);
    $list_data = $penjualan['data'];
    $total_data= $penjualan['total'];
    $id = "";
    $no = 1;
    
    $total_nota = 0;
    $total_terbayar = 0;
    foreach ($list_data as $key => $data) { 
        //$str = $data->id.'#'.$data->id_resep.'#'.$data->customer.'#'.$data->id_customer;
        if ($data->total > $data->terbayar) {
            $alert="warning";
        } else {
            $alert="";
        }
        ?>
        <tr id="<?= $data->id ?>" class="detail <?= ($id !== $data->id)?'odd':NULL ?> <?= $alert ?>">
            <td align="center"><?= ($id !== $data->id)?($no):NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?datetimefmysql($data->waktu):NULL ?></td>
            <td><?= ($id !== $data->id)?$data->customer:NULL ?></td>
            <td align="right"><?= ($id !== $data->id)?rupiah($data->diskon_rupiah):NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?$data->diskon_persen:NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?$data->ppn:NULL ?></td>
            <td align="right"><?= ($id !== $data->id)?rupiah($data->tuslah):NULL ?></td>
            <td align="right"><?= ($id !== $data->id)?rupiah($data->embalage):NULL ?></td>
            <td align="right"><?= ($id !== $data->id)?rupiah($data->total):NULL ?></td>
            <td align="right"><?= ($id !== $data->id)?rupiah($data->terbayar):NULL ?></td>
            <?php if ($_GET['status'] === 'detail') { ?>
            <td><?= $data->nama_barang ?></td>
            <td align="center"><?= $data->kemasan ?></td>
            <td align="center"><?= $data->qty ?></td>
            <td align="right"><?= rupiah($data->harga_jual) ?></td>
            <td align="right"><?= rupiah($data->subtotal) ?></td>
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
            <td colspan="8" align="right">TOTAL</td><td align="right"><b><?= rupiah($total_nota) ?></b></td><td align="right"><b><?= rupiah($total_terbayar) ?></b></td>
            <?php if ($_GET['status'] === 'detail') { ?>
            <td colspan="4"></td><td align="right"><b><?= rupiah($total_nota) ?></b></td>
            <?php } ?>
        </tr>
</tbody>
</table>
</body>