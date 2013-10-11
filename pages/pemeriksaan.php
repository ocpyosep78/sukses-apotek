<?php

set_include_path("../");
include_once("inc/essentials.php");
include_once("inc/functions.php");
include_once("models/transaksi.php");
include_once("pages/message.php");
?>
<script type="text/javascript">
$.cookie('session','false');
$(function() {
    load_data_pemeriksaan();
    
    $('#button').button({
        icons: {
            primary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_add();
    });
    $('#reset').button({
        icons: {
            primary: 'ui-icon-refresh'
        }
    }).click(function() {
        load_data_pemeriksaan();
    });
    $(document).on('keydown', function(e) {
        if (e.keyCode === 120) {
            if ($.cookie('session') === 'false') {
                form_add();
            }
        }
    });
    
    $('#search').click(function() {
        load_data_penjualan();
    });
});

function delete_obat(el) {
    var parent = el.parentNode.parentNode;
    parent.parentNode.removeChild(parent);
    var jml = $('.alergi').length;
    var no = 0;
    for(i = 1; i <= jml; i++) {
        $('.alergi:eq('+no+')').children('td:eq(0)').html(i);
        no++;
    }
}

function add_penyakit_pasien(id, nama, kode) {
    var jml = $('.penyakit').length+1;
    var str = 
            '<tr class=penyakit>'+
                '<td align=center>'+jml+'</td><td><input type=hidden name=id_penyakit[] value="'+id+'" /> '+nama+'</td><td align=center>'+kode+'</td><td width=1% class=aksi align=center><a class="deletion" onclick="delete_penyakit(this);" title="Klik untuk hapus">&nbsp;</a></td>'+
            '</tr>';
    $('#data-penyakit tbody').append(str);
}

function add_penyakit_pasien_load(id_pasien) {
    $.getJSON('models/autocomplete.php?method=get_penyakit_by_pasien&id_pasien='+id_pasien, function(data){
        $.each(data, function (index, value) {
            var str = 
                '<tr class=penyakit>'+
                    '<td align=center>'+(index+1)+'</td><td><input type=hidden name=id_penyakit[] value="'+value.id+'" /> '+value.topik+'</td><td align=center>'+value.sub_kode+'</td><td width=1% class=aksi align=center><a class="deletion" onclick="delete_penyakit(this);" title="Klik untuk hapus">&nbsp;</a></td>'+
                '</tr>';
            $('#data-penyakit tbody').append(str);
        });
    });
}

function add_obat_alergi(id, nama) {
    var jml = $('.alergi').length+1;
    var str = 
            '<tr class=alergi>'+
                '<td align=center>'+jml+'</td><td><input type=hidden name=id_obat[] value="'+id+'" /> '+nama+'</td><td width=1% class=aksi align=center><a class="deletion" onclick="delete_obat(this);" title="Klik untuk hapus">&nbsp;</a></td>'+
            '</tr>';
    $('#data-alergi tbody').append(str);
}

function get_alergi_data_obat(id_pasien) {
    $('#data-alergi tbody').html('');
    $.getJSON('models/autocomplete.php?method=get_alergi_data_obat&id_pasien='+id_pasien, function(data){
        $.each(data, function (index, value) {
            var str = 
            '<tr class=alergi>'+
                '<td align=center>'+(index+1)+'</td><td><input type=hidden name=id_obat[] value="'+value.id_barang+'" /> '+value.nama_barang+'</td><td width=1% class=aksi align=center><a class="deletion" onclick="delete_obat(this);" title="Klik untuk hapus">&nbsp;</a></td>'+
            '</tr>';
            $('#data-alergi tbody').append(str);
        });
    });
}

function add_saran_pengobatan(id, nama, jumlah, keterangan) {
    var jml = $('.saran_pengobatan').length+1;
    var str = 
            '<tr class=saran_pengobatan>'+
                '<td align=center>'+jml+'</td>'+
                '<td><input type=hidden name=id_obat_saran[] value="'+id+'" /> '+nama+'</td>'+
                '<td><input type=text name=jumlah[] value="'+jumlah+'" style="text-align: center;" /></td>'+
                '<td><input type=text name=keterangan[] value="'+keterangan+'" style="width: 100%;" /></td>'+
                '<td class=aksi align=center><a class="deletion" onclick="delete_obat(this);" title="Klik untuk hapus">&nbsp;</a></td>'+
            '</tr>';
    $('#data-saran-pengobatan tbody').append(str);
}

function add_saran_pengobatan_load(id_pemeriksaan) {
    $.getJSON('models/autocomplete.php?method=get_saran_pengobatan&id_pemeriksaan='+id_pemeriksaan, function(data){
        $.each(data, function (index, value) {
            var str = 
                '<tr class=saran_pengobatan>'+
                    '<td align=center>'+(index+1)+'</td>'+
                    '<td><input type=hidden name=id_obat_saran[] value="'+value.id_barang+'" /> '+value.nama_barang+'</td>'+
                    '<td><input type=text name=jumlah[] value="'+value.jumlah+'" style="text-align: center;" /></td>'+
                    '<td><input type=text name=keterangan[] value="'+value.keterangan+'" style="width: 100%;" /></td>'+
                    '<td class=aksi align=center><a class="deletion" onclick="delete_obat(this);" title="Klik untuk hapus">&nbsp;</a></td>'+
                '</tr>';
            $('#data-saran-pengobatan tbody').append(str);
        });
    });
}

function form_add() {
    var str = '<div id=form_pemeriksaan>'+
                '<form id=save_pemeriksaan action="models/update-transaksi.php?method=save_pemeriksaan" enctype=multipart/form-data>'+
                '<input type=hidden name=id_pemeriksaan id=id_pemeriksaan />'+
                '<div id="tabs">'+
                    '<ul>'+
                        '<li><a href="#tabs-1">Data Pasien</a></li>'+
                        '<li><a href="#tabs-4">Data Alergi Obat</a></li>'+
                        '<li><a href="#tabs-5">Penyakit Diderita / Diagnosa Dokter</a></li>'+
                        '<li><a href="#tabs-2">Riwayat Penyakit</a></li>'+
                        '<li><a href="#tabs-3">Konsultasi</a></li>'+
                    '</ul>'+
                    '<div id="tabs-1">'+
                            '<span id=output></span>'+
                            
                            '<table width=100% class=data-input style="line-height: 20px;">'+
                                '<tr><td width=13%>Tanggal:</td><td width=87%><?= form_input('tanggal', date("d/m/Y"), 'id=tanggal size=10') ?></td></tr>'+
                                '<tr><td>Nomor PMR:</td><td><?= form_input('norm', NULL, 'id=norm size=40') ?></td></tr>'+
                                '<tr><td>Nama Pasien:</td><td><?= form_input('pasien', NULL, 'id=pasien size=40') ?><?= form_hidden('id_pasien', NULL, 'id=id_pasien') ?></td></tr>'+
                                '<tr><td>Kelamin:</td><td id=kelamin></td></tr>'+
                                '<tr><td>Tempat Lahir:</td><td id=tempat-lahir></td></tr>'+
                                '<tr><td>Umur:</td><td id=tanggal-lahir></td></tr>'+
                                '<tr><td>Alamat:</td><td id=alamat></td></tr>'+
                                '<tr><td>No. Telepon:</td><td id=telp></td></tr>'+
                                '<tr><td>Email:</td><td id=email></td></tr>'+
                                '<tr><td>Asuransi:</td><td id=asuransi></td></tr>'+
                            '</table>'+
                    '</div>'+
                    '<div id="tabs-2">'+
                        '<table width=100% class=data-input>'+
                            '<tr valign=top><td width=13%><?= form_checkbox('rpd1', 'rpd1', 'rpd1', NULL, NULL) ?>Riwayat Penyakit Dahulu:</td><td><?= form_textarea('rpd', NULL, 'id=rpd cols=40') ?></td></tr>'+
                            '<tr valign=top><td><?= form_checkbox('rpk1', 'rpk1', 'rpk1', NULL, NULL) ?>Riwayat Penyakit Keluarga:</td><td><?= form_textarea('rpk', NULL, 'id=rpk cols=40') ?></td></tr>'+
                            '<tr valign=top><td><?= form_checkbox('ps1', 'ps1', 'ps1', NULL, NULL) ?>Pengobatan Sekarang:</td><td><?= form_textarea('ps', NULL, 'id=ps cols=40') ?></td></tr>'+
                            '<tr valign=top><td><?= form_checkbox('oh1', 'oh1', 'oh1', NULL, NULL) ?>Obat Herbal:</td><td><?= form_textarea('oh', NULL, 'id=oh cols=40') ?></td></tr>'+
                            '<tr valign=top><td><?= form_checkbox('al1', 'al1', 'al1', NULL, NULL) ?>Alergi Lain:</td><td><?= form_textarea('al', NULL, 'id=al cols=40') ?></td></tr>'+
                            '<tr valign=top><td><?= form_checkbox('dl1', 'dl1', 'dl1', NULL, NULL) ?>Dokter Lain:</td><td><?= form_textarea('dl', NULL, 'id=dl cols=40') ?></td></tr>'+
                            '<tr valign=top><td><?= form_checkbox('merokok1', 'merokok1', 'merokok1', NULL, NULL) ?>Merokok:</td><td><?= form_radio('merokok', 'Ya', 'merokok', 'Ya', FALSE) ?> <?= form_radio('merokok', 'Tidak', 'tidak_merokok', 'Tidak', TRUE) ?></td></tr>'+
                            '<tr valign=top><td><?= form_checkbox('ka1', 'ka1', 'ka1', NULL, NULL) ?>Konsumsi Alkohol:</td><td><?= form_radio('ka', 'Ya', 'alkohol', 'Ya', FALSE) ?> <?= form_radio('ka', 'Tidak', 'tidak_alkohol', 'Tidak', TRUE) ?></td></tr>'+
                            '<tr><td></td><td><?= form_button('Cetak', 'id=cetak style="margin-left: 0; width: 80px;"') ?></td></tr>'+
                        '</table>'+
                    '</div>'+
                    '<div id="tabs-3">'+
                        '<div id="tabs-sub-pemeriksaan">'+
                            '<ul>'+
                                '<li><a href="#sub-tabs-1">Utama</a></li>'+
                                '<li><a href="#sub-tabs-2">Saran Pengobatan</a></li>'+
                            '</ul>'+
                            '<div id="sub-tabs-1">'+
                                '<table width=100% class=data-input>'+
                                    '<tr><td width=14%>Subjektif:</td><td><?= form_textarea('subjektif', NULL, 'id=subjektif cols=40') ?></td></tr>'+
                                    '<tr><td>Objektif:</td><td></td></tr>'+
                                    '<tr><td style="padding-left: 20px;">Suhu Badan:</td><td><?= form_input('suhubadan', NULL, 'id=suhubadan') ?> <sup>o</sup> C</td></tr>'+
                                    '<tr><td style="padding-left: 20px;">Tekanan Darah:</td><td><?= form_input('tekanandarah', NULL, 'id=tekanandarah') ?> mmHg</td></tr>'+
                                    '<tr><td style="padding-left: 20px;">Respiration Rate:</td><td><?= form_input('respirationrate', NULL, 'id=respirationrate') ?> x / menit</td></tr>'+
                                    '<tr><td style="padding-left: 20px;">Nadi:</td><td><?= form_input('nadi', NULL, 'id=nadi') ?> x / menit</td></tr>'+
                                    '<tr><td style="padding-left: 20px;">Gula Darah Sewaktu:</td><td><?= form_input('gdsewaktu', NULL, 'id=gdsewaktu') ?> mg/dL</td></tr>'+
                                    '<tr><td style="padding-left: 20px;">Angka Kolesterol Total:</td><td><?= form_input('angkakoltotal', NULL, 'id=angkakoltotal') ?> mg/dL</td></tr>'+
                                    '<tr><td style="padding-left: 20px;">Kadar Asam Urat:</td><td><?= form_input('kadarasamurat', NULL, 'id=kadarasamurat') ?> mg/dL</td></tr>'+
                                    '<tr><td width=13%>Assesment:</td><td><?= form_textarea('assesment', NULL, 'id=assesment cols=40') ?></td></tr>'+
                                    '<tr><td width=13%>Goal Terapi:</td><td><?= form_textarea('goalterapi', NULL, 'id=goalterapi cols=40') ?></td></tr>'+
                                    '<tr><td width=13%>Saran Non Farmakoterapi:</td><td><?= form_textarea('sarannonfarm', NULL, 'id=sarannonfarm cols=40') ?></td></tr>'+
                                '</table>'+
                            '</div>'+
                            '<div id="sub-tabs-2">'+
                                '<table width=100% class=data-input>'+
                                '<tr><td width=14%>Pilih Nama Obat:</td><td> <?= form_input(NULL, NULL, 'id=saran_obat style="margin-left: 0; margin-bottom: 0;" size=40') ?> <?= form_hidden('id_saran_obat', NULL, 'id=id_saran_obat') ?></td></tr>'+
                                '<tr><td>Jumlah:</td><td><?= form_input('jumlah', NULL, 'id=jumlah size=5') ?></td></tr>'+
                                '<tr><td>Keterangan:</td><td><?= form_input('keterangan', NULL, 'id=keterangan onKeyup="javascript:this.value=this.value.toUpperCase();" size=40') ?></td></tr>'+
                                '</table>'+
                                '<table width=100% class=list-data-input id=data-saran-pengobatan>'+
                                    '<thead><tr>'+
                                        '<th width=5%>No.</th>'+
                                        '<th width=30%>Nama Obat</th>'+
                                        '<th width=5%>Jumlah</th>'+
                                        '<th width=59%>Keterangan</th>'+
                                        '<th width=1%>#</th>'+
                                    '</tr></thead>'+
                                    '<tbody></tbody>'+
                                '</table>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div id="tabs-4">'+
                        '<table width=50% class=data-input>'+
                        '<tr><td width=26%>Pilih Nama Obat:</td><td> <?= form_input('obat', NULL, 'id=obat style="margin-left: 0; margin-bottom: 0;" size=40') ?> <?= form_hidden('id_obat', NULL, 'id=id_obat') ?></td></tr>'+
                        '</table>'+
                        '<table width=50% class=list-data id=data-alergi>'+
                            '<thead><tr>'+
                                '<th width=5%>No.</th>'+
                                '<th width=92%>Nama Obat</th>'+
                                '<th width=3%>#</th>'+
                            '</tr></thead>'+
                            '<tbody></tbody>'+
                        '</table>'+
                    '</div>'+
                    '<div id="tabs-5">'+
                        '<table width=50% class=data-input>'+
                        '<tr><td>Nama Penyakit:</td><td><?= form_input(NULL, NULL, 'id=penyakit size=43') ?><?= form_hidden('id_penyakit', NULL, 'id=id_penyakit') ?></td></tr>'+
                        '</table>'+
                        '<table width=50% class=list-data id=data-penyakit>'+
                            '<thead><tr>'+
                                '<th width=5%>No.</th>'+
                                '<th width=82%>Nama Penyakit</th>'+
                                '<th width=10%>Kode ICD</th>'+
                                '<th width=3%>#</th>'+
                            '</tr></thead>'+
                            '<tbody></tbody>'+
                        '</table>'+
                    '</div>'+
                    '</form>'+
              '</div>';
    $('body').append(str);
    $('#tabs, #tabs-sub-pemeriksaan').tabs();
    var lebar = $('#pasien').width();
    $('#pasien,#norm').autocomplete("models/autocomplete.php?method=pasien_pemeriksaan",
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
        $('#kelamin').html(data.kelamin);
        $('#tempat-lahir').html(data.tempat_lahir);
        $('#tanggal-lahir').html(hitungUmur(data.tanggal_lahir));
        $('#alamat').html(data.alamat+' '+data.kabupaten+' '+data.provinsi);
        $('#telp').html(data.telp);
        $('#email').html(data.email);
        $('#asuransi').html(data.asuransi+' / '+data.nopolish);
        $('#foto').html('<img src="img/pemeriksaan/'+data.foto+'" height="120px"/>');
        $('#dokter').focus();
        $('#rpd').val(data.rpd);
        $('#rpk').val(data.rpk);
        $('#ps').val(data.ps);
        $('#oh').val(data.oh);
        $('#al').val(data.al);
        $('#dl').val(data.dl);
        if (data.mk === 'Ya') { $('#merokok').attr('checked','checked'); }
        if (data.mk === 'Tidak') { $('#tidak_merokok').attr('checked','checked'); }
        if (data.ka === 'Ya') { $('#alkohol').attr('checked','checked'); }
        if (data.ka === 'Tidak') { $('#tidak_alkohol').attr('checked','checked'); }
        
        get_alergi_data_obat(data.id);
        add_penyakit_pasien_load(data.id);
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
    var lebar_pny = $('#penyakit').width();
    $('#penyakit').autocomplete("models/autocomplete.php?method=diagnosis",
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
            var str = '<div class=result>'+data.sub_kode+' - '+data.topik+'</div>';
            return str;
        },
        width: lebar_pny, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $(this).val(data.topik);
        $('#id_penyakit').val(data.id);
        add_penyakit_pasien(data.id, data.topik, data.sub_kode);
        $('#penyakit, id_penyakit').val('');
    });
    $('#obat').autocomplete("models/autocomplete.php?method=barang",
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
        $('#id_obat').val(data.id);
        add_obat_alergi(data.id, data.nama);
        $('#obat, #id_obat').val('');
    });
    $('#saran_obat').autocomplete("models/autocomplete.php?method=barang",
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
        $('#id_saran_obat').val(data.id);
        $('#jumlah').val('1').focus().select();
    });
    $('#jumlah').keydown(function(e) {
        if (e.keyCode === 13) {
            $('#keterangan').focus();
        }
    });
    $('#keterangan').keydown(function(e) {
        if (e.keyCode === 13) {
            var id      = $('#id_saran_obat').val();
            var nama    = $('#saran_obat').val();
            var jumlah  = $('#jumlah').val();
            var ket     = $('#keterangan').val();
            add_saran_pengobatan(id, nama, jumlah, ket);
            $('#id_saran_obat, #saran_obat, #jumlah, #keterangan').val('');
            $('#saran_obat').focus();
        }
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
                    alert_refresh('Data konsultasi berhasil disimpan');
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
                //alert_dinamic('Under construction, call me at: arvin_nizar@yahoo.co.id !!');
                return false;
            }, 
            "Cancel": function() {    
                $(this).dialog().remove();
                $.cookie('session', 'false');
            }
        }, close: function() {
            $(this).dialog().remove();
            $.cookie('session', 'false');
        }, open: function() {
            $('#norm').focus();
            $.cookie('session', 'true');
        }
    });
    $('#tanggal').datepicker();
}

