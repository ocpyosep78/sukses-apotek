<?php
include_once '../config/database.php';
include_once '../inc/functions.php';
$method = $_GET['method'];
date_default_timezone_set("Asia/Jakarta");
if ($method === 'save_pemesanan') {
    session_start();
    $id             = $_POST['no_sp'];
    $tanggal        = date2mysql($_POST['tanggal'])." ".date("H:i:s");
    $tgl_datang     = date2mysql($_POST['tanggal_datang']);
    $id_supplier    = $_POST['id_supplier'];
    $id_barang      = $_POST['id_barang'];
    $id_kemasan     = $_POST['kemasan'];
    $jumlah         = $_POST['jumlah'];
    //$id_user        = 'NULL';
    $sql = "insert INTO pemesanan set
        id = '$id',
        tanggal = '$tanggal',
        tgl_datang = '$tgl_datang',
        id_supplier = '$id_supplier',
        id_users = '".$_SESSION['id_user']."'";
    //echo $sql;
    mysql_query($sql);
    $id_pemesanan = $id;
    
    foreach ($id_barang as $key => $data) {
        $id_packing = mysql_fetch_object(mysql_query("select id from kemasan where id_barang = '$data' and id_kemasan = '".$id_kemasan[$key]."'"));
        //echo "select id from kemasan where id_barang = '$data' and id_kemasan = '".$id_kemasan[$key]."'<br/>";
        $sql = "insert into detail_pemesanan set
            id_pemesanan = '$id_pemesanan',
            id_kemasan = '".$id_packing->id."',
            jumlah = '$jumlah[$key]'";
        //echo "select id from kemasan where id_barang = '$data' and id_kemasan = '".$id_kemasan[$key]."'<br/>";
        //echo $sql;
        mysql_query($sql);
    }
    
    $result['status'] = TRUE;
    $result['id_pemesanan'] = $id;
    $result['id'] = $id;
    die(json_encode($result));
}

if ($method === 'delete_pemesanan') {
    $id     = $_GET['id'];
    mysql_query("delete from pemesanan where id = '$id'");
}

