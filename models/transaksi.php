<?php

include_once '../config/database.php';

function pemesanan_load_data($param) {
    $q = NULL;
    if ($param['id'] !== '') {
        $q.="and p.id = '".$param['id']."' ";
    }
    if ($param['id_supplier'] !== '') {
        $q.=" and s.id = '".$param['id_supplier']."'";
    }
    $limit = " limit ".$param['start'].", ".$param['limit']."";
    $sql = "select p.*, k.nama as karyawan, dp.jumlah, concat_ws(' ',b.nama, b.kekuatan, st.nama) as nama_barang, st.nama as kemasan, s.nama as supplier from pemesanan p
        join supplier s on (p.id_supplier = s.id)
        join detail_pemesanan dp on (dp.id_pemesanan = p.id)
        join kemasan km on (km.id = dp.id_kemasan)
        join barang b on (b.id = km.id_barang)
        join satuan st on (st.id = km.id_kemasan)
        left join users u on (p.id_users = u.id)
        left join karyawan k on (u.id_karyawan = k.id)
        where p.id is not NULL $q";
    //echo $sql;
    $query = mysql_query($sql.$limit);
    $data = array();
    while ($row = mysql_fetch_object($query)) {
        $data[] = $row;
    }
    $total = mysql_num_rows(mysql_query($sql));
    $result['data'] = $data;
    $result['total']= $total;
    return $result;
}

function penerimaan_load_data($param) {
    $q = NULL;
    if ($param['id'] !== NULL) {
        $q.="and p.id = '".$param['id']."' ";
    }
    $limit = " limit ".$param['start'].", ".$param['limit']."";
    $sql = "select p.*, k.nama as karyawan, s.nama as supplier from penerimaan p
        left join pemesanan ps on (p.id_pemesanan = ps.id)
        join supplier s on (ps.id_supplier = s.id)
        left join users u on (p.id_users = u.id)
        left join karyawan k on (u.id_karyawan = k.id)
        where p.id is not NULL";
    $query = mysql_query($sql.$limit);
    $data = array();
    while ($row = mysql_fetch_object($query)) {
        $data[] = $row;
    }
    $total = mysql_num_rows(mysql_query($sql));
    $result['data'] = $data;
    $result['total']= $total;
    return $result;
}

function retur_penerimaan_load_data($param) {
    $q = NULL;
    if ($param['id'] !== '') {
        $q.="and rp.id = '".$param['id']."' ";
    }
    $limit = " limit ".$param['start'].", ".$param['limit']."";
    $sql = "select rp.tanggal, rp.id_supplier, st.nama as kemasan, b.nama as barang, b.kekuatan, 
        stn.nama as satuan, dp.*, s.nama as supplier from retur_penerimaan rp
        join detail_retur_penerimaan dp on (rp.id = dp.id_retur_penerimaan)
        join supplier s on (rp.id_supplier = s.id)
        join kemasan k on (k.id = dp.id_kemasan)
        join barang b on (b.id = k.id_barang)
        join satuan st on (st.id = k.id_kemasan)
        left join satuan stn on (stn.id = b.satuan_kekuatan)
        where rp.id is not NULL $q order by rp.id";
    //echo $sql;
    $query = mysql_query($sql.$limit);
    $data = array();
    while ($row = mysql_fetch_object($query)) {
        $data[] = $row;
    }
    $total = mysql_num_rows(mysql_query($sql));
    $result['data'] = $data;
    $result['total']= $total;
    return $result;
}

function load_data_stok_opname($param) {
    $q = NULL;
    if ($param['id'] !== '') {
        $q.="and s.id = '".$param['id']."' ";
    }
    $limit = " limit ".$param['start'].", ".$param['limit']."";
    $sql = "select s.*, b.kekuatan, b.nama, st.nama as satuan_kekuatan, sum(s.masuk) as masuk, sum(s.keluar) as keluar, (sum(s.masuk)-sum(s.keluar)) as sisa from stok s 
        join barang b on (s.id_barang = b.id)
        left join satuan st on (b.satuan_kekuatan = st.id)
        where s.id is not NULL $q group by s.id_barang";
    //echo $sql;
    $query = mysql_query($sql.$limit);
    $data = array();
    while ($row = mysql_fetch_object($query)) {
        $data[] = $row;
    }
    $total = mysql_num_rows(mysql_query($sql));
    $result['data'] = $data;
    $result['total']= $total;
    return $result;
}

