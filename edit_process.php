<?php
include "conf.php";

if(isset($_POST["edit"])){
    $id = $_POST["id"];
    $name = $_POST["editName"];
    $qnt = $_POST["editQnt"];
    $price = $_POST["editPrice"];
    $stock = $_POST["editStock"];
    $description = $_POST["editDescription"];
    $offer = $_POST["editOffer"];
    $offerPrice = $_POST["editOfferPrice"];
    $newArrival = $_POST["newArrival"];


    if(!empty($_FILES["editImage"]["name"])){
        $fileName = basename($_FILES["editImage"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

        $allowTypes = array('jpg', 'png', 'jpeg', 'webp');
        if(in_array($fileType, $allowTypes)){
            $image = $_FILES['editImage']['tmp_name'];
            $imgContent = addslashes(file_get_contents($image));

            $update = "update products set pName='$name', qnt='$qnt', price='$price', stock='$stock', description='$description', offer='$offer', offerPrice='$offerPrice', newArrival='$newArrival', image='$imgContent' WHERE pId='$id'";
            $result = mysqli_query($con, $update);

            if($result){
                echo "Image updated successfully";
            } else {
                echo "Image update failed, please try again!";
            }
        } else {
            echo "Sorry, only JPG, JPEG & PNG files are allowed to upload!";
        }
    } else {
        $update = "update products set pName='$name', qnt='$qnt', price='$price', stock='$stock', description='$description', offer='$offer', offerPrice='$offerPrice', newArrival='$newArrival' WHERE pId='$id'";
        $result = mysqli_query($con, $update);
    }

    $sql = "SELECT pId from products where offer='no' and inOfferSection='yes'";
    $result = mysqli_query($con, $sql);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $pId = $row['pId'];

            $sql = "DELETE FROM offersection where pId='$pId'";
            mysqli_query($con, $sql);

            $sql = "UPDATE products SET inOfferSection='no' where pId='$pId'";
            mysqli_query($con, $sql);
        }
    }

    $sql = "SELECT pId from products where stock='no' and inNewArrival='yes'";
    $result = mysqli_query($con, $sql);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $pId = $row['pId'];

            $sql = "DELETE FROM newarrivalsection where pId='$pId'";
            mysqli_query($con, $sql);

            $sql = "UPDATE products SET inNewArrival='no' where pId='$pId'";
            mysqli_query($con, $sql);
        }
    }

    $sql = "SELECT pId from products where newArrival='no' and inNewArrival='yes'";
    $result = mysqli_query($con, $sql);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $pId = $row['pId'];

            $sql = "DELETE FROM newarrivalsection where pId='$pId'";
            mysqli_query($con, $sql);

            $sql = "UPDATE products SET inNewArrival='no' where pId='$pId'";
            mysqli_query($con, $sql);
        }
    }
}

if(isset($_POST["delete"])){
    $id = $_POST["deleteId"];

    $sql = "DELETE FROM products WHERE pId='$id'";
    mysqli_query($con, $sql);
    echo "Product deleted successfully";

    $sql = "SELECT pId from offersection where pId='$id'";
    $result = mysqli_query($con, $sql);
    if($result){
        $sql = "DELETE FROM offersection WHERE pId='$id'";
        mysqli_query($con, $sql);
    }

    $sql = "SELECT pId from newarrivalsection where pId='$id'";
    $result = mysqli_query($con, $sql);
    if($result){
        $sql = "DELETE FROM newarrivalsection WHERE pId='$id'";
        mysqli_query($con, $sql);
    }
}

mysqli_close($con);
?>
