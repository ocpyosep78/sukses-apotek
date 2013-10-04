<?php
$subNav = array(
    "Keluar / Masuk Uang ; uang-in-out.php ; #509601;",
    "Set Kas Awal ; set-kas-awal.php ; #509601;",
);
set_include_path("../");
include_once("inc/essentials.php");
include_once("inc/functions.php");
include_once("models/masterdata.php");
include_once("pages/message.php");
?>
<script type="text/javascript">
$(function() {
    load_data_set_kas_awal();
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
        load_data_set_kas_awal();
    });
});

function form_add() {
    var str = '<div id="form_kas_awal">'+
            '<form id="save_kas_awal">'+
                '<?= form_hidden('id_shift', NULL, 'id=id_shift') ?>'+
                '<table width="100%" class=data-input>'+
                    '<tr><td>Tanggal:</td><td><?= form_input('tanggal', date("d/m/Y"), 'size=10 id=tanggal') ?></td></tr>'+
                    '<tr><td>Uang Awal:</td><td><?= form_input('uang_awal', NULL, 'size=10 id=uang_awal onkeyup="FormNum(this)"') ?></td></tr>'+
                    '<tr><td>Pendapatan Resep:</td><td><?= form_input('pend_resep', NULL, 'size=10 id=pend_resep readonly') ?></td></tr>'+
                    '<tr><td>Pendapatan Non Resep:</td><td><?= form_input('pend_nresep', NULL, 'size=10 id=pend_nresep readonly') ?></td></tr>'+
                    '<tr><td>Total:</td><td><?= form_input('total', NULL, 'size=10 id=total disabled') ?></td></tr>'+
                    '<tr><td>Uang Real:</td><td><?= form_input('uang_real', NULL, 'size=10 id=uang_real onkeyup="FormNum(this)"') ?></td></tr>'+
                    '<tr><td>Selisih:</td><td><?= form_input('selisih', NULL, 'size=10 id=selisih disabled') ?></td></tr>'+
                    '<tr><td>Keterangan:</td><td><?= form_input('keterangan', NULL, 'id=keterangan size=40') ?></td></tr>'+
                '</table>'+
            '</form>'+
          '</div>';
    $('body').append(str);
    $('#form_kas_awal').dialog({
        title: 'Form Kas Awal',
        autoOpen: true,
        modal: true,
        width: 500,
        height: 360,
        hide: 'clip',
        show: 'blind',
        buttons: {
            "Simpan (F8)": function() {
                $('#save_kas_awal').submit();
            },
            "Cancel": function() {
                  $(this).dialog().remove();
            }
        },
        close: function() {
              $(this).dialog().remove();
        },
        open: function() {
//            $.ajax({
//                url: 'models/autocomplete.php?method=',
//                dataType: 'json',
//                success: function(data) {
//                    $('#noref').val(data.in);
//                }
//            });
            $('#uang_awal').focus().select();
        }
    });
    $('#uang_real').keyup(function() {
        selisih();
    });
    $('#save_kas_awal').submit(function() {
        if ($('#uang_awal').val() === '') {
            alert_empty('Uang awal','#uang_awal'); return false;
        }
        $.ajax({
            url: 'models/update-transaksi.php?method=save_set_kas_awal',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    alert_refresh('Set kas awal berhasil dilakukan');
                } else {
                    alert_dinamic('Anda tidak di perbolehkan menambahkan data kas awal lagi');
                }
            }
        });
        return false;
    });
}
function load_data_set_kas_awal(page, search, id) {
    pg = page; src = search; id_barg = id;
    if (page === undefined) { var pg = ''; }
    if (search === undefined) { var src = ''; }
    if (id === undefined) { var id_barg = ''; }
    $.ajax({
        url: 'pages/set_kas_awal-list.php',
        cache: false,
        data: 'page='+pg+'&search='+src+'&id_set_kas_awal='+id_barg,
        success: function(data) {
            $('#result-set_kas_awal').html(data);
        }
    });
}

function paging(page, tab, search) {
    load_data_set_kas_awal(page, search);
}

function edit_kas_awal(data) {
    form_add();
    var arr = data.split('#');
    $('#id_shift').val(arr[0]);
    $('#uang_awal').val(numberToCurrency(arr[1]));
    $('#uang_real').val(numberToCurrency(arr[2]));
}

function cetak_kas_awal(id_shift, shift_ke, tanggal) {
    var wWidth = $(window).width();
    var dWidth = wWidth * 0.3;
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    var x = screen.width/2 - dWidth/2;
    var y = screen.height/2 - dHeight/2;
    window.open('pages/set-kas-awal-print.php?id='+id_shift+'&shift_ke='+shift_ke+'&tanggal='+tanggal,'Resep Shift','width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
}

function tutup_kas_awal(data) {
    form_add();
    var arr = data.split('#');
    $('#id_shift').val(arr[0]);
    $('#uang_awal').val(numberToCurrency(arr[1]));
    $('#uang_real').val(numberToCurrency(arr[2]));
    $.ajax({
        url: 'models/autocomplete.php?method=get_pemasukan_penjualan&id_shift='+arr[0],
        dataType: 'json',
        cache: false,
        success: function(data) {
            $('#pend_resep').val(numberToCurrency(parseInt(data.resep)));
            $('#pend_nresep').val(numberToCurrency(parseInt(data.nonresep)));
            $('#total').val(numberToCurrency(parseInt(data.total_pendapatan)));
            $('#uang_real').focus().select();
        }
    });
}

function selisih() {
    var total = parseInt(currencyToNumber($('#total').val()));
    var ureal = parseInt(currencyToNumber($('#uang_real').val()));
    var selisih = ureal - total;
    if (selisih <= 0) {
        $('#selisih').val(selisih);
    } else {
        $('#selisih').val(numberToCurrency(parseInt(selisih)));
    }
}
</script>
<h1 class="margin-t-0">Set Kas Awal Shift</h1>
<hr>
<button id="button">Tambah Data</button>
<button id="reset">Reset</button>
<div id="result-set_kas_awal">
    
</div>