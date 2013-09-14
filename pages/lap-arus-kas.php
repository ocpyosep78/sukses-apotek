<?php
include_once '../models/transaksi.php';
include_once '../inc/functions.php';
$jenis_transaksi = get_jenis_transaksi();
$jenis_laporan   = array('Harian','Bulanan','Tahunan');
?>
<script type="text/javascript">

</script>
<h1 class="margin-t-0">Lap. Arus Kas</h1>
<div class="input-parameter">
<table width="100%">
    <tr><td>Jenis Laporan:</td><td><select name="jenis"><?php foreach ($jenis_laporan as $data) { ?><option value="<?= $data ?>"><?= $data ?></option> <?php } ?></select></td></tr>
    <tr id="jenis"><td width="10%">Range Tanggal:</td><td><?= form_input('awal', date("d/m/Y"), 'id=awal size=10') ?> s . d <?= form_input('akhir', date("d/m/Y"), 'id=akhir size=10') ?></td></tr>
    <tr><td>Nama Transaksi:</td><td><select name="transaksi" id="transaksi"><?php foreach ($jenis_transaksi as $data) { ?><option value="<?= $data ?>"><?= $data ?></option> <?php } ?></select></td></tr>
    <tr><td></td><td><?= form_button('Tampilkan', 'id=search') ?> <?= form_button('Reset', 'id=reset') ?> <?= form_button('Cetak', 'id=cetak') ?></td></tr>
</table>
</div>
<div id="result-info">
    
</div>