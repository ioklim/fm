<?php
$zip = new ZipArchive;
if ($zip->open('29821_1_all1.b5.zip') === TRUE) {
    $zip->extractTo('./');
    $zip->close();
    echo 'ok';
} else {
    echo 'failed';
}
?>