function load_data_pemeriksaan(page, search, id) {
    pg = page; src = search; id_barg = id;
    if (page === undefined) { var pg = ''; }
    if (search === undefined) { var src = ''; }
    if (id === undefined) { var id_barg = ''; }
    $.ajax({
        url: 'pages/pemeriksaan-list.php',
        cache: false,
        data: 'page='+pg+'&search='+src+'&id_pemeriksaan='+id_barg,
        success: function(data) {
            $('#result-pemeriksaan').html(data);
        }
    });
}

function edit_pemeriksaan(id) {
    form_add();
    $('#id_pemeriksaan').val(id);
    $.ajax({
        url: 'models/autocomplete.php?method=get_data_pemeriksaan&id='+id,
        cache: false,
        dataType: 'json',
        success: function(data) {
            $('#norm').val(data.id_pasien);
            $('#pasien').val(data.nama);
            $('#id_pasien').val(data.id_pasien);
            $('#kelamin').html(data.kelamin);
            $('#tempat-lahir').html(data.tempat_lahir);
            $('#tanggal-lahir').html(hitungUmur(data.tanggal_lahir));
            $('#alamat').html(data.alamat+' '+data.kabupaten+' '+data.provinsi);
            $('#telp').html(data.telp);
            $('#email').html(data.email);
            $('#asuransi').html(data.asuransi+' / '+data.nopolish);
            $('#foto').html('<img src="img/pemeriksaan/'+data.foto+'" height="120px"/>');
            $('#dokter').focus();
            $('#rpd').val(data.rpd);
            $('#rpk').val(data.rpk);
            $('#ps').val(data.ps);
            $('#oh').val(data.oh);
            $('#al').val(data.al);
            $('#dl').val(data.dl);
            if (data.mk === 'Ya') { $('#merokok').attr('checked','checked'); }
            if (data.mk === 'Tidak') { $('#tidak_merokok').attr('checked','checked'); }
            if (data.ka === 'Ya') { $('#alkohol').attr('checked','checked'); }
            if (data.ka === 'Tidak') { $('#tidak_alkohol').attr('checked','checked'); }

            get_alergi_data_obat(data.id_pasien);
            add_penyakit_pasien_load(data.id_pasien);
            $('#subjektif').val(data.subjektif);
            $('#suhubadan').val(data.suhu_badan);
            $('#tekanandarah').val(data.tek_darah);
            $('#respirationrate').val(data.res_rate);
            $('#nadi').val(data.nadi);
            $('#gdsewaktu').val(data.gds);
            $('#angkakoltotal').val(data.angka_kolesterol);
            $('#kadarasamurat').val(data.asam_urat);
            $('#assesmen').val(data.assesment);
            $('#goalterapi').val(data.goal);
            $('#sarannonfarm').val(data.saran_non_farmakoterapi);
            add_saran_pengobatan_load(data.id_pemeriksaan);
        }
    });
}

