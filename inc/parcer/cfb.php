<?php

// Чтение WCBFF 
// Версия 0.2 
// Автор: Алексей Рембиш a.k.a Ramon 
// E-mail: alex@rembish.ru 
// Copyright 2009 
// Итак, мальчики-девочки, перед вами класс для работы с WCBFF, что расшифровывается, как 
// Windows Compound Binary File Format. Зачем это нужно? На основе этого 
// формата строятся такие "вкусные" файлы как .doc, .xls и .ppt. Поехали, смотреть, как 
// это устроено! 
class cfb {

    // В эту переменную будет прочитано содержимое файла, который нужно расшифровать. 
    protected $data = "";
    // Размеры FAT-сектора (1 << 9 = 512), Mini FAT-сектора (1 << 6 = 64) и максимальный 
    // размер потока, который может быть записан в miniFAT'е. 
    protected $sectorShift = 9;
    protected $miniSectorShift = 6;
    protected $miniSectorCutoff = 4096;
    // Массив последовательности FAT-секторов и массив "файлов" файловой структуры файла 
    protected $fatChains = array();
    protected $fatEntries = array();
    // Массив последовательностей Mini FAT-секторов и весь Mini FAT нашего файла 
    protected $miniFATChains = array();
    protected $miniFAT = "";
    // Версия (3 или 4), а также способ записи чисел (little-endian) 
    private $version = 3;
    private $isLittleEndian = true;
    // Количество "файлов" и позиция описания первого "файла" в FAT'е 
    private $cDir = 0;
    private $fDir = 0;
    // Количество FAT-секторов в файле 
    private $cFAT = 0;
    // Количество miniFAT-секторов и позиция последовательности miniFAT-секторов в файле 
    private $cMiniFAT = 0;
    private $fMiniFAT = 0;
    // DIFAT: количество таковых секторов и смещение до 110 сектора (первые 109 в заголовке) 
    private $DIFAT = array();
    private $cDIFAT = 0;
    private $fDIFAT = 0;

    // Костанты: конец цепочки и пустой сектор (по 4 байта каждая) 
    const ENDOFCHAIN = 0xFFFFFFFE;
    const FREESECT = 0xFFFFFFFF;

    // Читаем файл во внутреннюю переменную класса 
    public function read($filename) {
        $this->data = file_get_contents($filename);
    }

    public function parse() {
        // Первое что делаем, так проверяем - на самом ли деле перед нами CFB? 
        // Для это считываем первые 8 байт и проверяем на соответствие с двумя шаблонами: повсеместным и  
        // древним - оставшимся по совместимости. 
        $abSig = strtoupper(bin2hex(substr($this->data, 0, 8)));
        if ($abSig != "D0CF11E0A1B11AE1" && $abSig != "0E11FC0DD0CF11E0") {
            return false;
        }

        // Далее читаем заголовок файла; 
        $this->readHeader();
        // дополучаем оставшиеся DIFAT-сектора, если такие есть; 
        $this->readDIFAT();
        // читаем последовательности FAT-секторов 
        $this->readFATChains();
        // читаем последовательности MiniFAT-секторов 
        $this->readMiniFATChains();
        // получаем структуру "директории" внутри файла 
        $this->readDirectoryStructure();


        // Ну и наконец проверяем наличие корневого вхождения в файловую структуру 
        // Данный поток обязательно должен присутствовать в файле, как минимум потому, 
        // что содержит ссылку на miniFAT-файла, который мы прочитываем в соответствующую 
        // переменную. 
        $reStreamID = $this->getStreamIdByName("Root Entry");
        if ($reStreamID === false) {
            return false;
        }
        $this->miniFAT = $this->getStreamById($reStreamID, true);

        // Удаляем ненужные нам ссылку на DIFAT-сектора, вместо них у нас есть полноценные 
        // цепочки FAT. 
        unset($this->DIFAT);

        // После выполнения этих действий можно начинать работать с любым из "надстроечных" 
        // форматов Microsoft: doc, xls или ppt. 
    }

    // Функция, которая находит (если находит) номер потока (stream'а) в структуре "директории" 
    // по его имени. В противном случае - false. 
    public function getStreamIdByName($name, $from = 0) {
        for ($i = $from; $i < count($this->fatEntries); $i++) {
            if ($this->fatEntries[$i]["name"] == $name)
                return $i;
        }
        return false;
    }

