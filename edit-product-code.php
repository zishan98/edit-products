if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $update_name = mysqli_real_escape_string($con, $_POST['name']);
    $update_designation = mysqli_real_escape_string($con, $_POST['designation']);
    $about = mysqli_real_escape_string($con, $_POST['about']);
    // $speaker_image = basename($_FILES['speaker_image']['name']);
    
    
    $old_img = $_POST['old_img'];
    
    $max_size = 500 * 1024; // 500KB
    
    if (isset($_FILES['speaker_image']) && $_FILES['speaker_image']['size'] > 0) {
        $website_logo = uniqid() . '_' . basename($_FILES['speaker_image']['name']);
        
        if (file_exists("../speaker-image/".$old_img)) {
            unlink("../speaker-image/".$old_img);
        }
        
        if ($_FILES["speaker_image"]["size"] <= $max_size) {
            list($width, $height) = getimagesize($_FILES["speaker_image"]["tmp_name"]);
            
            if ($width <= 555 && $height <= 555) {
                move_uploaded_file($_FILES["speaker_image"]["tmp_name"], '../speaker-image/' . $website_logo);
                
                $update = "UPDATE `speaker` SET `name`='$update_name',`about`='$about',`designation`='$update_designation',
                `image`='$website_logo' WHERE id='$id'";
                $update_run = mysqli_query($con, $update);

                if($update_run){

                    $_SESSION['message'] ="Data Updated Successfully..!";
                    header("location:../speaker-edit.php?id=".$id);
                    exit();
                 }else{
                    $_SESSION['error'] ="Something went wrong!";
                    header("location:../speaker-edit.php?id=".$id);
                    exit();                    
                 }
            } else {
                $_SESSION['error'] = "Sorry, the image dimensions should be 555x555 pixels or less.";
                header("location:../speaker-edit.php?id=".$id);
                exit();
            }
        } else {
            $_SESSION['error'] = "Sorry, your file is too large. Please upload an image of size 500KB or less.";
            header("location:../speaker-edit.php?id=".$id);
            exit();
        }
    } else {
        // If no new image is uploaded, use the existing image name
        $website_logo = $old_img;
        $update = "UPDATE `speaker` SET `name`='$update_name',`about`='$about',`designation`='$update_designation',
        `image`='$website_logo' WHERE id='$id'";
        $update_run = mysqli_query($con, $update);

        if ($update_run) {
            $_SESSION['message'] = "Data Updated Successfully..!";
            header("location:../speaker-edit.php?id=".$id);
            exit();
        } else {
            $_SESSION['error'] = "Something went wrong!";
            header("location:../speaker-edit.php?id=".$id);
            exit();
        }
    }
}
