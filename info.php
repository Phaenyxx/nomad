<?php
if (extension_loaded('imagick')) {
    echo 'Imagick is installed and available on this server.';
} else {
    echo 'Imagick is not installed or not available on this server.';
}

phpinfo();
?>