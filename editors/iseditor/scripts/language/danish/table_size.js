function loadText()
    {
    var txtLang =  document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Inds\u00E6t r\u00E6kke";
    txtLang[1].innerHTML = "Inds\u00E6t kolonne";
    txtLang[2].innerHTML = "Flet r\u00E6kke";
    txtLang[3].innerHTML = "Flet kolonne";
    txtLang[4].innerHTML = "Slet r\u00E6kke";
    txtLang[5].innerHTML = "Slet kolonne";

	document.getElementById("btnInsRowAbove").title="Inds\u00E6t r\u00E6kke (Above)";
	document.getElementById("btnInsRowBelow").title="Inds\u00E6t r\u00E6kke (Below)";
	document.getElementById("btnInsColLeft").title="Inds\u00E6t kolonne (Left)";
	document.getElementById("btnInsColRight").title="Inds\u00E6t kolonne (Right)";
	document.getElementById("btnIncRowSpan").title="Increase Rowspan";
	document.getElementById("btnDecRowSpan").title="Decrease Rowspan";
	document.getElementById("btnIncColSpan").title="Increase Colspan";
	document.getElementById("btnDecColSpan").title="Decrease Colspan";
	document.getElementById("btnDelRow").title="Slet r\u00E6kke";
	document.getElementById("btnDelCol").title="Slet kolonne";

	document.getElementById("btnClose").value = " Luk ";
    }
function getText(s)
    {
    switch(s)
        {
        case "Cannot delete column.":
            return "Kolonnen kan ikke slettes. Kolonnen indeholder flettet celler fra andre kolonner, fjern venligst de flette celler f\u00F8rst.";
        case "Cannot delete row.":
            return "R\u00E6kken kan ikke slettes. R\u00E6kken indeholder flettede celler fra andre r\u00E6kker, fjern venligst dem f\u00F8rst.";
        default:return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Celler</title>")
    }