<?php
//clear old image tracking 
if(!isset($_SESSION)) session_start(); 
if(isset($_SESSION['current_image_id']))unset($_SESSION['current_image_id']);
if(isset($_SESSION['current_image_path']))unset($_SESSION['current_image_path']);




echo "
<div class='photo-gallery'></div>

<div class='popup'></div>
";

?>





