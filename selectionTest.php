<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit album</title>

      <!-- Bootstrap CSS v5.2.1 -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="styles/generalStyle.css" rel="stylesheet" />
</head>
<body>
    <main>
   <!-- other edits  ... -->



   <h2> Edit the images in your album </h2> 

   <form id='search-form'>

    <input id='search-bar' class='form-control me-sm-2' type='text'
    placeholder='Search for photos' name='search'/>

     <select class='form-select form-select-lg' name='order' id='order-select'>
     <option value='recent'>Recent</option>
     <option value='popular'>Most Popular</option>
</select>

     </form>

   <?php include "components/imageGrid.php" ?> 
</main>


    



    <!--Masonry.js for grid layout-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/4.2.2/masonry.pkgd.min.js"></script>

    <!--check the loading state of an image-->
    <script src="https://unpkg.com/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="scripts/allGridScript.js"></script>
</body>
</html>