function load_data_arus_stok($param) {
    $q = NULL; $limit = NULL;
    if ($param['id'] !== '') {
        $q.=" and s.id_barang = '".$param['id']."' ";
    }
    if ($param['awal'] !== '' and $param['akhir'] !== '') {
        $q.=" and date(s.waktu) between '".$param['awal']."' and '".$param['akhir']."'";
    }
    if ($param['limit'] !== '') {
        $limit = " limit ".$param['start'].", ".$param['limit']."";
    }
    $sql = "select s.*, b.kekuatan, b.nama, st.nama as satuan_kekuatan from stok s 
        join barang b on (s.id_barang = b.id)
        left join satuan st on (b.satuan_kekuatan = st.id)
        where s.id is not NULL $q order by s.waktu asc";
    //echo $sql.$limit;
    $query = mysql_query($sql.$limit);
    $data = array();
    while ($row = mysql_fetch_object($query)) {
        $data[] = $row;
    }
    $total = mysql_num_rows(mysql_query($sql));
    $result['data'] = $data;
    $result['total']= $total;
    return $result;
}

function load_data_resep($param) {
    $q = NULL;
    if ($param['id'] !== '') {
        $q.="and r.id = '".$param['id']."' ";
    }
    if (isset($param['awal'])) {
        $q.=" and date(r.waktu) between '".$param['awal']."' and '".$param['akhir']."'";
    }
    if (isset($param['dokter']) and $param['dokter'] !== '') {
        $q.=" and r.id_dokter = '".$param['dokter']."'";
    }
    if (isset($param['pasien']) and $param['pasien'] !== '') {
        $q.=" and r.id_pasien = '".$param['pasien']."'";
    }
    $limit = NULL;
    if (isset($param['start'])) {
        $limit = " limit ".$param['start'].", ".$param['limit']."";
    }
    $sql   = "select r.*, rr.id_resep, rr.id_barang, rr.id_tarif, rr.r_no, concat_ws(' ',b.nama, b.kekuatan, s.nama) as nama_barang, 
        rr.dosis_racik, rr.jumlah_pakai, rr.jual_harga, d.nama as dokter, k.nama as apoteker, t.nama as tarif, p.nama as pasien, 
        rr.resep_r_jumlah, 
        rr.tebus_r_jumlah, rr.pakai, rr.aturan, rr.iter, rr.nominal from resep r
        join resep_r rr on (r.id = rr.id_resep)
        join barang b on (b.id = rr.id_barang)
        left join satuan s on (b.satuan_kekuatan = s.id)
        left join tarif t on (t.id = rr.id_tarif)
        left join pelanggan p on (p.id = r.id_pasien)
        left join dokter d on (d.id = r.id_dokter)
        left join karyawan k on (k.id = rr.id_karyawan)
        where r.id is not NULL $q
    ";
    //echo $sql;
    $query = mysql_query($sql.$limit);
    $data = array();
    while ($row = mysql_fetch_object($query)) {
        $data[] = $row;
    }
    $total = mysql_num_rows(mysql_query($sql));
    $result['data'] = $data;
    $result['total']= $total;
    return $result;
}

function check_penjualan_availability($id_resep) {
    $sql = "select p.*, sum(db.bayar) as terbayar, (p.total-sum(db.bayar)) as sisa from penjualan p 
        join detail_bayar_penjualan db on (p.id = db.id_penjualan) 
        where p.id_resep = '$id_resep' group by db.id_penjualan";
    $query = mysql_query($sql);
    $rows  = mysql_fetch_object($query);
    return $rows;
}

