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

function add_obat_alergi(id, nama) {
    var jml = $('.alergi').length+1;
    var str = 
            '<tr class=alergi>'+
                '<td align=center>'+jml+'</td><td><input type=hidden name=id_obat[] value="'+id+'" /> '+nama+'</td><td width=1% class=aksi align=center><a class="deletion" onclick="delete_obat(this);" title="Klik untuk hapus">&nbsp;</a></td>'+
            '</tr>';
    $('#data-alergi tbody').append(str);
}

function get_alergi_data_obat(id_pasien) {
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
                '<td><input type=hidden name=id_obat[] value="'+id+'" /> '+nama+'</td>'+
                '<td><input type=text name=jumlah[] value="'+jumlah+'" style="text-align: center;" /></td>'+
                '<td><input type=text name=keterangan[] value="'+keterangan+'" style="width: 100%;" /></td>'+
                '<td class=aksi align=center><a class="deletion" onclick="delete_obat(this);" title="Klik untuk hapus">&nbsp;</a></td>'+
            '</tr>';
    $('#data-saran-pengobatan tbody').append(str);
}

function form_add() {
    var str = '<div id=form_pemeriksaan>'+
                '<form id=save_pemeriksaan action="models/update-transaksi.php?method=save_pemeriksaan" enctype=multipart/form-data>'+
                '<div id="tabs">'+
                    '<ul>'+
                        '<li><a href="#tabs-1">Data Pasien</a></li>'+
                        '<li><a href="#tabs-4">Data Alergi Obat</a></li>'+
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
                                '<tr><td>Tanggal Lahir:</td><td id=tanggal-lahir></td></tr>'+
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
                            '<tr valign=top><td><?= form_checkbox('merokok1', 'merokok1', 'merokok1', NULL, NULL) ?>Merokok:</td><td><?= form_radio('merokok', 'Ya', 'ya', 'Ya', FALSE) ?> <?= form_radio('merokok', 'Tidak', 'tidak', 'Tidak', TRUE) ?></td></tr>'+
                            '<tr valign=top><td><?= form_checkbox('ka1', 'ka1', 'ka1', NULL, NULL) ?>Konsumsi Alkohol:</td><td><?= form_radio('ka', 'Ya', 'ya', 'Ya', FALSE) ?> <?= form_radio('ka', 'Tidak', 'tidak', 'Tidak', TRUE) ?></td></tr>'+
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
                                    '<tr><td>Nama Penyakit:</td><td><?= form_input(NULL, NULL, 'id=penyakit size=43') ?><?= form_hidden('id_penyakit', NULL, 'id=id_penyakit') ?></td></tr>'+
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
                    '</form>'+
              '</div>';
    $('body').append(str);
    $('#tabs, #tabs-sub-pemeriksaan').tabs();
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
        $('#kelamin').html(data.kelamin);
        $('#tempat-lahir').html(data.tempat_lahir);
        $('#tanggal-lahir').html(datefmysql(data.tanggal_lahir));
        $('#alamat').html(data.alamat+' '+data.kabupaten+' '+data.provinsi);
        $('#telp').html(data.telp);
        $('#email').html(data.email);
        $('#asuransi').html(data.asuransi+' / '+data.nopolish);
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
        get_alergi_data_obat(data.id);
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
                    alert_tambah('#norm');
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
                //$('#save_pemeriksaan').submit();
                alert_dinamic('Under construction, call me at: arvin_nizar@yahoo.co.id !!');
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
</script>
<h1>Data Konsultasi</h1>
<hr>
<button id="button">Pemeriksaan (F9)</button>
<button id="reset">Reset</button>
<div id="result-pemeriksaan">
    
</div>