function delete_pemeriksaan(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                $.ajax({
                    url: 'models/update-transaksi.php?method=delete_pemeriksaan&id='+id,
                    cache: false,
                    success: function() {
                        load_data_pemeriksaan(page);
                        $('#alert').dialog().remove();
                    }
                });
            },
            "Cancel": function() {
                $(this).dialog().remove();
            }
        }
    });
}

function hitung_detail_total(jml, jum, diskon_rupiah, diskon_persen, harga_jual, isi_satuan) {
    if (diskon_persen === undefined) {
        dp = '0';
    } else {
        dp = diskon_persen;
    }
    
    if (diskon_rupiah === undefined) {
        dr = '0';
    } else {
        dr = diskon_rupiah;
    }
    $('#hargajual'+jml).html(numberToCurrency(harga_jual));
    $('#harga_jual'+jml).val(parseInt(harga_jual));
    //$('#diskon_rupiah'+jml).val(numberToCurrency(parseInt(dr)));
    //$('#diskon_persen'+jml).val(dp);
    var subtotal = (jum*harga_jual*isi_satuan);
    $('#subtotal'+jml).html(numberToCurrency(parseInt(subtotal)));
    hitung_total_penjualan();
}

function hitung_total_penjualan() {
    var panjang   = $('.tr_rows').length; // banyaknya baris data
    var total     = 0;
    for(i = 1; i <= panjang; i++) {
        var subtotal = parseInt(currencyToNumber($('#subtotal'+i).html()));
        total   = total + subtotal;
    }
    var diskon_pr = ($('#diskon_pr').val()/100); // diskon penjualan %
    var diskon_rp = $('#diskon_rp').val(); // diskon penjualan Rp.
    var ppn_jual  = ($('#ppn').val()/100);
    var tuslah    = parseInt(currencyToNumber($('#tuslah').val()));
    var embalage  = parseInt(currencyToNumber($('#embalage').val()));
    if (diskon_pr !== 0) {
        total_terdiskon = total-(total*diskon_pr); // total terdiskon persentase
    } else {
        total_terdiskon = total-diskon_rp; // total terdiskon rupiah
    }
    var total_tambah_ppn = total_terdiskon+(total_terdiskon*ppn_jual);
    var total_tambah_tuslah = total_tambah_ppn+tuslah+embalage;
    
    /*Dikurangi Reimbursement jika ada*/
    var reimburse = $('#reimburse').val();
    if (!isNaN(reimburse)) {
        if ($('#reimburse').is(':checked') === true) {
            total = total_tambah_tuslah-(total_tambah_tuslah*(reimburse/100));
        } else {
            total = total_tambah_tuslah;
        }
    } else {
        total = total_tambah_tuslah;
    }
    
    $('#total-penjualan').html(numberToCurrency(parseInt(total)));
    $('#total_penjualan').val(parseInt(total_tambah_tuslah));
}

