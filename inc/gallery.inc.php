<?php
// define some common variables
$DIR='portfolio';
$TDIR='thumbs';
require_once('variables.php');

// define some common functions
function endsWith($haystack, $needle)
{
    return $needle === '' || substr($haystack, -strlen($needle)) === $needle;
}

function isJpg($fileName) {
    return endsWith($fileName, '.jpg') || endsWith($fileName, '.jpeg');
}

function stripFileExt($filename){
    return preg_replace("/\\.[^.\\s]+$/", '', $filename);
}

function listDir($startPath, $excludeList=array('.','..','.gitignore')) {
    // added array values to 0 base the index again
    return array_values(array_diff(scandir($startPath), $excludeList));
}

function getFirstJpgFile($path) {
    $firstFile = 'NOT_FOUND';
    $dir = opendir($path);
    while(($currentFile = readdir($dir)) !== false) {
        if (!isJpg($currentFile) ) {
            continue;
        }
        $firstFile = $currentFile;
        break;
    }
    closedir($dir);
    return $firstFile;
}

function makePath() {
    return implode('/', func_get_args());
}

function imagePath($album, $imageName) {
    global $DIR;
	return makePath($DIR, $album, $imageName);
}

function thumbPath($album, $imageName) {
    global $TDIR;
	return makePath($TDIR, $album, $imageName);
}

function checkAndCreateThumbnail($album, $imageName) {
    $thumbPath = thumbPath($album, $imageName);
    
    // make dir structure if needed
    $thumbDir = dirname($thumbPath);
    if (!file_exists($thumbDir)) {
        mkdir($thumbDir, 0755, true);
    }
	
    // create thumbnail if it doesn't exist
    if (!is_file($thumbPath)) {
	    $imagePath = imagePath($album, $imageName);
	    $img = new Imagick($imagePath);
        global $thumbSize;
	    $img->cropThumbnailImage($thumbSize, $thumbSize);
	    $img->writeImage($thumbPath);
	    $img->destroy();
	}
}
?>