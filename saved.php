<!doctype html>
<html lang="en">

<head>
    <title>Saved</title>
  
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="styles/generalStyle.css" rel="stylesheet" />
    <link href="styles/saved.css" rel="stylesheet" />
</head>

<body>
   
    <?php 
    session_start();

    include 'components/header.php';
    include 'components/signinrequired.php'
    ?>


    <main>
        <div class="p-5 mb-4 saved-title">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold">Saved Images</h1>
                <p class="col-md-8 fs-4">
                    Here are the images you saved !
                </p>
            </div>
        </div>


    <form id='search-form' user_id = <?php echo $_SESSION['user_id']?> saved='true' >

    <input id='search-bar' class='form-control me-sm-2' type='text'
    placeholder='Search your saved photos' name='search'/>

     <select class='form-select form-select-lg' name='order' id='order-select'>
     <option value='recent'>Recent</option>
     <option value='popular'>Most Popular</option>
     </select>

     </form>

      <?php

      include 'components/imageGrid.php' ?>
      <!-- ------  -->


    <main>

   
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