<?php 
  session_start();
  include "conf.php";
  $showAddedToCartMsg = false;

  // if (isset($_SESSION['newUserId']) && !empty($_SESSION['newUserId'])) {
  //   $newUserId = $_SESSION['newUserId'];
  // } else {
  //   $newUserId = "no";
  // }

  $login = "no";

  //

  if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true){
    $newUserId = $_SESSION['newUserId'];
    $login = "yes";
  } else {
    $newUserId = "no";
    $login = "no";
  }
  
  // $login = "no";
  $useradded = "no";
  
  if(isset($_POST["signup"])){
    $username = $_POST["names"];
    $email = $_POST["email"];
    $pwd = $_POST["pwds"];
    $pwdConfirm = $_POST["pwdsconferm"];
    $productId = $_POST["pid"];

    if($pwd == $pwdConfirm){
      $sql = "INSERT INTO user(email, password, userName) values('$email', '$pwd', '$username')";
      mysqli_query($con, $sql);
      $useradded = "yes";
    }
    else{
      $useradded = "mismatch";
    }

    $query = "SELECT * FROM products WHERE pId = $productId";
    $result = mysqli_query($con, $query);
    if($result){
      $row = mysqli_fetch_assoc($result);
      $name = $row["pName"];
      $qnt = $row["qnt"];
      $price = $row["price"];
      $catogary = $row["catogary"];
      $stock = $row["stock"];
      $description = $row["description"];
      $productDetails = explode(',,', $description);
      $offer = $row["offer"];
      $offerPrice = $row["offerPrice"];
      $newArrival = $row["newArrival"];
      $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';
    }
  }
  else if(isset($_POST["login"])){
    $usernamee = $_POST["namel"];
    $password = $_POST["pwdl"];
    $productId = $_POST["pid"];

    $sql = "SELECT * from user WHERE userName='$usernamee' AND password='$password'";
    $user = mysqli_query($con, $sql);
    if(mysqli_num_rows($user) > 0){
      $login = "yes";
      while ($row = mysqli_fetch_assoc($user)){
        $userID = $row['userId'];
        $_SESSION["newUserId"] = $userID;
        $newUserId = $_SESSION["newUserId"];
      }
    }
    else{
      $login = "mismatch";
    }

    $query = "SELECT * FROM products WHERE pId = $productId";
    $result = mysqli_query($con, $query);
    if($result){
      $row = mysqli_fetch_assoc($result);
      $name = $row["pName"];
      $qnt = $row["qnt"];
      $price = $row["price"];
      $catogary = $row["catogary"];
      $stock = $row["stock"];
      $description = $row["description"];
      $productDetails = explode(',,', $description);
      $offer = $row["offer"];
      $offerPrice = $row["offerPrice"];
      $newArrival = $row["newArrival"];
      $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';
    }
  }
  else if(!isset($_POST["add-to-cart2"]) && !isset($_POST["cartItemClose"]) && !isset($_POST["login"]) && !isset($_POST["signup"])){
    $productId = $_GET['id'];

    $query = "SELECT * FROM products WHERE pId = $productId";
    $result = mysqli_query($con, $query);
    if($result){
      $row = mysqli_fetch_assoc($result);
      $name = $row["pName"];
      $qnt = $row["qnt"];
      $price = $row["price"];
      $catogary = $row["catogary"];
      $stock = $row["stock"];
      $description = $row["description"];
      $productDetails = explode(',,', $description);
      $offer = $row["offer"];
      $offerPrice = $row["offerPrice"];
      $newArrival = $row["newArrival"];
      $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';
    }
  }
  else if(isset($_POST["add-to-cart2"])){
    $productId = $_POST['id-qnt-section'];
    $quantity = $_POST['quantity'];

    $query = "SELECT * FROM products WHERE pId = $productId";
    $result = mysqli_query($con, $query);
    if($result){
      $row = mysqli_fetch_assoc($result);
      $name = $row["pName"];
      $qnt = $row["qnt"];
      $price = $row["price"];
      $catogary = $row["catogary"];
      $stock = $row["stock"];
      $description = $row["description"];
      $productDetails = explode(',,', $description);
      $offer = $row["offer"];
      $offerPrice = $row["offerPrice"];
      $newArrival = $row["newArrival"];
      $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';
    }

    $checkQuery = "SELECT * FROM cart WHERE id = $productId and userID='$newUserId'";
    $checkResult = mysqli_query($con, $checkQuery);
    if(mysqli_num_rows($checkResult) > 0) {
        $totalPrice = $quantity * $price;
        $updateQuery = "UPDATE cart SET qnt = $quantity, totalPrice = $totalPrice WHERE id = $productId and userID='$newUserId'";
        mysqli_query($con, $updateQuery);
    } else {
        $totalPrice = $quantity * $price;
        $insertQuery = "INSERT INTO cart (pName, priceEach, qnt, id, stock, totalPrice, userID) VALUES ('$name', '$price', '$quantity', '$productId', '$stock', '$totalPrice', '$newUserId')";
        mysqli_query($con, $insertQuery);
    }
    $showAddedToCartMsg = true;
  }
  else if(isset($_POST["cartItemClose"])){
    // $productId = $_POST['cartDeleteId'];
    $productId = $_POST['loadedPId'];

    $LoadedPId = $_POST['loadedPId'];
    $query = "SELECT * FROM products WHERE pId = $LoadedPId";
    $result = mysqli_query($con, $query);
    if($result){
      $row = mysqli_fetch_assoc($result);
      $name = $row["pName"];
      $qnt = $row["qnt"];
      $price = $row["price"];
      $catogary = $row["catogary"];
      $stock = $row["stock"];
      $description = $row["description"];
      $productDetails = explode(',,', $description);
      $offer = $row["offer"];
      $offerPrice = $row["offerPrice"];
      $newArrival = $row["newArrival"];
      $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';
    }

    $productIdToDelete = $_POST['cartDeleteId'];
    $deleteQuery = "DELETE FROM cart WHERE id = $productIdToDelete and userID='$newUserId'";
    mysqli_query($con, $deleteQuery);

    $renderCart = true;
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product</title>
    <link rel="stylesheet" href="producttt.css" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
      />
  </head>
  <body>
    <!-- <header>
      <span>LOGO</span>
      <nav>
        <ul>
          <li>
            <a href="#">
              <img
                src="svgs/1976053_home_home page_homepage_homepages_icon.svg"
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
            <?php if($newUserId == "no"){ ?>
              <div onclick="openLoginForm()">Log In</div>
            <?php } ?>
          </li>
          <li>
            <i style="color: white;" class="fa-solid fa-cart-shopping cartt" onclick="<?php if($newUserId == "no"){echo "openLoginForm()";} else{echo "showCart()";} ?>"></i>
          </li>
        </ul>
      </nav>
    </header> -->

    <!--  -->
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
              <i style="color: white;" class="fa-solid fa-cart-shopping cartt" onclick="<?php if($newUserId == "no"){echo "openLoginForm()";} else{echo "showCart()";} ?>"></i>
            </li>
            <?php
              if($login == "yes"){
            ?>
            <li class="profile-icon" onclick="showProfile()">
              <div><img src="svgs/5340287_man_people_person_user_users_icon.svg"></div>
            </li>
            <?php } else{ ?>
            <li>
              <?php if($newUserId == "no"){ ?>
                <div class="a loginbtn" onclick="openLoginForm()">Log In</div>
              <?php } ?>
            </li>
            <?php } ?>
          </ul>
        </nav>
      </header>
    <!--  -->

    <div class="main-container">
      <?php if($showAddedToCartMsg){ ?>
        <div class="addedToCartMsg">
          <div onclick="closeAddeditemMsg()">X</div>
          <span>Item added to the cart</span>
        </div>
      <?php } ?>
      <div class="product-list">
        <ul>
          <li><a href="#">Laptops</a></li>
          <li><a href="#">Processors</a></li>
          <li><a href="#">Motherboards</a></li>
          <li><a href="#">Memory(RAM)</a></li>
          <li><a href="#">Graphic Cards</a></li>
          <li><a href="#">Storage</a></li>
          <li><a href="#">Casings</a></li>
          <li><a href="#">Power Supplies</a></li>
          <li><a href="#">Moniters</a></li>
        </ul>
      </div>

      <div class="product">
        <div class="top">
          <h1><?php echo $name ?></h1>
          <span><?php echo $catogary ?></span>
        </div>
        <div class="bottom">
          <div class="left">
            <img class="left-arrow" src="./svgs/211617_b_left_arrow_icon.svg" />
            <img
              class="right-arrow"
              src="./svgs/211620_b_right_arrow_icon.svg"
            />
            <div class="image-slider">
              <div>
                <?php echo $image ?>
                <img
                  src="images/2071-20231031122022-LOQ_15IRH8_CT1_03 (1).png"
                />
                <img
                  src="images/2071-20231031122022-LOQ_15IRH8_CT1_03 (1).png"
                />
                <img
                  src="images/2071-20231031122022-LOQ_15IRH8_CT1_03 (1).png"
                />
              </div>
            </div>
            <div>
              <div><span><?php 
                if($stock == "yes"){
                  echo "IN-STOCK";
                }
                else{
                  echo "OUT-OF-STOCK";
                }
              ?></span></div>
              <div><span><?php
                echo "2 YEARS WARRENTY";
                // if($warrenty == 1){
                //   echo $warrenty."YEAR WARRENTY";
                // }
                // else if($warrenty > 0){
                //   echo $warrenty."YEARS WARRENTY";
                // }
                // else{
                //   echo "NO WARRENTY";
                // }
              ?></span></div>
            </div>
          </div>
          <div class="right">
            <div>
              <div><span>NEW ARRIVAL</span></div>
              <div><span>OFFER</span></div>
            </div>
            <div class="product-details">
              <?php
                foreach ($productDetails as $detail) {
              ?>
                <span><?php echo $detail ?></span>
              <?php } ?>

              <?php
                if($offer == "yes"){
              ?>
                <span class="price"><?php echo $offerPrice." LKR" ?></span>
                <span class="old-price"><?php echo $price." LKR" ?></span>
              <?php } else{?>
                <span class="price"><?php echo $price." LKR" ?></span>
              <?php } ?>
            </div>
            <!-- <a class="purcherse" href="#">PURCHERSE</a> -->
            <?php
              if($newUserId == "no"){
            ?>
              <button class="add-to-cart" onclick="openLoginForm()">ADD TO CART</button>
            <?php } else { ?>
              <button class="add-to-cart" onclick="showQntContainer()">ADD TO CART</button>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>


    <div class="<?php
      if($renderCart){
        echo 'cart-container';
      }
      else{
        echo 'cart-container cart-container-hide';
      }
    ?>">
      <div class="cart">
          <div class="cart-top">
            <span>YOUR PRODUCTS</span>
            <div class="cart-close">X</div>
          </div>
          <div class="cart-items">
            <?php 
              $sql = "SELECT * FROM cart where userID='$newUserId'";
              $result = mysqli_query($con, $sql);
              $cartTotal = 0;
              if($result){
                while($row = mysqli_fetch_assoc($result)){
                  $pId = $row["id"];
                  $cartPName = $row["pName"];
                  $pEach = $row["priceEach"];
                  $tPrice = $row["totalPrice"];
                  $cartQnt = $row["qnt"];
                  $cartStock = $row["stock"];
                  $sql = "SELECT image FROM products where pId='$pId'";
                  $row = mysqli_fetch_assoc(mysqli_query($con, $sql));
                  $cartPImage = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';

                  $cartTotal += $tPrice;
            ?>
              <div class="cart-item">
                <div class="image">
                  <?php echo $cartPImage ?>
                </div>
                <a href="#"><?php echo $cartPName ?></a>
                <div class="stock"><span><?php if($cartStock == 'yes'){echo "IN-STOCK";} else{"OUT OF STOCK";} ?></span></div>
                <div class="qnt">
                  <button>-</button>
                  <input class="qntFieldCart" type="text" value="<?php echo $cartQnt ?>" readonly/>
                  <button>+</button>
                </div>
                <div class="pricee">
                  <span>EACH</span>
                  <span><?php echo $pEach . " LKR" ?></span>
                </div>
                <div class="pricee">
                  <span>TOTAL</span>
                  <span><?php echo $tPrice . " LKR" ?></span>
                </div>
                <form action="product.php" method="post">
                  <input type="text" name="loadedPId" style="display: none;" readonly value="<?php echo $productId ?>">
                  <input type="text" name="cartDeleteId" readonly value="<?php echo $pId ?>" style="display: none;">
                  <button class="cart-item-close" name="cartItemClose">X</button>
                </form>
              </div>
            <?php
                }
              }
            ?>
          </div>

          <div class="cart-bottom">
            <div class="payment-options">
              <span>PAYMENT OPTIONS</span>
              <div>
                <img src="./svgs/paypal.svg" />
                <img src="./svgs/visa.svg" />
                <img src="./svgs/master-card.svg" />
              </div>
            </div>
            <div class="buy-now">
              <div><span>TOTAL: <?php echo $cartTotal . " LKR" ?></span></div>

              <form action="payment.php" method="post">
                <input type="text" name="cartTotal" style="display: none;" readonly value="<?php echo $cartTotal ?>">
                <button name="buy">Buy now</button>
              </form>
            </div>
          </div>
        </div>
    </div>

    <form action="product.php" method="post">
      <?php
        $query = "SELECT qnt from cart where id='$productId' and userID='$newUserId'";
        $result = mysqli_query($con, $query);
        $storedQnt = 1;
        if(mysqli_num_rows($result) > 0){
          $row = mysqli_fetch_assoc($result);
          $storedQnt = $row['qnt'];
        }
      ?>
      <div class="qnt-container qnt-container-hide">
        <div class="add-qnt-section">
            <div class="qnt-top">
              <div class="details">
                <img src="./images/2071-20231031122022-LOQ_15IRH8_CT1_03 (1).png">
                <div>
                  <span class="qnt-s-price">170,000 LKR</span>
                  <span class="qnt-s-offer">170,000 LKR</span>
                  <div><span>IN STOCK</span></div>
                </div>
              </div>
              <div class="add-qnt">
                <div onclick="handleDecrease()"><img src="./svgs/9057024_math_minus_icon (1).svg"></div>
                <input class="qntField" type="text" value="<?php echo $storedQnt ?>" name="quantity" readonly>
                <div onclick="handleIncrease(<?php echo $qnt ?>)"><img src="./svgs/211877_plus_round_icon.svg"></div>
              </div>
              <span class="message"></span>
            </div>
            <div class="qnt-bottom">
              <button id="add-to-cart2" name="add-to-cart2">Add To Cart</button>
              <div id="close">Close</div>
            </div>
          </div>
      </div>
      <input type="text" value="<?php echo $productId ?>" name="id-qnt-section" style="display: none;">
    </form>


    <div class="login-form-container <?php if($useradded == "mismatch" || $login == "mismatch") { echo "show"; } ?>">
      
      <form action="product.php" method="post" class="login-form">
        <div class="loginCloseBtn" onclick="closeLoginForm()">X</div>
        <div class="form-left">
          <div class="form-left-content <?php if($useradded == "mismatch"){echo "hide";} ?>">
            <span>USER LOGIN</span>
            <div class="login-options">
              <div class="login-option"><i class="fa-brands fa-facebook"></i></div>
              <div class="login-option"><i class="fa-brands fa-google-plus-g"></i></div>
            </div>
            <span>or use your username password</span>
            <div class="up-field">
              <img src="./svgs/11185794_user_person_profile_avatar_people_icon.svg">
              <input type="text" placeholder="USERNAME" name="namel">
            </div>
            <div class="up-field">
              <img src="./svgs/8201356_lock_password_padlock_privacy_ui_icon.svg">
              <input type="password" placeholder="PASSWORD" name="pwdl">
            </div>
            <?php if($login == "mismatch"){?>
              <span style="color: red;">Incorrect login details!</span>
            <?php } ?>
            <a href="">Forgot Your Password?</a>
            <input type="text" name="pid" style="display: none;" value="<?php echo $productId ?>">
            <button name="login" class="loginBtn">SIGN IN</button>
          </div>

          <div class="form-left-content-sign-up <?php if($useradded == "mismatch"){echo "show";} ?>">
            <span>Create Account</span>
            <div class="login-options">
              <div class="login-option"><i class="fa-brands fa-facebook"></i></div>
              <div class="login-option"><i class="fa-brands fa-google-plus-g"></i></div>
            </div>
            <span>or use your email for registeration</span>
            <div class="up-field">
              <img src="./svgs/11185794_user_person_profile_avatar_people_icon.svg">
              <input type="text" placeholder="USERNAME" name="names">
            </div>
            <div class="up-field">
              <img src="svgs/8155538_email_mail_message_talk_envelope_icon.svg">
              <input type="text" placeholder="EMAIL" name="email">
            </div>
            <div class="up-field">
              <img src="./svgs/8201356_lock_password_padlock_privacy_ui_icon.svg">
              <input type="password" placeholder="PASSWORD" name="pwds">
            </div>
            <div class="up-field">
              <img src="./svgs/8201356_lock_password_padlock_privacy_ui_icon.svg">
              <input type="password" placeholder="CONFIRM PASSWORD" name="pwdsconferm">
            </div>
            <?php if($useradded == "mismatch"){ ?>
              <span>confirm password falied!</span>
            <?php } ?>
            <input type="text" name="pid" style="display: none;" value="<?php echo $productId ?>">
            <button name="signup" class="signupBtn">SIGN UP</button>
          </div>
        </div>

        <div class="form-right">
          <div class="form-right-sign-up <?php if($useradded == "mismatch"){echo "hide";} ?>">
            <span>Don't have an account?</span>
            <p>Register with your personal details to use all of site features</p>
            <div class="fr-signup" onclick="displaySignUpForm()">SIGN UP</div>
          </div>

          <div class="form-right-sign-in <?php if($useradded == "mismatch"){echo "show";} ?>">
            <p>Enter your personal details to use all of site features</p>
            <div class="fr-signin" onclick="displaySignInForm()">SIGN IN</div>
          </div>
        </div>

        <div class="image-darker"></div>
      </form>
      </div>

    <script>
      //const addToCartBtn = document.querySelector(".add-to-cart");
      const cartCloseBtn = document.querySelector(".cart-close");
      const cartContainer = document.querySelector(".cart-container");

      const qntContainer = document.querySelector(".qnt-container");
      const addToCartBtn2 = document.getElementById("add-to-cart2");
      const qntSectionCloseBtn = document.getElementById("close");

      const qntFieldEl = document.querySelector(".qntField");
      const messageEl = document.querySelector(".message");

      const closeAddedItemMsgEl = document.querySelector(".addedToCartMsg");

      // addToCartBtn.addEventListener("click", () => {
      //   qntContainer.classList.remove("qnt-container-hide");
      // })

      function closeAddeditemMsg(){
        closeAddedItemMsgEl.classList.add("hide");
      }

      function showQntContainer(){
        qntContainer.classList.remove("qnt-container-hide");
      }

      addToCartBtn2.addEventListener("click", () => {
        qntContainer.classList.add("qnt-container-hide");
      })

      qntSectionCloseBtn.addEventListener("click", () => {
        qntContainer.classList.add("qnt-container-hide");
      })

      cartCloseBtn.addEventListener("click", () => {
        cartContainer.classList.add("cart-container-hide");
      })

      function handleDecrease(){
        messageEl.textContent = " ";
        if(qntFieldEl.value > 1){
          qntFieldEl.value --;
        }
      }

      function handleIncrease(qnt){
        if(qntFieldEl.value < qnt){
          qntFieldEl.value ++;
        }
        else{
          messageEl.textContent = "Only " + qnt + " items available";
        }
      }

      function showCart(){
        cartContainer.classList.remove("cart-container-hide");
      }

      // login form
      const formLeftContent = document.querySelector(".form-left-content");
      const formLeftContentSignUp = document.querySelector(".form-left-content-sign-up");

      const formRightSignIn = document.querySelector(".form-right-sign-in");
      const formRightSignUp = document.querySelector(".form-right-sign-up");

      const loginFormContainer = document.querySelector(".login-form-container");

      function displaySignUpForm(){
        formLeftContent.classList.add("hide");
        formLeftContentSignUp.classList.add("show");

        formRightSignIn.classList.add("show");
        formRightSignUp.classList.add("hide");
      }

      function displaySignInForm(){
        formLeftContent.classList.remove("hide");
        formLeftContentSignUp.classList.remove("show");

        formRightSignIn.classList.remove("show");
        formRightSignUp.classList.remove("hide");
      }

      function openLoginForm(){
        loginFormContainer.classList.add("show");
      }

      function closeLoginForm(){
        loginFormContainer.classList.remove("show");
      }
    </script>
  </body>
</html>
