<?php
include_once '../models/transaksi.php';
include_once '../models/masterdata.php';
include_once '../inc/functions.php';
?>
<script type="text/javascript">
function get_result_laba_rugi(page) {
    var awal = $('#awal').val();
    var akhir= $('#akhir').val();
    $.ajax({
        url: 'pages/laba-rugi-list.php?awal='+awal+'&akhir='+akhir,
        cache: false,
        success: function(data) {
            $('#result-info').html(data);
        }
    });
}

function paging(page, tab, search) {
    get_result_statistik(page, search);
}
$(function() {
    $('#awal,#akhir').datepicker({
        changeYear: true,
        changeMonth: true
    });
    $('#search').button().click(function() {
        get_result_laba_rugi();
    });
    $('#reset').button().click(function() {
        $('#awal,#akhir').val('<?= date("d/m/Y") ?>');
        $('#barang,#id_barang, select').val('');
        $('input:radio').removeAttr('checked');
        $('#result-info').html('');
    });
});
</script>
<h1 class="margin-t-0">Laporan Laba & Rugi</h1>
<div class="input-parameter">
<table width="100%">
    <tr><td width="10%">Range Tanggal:</td><td><?= form_input('awal', date("d/m/Y"), 'id=awal size=10') ?> s . d <?= form_input('akhir', date("d/m/Y"), 'id=akhir size=10') ?></td></tr>
    <tr><td></td><td><?= form_button('Tampilkan', 'id=search') ?> <?= form_button('Reset', 'id=reset') ?> </td></tr>
</table>
</div>
<div id="result-info">
    
</div>