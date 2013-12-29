<?php
$pageName = 'Album: ' . $_GET['album'];
include_once 'header.php';
include_once 'gallery.inc.php';

// makes sure the get variable is actually an album
$albumArray = listDir($DIR);
$album = $_GET['album'];
if (!in_array($album, $albumArray)) {
    die('Album not valid.');
}
?>
        <div id="album" class="container">
        <div class="grid 1of1 center remove-padding album-title"><?php echo $album; ?></div>
<?php
// traverse album dir
$imageArray = listDir(makePath($DIR, $album));
foreach ($imageArray as $imageName) {
    // skip non-jpg files
    if (!isJpg($imageName)) {
        continue;
    }
	checkAndCreateThumbnail($album, $imageName);
    echo '
        <div class="album-image grid 1of4">
          <a href="', imagePath($album, $imageName), '" data-lightbox="', $album, '" title="', stripFileExt($imageName), '">
            <img src="', thumbPath($album, $imageName), '" width="', $thumbSize, '" height="', $thumbSize, '" alt="', stripFileExt($imageName), '"/>
          </a>
        </div>', "\n";
}
echo '        </div>
        <script src="js/jquery-1.10.2.min.js"></script>
        <script src="js/lightbox-2.6.min.js"></script>';
include_once 'footer.php';
?>