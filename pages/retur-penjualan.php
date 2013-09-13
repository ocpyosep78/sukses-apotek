<?php
$subNav = array(
        "Retur Penerimaan ; retur-penerimaan.php ; #509601;",
	"Retur Penjualan ; retur-penjualan.php ; #509601;"
);
set_include_path("../");
include_once("inc/essentials.php");
include_once("inc/functions.php");
include_once("models/transaksi.php");
include_once("models/masterdata.php");
include_once("pages/message.php");
$kemasan  = satuan_load_data('0');
?>
<script type="text/javascript">
$(function() {
    load_data_retur_penjualan();
    $(document).keydown(function(e) {
        if (e.keyCode === 120) {
            form_add();
        }
    });
});

function removeMe(el) {
    var parent = el.parentNode.parentNode;
    parent.parentNode.removeChild(parent);
}
function load_list_data() {
    var nama_barang = $('#barang').val();
    var id_barang   = $('#id_barang').val();
    var kemasan     = $('#kemasan option:selected').text();
    var id_kemasan  = $('#kemasan').val();
    var ed          = $('#ed').val();
    var jumlah      = $('#pilih').val();
    var no   = $('.tr_rows').length+1;
    var list = '<tr class=tr_rows>'+
                    '<td align=center>'+no+'</td>'+
                    '<td><input type=text name=barang value="'+nama_barang+'" id=barang'+no+' size=50 /> <input type=hidden name=id_barang[] id=id_barang'+no+' value="'+id_barang+'" /></td>'+
                    '<td align=center id=kemasan'+no+'>'+kemasan+'<input type=hidden name=id_kemasan[] id=id_kemasan'+no+' value="'+id_kemasan+'" /></td>'+
                    '<td><input type=text name=ed[] id=ed'+no+' value="'+ed+'" size=10 style="text-align: center;" /></td>'+
                    '<td><input type=text name=jumlah[] id=jumlah'+no+' value="'+jumlah+'" size=10 style="text-align: center;" /></td>'+
                    '<td align=center class=aksi><img src="img/icons/delete.png" align=left title="Klik untuk hapus" onclick="removeMe(this);" /></td>'+
               '</tr>';
    $('#retur_penjualan-list tbody').append(list);
    $('input:text').on('keydown', function(e) {
        var n = $("input:text").length;
        if (e.keyCode === 13) {
            var nextIndex = $('input:text').index(this) + 1;
            if (nextIndex < n) {
                $('input:text')[nextIndex].focus();
            } else {
                $('input:text')[nextIndex-n].focus();
            }
        }
    });
    $('#barang,#id_barang,#ed,#pilih').val('');
    $('#kemasan').html('').append('<option value="">Pilih ...</option>');
    $('#barang').focus();
    $('#ed'+no).datepicker({
        changeMonth: true,
        changeYear: true
    });
}