function removeMe(el) {
    var parent = el.parentNode.parentNode;
    parent.parentNode.removeChild(parent);
    var jumlah = $('.tr_rows').length;
    var col = 0;
    for (i = 1; i <= jumlah; i++) {
        $('.tr_rows:eq('+col+')').children('td:eq(0)').html(i);
        $('.tr_rows:eq('+col+')').children('td:eq(1)').children('.id_barang').attr('id','id_barang'+i);
        $('.tr_rows:eq('+col+')').children('td:eq(2)').children('.jumlah').attr('id','jumlah'+i);
        $('.tr_rows:eq('+col+')').children('td:eq(3)').children('.harga_jual').attr('id','harga_jual'+i);
        $('.tr_rows:eq('+col+')').children('td:eq(3)').children('.kemasan').attr('id','kemasan'+i);
        $('.tr_rows:eq('+col+')').children('td:eq(4)').children('.ed').attr('id','ed'+i);
        $('.tr_rows:eq('+col+')').children('td:eq(5)').attr('id','sisa'+i);
        $('.tr_rows:eq('+col+')').children('td:eq(6)').attr('id','hargajual'+i);
        $('.tr_rows:eq('+col+')').children('td:eq(7)').children('.diskon_rupiah').attr('id','diskon_rupiah'+i);
        $('.tr_rows:eq('+col+')').children('td:eq(8)').children('.diskon_persen').attr('id','diskon_persen'+i);
        $('.tr_rows:eq('+col+')').children('td:eq(9)').attr('id','subtotal'+i);
        col++;
    }
    hitung_total_penjualan();
}

