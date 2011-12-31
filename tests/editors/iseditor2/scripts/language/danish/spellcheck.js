function loadText()
	{
    document.getElementById("btnCancel").value = "Annuller";
    document.getElementById("btnOk").value = " Ok ";
	}
function getText(s)
	{
	switch(s)
		{
		case "Required":
			return "ieSpell (fra www.iespell.com) er kr\u00E6vet.";
		default:return "";
		}
	}
function writeTitle()
	{
	document.write("<title>Stavekontrol</title>")
	}