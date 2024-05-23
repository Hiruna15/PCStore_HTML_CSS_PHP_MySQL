<?php 
  include "conf.php";
  session_start();
  $sql = "SELECT * FROM products WHERE catogary='processors'";
  $result = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laptops</title>
    <link rel="stylesheet" href="store.css" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
      />
    <script src="storee.js" defer></script>
  </head>
  <body>
    <header>
      <span>LOGO</span>
      <nav>
        <ul>
          <li class="homebtn">
            <a href="#">
              <img
                src="svgs/9110875_home_icon.svg"
              />
            </a>
          </li>
          <li>
            <a href="#">Store</a>
          </li>
          <li>
            <a href="#">Contact</a>
          </li>
          <li>
            <a href="#">About US</a>
          </li>
          <li>
            <a href="#">Log In</a>
          </li>
          <li>
            <i class="fa-solid fa-cart-shopping cart"></i>
          </li>
        </ul>
      </nav>
    </header>

    <?php 
      $sql = "SELECT * FROM offersection";
      $offers = mysqli_query($con, $sql);
      if($offers){
    ?>
      <div class="offer-section">
        <div class="offer-tag">
          <h2>New Offers</h2>
          <img src="svgs/352507_local_offer_icon.svg" />
        </div>
        <div class="offers">
          <div class="offer-slider">
            <?php
              while($row = mysqli_fetch_assoc($offers)){
                $id = $row["pId"];
                $name = $row["pName"];
                $price = $row["price"];
                $offerPrice = $row["offerPrice"];
                $sql = "SELECT image from products where pId='$id'";
                $row = mysqli_fetch_assoc(mysqli_query($con, $sql));
                $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';
            ?>
              <div class="offer-item" id="<?php echo $id ?>">
                <?php echo $image ?>
                <div>
                  <h1><?php echo $name ?></h1>
                  <span><?php echo $price." LKR" ?></span>
                  <span class="price"><?php echo $offerPrice." LKR" ?></span>
                  <a href="#">GET OFFER</a>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
        <img class="slide-btn" src="svgs/352468_arrow_right_icon.svg" />
        <img class="slide-btn-reverce" src="svgs/352468_arrow_right_icon.svg" />
      </div>
    <?php } ?>

    <div class="product-container">
      <div class="product-list">
        <ul>
          <li><a href="store.php">Laptops</a></li>
          <li class="open"><a href="processors.php">Processors</a></li>
          <li><a href="motherboards.php">Motherboards</a></li>
          <li><a href="memory.php">Memory(RAM)</a></li>
          <li><a href="gpu.php">Graphic Cards</a></li>
          <li><a href="storage.php">Storage</a></li>
          <li><a href="casings.php">Casings</a></li>
          <li><a href="ps.php">Power Supplies</a></li>
          <li><a href="moniters.php">Moniters</a></li>
        </ul>
      </div>
      <div class="products">
        <div class="top">
          <div class="in-stock-toggle">
            <div>
              <div></div>
            </div>
            <span>IN STOCK</span>
          </div>
          <div class="search-bar">
            <input type="text" />
            <div><img src="svgs/2703065_search_find_icon (1).svg" /></div>
          </div>
        </div>
        <div class="bottom">
          <?php 
            if($result){
              while($row = mysqli_fetch_assoc($result)){
                $Id = $row['pId'];
                $name = $row['pName'];
                $price = $row['price'];
                $stock = $row['stock'];
                $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';
          ?>

            <a href="product.php?id=<?php echo $Id ?>">
              <div class="item">
                  <div class="image">
                    <?php echo $image ?>
                    <div class="plus">
                      <img src="svgs/134224_add_plus_new_icon.svg" />
                    </div>
                  </div>
                  <div class="details">
                    <p><?php echo $name ?></p>
                    <p><?php echo $price."LKR" ?></p>
                  </div>
                  <?php
                    if($stock == "yes"){
                      echo '<div class="stock-status instock">IN-STOCK</div>';
                    }
                    else{
                      echo '<div class="stock-status outofstock">OUT-OF-STOCK</div>';
                    }
                  ?>
                </div>
            </a>
          <?php }}?>
        </div>
      </div>
    </div>
  </body>
</html>
<?php mysqli_close($con); ?>