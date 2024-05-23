<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="edit.js" defer></script>
    <link rel="stylesheet" href="edit.css" />
</head>
<body>
    <script>
        function handleEditt() {
            var selectedRow = event.target.closest('tr');
            var cells = selectedRow.getElementsByTagName('td');

            var id = cells[0].textContent;
            var name = cells[1].textContent;
            var quantity = cells[2].textContent;
            var price = cells[3].textContent;
            var category = cells[4].textContent;
            var stock = cells[5].textContent;
            var description = cells[6].textContent;
            var offer = cells[7].textContent;
            var offerPrice = cells[8].textContent;

            var image = cells[9].textContent;

            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editQnt').value = quantity;
            document.getElementById('editPrice').value = price;
            document.getElementById('editStock').value = stock;
            document.getElementById('editDescription').value = description;
            document.getElementById('editOffer').value = offer;
            document.getElementById('editOfferPrice').value = offerPrice;

            //document.getElementById('editForm').style.display = 'block';
        }

        function handleDelete(){
            var selectedRow = event.target.closest('tr');
            var cells = selectedRow.getElementsByTagName('td');
            var id = cells[0].textContent;
            var name = cells[1].textContent;

            document.getElementById('deleteId').value = id;
            document.getElementById('deleteName').value = name;
        }

        function setOfferPriceAvailability(e){
            const offerPriceEl = document.getElementById("editOfferPrice");

            if(e.target.value == "no"){
                offerPriceEl.value = 0;
                offerPriceEl.disabled = true;
            }
            else{
                offerPriceEl.disabled = false;
            }
        }
    </script>

    <?php
        include "conf.php";
        $sql = "SELECT * FROM products";
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
                <th>Description</th>
                <th>Offer</th>
                <th>Offer Price</th>
                <th>new arrival</th>
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
                    $description = $row["description"];
                    $offer = $row["offer"];
                    $offerPrice = $row["offerPrice"];
                    $newArrival = $row["newArrival"];
                    $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';
            ?>
                <tr> 
                    <td><?php echo $id ?></td> 
                    <td><?php echo $pName ?></td> 
                    <td><?php echo $qnt ?></td> 
                    <td><?php echo $price ?></td>
                    <td><?php echo $category ?></td> 
                    <td><?php echo $stock ?></td> 
                    <!-- <td class="description"><?php echo $description ?></td>  -->
                    <td class="description">
                      <div style="overflow: auto; max-height: 100px;">
                        <?php echo $description ?>
                      </div>
                    </td> 
                    <td><?php echo $offer ?></td> 
                    <td><?php echo $offerPrice ?></td>
                    <td><?php echo $newArrival ?></td>
                    <td><?php echo $image ?></td>
                    <td><button onclick="handleEditt()">EDIT</button> <button onclick="handleDelete()">DELETE</button></td>
                </tr>
            <?php } ?>
        </table>

        <form id="editForm" action="edit_process.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="editId">
            <label for="editName">Name:</label>
            <input type="text" name="editName" id="editName">

            <label for="editQnt">Quantity:</label>
            <input type="number" name="editQnt" id="editQnt">

            <label for="editPrice">Price:</label>
            <input type="text" name="editPrice" id="editPrice">

            <label for="editStock">Stock:</label>
            <select name="editStock" id="editStock">
                <option value="yes">IN-STOCK</option>
                <option value="no">OUT-OF-STOCK</option>
            </select>

            <label for="editDescription">Description:</label>
            <textarea name="editDescription" rows="4" cols="50" id="editDescription"></textarea>

            <label for="editOffer">Offer:</label>
            <select name="editOffer" id="editOffer" onchange="setOfferPriceAvailability(event)">
                <option value="yes">yes</option>
                <option value="no">no</option>
            </select>

            <label for="editOfferPrice">Offer Price:</label>
            <input type="text" name="editOfferPrice" id="editOfferPrice">

            <label for="newArrival">New Arrival:</label>
            <select name="newArrival">
                <option value="yes">yes</option>
                <option value="no">no</option>
            </select>

            <label for="editImage">Image:</label>
            <input type="file" name="editImage" id="editImage"/>
            
            <button type="submit" name="edit">Save Changes</button>
        </form>

        <form action="edit_process.php" name="deleteForm" enctype="multipart/form-data" method="post" id="deleteForm">
            <label for="id">Product ID</label>
            <input type="text" name="deleteId" id="deleteId" readonly>

            <label for="pName">Product Name</label>
            <input type="text" name="deleteName" id="deleteName" readonly>

            <button type="submit" name="delete">DELETE PRODUCT</button>
        </form>
    <?php } 
        mysqli_close($con);
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
      <a href="edit.php" class="active">
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

</body>
</html>
