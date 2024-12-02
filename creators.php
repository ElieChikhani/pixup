<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creators</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="styles/generalStyle.css" rel="stylesheet" />
    <link href="styles/creators.css" rel="stylesheet" />
</head>

<body>

    <?php
    if (!isset($_SESSION))
        session_start();
    include "components/header.php";
    ?>

    <main>

        <div class="p-5 mb-4 creators-title">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold"> Creators list</h1>
                <p class="col-md-8 fs-4">
                    Here is the list of our creative users
                </p>
            </div>
        </div>

        <div class="container my-5 users-container">

            <form id='search-form'>
                <input id='search-bar' class='form-control me-sm-2' type='text' placeholder='Search for users'
                    name='search' />
            </form>

            <div class="row g-4 users-grid">
                
              
           
            </div>
        </div>




    </main>



    <script src="scripts/usersGrid.js"> </script>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>