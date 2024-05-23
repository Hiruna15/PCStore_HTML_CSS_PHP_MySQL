<?php 
    include "conf.php";
    session_start();
    $newUserId = $_SESSION["newUserId"];
    $payment = false;


    if(isset($_POST["pay"])){
        $sql = "SELECT * FROM cart where userID='$newUserId'";
        $result = mysqli_query($con, $sql);
        if($result){
            while($row = mysqli_fetch_assoc($result)){
                $pid = $row["id"];
                $userQnt = $row["qnt"];

                $sql = "SELECT qnt from products where pId='$pid'";
                $pResult = mysqli_query($con, $sql);
                while($row = mysqli_fetch_assoc($pResult)){
                    $pQnt = $row["qnt"];

                    $updateQnt = $pQnt - $userQnt;
                    $sql = "UPDATE products SET qnt='$updateQnt' WHERE pId='$pid'";
                    mysqli_query($con, $sql);

                    $sql = "DELETE FROM cart where userID='$newUserId'";
                    mysqli_query($con, $sql);

                    $payment = true;
                }

            }
        }
    }

    // if(isset($_POST["buy"])){
    //     $cartTotal = $_POST["cartTotal"];

    //     $sql = "SELECT * FROM cart where userID='$newUserId'";
    //     $result = mysqli_query($con, $sql);
    // }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="payment.css" />
</head>
<body>
    <!-- <div class="message"></div> -->
    <form class="main-container" action="payment.php" method="post">
        <div class="left">
            <div><span>Checkout</span></div>
            <div class="payment-methods">
                <span>Payment Method</span>
                <div class="paypalBtn">
                    <img src="svgs/paypal.svg">
                </div>
                <span>or</span>
                <div class="cNum">
                    <input type="text" placeholder="Card Number">
                    <div>
                        <img src="svgs/master-card.svg">
                        <img src="svgs/visa.svg">
                    </div>
                </div>
                <div class="cNum"><input type="text" class="cardDt" placeholder="CVV"></div>
                <div class="cNum"><input type="text" class="cardDt" placeholder="zip code"></div>
                <button name="pay">Pay</button>
            </div>
        </div>
        <div class="right">
            <div class="total">
                <span>Order Total</span>
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
                            $cartTotal += $tPrice;}
                ?>
                    <span><?php echo $cartTotal . " LKR" ?></span>
                <?php 
            }else {?>
                    <span><?php echo $cartTotal . " LKR" ?></span>
            <?php } ?>
            </div>
            <div class="cartItemDt">
                <span>ITEM</span>
                <span>QTY</span>
                <span>PRICE</span>
            </div>
            <div class="price-section">
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
                    <div class="cart-details">
                        <div class="item">
                            <span><?php echo $cartPName ?></span>
                        </div>
                        <div class="qty">
                            <span><?php echo $cartQnt ?></span>
                        </div>
                        <div class="price">
                            <span><?php echo $tPrice . " LKR"?></span>
                        </div>
                    </div>
                <?php 
                        }
                    }
                ?>
            </div>
        </div>
    </form>
</body>
</html>