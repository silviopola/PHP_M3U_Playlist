<?php
function replaceChars($string, $characters, $replacement) {
    foreach ($characters as $character) {
        $string = str_replace($character, $replacement, $string);
    }
    return $string;
}

function generateM3UFromFolder($folderPath)
{    
    $files = scandir($folderPath);

    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $filePath = $folderPath . '/' . $file;

        if (is_dir($filePath)) {
            $m3uContent .= generateM3UFromFolder($filePath);
        } else {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (in_array($extension, ['avi', 'ogg', 'mp4', 'mkv'])) {
                $title = str_replace("<Full Path of fs starting folder>", "", $filePath);
		$title = replaceChars($title, array(" ", ",", ":", ";", "|", "#"), "_");				
		$urlPath = str_replace("<Base fs starting folder>", "https://www.spltek.com", $filePath);
                
                $m3uContent .= "#EXTINF:-1,$title\n";
                $m3uContent .= "$urlPath\n";
            }
        }
    }

    return $m3uContent;
}

$folderPath = '<Starting Folder>';
$m3uContent = "#EXTM3U\n" . generateM3UFromFolder($folderPath);
/*
header('Content-Type: application/x-mpegurl');
header('Content-Disposition: attachment; filename="playlist.m3u"');
*/
echo $m3uContent;
