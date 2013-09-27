<?php
set_include_path("../");
include_once("inc/essentials.php");
include_once("inc/functions.php");
include_once("models/transaksi.php");
include_once("pages/message.php");
?>

<script type="text/javascript">
$(document).tooltip();
load_data_expired();
$mainNav.set("home");
function load_data_expired(page, search, id) {
    pg = page; src = search; id_barg = id;
    if (page === undefined) { var pg = ''; }
    if (search === undefined) { var src = ''; }
    if (id === undefined) { var id_barg = ''; }
    $.ajax({
        url: 'pages/expired-list.php',
        cache: false,
        data: 'page='+pg+'&search='+src+'&id_expired='+id_barg,
        success: function(data) {
            $('#result-expired').html(data);
        }
    });
}

function paging(page, tab, search) {
    load_data_expired(page, search);
}


</script>
<h1 class="margin-t-0">Laporan Kadaluarsa Barang</h1>
<hr>

<div id="result-expired">
    
</div>