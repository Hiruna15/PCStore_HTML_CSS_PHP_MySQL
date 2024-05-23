<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="heroProduct.css" />
</head>
<body>
    <?php
        include "conf.php";

        if(isset($_POST["addHeroProduct"])){
            // Retrieve details of the clicked row
            $pid = $_POST["id"];
            $name = $_POST["name"];
            $price = $_POST["price"];
            $qnt = $_POST["qnt"];
            $category = $_POST["category"];
            $stock = $_POST["stock"];
            $image = $_POST["image"];

            // Insert the clicked row's details into the hero products table
            $sql = "INSERT INTO heroproducts (pId, pName, price, qnt, category, stock, image) values('$pid', '$name', '$price', '$qnt', '$category', '$stock', '$image')";
            mysqli_query($con, $sql);
            
            // Update the 'inHeroSection' flag in the products table
            $sql = "UPDATE products set inHeroSection='yes' where pId='$pid'";
            mysqli_query($con, $sql);

            echo "Product added to the hero section";
        }

        $sql = "SELECT * FROM products WHERE inHeroSection='no'";
        $result = mysqli_query($con, $sql);
        if(mysqli_num_rows($result) > 0) {
    ?>
        <table> 
            <tr>
                <th>ID</th> 
                <th>Name</th> 
                <th>Quantity</th> 
                <th>Price</th>
                <th>Category</th>
                <th>Stock</th>
                <th>Image</th>
                <th class="hide"></th>
            </tr>
            <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row["pId"];
                    $pName = $row["pName"];
                    $qnt = $row["qnt"];
                    $price = $row["price"];
                    $category = $row["catogary"];
                    $stock = $row["stock"];
                    $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';
            ?>
                <form action="heroProductSection.php" method="POST">
                    <tr> 
                        <td><?php echo $id ?></td> 
                        <td><?php echo $pName ?></td> 
                        <td><?php echo $qnt ?></td> 
                        <td><?php echo $price ?></td>
                        <td><?php echo $category ?></td> 
                        <td><?php echo $stock ?></td> 
                        <td><?php echo $image ?></td>
                        <td>
                            <input type="hidden" name="id" value="<?php echo $id ?>">
                            <input type="hidden" name="name" value="<?php echo $pName ?>">
                            <input type="hidden" name="price" value="<?php echo $price ?>">
                            <input type="hidden" name="qnt" value="<?php echo $qnt ?>">
                            <input type="hidden" name="category" value="<?php echo $category ?>">
                            <input type="hidden" name="stock" value="<?php echo $stock ?>">
                            <input type="hidden" name="image" value='<?php echo $image ?>'>
                            <input type="submit" name="addHeroProduct" value="Add to hero products" />
                        </td>
                    </tr>
                </form>
            <?php } ?>
        </table>
    <?php } ?>

    <?php 
        if(isset($_POST["heroProductRemove"])){
            $pId = $_POST["pId"];

            $sql = "DELETE FROM heroproducts WHERE pId='$pId'";
            mysqli_query($con, $sql);
            
            $sql = "UPDATE products set inHeroSection='no' where pId='$pId'";
            mysqli_query($con, $sql);
            
            echo "Product removed from hero section";
        }

        $sql = "SELECT * FROM heroproducts";
        $result = mysqli_query($con, $sql);
        if(mysqli_num_rows($result) > 0) {
    ?>
        <table> 
            <tr> 
                <th>ID</th> 
                <th>Name</th> 
                <th>Quantity</th> 
                <th>Price</th>
                <th>Category</th>
                <th>Stock</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row["pId"];
                    $pName = $row["pName"];
                    $price = $row["price"];
                    $qnt = $row["qnt"];
                    $category = $row["category"];
                    $stock = $row["stock"];

                    $sql = "SELECT image FROM products where pId='$id'";
                    $img = mysqli_query($con, $sql);
                    $row_img = mysqli_fetch_assoc($img);
                    $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row_img['image']) . '" />';
            ?>
                <tr> 
                    <td><?php echo $id ?></td> 
                    <td><?php echo $pName ?></td> 
                    <td><?php echo $qnt ?></td> 
                    <td><?php echo $price ?></td>
                    <td><?php echo $category ?></td>
                    <td><?php echo $stock ?></td>
                    <td><?php echo $image ?></td>
                    <td>
                        <!-- Form for each row with hidden input field for product ID -->
                        <form action="heroProductSection.php" method="POST">
                            <input type="hidden" name="pId" value="<?php echo $id ?>">
                            <input type="submit" name="heroProductRemove" value="Remove">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>


    <div class="sidebar">
  <div class="logo-details">
    <i class="fas fa-cloud"></i>
    <span class="logo-name">SonicPulse</span>
  </div>
  <ul class="nav-links">
    <li>
      <a href="admin.php">
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
      <a href="heroProductSection.php" class="active">
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
</body>
</html>
