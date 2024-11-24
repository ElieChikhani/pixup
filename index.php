<!doctype html>
<html lang="en">

<head>
    <title>PixUp</title>
  
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="styles/generalStyle.css" rel="stylesheet" />
    <link href="styles/indexStyle.css" rel="stylesheet" />
</head>

<body>



    <?php  

    if(!isset($_SESSION)) session_start(); 
    
    $_SESSION['user_id']=2; 
    $_SESSION['username']='clararezkallah';
    

    include 'components/header.php'; ?>
    <main>


      <div class="p-5 mb-4 welcome">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold" id="welcome-message">Welcome, visitor !</h1>
            <p class="col-md-8 fs-4" id="welcome-info">
                Here lays the art of "drawing with light" pretty much known as Photography...
            </p>

        </div>

        <img src="webPictures/welcome.png">
      </div>

      <?php
      
      //FOR IMAGE GRID
      if($_SERVER['REQUEST_METHOD'] == "POST") {
         $search = htmlspecialchars($_POST['search']);
         $order = htmlspecialchars($_POST['order']);
      } else {
         $search= '';
         $order = 'recent'; //default ordering at first load of the page 
      }

      ?>

    <form id='search-form' action='<?php $_SERVER['PHP_SELF'] ?>' method = 'POST'>

    <input id='search-bar' class='form-control me-sm-2' type='text'
    placeholder='Search for photos' name='search' value='<?php echo $search?>'/>

     <select class='form-select form-select-lg' name='order' id='order-select'>
     <option <?php echo ($order === 'recent') ? 'selected': ''?> value='recent'>Recent</option>
     <option <?php echo ($order === 'popular') ? 'selected':''?> value='popular'>Most Popular</option>
     </select>

     </form>

   

      <?php

      include 'components/imageGrid.php' ?>
      <!-- ------  -->

        
    </div>
      </div>
    </div>
    </div>
   
    

    </main>

    <footer>

    </footer>

    <!--Masonry.js for grid layout-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/4.2.2/masonry.pkgd.min.js"></script>

    <!--check the loading state of an image-->
    <script src="https://unpkg.com/imagesloaded/imagesloaded.pkgd.min.js"></script>

    <script src="scripts/allGridScript.js"></script>


    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>