<?php
$subNav = array(
	"Buku Besar ; buku-besar.php ; #509601;",
        "Neraca; neraca.php ; #509601;",
);
set_include_path("../");
include_once("inc/essentials.php");
include_once '../models/transaksi.php';
include_once '../inc/functions.php';
?>
<h1 class="margin-t-0">Buku Besar</h1>
<div class="input-parameter">
<table width="100%">
    <tr><td width="10%">Range Tanggal:</td><td><?= form_input('awal', date("d/m/Y"), 'id=awal size=10') ?> s . d <?= form_input('akhir', date("d/m/Y"), 'id=akhir size=10') ?></td></tr>
    <tr><td></td><td><?= form_button('Tampilkan', 'id=search') ?> <?= form_button('Reset', 'id=reset') ?> </td></tr>
</table>
</div>
<div id="result-info">
    
</div>