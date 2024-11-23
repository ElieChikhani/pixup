<!doctype html>
<html lang="en">

<head>
    <title>Upload</title>
  
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="styles/generalStyle.css" rel="stylesheet" />
    <link href="styles/uploadStyle.css" rel="stylesheet" />
</head>

<body>
    <header>

        <nav class="navbar navbar-expand-sm navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="#"> <i class="fas fa-camera-retro"> </i> PixUp</a>
                <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavId">
                    <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="#" aria-current="page">Home </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">Albums</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">Saved</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="#">Upload</a>
                         
                        </li>

                    </ul>
                   
                </div>
            </div>
        </nav>





    </header>
    <main>
        <div class="p-5 mb-4 upload-title">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold">Upload Image</h1>
                <p class="col-md-8 fs-4">
                    Share with the comunity your most creative image !
                </p>
    
            </div>
        </div>


     <section id="upload-section">

        <h3> Fill this form correctly in order to upload your picture </h3>
        <div
        class="alert alert-warning"
        role="alert"
      >
      <strong> <i class="fas fa-circle-exclamation"> </i> Copyright rules : </strong> Please upload a picture that you "own" Or, mention in the description the rightful owner of the image
      </div>


    
    <form id="image-upload-form" action="dbModule/uploadImage.php" method="POST" enctype="multipart/form-data" novalidate class="needs-validation">
        <div class="mb-3">
            <label for="title" class="form-label">Image Title</label>
            <input
                type="text"
                class="form-control"
                name="image-title"
                aria-describedby="helpId"
                required
            />
          <div class="invalid-feedback"> You must provide a title for your image </div>
        </div>

        <label> Upload your image here </label>
        <div class="mb-3" id="image-upload">
        <label for="input-file" class="form-label" id="drop-area">
            <input
                type="file"
                class="form-control"
                name="image"
                id="input-file"
                placeholder=""
                aria-describedby="fileHelpId"
                accept=".jpg, .jpeg, .png, .gif"
                hidden
                required
            />

            <div id="img-view">
                <img src="webPictures/upload.png" alt="No image selected">
                <p> Drag and drop or click here to upload image</p>

            </div>

            <div class="invalid-feedback"> You must upload your image here.</div>
       
        </label>

       

        </div> 

        <div class="mb-3">
            <label for="image-descrption" class="form-label"> Describe your image in few words </label>
            <textarea class="form-control" name="image-description" id="image-description" rows="3" maxlength="500"></textarea>
            <small id="char-count"> 500 characters left </small> 
        </div>



       <?php 

        $user_id=2; 

        $host = 'localhost';
        $db = 'pixup';
        $user = 'root';
        $pass = '';

        $conn = new mysqli($host, $user, $pass, $db);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT album_id, album_name FROM albums WHERE user_id = $user_id AND album_name <> 'All'"; 
        $result = $conn->query($sql);
        $albums = array();

        if($result->num_rows>0){

            echo "
             <label id='album_select_label'> Include in these albums :  </label>
             <div class='list-group'>
            ";

         while ($row = $result->fetch_assoc()) {
            $albums[$row['album_id']] = $row['album_name'];
         }

        foreach ($albums as $album_id => $album_name) {
            echo "
             <label class='list-group-item'>
            <input class='orm-check-input me-1' type='checkbox' value='$album_id' name='albums[]'/>
            $album_name 
            </label>
            ";
        }

        echo "</div>";

        }

       ?>
    

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="terms_check" id="terms_check" required />
            <label class="form-check-label" for="terms_check"> I accept that this image will be accessed publically and might be subject for AI training since it will be categorized by an AI model </label>
            <div class="invalid-feedback"> You must agree before submitting.</div>
        </div>

        <div class="d-grid gap-2">
            <button
            id="done-button"
            class="btn btn-primary"
        >
            Done
        </button>
        </div>

        

    </form>
    
    
    </section>

    
    </main>
    <footer>

    </footer>

    <script src="scripts/upload.js"> </script>



    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>