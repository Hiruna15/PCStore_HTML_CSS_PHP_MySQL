<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="feedbackSection.css" />
</head>
<body>

    <!--  -->
    <?php
        include "conf.php";

        if(isset($_POST["addfeedbackSection"])){
            $id = $_POST["id"];
            $name = $_POST["name"];
            $message = $_POST["message"];
            $feedbackID = $_POST["feedbackID"];
            $image = $_POST["image"];

            $countSql = "SELECT COUNT(*) AS count FROM feedback_section";
            $countResult = mysqli_query($con, $countSql);
            $rowCount = mysqli_fetch_assoc($countResult)["count"];

            if ($rowCount < 4) {
                $sql = "INSERT INTO feedback_section (feedbackID, id, username, message, image) values ('$feedbackID', '$id', '$name', '$message', '$image')";
                mysqli_query($con, $sql);
                
                $sql = "UPDATE feedbacks set inFeedBackSection='yes' where feedbackID='$feedbackID'";
                mysqli_query($con, $sql);

                echo "feedback Added to the feedback section";
            } else{
                echo "Maximum feedback limit reached. You can't add more feedbacks.";
            }
        }

        $sql = "SELECT * FROM feedbacks WHERE inFeedBackSection='no'";
        $result = mysqli_query($con, $sql);
        if(mysqli_num_rows($result) > 0) {
    ?>
        <table> 
            <tr>
                <th>feedback id</th> 
                <th>user id</th> 
                <th>username</th> 
                <th>message</th>
                <th>image</th>
                <th class="hide"></th>
            </tr>
            <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row["userId"];
                    $feedbackID = $row["feedbackID"];
                    $username = $row["username"];
                    $message = $row["message"];
                    $sql = "SELECT image FROM user WHERE userId='$id'";
                    $r = mysqli_query($con, $sql);
                    $row = mysqli_fetch_assoc($r);
                    $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image']) . '" />';
            ?>
                <form action="feedbackSection.php" method="POST">
                    <tr> 
                        <td><?php echo $feedbackID ?></td> 
                        <td><?php echo $id ?></td> 
                        <td><?php echo $username ?></td> 
                        <td><?php echo $message ?></td>
                        <td><?php echo $image ?></td> 
                        <td>
                            <input type="hidden" name="id" value="<?php echo $id ?>">
                            <input type="hidden" name="name" value="<?php echo $username ?>">
                            <input type="hidden" name="message" value="<?php echo $message ?>">
                            <input type="hidden" name="feedbackID" value="<?php echo $feedbackID ?>">
                            <input type="hidden" name="image" value='<?php echo $image ?>'>
                            <input type="submit" name="addfeedbackSection" value="Add to feedback section" />
                        </td>
                    </tr>
                </form>
            <?php } ?>
        </table>
    <?php } ?>


    <!--  -->
    <?php 
        if(isset($_POST["feedbackRemove"])){
            $fId = $_POST["feedbackId"];

            $sql = "DELETE FROM feedback_section WHERE feedbackID='$fId'";
            mysqli_query($con, $sql);
            
            $sql = "UPDATE feedbacks set inFeedBackSection='no' where feedbackID='$fId'";
            mysqli_query($con, $sql);
            
            echo "feedback removed from the feedback section";
        }

        $sql = "SELECT * FROM feedback_section";
        $result = mysqli_query($con, $sql);
        if(mysqli_num_rows($result) > 0) {
    ?>
        <table> 
            <tr> 
                <th>feedbackId</th> 
                <th>userId</th> 
                <th>username</th> 
                <th>message</th>
                <th>image</th>
                <th>Action</th>
            </tr>
            <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $feedbackId = $row["feedbackID"];
                    $userid = $row["id"];
                    $username = $row["username"];
                    $message = $row["message"];
                    $image = $row["image"];

                    $sql = "SELECT image FROM user where userId='$userid'";
                    $img = mysqli_query($con, $sql);
                    $row_img = mysqli_fetch_assoc($img);
                    $image = '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row_img['image']) . '" />';
            ?>
                <tr> 
                    <td><?php echo $feedbackId ?></td> 
                    <td><?php echo $userid ?></td> 
                    <td><?php echo $username ?></td> 
                    <td><?php echo $message ?></td>
                    <td><?php echo $image ?></td>
                    <td>
                        <form action="feedbackSection.php" method="POST">
                            <input type="hidden" name="feedbackId" value="<?php echo $feedbackId ?>">
                            <input type="submit" name="feedbackRemove" value="Remove">
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
      <a href="heroProductSection.php">
        <i class="fas fa-address-book"></i>
        <span class="link-name">Edit hero section</span>
      </a>
    </li>
    <li>
      <a href="feedbackSection.php" class="active">
        <i class="fas fa-cog"></i>
        <span class="link-name">Edit feedback section</span>
      </a>
    </li>
  </ul>
</div>
</body>
</html>
