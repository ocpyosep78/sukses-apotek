<?php
include_once '../config/database.php';
include_once '../inc/functions.php';

function print_pemesanan($id) {
    $sql = mysql_query("select p.*, k.nama as karyawan, dp.jumlah, concat_ws(' ',b.nama, b.kekuatan, st.nama) as nama_barang, 
        st.nama as kemasan, s.nama as supplier, s.alamat as alamat_supplier from pemesanan p
        join supplier s on (p.id_supplier = s.id)
        join detail_pemesanan dp on (dp.id_pemesanan = p.id)
        join kemasan km on (km.id = dp.id_kemasan)
        join barang b on (b.id = km.id_barang)
        join satuan st on (st.id = km.id_kemasan)
        left join users u on (p.id_users = u.id)
        left join karyawan k on (u.id_karyawan = k.id)
        where p.id = '$id'
    ");
    $data = array();
    while ($row = mysql_fetch_object($sql)) {
        $data[] = $row;
    }
    return $data;
}
?>