function add_new_rows(id_brg, nama_brg, jumlah, id_packing) {
    var jml = $('.tr_rows').length+1;
    
    var str = '<tr class="tr_rows">'+
                '<td align=center>'+jml+'</td>'+
                '<td>&nbsp;'+nama_brg+' <input type=hidden name=id_barang[] value="'+id_brg+'" class=id_barang id=id_barang'+jml+' /></td>'+
                '<td><input type=text name=jumlah[] id=jumlah'+jml+' value="'+jumlah+'" class=jumlah style="text-align: center;" /></td>'+
                '<td><input type=hidden name=harga_jual[] id=harga_jual'+jml+' class=harga_jual /> <input type=hidden name=isi_satuan[] id=isi_satuan'+jml+' /> <select name=kemasan[] class=kemasan id=kemasan'+jml+'></select></td>'+
                '<td align=center><select name=ed[] class=ed id=ed'+jml+' class=ed></select></td>'+
                '<td align=center id=sisa'+jml+'></td>'+
                '<td align=right id=hargajual'+jml+'></td>'+
                '<td><input type=text name=diskon_rupiah[] class=diskon_rupiah style="text-align: right;" id=diskon_rupiah'+jml+' value="0" onblur="FormNum(this)" /></td>'+
                '<td><input type=text name=diskon_persen[] class=diskon_persen style="text-align: center;" id=diskon_persen'+jml+' value="0" /></td>'+
                '<td align=right id=subtotal'+jml+'></td>'+
                '<td align=center><img onclick=removeMe(this); title="Klik untuk hapus" src="img/icons/delete.png" class=add_kemasan align=left /></td>'+
              '</tr>';
    $('#pesanan-list tbody').append(str);
    $.getJSON('models/autocomplete.php?method=get_kemasan_barang&id='+id_brg, function(data){
        $('#kemasan'+jml).html('');
        $.each(data, function (index, value) {
            $('#kemasan'+jml).append("<option value='"+value.id+"'>"+value.nama+"</option>");
            if (value.default_kemasan === '1') { $('#kemasan'+jml).val(value.id); }
        });
    });
    $.getJSON('models/autocomplete.php?method=get_expiry_barang&id='+id_brg, function(data){
        $('#ed'+jml).html('');
        $.each(data, function (index, value) {
            $('#ed'+jml).append("<option value='"+value.ed+"'>"+datefmysql(value.ed)+"</option>");
        });
    });
    $.ajax({
        url: 'models/autocomplete.php?method=get_detail_harga_barang&id='+id_packing+'&jumlah='+jumlah,
        dataType: 'json',
        cache: false,
        success: function(data) {
            hitung_detail_total(jml, jumlah, data.diskon_rupiah, data.diskon_persen, Math.ceil(data.harga_jual), data.isi_satuan);
            $('#isi_satuan'+jml).val(data.isi_satuan);
        }
    });
    $.ajax({
        url: 'models/autocomplete.php?method=get_stok_sisa&id='+id_brg,
        dataType: 'json',
        cache: false,
        success: function(data) {
            if (data.sisa === null) {
                sisa = '0';
            } else {
                sisa = data.sisa;
            }
            $('#sisa'+jml).html(sisa);
        }
    });
    $('#ed'+jml).datepicker({
        changeYear: true,
        changeMonth: true,
        minDate: 0
    });
    $('#jumlah'+jml).blur(function() {
        var jumlah      = $('#jumlah'+jml).val();
        var hrg_jual    = parseInt(currencyToNumber($('#hargajual'+jml).html()));
        var isi_satuan  = parseInt($('#isi_satuan'+jml).val());
        var diskon      = 0;
        if ($('#diskon_rupiah'+jml).val() !== '0') {
            diskon  = parseInt(currencyToNumber($('#diskon_rupiah'+jml).val()));
        }
        else if ($('#diskon_persen'+jml).val() !== '0') {
            var diskonpr= $('#diskon_persen'+jml).val()/100;
            diskon  = ((jumlah*hrg_jual)*diskonpr);
        }
        
        var subtotal    = (hrg_jual*jumlah*isi_satuan)-diskon;
        $('#subtotal'+jml).html(numberToCurrency(parseInt(subtotal)));
        hitung_total_penjualan();
    });
    $('#diskon_rupiah'+jml).blur(function() {
        if ($(this).val() !== '0') {
            $('#diskon_persen'+jml).val('0');
        }
        var jumlah      = $('#jumlah'+jml).val();
        var hrg_jual    = parseInt(currencyToNumber($('#hargajual'+jml).html()));
        var isi_satuan  = parseInt($('#isi_satuan'+jml).val());
        var diskon      = 0;
        if ($('#diskon_rupiah'+jml).val() !== '0') {
            diskon  = parseInt(currencyToNumber($('#diskon_rupiah'+jml).val()));
        }
        else if ($('#diskon_persen'+jml).val() !== '0') {
            var diskonpr= $('#diskon_persen'+jml).val()/100;
            diskon  = ((jumlah*hrg_jual)*diskonpr);
        }
        
        var subtotal    = (hrg_jual*jumlah*isi_satuan)-diskon;
        $('#subtotal'+jml).html(numberToCurrency(parseInt(subtotal)));
        hitung_total_penjualan();
    });
    $('#diskon_persen'+jml).blur(function() {
        if ($(this).val() !== '0') {
            $('#diskon_rupiah'+jml).val('0');
        }
        var jumlah      = $('#jumlah'+jml).val();
        var hrg_jual    = parseInt(currencyToNumber($('#hargajual'+jml).html()));
        var isi_satuan  = parseInt($('#isi_satuan'+jml).val());
        var diskon      = 0;
        if ($('#diskon_rupiah'+jml).val() !== '0') {
            diskon  = parseInt(currencyToNumber($('#diskon_rupiah'+jml).val()));
        }
        else if ($('#diskon_persen'+jml).val() !== '0') {
            var diskonpr= $('#diskon_persen'+jml).val()/100;
            diskon  = ((jumlah*hrg_jual)*diskonpr);
        }
        
        var subtotal    = (hrg_jual*jumlah*isi_satuan)-diskon;
        $('#subtotal'+jml).html(numberToCurrency(parseInt(subtotal)));
        hitung_total_penjualan();
    });
    $('#kemasan'+jml).change(function() {
        var id  = $(this).val();
        var jum = $('#jumlah'+jml).val();
        $.ajax({
            url: 'models/autocomplete.php?method=get_detail_harga_barang&id='+id+'&jumlah='+jum,
            dataType: 'json',
            cache: false,
            success: function(data) {
                $('#isi_satuan'+jml).val(data.isi_satuan);
                hitung_detail_total(jml, jum, data.diskon_rupiah, data.diskon_persen, Math.ceil(data.harga_jual), data.isi_satuan);
                hitung_total_penjualan();
            }
        });
    });
    $.ajax({
        url: 'models/autocomplete.php?method=get_stok_sisa&id='+id_brg,
        dataType: 'json',
        cache: false,
        success: function(data) {
            if (data.sisa === null) {
                sisa = '0';
            } else {
                sisa = data.sisa;
            }
            $('#sisa'+jml).html(sisa);
        }
    });
}

