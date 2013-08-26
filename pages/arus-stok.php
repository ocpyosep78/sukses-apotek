<?php
include_once '../models/transaksi.php';
include_once '../inc/functions.php';
?>
<script type="text/javascript">
function get_result_arus_stok(page) {
    var awal = $('#awal').val();
    var akhir= $('#akhir').val();
    var id   = $('#id_barang').val();
    var pg   = (page === undefined)?'':page;
    $.ajax({
        url: 'pages/arus-stok-list.php?awal='+awal+'&akhir='+akhir+'&id='+id+'&page='+pg,
        cache: false,
        success: function(data) {
            $('#result-info').html(data);
        }
    });
}

function paging(page, tab, search) {
    get_result_arus_stok(page, search);
}
$(function() {
    $('#awal,#akhir').datepicker({
        changeYear: true,
        changeMonth: true
    });
    $('#search').button().click(function() {
        get_result_arus_stok();
    });
    $('#reset').button().click(function() {
        $('#awal,#akhir').val('<?= date("d/m/Y") ?>');
        $('#barang,#id_barang').val('');
        $('#result-info').html('');
    });
    $('#cetak').button().click(function() {
        var awal = $('#awal').val();
        var akhir= $('#akhir').val();
        var id   = $('#id_barang').val();
        window.open('pages/arus-stok-print.php?awal='+awal+'&akhir='+akhir+'&id='+id, 'Stok', 'width=800px, height=350px, resizable=1, scrollable=1');
    });
    var lebar = $('#barang').width();
    $('#barang').autocomplete("models/autocomplete.php?method=barang",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].nama // nama field yang dicari
                };
            }
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.nama_barang+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0
    }).result(
    function(event,data,formated){
        $(this).val(data.nama_barang);
        $('#id_barang').val(data.id);
    });
});
</script>
<h1 class="margin-t-0">Arus Stok</h1>
<div class="input-parameter">
<table width="100%">
    <tr><td width="10%">Range Tanggal:</td><td><?= form_input('awal', date("d/m/Y"), 'id=awal size=10') ?> s . d <?= form_input('akhir', date("d/m/Y"), 'id=akhir size=10') ?></td></tr>
    <tr><td>Nama Barang:</td><td><?= form_input('barang', NULL, 'id=barang size=40') ?><?= form_hidden('id_barang', NULL, 'id=id_barang') ?></td></tr>
    <tr><td></td><td><?= form_button('Tampilkan', 'id=search') ?> <?= form_button('Reset', 'id=reset') ?> <?= form_button('Cetak', 'id=cetak') ?></td></tr>
</table>
</div>
<div id="result-info">
    
</div>