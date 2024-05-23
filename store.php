<?php
include "conf.php";
session_start();

  $login = "no";

  //
  if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true){
    $login = "yes";
  } else {
    $login = "no";
  }


$sql = "SELECT * FROM products WHERE catogary='laptops'";


$isChecked = isset($_POST['instock']) ? true : false;

if ($isChecked) {
    $sql .= " AND stock='yes'";
}

$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laptops</title>
    <link rel="stylesheet" href="storee.css" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />
    <script src="storee.js" defer></script>
    <style>
        .toggle.active div {
            transform: translateX(20px); /* Adjust the value as needed */
        }
    </style>
</head>
<body>
    <!-- <header>
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
    </header> -->

    <header>
      <span><span style="color: #6103d9;">Sonic</span><span style="font-size: 1.5rem;">Pulse</span></span>
      <nav>
        <ul>
          <li class="homebtn">
            <a href="homepage.php">
              <img
                src="svgs/9110875_home_icon.svg"
              />
            </a>
          </li>
          <li>
          <a class="a" href="store.php">Store</a>
          </li>
          <li class="gotoContact" onclick="scrollToContact()">
            Contact
          </li>
          <li onclick="scrollToAboutUs()">
            About Us
          </li>
          <li>
            <i style="color: white;" class="fa-solid fa-cart-shopping cartt"></i>
          </li>
          <?php
              if($login == "yes"){
            ?>
            <li class="profile-icon" onclick="showProfile()">
              <div><img src="svgs/5340287_man_people_person_user_users_icon.svg"></div>
            </li>
            <?php } else{ ?>
            <li>
              <?php if($login == "no"){ ?>
                <div class="a loginbtn" onclick="openLoginForm()">Log In</div>
              <?php } ?>
            </li>
            <?php } ?>
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
          <li class="open"><a href="store.php">Laptops</a></li>
          <li><a href="processors.php">Processors</a></li>
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

          <form action="store.php" method="post" id="instockToggleForm">
            <div class="in-stock-toggle">
              <label for="instock">
                <div class="toggle <?php echo $isChecked ? 'active' : ''; ?>">
                  <div></div>
                </div>
              </label>
              <input style="display: none;" type="checkbox" name="instock"  id="instock" <?php echo $isChecked ? 'checked' : ''; ?>>
              <input style="display: none;" type="submit" name="toggleSubmit">
              <span>IN STOCK</span>
            </div>
          </form>

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

    <script>
      const toggle = document.querySelector(".toggle");
      const toggleBall = toggle.querySelector("div");

      toggle.addEventListener("click", () => {
        toggleBall.classList.toggle("active");
      });

      // Submit form when checkbox is clicked
      document.getElementById("instock").addEventListener("click", function() {
        document.getElementById("instockToggleForm").submit();
      });
    </script>
</body>
</html>

<?php mysqli_close($con); ?>
