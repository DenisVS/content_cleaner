<?php

/**
 * @file 
 * Главный файл
 * 
 */
include 'include/functions.php';
include 'include/classes.php';
$LineByLine = new LineByLine(); //новый объект
$path = new ControlEdgeSymbol (); //новый объект

if (isset($argv[1])) {
  $path->text = $argv[1];
  $path->symbol = '/';
  $path->symbolSholdBe = 0;
  $path->position = 'END';
  $inDir = $path->controlStartEndSymbol()['text'];
  $lenghtInPrefixPath = $path->controlStartEndSymbol()['lenght']; //НЕ УБИРАТЬ! Из класса нельзя получить, потому что используется многократно!
  echo 'Длина пути к файлу ' . $lenghtInPrefixPath . "\n";
//  var_dump($inDir);

  echo 'Директория исходных файлов ' . $inDir . "\n";
}
else {
  echo "Введите директорию с исходными файлами\n";
  exit();
}

if (isset($argv[2])) {

  $path->text = $argv[2];
  $path->symbol = '/';
  $path->symbolSholdBe = 0;
  $path->position = 'END';
  $outDir = $path->controlStartEndSymbol()['text'];
  //$lenghtOutPrefixPath = $path->controlStartEndSymbol()['lenght'];
  if (!file_exists($outDir)) {
    mkdir($outDir, 0755, true);
  }
  echo 'Директория с обработанными файлами ' . $outDir . "\n";
}
else {
  echo "Введите директорию с обработанными файлами\n";
  exit();
}


if (isset($argv[3])) {

  $path->text = $argv[3];
  $path->symbol = '/';
  $path->symbolSholdBe = 0;
  $path->position = 'END';
  $mediaDir = $path->controlStartEndSymbol()['text'];
  //$lenghtMediaPrefixPath = $path->controlStartEndSymbol()['lenght'];

  if (!file_exists($mediaDir)) {
    mkdir($mediaDir, 0755, true);
  }
  echo 'Директория с медиа файлами ' . $mediaDir . "\n";
}
else {
  echo "Введите директорию с медиа файлами\n";
  exit();
}


