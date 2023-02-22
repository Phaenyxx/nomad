<?php
function handle_uploaded_image()
{
    $image_path = null;
    if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
        // Define the maximum image dimensions
        $max_width = 600;
        $max_height = 400;

        // Get the image file extension and create a new filename
        $image_info = getimagesize($_FILES['image']['tmp_name']);
        $image_extension = image_type_to_extension($image_info[2], false);
        $image_name = uniqid('image_');

        // Resize the image to fit within the maximum dimensions
        $img_src = null;
        switch ($image_info[2]) {
            case IMAGETYPE_JPEG:
                $img_src = imagecreatefromjpeg($_FILES['image']['tmp_name']);
                break;
            case IMAGETYPE_PNG:
                $img_src = imagecreatefrompng($_FILES['image']['tmp_name']);
                break;
            case IMAGETYPE_WEBP:
                $img_src = imagecreatefromwebp($_FILES['image']['tmp_name']);
                break;
        }
        if ($img_src) {
            $original_width = imagesx($img_src);
            $original_height = imagesy($img_src);
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
            $img = imagecreatetruecolor($new_width, $new_height);
            imagealphablending($img, false);
            $col = imagecolorallocatealpha($img, 0, 0, 0, 127);
            imagefilledrectangle($img, 0, 0, $new_width, $new_height, $col);
            imagealphablending($img, true);
            imagecopyresampled($img, $img_src, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
            imagedestroy($img_src);
            $image_path = '../../uploads/' . $image_name . '.png';
            imagesavealpha( $img, true );
            imagepng($img, $image_path);
            imagedestroy($img);
        }
    }
    return $image_name;
}
?>