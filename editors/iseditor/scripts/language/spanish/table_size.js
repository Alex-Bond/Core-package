function loadText()
    {
    var txtLang =  document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Insertar Fila";
    txtLang[1].innerHTML = "Insertar Columna";
    txtLang[2].innerHTML = "Unir Fila";
    txtLang[3].innerHTML = "Unir Columna";
    txtLang[4].innerHTML = "Borrar Fila";
    txtLang[5].innerHTML = "Borrar Columna";

	document.getElementById("btnInsRowAbove").title="Insertar Fila (Above)";
	document.getElementById("btnInsRowBelow").title="Insertar Fila (Below)";
	document.getElementById("btnInsColLeft").title="Insertar Columna (Left)";
	document.getElementById("btnInsColRight").title="Insertar Columna (Right)";
	document.getElementById("btnIncRowSpan").title="Increase Rowspan";
	document.getElementById("btnDecRowSpan").title="Decrease Rowspan";
	document.getElementById("btnIncColSpan").title="Increase Colspan";
	document.getElementById("btnDecColSpan").title="Decrease Colspan";
	document.getElementById("btnDelRow").title="Borrar Fila";
	document.getElementById("btnDelCol").title="Borrar Columna";
	document.getElementById("btnClose").value = " Cerrar ";
    }
function getText(s)
    {
    switch(s)
        {
        case "Cannot delete column.":
            return "No es posible borrar la columna. La columna contiene celdas unidas con otra columna. Por favor elimine primero la union.";
        case "Cannot delete row.":
            return "No es posible borrar la fila. La fila contiene celdas unidas con otra fila. Por favor elimine primero la union";
        default:return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Tama\u00F1o&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</title>")
    }