$sourceFiles = (getFileList($inDir, TRUE, FALSE, TRUE)); // получаем листинг
var_dump($sourceFiles);
//цикл перебора массива файлов
for ($i = 0; $i < count($sourceFiles); $i++) {
  echo "Обрабатывается " . $sourceFiles[$i]['name'] . "\n";
  //================ Блок определения параметров URL из пути
  $baseName = pathinfo($sourceFiles[$i]['name'], PATHINFO_BASENAME); // файл без пути
  $filename = pathinfo($sourceFiles[$i]['name'], PATHINFO_FILENAME); //расширение отдельно
  $extension = pathinfo($sourceFiles[$i]['name'], PATHINFO_EXTENSION); //расширение отдельно


  $currentFileNameFromRoot = $sourceFiles[$i]['name'];  //фиксируем имя текущего файла
  $currentFileNameInsideDir = mb_substr($currentFileNameFromRoot, $lenghtInPrefixPath + 1); // полный путь текущего файла внутри обрабатываемой директории (inDir)
  // ОТДЕЛЯЕМ ТЕКСТ ОТ МЕДИА
  if ($extension == 'htm' OR $extension == 'html') {
    //Если файл непустой 
    if ($sourceFiles[$i]['size'] > 0) {
      echo "Файл: " . $sourceFiles[$i]['name'] . "\n";
      echo "Размер > 0!\n";


      $inFileContent = file_get_contents($currentFileNameFromRoot); // дёргаем контент целиком
      //echo "Содержимое файла целиком:\n".$contentInFile."\n";
//===================== НАЧИНАЕМ РАЗБИРАТЬ ФАЙЛА ЦЕЛИКОМ =================
      $inFileContent = mb_str_replace("\n\r", "\n", $inFileContent);
      $inFileContent = mb_str_replace("\n\n\n\n\n", "\n", $inFileContent);
      $inFileContent = mb_str_replace("\n\n\n\n", "\n", $inFileContent);
      $inFileContent = mb_str_replace("\n\n\n", "\n", $inFileContent);
      $inFileContent = mb_str_replace("\n\n", "\n", $inFileContent);

      $inFileContent = str_replace('oldchakra.com', 'localhost/out', $inFileContent);
      $inFileContent = removeExcess($inFileContent, '<meta name=" vi60_defaultclientscript"="">', 'Lord Chaitanya, Balar">', TRUE);
      $inFileContent = removeExcess($inFileContent, '<meta name="VI60_', 'Balar">', TRUE);
      $inFileContent = removeExcess($inFileContent, '<script', '/script>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<script', '/script>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<script', '/script>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<script', '/script>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<script', '/script>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<meta name="Description" ', 'content="FrontPage.Editor.Document">', TRUE);

      $inFileContent = removeExcess($inFileContent, '<!onMouseover --', '/style2003.css">', TRUE);
      $inFileContent = removeExcess($inFileContent, 'bgcolor="#FFFFFF"', '"fixed"', TRUE);
      $inFileContent = removeExcess($inFileContent, '<p align="center"><a href="http://localhost/out/#top">', '>&nbsp;</p>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<p align="Center">All rights', '&nbsp;</p>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<p align="Center"><strong><font face="Arial', '</strong>', TRUE);
      $inFileContent = mb_str_replace('<!-- =================TOOLBAR===============  -->', '', $inFileContent);
      $inFileContent = mb_str_replace('<link rel="stylesheet" type="text/css" href="http://localhost/out/~styles/style2003.css">', '', $inFileContent);
      $inFileContent = mb_str_replace('<link type="text/css" rel="stylesheet" href="/static/css/banner-styles.css"/>', '', $inFileContent);
      $inFileContent = mb_str_replace('<meta http-equiv="Content-Type"
content="text/html; charset=UTF-8>', '<meta http-equiv="Content-Type" content="text/html"; charset=UTF-8>', $inFileContent);
      $inFileContent = mb_str_replace('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8>', '<meta http-equiv="Content-Type" content="text/html"; charset=UTF-8>', $inFileContent);
      $inFileContent = mb_str_replace('<body text="#000000" link="#0000ff"
vlink="#0000ff" bgcolor="#ffffff">', '<body>', $inFileContent);
      $inFileContent = removeExcess($inFileContent, '<!--webbot', '-->', TRUE);
      $inFileContent = removeExcess($inFileContent, '<!--webbot', '-->', TRUE);
      $inFileContent = removeExcess($inFileContent, '<!--webbot', '-->', TRUE);
      $inFileContent = removeExcess($inFileContent, '<!--webbot', '-->', TRUE);
      $inFileContent = removeExcess($inFileContent, '<!--webbot', '-->', TRUE);
      $inFileContent = removeExcess($inFileContent, '<!--webbot', '-->', TRUE);
      $inFileContent = removeExcess($inFileContent, '<!--webbot', '-->', TRUE);
      $inFileContent = removeExcess($inFileContent, '<meta name="Description', '">', TRUE);
      $inFileContent = removeExcess($inFileContent, '<meta name="KEYWORDS"', '">', TRUE);
      $inFileContent = removeExcess($inFileContent, '<table border="1" width="100%">', '</table>', TRUE);
      //$inFileContent = removeExcess($inFileContent, '', '', TRUE);
      $inFileContent = removeExcess($inFileContent, '<table border="0" cellpadding="6" cellspacing="0" width="100%">', '</table>', TRUE);


      $inFileContent = removeExcess($inFileContent, '<p align="center"><strong><font color="#800000"><small>', '</p>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<p align="center"><small>For', '</p>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<p><font color="#cc0000" face="Times New Roman', '/p>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<p align="center"><a href="http://localhost/out/index.html"><img', '></p>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<p align="center"><strong><font
color="#800000"><small>©', '</p>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<!-- Pi', 'e -->', TRUE);
      $inFileContent = removeExcess($inFileContent, '<p><font color="#cc0000" face="Times', '</p>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<p align="left"><i>©', 'p>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<meta name="GENERATOR"', 'Document">', TRUE);
      $inFileContent = removeExcess($inFileContent, '<p align="center"><small>Hare Krishna!', '</table>', TRUE);
//$inFileContent = removeExcess($inFileContent, '', '', TRUE);
      $inFileContent = mb_str_replace('<p>&nbsp;</p>', '', $inFileContent);
      $inFileContent = mb_str_replace('<p align="left">&nbsp;</p>', '', $inFileContent);

      $inFileContent = removeExcess($inFileContent, '<table border="4" width="210"', '</table>', TRUE);
//$inFileContent = removeExcess($inFileContent, '', '</div>', TRUE);
      $inFileContent = mb_str_replace('<div align="right">
          
        </div>', '', $inFileContent);
      $inFileContent = mb_str_replace('<span style="font-size:11.0pt;
mso-bidi-font-size:10.0pt;font-family:Arial;mso-fareast-font-family:&quot;MS Mincho&quot;">', '<span>', $inFileContent);
      $inFileContent = removeExcess($inFileContent, '<font size="2">This', '</font>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<table border="0" width="100%"
bgcolor="#FFC8C8"', '</table>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<table border="0" width="100%"
bgcolor="#FFC8C8"', '</table>', TRUE);

      $inFileContent = removeExcess($inFileContent, '<table style="font-family: Arial, Helvetica; font-size: 8pt" bgcolor="#FFC8C8"', '</table>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<p><a href="http://www.chantandbehappy.com/">', '/p>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<table border="1" cellpadding="4" cellspacing="1" width="100%">', '</table>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<table border="0" width="100%"
bgcolor="#FFC8C8"', '</table>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<p><a
href="http://www.chantandbehappy.com/', '</p>', TRUE);

      $inFileContent = removeExcess($inFileContent, '<table border="1" width="100%"
cellspacing="1" cellpadding="4">
<tr>', '</table>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<table style="font-family: Arial, Helvetica; font-size: 8pt" bordercolordark="#C0C0C0" bgcolor="#FFC8C8" border="2" cellpadding="0" cellspacing="0" width="100%">', '</tbody></table>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<td width="100%">&nbsp;</td>
</tr>
</tbody>', '</table>', TRUE);

      $inFileContent = removeExcess($inFileContent, '<table style="font-family: Arial, Helvetica; font-size: 8pt" bordercolordark="#C0C0C0" bgcolor="#FFC8C8" border="2"', '</table>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<table border="2" width="100%"', '</table>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<p align="center"><a href="http://', '</a></p>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<p align="center"><a
href="http://', '</a></p>', TRUE);

      $inFileContent = mb_str_replace('<table bgcolor="#FFC8C8" border="0" cellpadding="0" cellspacing="0" width="100%">
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
</tbody></table>', '', $inFileContent);
//$inFileContent = mb_str_replace('', '', $inFileContent);
      $inFileContent = removeExcess($inFileContent, '<style ', '</style>', TRUE);
      $inFileContent = removeExcess($inFileContent, '<table border="1" width="100%" bgcolor="#FFC8C8">', '</table>', TRUE);
$inFileContent = mb_str_replace('<td width="100%">&nbsp;</td>', '', $inFileContent);

$inFileContent = removeExcess($inFileContent, '<td valign="top" width="15%" bgcolor="#FFC8C8" style="border-right: 1 solid #C0C0C0;', '</td>', TRUE);
$inFileContent = removeExcess($inFileContent, '<td valign="top" width="15%"
bgcolor="#FFC8C8"', '</td>', TRUE);
$inFileContent = removeExcess($inFileContent, '<td width="100%" bgcolor="#FFFFCE">', '</font></td>', TRUE);
$inFileContent = removeExcess($inFileContent, '<div align="right">', '</div>', TRUE);
$inFileContent = removeExcess($inFileContent, '<div align="right">', '</div>', TRUE);
$inFileContent = removeExcess($inFileContent, '<p align="left"><font color="#cc0000" face="Times New Roman, Georgia, Times" size="4"><b>Go to the &#147;<a href="http://', '</font></p>', TRUE);
$inFileContent = truncateText($inFileContent, '', '<p align="center"><strong><font');
$inFileContent = mb_str_replace('<p align="center"><a name="top"></a><small>Hare Krishna!', '', $inFileContent);





//$inFileContent = removeExcess($inFileContent, '', '</table>', TRUE);
      //


      $contentInArray = $LineByLine->stripping($inFileContent); //преобразуем содержимое файла в массив
      //echo "Содержимое файла по строкам в массиве:\n"; var_dump($contentInArray); echo "\n";
//===================== НАЧИНАЕМ РАЗБИРАТЬ КОНТЕНТ =================
//====================== НИЖЕ СОБИРАЕМ ФАЙЛ И ПИШЕМ ================
      $outFileContent = $LineByLine->assembling($contentInArray);  //возвращаем из массива в неформатированный текст
      //echo "Содержимое файла целиком:\n".$contentInFile."\n";
      //echo 'Текущий файл: ' . $currentFileName . "\n";
      //извлекаем из полного пути+файла имя файла. Пристыковываем выходную директорию и дерево
      $outFilePath = $outDir . "/" . $currentFileNameInsideDir;
      echo "Путь целевого файла " . $outFilePath . "\n";

      $targetFile = fopen($outFilePath, 'a') or die("can't open file");
      fwrite($targetFile, $outFileContent); //выводим в файл
      fclose($targetFile); //закрываем

      echo "-------------------------------------------------\n";
    }
    else {
      echo "Размер = 0!\n";
      //размер нулевой, проверяем, файл или директория
      $path->text = $currentFileNameFromRoot;
      $path->symbol = '/';
      $path->position = 'END';
      $isItDir = $path->checkingForSymbol();
      if ($isItDir == FALSE) {
        echo "Копируемый файл нулевой длины " . $currentFileNameFromRoot . "\n";
        //ЭТО ВСТАВКА, ДЛЯ СОЗДАНИЯ ПУСТЫХ ФАЙЛОВ.      
        $outFilePath = $outDir . "/" . $currentFileNameInsideDir; //извлекаем из полного пути+файла имя файла. Пристыковываем выходную директорию и дерево
        echo "Путь целевого файла " . $outFilePath . "\n";
        $targetFile = fopen($outFilePath, 'a') or die("can't open file"); //создаём, пусть будет?
        fclose($targetFile); //закрываем
        //конец вставки
        echo "-------------------------------------------------\n";
      }
      else {
        // если же директория
        echo 'Копируемая директория ' . $currentFileNameFromRoot . "\n";
        createDir($outDir . "/" . $currentFileNameInsideDir);
        createDir($mediaDir . "/" . $currentFileNameInsideDir);
        echo "-------------------------------------------------\n";
      }
    }
    //=====================ВЫШЕ ФАПЙЛЫ TXT ====================
  }
  else {
    //=======================НИЖЕ ФАЙЛЫ не TXT ====================
    //Если файл непустой 
    if ($sourceFiles[$i]['size'] > 0) {
      echo "Размер > 0!\n";
      //================ БЛОК РАЗБОРА ТИПОВ ФАЙЛОВ ===================
      //если без расширения, определить тип
      if ($extension == '') {
        $filetype = trim(shell_exec('/usr/bin/file -i ' . $sourceFiles[$i]['name'] . ' | /usr/bin/awk \'{print $2}\'')) . "\n";
        echo $sourceFiles[$i]['name'] . " FILETYPE: " . $filetype . "\n";
      }
      //если без имени, но с расширением
      if ($extension != '' && $filename == '') {
        echo "NONAME: " . $sourceFiles[$i]['name'] . "\n";
      }
      //=====================================

      $inFileContent = file_get_contents($currentFileNameFromRoot); // дёргаем контент целиком
      //echo "Содержимое файла целиком:\n".$contentInFile."\n";

      $mediaFileContent = $inFileContent;  // Файл со входа у нас попадает без обработки на выход
      //echo "Содержимое файла целиком:\n".$contentInFile."\n";
      //echo 'Текущий файл: ' . $currentFileName . "\n";
      //извлекаем из полного пути+файла имя файла. Пристыковываем выходную директорию и дерево
      $mediaFilePath = $mediaDir . "/" . $currentFileNameInsideDir;
      echo "Путь целевого файла " . $mediaFilePath . "\n";

      $targetFile = fopen($mediaFilePath, 'a') or die("can't open file");
      fwrite($targetFile, $mediaFileContent); //выводим в файл
      fclose($targetFile); //закрываем

      echo "-------------------------------------------------\n";
    }
    else {
      echo "Размер = 0!\n";
      //размер нулевой, проверяем, файл или директория
      $path->text = $currentFileNameFromRoot;
      $path->symbol = '/';
      $path->position = 'END';
      $isItDir = $path->checkingForSymbol();
      if ($isItDir == FALSE) {
        echo "Копируемый файл нулевой длины " . $currentFileNameFromRoot . "\n";
        //ЭТО ВСТАВКА, ДЛЯ СОЗДАНИЯ ПУСТЫХ ФАЙЛОВ.      
        $mediaFilePath = $mediaDir . "/" . $currentFileNameInsideDir; //извлекаем из полного пути+файла имя файла. Пристыковываем выходную директорию и дерево
        echo "Путь целевого файла " . $mediaFilePath . "\n";
        $targetFile = fopen($mediaFilePath, 'a') or die("can't open file"); //создаём, пусть будет?
        fclose($targetFile); //закрываем
        //конец вставки
        echo "-------------------------------------------------\n";
      }
      else {
        // если же директория
        echo 'Копируемая директория ' . $currentFileNameFromRoot . "\n";
        createDir($mediaDir . "/" . $currentFileNameInsideDir);
        createDir($outDir . "/" . $currentFileNameInsideDir);
        echo "-------------------------------------------------\n";
      }
    }
  }
  unset($currentFileNameFromRoot);  // на всякий случай прибиваем имя текущего файла.
}

