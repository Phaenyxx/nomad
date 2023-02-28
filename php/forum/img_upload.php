<?php
function handle_uploaded_image()
{
    if (!isset($_FILES['image']) || empty($_FILES['image']['name'])) {
        return null;
    }

    $image_name = uniqid('image_');
    $uploads_dir = realpath(__DIR__ . '/../../uploads');
    $image_path = $uploads_dir . '/' . $image_name . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

    $max_width = 1200;
    $max_height = 800;

    $img = new Imagick();
    $img->readImageBlob(file_get_contents($_FILES['image']['tmp_name']));

    $original_width = $img->getImageWidth();
    $original_height = $img->getImageHeight();
    $new_width = $original_width;
    $new_height = $original_height;

    if ($original_width > $max_width) {
        $new_width = $max_width;
        $new_height = $new_width * $original_height / $original_width;
    }

    if ($new_height > $max_height) {
        $new_height = $max_height;
        $new_width = $new_height * $original_width / $original_height;
    }

    $img->resizeImage($new_width, $new_height, Imagick::FILTER_LANCZOS, 1, true);
    $img->setImageBackgroundColor('transparent');
    $img->extentImage($new_width, $new_height, 0, 0);

    if ($img->getImageFormat() == 'GIF') {
        $img = $img->coalesceImages();
        $img->writeImages($image_path, true);
    } else {
        $img->setImageFormat('png');
        $img->writeImage($image_path);
    }

    $img->destroy();

    return $image_name;
}

?>