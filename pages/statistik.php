<?php
include_once '../models/transaksi.php';
include_once '../models/masterdata.php';
include_once '../inc/functions.php';
$perundangan = perundangan_load_data();
$golongan    = load_data_golongan(array());
$admr        = admr_load_data();
$sediaan     = sediaan_load_data();
?>
<script type="text/javascript">
function get_result_statistik(page) {
    var awal = $('#awal').val();
    var akhir= $('#akhir').val();
    var id   = '';
    var perundangan = $('#perundangan').val();
    var sediaan     = $('#sediaan').val();
    var golongan    = $('#golongan').val();
    var formularium = $('.formularium:checked').val();
    var admr        = $('#admr').val();
    var generik     = $('.generik:checked').val();
    var pg   = (page === undefined)?'':page;
    $.ajax({
        url: 'pages/statistik-list.php?awal='+awal+'&akhir='+akhir+'&id='+id+'&perundangan='+perundangan+'&sediaan='+sediaan+'&golongan='+golongan+'&formularium='+formularium+'&admr='+admr+'&generik='+generik+'&page='+pg,
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
        get_result_statistik();
    });
    $('#reset').button().click(function() {
        $('#awal,#akhir').val('<?= date("d/m/Y") ?>');
        $('#barang,#id_barang, select').val('');
        $('input:radio').removeAttr('checked');
        $('#result-info').html('');
    });
    $('#cetak').button().click(function() {
        var awal = $('#awal').val();
        var akhir= $('#akhir').val();
        var id   = $('#id_barang').val();
        var perundangan = $('#perundangan').val();
        var wWidth = $(window).width();
        var dWidth = wWidth * 0.8;
        var wHeight= $(window).height();
        var dHeight= wHeight * 1;
        var x = screen.width/2 - dWidth/2;
        var y = screen.height/2 - dHeight/2;
        window.open('pages/arus-stok-print.php?awal='+awal+'&akhir='+akhir+'&id='+id+'&perundangan='+perundangan, 'Stok', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
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
<h1 class="margin-t-0">Statistik Obat</h1>
<div class="input-parameter">
<table width="100%">
    <tr><td width="10%">Range Tanggal:</td><td><?= form_input('awal', date("d/m/Y"), 'id=awal size=10') ?> s . d <?= form_input('akhir', date("d/m/Y"), 'id=akhir size=10') ?></td></tr>
    <tr><td>Sediaan:</td><td><select name=sediaan id=sediaan><option value="">Semua Sediaan ...</option><?php foreach ($sediaan as $data) { echo '<option value="'.$data->id.'">'.$data->nama.'</option>'; } ?></select></td></tr>
    <tr><td>Golongan:</td><td><select name="golongan" id="golongan"><option value="">Semua Golongan ...</option><?php foreach ($golongan['data'] as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>
    <tr><td>Perundangan:</td><td><select name="perundangan" id="perundangan"><option value="">Semua Perundangan ...</option><?php foreach ($perundangan as $data) { ?><option value="<?= $data ?>"><?= $data ?></option><?php } ?></select></td></tr>
    <tr><td>Formularium:</td><td><?= form_radio('formularium', 'Ya', 'yes', 'Ya', 'class=formularium') ?> <?= form_radio('formularium', 'Tidak', 'no', 'Tidak', 'class=formularium') ?></td></tr>
    <tr><td>R. Administrasi:</td><td><select name=admr id=admr><option value="">Pilih ...</option><?php foreach ($admr as $data) { echo '<option value="'.$data.'">'.$data.'</option>'; } ?></select></td></tr>
    <tr><td></td><td><?= form_radio('generik', '1', 'ya', 'Generik', 'class=generik') ?> <?= form_radio('generik', '0', 'tidak', 'Non Generik','class=generik') ?></td></tr>
    <tr><td></td><td><?= form_button('Tampilkan', 'id=search') ?> <?= form_button('Reset', 'id=reset') ?> </td></tr>
</table>
</div>
<div id="result-info">
    
</div>