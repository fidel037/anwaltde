<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
</head>
<body>
     <?php
       foreach ($images as $image) { ?>
           <img src="<?php echo $image;  ?>">
       <?php } ?>
</body>
</html>
