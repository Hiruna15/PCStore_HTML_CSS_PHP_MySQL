<?php
    include "conf.php";

    $sql = "SELECT * FROM testing ORDER BY id DESC";
    $result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="gallery">
        <?php if(mysqli_num_rows($result) > 0){ ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['img']); ?>" />
            <?php } ?>
        <?php }else{ ?>
            <p>Image(s) not found....</p>
        <?php } ?>
    </div>
</body>
</html>