function load_list_data_by_id_penjualan(id) {
    $('#retur_penjualan-list tbody').html('');
    $.getJSON('models/autocomplete.php?method=get_data_penjualan&id='+id, function(data){
        $.each(data, function (index, val) {
            //$('#kemasan').append("<option value='"+value.id_kemasan+"'>"+value.nama+"</option>");
            var nama_barang = val.nama_barang;
            var id_barang   = val.id_barang;
            var kemasan     = val.kemasan;
            var id_kemasan  = val.id_kemasan;
            var ed          = '';
            var jumlah      = val.qty;
            var no   = $('.tr_rows').length+1;
            var list = '<tr class=tr_rows>'+
                            '<td align=center>'+no+'</td>'+
                            '<td><input type=text name=barang value="'+nama_barang+'" id=barang'+no+' size=50 /> <input type=hidden name=id_barang[] id=id_barang'+no+' value="'+id_barang+'" /></td>'+
                            '<td align=center id=kemasan'+no+'>'+kemasan+'<input type=hidden name=id_kemasan[] id=id_kemasan'+no+' value="'+id_kemasan+'" /></td>'+
                            '<td><input type=text name=ed[] id=ed'+no+' value="'+ed+'" size=10 style="text-align: center;" /></td>'+
                            '<td><input type=text name=jumlah[] id=jumlah'+no+' value="'+jumlah+'" size=10 style="text-align: center;" /></td>'+
                            '<td align=center class=aksi><img src="img/icons/delete.png" align=left title="Klik untuk hapus" onclick="removeMe(this);" /></td>'+
                       '</tr>';
            $('#retur_penjualan-list tbody').append(list);
            $('input:text').on('keydown', function(e) {
                var n = $("input:text").length;
                if (e.keyCode === 13) {
                    var nextIndex = $('input:text').index(this) + 1;
                    if (nextIndex < n) {
                        $('input:text')[nextIndex].focus();
                    } else {
                        $('input:text')[nextIndex-n].focus();
                    }
                }
            });
            $('#barang,#id_barang,#ed,#pilih').val('');
            $('#kemasan').html('').append('<option value="">Pilih ...</option>');
            $('#barang').focus();
            $('#ed'+no).datepicker({
                changeMonth: true,
                changeYear: true
            });
        });
    });
    
}

