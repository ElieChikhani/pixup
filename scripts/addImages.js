document.getElementById('done-button').addEventListener('click', () => {
    let selectedImages = document.getElementsByClassName('selected'); 
    let selectedImagesArray = Array.from(selectedImages);

    selectedImagesArray.forEach((selectedImage) => {
        let image_id = selectedImage.id.replace('image','');
        let album_id = document.getElementById('search-form').getAttribute('album_id_not');
        let data= new URLSearchParams({
            image_id: image_id, 
            album_id: album_id  
          }); 

        fetch("dbModule/addImageToAlbum.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
              },
            body: data.toString(),
          })
            .then((response) => {
              if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
              }
              return response.json();
            })
          
            .then((data) => {
              if(!data.success){
               
              }
            })
            .catch((error) => {
              console.error("An error occurred:", error);
            });


    })
})