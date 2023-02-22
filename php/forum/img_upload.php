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
        $image_resource = null;
        switch ($image_info[2]) {
            case IMAGETYPE_JPEG:
                $image_resource = imagecreatefromjpeg($_FILES['image']['tmp_name']);
                break;
            case IMAGETYPE_PNG:
                $image_resource = imagecreatefrompng($_FILES['image']['tmp_name']);
                break;
            case IMAGETYPE_WEBP:
                $image_resource = imagecreatefromwebp($_FILES['image']['tmp_name']);
                break;
        }
        if ($image_resource) {
            $original_width = imagesx($image_resource);
            $original_height = imagesy($image_resource);
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
            $resized_image_resource = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($resized_image_resource, $image_resource, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
            imagedestroy($image_resource);
            $image_path = '../../uploads/' . $image_name . '.png';
            imagepng($resized_image_resource, $image_path);
            imagedestroy($resized_image_resource);
        }
    }
    return $image_name;
}
?>