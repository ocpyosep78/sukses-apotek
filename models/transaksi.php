<?php

include_once '../config/database.php';

function header_surat() {
    $sql = "select * from apotek";
    $result = mysql_query($sql);
    $data   = mysql_fetch_object($result);
    echo "<h1 class=kop-surat>".$data->nama."<br/>".$data->alamat."<br/>".$data->telp." ".$data->email."</h1>";
}

function get_bottom_label() {
    $sql = "select * from apotek";
    $result = mysql_query($sql);
    $data   = mysql_fetch_object($result);
    return $data;
}

function get_apa_from_karyawan() {
    $sql = "select * from karyawan where jabatan = 'APA'";
    $result = mysql_query($sql);
    $data   = mysql_fetch_object($result);
    return $data;
}

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
        where p.id is not NULL $q order by p.tanggal desc";
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
    } else {
        $q.="and date(p.waktu) between '".date("Y-m-d")."' and '".date("Y-m-d")."'";
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

function penjualan_load_data($param) {
    $q = NULL; $limit = NULL;
    if ($param['id'] !== '') {
        $q.=" and p.id = '".$param['id']."' ";
    } 
    if (isset($param['pasien']) and $param['pasien'] !== '') {
        $q.=" and p.id_pelanggan = '".$param['pasien']."'";
    }
    if (isset($param['dokter']) and $param['dokter'] !== '') {
        $q.=" and r.id_dokter = '".$param['dokter']."'";
    }
    if (isset($param['laporan'])) {
        $q.=" and date(p.waktu) between '".$param['awal']."' and '".$param['akhir']."'";
        $q.=" group by p.id";
    } else {
        $q.=" and date(p.waktu) between '".date("Y-m-d")."' and '".date("Y-m-d")."'";
        $limit = " limit ".$param['start'].", ".$param['limit']."";
    }
    
    $sql = "select p.*, date(p.waktu) as tanggal, pl.nama as customer, pl.id as id_customer, a.nama as asuransi, d.nama as dokter, 
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
        left join dokter d on (r.id_dokter = d.id)
        where p.id_resep is not NULL $q order by p.waktu desc";
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

function pemeriksaan_load_data($param) {
    $q = NULL;
    if ($param['id'] !== '') {
        $q.=" and p.id = '".$param['id']."'";
    }
    $limit = " limit ".$param['start'].", ".$param['limit']."";
    $sql = "select p.*, pl.nama as pasien, d.nama as dokter, py.topik, py.sub_kode, tr.id as id_tarif, tr.nama as tarif, t.nominal from pemeriksaan p
        join pendaftaran pd on (p.id_pendaftaran = pd.id)
        join pelanggan pl on (pd.id_pelanggan = pl.id)
        join dokter d on (p.id_dokter = d.id)
        left join diagnosis dg on (p.id = dg.id_pemeriksaan)
        left join penyakit py on (dg.id_penyakit = py.id)
        left join tindakan t on (p.id = t.id_pemeriksaan)
        left join tarif tr on (t.id_tarif = tr.id)
        where p.id is not NULL $q order by p.tanggal desc";
    
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

function inkaso_load_data($param) {
    $q = NULL;
    if ($param['id'] !== '') {
        $q.=" and i.id = '".$param['id']."'";
    }
    if ($param['search'] !== '') {
        $q.=" and i.no_ref like ('%".$param['search']."%') or s.nama like ('%".$param['search']."%') or b.nama like ('%".$param['search']."%')";
    }
    $limit = " limit ".$param['start'].", ".$param['limit']."";
    $sql = "select i.*, p.faktur, s.nama supplier, b.nama as bank from inkaso i
        join penerimaan p on (i.id_penerimaan = p.id)
        join supplier s on (p.id_supplier = s.id)
        left join bank b on (i.id_bank = b.id) where i.id is not NULL $q";
    
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
function load_data_defecta($param) {
    $q = NULL;
    if ($param['id'] !== '') {
        $q.=" and s.id = '".$param['id']."' ";
    }
    if ($param['search'] !== '') {
        $q.=" and b.nama like ('%".$param['search']."%')";
    }
    $limit = " limit ".$param['start'].", ".$param['limit']."";
    $sql = "select s.*, b.kekuatan, b.id as id_barang, b.nama, b.stok_minimal, st.nama as satuan_kekuatan, sum(s.masuk) as masuk, 
        sum(s.keluar) as keluar, (sum(s.masuk)-sum(s.keluar)) as sisa 
        from stok s 
        join barang b on (s.id_barang = b.id) 
        left join satuan st on (b.satuan_kekuatan = st.id) 
        where b.id not in (select id_barang from defecta where status = '0') $q
        group by s.id_barang  
        having sisa <= b.stok_minimal order by b.nama";
    
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

function get_distributor_by_barang($id_barang) {
    $sql = mysql_query("select s.nama from supplier s
        join penerimaan p on (s.id = p.id_supplier)
        join detail_penerimaan dp on (p.id = dp.id_penerimaan)
        join kemasan k on (k.id = dp.id_kemasan)
        join barang b on (k.id_barang = b.id)
        inner join (
            select id_kemasan, max(id) as id_max from detail_penerimaan group by id_kemasan
        ) dm on (dp.id_kemasan = dm.id_kemasan and dp.id = dm.id_max)
        where b.id = '$id_barang'
    ");
    $row = mysql_fetch_object($sql);
    return $row;
}

function pemesanan_plant_load_data($param = NULL) {
    $limit = NULL;
    if (isset($param['list'])) {
        $limit = " limit ".$param['start'].", ".$param['limit']."";
    }
    $sql = "select d.*, concat_ws(' ',b.nama, b.kekuatan, s.nama) as nama_barang from defecta d
        join barang b on (d.id_barang = b.id)
        left join satuan s on (b.satuan_kekuatan = s.id) where status = '0' order by b.nama";
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

function load_data_pendaftaran($param) {
    $limit = " limit ".$param['start'].", ".$param['limit']."";
    $sql = "select p.*, pl.nama, s.nama as spesialisasi, d.nama as dokter, pm.id as id_pemeriksaan from pendaftaran p
        join pelanggan pl on (p.id_pelanggan = pl.id)
        join spesialisasi s on (p.id_spesialisasi = s.id)
        left join dokter d on (p.id_dokter = d.id)
        left join pemeriksaan pm on (p.id = pm.id_pendaftaran)
        where date(p.waktu) = '".date("Y-m-d")."' order by s.id, p.no_antri";
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

function cetak_no_antri($id_daftar) {
    $sql = "select p.*, pl.nama from pendaftaran p
        join pelanggan pl on (p.id_pelanggan = pl.id)
        where p.id = '$id_daftar'";
    $result = mysql_query($sql);
    $rows   = mysql_fetch_object($result);
    return $rows;
}
?>