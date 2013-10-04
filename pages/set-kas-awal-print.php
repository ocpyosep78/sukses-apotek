<?php
include_once '../models/transaksi.php';
include_once '../inc/functions.php';
?>
<link rel="stylesheet" href="../themes/theme_default/theme-print.css" />
<script type="text/javascript">
function cetak() {
    window.print();
    setTimeout(function(){ window.close();},300);
    //SCETAK.innerHTML = '<br /><input onClick=\'cetak()\' type=\'submit\' name=\'Submit\' value=\'Cetak\' class=\'tombol\'>';
}
</script>
<body onload="cetak();">
<?php header_surat(); ?>
<h1>
    LAPORAN SHIFT TANGGAL <?= datefmysql($_GET['tanggal']) ?>, SHIFT Ke-<?= $_GET['shift_ke'] ?>
</h1>
    <table>
    <?php
    $param = array(
        'id' => $_GET['id']
    );
    $arus_kas = set_kas_awal_load_data($param);
    $list_data = $arus_kas['data'];
    $p_resep=0;
    $p_nresep = 0;
    foreach ($list_data as $key => $data) {
        $uang_masuk = $data->uang_awal+$data->pendapatan_resep+$data->pendapatan_non_resep;
        ?>
            <tr><td>Karyawan: </td><td><?= $data->karyawan ?></td></tr>
            <tr><td>Uang Awal:</td><td>Rp. <?= rupiah($data->uang_awal) ?></td></tr>
            <tr><td>Pendapatan Resep:</td><td>Rp. <?= rupiah($data->pendapatan_resep) ?></td></tr>
            <tr><td>Pendapatan Non Resep:</td><td>Rp. <?= rupiah($data->pendapatan_non_resep) ?></td></tr>
            <tr><td>Total:</td><td>Rp. <?= rupiah($uang_masuk) ?></td></tr>
            <tr><td>Uang Fisik:</td><td>Rp. <?= rupiah($data->total_real) ?></td></tr>
            <tr><td>Selisih:</td><td>Rp. <?= rupiah($data->total_real-$uang_masuk) ?></td></tr>
            <tr><td>Keterangan:</td><td><?= $data->keterangan ?></td></tr>
    <?php 
    $p_resep = $p_resep+$data->pendapatan_resep;
    $p_nresep= $p_nresep+$data->pendapatan_non_resep;
    }
    ?>
            </table>
</body>