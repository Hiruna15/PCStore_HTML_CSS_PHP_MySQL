<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>offer section</title>
    <link rel="stylesheet" href="newArrivvalSection.css" />
</head>
<body>
    <script>
        function handleAddNewArrivals(){
            var selectedRow = event.target.closest('tr');
            var cells = selectedRow.getElementsByTagName('td');

            var id = cells[0].textContent;
            var name = cells[1].textContent;
            var price = cells[3].textContent;
            var offer = cells[5].textContent;
            var offerPrice = cells[6].textContent;

            document.getElementById('id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('price').value = price;
            document.getElementById('offer').value = offer;
            document.getElementById('offerPrice').value = offerPrice;
            
        }

        function handleRemove(){
            var selectedRow = event.target.closest('tr');
            var cells = selectedRow.getElementsByTagName('td');

            var id = cells[0].textContent;
            var name = cells[1].textContent;

            document.getElementById('deleteId').value = id;
            document.getElementById('deleteName').value = name;
        }
    </script>

    <?php
        include "conf.php";
        $sql = "SELECT * FROM products WHERE stock='yes' and inNewArrival='no' and newArrival='yes'";
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
                <th>Offer</th>
                <th>Offer Price</th>
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
                    $offer = $row["offer"];
                    $offerPrice = $row["offerPrice"];
                    $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';
            ?>
                <tr> 
                    <td><?php echo $id ?></td> 
                    <td><?php echo $pName ?></td> 
                    <td><?php echo $qnt ?></td> 
                    <td><?php echo $price ?></td>
                    <td><?php echo $category ?></td>
                    <td><?php echo $offer ?></td>
                    <td><?php echo $offerPrice ?></td>
                    <td><?php echo $image ?></td>
                    <td><button type="submit" name="add_newarrivals" onclick="handleAddNewArrivals()">Add to new arrivals</button></td>
                </tr>
            <?php } ?>
        </table>
    <?php } 
        if(isset($_POST["add"])){
            $sql = "SELECT * FROM products where inNewArrival='yes'";
            $result = mysqli_query($con, $sql);
            $row_count = mysqli_num_rows($result);
            if($row_count < 10){
                $pid = $_POST["id"];
                $name = $_POST["name"];
                $price = $_POST["price"];
                $offer = $_POST["offer"];
                $offerPrice = $_POST["offerPrice"];

                $sql = "INSERT INTO newarrivalsection (pId, pName, price, offer, offerPrice) values('$pid', '$name', '$price', '$offer', '$offerPrice')";
                mysqli_query($con, $sql);
                echo "product added to the new arrival section";

                $sql = "UPDATE products set inNewArrival='yes' where pId='$pid'";
                mysqli_query($con, $sql);
            }
            else{
                echo "new arrival section can have maximum of 10 products!";
            }
        }

        // mysqli_close($con); 
    ?>

    <form action="newArrivalSection.php" method="post">
        <label for="id">ID</label>
        <input type="text" name="id" id="id" readonly>

        <label for="name">Name</label>
        <input type="text" name="name" id="name" readonly>

        <label for="price">Price</label>
        <input type="text" name="price" id="price" readonly>

        <label for="offer">Offer</label>
        <input type="text" name="offer" id="offer" readonly>

        <label for="offerPrice">Offer Price</label>
        <input type="text" name="offerPrice" id="offerPrice" readonly>

        <button type="submit" name="add">add</button>
    </form>

    <?php 
        $sql = "SELECT * FROM newarrivalsection";
        $result = mysqli_query($con, $sql);
        if(mysqli_num_rows($result) > 0) {
    ?>
        <table> 
            <tr> 
                <th>ID</th> 
                <th>Name</th> 
                <th>Price</th>
                <th>Offer</th> 
                <th>Offer Price</th>
                <th>Image</th>
                <th class="hide"></th>
            </tr>
            <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row["pId"];
                    $pName = $row["pName"];
                    $price = $row["price"];
                    $offer = $row["offer"];
                    $offerprice = $row["offerPrice"];

                    $sql = "SELECT image FROM products where pId='$id'";
                    $img = mysqli_query($con, $sql);
                    $row = mysqli_fetch_assoc($img);
                    $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';
            ?>
                <tr> 
                    <td><?php echo $id ?></td> 
                    <td><?php echo $pName ?></td> 
                    <td><?php echo $price ?></td>
                    <td><?php echo $offer ?></td>
                    <td><?php echo $offerprice ?></td>
                    <td><?php echo $image ?></td>
                    <td><button onclick="handleRemove()">Remove</button></td>
                </tr>
            <?php } ?>
        </table>

        <form action="newArrivalSection.php" method="post">
            <label for="deleteId">id</label>
            <input type="text" readonly name="deleteId" id="deleteId">

            <label for="deleteName">name</label>
            <input type="text" readonly name="deleteName" id="deleteName">

            <button type="submit" name="remove" id="remove">Remove</button>
        </form>
    <?php }
        if(isset($_POST["remove"])){
            $removeId = $_POST["deleteId"];

            $sql = "DELETE FROM newarrivalsection WHERE pId='$removeId'";
            mysqli_query($con, $sql);
            echo "Product removed from offer section";

            $sql = "UPDATE products set inNewArrival='no' where pId='$removeId'";
            mysqli_query($con, $sql);
        }
    ?>

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
      <a href="newArrivalSection.php" class="active">
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
</body>
</html>