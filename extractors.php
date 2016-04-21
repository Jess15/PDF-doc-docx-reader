<?php
class DocxConversion
{
    private $filename;

    public function __construct($filePath)
    {
        $this->filename = $filePath;
    }

    // ############################## DOC FILE #######################################

    private function read_doc () {
        $fileHandle = fopen($this->filename, 'r'); // open file  as read only
        $line = @fread($fileHandle, filesize($this->filename)); // read file secure binary mode, and get its size
        $lines = explode(chr(0x0D), $line); // separates the file in lines (0x0D = \n)
        $outtext = "";

        foreach ($lines as $thisline) {
//            $pos = strpos($thisline, chr(0x00)); // finds invalid or null character
//
//            if (($pos !== FALSE)||(strlen($thisline) == 0)) {
//
//            } else {
            $outtext .= $thisline . "";
//            }
//        }

            $outtext = preg_replace(chr(0x20), chr(0x00), $outtext); // replace spaces (0x20) for null (0x00)

            return $outtext;
        }
    }

    // ############################## DOCX FILE #######################################

    private function read_docx () {
        $stripped_content = "";
        $content = "";
        $zip = zip_open($this->filename);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {
            if (zip_entry_open($zip,$zip_entry) == FALSE) continue;
            if (zip_entry_name($zip_entry) != "word/document.xml") continue;
            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            zip_entry_close($zip_entry);
        }

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', "", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $stripped_content = strip_tags($content);

        return $stripped_content;
    }
}


