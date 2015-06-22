<?php

/**
 * @file Функции
 * 
 */

/**
 *  Сканирование директорий и получение списка объектов
 * @param string $dir Директория для сканирования
 * @param boolean $recurse Рекурсивнаое сканирование
 * @param int $depth Глубина (false = без ограничений)
 * @param boolean $hidden Учитывать скрытые файлы (с точки). По умолчанию FALSE
 * 
 * array['fields']  
 *  [fieldName] 
 *    ['name'] путь к файлу от текущего уровня
 *    ['size'] размер файла (диретории) в байтах
 *    ['lastmod'] время последней модификации файла(директории) в UNIXTIME
 * @return array(string|int|int)[]  $retval (See above)
 * 
 *  */
function getFileList($dir, $recurse = FALSE, $depth = FALSE, $hidden = FALSE) {
  // массив, хранящий возвращаемое значение
  $retval = array();

  // добавить конечный слеш, если его нет
  if (substr($dir, -1) != "/")
    $dir .= "/";

  // указание директории и считывание списка файлов
  $d = @dir($dir) or die("getFileList: Не удалось открыть каталог $dir для чтения");
  while (false !== ($entry = $d->read())) {

    // пропустить скрытые файлы
    if (($entry[0] == "." && $hidden == FALSE) OR $entry == "." OR $entry == "..")
      continue;
    if (is_dir("$dir$entry")) {
      $retval[] = array(
        "name" => "$dir$entry/",
        "size" => 0,
        "lastmod" => filemtime("$dir$entry")
      );
      if ($recurse && is_readable("$dir$entry/")) {
        if ($depth === false) {
          $retval = array_merge($retval, getFileList("$dir$entry/", TRUE, FALSE, $hidden));
        }
        elseif ($depth > 0) {
          $retval = array_merge($retval, getFileList("$dir$entry/", TRUE, $depth - 1, $hidden));
        }
      }
    }
    elseif (is_readable("$dir$entry")) {
      $retval[] = array(
        "name" => "$dir$entry",
        "size" => filesize("$dir$entry"),
        "lastmod" => filemtime("$dir$entry")
      );
    }
  }
  $d->close();

  return $retval;
}

/**
 * 
 * Функция обрезания текста по вхождениям
 * @param string $text Текст на входе
 * @param string $startEntry Начальное вхождение
 * @param string $endEntry Конечное вхождение
 * @param boolean $includeStart Устарело
 * @param boolean $includeEnd Устарело
 * @return string Текст на выходе
 * @todo Разобраться с устаревшими параметрами
 */
function truncateText($text, $startEntry, $endEntry, $includeStart = FALSE, $includeEnd = FALSE) {
  $lenghtStartEntry = mb_strlen($startEntry);
  $lenghtEndEntry = mb_strlen($endEntry);

//    if ($includeStart == TRUE) {
//        $positionStart = mb_strpos($text, $startEntry);
//    } else {
//        $positionStart = mb_strpos($text, $startEntry) + $lenghtStartEntry;
//    }


  if ($startEntry == NULL) {
    $positionStart = 0;  //
  }
  else {
    $positionStart = mb_strpos($text, $startEntry) + $lenghtStartEntry;
  }

  if ($endEntry == NULL) {
    $result = trim(mb_substr($text, $positionStart));  //
  }
  else {
    $positionEnd = mb_strpos($text, $endEntry, $positionStart);
    //если же вхождение не найдено
    if ($positionEnd == NULL) {
      $result = trim(mb_substr($text, $positionStart));
    }
    else {
      $result = trim(mb_substr($text, $positionStart, $positionEnd - $positionStart));  //
    }
  }


  return $result;
}

/**
 *
 * Функция вырезания текста по вхождениям
 * @param string $text Текст на входе
 * @param string $startEntry Начальное вхождение
 * @param string $endEntry Конечное вхождение
 * @return string Текст на выходе
 * 
 */
