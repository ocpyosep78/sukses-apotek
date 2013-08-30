<?php
include_once '../models/masterdata.php';
include_once '../inc/functions.php';
?>
<table cellspacing="0" width="100%" class="list-data">
    <thead>
        <tr>
            <th width="5%">No.</th>
            <th width="20%">Nama Item</th>
            <th width="5%">Margin %</th>
            <th width="8%">Margin Rp.</th>
            <th width="5%">Diskon %</th>
            <th width="8%">Diskon Rp.</th>
            <th width="20%">Nama Barang</th>
            <th width="5%">Kemasan</th>
            <th width="5%">Jumlah</th>
            <th width="2%">#</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $limit = 10;
        $page  = $_GET['page'];
        if ($_GET['page'] === '') {
            $page = 1;
            $offset = 0;
        } else {
            $offset = ($page-1)*$limit;
        }

        $param = array(
            'id' => $_GET['id_itemkit'],
            'limit' => $limit,
            'start' => $offset,
            'search' => $_GET['search']
        );
        $master_item_kit = item_kit_load_data($param);
        $list_data = $master_item_kit['data'];
        $total_data= $master_item_kit['total'];
        foreach ($list_data as $key => $data) { ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php } ?>
    </tbody>
</table>