function pembulatan_seratus(angka) {
    var kelipatan = 100;
    var sisa = angka % kelipatan;
    if (sisa !== 0) {
        var kekurangan = kelipatan - sisa;
        var hasilBulat = angka + kekurangan;
        return Math.ceil(hasilBulat);
    } else {
        return Math.ceil(angka);
    }   
}

function hitung_kembalian() {
    var pembulatan = parseInt(currencyToNumber($('#pembulatan').val()));
    var pembayaran = parseInt(currencyToNumber($('#pembayaran').val()));
    var kembalian  = pembayaran - pembulatan;
    //,#pembulatan_bayar,#pembayaran_bayar'
    if (kembalian < 0) {
        $('#label_kembali').html('Kekurangan:');
        $('#kembalian').html(kembalian);
    } else {
        $('#label_kembali').html('Kembalian:');
        $('#kembalian').html(numberToCurrency(parseInt(kembalian)));
    }
    $('#pembulatan_bayar').val(pembulatan);
    $('#pembayaran_bayar').val(pembayaran);
}

function cetak_struk(id_penjualan) {
    var wWidth = $(window).width();
    var dWidth = wWidth * 0.3;
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    var x = screen.width/2 - dWidth/2;
    var y = screen.height/2 - dHeight/2;
    window.open('pages/nota-penjualan.php?id='+id_penjualan, 'Penjualan Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
}

function form_pembayaran() {
    var str = '<div id="form-pembayaran">'+
                '<table width="100%" style="font-size: 30px;">'+
                    '<tr><td>Total Tagihan:</td><td><?= form_input(null, null, 'id=total_tagihan readonly size=10') ?></td></tr>'+
                    '<tr><td>Pembulatan:</td><td><?= form_input('pembulatan', NULL, 'id=pembulatan size=10') ?></td></tr>'+
                    '<tr><td>Pembayaran:</td><td><?= form_input('pembayaran', NULL, 'id=pembayaran size=10') ?></td></tr>'+
                    '<tr><td id=label_kembali>Kembalian:</td><td id=kembalian></td></tr>'+
                '</table>'+
              '</div>';
      $('body').append(str);
      $(document).keydown(function(e) {
          if (e.keyCode === 13) {
              $('#save_penjualannr').submit();
          }
      });
      $('#total_tagihan,#pembulatan,#pembayaran').keyup(function() {
            FormNum(this);
            hitung_kembalian();
      });
      $('#form-pembayaran').dialog({
            title: 'Form Pembayaran',
            autoOpen: true,
            modal: true,
            width: 500,
            height: 300,
            buttons: {
                "Simpan": function() {
                    $('#save_penjualannr').submit();
                    $('#form-pembayaran').dialog().remove();
                }
            },
            open: function() {
                var total = parseInt(currencyToNumber($('#total-penjualan').html()));
                $('#total_tagihan').val(numberToCurrency(total));
                $('#pembulatan,#pembayaran,#pembulatan_bayar,#pembayaran_bayar').val(numberToCurrency(pembulatan_seratus(total)));
                $('#kembalian').html('0');
                $('#pembayaran').focus().select();
                $.cookie('formbayar', 'true');
            }
            ,close: function() {
                $('#form-pembayaran').dialog().remove();
                $.cookie('formbayar', 'false');
            }
      });
}

function form_penjualan() {
    var str = '<div id="form_penjualannr">'+
            '<form id="save_penjualannr">'+
            '<?= form_hidden('pembulatan', NULL, 'id=pembulatan_bayar') ?>'+
            '<?= form_hidden('pembayaran', NULL, 'id=pembayaran_bayar') ?>'+
            '<table width=100% class=data-input><tr valign=top><td width=50%><table width=100% id="attr-utama">'+
                '<tr><td width=20%>Tanggal:</td><td><?= form_input('tanggal', date("d/m/Y"), 'id=tanggal size=10') ?></td></tr>'+
                '<tr><td>Diskon:</td><td><?= form_input('diskon_pr', '0', 'id=diskon_pr maxlength=5 onblur="hitung_total_penjualan();" size=10') ?> %, Rp. <?= form_input('diskon_rp', '0', 'id=diskon_rp onblur="hitung_total_penjualan();" size=10') ?></td></tr>'+
                '<tr><td>PPN:</td><td><?= form_input('ppn', '0', 'id=ppn size=10 maxlength=5 onblur="hitung_total_penjualan();"') ?></td></tr>'+
                '<tr><td>Tuslah Rp.:</td><td><?= form_input('tuslah', '0', 'id=tuslah onblur=FormNum(this) onkeyup="hitung_total_penjualan();" size=10') ?></td></tr>'+
                '<tr><td>Embalage Rp.:</td><td><?= form_input('embalage', '0', 'id=embalage size=10 onblur=FormNum(this) onkeyup="hitung_total_penjualan();"') ?></td></tr>'+
                '<tr><td>Customer:</td><td><?= form_input('customer', NULL, 'id=customer size=40') ?> <?= form_hidden('id_customer', NULL, 'id=id_customer') ?> <?= form_hidden('asuransi', NULL, 'id=asuransi') ?></td></tr>'+
            '</table></td><td width=50%><table width=100%>'+
                '<tr><td width=20%>Barcode:</td><td><?= form_input('barcode', NULL, 'id=barcode size=40') ?></td></tr>'+
                '<tr><td width=20%>Nama Barang:</td><td><?= form_input('barang', NULL, 'id=barang size=40') ?><?= form_hidden('id_barang', NULL, 'id=id_barang') ?></td></tr>'+
                '<tr><td>Jumlah:</td><td><input type=text value="1" size=5 id=pilih /></td></tr>'+
                '<tr><td>TOTAL:</td><td style="font-size: 45px;"><span>Rp. </span><span id=total-penjualan>0</span>, 00</td></tr>'+
            '</table><input type=hidden name=total_penjualan id=total_penjualan /></td></tr></table>'+
            '<table width=100% cellspacing="0" class="list-data-input" id="pesanan-list"><thead>'+
                '<tr><th width=5%>No.</th>'+
                    '<th width=29%>Nama Barang</th>'+
                    '<th width=10%>Jumlah</th>'+
                    '<th width=10%>Kemasan</th>'+
                    '<th width=10%>ED</th>'+
                    '<th width=5%>Sisa<br/>Stok</th>'+
                    '<th width=10%>Harga Jual</th>'+
                    '<th width=10%>Diskon RP.</th>'+
                    '<th width=5%>Diskon %</th>'+
                    '<th width=10%>Subtotal</th>'+
                    '<th width=1%>#</th>'+
                '</tr></thead>'+
                '<tbody></tbody>'+
            '</table>'+
            '</form></div>';
    $('body').append(str);
    var lebar = $('#pabrik').width();
    $('#diskon_pr').blur(function() {
        if ($('#diskon_pr').val() !== '' && $('#diskon_pr').val() !== '0') {
            $('#diskon_rp').val('0');
        }
    });
    $('#diskon_rp').blur(function() {
        if ($('#diskon_rp').val() !== '' && $('#diskon_rp').val() !== '0') {
            $('#diskon_pr').val('0');
        }
    });
    $('#pilih').keydown(function(e) {
        if (e.keyCode === 13) {
            var id_barang   = $('#id_barang').val();
            var nama        = $('#barang').val();
            var jumlah      = $('#pilih').val();
            if (id_barang !== '') {
                $.ajax({
                    url: 'models/autocomplete.php?method=get_detail_harga_barang_resep&id='+id_barang+'&jumlah='+jumlah,
                    dataType: 'json',
                    cache: false,
                    success: function(data) {
                        add_new_rows(id_barang, nama, jumlah ,data.id_packing);
                    }
                });
            }
            $('#id_barang, #pilih').val('');
            $('#barang').val('').focus();
        }
    });
    $(document).keydown(function(e) {
        if (e.keyCode === 119) {
            if ($.cookie('session') === 'true' && $.cookie('formbayar') === 'false') {
                form_pembayaran();
            }
        }
    });
    $('#barcode').keydown(function(e) {
        if (e.keyCode === 13) {
            var barcode = $('#barcode').val();
            if (barcode !== '' && barcode !== ' ') {
                $.ajax({
                    url: 'models/autocomplete.php?method=get_barang&barcode='+barcode,
                    dataType: 'json',
                    success: function(data) {
                        $('#barang').val(data.nama_barang);
                        $('#id_barang').val(data.id);
                        if (data.id !== '') {
                            add_new_rows(data.id, data.nama, '1', data.id_packing);
                        }
                        $('#id_barang').val('');
                        $('#barang').val('');
                        $('#barcode').val('').focus();
                    }
                });
            }
        }
    });
    $('#barang').keydown(function(e) {
        if (e.keyCode === 13) {
            $('#pilih').val('1').focus().select();
        }
    });
    $('#customer').autocomplete("models/autocomplete.php?method=pasien",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].nama // nama field yang dicari
                };
            }
            $('#id_customer').val('');
            $('#attr-utama').append('');
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.id+' '+data.nama+' <br/> '+data.alamat+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0
    }).result(
    function(event,data,formated){
        $(this).val(data.nama);
        $('#id_customer').val(data.id);
        $('#asuransi').val(data.id_asuransi);
        $('#diskon_pr').val(data.diskon);
        $('#newrow').remove();
        if (data.id_asuransi !== null) {
            var attr = '<tr id="newrow"><td>Reimburse:</td><td><?= form_checkbox('reimburse', '', 'reimburse', '', TRUE) ?></td></tr>';
            $('#attr-utama').append(attr);
            $('#reimburse').val(data.reimburse);
            $('label').html(data.reimburse+' %');
            $('#reimburse').unbind('click');
            $('#reimburse').bind('click', function() {
                hitung_total_penjualan();
            });
        }
        hitung_total_penjualan();
    });
    
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
    var wWidth = $(window).width();
    var dWidth = wWidth * 1;
    
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    $('#form_penjualannr').dialog({
        title: 'Penjualan Bebas',
        autoOpen: true,
        modal: true,
        width: dWidth,
        height: dHeight,
        hide: 'clip',
        show: 'blind',
        buttons: {
            "Bayar (F8)": function() {
                form_pembayaran();
            }, 
            "Cancel": function() {    
                $(this).dialog().remove();
                $.cookie('session', 'false');
            }
        }, close: function() {
            $(this).dialog().remove();
            $.cookie('session', 'false');
        }, open: function() {
            $('#barang').focus();
            $('#barcode').val('');
            $.cookie('session', 'true');
        }
    });
    $('#tanggal').datepicker({
        changeYear: true,
        changeMonth: true
    });
    $('#save_penjualannr').submit(function() {
        var jumlah = $('.tr_rows').length;
        
        if (jumlah === 0) {
            alert_empty('Barang', '#barang');
            return false;
        }
        if (jumlah > 0) {
            for (i = 1; i <= jumlah; i++) {
                // alert here
            }
        }
        $.ajax({
            url: 'models/update-transaksi.php?method=save_penjualannr',
            data: $(this).serialize(),
            dataType: 'json',
            type: 'POST',
            success: function(data) {
                if (data.status === true) {
                    //load_data_penjualannr();
                    $('#pesanan-list tbody').html('');
                    $('#total_penjualan, #customer, #id_customer, #pilih').val('');
                    $('#ppn, #tuslah, #embalage').val('0');
                    $('#total-penjualan').html('0');
                    $('#form-pembayaran').dialog().remove();
                    $('#barang').focus();
                    $.cookie('session', 'true');
                    $.cookie('formbayar', 'false');
                    alert_refresh('Penjualan berhasil disimpan');
                    cetak_struk(data.id);
                    $('#form_penjualannr').dialog().remove();
                    //alert_tambah('#barcode');
                }
            }
            
        });
        return false;
    });
}

function penjualan(id_pemeriksaan, id_pasien, pasien) {
    form_penjualan();
    $.getJSON('models/autocomplete.php?method=get_saran_pengobatan&id_pemeriksaan='+id_pemeriksaan, function(data){
        $.each(data, function (index, value) {
            add_new_rows(value.id_barang, value.nama_barang, value.jumlah, value.id_packing);
        });
    });
    $('#customer').val(pasien);
    $('#id_customer').val(id_pasien);
}
</script>
<h1>Data Konsultasi</h1>
<hr>
<button id="button">Pemeriksaan (F9)</button>
<button id="reset">Reset</button>
<div id="result-pemeriksaan">
    
</div>