<?php
$subNav = array(
        "Stok Opname ; stok-opname.php ; #509601;",
        "Koreksi Stok ; koreksi-stok.php ; #509601;",
);
set_include_path("../");
include_once("inc/essentials.php");
include_once("inc/functions.php");
include_once("models/transaksi.php");
include_once("pages/message.php");
?>

<script type="text/javascript">
$(function() {
    $('#search').keyup(function() {
        var value = $(this).val();
        load_data_koreksi_stok('',value,'');
    });
});
$(document).tooltip();
load_data_koreksi_stok();
$mainNav.set("home");
$('#button').button({
    icons: {
        primary: 'ui-icon-newwin'
    }
});
$('#button').click(function() {
    form_add();
});
$('#reset').button({
    icons: {
        primary: 'ui-icon-refresh'
    }
}).click(function() {
    load_data_koreksi_stok();
});
$.plugin($afterSubPageShow,{ // <-- event is here
    showAlert:function(){ // <-- random function name is here (choose whatever you want)
    /* The code that will be executed */
    }
});
function load_data_koreksi_stok(page, search, id) {
    pg = page; src = search; id_barg = id;
    if (page === undefined) { var pg = ''; }
    if (search === undefined) { var src = ''; }
    if (id === undefined) { var id_barg = ''; }
    $.ajax({
        url: 'pages/koreksi-stok-list.php',
        cache: false,
        data: 'page='+pg+'&search='+src+'&id_koreksi_stok='+id_barg,
        success: function(data) {
            $('#result-koreksi_stok').html(data);
        }
    });
}

function paging(page, tab, search) {
    load_data_koreksi_stok(page, search);
}

function edit_koreksi_stok(str) {
    
    var arr = str.split('#');
    form_add();
    $('#form_add').dialog({ title: 'Edit koreksi_stok' });
    $('#id_koreksi_stok').val(arr[0]);
    $('#nama').val(arr[1]);
    if (arr[2] === 'P') { $('#prm').attr('checked','checked'); }
    if (arr[2] === 'L') { $('#l').attr('checked','checked'); }
    $('#alamat').val(arr[3]);
    $('#telp').val(arr[4]);
    $('#email').val(arr[5]);
    $('#nostr').val(arr[6]);
    $('#spesialis').val(arr[7]);
    $('#tglmulai').val(arr[8]);
    
}

function delete_koreksi_stok(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                $.ajax({
                    url: 'models/update-transaksi.php?method=delete_koreksi_stok&id='+id,
                    cache: false,
                    success: function() {
                        load_data_koreksi_stok(page);
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
<h1 class="margin-t-0">Koreksi Stok</h1>
<hr>
<button id="button">Tambah Data</button>
<button id="reset">Reset</button>
<?= form_input('search', NULL, 'id=search placeholder="Search ..." class=search') ?>
<div id="result-koreksi_stok">
    
</div>