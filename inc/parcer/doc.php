<?php
// Чтение текста из DOC 
// Версия 0.4 
// Автор: Алексей Рембиш a.k.a Ramon 
// E-mail: alex@rembish.ru 
// Copyright 2009 
// Чтобы работать с doc, мы дожны уметь работать с WCBFF не так ли? 
require_once "cfb.php";

// Класс для работы с Microsoft Word Document (в народе doc), расширяет 
// Windows Compound Binary File Format. Давайте попробуем найти текст и 
// здесь 
class doc extends cfb {

    // Функция parse расширяет родительскую функцию и на выходе получает 
    // текст из данного файла. Если что-то пошло не так - возвращает false 
    public function parse() {
        parent::parse();

        // Для чтения DOC'а нам нужны два потока - WordDocument и 0Table или 
        // 1Table в зависимости от ситуации. Для начала найдём первый - в нём 
        // (потоке) разбросаны кусочки текста, которые нам нужной поймать. 
        $wdStreamID = $this->getStreamIdByName("WordDocument");
        if ($wdStreamID === false) {
            return false;
        }

        // Поток нашли, читаем его в переменную 
        $wdStream = $this->getStreamById($wdStreamID);

        // Далее нам нужно получить кое-что из FIB - специальный блок под названием 
        // File Information Block в начале потока WordDocument. 
        $bytes = $this->getShort(0x000A, $wdStream);
        // Считываем какую именно таблицу нам нужно будет читать - первую или нулевую. 
        // Для этого прочитаем один маленький бит из заголовка по известному смещению. 
        $fWhichTblStm = ($bytes & 0x0200) == 0x0200;

        // Теперь нам нужно узнать позицию CLX в табличном потоке. Ну и размер этого самого 
        // CLX - пусть ему пусто будет. 
        $fcClx = $this->getLong(0x01A2, $wdStream);
        $lcbClx = $this->getLong(0x01A6, $wdStream);

        // Читаем несколько значений, чтобы отделить позиции от размерности в clx 
        $ccpText = $this->getLong(0x004C, $wdStream);
        $ccpFtn = $this->getLong(0x0050, $wdStream);
        $ccpHdd = $this->getLong(0x0054, $wdStream);
        $ccpMcr = $this->getLong(0x0058, $wdStream);
        $ccpAtn = $this->getLong(0x005C, $wdStream);
        $ccpEdn = $this->getLong(0x0060, $wdStream);
        $ccpTxbx = $this->getLong(0x0064, $wdStream);
        $ccpHdrTxbx = $this->getLong(0x0068, $wdStream);

        // С помощью вышенайденных значений, находим значение последнего CP - character position 
        $lastCP = $ccpFtn + $ccpHdd + $ccpMcr + $ccpAtn + $ccpEdn + $ccpTxbx + $ccpHdrTxbx;
        $lastCP += ($lastCP != 0) + $ccpText;

        // Находим в файле нужную нам табличку. 
        $tStreamID = $this->getStreamIdByName(intval($fWhichTblStm) . "Table");
        if ($tStreamID === false) {
            return false;
        }

        // È ñ÷èòûâàåì èç íå¸ ïîòîê â ïåðåìåííóþ 
        $tStream = $this->getStreamById($tStreamID);
        // Ïîòîì íàõîäèì â ïîòîêå CLX 
        $clx = substr($tStream, $fcClx, $lcbClx);

        // À òåïåðü íàì â CLX (complex, àãà) íóæíî íàéòè êóñîê ñî ñìåùåíèÿìè è ðàçìåðíîñòÿìè 
        // êóñî÷êîâ òåêñòà. 
        $lcbPieceTable = 0;
        $pieceTable = "";

        // Îòìå÷ó, ÷òî çäåñü âààààààààààùå æîïà. Â äîêóìåíòàöèè íà ñàéòå òîëêîì íå ñêàçàíî 
        // ñêîëüêî ãîíà ìîæåò áûòü äî pieceTable â ýòîì CLX, ïîýòîìó áóäåì èñõîäèòü èç òóïîãî 
        // ïåðåáîðà - èùåì âîçìîæíîå íà÷àëî pieceTable (îáÿçàòåëüíî íà÷èíàåòñÿ íà 0õ02), çàòåì 
        // ÷èòàåì ñëåäóþùèå 4 áàéòà - ðàçìåðíîñòü pieceTable. Åñëè ðàçìåðíîñòü ïî ôàêòó è 
        // ðàçìåðíîñòü, çàïèñàííàÿ ïî ñìåùåíèþ, òî áèíãî! ìû íàøëè íàøó pieceTable. Íåò? 
        // èùåì äàëüøå. 

        $from = 0;
        // Èùåì 0õ02 ñ òåêóùåãî ñìåùåíèÿ â CLX 
        while (($i = strpos($clx, chr(0x02), $from)) !== false) {
            // Íàõîäèì ðàçìåð pieceTable 
            $lcbPieceTable = $this->getLong($i + 1, $clx);
            // Íàõîäèì pieceTable 
            $pieceTable = substr($clx, $i + 5);

            // Åñëè ðàçìåð ôàêòè÷åñêèé îòëè÷àåòñÿ îò íóæíîãî, òî ýòî íå òî - 
            // åäåì äàëüøå. 
            if (strlen($pieceTable) != $lcbPieceTable) {
                $from = $i + 1;
                continue;
            }
            // Õîòÿ íåò - âðîäå íàøëè, break, òîâàðèùè! 
            break;
        }

        // Òåïåðü çàïîëíÿåì ìàññèâ character positions, ïîêà íå íàòêí¸ìñÿ 
        // íà ïîñëåäíèé CP. 
        $cp = array();
        $i = 0;
        while (($cp[] = $this->getLong($i, $pieceTable)) != $lastCP)
            $i += 4;
        // Îñòàòîê èä¸ò íà PCD (piece descriptors) 
        $pcd = str_split(substr($pieceTable, $i + 4), 8);

        $text = "";
        // Óðà! ìû ïîäîøëè ê ãëàâíîìó - ÷òåíèå òåêñòà èç ôàéëà. 
        // Èä¸ì ïî äåêñêðèïòîðàì êóñî÷êîâ 
        for ($i = 0; $i < count($pcd); $i++) {
            // Ïîëó÷àåì ñëîâî ñî ñìåùåíèåì è ôëàãîì êîìïðåññèè 
            $fcValue = $this->getLong(2, $pcd[$i]);
            // Ñìîòðèì - ÷òî ïåðåä íàìè òóïîé ANSI èëè Unicode 
            $isANSI = ($fcValue & 0x40000000) == 0x40000000;
            // Îñòàëüíîå áåç ìàêóøêè èä¸ò íà ñìåùåíèå 
            $fc = $fcValue & 0x3FFFFFFF;

            // Ïîëó÷àåì äëèíó êóñî÷êà òåêñòà 
            $lcb = $cp[$i + 1] - $cp[$i];
            // Åñëè ïåðåä íàìè Unicode, òî ìû äîëæíû ïðî÷èòàòü â äâà ðàçà áîëüøå ôàéëîâ 
            if (!$isANSI)
                $lcb *= 2;
            // Åñëè ANSI, òî íà÷àòü â äâà ðàçà ðàíüøå. 
            else
                $fc /= 2;

            // ×èòàåì êóñîê ñ ó÷¸òîì ñìåùåíèÿ è ðàçìåðà èç WordDocument-ïîòîêà 
            $part = substr($wdStream, $fc, $lcb);
            // Åñëè ïåðåä íàìè Unicode, òî ïðåîáðàçîâûâàåì åãî â íîðìàëüíîå ñîñòîÿíèå 
            if (!$isANSI)
                $part = $this->unicode_to_utf8($part);

            // Äîáàâëÿåì êóñî÷åê ê îáùåìó òåêñòó 
            $text .= $part;
        }

        // Óäàëÿåì èç ôàéëà âõîæäåíèÿ ñ âíåäð¸ííûìè îáúåêòàìè 
        $text = preg_replace("/HYPER13 *(INCLUDEPICTURE|HTMLCONTROL)(.*)HYPER15/iU", "", $text);
        $text = preg_replace("/HYPER13(.*)HYPER14(.*)HYPER15/iU", "$2", $text);
        // Âîçâðàùàåì ðåçóëüòàò 
        return $text;
    }

