function loadText()
	{
    	document.getElementById("btnCancel").value = "Abbrechen";
    	document.getElementById("btnOk").value = " OK ";
	}
function getText(s)
	{
	switch(s)
		{
		case "Required":
			return "ieSpell (from www.iespell.com) wird ben\u00f6tigt.";
		default:return "";
		}
	}
function writeTitle()
	{
	document.write("<title>Rechtschreibpr\u00fcfung</title>")
	}