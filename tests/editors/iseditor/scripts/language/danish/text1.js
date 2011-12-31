var sStyleWeight1;
var sStyleWeight2;
var sStyleWeight3;
var sStyleWeight4; 

function loadText()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Skrifttype";
    txtLang[1].innerHTML = "Typografi";
    txtLang[2].innerHTML = "St\u00F8rrelse";
    txtLang[3].innerHTML = "Forgrund";
    txtLang[4].innerHTML = "Baggrund";
    txtLang[5].innerHTML = "Effekter";
    
    txtLang[6].innerHTML = "Dekoration";
    txtLang[7].innerHTML = "Tekst";
    txtLang[8].innerHTML = "Minicaps";
    txtLang[9].innerHTML = "Vertikal";

    txtLang[10].innerHTML = "Ingen";
    txtLang[11].innerHTML = "Understreget";
    txtLang[12].innerHTML = "Overstreget";
    txtLang[13].innerHTML = "Gennemstreget";
    txtLang[14].innerHTML = "Normal";

    txtLang[15].innerHTML = "Ingen";
    txtLang[16].innerHTML = "Kapit\u00E6ler";
    txtLang[17].innerHTML = "St. bogstv.";
    txtLang[18].innerHTML = "Sm\u00E5 bogstv.";
    txtLang[19].innerHTML = "Normal";

    txtLang[20].innerHTML = "Ingen";
    txtLang[21].innerHTML = "Small-Caps";
    txtLang[22].innerHTML = "Normal";

    txtLang[23].innerHTML = "Ingen";
    txtLang[24].innerHTML = "H\u00E6vet skrift";
    txtLang[25].innerHTML = "S\u00E6nket skrift";
    txtLang[26].innerHTML = "Relativ";
    txtLang[27].innerHTML = "Grundlinie";//Baseline
    
    txtLang[28].innerHTML = "Tegnafstand";
    txtLang[29].innerHTML = "Eksempel";
    txtLang[30].innerHTML = "Overf\u00F8r til";

    var optLang = document.getElementsByName("optLang");
    optLang[0].text = "Almindlig"
    optLang[1].text = "Kursiv"
    optLang[2].text = "Fed"
    optLang[3].text = "Fed kursiv"
    
    optLang[0].value = "Almindelig"
    optLang[1].value = "Kursiv"
    optLang[2].value = "Fed"
    optLang[3].value = "Fed kursiv"
    
    sStyleWeight1 = "Almindelig"
    sStyleWeight2 = "Kursiv"
    sStyleWeight3 = "Fed"
    sStyleWeight4 = "Fed kursiv"
    
    optLang[4].text = "\u00D8verst"
    optLang[5].text = "Midt"
    optLang[6].text = "Nederst"
    optLang[7].text = "Tekst top"
    optLang[8].text = "Tekst bund"
    optLang[9].text = "Valgte tekst"
    optLang[10].text = "Aktuelle kodetag"
    
    document.getElementById("btnPick1").value = "V\u00E6lg"
    document.getElementById("btnPick2").value = "V\u00E6lg"

    document.getElementById("btnCancel").value = "Annuller";
    document.getElementById("btnApply").value = "Opdater";
    document.getElementById("btnOk").value = " Ok ";
    }
function getText(s)
    {
    switch(s)
        {
        case "Custom Colors": return "Egne farver";
        case "More Colors...": return "Flere farver...";
        default: return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Tekst formatering</title>")
    }