    // Функция получает на вход номер потока ($id) и, в качестве исключения для корневого 
    // вхождения, второй параметр. Возвращает бинарное содержимое данного потока. 
    public function getStreamById($id, $isRoot = false) {
        $entry = $this->fatEntries[$id];
        // Получаем размер и позицию смещения на содержимое "текущего" файла. 
        $from = $entry["start"];
        $size = $entry["size"];

        // Дальше варианта два - если размер меньше 4096 байт, то нам стоит читать данные 
        // из MiniFAT'а, если больше так будем читать из общего FAT'а. Исключение RootEntry, 
        // для которого мы должны прочитать содержимое из FAT'а - ведь там как раз таки 
        // хранится MiniFAT. 

        $stream = "";
        // Итак, перед нами вариант №1 - маленький размер и не корень 
        if ($size < $this->miniSectorCutoff && !$isRoot) {
            // Получаем размер сектора miniFAT - 64 байта 
            $ssize = 1 << $this->miniSectorShift;

            do {
                // Получаем смещение в miniFAT'е 
                $start = $from << $this->miniSectorShift;
                // Читаем miniFAT-сектор 
                $stream .= substr($this->miniFAT, $start, $ssize);
                // Находим следующий кусок miniFAT'а в массиве последовательностей 
                $from = isset($this->miniFATChains[$from]) ? $this->miniFATChains[$from] : self::ENDOFCHAIN;
                // Пока не наткнёмся на флаг конца последовательности. 
            } while ($from != self::ENDOFCHAIN);
        } else {
            // Вариант №2 - кусок большой - читаем из FAT. 
            // Находим размер сектора - 512 (или 4096 для новых версий) 
            $ssize = 1 << $this->sectorShift;

            do {
                // Находим смещение в файле (учитывая, что вначале файла заголовок на 512 байт) 
                $start = ($from + 1) << $this->sectorShift;
                // Читаем сектор 
                $stream .= substr($this->data, $start, $ssize);
                // Находим следующий сектор в массиве FAT-последовательностей 
                #if (!isset($this->fatChains[$from])) 
                #    $from = self::ENDOFCHAIN; 
                #elseif ($from != self::ENDOFCHAIN && $from != self::FREESECT) 
                #    $from = $this->fatChains[$from]; 
                $from = isset($this->fatChains[$from]) ? $this->fatChains[$from] : self::ENDOFCHAIN;
                // Пока не наткнёмся на конец последовательности. 
            } while ($from != self::ENDOFCHAIN);
        }
        // Возвращаем содержимое потока с учётом его размера. 
        return substr($stream, 0, $size);
    }

    // Функция читает нужные и важные данные из заголовка файла 
    private function readHeader() {
        // Для начала узнаем как записаны данные в файле 
        $uByteOrder = strtoupper(bin2hex(substr($this->data, 0x1C, 2)));
        // Что ж наверняка это будет little-endian запись, но на всякий случай проверим 
        $this->isLittleEndian = $uByteOrder == "FEFF";

        // Версия 3 или 4 (4ую ни разу не встречал, но в документации она описана) 
        $this->version = $this->getShort(0x1A);

        // Смещения для FAT и miniFAT 
        $this->sectorShift = $this->getShort(0x1E);
        $this->miniSectorShift = $this->getShort(0x20);
        $this->miniSectorCutoff = $this->getLong(0x38);

        // Количество вхождений в директорию файла и смещения до первого описания в файле 
        if ($this->version == 4)
            $this->cDir = $this->getLong(0x28);
        $this->fDir = $this->getLong(0x30);

        // Количество FAT-секторов в файле 
        $this->cFAT = $this->getLong(0x2C);

        // Количество и позиция первого miniFAT-сектора последовательностей. 
        $this->cMiniFAT = $this->getLong(0x40);
        $this->fMiniFAT = $this->getLong(0x3C);

        // Где лежат цепочки FAT-секторов и сколько таких цепочек. 
        $this->cDIFAT = $this->getLong(0x48);
        $this->fDIFAT = $this->getLong(0x44);
    }

