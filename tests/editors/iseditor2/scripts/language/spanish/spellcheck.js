function loadText()
	{
    	document.getElementById("btnCancel").value = "Cancelar";
    	document.getElementById("btnOk").value = " Aceptar ";
	}
function getText(s)
	{
	switch(s)
		{
		case "Required":
			return "ieSpell (desde www.iespell.com) es necessario.";
		default:return "";
		}
	}
function writeTitle()
	{
	document.write("<title>Corrector ortografico</title>")
	}