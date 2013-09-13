<?php
include_once '../models/transaksi.php';
include_once '../models/masterdata.php';
include_once '../inc/functions.php';

$apt = apotek_atribute();
$attr= penjualan_load_data_barang($_GET['id']);
foreach ($attr as $rows);
?>
<title>Nota</title>
<link rel="stylesheet" href="../themes/theme_default/theme-print.css" />
<script type="text/javascript">
window.onunload = refreshParent;
function refreshParent() {
    window.opener.location.reload();
}
function cetak() {  		
    window.print();
    setTimeout(function(){ window.close();},300);
}
</script>
<body onload="cetak();">
<div class="layout-print-struk">
    <table style="border-bottom: 1px solid #000;" width="100%">
        <tr><td align="center" style="text-transform: uppercase; font-size: 12px;"><?= $apt->nama ?></td> </tr>
        <tr><td align="center" style="font-size: 12px;"><?= $apt->alamat ?></td> </tr>
        <tr><td align="center" style="font-size: 12px;">Telp. <?= $apt->telp ?>,  Fax. <?= $apt->fax ?>, Email <?= $apt->email ?></td> </tr>
    </table>
    <table width="100%" style="border-bottom: 1px solid #000;">
        <tr><td width="40%">Nomor:</td><td width="60%"><?= $_GET['id'] ?></td></tr>
        <tr><td>Tanggal:</td><td><?= datetimefmysql($rows->waktu) ?></td></tr>
        <tr><td>Pelanggan:</td><td style="white-space: nowrap"><?= $rows->pelanggan ?></td></tr>
    </table>
    <table width="100%" style="border-bottom: 1px solid #000;">
        <tr>
            <th>Nama Barang</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
        <?php 
        $total_brg = 0;
        foreach ($attr as $key => $data) { 
            $total_brg = $total_brg + ($data->harga_jual*$data->qty);
            ?>
        <tr>
            <td><?= $data->nama.' '.$data->kekuatan.' '.$data->satuan ?></td>
            <td align="center"><?= $data->qty ?></td>
            <td align="right"><?= rupiah($data->harga_jual) ?></td>
            <td align="right"><?= rupiah($data->harga_jual*$data->qty) ?></td>
        </tr>
        <?php } ?>
    </table>
    <?php
    $ppn = ($total_brg*($rows->ppn/100));
    $tusem = $rows->tuslah+$rows->embalage;
    $total= $total_brg+$ppn+$tusem;
    $biaya_apoteker = 0;
    ?>
    <table width="100%">
        <tr><td>Subtotal:</td><td align="right"><?= rupiah($total_brg) ?></td></tr>
        <tr><td>Diskon:</td><td align="right"><?= rupiah($rows->diskon_rupiah) ?></td></tr>
        <tr><td>PPN <?= $rows->ppn ?> %:</td><td align="right"><?= rupiah($ppn) ?></td></tr>
        <tr><td>Tuslah & Embalage:</td><td align="right"><?= rupiah($tusem) ?></td></tr>
        <?php if ($rows->id_resep !== NULL) { 
        $biaya_apt = mysql_fetch_object(mysql_query("select sum(nominal) as total from resep_r where id_resep = '".$rows->id_resep."'"));    
        $biaya_apoteker = $biaya_apt->total;
        ?>
        <tr><td>Biaya Apoteker:</td><td align="right"><?= rupiah($biaya_apt->total) ?></td></tr>
        <?php } ?>
        <tr><td>Total:</td><td align="right"><?= rupiah($total+$biaya_apoteker) ?></td></tr>
    </table>
    <br/>
    <center style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc;">
        TERIMA KASIH, SEMOGA LEKAS SEMBUH
    </center>
</div>
</body>