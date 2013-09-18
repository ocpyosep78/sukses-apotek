<?php
include_once '../models/transaksi.php';
include_once '../models/cetak-transaksi.php';
include_once '../inc/functions.php';
?>
<link rel="stylesheet" href="../themes/theme_default/theme-print.css" />
<script type="text/javascript">
window.onunload = refreshParent;
function refreshParent() {
    //window.opener.location.reload();
}
function cetak() {  		
    window.print();
    setTimeout(function(){ window.close();},300);
}
</script>
<body onload="cetak();">
<?php
$load = etiket_load_data($_GET['id_resep'],$_GET['no_r']);
foreach ($load as $rows);
?>

<div style="padding: 3px;">
    No. R/: <?= $rows->r_no ?><br/>
    <?php
        foreach ($load as $data) { ?>
                <?= $data->nama_barang ?><br/>
    <?php }
    ?>
                Aturan Pakai:<br/><textarea name="aturan_pakai" id="aturan_pakai" style="width: 100%"></textarea>
</div>
</body>