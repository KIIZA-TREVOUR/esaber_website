<?php
// include('connection.php'); 
function share_file($name, $destination, $resize = false, $height = 0, $width = 0) {
    // global $wallet, $sqlConnect;
    $ext = pathinfo($_FILES[$name]['name'], PATHINFO_EXTENSION);
    $filename = md5(time() . $_FILES[$name]['name']) . '.' . $ext;
    $file = $_FILES[$name]['tmp_name'];
    $allowedTypes = array('image/bmp', 'image/jpeg', 'image/x-png', 'image/png', 'image/gif');

    // Get the file type to determine if it's an image or a document
    $fileType = $_FILES[$name]['type'];

    // Check if the uploaded file is an image
    if (in_array($fileType, $allowedTypes)) {
        // Image processing (resize if requested)
        if ($resize) {
            $source_properties = getimagesize($file);
            if ($source_properties === false) {
                return 'Error: Invalid image file'; // Return error if the file is not a valid image
            }

            $image_type = $source_properties[2];
            if ($image_type == IMAGETYPE_JPEG) {
                $image_resource_id = imagecreatefromjpeg($file);
            } elseif ($image_type == IMAGETYPE_GIF) {
                $image_resource_id = imagecreatefromgif($file);
            } elseif ($image_type == IMAGETYPE_PNG) {
                $image_resource_id = imagecreatefrompng($file);
            } else {
                return 'Error: Unsupported image type'; // Handle unsupported image types
            }

            // Ensure image resource was created successfully
            if ($image_resource_id === false) {
                return 'Error: Unable to create image resource';
            }

            $target_layer = imagecreatetruecolor($width, $height);
            imagecopyresampled($target_layer, $image_resource_id, 0, 0, 0, 0, $width, $height, $source_properties[0], $source_properties[1]);
            imagejpeg($target_layer, $destination . $filename);
            return $destination . $filename;
        } else {
            // If resizing is not requested, just move the uploaded file
            if (move_uploaded_file($_FILES[$name]['tmp_name'], $destination . $filename)) {
                return $destination . $filename;
            }
        }
    } else {
        // If the file is not an image (i.e., it's a document), skip the image processing
        if (move_uploaded_file($_FILES[$name]['tmp_name'], $destination . $filename)) {
            return $destination . $filename;
        } else {
            return 'Error: File upload failed';
        }
    }

    return ''; // Return empty if all else fails
}

function addNews($conn, $title, $description, $image){
    $query = "insert into news (title, description, image) values (?,?,?)";
    $stmt = mysqli_prepare($conn, $query);
    if($stmt){
        mysqli_stmt_bind_param($stmt, "sss", $title, $description, $image);
        if(mysqli_stmt_execute($stmt)){
            return true;
        } else{
            return false;
        }
    }
}

function getNews($conn){
    $query ="select * from news";
    $stmt = mysqli_query($conn, $query);
    $news = [];
    if($stmt) {
        while($row = mysqli_fetch_assoc($stmt)){
            $news[] = $row;
        }
        return $news;
    }
}

function deleteNews($conn, $id){
    $query = "delete from news where id=?";
    $stmt = mysqli_prepare($conn, $query);
    if($stmt){
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        return true;
    }
}
function addHero($conn, $title, $subtitle, $description, $image){
    $query = "insert into home_slider (title, subtitle, description, image) values (?,?,?,?)";
    $stmt = mysqli_prepare($conn, $query);
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "ssss", $title, $subtitle, $description, $image);
        if(mysqli_stmt_execute($stmt)){
            return true;
        } else {
            return false;
        }
    }

}

function getSettingsById($conn, $id) {
    $query = "select * from settings where id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $results = mysqli_stmt_get_result($stmt);
        if($results) {
            return mysqli_fetch_assoc($results);
        }

    } else {
        return false;
    }
}

function updateSettings($conn, $id, $phone, $address, $email, $image, $desc) {
    $query = "update settings set contact=?, address=?, email=?, image=?, description=? where id=?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssi", $phone, $address, $email, $image, $desc, $id);
        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
    }
}


function getHeros($conn) {
    $query ="select * from home_slider";
    $stmt =mysqli_query($conn, $query);
    $heros = [];
    while($row= mysqli_fetch_assoc($stmt)){
        $heros[] = $row;
    }
    return $heros;
}

?>