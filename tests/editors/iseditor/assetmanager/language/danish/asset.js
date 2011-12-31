function getText(s)
	{
    switch(s)
        {
        case "Cannot delete Asset Base Folder.":return "Man kan ikke slette rodmappen.";
        case "Delete this file ?":return "Vil du slette filen ?";
        case "Uploading...":return "Sender til server...";//or "Sender..."
        case "File already exists. Do you want to replace it?":return "File already exists. Do you want to replace it?";
        
		case "Files": return "Filer"
		case "del": return "Slet"
		case "Empty...": return "Tom..."
        }
    }
function loadText()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Opret&nbsp;mappe";
    txtLang[1].innerHTML = "Slet&nbsp;mappe";
    txtLang[2].innerHTML = "Send fil";
    
	var optLang = document.getElementsByName("optLang");
    optLang[0].text = "Alle filer";
    optLang[1].text = "Medier";
    optLang[2].text = "Billeder";
    optLang[3].text = "Flash";

    document.getElementById("btnOk").value = " ok ";
    document.getElementById("btnUpload").value = "Send";
    }
function writeTitle()
    {
    document.write("<title>Filmanager</title>")
    }
