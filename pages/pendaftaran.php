<?php
$subNav = array(
	"Data Pendaftaran ; pendaftaran.php ; #509601;",
        "Data Pemeriksaan ; pemeriksaan.php ; #509601;"
);
set_include_path("../");
include_once 'inc/essentials.php';
include_once '../inc/functions.php';
include_once '../pages/message.php';
?>
<script type="text/javascript">
$(function() {
    displayTime();
    load_data_pendaftaran();
    $('#simpan, #reset').button();
    $('#reset').click(function() {
        load_data_pendaftaran();
    });
    var lebar = $('#pasien').width();
    $('#pasien').autocomplete("models/autocomplete.php?method=pasien",
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
            var str = '<div class=result>'+data.id+'<br/> '+data.nama+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0
    }).result(
    function(event,data,formated){
        $(this).val(data.id+' - '+data.nama);
        $('#id_pasien').val(data.id);
        $('#spesialisasi').focus().select();
    });
    $('#spesialisasi').autocomplete("models/autocomplete.php?method=spesialisasi",
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
            var str = '<div class=result>'+data.nama+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0
    }).result(
    function(event,data,formated){
        $(this).val(data.nama);
        $('#id_spesialisasi').val(data.id);
        $.ajax({
            url: 'models/autocomplete.php?method=get_no_antri&id_spesialisasi='+data.id,
            dataType: 'json',
            cache: false,
            success: function(data) {
                $('#noantri').html(data);
                $('#simpan').focus();
            }
        });
    });
    $('#simpan').click(function() {
        if ($('#id_pasien').val() === '') {
            alert_empty('No. pasien','#pasien'); return false;
        }
        if ($('#id_spesialisasi').val() === '') {
            alert_empty('Nama pelayanan','#spesialisasi'); return false;
        }
        var waktu       = $('#waktu').val();
        var pasien      = $('#id_pasien').val();
        var spesialis   = $('#id_spesialisasi').val();
        var noantri     = $('#noantri').html();
        $.ajax({
            url: 'models/update-transaksi.php?method=save_pendaftaran',
            data: 'waktu='+waktu+'&pasien='+pasien+'&spesialis='+spesialis+'&noantri='+noantri,
            cache: false,
            dataType: 'json',
            success: function(data) {
                if (data.status === true) {
                    alert_tambah('#pasien');
                    $('#pasien, #id_pasien, #spesialisasi, #id_spesialisasi').val('');
                    $('#noantri').html('');
                    window.open('pages/cetak-antrian.php?id_daftar='+data.id,'Print Antri','width=300px, height=300px');
                    load_data_pendaftaran();
                }
            }
        });
    });
});

function displayTime() {
    //var elt = document.getElementById("waktu");  // Find element with id="clock"
    var now = new Date();                        // Get current time
    var dt  = now.toDateString()+' '+now.toLocaleTimeString();
    $('#waktu').val(dt);    // Make elt display it
    
    setTimeout(displayTime, 1000);               // Run again in 1 second
}


function load_data_pendaftaran(page, search, id) {
    pg = page; src = search; id_barg = id;
    if (page === undefined) { var pg = ''; }
    if (search === undefined) { var src = ''; }
    if (id === undefined) { var id_barg = ''; }
    $.ajax({
        url: 'pages/pendaftaran-list.php',
        cache: false,
        data: 'page='+pg+'&search='+src+'&id_pendaftaran='+id_barg,
        success: function(data) {
            $('#result').html(data);
        }
    });
}
</script>
<h1 class="margin-t-0">Formulir Pendaftaran Antrian</h1>
<div class="input-parameter">
    <table width="100%">
        <tr><td width="15%">Waktu:</td><td><?= form_input('waktu', date("d/m/Y H:i"), 'size=27 readonly id=waktu') ?></td></tr>
        <tr><td>No. RM / Nama Pasien:</td><td><?= form_input('pasien', NULL, 'id=pasien size=40') ?><?= form_hidden('id_pasien', NULL, 'id=id_pasien') ?></td></tr>
        <tr><td>Nama Pelayanan:</td><td><?= form_input('spesialisasi', NULL, 'id=spesialisasi size=40') ?><?= form_hidden('id_spesialisasi', NULL, 'id=id_spesialisasi') ?></td></tr>
        <tr><td>No. Antrian:</td><td style="font-size: 40px;" id="noantri"></td></tr>
        <tr><td></td><td><?= form_button('Simpan', 'id=simpan') ?> <?= form_button('Reset', 'id=reset') ?></td></tr>
    </table>
</div>
<div id="result">
    
</div>