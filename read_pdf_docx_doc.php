<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');

// ############################## PDF PDF ############################ (install xpdfbin)

$pdfname = "../uploads/testpdf.pdf";
$pdfcontent = shell_exec('/usr/local/bin/pdftotext '.$pdfname.' -');  // get the pdf content

// clear
$pdfcontent = preg_replace('/\s+/', '', $pdfcontent);
$striped_content = trim($pdfcontent);                                    // delete all whitespaces
$pdflen = strlen($pdfcontent);                                           // get the text length

//for ($i = 0; $i<$pdflen; $i++){
//  echo $pdfcontent[$i].".";                                         // print characters individually separates for a dot
//}

echo $pdfcontent;
echo $pdflen;

// ############################## DOCX DOCX ############################

$docxname = "../uploads/testx.docx";

$striped_content = '';
$docxcontent = '';

if (!$docxname || !file_exists($docxname)) return false;

$zip = zip_open($docxname);                                         // open file
if (!$zip || is_numeric($zip)) return false;

while ($zip_entry = zip_read($zip)){                                // read the next entry of file
  if (zip_entry_open($zip, $zip_entry) == FALSE) continue;          // try to open an entry of directory for read

  if (zip_entry_name($zip_entry) != "word/document.xml") continue;  // return the name of the entry of a directory

  $docxcontent .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));  // read from an entry of directory opened

  zip_entry_close($zip_entry);
}

zip_close($zip);

// clear
$docxcontent = str_replace('</w:r></w:p></w:tc><w:tc>', "", $docxcontent);
$docxcontent = str_replace('</w:r></w:p>', "", $docxcontent);
$docxcontent = preg_replace('/\s+/', '', $docxcontent);
$striped_content = strip_tags($docxcontent);
$striped_content = trim($striped_content);
$docxlen = strlen($striped_content);

echo $striped_content;
echo $docxlen;

// ############################## DOC DOC ############################ (install catdoc)

$docname = "../uploads/test.doc";
$doccontent = shell_exec("catdoc ".$docname);             // read file

// clear
$doccontent = preg_replace('/\s+/', '', $doccontent);
$striped_content = strip_tags($doccontent);
$striped_content = trim($striped_content);
$doclen = strlen($striped_content);

echo $doccontent;
echo $doclen;
