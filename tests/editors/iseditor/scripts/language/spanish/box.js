function loadText()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Color";
    txtLang[1].innerHTML = "Sombra";    
    
    txtLang[2].innerHTML = "Margin";
    txtLang[3].innerHTML = "Left";
    txtLang[4].innerHTML = "Right";
    txtLang[5].innerHTML = "Top";
    txtLang[6].innerHTML = "Bottom";
    
    txtLang[7].innerHTML = "Padding";
    txtLang[8].innerHTML = "Left";
    txtLang[9].innerHTML = "Right";
    txtLang[10].innerHTML = "Top";
    txtLang[11].innerHTML = "Bottom";

    txtLang[12].innerHTML = "Dimension";
    txtLang[13].innerHTML = "Width";
    txtLang[14].innerHTML = "Height";

    var optLang = document.getElementsByName("optLang");
    optLang[0].text = "pixels";
    optLang[1].text = "percent";
    optLang[2].text = "pixels";
    optLang[3].text = "percent";
    
    document.getElementById("btnCancel").value = "Cancelar";
    document.getElementById("btnApply").value = "Aplicar";
    document.getElementById("btnOk").value = " Aplicar y salir ";
    }
function getText(s)
    {
    switch(s)
        {
        case "No Border": return "Sin bordes";
        case "Outside Border": return "Borde alrededor";
        case "Left Border": return "Borde izquierda";
        case "Top Border": return "Border encima";
        case "Right Border": return "Borde derecha";
        case "Bottom Border": return "Borde abajo";
        case "Pick": return "Pick"; //"Seleccionar" => exceed the dialog dimension
        case "Custom Colors": return "Custom Colors";
        case "More Colors...": return "More Colors...";
        default: return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Box Formatting</title>")
    }