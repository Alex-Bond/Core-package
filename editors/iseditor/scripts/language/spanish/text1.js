var sStyleWeight1;
var sStyleWeight2;
var sStyleWeight3;
var sStyleWeight4; 

function loadText()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Fuente";
    txtLang[1].innerHTML = "Estilo";
    txtLang[2].innerHTML = "Tama\u00F1o";
    txtLang[3].innerHTML = "Color Texto";
    txtLang[4].innerHTML = "Color Fondo";
    txtLang[5].innerHTML = "Efectos";
    
    txtLang[6].innerHTML = "Decoraci\u00F3n";
    txtLang[7].innerHTML = "May./Min.";
    txtLang[8].innerHTML = "Variante";
    txtLang[9].innerHTML = "Alineaci\u00F3n Vertical";

    txtLang[10].innerHTML = "No definido";
    txtLang[11].innerHTML = "Subrallado";
    txtLang[12].innerHTML = "Sobreimpresi\u00F3n";
    txtLang[13].innerHTML = "Tachar";
    txtLang[14].innerHTML = "Ninguno";

    txtLang[15].innerHTML = "No definido";
    txtLang[16].innerHTML = "1&ordf; may&uacute;sculas";
    txtLang[17].innerHTML = "may\u00FAsculas";
    txtLang[18].innerHTML = "Min\u00FAsculas";
    txtLang[19].innerHTML = "Ninguna";

    txtLang[20].innerHTML = "No definido";
    txtLang[21].innerHTML = "Versales";
    txtLang[22].innerHTML = "Normal";

    txtLang[23].innerHTML = "No definido";
    txtLang[24].innerHTML = "Super\u00EDndice";
    txtLang[25].innerHTML = "Sub\u00EDndice";
    txtLang[26].innerHTML = "Relativo";
    txtLang[27].innerHTML = "l\u00EDnea de Base ";
    
    txtLang[28].innerHTML = "Espacio caracteres";
    txtLang[29].innerHTML = "Vista Previa";
    txtLang[30].innerHTML = "Aplicar a";

    var optLang = document.getElementsByName("optLang");
    optLang[0].text = "Regular"
    optLang[1].text = "Italica"
    optLang[2].text = "Negrita"
    optLang[3].text = "Negrita Italica"
    
    optLang[0].value = "Regular"
    optLang[1].value = "Italica"
    optLang[2].value = "Negrita"
    optLang[3].value = "Negrita Italica"
    
    sStyleWeight1 = "Regular"
    sStyleWeight2 = "Italica"
    sStyleWeight3 = "Negrita"
    sStyleWeight4 = "Negrita Italica"
    
    optLang[4].text = "Arriba"
    optLang[5].text = "Medio"
    optLang[6].text = "Abajo"
    optLang[7].text = "Texto-superior"
    optLang[8].text = "Texto-inferior"
    optLang[9].text = "Texto seleccionado"
    optLang[10].text = "Etiqueta actual"
    
    document.getElementById("btnPick1").value = "Seleccionar";
    document.getElementById("btnPick2").value = "Seleccionar";

    document.getElementById("btnCancel").value = "Cancelar";
    document.getElementById("btnApply").value = "Aplicar";
    document.getElementById("btnOk").value = " Aplicar y salir ";
    }
function getText(s)
    {
    switch(s)
        {
        case "Custom Colors": return "Custom Colors";
        case "More Colors...": return "More Colors...";
        default: return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Formato Texto</title>")
    }