function removeExcess($text, $startEntry, $endEntry, $required = FALSE) {
  $lenghtStartEntry = mb_strlen($startEntry);
  $lenghtEndEntry = mb_strlen($endEntry);
  if ($startEntry == NULL) {
    $positionStart = 0;  //
  }
  else {
    //$positionStart = mb_strpos($text, $startEntry) + $lenghtStartEntry;
    $positionStart = mb_strpos($text, $startEntry);
  }
  $contentHead = mb_substr($text, 0, $positionStart);


  $contentTail = mb_substr($text, $positionStart);

  $tailLenght = mb_strlen($contentTail);
  if ($endEntry == NULL) {
    $positionEnd = $tailLenght;
  }
  else {
    //$positionStart = mb_strpos($text, $startEntry) + $lenghtStartEntry;
    $positionEnd = mb_strpos($contentTail, $endEntry);
  }
  $contentTail = mb_substr($contentTail, $positionEnd + $lenghtEndEntry);

  echo "AA" . $positionStart . " - " . $positionEnd . "\n";


  if ($required == TRUE && $positionStart !== FALSE && $positionEnd !== FALSE) {
    $result = $contentHead . $contentTail;
  }
  elseif ($required == FALSE) {
    $result = $contentHead . $contentTail;
  }
  else {
    $result = $text;
  }

  return $result;
}

/**
 * Функция проверки наличия слэша в конце строки
 * @param string $text Проверяемая строка
 * @return boolean
 */
function checkingForSlash($text) {
  $pos = mb_strpos($text, "/", mb_strlen($text) - 1);
  if ($pos === false) {
    return FALSE;
  }
  else {
    return TRUE;
  }
}

function createDir($path) {
  if (!file_exists($path)) {
    echo 'Создаваемая директория ' . $path . "\n";
    mkdir($path, 0755, true); // создаём директорию
  }
}

function minMaxValues($array) {
  $count['max']['value'] = max($array);
  $count['min']['value'] = min($array);
  echo 'Минимальное значение: ' . $count['min']['value'] . "\n";
  echo 'Максимальное значение: ' . $count['max']['value'] . "\n";
  //exit();
  $count['max']['amount'] = 0;
  $count['min']['amount'] = 0;
  foreach ($array as $key => $val) {
    if ($count['max']['value'] == $val) {
      $count['indexes'][] = $key;
      $count['max']['amount'] ++; //считаем, сколько ключей с максимальным значением
    }
  }
  foreach ($array as $key => $val) {
    if ($count['max']['value'] == $val) {
      $count['indexes'][] = $key;
      $count['min']['amount'] ++; //считаем, сколько ключей с минимальным значением
    }
  }
  return $count;
}

function lenghtEntryAsterisks($param) {
  $asteriskLenght = 0;
  //извлекаем все строки со звёздочками, содержащие текст
  if (preg_match('/(^(\*){1,50})(\*?)(.*?)([^*])(\**)\z/sm', $param)) {
    $asterisksBefore = trim(preg_replace('/(^(\*){1,50})(\*?)(.*?)([^*])(\**)\z/sm', '$1', $param));
    $asteriskLenght = (int) strlen($asterisksBefore); //длина "одни звёздочки" в цифрах
  }
  return $asteriskLenght;
}

function manStyle($param) {
  if (preg_match('/(.*)\S\s--\s\S(.*)\z/m', $param)) {
    return TRUE;
  }
  else {
    return FALSE;
  }
}

function mb_str_replace($needle, $replacement, $haystack) {
  $needle_len = mb_strlen($needle);
  $replacement_len = mb_strlen($replacement);
  $pos = mb_strpos($haystack, $needle);
  while ($pos !== false) {
    $haystack = mb_substr($haystack, 0, $pos) . $replacement
        . mb_substr($haystack, $pos + $needle_len);
    $pos = mb_strpos($haystack, $needle, $pos + $replacement_len);
  }
  return $haystack;
}

