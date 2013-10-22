/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function datefmysql(tgl) {
        var tanggal=tgl;
        var elem = tanggal.split('-');
        var tahun = elem[0];
        var bulan = elem[1];
        var hari  = elem[2];
        return hari+'/'+bulan+'/'+tahun;
}
function Angka(obj) {
        a = obj.value;
        b = a.replace(/[^\d]/g,'');
        c = '';
        lengthchar = b.length;
        j = 0;
        for (i = lengthchar; i > 0; i--) {
                j = j + 1;
                if (((j % 3) == 1) && (j != 1)) {
                        c = b.substr(i-1,1) + '' + c;
                } else {
                        c = b.substr(i-1,1) + c;
                }
        }
        obj.value = c;
}

function FormNum(obj) {
        a = obj.value;
        b = a.replace(/[^\d]/g,'');
        c = '';
        lengthchar = b.length;
        j = 0;
        for (i = lengthchar; i > 0; i--) {
                j = j + 1;
                if (((j % 3) == 1) && (j != 1)) {
                        c = b.substr(i-1,1) + '.' + c;
                } else {
                        c = b.substr(i-1,1) + c;
                }
        }
        obj.value = c;
}

function numberToCurrency(a){
       a=a.toString();       
        var b = a.replace(/[^\d\,]/g,'');
		var dump = b.split(',');
        var c = '';
        var lengthchar = dump[0].length;
        var j = 0;
        for (var i = lengthchar; i > 0; i--) {
                j = j + 1;
                if (((j % 3) == 1) && (j != 1)) {
                        c = dump[0].substr(i-1,1) + '.' + c;
                } else {
                        c = dump[0].substr(i-1,1) + c;
                }
        }
		
		if(dump.length>1){
			if(dump[1].length>0){
				c += ','+dump[1];
			}else{
				c += ',';
			}
		}
    return c;
}

function currencyToNumber(a) {
    var b=a.toString();
    var c='';
    if(a!==''){
        c=b.replace(/\.+/g, '');
        return parseInt(c);
    } else {
        return '';
    }
}

function hitungUmur(tanggal){
        var elem = tanggal.split('-');
        var tahun = elem[0];
        var bulan = elem[1];
        var hari  = elem[2];
        if((tahun===0 && bulan===0 && tahun===0)|| tanggal===null || tanggal===''){
            $('#umur-tahun').attr('value',0);
            $('#umur-bulan').attr('value',0);
            $('#umur-hari').attr('value',0);
            return false;
        }
        var now=new Date();
        var day =now.getUTCDate();
        var month =now.getUTCMonth()+1;
        var year =now.getYear()+1900;
        
        tahun=year-tahun;
        bulan=month-bulan;
        hari=day-hari;
        
        var jumlahHari;
        var bulanTemp=(month===1)?12:month-1;
        if(bulanTemp===1 || bulanTemp===3 || bulanTemp===5 || bulanTemp===7 || bulanTemp===8 || bulanTemp===10 || bulanTemp===12){
            jumlahHari=31;
        }else if(bulanTemp===2){
            if(tahun % 4===0)
                jumlahHari=29;
            else
                jumlahHari=28;
        }else{
            jumlahHari=30;
        }

        if(hari<=0){
            hari+=jumlahHari;
            bulan--;
        }
        if(bulan<0 || (bulan===0 && tahun!==0)){
            bulan+=12;
            tahun--;
        }
        if (tanggal === '0000-00-00' || tanggal === null) {
            return '-';
        } else {
            return tahun+' Tahun ' +bulan+ ' Bulan ' +hari+ ' Hari';
        }
}