if ($method === 'save_penerimaan') {
    session_start();
    $faktur         = $_POST['faktur'];
    $tanggal        = date2mysql($_POST['tanggal']);
    $no_sp          = ($_POST['no_sp'] !== '')?"'".$_POST['no_sp']."'":"NULL";
    $supplier       = $_POST['id_supplier'];
    $ppn            = $_POST['ppn'];
    $materai        = currencyToNumber($_POST['materai']);
    $tempo          = ($_POST['tempo'] !== '')?"'".date2mysql($_POST['tempo'])."'":"NULL";
    $status         = $_POST['status'];
    //$id_user        = ""; // unUsed
    $disc_pr        = $_POST['disc_pr'];
    $disc_rp        = currencyToNumber($_POST['disc_rp']);
    $total          = currencyToNumber($_POST['total']);
    $id_penerimaan  = $_POST['id_penerimaan'];
    $hna            = $_POST['hna'];
    
    if ($id_penerimaan === '') {
        $sql = "insert into penerimaan set
            faktur = '$faktur',
            tanggal = '$tanggal',
            id_supplier = '$supplier',
            id_pemesanan = $no_sp,
            ppn = '$ppn',
            materai = '$materai',
            jatuh_tempo = $tempo,
            id_users = '$_SESSION[id_user]',
            diskon_persen = '$disc_pr',
            diskon_rupiah = '$disc_rp',
            total = '$total',
            status = '$status'";
        mysql_query($sql);
        $id = mysql_insert_id();
        
        $id_barang  = $_POST['id_barang'];
        $id_kemasan = $_POST['satuan'];
        $jumlah     = $_POST['jumlah'];
        $no_batch   = $_POST['nobatch'];
        $ed         = $_POST['ed'];
        $harga      = $_POST['harga'];
        $diskon_pr  = $_POST['diskon_pr'];
        $diskon_rp  = $_POST['diskon_rp'];
        foreach ($id_barang as $key => $data) {
            $query  = mysql_query("select * from kemasan where id_barang = '$data' and id_kemasan = '$id_kemasan[$key]'");
            $rows   = mysql_fetch_object($query);
            
            $harga_a= currencyToNumber($harga[$key]);
            
            $base_hpp 	= ((currencyToNumber($harga[$key])*$jumlah[$key]) - ((currencyToNumber($harga[$key])*$jumlah[$key]) * ($diskon_pr[$key]/100))) / ($jumlah[$key]);
            $hpp_ppn	= ($ppn/100)*$base_hpp;
            $hpp 	= $base_hpp+$hpp_ppn;
            
            $sql = "insert into detail_penerimaan set
                id_penerimaan = '$id',
                id_kemasan = '".$rows->id."',
                nobatch = '$no_batch[$key]',
                expired = '".date2mysql($ed[$key])."',
                harga = '$harga_a',
                jumlah = '$jumlah[$key]',
                disc_pr = '$diskon_pr[$key]',
                disc_rp = '".currencyToNumber($diskon_rp[$key])."',
                hpp = '$hpp'
                ";
            mysql_query($sql);
            
            mysql_query("update barang set hna = '".$hna[$key]."' where id = '$data'");
            
            $sqk = mysql_query("select dhj.id, b.nama, b.hna, dhj.margin_non_resep, dhj.margin_resep, k.isi, k.isi_satuan,
                b.hna+(b.hna*(dhj.margin_non_resep/100)) as hja_nr, dhj.diskon_persen, dhj.diskon_rupiah,
                b.hna+(b.hna*(dhj.margin_resep/100)) as hja_r from barang b
                join kemasan k on (b.id = k.id_barang)
                join dinamic_harga_jual dhj on (k.id = dhj.id_kemasan)
                where b.id = '$data'");
            while ($rowk = mysql_fetch_object($sqk)) {
                $isi = $rowk->isi*$rowk->isi_satuan;
                if ($rowk->diskon_persen === '0') {
                    $terdiskon_nr = ($rowk->hja_nr*$isi)-$rowk->diskon_rupiah;  // hitung diskon rupiah
                    $terdiskon_r  = ($rowk->hja_r*$isi)-$rowk->diskon_rupiah;
                }
                else {
                    $terdiskon_nr = ($rowk->hja_nr*$isi)-(($rowk->hja_nr*$isi)*($rowk->diskon_persen/100));
                    $terdiskon_r  = ($rowk->hja_r*$isi)-(($rowk->hja_r*$isi)*($rowk->diskon_persen/100));
                }
                mysql_query("update dinamic_harga_jual set 
                    hj_non_resep = '$terdiskon_nr',
                    hj_resep = '$terdiskon_r'
                    where id = '".$rowk->id."'");
            }
            
            $stok= "insert into stok set
                waktu = '$tanggal ".date("H:i:s")."',
                id_transaksi = '$id',
                transaksi = 'Penerimaan',
                nobatch = '$no_batch[$key]',
                id_barang = '$data',
                ed = '".date2mysql($ed[$key])."',
                masuk = '".($jumlah[$key]*($rows->isi*$rows->isi_satuan))."'
            ";
            mysql_query($stok);
        }
        
        if ($status === 'Cash') {
            $sql = mysql_query("select substr(no_ref, 4,3) as id  from inkaso order by id desc limit 1");
            $row = mysql_fetch_object($sql);
            if (!isset($row->id)) {
                $res = "IN.001-".date("m/Y");
            } else {
                $res = "IN.".str_pad((string)($row->id+1), 3, "0", STR_PAD_LEFT)."-".date("m/Y");
            }
            $q_inkaso = "insert into inkaso set
                no_ref = '$res',
                tanggal = NOW(),
                id_penerimaan = '$id_penerimaan',
                cara_bayar = 'Uang',
                nominal = '$total'";
            mysql_query($q_inkaso);
        }
        
        $result['action'] = 'add';
    } else {
        $sql = "update penerimaan set
            faktur = '$faktur',
            tanggal = '$tanggal',
            id_supplier = '$supplier',
            id_pemesanan = '$no_sp',
            ppn = '$ppn',
            materai = '$materai',
            jatuh_tempo = '$tempo',
            id_users = '$_SESSION[id_user]',
            diskon_persen = '$disc_pr',
            diskon_rupiah = '$disc_rp',
            total = '$total',
            status = '$status'
            where id = '$id_penerimaan'";
        mysql_query($sql);
        $id = $id_penerimaan;
        mysql_query("delete from detail_penerimaan where id_penerimaan = '$id_penerimaan'");
        $id_barang  = $_POST['id_barang'];
        $id_kemasan = $_POST['satuan'];
        $jumlah     = $_POST['jumlah'];
        $no_batch   = $_POST['nobatch'];
        $ed         = $_POST['ed'];
        $harga      = $_POST['harga'];
        $diskon_pr  = $_POST['diskon_pr'];
        $diskon_rp  = $_POST['diskon_rp'];
        foreach ($id_barang as $key => $data) {
            $query = mysql_query("select * from kemasan where id_barang = '$data' and id_kemasan = '$id_kemasan[$key]'");
            $rows  = mysql_fetch_object($query);
            
            $base_hpp 	= ((currencyToNumber($harga[$key])*$jumlah[$key]) - ((currencyToNumber($harga[$key])*$jumlah[$key]) * ($diskon_pr[$key]/100))) / ($jumlah[$key]);
            $hpp_ppn	= ($ppn/100)*$base_hpp;
            $hpp 	= $base_hpp+$hpp_ppn;
            
            $sql = "insert into detail_penerimaan set
                id_penerimaan = '$id',
                id_kemasan = '".$rows->id."',
                nobatch = '$no_batch[$key]',
                expired = '".date2mysql($ed[$key])."',
                harga = '$harga_a',
                jumlah = '$jumlah[$key]',
                disc_pr = '$diskon_pr[$key]',
                disc_rp = '".currencyToNumber($diskon_rp[$key])."',
                hpp = '$hpp'
                ";
            mysql_query($sql);
        }
        $result['action'] = 'edit';
    }
    $result['status'] = TRUE;
    $result['id_penerimaan'] = $id;
    
    die(json_encode($result));
}

if ($method === 'delete_penerimaan') {
    $id     = $_GET['id'];
    mysql_query("delete from penerimaan where id = '$id'");
    mysql_query("delete from stok where id_transaksi = '$id' and transaksi = 'Penerimaan'");
}

if ($method === 'save_stokopname') {
    $tanggal    = date2mysql($_POST['tanggal']).' '.date("H:i:s");
    $id_barang  = $_POST['id_barang'];
    $nobatch    = $_POST['nobatch'];
    $ed         = $_POST['ed'];
    $masuk      = $_POST['masuk'];
    $keluar     = $_POST['keluar'];
    
    foreach ($id_barang as $key => $data) {
        $sql = "insert into stok set
            waktu = '$tanggal',
            transaksi = 'Stok Opname',
            nobatch = '$nobatch[$key]',
            id_barang = '$data',
            ed = '".date2mysql($ed[$key])."',
            masuk = '$masuk[$key]',
            keluar = '$keluar[$key]'
        ";
        mysql_query($sql);
    }
    die(json_encode(array('status' => TRUE)));
}

if ($method === 'delete_stokopname') {
    $id = $_GET['id'];
    mysql_query("delete from stok where id = '$id'");
}

if ($method === 'save_penjualannr') {
    session_start();
    $tanggal    = date2mysql($_POST['tanggal']).' '.date("H:i:s");
    $customer   = ($_POST['id_customer'] !== '')?$_POST['id_customer']:"NULL";
    $diskon_pr  = $_POST['diskon_pr'];
    $diskon_rp  = currencyToNumber($_POST['diskon_pr']);
    $ppn        = $_POST['ppn'];
    $total      = currencyToNumber($_POST['total_penjualan']);
    $tuslah     = currencyToNumber($_POST['tuslah']);
    $asuransi   = ($_POST['asuransi'] !== '')?$_POST['asuransi']:'NULL';
    $embalage   = currencyToNumber($_POST['embalage']);
    $reimburse  = isset($_POST['reimburse'])?$_POST['reimburse']:'0';
    $uangserah  = currencyToNumber($_POST['pembayaran']);
    $pembayaran = currencyToNumber($_POST['pembulatan']); // yang dientrikan pembulatan pembayarannya
    $sql = "insert into penjualan set
        waktu = '$tanggal',
        id_pelanggan = $customer,
        diskon_persen = '$diskon_pr',
        diskon_rupiah = '$diskon_rp',
        ppn = '$ppn',
        total = '$total',
        tuslah = '$tuslah',
        embalage = '$embalage',
        id_asuransi = $asuransi,
        reimburse = '$reimburse',
        bayar = '$uangserah'";
    
    mysql_query($sql);
    $id_penjualan = mysql_insert_id();
    
    $query = "insert into detail_bayar_penjualan set
        waktu = '$tanggal',
        id_penjualan = '$id_penjualan',
        bayar = '$pembayaran'";
    mysql_query($query); // insert ke tabel detail pembayaran
    
    $query2= "insert into arus_kas set
        id_transaksi = '$id_penjualan',
        transaksi = 'Penjualan Non Resep',
        id_users = '$_SESSION[id_user]',
        waktu = '$tanggal',
        masuk = '$pembayaran'";
    mysql_query($query2);
    
    $id_barang  = $_POST['id_barang'];
    $kemasan    = $_POST['kemasan'];
    $jumlah     = $_POST['jumlah'];
    $harga_jual = $_POST['harga_jual'];
    $ed         = $_POST['ed'];
        foreach ($id_barang as $key => $data) {
            $query = mysql_query("select k.*, b.hna from kemasan k join barang b on (k.id_barang = b.id) where k.id = '$kemasan[$key]'");
            $rows  = mysql_fetch_object($query);
            $isi   = $rows->isi*$rows->isi_satuan;
            $expired = ($ed[$key] !== '')?"'.$ed[$key].'":'NULL';
            $sql = "insert into detail_penjualan set
                id_penjualan = '$id_penjualan',
                id_kemasan = '$kemasan[$key]',
                expired = $expired,
                hna = '".$rows->hna."',
                qty = '".$jumlah[$key]."',
                harga_jual = '$harga_jual[$key]'
                ";
            
            mysql_query($sql);
            
            $last = mysql_fetch_object(mysql_query("select * from stok where id_barang = '$data' order by id desc limit 1"));
            
            //$fefo  = mysql_query("SELECT id_barang, ed, (sum(masuk)-sum(keluar)) as sisa FROM `stok` WHERE id_barang = '$data' and ed > '".date("Y-m-d")."' group by ed order by ed");
            //while ($val = mysql_fetch_object($fefo)) {
                $stok = "insert into stok set
                    waktu = '$tanggal',
                    id_transaksi = '$id_penjualan',
                    transaksi = 'Penjualan',
                    id_barang = '$data',
                    ed = $expired,
                    keluar = '".($jumlah[$key]*$isi)."'";
                //echo $stok;
                mysql_query($stok);
            //}
        }
    die(json_encode(array('status' => TRUE, 'id' => $id_penjualan)));
}

if ($method === 'delete_penjualannr') {
    $id     = $_GET['id'];
    mysql_query("delete from penjualan where id = '$id'");
    mysql_query("delete from stok where transaksi = 'Penjualan' and id_transaksi = '$id'");
}

if ($method === 'save_pemusnahan') {
    $tanggal    = date2mysql($_POST['tanggal']).' '.date("H:i:s");
    $apoteker   = $_POST['id_apoteker'];
    $bpom       = strtoupper($_POST['bpom']);
    
    $sql = "insert into pemusnahan set
        tanggal = '$tanggal',
        saksi_apotek = '$apoteker',
        saksi_bpom = '$bpom'";
    mysql_query($sql);
    $id_pemusnahan = mysql_insert_id();
    
    $id_barang  = $_POST['id_barang'];
    $kemasan    = $_POST['kemasan'];
    $jumlah     = $_POST['jumlah'];
    $ed         = $_POST['ed'];
    $hpp        = $_POST['hpp'];
    
    foreach ($id_barang as $key => $data) {
        $query = mysql_query("select * from kemasan where id_barang = '$data' and id_kemasan = '$kemasan[$key]'");
        $rows  = mysql_fetch_object($query);
        $isi   = $rows->isi*$rows->isi_satuan;
        $qwe   = "insert into detail_pemusnahan set 
            id_pemusnahan = '$id_pemusnahan',
            id_kemasan = '".$rows->id."',
            ed = '$ed[$key]',
            jumlah = '".$jumlah[$key]."',
            hpp = '".$hpp[$key]."'";
        mysql_query($qwe);
        
        $stok = "insert into stok set
            waktu = '$tanggal',
            id_transaksi = '$id_pemusnahan',
            transaksi = 'Pemusnahan',
            id_barang = '$data',
            ed = '$ed[$key]',
            keluar = '".($jumlah[$key]*$isi)."'";
        //echo $stok;
        mysql_query($stok);
    }
    die(json_encode(array('status' => TRUE, 'id' => $id_pemusnahan)));
}

if ($method === 'delete_pemusnahan') {
    $id     = $_GET['id'];
    mysql_query("delete from pemusnahan where id = '$id'");
    mysql_query("delete from stok where transaksi = 'Pemusnahan' and id_transaksi = '$id'");
}

if ($method === 'delete_penjualan') {
    $id     = $_GET['id'];
    mysql_query("delete from penjualan where id = '$id'");
    mysql_query("delete from stok where transaksi = 'Penjualan' and id_transaksi = '$id'");
}

if ($method === 'save_retur_penerimaan') {
    $tanggal        = date2mysql($_POST['tanggal']);
    $id_supplier    = $_POST['id_supplier'];
    $id_barang      = $_POST['id_barang'];
    $id_kemasan     = $_POST['id_kemasan'];
    $ed             = $_POST['ed'];
    $jumlah         = $_POST['jumlah'];
    $id_retur       = $_POST['id_retur_penerimaan'];
    
    if ($id_retur === '') {
        $sql = "insert into retur_penerimaan set
            tanggal = '$tanggal',
            id_supplier = '$id_supplier'";
        mysql_query($sql);
        $id         = mysql_insert_id();
        foreach ($id_barang as $key => $data) {
            $kemasan = mysql_fetch_object(mysql_query("select id from kemasan where id_barang = '$data' and id_kemasan = '$id_kemasan[$key]'"));
            $query = "insert into detail_retur_penerimaan set
                id_retur_penerimaan = '$id',
                id_kemasan = '".$kemasan->id."',
                expired = '".date2mysql($ed[$key])."',
                jumlah = '$jumlah[$key]'
                ";
            //echo $query;
            mysql_query($query);
            $query1 = mysql_query("select dp.* from kemasan k
                join barang b on (k.id_barang = b.id) 
                join detail_penerimaan dp on (k.id = dp.id_kemasan)
                where b.id = '$data' and k.id = '$id_kemasan[$key]' and dp.expired = '".date2mysql($ed[$key])."'");
            $row = mysql_fetch_object($query1);
           $query2 = "insert into stok set
                waktu = '$tanggal ".date("H:i:s")."',
                id_transaksi = '$id',
                transaksi = 'Retur Penerimaan',
                nobatch = '".(isset($row->nobatch)?$row->nobatch:'')."',
                id_barang = '$data',
                ed = '".date2mysql($ed[$key])."',
                keluar = '$jumlah[$key]'";
           //echo $query2;
           mysql_query($query2);
        }
    }
    $result['status'] = TRUE;
    $result['action'] = 'add';
    die(json_encode($result));
}

if ($method === 'save_retur_penjualan') {
    session_start();
    $tanggal        = date2mysql($_POST['tanggal']).' '.date("H:i:s");
    $id_barang      = $_POST['id_barang'];
    $id_kemasan     = $_POST['id_kemasan'];
    $ed             = $_POST['ed'];
    $jumlah         = $_POST['jumlah'];
    $id_retur       = $_POST['nonota'];
    $total_retur    = $_POST['total'];
    
    if ($id_retur !== '') {
        $sql = "insert into retur_penjualan set
            waktu = '$tanggal',
            id_penjualan = '$id_retur',
            total = '$total_retur'";
        mysql_query($sql);
        $id         = mysql_insert_id();
        foreach ($id_barang as $key => $data) {
            //$kemasan = mysql_fetch_object(mysql_query("select id from kemasan where id_barang = '$data' and id_kemasan = ''"));
            $query = "insert into detail_retur_penjualan set
                id_retur_penjualan = '$id',
                id_kemasan = '$id_kemasan[$key]',
                expired = '".date2mysql($ed[$key])."',
                qty = '$jumlah[$key]'
                ";
            //echo $query;
            mysql_query($query);
            
           $query2 = "insert into stok set
                waktu = '$tanggal ".date("H:i:s")."',
                id_transaksi = '$id',
                transaksi = 'Retur Penjualan',
                id_barang = '$data',
                ed = '".date2mysql($ed[$key])."',
                masuk = '$jumlah[$key]'";
           //echo $query2;
           mysql_query($query2);
        }
    }
    $query2= "insert into arus_kas set
        id_transaksi = '$id_retur',
        transaksi = 'Retur Penjualan',
        id_users = '$_SESSION[id_user]',
        waktu = NOW(),
        keluar = '$total_retur'";
    mysql_query($query2);
    
    $result['status'] = TRUE;
    $result['action'] = 'add';
    die(json_encode($result));
}

if ($method === 'save_resep') {
    $noresep    = $_POST['noresep'];
    $waktu      = date2mysql($_POST['waktu']).' '.date("H:i:s");
    $dokter     = $_POST['id_dokter'];
    $pasien     = $_POST['id_pasien'];
    $keterangan = $_POST['keterangan'];
    $id_resep   = $_POST['id_resep'];
    
    //$id_user    = 'NULL';
    if ($id_resep === '') {
        $sql = "insert into resep set
            id = '$noresep',
            waktu = '$waktu',
            id_dokter = '$dokter',
            id_pasien = '$pasien',
            keterangan = '$keterangan'";
        mysql_query($sql);
        $id = $noresep;
        $result['action'] = 'add';
    } else {
        $sql = "update resep set 
            waktu = '$waktu',
            id_dokter = '$dokter',
            id_pasien = '$pasien',
            keterangan = '$keterangan'
            where id = '$id_resep'";
        mysql_query($sql);
        $id = $id_resep;
        mysql_query("delete from resep_r where id_resep = '$id'");
        $result['action'] = 'edit';
    }
    
    $no_r       = $_POST['no_r'];
    $id_barang  = $_POST['id_barang'];
    $jml_minta  = $_POST['jp'];
    $jml_tebus  = $_POST['jt'];
    $aturan     = $_POST['a'];
    $pakai      = $_POST['p'];
    $iterasi    = $_POST['it'];
    //$kekuatan   = $_POST['kekuatan'];
    $dosis_racik= $_POST['dr'];
    $jml_pakai  = $_POST['jpi'];
    $id_tarif   = $_POST['id_tarif'];
    $jasa_apt   = $_POST['jasa'];
    $harga_brg  = $_POST['hrg_barang'];
    
    foreach ($no_r as $arr => $data) {
        $query = "insert into resep_r set
            id_resep = '$id',
            r_no = '$data',
            resep_r_jumlah = '$jml_minta[$arr]',
            tebus_r_jumlah = '$jml_tebus[$arr]',
            aturan = '$aturan[$arr]',
            pakai = '$pakai[$arr]',
            iter = '$iterasi[$arr]',
            id_tarif = ".(($id_tarif[$arr] !== '0')?$id_tarif[$arr]:'NULL').",
            nominal = '".  currencyToNumber($jasa_apt[$arr])."',
            id_barang = '$id_barang[$arr]',
            jual_harga = '".  currencyToNumber($harga_brg[$arr])."',
            dosis_racik = '$dosis_racik[$arr]',
            jumlah_pakai = '$jml_pakai[$arr]'
            ";
        //echo $query."<br/>";
        mysql_query($query);
        //$id_resep_r = mysql_insert_id();
        
    }
    $result['status'] = TRUE;
    $result['id'] = $id;
    die(json_encode($result));
}

if ($method === 'delete_resep') {
    $id = $_GET['id'];
    mysql_query("delete from resep where id = '$id'");
}

if ($method === 'save_penjualan') {
    session_start();
    $tanggal    = date2mysql($_POST['tanggal']).' '.date("H:i:s");
    $customer   = ($_POST['id_customer'] !== '')?$_POST['id_customer']:"NULL";
    $diskon_pr  = $_POST['diskon_pr'];
    $diskon_rp  = currencyToNumber($_POST['diskon_rp']);
    $ppn        = $_POST['ppn'];
    $total      = $_POST['total_penjualan'];
    $tuslah     = currencyToNumber($_POST['tuslah']);
    $asuransi   = ($_POST['asuransi'] !== '')?$_POST['asuransi']:'NULL';
    $embalage   = currencyToNumber($_POST['embalage']);
    $reimburse  = isset($_POST['reimburse'])?$_POST['reimburse']:'0';
    $uangserah  = currencyToNumber($_POST['pembayaran']);
    $pembayaran = currencyToNumber($_POST['pembulatan']); // yang dientrikan pembulatan pembayarannya
    $id_resep   = $_POST['id_resep'];
    $expired    = $_POST['ed'];
    // cek apakah nomor resep pernah ditransaksikan
    $cek = mysql_query("select count(*) as jumlah, id from penjualan where id_resep = '$id_resep'");
    $row = mysql_fetch_object($cek);
    if ($row->jumlah === '0') {
        $sql = "insert into penjualan set
            waktu = '$tanggal',
            id_resep = '$id_resep',
            id_pelanggan = $customer,
            diskon_persen = '$diskon_pr',
            diskon_rupiah = '$diskon_rp',
            ppn = '$ppn',
            total = '$total',
            tuslah = '$tuslah',
            embalage = '$embalage',
            id_asuransi = $asuransi,
            reimburse = '$reimburse',
            bayar = '$uangserah'";
        mysql_query($sql);
        $id_penjualan = mysql_insert_id();
    
        $query = "insert into detail_bayar_penjualan set
            waktu = '$tanggal',
            id_penjualan = '$id_penjualan',
            bayar = '$pembayaran'";
        mysql_query($query);
        
        $query2= "insert into arus_kas set
            id_transaksi = '$id_penjualan',
            transaksi = 'Penjualan Resep',
            id_users = '$_SESSION[id_user]',
            waktu = '$tanggal',
            masuk = '$pembayaran'";
        mysql_query($query2);
        
    $id_barang  = $_POST['id_barang'];
    $kemasan    = $_POST['kemasan'];
    $jumlah     = $_POST['jumlah'];
    $harga_jual = $_POST['harga_jual'];
        foreach ($id_barang as $key => $data) {
            $query = mysql_query("select k.*, b.hna from kemasan k join barang b on (k.id_barang = b.id) where k.id = '$kemasan[$key]'");
            $rows  = mysql_fetch_object($query);
            $isi   = $rows->isi*$rows->isi_satuan;
            
            $exp   = ($expired[$key] !== '')?"'.$expired[$key].'":'NULL';
            $sql = "insert into detail_penjualan set
                id_penjualan = '$id_penjualan',
                id_kemasan = '$kemasan[$key]',
                expired = $exp,
                hna = '".$rows->hna."',
                qty = '".$jumlah[$key]."',
                harga_jual = '$harga_jual[$key]'
                ";
            mysql_query($sql);
            
            $last = mysql_fetch_object(mysql_query("select * from stok where id_barang = '$data' order by id desc limit 1"));
            
            $fefo  = mysql_query("SELECT id_barang, ed, IFNULL((sum(masuk)-sum(keluar)),'0') as sisa FROM `stok` WHERE id_barang = '$data' and ed > '".date("Y-m-d")."' group by ed HAVING sisa > 0 order by ed limit 1");
            $ed    = mysql_fetch_object($fefo);
            $stok = "insert into stok set
                waktu = '$tanggal',
                id_transaksi = '$id_penjualan',
                transaksi = 'Penjualan',
                id_barang = '$data',
                ed = $exp,
                keluar = '".($jumlah[$key]*$isi)."'";
            //echo $stok;
            mysql_query($stok);
        }
    } else {
        $id_penjualan = $row->id;
        $sql = "insert into detail_bayar_penjualan set
            waktu = '$tanggal',
            id_penjualan = '$id_penjualan',
            bayar = '$pembayaran'";
        mysql_query($sql);
    }
    die(json_encode(array('status' => TRUE, 'id' => $id_penjualan)));
}

if ($method === 'save_pemeriksaan') {
    $id_periksa     = $_POST['id_pemeriksaan'];
    
    $id_pasien      = $_POST['norm'];
    $id_obat        = $_POST['id_obat']; // array
    $id_penyakit    = $_POST['id_penyakit']; // array
    if (!empty($id_obat)) {
        mysql_query("delete from alergi_obat_pasien where id_pasien = '$id_pasien'");
        foreach ($id_obat as $obat) {
            $sql1 = "insert into alergi_obat_pasien set 
                id_pasien = '$id_pasien',
                id_barang = '$obat'";
            mysql_query($sql1);
        }
    }
    if (!empty($id_penyakit)) {
        mysql_query("delete from penyakit_pasien where id_pasien = '$id_pasien'");
        foreach ($id_penyakit as $pyk) {
            $sql2 = "insert into penyakit_pasien set
                id_pasien = '$id_pasien',
                id_penyakit = '$pyk'";
            mysql_query($sql2);
        }
    }
    $tanggal        = date2mysql($_POST['tanggal']);
    $rpd            = $_POST['rpd'];
    $rpk            = $_POST['rpk'];
    $ps             = $_POST['ps'];
    $oh             = $_POST['oh'];
    $al             = $_POST['al'];
    $dl             = $_POST['dl'];
    $merokok        = $_POST['merokok'];
    $ka             = $_POST['ka'];
    $cek = mysql_num_rows(mysql_query("select * from detail_pasien where id_pasien = '$id_pasien'"));
    if ($cek === 0) {
        $sql3 = "insert into detail_pasien set
            id_pasien = '$id_pasien',
            tanggal = '$tanggal',
            rpd = '$rpd',
            rpk = '$rpk',
            ps = '$ps',
            oh = '$oh',
            al = '$al',
            dl = '$dl',
            mk = '$merokok',
            ka = '$ka'";
        mysql_query($sql3);
    } else {
        $sql3 = "update detail_pasien set
            tanggal = '$tanggal',
            rpd = '$rpd',
            rpk = '$rpk',
            ps = '$ps',
            oh = '$oh',
            al = '$al',
            dl = '$dl',
            mk = '$merokok',
            ka = '$ka'
            where id_pasien = '$id_pasien'";
        mysql_query($sql3);
    }
    $subjectif      = $_POST['subjektif'];
    $suhubadan      = $_POST['suhubadan'];
    $tekanandarah   = $_POST['tekanandarah'];
    $respiration    = $_POST['respirationrate'];
    $nadi           = $_POST['nadi'];
    $gdsewaktu      = $_POST['gdsewaktu'];
    $angkakoltotal  = $_POST['angkakoltotal'];
    $kadarasamurat  = $_POST['kadarasamurat'];
    $assesment      = $_POST['assesment'];
    $goalterapi     = $_POST['goalterapi'];
    $sarannonfarm   = $_POST['sarannonfarm'];
    
    if ($id_periksa === '') {
        $sql4 = "insert into pemeriksaan set
            id_pasien = '$id_pasien',
            tanggal = '$tanggal',
            subjektif = '$subjectif',
            suhu_badan = '$suhubadan',
            tek_darah = '$tekanandarah',
            res_rate = '$respiration',
            nadi = '$nadi',
            gds = '$gdsewaktu',
            angka_kolesterol = '$angkakoltotal',
            asam_urat = '$kadarasamurat',
            assesment = '$assesment',
            goal = '$goalterapi',
            saran_non_farmakoterapi = '$sarannonfarm'";
        mysql_query($sql4);
        $id_pemeriksaan = mysql_insert_id();
    } else {
        $sql4 = "update pemeriksaan set
            id_pasien = '$id_pasien',
            tanggal = '$tanggal',
            subjektif = '$subjectif',
            suhu_badan = '$suhubadan',
            tek_darah = '$tekanandarah',
            res_rate = '$respiration',
            nadi = '$nadi',
            gds = '$gdsewaktu',
            angka_kolesterol = '$angkakoltotal',
            asam_urat = '$kadarasamurat',
            assesment = '$assesment',
            goal = '$goalterapi',
            saran_non_farmakoterapi = '$sarannonfarm'
            where id = '$id_periksa'";
        mysql_query($sql4);
        $id_pemeriksaan = $id_periksa;
    }
    
    
    $id_obat_saran  = $_POST['id_obat_saran'];
    $jumlah         = $_POST['jumlah'];
    $keterangan     = $_POST['keterangan'];
    if (!empty($id_obat_saran)) {
        mysql_query("delete from saran_pengobatan where id_pemeriksaan = '$id_pemeriksaan'");
        foreach ($id_obat_saran as $key => $data) {
            $sql5 = "insert into saran_pengobatan set
                id_pemeriksaan = '$id_pemeriksaan',
                id_barang = '$data',
                jumlah = '$jumlah[$key]',
                keterangan = '$keterangan[$key]'";
            mysql_query($sql5);
        }
    }
    die(json_encode(array('status' => TRUE, 'id' => $id_pemeriksaan)));
}

if ($method === 'delete_pemeriksaan') {
    mysql_query("delete from pemeriksaan where id = '$_GET[id]'");
}

if ($method === 'save_inkaso') {
    session_start();
    $noref      = $_POST['noref'];
    $tanggal    = date2mysql($_POST['tanggal']);
    $id_penerimaan= $_POST['id_penerimaan'];
    $cara_bayar = $_POST['cara_bayar'];
    $id_bank    = ($_POST['bank'] !== '')?$_POST['bank']:'NULL';
    $no_trans   = $_POST['notransaksi'];
    $keterangan = $_POST['keterangan'];
    $nominal    = currencyToNumber($_POST['nominal']);
    
    $sql = "insert into inkaso set
        no_ref = '$noref',
        tanggal = '$tanggal',
        id_penerimaan = '$id_penerimaan',
        cara_bayar = '$cara_bayar',
        id_bank = $id_bank,
        no_transaksi = '$no_trans',
        keterangan = '$keterangan',
        nominal = '$nominal'";
    mysql_query($sql);
    $id = mysql_insert_id();
    
    $query2= "insert into arus_kas set
        id_transaksi = '$id',
        transaksi = 'Inkaso',
        id_users = '$_SESSION[id_user]',
        waktu = '$tanggal ".date("H:i:s")."',
        keluar = '$nominal'";
    mysql_query($query2);
    
    die(json_encode(array('status' => TRUE, 'id' => $id)));
}

if ($method === 'delete_inkaso') {
    $id = $_GET['id'];
    mysql_query("delete from inkaso where id = '$id'");
}

if ($method === 'add_rencana_pemesanan') {
    $id = $_GET['id'];
    mysql_query("insert INTO defecta set
        id_barang = '$id',
        jumlah = '1'");
}

if ($method === 'delete_pemesanan_plant') {
    $id = $_GET['id'];
    mysql_query("delete from defecta where id = '$id'");
}

if ($method === 'save_rencana_pemesanan') {
    session_start();
    $id             = $_POST['no_sp'];
    $tanggal        = date2mysql($_POST['tanggal'])." ".date("H:i:s");
    $tgl_datang     = date2mysql($_POST['tanggal_datang']);
    $id_supplier    = $_POST['id_supplier'];
    $id_barang      = $_POST['id_barang'];
    $id_kemasan     = $_POST['kemasan'];
    $jumlah         = $_POST['jumlah'];
    //$id_user        = 'NULL';
    $sql = "insert INTO pemesanan set
        id = '$id',
        tanggal = '$tanggal',
        tgl_datang = '$tgl_datang',
        id_supplier = '$id_supplier',
        id_users = '".$_SESSION['id_user']."'";
    mysql_query($sql);
    $id_pemesanan = $id;
    
    foreach ($id_barang as $key => $data) {
        $sql = "insert into detail_pemesanan set
            id_pemesanan = '$id_pemesanan',
            id_kemasan = '$id_kemasan[$key]',
            jumlah = '$jumlah[$key]'";
        //echo "select id from kemasan where id_barang = '$data' and id_kemasan = '".$id_kemasan[$key]."'<br/>";
        //echo $sql;
        mysql_query($sql);
        mysql_query("update defecta set status = '1' where id_barang = '".$data."'");
    }
    
    $result['status'] = TRUE;
    $result['id_pemesanan'] = get_last_pemesanan();
    $result['id'] = $id_pemesanan;
    die(json_encode($result));
}

if ($method === 'save_pendaftaran') {
    $waktu      = date("Y-m-d H:i:s");
    $pasien     = $_GET['pasien'];
    $spesialis  = $_GET['spesialis'];
    $noantri    = $_GET['noantri'];
    $sql = "insert into pendaftaran set
        waktu = '$waktu',
        id_pelanggan = '$pasien',
        no_antri = '$noantri',
        id_spesialisasi = '$spesialis'";
    mysql_query($sql);
    $result['id'] = mysql_insert_id();
    $result['status'] = TRUE;
    die(json_encode($result));
}

if ($method === 'save_in_out_uang') {
    session_start();
    $tanggal = date2mysql($_POST['waktu']).' '.date("H:i:s");
    $jenis   = $_POST['jenis'];
    $nominal = currencyToNumber($_POST['nominal']);
    $keterangan = $_POST['keterangan'];
    
    if ($jenis === 'masuk') {
        $sql = "insert into arus_kas set
        transaksi = 'Lain-lain',
        id_users = '$_SESSION[id_user]',
        waktu = '$tanggal',
        masuk = '$nominal',
        keterangan = '$keterangan'
        ";
    } else {
        $sql = "insert into arus_kas set
        transaksi = 'Lain-lain',
        id_users = '$_SESSION[id_user]',
        waktu = '$tanggal',
        keluar = '$nominal',
        keterangan = '$keterangan'
        ";
    }
    mysql_query($sql);
    $result['status'] = TRUE;
    die(json_encode($result));
}

if ($method === 'delete_in_out_uang') {
    $id = $_GET['id'];
    mysql_query("delete from arus_kas where id = '$id'");
}

if ($method === 'save_koreksi_stok') {
    session_start();
    $id_barang  = $_POST['id_barang'];
    $penyesuaian= $_POST['penyesuaian'];
    
    foreach ($id_barang as $key => $data) {
        $get    = mysql_fetch_object(mysql_query("select * from stok where id_barang = '$data' order by ed desc limit 1"));
        if ($penyesuaian[$key] !== '') {
            if ($penyesuaian[$key] < 0) {
                $sql= "insert into stok set
                    waktu = NOW(),
                    transaksi = 'Koreksi Stok',
                    id_barang = '$data',
                    ed = '".(isset($get->ed)?$get->ed:'NULL')."',
                    keluar = '".abs($penyesuaian[$key])."',
                    id_users = '".$_SESSION['id_user']."'";
                mysql_query($sql);
            }
            else if ($penyesuaian[$key] > 0) {
                $sql = "insert into stok set
                    waktu = NOW(),
                    transaksi = 'Koreksi Stok',
                    id_barang = '$data',
                    ed = '".(isset($get->ed)?$get->ed:'NULL')."',
                    masuk = '".abs($penyesuaian[$key])."',
                    id_users = '".$_SESSION['id_user']."'";
                //echo $sql;
                mysql_query($sql);
            }
        }
    }
    die(json_encode(array('status' => TRUE)));
}
?>