function cleanPageContent($text) {
  $text = mb_str_replace("\n\r", "\n", $text);
  $text = mb_str_replace("\n\n\n\n\n", "\n", $text);
  $text = mb_str_replace("\n\n\n\n", "\n", $text);
  $text = mb_str_replace("\n\n\n", "\n", $text);
  $text = mb_str_replace("\n\n", "\n", $text);

  $text = str_replace('oldchakra.com', 'localhost/out', $text);
  $text = removeExcess($text, '<meta name=" vi60_defaultclientscript"="">', 'Lord Chaitanya, Balar">', TRUE);
  $text = removeExcess($text, '<meta name="VI60_', 'Balar">', TRUE);
  $text = removeExcess($text, '<script', '/script>', TRUE);
  $text = removeExcess($text, '<script', '/script>', TRUE);
  $text = removeExcess($text, '<script', '/script>', TRUE);
  $text = removeExcess($text, '<script', '/script>', TRUE);
  $text = removeExcess($text, '<script', '/script>', TRUE);
  $text = removeExcess($text, '<meta name="Description" ', 'content="FrontPage.Editor.Document">', TRUE);

  $text = removeExcess($text, '<!onMouseover --', '/style2003.css">', TRUE);
  $text = removeExcess($text, 'bgcolor="#FFFFFF"', '"fixed"', TRUE);
  $text = removeExcess($text, '<p align="center"><a href="http://localhost/out/#top">', '>&nbsp;</p>', TRUE);
  $text = removeExcess($text, '<p align="Center">All rights', '&nbsp;</p>', TRUE);
  $text = removeExcess($text, '<p align="Center"><strong><font face="Arial', '</strong>', TRUE);
  $text = mb_str_replace('<!-- =================TOOLBAR===============  -->', '', $text);
  $text = mb_str_replace('<link rel="stylesheet" type="text/css" href="http://localhost/out/~styles/style2003.css">', '', $text);
  $text = mb_str_replace('<link type="text/css" rel="stylesheet" href="/static/css/banner-styles.css"/>', '', $text);
  $text = mb_str_replace('<meta http-equiv="Content-Type"
content="text/html; charset=UTF-8>', '<meta http-equiv="Content-Type" content="text/html"; charset=UTF-8>', $text);
  $text = mb_str_replace('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8>', '<meta http-equiv="Content-Type" content="text/html"; charset=UTF-8>', $text);
  $text = mb_str_replace('<body text="#000000" link="#0000ff"
vlink="#0000ff" bgcolor="#ffffff">', '<body>', $text);
  $text = removeExcess($text, '<!--webbot', '-->', TRUE);
  $text = removeExcess($text, '<!--webbot', '-->', TRUE);
  $text = removeExcess($text, '<!--webbot', '-->', TRUE);
  $text = removeExcess($text, '<!--webbot', '-->', TRUE);
  $text = removeExcess($text, '<!--webbot', '-->', TRUE);
  $text = removeExcess($text, '<!--webbot', '-->', TRUE);
  $text = removeExcess($text, '<!--webbot', '-->', TRUE);
  $text = removeExcess($text, '<meta name="Description', '">', TRUE);
  $text = removeExcess($text, '<meta name="KEYWORDS"', '">', TRUE);
  $text = removeExcess($text, '<table border="1" width="100%">', '</table>', TRUE);
  //$inFileContent = removeExcess($inFileContent, '', '', TRUE);
  $text = removeExcess($text, '<table border="0" cellpadding="6" cellspacing="0" width="100%">', '</table>', TRUE);


  $text = removeExcess($text, '<p align="center"><strong><font color="#800000"><small>', '</p>', TRUE);
  $text = removeExcess($text, '<p align="center"><small>For', '</p>', TRUE);
  $text = removeExcess($text, '<p><font color="#cc0000" face="Times New Roman', '/p>', TRUE);
  $text = removeExcess($text, '<p align="center"><a href="http://localhost/out/index.html"><img', '></p>', TRUE);
  $text = removeExcess($text, '<p align="center"><strong><font
color="#800000"><small>©', '</p>', TRUE);
  $text = removeExcess($text, '<!-- Pi', 'e -->', TRUE);
  $text = removeExcess($text, '<p><font color="#cc0000" face="Times', '</p>', TRUE);
  $text = removeExcess($text, '<p align="left"><i>©', 'p>', TRUE);
  $text = removeExcess($text, '<meta name="GENERATOR"', 'Document">', TRUE);
  $text = removeExcess($text, '<p align="center"><small>Hare Krishna!', '</table>', TRUE);
//$inFileContent = removeExcess($inFileContent, '', '', TRUE);
  $text = mb_str_replace('<p>&nbsp;</p>', '', $text);
  $text = mb_str_replace('<p align="left">&nbsp;</p>', '', $text);

  $text = removeExcess($text, '<table border="4" width="210"', '</table>', TRUE);
//$inFileContent = removeExcess($inFileContent, '', '</div>', TRUE);
  $text = mb_str_replace('<div align="right">
          
        </div>', '', $text);
  $text = mb_str_replace('<span style="font-size:11.0pt;
mso-bidi-font-size:10.0pt;font-family:Arial;mso-fareast-font-family:&quot;MS Mincho&quot;">', '<span>', $text);
  $text = removeExcess($text, '<font size="2">This', '</font>', TRUE);
  $text = removeExcess($text, '<table border="0" width="100%"
bgcolor="#FFC8C8"', '</table>', TRUE);
  $text = removeExcess($text, '<table border="0" width="100%"
bgcolor="#FFC8C8"', '</table>', TRUE);

  $text = removeExcess($text, '<table style="font-family: Arial, Helvetica; font-size: 8pt" bgcolor="#FFC8C8"', '</table>', TRUE);
  $text = removeExcess($text, '<p><a href="http://www.chantandbehappy.com/">', '/p>', TRUE);
  $text = removeExcess($text, '<table border="1" cellpadding="4" cellspacing="1" width="100%">', '</table>', TRUE);
  $text = removeExcess($text, '<table border="0" width="100%"
bgcolor="#FFC8C8"', '</table>', TRUE);
  $text = removeExcess($text, '<p><a
href="http://www.chantandbehappy.com/', '</p>', TRUE);

  $text = removeExcess($text, '<table border="1" width="100%"
cellspacing="1" cellpadding="4">
<tr>', '</table>', TRUE);
  $text = removeExcess($text, '<table style="font-family: Arial, Helvetica; font-size: 8pt" bordercolordark="#C0C0C0" bgcolor="#FFC8C8" border="2" cellpadding="0" cellspacing="0" width="100%">', '</tbody></table>', TRUE);
  $text = removeExcess($text, '<td width="100%">&nbsp;</td>
</tr>
</tbody>', '</table>', TRUE);

  $text = removeExcess($text, '<table style="font-family: Arial, Helvetica; font-size: 8pt" bordercolordark="#C0C0C0" bgcolor="#FFC8C8" border="2"', '</table>', TRUE);
  $text = removeExcess($text, '<table border="2" width="100%"', '</table>', TRUE);
  $text = removeExcess($text, '<p align="center"><a href="http://', '</a></p>', TRUE);
  $text = removeExcess($text, '<p align="center"><a
href="http://', '</a></p>', TRUE);

  $text = mb_str_replace('<table bgcolor="#FFC8C8" border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody><tr>
<td width="100%">&nbsp;</td>
</tr>
</tbody></table>
</div>



<p>&nbsp;</p></td>
<td width="2%"></td>
<td align="left" valign="top" width="80%">
<div align="right">
<table align="right" border="0" cellpadding="0" cellspacing="0" width="30%">
<tbody><tr>
<td width="100%">



</td>
</tr>
</tbody></table>', '', $text);
//$inFileContent = mb_str_replace('', '', $inFileContent);
  $text = removeExcess($text, '<style ', '</style>', TRUE);
  $text = removeExcess($text, '<table border="1" width="100%" bgcolor="#FFC8C8">', '</table>', TRUE);
  $text = mb_str_replace('<td width="100%">&nbsp;</td>', '', $text);

  $text = removeExcess($text, '<td valign="top" width="15%" bgcolor="#FFC8C8" style="border-right: 1 solid #C0C0C0;', '</td>', TRUE);
  $text = removeExcess($text, '<td valign="top" width="15%"
bgcolor="#FFC8C8"', '</td>', TRUE);
  $text = removeExcess($text, '<td width="100%" bgcolor="#FFFFCE">', '</font></td>', TRUE);
  $text = removeExcess($text, '<div align="right">', '</div>', TRUE);
  $text = removeExcess($text, '<div align="right">', '</div>', TRUE);
  $text = removeExcess($text, '<p align="left"><font color="#cc0000" face="Times New Roman, Georgia, Times" size="4"><b>Go to the &#147;<a href="http://', '</font></p>', TRUE);
  $text = truncateText($text, '', '<p align="center"><strong><font');
  $text = mb_str_replace('<p align="center"><a name="top"></a><small>Hare Krishna!', '', $text);
  return $text;
}
