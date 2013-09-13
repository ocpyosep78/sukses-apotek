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
function add_diagnosis(id, nama) {
    var str = '<tr>'+
                '<td><input type=hidden name=id_diagnosis[] value="'+id+'" /> '+nama+'</td>'+
              '</tr>';
    $('.diagnosis').append(str);
    $('#diagnosis,#id_diagnosis').val('');
    $('#diagnosis').focus();
}

function add_tindakan(id, nama, nominal) {
    var str = '<tr>'+
                '<td><input type=hidden name=id_tindakan[] value="'+id+'" /> '+nama+' <input type=hidden name=nominal[] value="'+nominal+'" /></td>'+
              '</tr>';
    $('.tindakan').append(str);
    $('#tindakan,#id_tindakan').val('');
    $('#tindakan').focus();
}

function form_pemeriksaan(id_daftar, id_pasien, nama) {
    
    var str = '<div id=form_pemeriksaan>'+
                '<form id=save_pemeriksaan action="models/update-transaksi.php?method=save_pemeriksaan" enctype=multipart/form-data>'+
                    '<span id=output></span><?= form_hidden('id_pendaftaran', NULL, 'id=id_pendaftaran') ?>'+
                    '<table width=100% class=data-input><tr valign=top><td width=33%>'+
                    '<table width=100%>'+
                        '<tr><td>No. Pemeriksaan</td><td><?= form_input('nopemeriksaan', NULL, 'id=nopemeriksaan readonly size=10') ?></td></tr>'+
                        '<tr><td>Tanggal:</td><td><?= form_input('tanggal', date("d/m/Y"), 'id=tanggal size=10') ?></td></tr>'+
                        '<tr><td>Nomor PMR:</td><td><?= form_input('norm', NULL, 'id=norm size=40') ?></td></tr>'+
                        '<tr><td>Nama Pasien:</td><td><?= form_input('pasien', NULL, 'id=pasien size=40') ?><?= form_hidden('id_pasien', NULL, 'id=id_pasien') ?></td></tr>'+
                        '<tr><td>Dokter:</td><td><?= form_input('dokter', NULL, 'id=dokter size=40') ?><?= form_hidden('id_dokter', NULL, 'id=id_dokter') ?></td></tr>'+
                        '<tr><td>Foto Pasien:</td><td><?= form_upload('mFile') ?></td></tr>'+
                    '</table></td><td width=33%>'+
                    '<table width=100%>'+
                        '<tr><td valign=top>Anamnesis:</td><td><?= form_textarea('anamnesis', NULL, 'id=anamnesis cols=37 style="height: 30px"') ?></td></tr>'+
                        '<tr><td>Diagnosis:</td><td><?= form_input('diagnosis', NULL, 'id=diagnosis size=40') ?><?= form_hidden('id_diagnosis', NULL, 'id=id_diagnosis') ?></td></tr>'+
                        '<tr><td>Tindakan:</td><td><?= form_input('tindakan', NULL, 'id=tindakan size=40') ?><?= form_hidden('id_tindakan', NULL, 'id=id_tindakan') ?></td></tr>'+
                    '</table>'+
                    '</td><td id=foto></td></tr></table>'+
                    '<table width=100% cellspacing="0" class="list-data-input" id="penjualan-list">\n\
                        <thead><tr>'+
                            '<th width=50%>DIAGNOSIS</th>'+
                            '<th width=50%>TINDAKAN</th>'+
                        '</tr></thead>'+
                        '<tbody><tr><td valign=top><table width=100% class=diagnosis></table></td><td valign=top><table width=100% class=tindakan></td></tr></tbody>'+
                    '</table>'+
                '</form>'+
              '</div>';
    $('body').append(str);
    $('#id_pendaftaran').val(id_daftar);
    $('#norm, #id_pasien').val(id_pasien);
    $('input[name=pasien]').val(nama);
    
    var lebar = $('#pasien').width();
    $('#pasien,#norm').autocomplete("models/autocomplete.php?method=pasien",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].nama // nama field yang dicari
                };
            }
            $('#id_pasien').val('');
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
        $('#norm').val(data.id);
        $('#pasien').val(data.nama);
        $('#id_pasien').val(data.id);
        $.ajax({
            url: 'models/autocomplete.php?method=get_photo_pemeriksaan&id_pelanggan='+data.id,
            dataType: 'json',
            cache: false,
            success: function(msg) {
                if (msg.foto !== null) {
                    $('#foto').html('<img src="img/pemeriksaan/'+msg.foto+'" height="120px"/>');
                } else {
                    $('#foto').html('');
                }
                $('#id_pendaftaran').val(msg.id);
            }
        });
        $('#dokter').focus();
    });
    $('#dokter').autocomplete("models/autocomplete.php?method=dokter",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].nama // nama field yang dicari
                };
            }
            $('#id_dokter').val('');
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.nama+'<br/> '+data.no_str+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $(this).val(data.nama);
        $('#id_dokter').val(data.id);
        //alert(data.id);
    });
    $('#diagnosis').autocomplete("models/autocomplete.php?method=diagnosis",
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
            var str = '<div class=result>'+data.topik+'<br/> '+data.sub_kode+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $(this).val(data.topik);
        $('#id_diagnosis').val(data.id);
        add_diagnosis(data.id, data.topik);
    });
    $('#tindakan').autocomplete("models/autocomplete.php?method=tindakan",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].nama // nama field yang dicari
                };
            }
            $('#id_tindakan').val('');
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.nama+'<br/> '+data.nominal+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json' // tipe data yang diterima oleh library ini disetup sebagai JSON
    }).result(
    function(event,data,formated){
        $(this).val(data.nama);
        $('#id_tindakan').val(data.id);
        add_tindakan(data.id, data.nama, data.nominal);
    });
    $('#save_pemeriksaan').on('submit', function(e) {
        e.preventDefault();
        if ($('#id_pasien').val() === '') {
            alert_empty('Nomor pasien','#norm'); return false;
        }
        if ($('#id_dokter').val() === '') {
            alert_empty('Dokter','#dokter'); return false;
        }   
        $(this).ajaxSubmit({
            target: '#output',
            dataType: 'json',
            success:  function(data) {
                if (data.status === true) {
                    $('#form_pemeriksaan').dialog().remove();
                    alert_refresh('Data berhasil di simpan !');
                }
            }
        });
    });
    var wWidth = $(window).width();
    var dWidth = wWidth * 1;
    
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    $('#form_pemeriksaan').dialog({
        title: 'Pemeriksaan',
        autoOpen: true,
        modal: true,
        width: dWidth,
        height: dHeight,
        hide: 'clip',
        show: 'blind',
        buttons: {
            "Simpan (F8)": function() {
                $('#save_pemeriksaan').submit();
            }, 
            "Cancel": function() {    
                $(this).dialog().remove();
                $.cookie('session', 'false');
            }
        }, close: function() {
            $(this).dialog().remove();
            $.cookie('session', 'false');
        }, open: function() {
            
            $.ajax({
                url: 'models/autocomplete.php?method=get_no_pemeriksaan',
                cache: false,
                dataType: 'json',
                success: function(data) {
                    $('#nopemeriksaan').val(data);
                }
            });
            $.cookie('session', 'true');
        }
    });
    $('#tanggal').datepicker();
}
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
                    alert_refresh('Data berhasil di simpan');
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

function paging(page, tab, search) {
    load_data_pendaftaran(page, search);
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