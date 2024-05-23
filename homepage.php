<?php 
  session_start();
  $newUserId = "no";
  include "conf.php";

  $sql = "SELECT * FROM newarrivalsection";
  $result = mysqli_query($con, $sql);

  $useradded = "no";
  $login = "no";

  //
  if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true){
    $login = "yes";
  } else {
    $login = "no";
  }
  //

  if(isset($_POST["logout"])){
    session_destroy();
    header("Location: homepage.php");
    exit();
  }

  if(isset($_POST["signup"])){
    $username = $_POST["names"];
    $email = $_POST["email"];
    $pwd = $_POST["pwds"];
    $pwdConfirm = $_POST["pwdsconferm"];

    if($pwd == $pwdConfirm){
      $sql = "INSERT INTO user(email, password, userName) values('$email', '$pwd', '$username')";
      mysqli_query($con, $sql);
      $useradded = "yes";
    }
    else{
      $useradded = "mismatch";
    }
  }

  if(isset($_POST["login"])){
    $usernamee = $_POST["namel"];
    $password = $_POST["pwdl"];

    $sql = "SELECT * from user WHERE userName='$usernamee' AND password='$password'";
    $user = mysqli_query($con, $sql);
    if(mysqli_num_rows($user) > 0){
      $login = "yes";
      while ($row = mysqli_fetch_assoc($user)){
        $userID = $row['userId'];
        $username = $row['userName'];
        $_SESSION["newUserId"] = $userID;
        $_SESSION["loggedIn"] = true;
      }

      //
      header("Location: homepage.php");
      exit();
      //
    }
    else{
      $login = "mismatch";
    }
  }

  if(isset($_POST['editProfileSubmit'])) {
    $username = $_POST['username'];
    $image = $_FILES['image']['tmp_name'];
    
    $id = $_SESSION['newUserId'];
    $updateUsernameQuery = "UPDATE user SET userName='$username' WHERE userId='$id'";
    mysqli_query($con, $updateUsernameQuery);
    

    if($image != "") {
      $imageData = addslashes(file_get_contents($image));
      $updateImageQuery = "UPDATE user SET image='$imageData' WHERE userId='$id'";
      mysqli_query($con, $updateImageQuery);
    }

    header("Location: homepage.php");
    exit();
  }

  if(isset($_POST['feedbackSubmit'])){
    $message = $_POST['message'];
    $userID = $_SESSION["newUserId"];
    $sql = "SELECT userName from user where userId='$userID'";
    $r = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_assoc($r)){
      $username = $row['userName'];
    }
    $sql = "INSERT INTO feedbacks(userId, username, message) values('$userID', '$username', '$message')";
    mysqli_query($con, $sql);
  }

  // 
  if(isset($_POST["cartItemClose"])){
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
  // 
?>
  <!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Home</title>
      <link rel="stylesheet" href="homepage.css" />
      <script src="homepagee.js" defer></script>
      <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
      />
    </head>
    <body>
      <header>
        <span><span style="color: #6103d9;">Sonic</span><span style="font-size: 1.5rem;">Pulse</span></span>
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
            <a class="a" href="store.php">Store</a>
            </li>
            <li class="gotoContact" onclick="scrollToContact()">
              Contact
            </li>
            <li onclick="scrollToAboutUs()">
              About Us
            </li>
            <li>
              <i style="color: white;" class="fa-solid fa-cart-shopping cartt" onclick="<?php if( $login == "no"){echo "openLoginForm()";} else{echo "showCart()";} ?>"></i>
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

      <div class="hero">
        <img class="heroArrowDown" src="svgs/scroll-down.svg" onclick="scrollToNewArrival()">
        <div class="menubar">
          <div class="left">
            <div class="top"></div>
            <div class="bottom"></div>
          </div>
          <div class="right">
            <ul>
              <a href="store.php"><li>LAPTOPS</li></a> |
              <a href="processors.php"><li>PROCESSORS</li></a> |
              <a href="motherboards.php"><li>MOTHERBOARDS</li></a> |
              <a href="memory.php"><li>MEMORY</li></a> |
              <a href="gpu.php"><li>GPUS</li></a> |
              <a href="store.php"><li>STORAGE</li></a> |
              <a href="casings.php"><li>CASINGS</li></a> |
              <a href="ps.php"><li>POWER SUPPLIES</li></a> |
              <a href="moniters.php"><li>MONITERS</li></a>
            </ul>
          </div>
        </div>
        <div class="hero-child">
          <div class="hero-text">
            <h1><span>Power</span> Up Your Rig</h1>
            <p>
              Discover a World of Top-Quality Computer Components and Laptops at
              SonicPulce Upgrade Your PC with the Best Components and Find
              Feature-Packed Laptops for Ultimate Performance! Unleash the Power
              of Cutting-Edge Technology and Elevate Your Computing Experience
              with Us.
            </p>
            <a href="#">Build Your Pc</a>
          </div>
          <div class="hero-products">
              <div class="hero-product-slider">
                <?php 
                  $sql = "SELECT * FROM heroproducts";
                  $result2 = mysqli_query($con, $sql);
                  while($row = mysqli_fetch_assoc($result2)){
                    $Id = $row['pId'];
                    $name = $row['pName'];
                    $sql = "SELECT image from products WHERE pId='$Id'";
                    $row = mysqli_fetch_assoc(mysqli_query($con, $sql));
                    $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';             
                ?>

                    <?php echo $image ?>
                <?php } ?>
              </div>
              <div class="heropDetailsContainer">
                <?php 
                  mysqli_data_seek($result2, 0);
                  while($row = mysqli_fetch_assoc($result2)){
                    $Id = $row['pId'];
                    $name = $row['pName'];
                ?>

                  <p><?php echo $name ?></p>

                <?php } ?>
              </div>
              <div class="heropButton">
                <img src="svgs/211621_c_right_arrow_icon.svg">
              </div>
          </div>
        </div>

        <div class="hero-background hero-child">
          <img src="images/e1584dc072c3506ee9b809b566e5e242.png">
        </div>

        <form action="homepage.php" method="post" class="profile">
          <div class="image">
            <?php
              if($login == "yes"){
                $id = $_SESSION["newUserId"];
                $sql = "SELECT userName, image FROM user where userId='$id'";
                $userResult = mysqli_query($con, $sql);
                while($row = mysqli_fetch_assoc($userResult)){
                  $username = $row["userName"];
                  $image = $row["image"];
                }
            ?>
              <?php if($image != null){ ?>
                <div><img src="data:image/jpeg;base64,<?php echo base64_encode($image); ?>" /></div>
              <?php } else{ ?>
                <div></div>
              <?php } ?>
              <span><?php echo $username ?></span>
            <?php } ?>
          </div>
          <button name="logout"><img src="svgs/7124045_logout_icon.svg">Log Out</button>
          <div class="profile-settings" onclick="openProfileEditForm()"><i class="fa-solid fa-gear"></i></div>
        </form>
      </div>

      <div class="new-arrivals" id="newArrival">
        <img class="scroll-btn" src="./svgs/352468_arrow_right_icon.svg">
        <img class="scroll-btn-left" src="./svgs/4781844_arrow_back_chevron_direction_left_icon.svg">
        <div class="top">
          <?php 
            if($result){
          ?>
            <div class="new-arrival-icon">
              <img
                src="svgs/6791549_box_new product_package_product_products_icon.svg"
              />
              <span>New Arrivals</span>
            </div>
          <?php } ?>
          <div class="search-bar">
            <input type="text" />
            <div><img src="svgs/2703065_search_find_icon (1).svg" /></div>
          </div>
        </div>

        <?php
          if($result){
        ?>
          <div class="items">
            <?php 
              while($row = mysqli_fetch_assoc($result)){
                $Id = $row['pId'];
                $name = $row['pName'];
                $price = $row['price'];
                $offer = $row['offer'];
                $offerPrice = $row['offerPrice'];
                $sql = "SELECT image from products WHERE pId='$Id'";
                $row = mysqli_fetch_assoc(mysqli_query($con, $sql));
                $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';
            ?>
              <div class="item">
                <div class="image">
                  <?php echo $image?>
                  <div class="plus">
                    <img src="svgs/134224_add_plus_new_icon.svg" />
                  </div>
                </div>
                <div class="details">
                  <p><?php echo $name ?></p>
                  <p><?php echo $price."LKR" ?></p>
                </div>
              </div>
            <?php } ?>
          </div>
        <?php } ?>
      </div>

      <div class="features-section">
        <h2>Get the best service from us</h2>
        <div class="features">
          <div>
            <img src="svgs/delivery-fast-svgrepo-com (2).svg" />
            <span>Free and Fast Delivery</span>
            <div class="round"></div>
            <div class="des">Enjoy the convenience of Free and Fast Delivery when you shop with us. We're committed to getting your orders to you swiftly and without any additional cost</div>
          </div>
          <div>
            <img src="svgs/trust-14873 (1).svg" />
            <span>Warranty and Support</span>
            <div class="round"></div>
            <div class="des">we stand behind the quality of our products and are committed to providing exceptional customer service. Our Warranty and Support program ensures that you have peace of mind long after your purchase</div>
          </div>
          <div>
            <img
              src="svgs/money-back-business-warranty-satisfaction-marketing-guaranted-svgrepo-com.svg"
            />
            <span>Hassle-Free Returns</span>
            <div class="round"></div>
            <div class="des">we understand that sometimes things don't go as planned. That's why we offer a Hassle-Free Returns policy to ensure your satisfaction with every purchase</div>
          </div>
          <div>
            <img
              src="images/onlineOrder.png"
            />
            <span>Online Ordering</span>
            <div class="round"></div>
            <div class="des">Browse, customize, and order your tech needs hassle-free! Enjoy quick delivery or pickup options. Get your gadgets with a click!</div>
          </div>
        </div>
        <!-- <span>We are partners of</span> -->
        <div class="partners">
          <img class="small" src="svgs/download.svg" />
          <img class="small" src="svgs/download (1).svg" />
          <img src="svgs/download (2).svg" />
          <img src="svgs/download (3).svg" />
          <img src="svgs/download (4).svg" />
        </div>
      </div>

      <?php 
        $sql = "SELECT id,username,message,image from feedback_section";
        $fResult = mysqli_query($con, $sql);
        if(mysqli_num_rows($fResult) > 3) {
          $rows = array();
          while ($row = mysqli_fetch_assoc($fResult)) {
            $rows[] = $row;
          }
      ?>
        <div class="revs-container">
          <div class="rev-container right">
            <div class="dot"></div>
            <div class="line"></div>

            <p class="rev-head"><span>Customers'</span><br> Take On Our Service</p>
            <div class="rev">
              <?php
                $row = $rows[0];
                $uname = $row['username'];
                $message = $row['message'];
                $uId = $row['id'];
                $sql = "SELECT image from user where userId='$uId'";
                $fResult = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($fResult);
                $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';
              ?>
              <?php echo $image ?>
              <div>
                <span><?php echo $uname ?></span>
                <p><?php echo $message ?></p>
              </div>
            </div>
          </div>

          <div class="rev-container left">
            <div class="dot"></div>
            <div class="rev">
              <?php
                $row = $rows[1];
                $uname = $row['username'];
                $message = $row['message'];
                $uId = $row['id'];
                $sql = "SELECT image from user where userId='$uId'";
                $fResult = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($fResult);
                $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';
              ?>
              <div>
                <span><?php echo $uname ?></span>
                <p><?php echo $message ?></p>
              </div>
              <?php echo $image ?>
            </div>
          </div>

          <div class="rev-container right">
            <div class="dot"></div>
            <div class="rev">
              <?php
                $row = $rows[2];
                $uname = $row['username'];
                $message = $row['message'];
                $uId = $row['id'];
                $sql = "SELECT image from user where userId='$uId'";
                $fResult = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($fResult);
                $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';
              ?>
              <?php echo $image ?>
              <div>
                <span><?php echo $uname ?></span>
                <p><?php echo $message ?></p>
              </div>
            </div>
          </div>

          <div class="rev-container left">
            <div class="dot"></div>
            <div class="rev">
              <?php
                $row = $rows[3];
                $uname = $row['username'];
                $message = $row['message'];
                $uId = $row['id'];
                $sql = "SELECT image from user where userId='$uId'";
                $fResult = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($fResult);
                $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';
              ?>
              <div>
                <span><?php echo $uname ?></span>
                <p><?php echo $message ?></p>
              </div>
              <?php echo $image ?>
            </div>
          </div>
        </div>
      <?php } ?>

      <div class="contact-us" id="contact">
        <h1>GET IN TOUCH <div class="underline"></div></h1>
        <div>
          <div class="address">
            <div class="image"><img src="svgs/4200779_address_location_map_navigation_icon.svg"></div>
            <span>ADDRESS</span>
            <p>No. 100, Galle Road, Bambalapitiya, Colombo 04 Colombo 4, 00400</p>
          </div>
          <div class="phone">
            <div class="image"><img src="svgs/9055406_bxs_phone_icon.svg"></div>
            <span>PHONE</span>
            <p>0767139018</p>
            <p>0767139018</p>
          </div>
          <div class="email">
            <div class="image"><img src="svgs/1564513_comment_message_voice_icon.svg"></div>
            <span>EMAIL</span>
            <p>hirunadilmith15@gail.com</p>
          </div>
        </div>
      </div>

      <div class="about-us" id="aboutus">
        <h1>About Us</h1>
        <div class="about-left">
          <h1>About Us</h1>
          <img src="images/jack-b-fewhfXbCUzI-unsplash.jpg">
          <div class="img-blure"></div>
        </div>
        <div class="about-right">
          <div>
            <div class="left"><img src="svgs/star-1-svgrepo-com.svg"></div>
            <div class="right">
              <h3>Our Products</h3>
              <p>At we believe in offering only the best. That's why we carefully curate our selection to include products from leading brands known for their quality, reliability, and performance. From cutting-edge processors and graphics cards to sleek laptops and peripherals, we have everything you need to stay connected, productive, and entertained.</p>
            </div>
          </div>
          <div>
            <div class="left"><img src="svgs/star-1-svgrepo-com.svg"></div>
            <div class="right">
              <h3>Expertise You Can Trust</h3>
              <p>With years of experience in the industry, our team of knowledgeable professionals is dedicated to helping you find the perfect solution for your computing needs. Whether you're looking for a powerful gaming rig, a reliable business workstation, or simply need advice on upgrading your existing setup, we're here to lend a helping hand.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="feedback-container">
        <p class="feedbak-head">Got a minute to <br> give us some <br> feedback?</p>
        <div class="submitError1-message">
           <span>Enter name!</span>
        </div>
        <div class="submitError2-message">
           <span>Enter message!</span>
        </div>
        <div class="left">
          <p>Got a minute to <br> give us some <br> feedback?</p>
        </div>
        <div class="right">
          <?php if($login == "yes"){ ?>
          <form action="homepage.php" method="post" onsubmit="return feedbackValidate()">
          <?php } else{ ?>
          <form action="homepage.php" method="post" onsubmit="return openLoginForm()">
          <?php } ?>
            <?php
              if($login == "yes"){
            ?>
              <input type="text" placeholder="Name" name="name" class="feedback-name" value="<?php echo $username ?>">
            <?php }else{ ?>
              <input type="text" placeholder="Name" name="name" class="feedback-name">
            <?php } ?>
            <textarea name="message" cols="30" rows="10" placeholder="Message" class="feedback-message"></textarea>
            <input type="submit" name="feedbackSubmit" value="SEND" class="feedback-btn">
          </form>
        </div>
      </div>

      <footer>
        <span style="color: white; font-family:'Roboto'; color: gray; font-size: 0.7rem">Designed and developed by HirunA Dilmith</span>
        <div class="footerSocial">
          <span style="color: white;">Folow us on</span>
          <div><i style="color: #6103d9;" class="fa-brands fa-facebook-f"></i></div>
          <div><i style="color: #6103d9;" class="fa-brands fa-instagram"></i></div>
        </div>
      </footer>

      <div class="login-form-container <?php if($useradded == "mismatch" || $login == "mismatch") { echo "show"; } ?>">
        <form action="homepage.php" method="post" class="login-form">
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
                <span>Incorrect login details!</span>
              <?php } ?>
              <a href="">Forgot Your Password?</a>
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

      <div class="profileEditFormContainer">
        <div class="profileEditForm">
          <div>
            <span>EDIT PROFILE</span>
            <div onclick="closeProfileEditForm()"><img src="svgs/icons8-close.svg" /></div>
          </div>
          <form action="homepage.php" method="post" enctype="multipart/form-data" onsubmit="return validateEditProfile()">
            <label>profile image</label>
            <label for="image" class="pro-image">
              <?php
                if($login == "yes"){
                  $id = $_SESSION["newUserId"];
                  $sql = "SELECT image FROM user where userId='$id'";
                  $userResult = mysqli_query($con, $sql);
                  while($row = mysqli_fetch_assoc($userResult)){
                    $image = $row["image"];
                  }
              ?>
                <?php if($image != null){ ?>
                  <div>
                    <img id="profileImage" src="data:image/jpeg;base64,<?php echo base64_encode($image); ?>" />
                    <img src="images/camera.png" class="camera" />
                  </div>
                <?php } else{ ?>
                  <div>
                    <img id="profileImage" src="" />
                    <img src="images/camera.png" class="camera" />
                  </div>
                <?php } ?>
              <?php } ?>
            </label>
            <input style="display: none" type="file" name="image" id="image" onchange="displayImage(event)"/>
            <label for="username">username</label>
            <input
              type="text"
              name="username"
              id="username"
              class="username"
              value="<?php echo $username ?>"
            />
            <span class="proEdit-error">Please add a username!</span>
            <input
              type="submit"
              name="editProfileSubmit"
              value="save changes"
              class="editProfileSubmit"
            />
          </form>
        </div>
      </div>

      <!--  -->
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
      <!--  -->

      <script>
        const formLeftContent = document.querySelector(".form-left-content");
        const formLeftContentSignUp = document.querySelector(".form-left-content-sign-up");

        const formRightSignIn = document.querySelector(".form-right-sign-in");
        const formRightSignUp = document.querySelector(".form-right-sign-up");

        const loginFormContainer = document.querySelector(".login-form-container");
        const logOutFrom = document.querySelector(".profile");

        const heropNameslider = document.querySelector('.heropDetailsContainer');
        const heropNames = heropNameslider.querySelectorAll('p');
        const slider = document.querySelector('.hero-product-slider');
        const images = slider.querySelectorAll('img');
        let currentSlide = 0;
        let currentSlide2 = 0;
        function nextSlide() {
          images[currentSlide].style.display = 'none';
          heropNames[currentSlide2].style.display = 'none';
          currentSlide = (currentSlide + 1) % images.length;
          currentSlide2 = (currentSlide2 + 1) % heropNames.length;
          images[currentSlide].style.display = 'block';
          heropNames[currentSlide2].style.display = 'block';
        }
        for (let i = 1; i < images.length; i++) {
          images[i].style.display = 'none';
          heropNames[i].style.display = 'none';
        }
        setInterval(nextSlide, 5000);


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
          return false;
        }

        function showProfile(){
          logOutFrom.classList.toggle("show");
        }

        function openProfileEditForm(){
          logOutFrom.classList.remove("show");
          document.querySelector(".profileEditFormContainer").classList.add("show");
        }

        function closeProfileEditForm(){
          document.querySelector(".profileEditFormContainer").classList.remove("show");
        }

        function displayImage(event) {
          var image = document.getElementById('profileImage');
          image.src = URL.createObjectURL(event.target.files[0]);
        }

        function validateEditProfile(){
          var username = document.getElementById("username").value.trim();

          if (username === "") {
            document.querySelector('.proEdit-error').classList.add('show');
            return false;
          } else {
            document.querySelector('.proEdit-error').classList.remove('show');
            return true;
          }
        }

        function feedbackValidate() {
          var name = document.getElementsByName("name")[0].value;
          var message = document.getElementsByName("message")[0].value;

          if (name == "") {
            document.querySelector(".submitError1-message").classList.add("show");
            setTimeout(function() {
              document.querySelector(".submitError1-message").classList.remove("show");
            }, 5000);
            return false;
          }

          if (message == "") {
            document.querySelector(".submitError2-message").classList.add("show");
            setTimeout(function() {
              document.querySelector(".submitError2-message").classList.remove("show");
            }, 5000);
            return false;
          }

          return true;
        }

        function showCart(){
          cartContainer.classList.remove("cart-container-hide");
        }


        function scrollToContact() {
          var contactSection = document.getElementById('contact');
          var navbarHeight = document.querySelector('header').offsetHeight;
          var scrollToPosition = contactSection.offsetTop - navbarHeight;
          window.scrollTo({
              top: scrollToPosition,
              behavior: 'smooth'
          });
        }

        function scrollToAboutUs(){
          var aboutSection = document.getElementById('aboutus');
          var navbarHeight = document.querySelector('header').offsetHeight;
          var scrollToPosition = aboutSection.offsetTop - navbarHeight;
          window.scrollTo({
              top: scrollToPosition,
              behavior: 'smooth'
          });
        }

        function scrollToNewArrival(){
          var newArrivalSection = document.getElementById('newArrival');
          var navbarHeight = document.querySelector('header').offsetHeight;
          var scrollToPosition = newArrivalSection.offsetTop - navbarHeight;
          window.scrollTo({
              top: scrollToPosition,
              behavior: 'smooth'
          });
        }

        function closeLoginForm(){
          loginFormContainer.classList.remove("show");
        }
      </script>
    </body>
  </html>
<?php mysqli_close($con); ?>