    // Итак, DIFAT. DIFAT показывает в каких секторах файла лежат 
    // описания цепочек FAT-секторов. Без этих цепочек мы не сможем 
    // прочитать содержимое потоков в сильно "фрагментированных" 
    // файлах 
    private function readDIFAT() {
        $this->DIFAT = array();
        // Первые 109 ссылок на цепочки хранятся прямо в заголовке нашего файла 
        for ($i = 0; $i < 109; $i++)
            $this->DIFAT[$i] = $this->getLong(0x4C + $i * 4);

        // Там же мы смотрим, есть ли ещё где-нибудь ссылки на цепочки. В небольших 
        // файлах (до 8,5 Мб) их нет (хватает первых 109 ссылок), в больших - мы 
        // обязаны прочитать и их. 
        if ($this->fDIFAT != self::ENDOFCHAIN) {
            // Размер сектора и позиция откуда надо начинать читать ссылки. 
            $size = 1 << $this->sectorShift;
            $from = $this->fDIFAT;
            $j = 0;

            do {
                // Получаем позицию в файле с учётом заголовка 
                $start = ($from + 1) << $this->sectorShift;
                // Читаем ссылки на сектора цепочек 
                for ($i = 0; $i < ($size - 4); $i += 4)
                    $this->DIFAT[] = $this->getLong($start + $i);
                // Íàõîäèì ñëåäóþùèé DIFAT-ñåêòîð - ññûëêà íà íåãî 
                // çàïèñàíà ïîñëåäíèì "ñëîâîì" â òåêóùåì DIFAT-ñåêòîðå 
                $from = $this->getLong($start + $i);
                // Åñëè ñåêòîð ñóùåñòâóåò, òî ìåòí¸ìñÿ ê íåìó. 
            } while ($from != self::ENDOFCHAIN && ++$j < $this->cDIFAT);
        }

        // Äëÿ ýêîíîìèè óäàëÿåì êîíå÷íûå íåèñïîëüçóåìûå ññûëêè. 
        while ($this->DIFAT[count($this->DIFAT) - 1] == self::FREESECT)
            array_pop($this->DIFAT);
    }

    // Òàê, DIFAT ìû ïðî÷èòàëè - òåïåðü íóæíî ññûëêè íà öåïî÷êè FAT-ñåêòîðîâ 
    // ïðåâðàòèòü â ðåàëüíûå öåïî÷êè. Ïîýòîìó ïîáåãàåì ïî ôàéëó äàëüøå. 
    private function readFATChains() {
        // Ðàçìåð ñåêòîðà 
        $size = 1 << $this->sectorShift;
        $this->fatChains = array();

        // Îáõîäèì ìàññèâ DIFAT. 
        for ($i = 0; $i < count($this->DIFAT); $i++) {
            // Èä¸ì ïî ññûëêå íà íóæíûé íàì ñåêòîð (ñ ó÷¸òîì çàãîëîâêà) 
            $from = ($this->DIFAT[$i] + 1) << $this->sectorShift;
            // Ïîëó÷àåì öåïî÷êó FAT: èíäåêñ ìàññèâà - ýòî òåêóùèé ñåêòîð, 
            // çíà÷åíèå ýëåìåíòà ìàññèâà - èíäåêñ ñëåäóþùåãî ýëåìåíòà èëè 
            // ENDOFCHAIN - åñëè ýòî ïîñëåäíèé ýëåìåíò öåïî÷êè. 
            for ($j = 0; $j < $size; $j += 4)
                $this->fatChains[] = $this->getLong($from + $j);
        }
    }

    // FAT-öåïî÷êè ìû ïðî÷èòàëè, òåïåðü íóæíî ïðî÷èòàòü MiniFAT-öåïî÷êè 
    // àáñîëþòíî òàêæå. 
    private function readMiniFATChains() {
        // Ðàçìåð ñåêòîðà 
        $size = 1 << $this->sectorShift;
        $this->miniFATChains = array();

        // Èùåì ïåðâûé ñåêòîð ñ MiniFAT-öåïî÷êàìè 
        $from = $this->fMiniFAT;
        // Åñëè â ôàéëå MiniFAT èñïîëüçóåòñÿ, òî  
        while ($from != self::ENDOFCHAIN) {
            // íàõîäèì ñìåùåíèå ê ñåêòîðó ñ MiniFat-öåïî÷êîé 
            $start = ($from + 1) << $this->sectorShift;
            // ×èòàåì öåïî÷êó èç òåêóùåãî ñåêòîðà 
            for ($i = 0; $i < $size; $i += 4)
                $this->miniFATChains[] = $this->getLong($start + $i);
            // È åñëè ýòîò ñåêòîð íå êîíå÷íûé â FAT-öåïî÷êå, òî ïåðåõîäèì äàëüøå. 
            $from = isset($this->fatChains[$from]) ? $this->fatChains[$from] : self::ENDOFCHAIN;
        }
    }

