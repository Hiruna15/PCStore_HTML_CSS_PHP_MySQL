<?php
    include "conf.php";

    if(isset($_POST["submit"])){
        $pName = $_POST["name"];
        $qnt = $_POST["qnt"];
        $price = $_POST["price"];
        $catogary = $_POST["catogary"];
        $stock = $_POST["stock"];
        $description = $_POST["description"];
        $offer = $_POST["offer"];
        $offerPrice = $_POST["offerPrice"];
        $newArrival = $_POST["newArrival"];

        if(!empty($_FILES["image"]["name"])){
            $fileName = basename($_FILES["image"]["name"]);
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

            $allowTypes = array('jpg', 'png', 'jpeg', 'webp');
            if(in_array($fileType, $allowTypes)){
                $image = $_FILES['image']['tmp_name'];
                $imgContent = addslashes(file_get_contents($image));

                $sql = "INSERT INTO products (pName, qnt, price, catogary, stock, description, offer, offerPrice, newArrival, image) VALUES('$pName', '$qnt', '$price', '$catogary', '$stock', '$description', '$offer', '$offerPrice', '$newArrival', '$imgContent')";
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

    if(isset($_POST["displayProduct"])){
        echo "Display data";
    }

    mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin</title>
    <link rel="stylesheet" href="adminn.css" />
    <script src="admin.js" defer></script>
  </head>
  <body>
    <div class="container">
    <form
      action="admin.php"
      method="post"
      enctype="multipart/form-data"
      name="addProductForm"
    >
      <label for="name">product name</label>
      <input type="text" name="name" />

      <label for="qnt">quantity</label>
      <input type="number" name="qnt" />

      <label for="price">price</label>
      <input type="text" name="price" />

      <label for="catagory">catogary</label>
      <select name="catogary">
        <option value="laptops">laptops</option>
        <option value="processors">processors</option>
        <option value="motherboards">motherboards</option>
        <option value="ram">ram</option>
        <option value="gpu">gpu</option>
        <option value="storage">storage</option>
        <option value="casings">casings</option>
        <option value="power-supplies">power-supplies</option>
        <option value="monitors">monitors</option>
      </select>

      <label for="stock">stock status</label>
      <select name="stock">
        <option value="yes">IN-STOCK</option>
        <option value="no">OUT-OF-STOCK</option>
      </select>

      <label for="description">description</label>
      <textarea name="description" rows="4" cols="50"></textarea>

      <label for="offer">offer</label>
      <select name="offer" onchange="setOfferPriceAvailability(event)">
        <option value="yes">yes</option>
        <option value="no">no</option>
      </select>

      <label for="offerPrice">offer price</label>
      <input type="text" name="offerPrice" value="0" id="offerPrice"/>

      <label for="newArrival">new arrival</label>
      <select name="newArrival">
        <option value="yes">yes</option>
        <option value="no">no</option>
      </select>

      <label for="image">image</label>
      <input type="file" name="image" />

      <input type="submit" name="submit" value="Add">
    </form>
    </div>

    <!--  -->
    <div class="sidebar">
  <div class="logo-details">
    <i class="fas fa-cloud"></i>
    <span class="logo-name">SonicPulse</span>
  </div>
  <ul class="nav-links">
    <li>
      <a href="admin.php" class="active">
        <i class="fas fa-th-large"></i>
        <span class="link-name">ADD PRODUCTS</span>
      </a>
    </li>
    <li>
      <a href="edit.php">
        <i class="fas fa-th-large"></i>
        <span class="link-name">EDIT PRODUCT</span>
      </a>
    </li>
    <li>
      <a href="offerSection.php">
        <i class="fas fa-box-open"></i>
        <span class="link-name">Edit offer section</span>
      </a>
    </li>
    <li>
      <a href="newArrivalSection.php">
        <i class="fas fa-chart-line"></i>
        <span class="link-name">Edit new arrival section</span>
      </a>
    </li>
    <li>
      <a href="heroProductSection.php">
        <i class="fas fa-address-book"></i>
        <span class="link-name">Edit hero section</span>
      </a>
    </li>
    <li>
      <a href="feedbackSection.php">
        <i class="fas fa-cog"></i>
        <span class="link-name">Edit feedback section</span>
      </a>
    </li>
  </ul>
</div>

    <!--  -->

    <script>
      const offerPriceEl = document.getElementById("offerPrice");

      function setOfferPriceAvailability(e){
        if(e.target.value == "no"){
          offerPriceEl.value = 0;
          offerPriceEl.readOnly = true;
        }
        else{
          offerPriceEl.readOnly = false;
        }
      }
    </script>
  </body>
</html>