var Util = {
    dataAtual: function(){
        var data = new Date();
        // Guarda cada pedaço em uma variável
        var dia     = data.getDate();           // 1-31
        //var dia_sem = data.getDay();            // 0-6 (zero=domingo)
        var mes     = data.getMonth();          // 0-11 (zero=janeiro)
        //var ano2    = data.getYear();           // 2 dígitos
        var ano4    = data.getFullYear();       // 4 dígitos
        //var hora    = data.getHours();          // 0-23
        //var min     = data.getMinutes();        // 0-59
        //var seg     = data.getSeconds();        // 0-59
        //var mseg    = data.getMilliseconds();   // 0-999
        //var tz      = data.getTimezoneOffset(); // em minutos

        // Formata a data e a hora (note o mês + 1)
        if (dia >= 1 && dia <= 9 ) {
            dia = "0" + dia;
        }
        var str_data = dia + '/' + (mes+1) + '/' + ano4;
        //var str_hora = hora + ':' + min + ':' + seg;
        return str_data;
        // Mostra o resultado
        //alert('Hoje é ' + str_data + ' às ' + str_hora);
    },
    mascaraTelefone: function(v) {
        if ((v.length) > 16) {
            v = v.substring(0, (v.length - 1));
        }
        v = v.replace(/\D/g, ""); //Remove tudo o que não é dígito

        if ((v.length) <= 10) {
            v = v.replace(/^(\d\d)(\d)/g, "($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
            v = v.replace(/(\d{4})(\d)/, "$1-$2"); //Coloca hífen entre o quarto e o quinto dígitos
        } else {
            v = v.replace(/^(\d\d)(\d)/g, "($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
            v = v.replace(/(\d)(\d{2})/, "$1 $2"); //Coloca ponto referênte ao nono dígitos
            v = v.replace(/(\d{4})(\d)/, "$1-$2"); //Coloca hífen entre o quarto e o quinto dígitos
        }
        return v;
    },
    limparCaracteres: function(v) {

        v = v.replace(/\D/g, ""); //Remove tudo o que não é dígito
        return v;
    },
    mascaraValor: function(v) {
        v = v.replace(/\D/g, ""); //Remove tudo o que não é dígito
        v = v.replace(/(\d)(\d{8})$/, "$1.$2"); //coloca o ponto dos milhões
        v = v.replace(/(\d)(\d{5})$/, "$1.$2"); //coloca o ponto dos milhares
        v = v.replace(/(\d)(\d{2})$/, "$1,$2"); //coloca a virgula antes dos 2 últimos dígitos
        return v;
    },
    validarData: function(v) {
        var data = v;
        var dia = data.substr(0, 2);
        var barra1 = data.substr(2, 1);
        var mes = data.substr(3, 2);
        var barra2 = data.substr(5, 1);
        var ano = data.substr(6, 4);
        if (data.length != 10 || barra1 != "/" || barra2 != "/" || isNaN(dia) || isNaN(mes) || isNaN(ano) || dia > 31 || mes > 12) {
            return false;
        }
        if ((mes == 4 || mes == 6 || mes == 9 || mes == 11) && dia == 31) {
            return false;
        }
        if (mes == 2 && (dia > 29 || (dia == 29 && ano % 4 != 0))) {
            return false;
        }
        if (ano < 1900) {
            return false;
        }
        return true;
    },
    numeroParaMoeda: function(n, c, d, t) {
        c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    },
    compararExtensao: function(ext, conjunto) {
        for (var i = 0; i < conjunto.length; i++) {
            if (ext.toUpperCase() == conjunto[i]) {
                return true;
            }
        }
        return false;
    },
    mascaraData: function(v) {
        if ((v.length) == 10) {
            if (validaDat(v) == false) {
                return v;
            }
        }
        v = v.replace(/\D/g, "");                    //Remove tudo o que não é dígito
        v = v.replace(/(\d{2})(\d)/, "$1/$2");
        v = v.replace(/(\d{2})(\d)/, "$1/$2");

        v = v.replace(/(\d{2})(\d{2})$/, "$1$2");
        return v;
    }


};