function form_add() {
    var str = '<div id="retur_penjualan"><form id="save_retur_penjualan">'+
                '<input type=hidden name=id_retur_penjualan id=id_retur_penjualan />'+
                '<table width=100% class=data-input><tr valign=top><td width=50%>'+
                    '<table width=100%>'+
                        '<tr><td>Tanggal:</td><td><input type=text value="<?= date("d/m/Y") ?>" name=tanggal id=tanggal size=10 /></td></tr>'+
                        '<tr><td>Nomer Nota:</td><td><?= form_input('nonota', NULL, 'id=nonota size=40') ?></td></tr>'+
                        '<tr><td width=20%>Nama Barang:</td><td><?= form_input('barang', NULL, 'id=barang size=40') ?><?= form_hidden('id_barang', NULL, 'id=id_barang') ?></td></tr>'+
                        '<tr><td>Kemasan:</td><td><select name=id_kemasan id=kemasan style="min-width: 86px;"><option value="">Pilih ...</option></select></td></tr>'+
                        '<tr><td>Expired Date:</td><td><input type=text name=ed id=ed size=10 /><?= form_hidden(NULL, NULL, 'id=nobatch') ?></td></tr>'+
                        '<tr><td>Jumlah:</td><td><input type=text size=10 id=pilih /></td></tr>'+
                    '</table>'+
                    '</td><td width=50%>'+
                    
                '</td></tr></table>'+
                '<table width=100% cellspacing="0" class="list-data-input" id="retur_penjualan-list"><thead>'+
                    '<tr>'+
                        '<th width=8%>No.</th>'+
                        '<th width=60%>Nama Barang</th>'+
                        '<th width=10%>Kemasan</th>'+
                        '<th width=10%>ED</th>'+
                        '<th width=10%>Jumlah</th>'+
                        '<th width=2%>#</th>'+
                    '</tr></thead>'+
                    '<tbody></tbody>'+
                '</table>'+
              '</form></div>';
    $('body').append(str);
    var wWidth = $(window).width();
    var dWidth = wWidth * 1;
    
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    $('#retur_penjualan').dialog({
        title: 'Retur penjualan',
        autoOpen: true,
        modal: true,
        width: dWidth,
        height: dHeight,
        hide: 'clip',
        show: 'blind',
        buttons: {
            "Simpan": function() {
                $('#save_retur_penjualan').submit();
            }, 
            "Cancel": function() {    
                $(this).dialog().remove();
            }
        }, close: function() {
            $(this).dialog().remove();
        }, open: function() {
            $('#nonota').focus();
        }
    });
    
    $('#pilih').keydown(function(e) {
        if (e.keyCode === 13) {
            if ($('#id_barang').val() === '') {
                alert_empty('Barang','#barang'); return false;
            }
            if ($('#kemasan').val() === '') {
                alert_empty('Kemasan','#kemasan'); return false;
            }
            if ($('#ed').val() === '') {
                alert_empty('Expired date','#ed'); return false;
            }
            if ($('#pilih').val() === '') {
                alert_empty('Jumlah','#pilih'); return false;
            }
            load_list_data();
        }
    });
    $('#kemasan').keydown(function(e) {
        if (e.keyCode === 13) {
            if ($('#ed').val() === '') {
                $('#ed').focus();
            } else {
                $('#pilih').val('1').focus().select();
            }
        }
    });
    $('#kemasan').change(function() {
        if ($('#ed').val() === '') {
            $('#ed').focus();
        } else {
            $('#pilih').val('1').focus().select();
        }
    }); 
    var lebar = $('#supplier').width();
    $('#nonota').autocomplete("models/autocomplete.php?method=nonota",
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
            var str = '<div class=result>'+data.id+' - '+((data.pelanggan !== null)?data.pelanggan:'')+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0
    }).result(
    function(event,data,formated){
        $(this).val(data.id);
        load_list_data_by_id_penjualan(data.id);
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
        $('#kemasan').html('');
        $.getJSON('models/autocomplete.php?method=get_kemasan_barang&id='+data.id, function(data){
            if (data === null) {
                alert('Kemasan tidak barang tidak tersedia !');
            } else {
                $.each(data, function (index, value) {
                    $('#kemasan').append("<option value='"+value.id_kemasan+"'>"+value.nama+"</option>");
                });
            }
            $('#kemasan').focus();
        });
    });
    $('#supplier').autocomplete("models/autocomplete.php?method=supplier",
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
            var str = '<div class=result>'+data.nama+'<br/> '+data.alamat+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json' // tipe data yang diterima oleh library ini disetup sebagai JSON
    }).result(
    function(event,data,formated){
        $(this).val(data.nama);
        $('#id_supplier').val(data.id);
        $('#barang').focus().select();
    });
    $('#tanggal').datepicker();
    $('#ed').datepicker({
        changeYear: true,
        changeMonth: true,
        onSelect: function() {
            $('#pilih').val('1').focus().select();
        }
    });
    $('#save_retur_penjualan').submit(function() {
        if ($('#id_supplier').val() === '') {
            alert_empty('Supplier','#supplier'); return false;
        }
        $.ajax({
            url: 'models/update-transaksi.php?method=save_retur_penjualan',
            type: 'POST',
            dataType: 'json',
            data: $('#save_retur_penjualan').serialize(),
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    if (data.action === 'add') {
                        alert_tambah('#supplier');
                        $('#supplier, #id_supplier').val('');
                        load_data_retur_penjualan();
                        $('#retur_penjualan-list tbody').html('');
                    } else {
                        alert_edit();
                        load_data_retur_penjualan();
                    }
                }
            }
        });
        return false;
    });
}
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
    load_data_retur_penjualan();
});
function load_data_retur_penjualan(page, search, id) {
    pg = page; src = search; id_barg = id;
    if (page === undefined) { var pg = ''; }
    if (search === undefined) { var src = ''; }
    if (id === undefined) { var id_barg = ''; }
    $.ajax({
        url: 'pages/retur_penjualan-list.php',
        cache: false,
        data: 'page='+pg+'&search='+src+'&id_retur_penjualan='+id_barg,
        success: function(data) {
            $('#result-retur_penjualan').html(data);
        }
    });
}

function paging(page, tab, search) {
    load_data_retur_penjualan(page, search);
}
function delete_retur_penjualan(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                
                $.ajax({
                    url: 'models/update-transaksi.php?method=delete_retur_penjualan&id='+id,
                    cache: false,
                    success: function() {
                        load_data_retur_penjualan(page);
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
<h1 class="margin-t-0">Retur penjualan</h1>
<hr>
<button id="button">Tambah Retur (F9)</button>
<button id="reset">Reset</button>
<div id="result-retur_penjualan">
    
</div>