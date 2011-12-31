var sStyleWeight1;
var sStyleWeight2;
var sStyleWeight3;
var sStyleWeight4; 

function loadText()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Teckensnitt";
    txtLang[1].innerHTML = "Format";
    txtLang[2].innerHTML = "Storlek";
    txtLang[3].innerHTML = "F\u00F6rgrund";
    txtLang[4].innerHTML = "Bakgrund";
    txtLang[5].innerHTML = "Effekter";
    
    txtLang[6].innerHTML = "M\u00F6nster";
    txtLang[7].innerHTML = "Effekter";
    txtLang[8].innerHTML = "Liten bokstav";
    txtLang[9].innerHTML = "Vertikal";

    txtLang[10].innerHTML = "Ej valt";
    txtLang[11].innerHTML = "Understruken";
    txtLang[12].innerHTML = "\u00D6verstruken";
    txtLang[13].innerHTML = "Genomstruken";
    txtLang[14].innerHTML = "Ingen";

    txtLang[15].innerHTML = "Ej valt";
    txtLang[16].innerHTML = "Stor";
    txtLang[17].innerHTML = "Versaler";
    txtLang[18].innerHTML = "Gemener";
    txtLang[19].innerHTML = "Ingen";

    txtLang[20].innerHTML = "Ej valt";
    txtLang[21].innerHTML = "Kapit\u00E4ler";
    txtLang[22].innerHTML = "Normal";

    txtLang[23].innerHTML = "Ej valt";
    txtLang[24].innerHTML = "Upph\u00F6jd";
    txtLang[25].innerHTML = "Neds\u00E4nkt";
    txtLang[26].innerHTML = "Relativ";
    txtLang[27].innerHTML = "Baslinje";
    
    txtLang[28].innerHTML = "Teckenavst\u00E5nd";
    txtLang[29].innerHTML = "F\u00F6rhandsgranska";

    var optLang = document.getElementsByName("optLang");
    optLang[0].text = "Normal"
    optLang[1].text = "Kursiv"
    optLang[2].text = "Fet"
    optLang[3].text = "Fet Kursiv"

    optLang[0].value = "Normal"
    optLang[1].value = "Kursiv"
    optLang[2].value = "Fet"
    optLang[3].value = "Fet Kurisv"
    
    sStyleWeight1 = "Normal"
    sStyleWeight2 = "Kursiv"
    sStyleWeight3 = "Fet"
    sStyleWeight4 = "Fet Kursiv"
    
    optLang[4].text = "\u00D6verst"
    optLang[5].text = "Mitten"
    optLang[6].text = "Nederst"
    optLang[7].text = "Text-\u00F6verst"
    optLang[8].text = "Text-nederst"
    
    document.getElementById("btnPick1").value = "V\u00E4lj";
    document.getElementById("btnPick2").value = "V\u00E4lj";

    document.getElementById("btnCancel").value = "Avbryt";
    document.getElementById("btnOk").value = " OK ";
    }
function getText(s)
    {
    switch(s)
        {
        case "Custom Colors": return "Custom Colors";
        case "More Colors...": return "More Colors...";
        default: return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Textformatering</title>")
    }