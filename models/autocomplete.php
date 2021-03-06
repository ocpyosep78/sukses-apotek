<?php
include_once '../config/database.php';
$method = isset($_GET['method'])?$_GET['method']:NULL;
$q      = isset($_GET['q'])?$_GET['q']:NULL;
if ($method === 'login') {
    session_start();
    $username = strip_tags($_POST['username']);
    $password = md5(strip_tags($_POST['password']));
    $query  = "select u.*, k.nama from users u 
        join karyawan k on (u.id_karyawan = k.id) 
        where u.username = '$username' and u.password = '$password'";
    $result = mysql_query($query);
    $data   = mysql_fetch_object($result);
    if (isset($data->username)) {
        $_SESSION['username'] = $data->username;
        $_SESSION['password'] = $data->password;
        $_SESSION['id_user']  = $data->id;
        $_SESSION['level']    = $data->level;
    } else {
        $_SESSION['username'] = NULL;
    }
    die(json_encode($_SESSION));
}
if ($method === 'pabrik') {
    $rows = array();
    $sql = mysql_query("select * from pabrik where nama like ('%$q%') order by locate('$q',nama)");
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'dokter') {
    $sql = mysql_query("select * from dokter where nama like ('%$q%') order by locate('$q', nama)");
    $rows = array();
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'pasien') {
    $sql = mysql_query("select p.*, a.nama as asuransi, a.diskon as reimburse
        from pelanggan p
        left join asuransi a on (p.id_asuransi = a.id) 
        where p.nama like ('%$q%') or p.id like ('%$q%') order by locate('$q', p.id)");
    $rows = array();
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'pasien_pemeriksaan') {
    $sql = mysql_query("select p.*, a.nama as asuransi, a.diskon as reimburse, 
        dp.rpd, dp.rpk, dp.ps, dp.oh, dp.al, dp.dl, dp.mk, dp.ka
        from pelanggan p
        left join asuransi a on (p.id_asuransi = a.id) 
        left join detail_pasien dp on (p.id = dp.id_pasien)
        where p.nama like ('%$q%') or p.id like ('%$q%') order by locate('$q', p.id)");
    $rows = array();
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'get_photo_pemeriksaan') {
    $sql = mysql_query("select pd.*, p.foto from pendaftaran pd
        left join pemeriksaan p on (pd.id = p.id_pendaftaran) 
        where pd.id_pelanggan = '".$_GET['id_pelanggan']."' order by pd.waktu desc limit 1");
    $row = mysql_fetch_object($sql);
    die(json_encode($row));
}

if ($method === 'get_penyakit_by_pasien') {
    $sql = mysql_query("select py.* from penyakit_pasien pp
        join pelanggan pl on (pp.id_pasien = pl.id)
        join penyakit py on (pp.id_penyakit = py.id)");
    $rows = array();
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'get_data_kemasan') {
    $id = $_GET['id'];
    $sql = mysql_query("select s.id, s.nama as kemasan, st.nama as satuan_kecil, k.isi from kemasan k
        join satuan s on (k.id_kemasan = s.id)
        left join satuan st on (k.id_satuan = st.id)
        where k.id_barang = '$id'");
    $rows = array();
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'supplier') {
    $rows = array();
    $sql = mysql_query("select * from supplier where nama like ('%$q%') order by locate('$q',nama)");
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'barang') {
    $rows = array();
    $sql = mysql_query("select b.*, p.nama as pabrik, g.nama as golongan, st.nama as satuan, sd.nama as sediaan,
        concat_ws(' ', b.nama, b.kekuatan, st.nama) as nama_barang
        from barang b 
        left join pabrik p on (b.id_pabrik = p.id)
        left join golongan g on (b.id_golongan = g.id)
        left join satuan st on (b.satuan_kekuatan = st.id)
        left join sediaan sd on (b.id_sediaan = sd.id) having nama_barang like ('%$q%')");
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'get_barang') {
    $barcode = $_GET['barcode'];
    $sql = mysql_query("select b.*, k.id as id_packing, p.nama as pabrik, g.nama as golongan, st.nama as satuan, sd.nama as sediaan,
        concat_ws(' ', b.nama, b.kekuatan, st.nama) as nama_barang
        from barang b 
        join kemasan k on (b.id = k.id_barang)
        left join pabrik p on (b.id_pabrik = p.id)
        left join golongan g on (b.id_golongan = g.id)
        left join satuan st on (b.satuan_kekuatan = st.id)
        left join sediaan sd on (b.id_sediaan = sd.id) where b.barcode = '$barcode' and default_kemasan = '1'");
    $data = mysql_fetch_object($sql);
    die(json_encode($data));
}

if ($method === 'farmakoterapi') {
    $rows = array();
    $id   = $_GET['id'];
    $sql = mysql_query("select * from kelas_terapi where id_farmako_terapi = '$id'");
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'golongan_load_data') {
    $id   = $_GET['id'];
    $sql = mysql_query("select * from golongan where id = '$id'");
    $data = mysql_fetch_object($sql);
    die(json_encode($data));
}

if ($method === 'nofaktur') {
    $sql = mysql_query("select * from penerimaan p join supplier");
}

if ($method === 'get_detail_barang_by_ed') {
    $id_barang  = $_GET['id'];
    $ed         = date2mysql($_GET['ed']);
    $kemasan    = $_GET['kemasan'];
    $sql = mysql_query("select dp.* from kemasan k
        join barang b on (k.id_barang = b.id) 
        join detail_penerimaan dp on (k.id = dp.id_kemasan)
        where b.id = '$id_barang' and k.id = '$kemasan' and dp.expired = '$ed'");
    $row = mysql_fetch_object($sql);
    die(json_encode($row));
}

if ($method === 'get_kemasan_barang') {
    $id = $_GET['id'];
    $rows = NULL;
    $sql = mysql_query("select k.id, k.default_kemasan, k.id_kemasan, s.nama, k.isi, k.isi_satuan 
        from kemasan k 
        join satuan s on (k.id_kemasan = s.id) 
        where k.id_barang = '$id' order by k.id desc");
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'get_nomor_sp') {
    $sql = mysql_query("select p.*, s.nama as supplier 
        FROM pemesanan p 
        join supplier s on (p.id_supplier = s.id) 
        where p.id not in (select id_pemesanan from penerimaan where id_pemesanan is not NULL) and p.id like ('%$q%') order by locate('$q',p.id)");
    $rows = array();
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'get_attr_penerimaan') {
    $query= mysql_query("select penerimaan from config_autonumber");
    $auto = mysql_fetch_object($query);
    
    $sql  = mysql_query("select substr(faktur, 4, 6) as id from penerimaan order by id desc limit 1");
    $row  = mysql_fetch_object($sql);
    
    
    if (isset($row->id)) {
        $last_faktur = $auto->penerimaan.str_pad((string)($row->id+1), 6, "0", STR_PAD_LEFT);
    } else {
        $last_faktur = $auto->penerimaan.'000001';
    }
    
    $date = mktime(0, 0, 0, date("m"), date("d")+30, date("Y"));
    $tempo= date("d/m/Y",$date);
    die(json_encode(array('faktur' => $last_faktur, 'tempo' => $tempo)));
}

if ($method === 'get_data_pemesanan_penerimaan') {
    $id = $_GET['id'];
    $sql = "select b.id as id_barang, b.nama, b.hna, b.kekuatan, st.nama as satuan_kekuatan, 
        s.id as id_kemasan, s.nama as kemasan, k.id, dp.jumlah, k.isi, k.isi_satuan 
        from detail_pemesanan dp
        join kemasan k on (k.id = dp.id_kemasan)
        join barang b on (b.id = k.id_barang)
        left join satuan s on (k.id_kemasan = s.id)
        left join satuan st on (b.satuan_kekuatan = st.id) where dp.id_pemesanan = '$id'";
    $result = mysql_query($sql);
    while ($data = mysql_fetch_object($result)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'get_stok_sisa') {
    $id  = $_GET['id'];
    $sql = mysql_query("select (sum(masuk)-sum(keluar)) as sisa from stok where id_barang = '$id'");
    $row = mysql_fetch_object($sql);
    
    die(json_encode($row));
}

if ($method === 'get_detail_harga_barang_pemesanan') { // ambil data barang resep
    $id = $_GET['id']; // id barang
    $id_kemasan = $_GET['id_kemasan'];
    $query = mysql_query("select b.*, (b.hna*k.isi_satuan*k.isi) as esti, k.id as id_packing from barang b
        join kemasan k on (b.id = k.id_barang)
        where k.id_barang = '$id' and k.id_kemasan = '$id_kemasan'");
    $get = mysql_fetch_object($query);
    
    die(json_encode($get));
}

if ($method === 'get_detail_harga_barang_resep') { // ambil data barang resep
    $id = $_GET['id']; // id barang
    $jml= $_GET['jumlah'];
    $query = mysql_query("select b.*, k.id as id_packing from barang b
        join kemasan k on (b.id = k.id_barang)
        where b.id = '$id' and k.default_kemasan = '1'");
    $get = mysql_fetch_object($query);
    
    $qry= mysql_query("select is_harga_bertingkat from kemasan where id = '".$get->id_packing."'");
    $cek= mysql_fetch_object($qry);
    if ($cek->is_harga_bertingkat === '0') {
        $sql = mysql_query("select b.*, k.id as id_packing, k.isi_satuan, (b.hna+(b.hna*(b.margin_resep/100))) as harga_jual, (b.hna+(b.hna*(b.margin_non_resep/100))) as harga_jual_nr from kemasan k join barang b on (k.id_barang = b.id) where k.id = '".$get->id_packing."'");
        $rows= mysql_fetch_object($sql);
    } else {
        $sql= mysql_query("select d.*, d.hj_resep as harga_jual, k.isi_satuan, d.hj_non_resep as harga_jual_nr, k.id as id_packing, d.hj_resep as harga_jual_resep, (k.isi*k.isi_satuan) as isi_satuan
            from dinamic_harga_jual d
            join kemasan k on (d.id_kemasan = k.id)
            where d.id_kemasan = '".$get->id_packing."' and $jml between d.jual_min and d.jual_max");
        $rows= mysql_fetch_object($sql);
    }
    die(json_encode($rows));
}

if ($method === 'get_detail_harga_barang_penerimaan') { // ambil data barang
    $id_kemasan = $_GET['id_kemasan']; // kemasan
    $id_barang  = $_GET['id_barang']; // id barang
    $jml= $_GET['jumlah'];
    $query = mysql_query("select b.*, k.id as id_packing from barang b
        join kemasan k on (b.id = k.id_barang)
        where b.id = '$id_barang' and k.id_kemasan = '$id_kemasan'");
    $get = mysql_fetch_object($query);
    
    $qry= mysql_query("select is_harga_bertingkat from kemasan where id = '".$get->id_packing."'");
    $cek= mysql_fetch_object($qry);
    if ($cek->is_harga_bertingkat === '0') {
        $sql = mysql_query("select b.*, k.id as id_packing, k.isi, k.isi_satuan, k.isi_satuan as isi_sat, (b.hna+(b.hna*(b.margin_resep/100))) as harga_jual, (b.hna+(b.hna*(b.margin_non_resep/100))) as harga_jual_nr from kemasan k join barang b on (k.id_barang = b.id) where k.id = '".$get->id_packing."'");
        $rows= mysql_fetch_object($sql);
    } else {
        $sql= mysql_query("select d.*, d.hj_resep as harga_jual, k.isi, k.isi_satuan, k.isi_satuan as isi_sat, d.hj_non_resep as harga_jual_nr, k.id as id_packing, d.hj_resep as harga_jual_resep, (k.isi*k.isi_satuan) as isi_satuan
            from dinamic_harga_jual d
            join kemasan k on (d.id_kemasan = k.id)
            where d.id_kemasan = '".$get->id_packing."' and $jml between d.jual_min and d.jual_max");
        $rows= mysql_fetch_object($sql);
    }
    die(json_encode($rows));
}

if ($method === 'get_detail_harga_barang') {
    $id = $_GET['id']; // id packing
    $jml= $_GET['jumlah'];
    $qry= mysql_query("select is_harga_bertingkat from kemasan where id = '$id'");
    $cek= mysql_fetch_object($qry);
    if ($cek->is_harga_bertingkat === '0') {
        $sql = mysql_query("select b.*, (b.hna+(b.hna*(b.margin_non_resep/100))) as harga_jual, 
            (b.hna+(b.hna*(b.margin_resep/100))) as harga_jual_resep, k.isi, k.isi_satuan as isi_sat, (k.isi*k.isi_satuan) as isi_satuan
            from kemasan k 
            join barang b on (k.id_barang = b.id) 
            where k.id = '$id'");
        $rows= mysql_fetch_object($sql);
    } else {
        $sql= mysql_query("select d.*, d.hj_non_resep as harga_jual, d.hj_resep as harga_jual_resep, k.isi, k.isi_satuan as isi_sat,  IF( k.isi_satuan !=1, '1', '1' ) AS isi_satuan
            from dinamic_harga_jual d
            join kemasan k on (d.id_kemasan = k.id)
            where d.id_kemasan = '$id' and $jml between d.jual_min and d.jual_max");
        $rows= mysql_fetch_object($sql);
    }
    die(json_encode($rows));
}

if ($method === 'get_data_noresep') {
    $sql = "select r.*, IFNULL(pj.total,'0') as total_tagihan, IFNULL(sum(dp.bayar),'0') as terbayar, p.nama, p.id_asuransi, a.diskon as reimburse from resep r 
        join pelanggan p on (r.id_pasien = p.id)
        left join penjualan pj on (r.id = pj.id_resep)
        left join detail_bayar_penjualan dp on (pj.id = dp.id_penjualan)
        left join asuransi a on (p.id_asuransi = a.id)
        where r.id like ('%$q%') group by pj.id having terbayar = '0' or terbayar < total_tagihan";
    $result = mysql_query($sql);
    $rows = array();
    while ($data = mysql_fetch_object($result)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'get_no_resep') {
    $sql = mysql_query("select substr(id, 1, 3) as jumlah from resep where date(waktu) like '%".date("Y-m")."%' order by waktu desc limit 1");
    $row = mysql_fetch_object($sql);
    if (!isset($row->jumlah)) {
        $str = "001-".date("m")."/".date("Y");
    } else {
        $str = str_pad((string)($row->jumlah+1), 3, "0", STR_PAD_LEFT)."-".date("m")."/".date("Y");
    }
    die(json_encode($str));
}

if ($method === 'get_no_pemeriksaan') {
    $sql = mysql_query("select count(*) as jumlah from pemeriksaan where tanggal like '%".date("Y-m")."%'");
    $row = mysql_fetch_object($sql);
    if (!isset($row->jumlah)) {
        $str = "PR.001-".date("m")."/".date("Y");
    } else {
        $str = "PR.".str_pad((string)($row->jumlah+1), 3, "0", STR_PAD_LEFT)."-".date("m")."/".date("Y");
    }
    die(json_encode($str));
}

if ($method === 'diagnosis') {
    $sql = "select * from penyakit where topik like ('%$q%') or sub_kode like ('%$q%') order by locate('$q', topik)";
    $result = mysql_query($sql);
    $rows = array();
    while ($data = mysql_fetch_object($result)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'tindakan') {
    $sql = "select * from tarif where nama like ('%$q%') order by locate('$q', nama)";
    $result = mysql_query($sql);
    $rows = array();
    while ($data = mysql_fetch_object($result)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'generate_new_sp') {
    $sql = mysql_query("select substr(id, 4,3) as id  from pemesanan order by tanggal desc limit 1");
    $row = mysql_fetch_object($sql);
    if (!isset($row->id)) {
        $result['sp'] = "SP.001/".date("m/Y");
    } else {
        $result['sp'] = "SP.".str_pad((string)($row->id+1), 3, "0", STR_PAD_LEFT)."/".date("m/Y");
    }
    die(json_encode($result));
}

if ($method === 'create_ref_inkaso') {
    $sql = mysql_query("select substr(no_ref, 4,3) as id  from inkaso order by id desc limit 1");
    $row = mysql_fetch_object($sql);
    if (!isset($row->id)) {
        $result['in'] = "IN.001-".date("m/Y");
    } else {
        $result['in'] = "IN.".str_pad((string)($row->id+1), 3, "0", STR_PAD_LEFT)."-".date("m/Y");
    }
    die(json_encode($result));
}

if ($method === 'get_sisa_hutang_supplier') {
    $id_supplier = $_GET['id_supplier'];
    $sql = mysql_query("select sum(total) as total_hutang FROM penerimaan where faktur = '$id_supplier'");
    $hutang = mysql_fetch_object($sql);
    
    $sql2= mysql_query("select sum(i.nominal) as terbayar from inkaso i join penerimaan p on (i.id_penerimaan = p.id) where p.faktur = '$id_supplier'");
    $terbyr = mysql_fetch_object($sql2);
    
    $sisa = $hutang->total_hutang - $terbyr->terbayar;
    die(json_encode(array('sisa' => $sisa)));
}

if ($method === 'spesialisasi') {
    $sql = mysql_query("select * from spesialisasi where nama like ('%$q%') order by locate('$q',nama)");
    $rows= array();
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'get_no_antri') {
    $now = date("Y-m-d");
    $id  = $_GET['id_spesialisasi'];
    $sql = mysql_query("select max(no_antri) as no from pendaftaran where date(waktu) = '$now' and id_spesialisasi = '$id'");
    $row = mysql_fetch_object($sql);
    if (isset($row->no)) {
        $no = $row->no+1;
    } else {
        $no = 1;
    }
    die(json_encode($no));
}

if ($method === 'karyawan') {
    $sql = mysql_query("select * from karyawan where nama like ('%$q%') order by locate ('$q',nama)");
    $rows = array();
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'nonota') {
    $sql = mysql_query("select p.*, pl.nama as pelanggan, plg.nama as pasien 
        from penjualan p 
        left join resep r on (p.id_resep = r.id)
        left join pelanggan pl on (p.id_pelanggan = pl.id)
        left join pelanggan plg on (r.id_pasien = plg.id)
        where p.id like ('%$q%') order by locate('$q',p.id)");
    $rows = array();
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'get_data_penjualan') {
    $id = $_GET['id'];
    $sql = mysql_query("select p.*, date(p.waktu) as tanggal, pl.nama as customer, pl.id as id_customer, a.nama as asuransi, d.nama as dokter, 
        (select sum(bayar) from detail_bayar_penjualan where id_penjualan = p.id) as terbayar, dp.id_kemasan, b.id as id_barang,
        concat_ws(' ',b.nama,b.kekuatan,s.nama) as nama_barang, st.nama as kemasan, dp.qty, dp.harga_jual, (dp.harga_jual*dp.qty) as subtotal,
        dp.expired
        from penjualan p
        join detail_penjualan dp on (p.id = dp.id_penjualan)
        join kemasan k on (k.id = dp.id_kemasan)
        join barang b on (k.id_barang = b.id)
        left join satuan s on (b.satuan_kekuatan = s.id)
        left join satuan st on (k.id_kemasan = st.id)
        left join pelanggan pl on (p.id_pelanggan = pl.id)
        left join asuransi a on (pl.id_asuransi = a.id) 
        left join resep r on (p.id_resep = r.id)
        left join dokter d on (r.id_dokter = d.id)
        where p.id = '$id' order by p.waktu desc");
    $rows = array();
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'get_nofaktur') {
    $sql = mysql_query("select p.*, s.nama as supplier, IFNULL(sum(i.nominal),'0') as terbayar from penerimaan p
        join supplier s on (p.id_supplier = s.id) 
        left join inkaso i on (i.id_penerimaan = p.id)
        where p.faktur like ('%$q%') group by p.id having p.total > terbayar ");
    $rows = array();
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'get_faktur') {
    $sql = mysql_query("select p.*, s.nama as supplier, sum(i.nominal) as terbayar from penerimaan p
        join supplier s on (p.id_supplier = s.id) 
        left join inkaso i on (i.id_penerimaan = p.id)
        where p.faktur like ('%$q%') group by p.id");
    $rows = array();
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'get_expiry_barang') {
    $id_barang = $_GET['id'];
    $sql = mysql_query("SELECT id_barang, ed, IFNULL((sum(masuk)-sum(keluar)),'0') as sisa 
        FROM `stok` WHERE id_barang = '$id_barang' and ed > '".date("Y-m-d")."' group by ed having sisa > 0 order by ed");
    $rows = array();
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'apoteker') {
    $sql = mysql_query("select * from karyawan where nama like ('%$q%') order by locate('$q',nama)");
    $rows = array();
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'bpom') {
    $sql = mysql_query("select * from pemusnahan where saksi_bpom like ('%$q%') group by saksi_bpom order by locate('$q',saksi_bpom) ");
    $rows = array();
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'get_detail_hpp') {
    $cek = mysql_fetch_object(mysql_query("select id, isi, isi_satuan from kemasan where id_barang = '$_GET[id]' and id_kemasan = '$_GET[id_kemasan]'"));
    $row = mysql_fetch_object(mysql_query("select * from detail_penerimaan where id_kemasan = '".$cek->id."' order by id desc limit 1"));
    $hpp = isset($row->hpp)?$row->hpp:'0';
    $total_hpp = $cek->isi_satuan*$hpp;
    die(json_encode(array('total_hpp' => $total_hpp)));
}

if ($method === 'get_alergi_data_obat') {
    $id_pasien = $_GET['id_pasien'];
    $sql = mysql_query("select a.*, CONCAT_WS(' ',b.nama,b.kekuatan,s.nama) as nama_barang
        from alergi_obat_pasien a
        join barang b on (a.id_barang = b.id)
        join pelanggan p on (a.id_pasien = p.id)
        join satuan s on (b.satuan_kekuatan = s.id)
        where p.id = '$id_pasien'
    ");
    $rows = array();
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}

if ($method === 'get_data_pemeriksaan') {
    $id_pemeriksaan = $_GET['id'];
    $sql = mysql_query("select p.*, pl.*, dp.*, pl.nama as pasien, p.id, pl.id as id_pasien, sp.jumlah, sp.keterangan, 
        CONCAT_WS(' ',b.nama, b.kekuatan, s.nama) as nama_barang, a.nama as asuransi, p.id as id_pemeriksaan from pemeriksaan p
        join pelanggan pl on (p.id_pasien = pl.id)
        left join asuransi a on (pl.id_asuransi = a.id)
        left join detail_pasien dp on (pl.id = dp.id_pasien)
        left join saran_pengobatan sp on (p.id = sp.id_pemeriksaan)
        left join barang b on (sp.id_barang = b.id)
        left join satuan s on (b.satuan_kekuatan = s.id)
        where p.id = '$id_pemeriksaan'");
    $data = mysql_fetch_object($sql);
    die(json_encode($data));
}

if ($method === 'get_saran_pengobatan') {
    $id = $_GET['id_pemeriksaan'];
    $sql = mysql_query("select sp.*, CONCAT_WS(' ',b.nama,b.kekuatan,s.nama) as nama_barang, k.id as id_packing 
        from saran_pengobatan sp
        join barang b on (sp.id_barang = b.id)
        join satuan s on (b.satuan_kekuatan = s.id)
        join kemasan k on (k.id_barang = b.id)
        where sp.id_pemeriksaan = '$id' and k.default_kemasan = '1'");
    $rows = array();
    while ($data = mysql_fetch_object($sql)) {
        $rows[] = $data;
    }
    die(json_encode($rows));
}
?>