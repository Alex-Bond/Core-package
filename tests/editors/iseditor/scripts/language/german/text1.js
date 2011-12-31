var sStyleWeight1;
var sStyleWeight2;
var sStyleWeight3;
var sStyleWeight4; 

function loadText()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Schrift";
    txtLang[1].innerHTML = "Stil";
    txtLang[2].innerHTML = "Gr&ouml;&szlig;e";
    txtLang[3].innerHTML = "Schriftfarbe";
    txtLang[4].innerHTML = "Hintergrund";
    txtLang[5].innerHTML = "Effekte";
    
    txtLang[6].innerHTML = "Hervorhebung";
    txtLang[7].innerHTML = "Text Case";
    txtLang[8].innerHTML = "Kapit&auml;lchen";
    txtLang[9].innerHTML = "Versatz";

    txtLang[10].innerHTML = "k.A.";
    txtLang[11].innerHTML = "Unterstrichen";
    txtLang[12].innerHTML = "&Uuml;berstrichen";
    txtLang[13].innerHTML = "Durchgestrichen";
    txtLang[14].innerHTML = "keine";

    txtLang[15].innerHTML = "k.A.";
    txtLang[16].innerHTML = "Gross/Klein";
    txtLang[17].innerHTML = "GROSS";
    txtLang[18].innerHTML = "klein";
    txtLang[19].innerHTML = "keine";

    txtLang[20].innerHTML = "k.A.";
    txtLang[21].innerHTML = "Kapit&auml;lchen";
    txtLang[22].innerHTML = "Normal";

    txtLang[23].innerHTML = "k.A.";
    txtLang[24].innerHTML = "hochgestellt";
    txtLang[25].innerHTML = "tiefgestellt";
    txtLang[26].innerHTML = "Relativ";      //added by PAS
    txtLang[27].innerHTML = "Grundlinie";   //added by PAS
    
    txtLang[28].innerHTML = "Spationierung";
    txtLang[29].innerHTML = "Vorschau";
    txtLang[30].innerHTML = "anwenden auf";

    var optLang = document.getElementsByName("optLang");
    optLang[0].text = "Regular"
    optLang[1].text = "Kursiv"
    optLang[2].text = "Fett"
    optLang[3].text = "Fett Kursiv"
    
    optLang[0].value = "Regular"
    optLang[1].value = "Kursiv"
    optLang[2].value = "Fett"
    optLang[3].value = "Fett Kursiv"
    
    sStyleWeight1 = "Regular"
    sStyleWeight2 = "Kursiv"
    sStyleWeight3 = "Fett"
    sStyleWeight4 = "Fett Kursiv"
    
    optLang[4].text = "oben"
    optLang[5].text = "mitte"
    optLang[6].text = "unten"
    optLang[7].text = "Text-oben"
    optLang[8].text = "Text-unten"
    optLang[9].text = "markierter Text"
    optLang[10].text = "aktuelles Tag"
    
    document.getElementById("btnPick1").value = "w\u00E4hlen";//"Pick";
    document.getElementById("btnPick2").value = "w\u00E4hlen";//"Pick";

    document.getElementById("btnCancel").value = "Abbrechen";
    document.getElementById("btnApply").value = "\u00DCbernehmen"; //"apply";
    document.getElementById("btnOk").value = " OK ";
    }
function getText(s)
    {
    switch(s)
        {
        case "Custom Colors": return "Benutzerfarben";
        case "More Colors...": return "weitere Farben...";
        default: return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Textformatierung</title>")
    }