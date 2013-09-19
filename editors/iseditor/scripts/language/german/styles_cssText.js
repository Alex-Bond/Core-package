function loadText()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "CSS Text";
    txtLang[1].innerHTML = "Class Name";
    txtLang[2].innerHTML = "Vorschau";
    txtLang[3].innerHTML = "anwenden auf";
    
    var optLang = document.getElementsByName("optLang");
    optLang[0].text = "markierter Text"
    optLang[1].text = "aktuelles Tag"
    
    document.getElementById("btnCancel").value = "Abbrechen";
    document.getElementById("btnApply").value = "\u00DCbernehmen"; //"apply";
    document.getElementById("btnOk").value = " OK ";
    }
function getText(s)
    {
    switch(s)
        {
        case "You're selecting BODY element.":
            return "Sie haben das BODY Element ausgew\u00E4hlt.";
        case "Please select a text.":
            return "Bitte w\u00E4hlen Sie einen Text aus.";
        default:return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Benutzer CSS</title>")
    }