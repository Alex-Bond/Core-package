function loadText()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Texto CSS";
    txtLang[1].innerHTML = "Nombre clase";
    txtLang[2].innerHTML = "Vista Previa";
    txtLang[3].innerHTML = "Aplicar a";
    
    var optLang = document.getElementsByName("optLang");
    optLang[0].text = "Texto seleccionado"
    optLang[1].text = "Esta etiqueta"
    
    document.getElementById("btnCancel").value = "Cancelar";
    document.getElementById("btnApply").value = "Aplicar";
    document.getElementById("btnOk").value = " Aplicar y salir ";
    }
function getText(s)
    {
    switch(s)
        {
        case "You're selecting BODY element.":
            return "Seleccione un elemento del cuerpo.";
        case "Please select a text.":
            return "Por favor, seleccione un texto.";
        default:return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Crear CSS</title>")
    }