    // Ñàìàÿ âàæíàÿ ôóíêöèÿ, êîòîðàÿ ÷èòàåò ñòðóêòóðó "ôàéëîâ" äàííîãî ôàéëà (óæ ïðîñòèòå 
    // çà êàëàìáóð). Â ýòó ñòðóêòóðó çàïèñàíû âñå îáúåêòû ÔÑ äàííîãî ôàéëà. 
    private function readDirectoryStructure() {
        // Íàõîäèì ïåðâûé ñåêòîð ñ "ôàéëàìè" ÔÑ 
        $from = $this->fDir;
        // Ïîëó÷àåì ðàçìåð ñåêòîðà 
        $size = 1 << $this->sectorShift;
        $this->fatEntries = array();
        do {
            // Íàõîäèì ñåêòîð â ôàéëå 
            $start = ($from + 1) << $this->sectorShift;
            // Äàëåå øë¸ïàåì ïî ñîäåðæèìîìó ñåêòîðà. Â îäíîì ñåêòîðå ñîäåðæèòñÿ äî 4 (èëè 128 äëÿ âåðñèè 4) 
            // âõîæäåíèé â ÔÑ. ×èòàåì èõ. 
            for ($i = 0; $i < $size; $i += 128) {
                // Ïîëó÷àåì áèíàðíûé êóñîê 
                $entry = substr($this->data, $start + $i, 128);
                // è îáðàáàòûâàåì åãî: 
                $this->fatEntries[] = array(
                    // Ïîëó÷àåì èìÿ âõîæäåíèÿ 
                    "name" => $this->utf16_to_ansi(substr($entry, 0, $this->getShort(0x40, $entry))),
                    // åãî òèï - ïîòîê, ïîëüçîâàòåëüñêèå äàííûå, ïóñòîé ñåêòîð è ò.ä. 
                    "type" => ord($entry[0x42]),
                    // åãî öâåò â Red-Black äåðåâå 
                    "color" => ord($entry[0x43]),
                    // åãî ëåâûå áðàòüÿ 
                    "left" => $this->getLong(0x44, $entry),
                    // åãî ïðàâûå áðàòüÿ 
                    "right" => $this->getLong(0x48, $entry),
                    // åãî äî÷åðíèé ýëåìåíò 
                    "child" => $this->getLong(0x4C, $entry),
                    // ñìåùåíèå äî ñîäåðæèìîãî â FAT èëè miniFAT 
                    "start" => $this->getLong(0x74, $entry),
                    // ðàçìåð ñîäåðæèìîãî 
                    "size" => $this->getSomeBytes($entry, 0x78, 8),
                );
            }

            // Ïîòîì íàõîäèì ñëåäóþùèé ñåêòîð ñ îïèñàíèÿìè è ïðûãàåì òóäà 
            $from = isset($this->fatChains[$from]) ? $this->fatChains[$from] : self::ENDOFCHAIN;
            // Åñëè êîíå÷íî òàêîé èìååòñÿ 
        } while ($from != self::ENDOFCHAIN);

        // Óäàëÿåì êîíå÷íûå "ïóñòûå" âõîæäåíèÿ, åñëè òàêîâûå åñòü. 
        while ($this->fatEntries[count($this->fatEntries) - 1]["type"] == 0)
            array_pop($this->fatEntries);

        #dump($this->fatEntries, false); 
    }

    // Âñïîìîãàòåëüíàÿ ôóíêöèÿ äëÿ ïîëó÷åíèÿ àäåêâàòíîãî èìåíè òåêóùåãî âõîæäåíèÿ â ÔÑ. 
    // Çàìå÷ó, ÷òî èìåíà çàïèñàíû â Unicode. 
    private function utf16_to_ansi($in) {
        $out = "";
        for ($i = 0; $i < strlen($in); $i += 2)
            $out .= chr($this->getShort($i, $in));
        return trim($out);
    }

