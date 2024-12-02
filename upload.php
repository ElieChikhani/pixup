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

<div class="loader-container">
        <div class="loader"></div>
    </div>

   
    <?php 
    session_start();

    include 'components/header.php';
    include 'components/signinrequired.php'
    ?>





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
      <strong> <i class="fas fa-circle-exclamation"> </i> Copyright rules : </strong> Please upload a picture that you own or mention the rightful owner of the image in the description
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

        $user_id=$_SESSION['user_id'];
        
        if(!empty($user_id)){

        //calling the searchAlbum to search for albums
        $searchURL = "http://localhost/PIXUP/dbModule/searchAlbum.php?user_id=$user_id";
        $searchResponse = file_get_contents($searchURL);

        if ($searchResponse === false) {
            die("Error: Unable to fetch data from the URL: $searchURL");
        }

        $searchData = json_decode($searchResponse, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            die("Error: Failed to decode JSON. " . json_last_error_msg());
        }

        if($searchData['success'] && $searchData['result_number']>1){ //if the user has more than the "ALL" album

            echo "
             <label id='album_select_label'> Include in these albums :  </label>
             <div class='list-group'>
            ";

        $albums = $searchData['albums']; 
        foreach ($albums as $album) {
            $album_id = $album['album_id'];
            $album_name = $album['album_name'];

            //the all album will include the new image regardless if the user accepts or not so it is not included in the choice
            if($album_name != 'All'){
            echo "
             <label class='list-group-item'>
            <input class='orm-check-input me-1' type='checkbox' value='$album_id' name='albums[]'/>
            $album_name 
            </label>
            ";
            }
        }

        echo "</div>";

    }

        }

       ?>
    

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="terms_check" id="terms_check" required />
            <label class="form-check-label" for="terms_check"> 
            By uploading this image, I acknowledge and accept that it will be publicly
                 accessible and that it will be processed and categorized using artificial intelligence (AI) technology. I understand that this may involve analysis and use of the image data for AI training and categorization purposes. </label>
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