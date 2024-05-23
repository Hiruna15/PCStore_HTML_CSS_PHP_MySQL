<?php
    include "conf.php";

    if(isset($_POST["submit"])){
        if(!empty($_FILES["image"]["name"])){
            $fileName = basename($_FILES["image"]["name"]);
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

            $allowTypes = array('jpg', 'png', 'jpeg', 'webp');
            if(in_array($fileType, $allowTypes)){
                $image = $_FILES['image']['tmp_name'];
                $imgContent = addslashes(file_get_contents($image));

                $sql = "INSERT INTO testing (img) VALUES('$imgContent')";
                $result = mysqli_query($con, $sql);

                if($result){
                    echo "File uploaded successfully";
                }
                else{
                    echo "File upload failed, please try again!";
                }
            }
            else{
                echo "Sorry, only JPG, JPEG & PNG files are allowed to upload!";
            }
        }
        else{
            echo "Please select an image file to upload!";
        }
    }

    mysqli_close($con);
?>