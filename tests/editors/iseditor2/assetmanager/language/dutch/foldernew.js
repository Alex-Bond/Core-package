function getText(s)
    {
    switch(s)
        {
		case "Folder already exists.": return "Map bestaat al.";
		case "Folder created.": return "Map aangemaakt.";
        case "Invalid input.":return "Ongeldige tekens.";
        }
    }
function loadText()
    {
    document.getElementById("txtLang").innerHTML = "Nieuwe Map";
    document.getElementById("btnCloseAndRefresh").value = "sluiten & vernieuwen";
    document.getElementById("btnCreate").value = "maken";
    }
function writeTitle()
    {
    document.write("<title>Nieuwe Map</title>")
    }