function penjualan_nr_load_data($param) {
    $q = NULL;
    if ($param['id'] !== '') {
        $q.="and p.id = '".$param['id']."' ";
    }
    $limit = " limit ".$param['start'].", ".$param['limit']."";
    $sql = "select p.*, date(p.waktu) as tanggal, pl.nama as customer, a.nama as asuransi,
        (select sum(bayar) from detail_bayar_penjualan where id_penjualan = p.id) as terbayar,
        concat_ws(' ',b.nama,b.kekuatan,s.nama) as nama_barang, st.nama as kemasan, dp.qty, dp.harga_jual, (dp.harga_jual*dp.qty) as subtotal
        from penjualan p
        join detail_penjualan dp on (p.id = dp.id_penjualan)
        join kemasan k on (k.id = dp.id_kemasan)
        join barang b on (k.id_barang = b.id)
        left join satuan s on (b.satuan_kekuatan = s.id)
        left join satuan st on (k.id_kemasan = st.id)
        left join pelanggan pl on (p.id_pelanggan = pl.id)
        left join asuransi a on (pl.id_asuransi = a.id) 
        where p.id_resep is NULL $q order by p.waktu desc";
    $query = mysql_query($sql.$limit);
    $data = array();
    while ($row = mysql_fetch_object($query)) {
        $data[] = $row;
    }
    $total = mysql_num_rows(mysql_query($sql));
    $result['data'] = $data;
    $result['total']= $total;
    return $result;
}

function penjualan_load_data($param) {
    $q = NULL;
    if ($param['id'] !== '') {
        $q.="and p.id = '".$param['id']."' ";
    }
    $limit = " limit ".$param['start'].", ".$param['limit']."";
    $sql = "select p.*, date(p.waktu) as tanggal, pl.nama as customer, pl.id as id_customer, a.nama as asuransi, 
        (select sum(bayar) from detail_bayar_penjualan where id_penjualan = p.id) as terbayar,
        concat_ws(' ',b.nama,b.kekuatan,s.nama) as nama_barang, st.nama as kemasan, dp.qty, dp.harga_jual, (dp.harga_jual*dp.qty) as subtotal
        from penjualan p
        join detail_penjualan dp on (p.id = dp.id_penjualan)
        join kemasan k on (k.id = dp.id_kemasan)
        join barang b on (k.id_barang = b.id)
        left join satuan s on (b.satuan_kekuatan = s.id)
        left join satuan st on (k.id_kemasan = st.id)
        left join pelanggan pl on (p.id_pelanggan = pl.id)
        left join asuransi a on (pl.id_asuransi = a.id) 
        join resep r on (p.id_resep = r.id)
        where p.id_resep is not NULL $q order by p.waktu desc";
    $query = mysql_query($sql.$limit);
    $data = array();
    while ($row = mysql_fetch_object($query)) {
        $data[] = $row;
    }
    $total = mysql_num_rows(mysql_query($sql));
    $result['data'] = $data;
    $result['total']= $total;
    return $result;
}

function penjualan_load_data_barang($id) {
    $sql = "select b.*, s.nama as satuan, dp.qty, dp.harga_jual, 
        p.waktu, p.total, p.tuslah, p.embalage, p.ppn, p.diskon_persen, p.diskon_rupiah, p.id_resep, pl.nama as pelanggan,
        (dp.qty*dp.harga_jual) as subtotal from detail_penjualan dp
        join penjualan p on (dp.id_penjualan = p.id)
        left join pelanggan pl on (p.id_pelanggan = pl.id)
        join kemasan k on (dp.id_kemasan = k.id)
        join barang b on (k.id_barang = b.id)
        left join satuan s on (b.satuan_kekuatan = s.id)
        where dp.id_penjualan = '$id'";
    $query = mysql_query($sql);
    $data = array();
    while ($row = mysql_fetch_object($query)) {
        $data[] = $row;
    }
    return $data;
}
?>