    // Ôóíêöèÿ ïðåîáðàçîâàíèÿ èç Unicode â UTF8, à òî êàê-òî íå àéñ. 
    protected function unicode_to_utf8($in) {
        $out = "";
        // Èä¸ì ïî äâóõáàéòîâûì ïîñëåäîâàòåëüíîñòÿì 
        for ($i = 0; $i < strlen($in); $i += 2) {
            $cd = substr($in, $i, 2);

            // Åñëè âåðõíèé áàéò íóëåâîé, òî ïåðåä íàìè ANSI 
            if (ord($cd[1]) == 0) {
                // Â ñëó÷àå, åñëè ASCII-çíà÷åíèå íèæíåãî áàéòà âûøå 32, òî ïèøåì êàê åñòü. 
                if (ord($cd[0]) >= 32)
                    $out .= $cd[0];

                // Â ïðîòèâíîì ñëó÷àå ïðîâåðÿåì ñèìâîëû íà âíåäð¸ííûå êîìàíäû (ñïèñîê ìîæíî 
                // äîïîëíèòü è ïîïîëíèòü). 
                switch (ord($cd[0])) {
                    case 0x0D: case 0x07: $out .= "\n";
                        break;
                    case 0x08: case 0x01: $out .= "";
                        break;
                    case 0x13: $out .= "HYPER13";
                        break;
                    case 0x14: $out .= "HYPER14";
                        break;
                    case 0x15: $out .= "HYPER15";
                        break;
                }
            } else // Èíà÷å ïðåîáðàçîâûâàåì â HTML entity 
                $out .= html_entity_decode("&#x" . sprintf("%04x", $this->getShort(0, $cd)) . ";");
        }

        // È âîçâðàùàåì ðåçóëüòàò 
        return $out;
    }

}

// Ôóíêöèÿ äëÿ ïðåîáðàçîâàíèÿ doc â plain-text. Äëÿ òåõ, êîìó "íå íóæíû êëàññû". 
function doc2text($filename) {
    $doc = new doc;
    $doc->read($filename);
    return $doc->parse();
}