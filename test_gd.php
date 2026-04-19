<?php
if (extension_loaded('gd')) {
    echo "GD extension is loaded.<br>";
    echo "GD Version: " . gd_info()['GD Version'];
} else {
    echo "GD extension is NOT loaded. Enable it in php.ini";
}
?>