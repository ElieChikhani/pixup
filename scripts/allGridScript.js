document.addEventListener("DOMContentLoaded", function() {
    var grid = document.querySelector('.photo-gallery');
    var msnry = new Masonry(grid, {
        itemSelector: '.grid-item',
        columnWidth: '.grid-item',
        percentPosition: true

    });

     // Ensuring images are loaded before layout
     imagesLoaded(grid).on('progress', function () {
        msnry.layout();
    });

    //making the images clickable
    const images = document.querySelectorAll('.grid-item');
    
    Array.from(images).forEach((image)=> {
        let image_id;

        image.addEventListener('click', function(event){ 

            image_id=event.target.id.replace('image',"");
            fetch(`dbModule/getImageInfo.php?image_id=${image_id}`)
            .then(response => {
                 if (!response.ok) {
                     throw new Error(`HTTP error! Status: ${response.status}`);
                 }
            return response.json(); // Parse the JSON response
            })
            .then(data => {
                console.log(data); 
               if(data.success) {
                displayImageInfoPopup(data.data.isLoggedIn,data.data.isOwner,data.data.image_id,data.data.title,data.data.path,
                    data.data.username,data.data.description,data.data.savedCount,data.data.upload_date,data.data.tags,data.data.saved); 
               }
                
                
            })
            .catch(error => {
            console.error('Error fetching data:', error); // Handle any errors
             });

          })
    }); 
    
});


const save_button=document.getElementsByClassName('save-button')[0]; 
save_button.addEventListener('click',()=>{
    handleImageSave(save_button)
 }); 

//the search query should be submitted automatically when the ordering changes 
document.getElementById("order-select").addEventListener('change',()=>{
    document.getElementById("search-form").submit(); 
})

function displayImageInfoPopup(isLoggedIn,isOwner,image_id,title,path,username,description,savedCount,uploadDate,tags,saved){
    document.getElementById('image-title').innerText = title; 
    document.getElementById('by').innerText = 'By '+ username;
    document.getElementById('description').innerText = description;
    document.getElementById('saved-count').innerText = savedCount;
    document.getElementById('upload-date').innerText = 'Uploaded on '+ uploadDate;
    document.getElementById('image-popup').style.display='flex'; 

    let image_element = document.getElementsByClassName('popupImage')[0];
    image_element.src = path;
    image_element.id = 'popupImage'+image_id;

    let tags_container = document.getElementById('image-tags');
    tags_container.innerHTML = ""; //clearing the container

    tags.forEach(tag => {
        let tag_container = document.createElement('div'); 
        tag_container.className = 'tag'; 
        tag_container.innerText = tag;
        tags_container.appendChild(tag_container); 
    })

     //adding event listener to close 
     document.getElementById('image-popup-close').addEventListener('click',()=>{
        document.getElementById('image-popup').style.display='none';
    }); 

    //if the image is not owned by the user allow the display of save button 
    if(!isOwner&&isLoggedIn) {
    save_button.style.display='block'; 
    setSaveButtonState(save_button,saved);

   }else{
    save_button.style.display='none'; 
   }

}

function setSaveButtonState(save_button,saved){
    if(saved){
        save_button.classList.add('unsave'); 
        save_button.innerText= 'Unsave';
    }else{
        save_button.classList.remove('unsave');
        save_button.innerText= 'Save';
    }

}

function handleImageSave(save_button){

    //getting image id of the image being currently displayed
    let image_id = document.getElementsByClassName('popupImage')[0].id.replace("popupImage","");


    fetch(`dbModule/updateSaveTable.php?image_id=${image_id}`)
    .then(response => {
         if (!response.ok) {
             throw new Error(`HTTP error! Status: ${response.status}`);
         }
    return response.json(); // Parse the JSON response
    })
    .then(data => {
       if(data.success) {

         if(data.action==='saved'){
            setSaveButtonState(save_button,true); 
         }else {
            setSaveButtonState(save_button,false);
         }
       }
        
    })
    .catch(error => {
    console.error('Error fetching data:', error); // Handle any errors
     });

}