    // Ôóíêöèÿ ïðåîáðàçîâàíèÿ èç Unicode â UTF8, à òî êàê-òî íå àéñ. 
    protected function unicode_to_utf8($in, $check = false) {
        $out = "";
        if ($check && strpos($in, chr(0)) !== 1) {
            while (($i = strpos($in, chr(0x13))) !== false) {
                $j = strpos($in, chr(0x15), $i + 1);
                if ($j === false)
                    break;

                $in = substr_replace($in, "", $i, $j - $i);
            }
            for ($i = 0; $i < strlen($in); $i++) {
                if (ord($in[$i]) >= 32) {
                    
                } elseif ($in[$i] == ' ' || $in[$i] == '\n') {
                    
                }
                else
                    $in = substr_replace($in, "", $i, 1);
            }
            $in = str_replace(chr(0), "", $in);

            return $in;
        } elseif ($check) {
            while (($i = strpos($in, chr(0x13) . chr(0))) !== false) {
                $j = strpos($in, chr(0x15) . chr(0), $i + 1);
                if ($j === false)
                    break;

                $in = substr_replace($in, "", $i, $j - $i);
            }
            $in = str_replace(chr(0) . chr(0), "", $in);
        }

        // Èä¸ì ïî äâóõáàéòîâûì ïîñëåäîâàòåëüíîñòÿì 
        $skip = false;
        for ($i = 0; $i < strlen($in); $i += 2) {
            $cd = substr($in, $i, 2);
            if ($skip) {
                if (ord($cd[1]) == 0x15 || ord($cd[0]) == 0x15)
                    $skip = false;
                continue;
            }

            // Åñëè âåðõíèé áàéò íóëåâîé, òî ïåðåä íàìè ANSI 
            if (ord($cd[1]) == 0) {
                // Â ñëó÷àå, åñëè ASCII-çíà÷åíèå íèæíåãî áàéòà âûøå 32, òî ïèøåì êàê åñòü. 
                if (ord($cd[0]) >= 32)
                    $out .= $cd[0];
                elseif ($cd[0] == ' ' || $cd[0] == '\n')
                    $out .= $cd[0];
                elseif (ord($cd[0]) == 0x13)
                    $skip = true;
                else {
                    continue;
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
                        default: $out .= " ";
                            break;
                    }
                }
            } else { // Èíà÷å ïðåîáðàçîâûâàåì â HTML entity 
                if (ord($cd[1]) == 0x13) {
                    echo "@";
                    $skip = true;
                    continue;
                }
                $out .= "&#x" . sprintf("%04x", $this->getShort(0, $cd)) . ";";
            }
        }

        // È âîçâðàùàåì ðåçóëüòàò 
        return $out;
    }

    // Âñïîìîãàòåëüíàÿ ôóíêöèÿ äëÿ ÷òåíèÿ íåêîòîðîãî êîëè÷åñòâà áàéò èç ñòðîêè 
    // ñ ó÷¸òîì ïîðÿäêà áàéòîâ è ïðåîáðàçîâàíèÿ çíà÷åíèå â ÷èñëî. 
    protected function getSomeBytes($data, $from, $count) {
        // Ïî óìîë÷àíèþ ÷èòàåì äàííûå èç ïåðåìåííîé êëàññà $data. 
        if ($data === null)
            $data = $this->data;

        // ×èòàåì êóñîê 
        $string = substr($data, $from, $count);
        // Â ñëó÷àå îáðàòíîãî ïîðÿäêà áàéòîâ - ïåðåâîðà÷èâàåì êóñîê 
        if ($this->isLittleEndian)
            $string = strrev($string);

        // Ïåðåêîäèðóåì èç áèíàðíîãî ôîðìàòà â hex'û, à ïîòîì â ÷èñëî. 
        return hexdec(bin2hex($string));
    }

    // ×èòàåì ñëîâî èç ïåðåìåííîé (ïî óìîë÷àíèþ èç this->data) 
    protected function getShort($from, $data = null) {
        return $this->getSomeBytes($data, $from, 2);
    }

    // ×èòàåì äâîéíîå ñëîâî èç ïåðåìåííîé (ïî óìîë÷àíèþ èç this->data) 
    protected function getLong($from, $data = null) {
        return $this->getSomeBytes($data, $from, 4);
    }

}

?>