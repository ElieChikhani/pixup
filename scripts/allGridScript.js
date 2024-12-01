const grid = document.querySelector('.photo-gallery');
const search_form = document.getElementById("search-form");

//used to load images as the user scrolls
let current_page = 1;
const limit = 10; // Number of images to load per request
let search_term=""; 
let order='recent'; 
let more_images = true; //to stop generating as the usrr scrolls down where their is no more images
let current_popup = undefined; 
let first_render = true; 
let filter = ""; 
let active_filter_button = document.getElementById('initial-filter'); 
let selectableImages = false; 


loadImages();  
search_form.addEventListener('submit', (event) => {
    event.preventDefault();
    submitSearchForm(); 
} )


//the search query should be submitted automatically when the ordering changes 
document.getElementById("order-select").addEventListener('change',()=>{
    submitSearchForm(); 
})

function setSaveButtonState(save_button,saved){
    if(saved){
        save_button.classList.add('unsave'); 
        save_button.innerText= 'Unsave';
    }else{
        save_button.classList.remove('unsave');
        save_button.innerText= 'Save';
    }

}

function handleImageSave(save_button) {
    
    fetch('dbModule/updateSaveTable.php', {
        method: 'POST', 
        headers: {
            'Content-Type': 'application/json', 
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json(); 
    })
    .then(data => {
        if (data.success) {
            if (data.action === 'saved') {
                setSaveButtonState(save_button, true);
            } else {
                setSaveButtonState(save_button, false);
            }

              //resubmit if saved page
              if(document.title==='Saved')  {
                handlePopupClosure(current_popup);
                submitSearchForm(); 
              }
        }else {
            console.log(data);
        }
    })

  
    .catch(error => {
        console.error('Error fetching data:', error); // Handle any errors
    });
}

function handleImageDelete (){
    fetch('dbModule/deleteImage.php', {
        method: 'POST'
    }).then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json(); 
    })
    .then(data => {
        console.log('Request succeeded:', data);
    })
    .catch(error => {
        console.error('An error occurred:', error);
    });

    handlePopupClosure(current_popup)
    submitSearchForm();  

}

function handlePopupClosure (popup){
    popup.style.display='none'; 
    fetch(`components/imageInfo.php?action=close`); //clear the image tracking
}

function submitSearchForm(){
    more_images=true; 
    grid.innerHTML = ''; //to reset the grid
    current_page = 1; //current page is reseted to 1 for new query results
    grid.style.height = 'auto';//reset the hieght of the grids
    first_render=true; 
    search_term = document.getElementById("search-bar").value;
    order = document.getElementById("order-select").value;
    
    loadImages(); 

}

function loadImages() {
    let url = `dbModule/searchImage.php?order=${encodeURIComponent(order)}
             &keyword=${encodeURIComponent(search_term)}&limit=${encodeURIComponent(limit)}&current_page=${encodeURIComponent(current_page)}&&filter=${encodeURIComponent(filter)}`;

    if (search_form.getAttribute('album_id')) {
        let album_id = search_form.getAttribute('album_id');
        url += `&album_id=${encodeURIComponent(album_id)}`;
    }

    if (search_form.getAttribute('album_id_not')) {
        let album_id = search_form.getAttribute('album_id_not');
        url += `&album_id_not=${encodeURIComponent(album_id)}`;
    }

    if (search_form.getAttribute('saved')&&search_form.getAttribute('user_id')) {
        let saved = search_form.getAttribute('saved');
        let user_id = search_form.getAttribute('user_id');
        url += `&saved=${encodeURIComponent(saved)}&user_id=${encodeURIComponent(user_id)}`;
    }


    //hiding the error message if it exists 
    let error_message = document.getElementsByClassName("error-message")[0];
    if(error_message && more_images) grid.innerHTML = ''; //if more images is resetd to true and the error_message is still appearing we should empty the grid to accept new images
    if(!more_images) return; 
   

    fetchImages(url); 
  
}

function fetchImages(url){
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json(); 
        })
        .then(data => {
            console.log(url);
            console.log(data);
            if(data.success && data.result_number > 0){
               data.images.forEach((image_data) =>  {
                    let grid_item = document.createElement('div');
                    grid_item.className = 'grid-item';
                    grid.appendChild(grid_item); 
                    let image = document.createElement('img'); 
                    image.src = image_data.path;
                    image.id = `image${image_data.id}`; 
                    grid_item.appendChild(image); 
                })

                first_render=false; 
                adjustGridDisplay(); 
            }else {
                more_images = false; 
                if(grid.innerHTML==='' && current_page === 1){
                grid.innerHTML=`
                     <div class='error-message'>
                     <img src='webPictures/notFound.png' width=300px>
                        <h3> Oops ! Coudn't find what you're looking for ... </h3>
                     </div>
                `;
                
                }
            }

            
        })
        .catch(error => {
            console.error('Error fetching images:', error);
        });

}

function adjustGridDisplay(){

    const msnry = new Masonry(grid, {
        itemSelector: '.grid-item',
        columnWidth: '.grid-item',
        percentPosition: true
    
    });
  
    imagesLoaded(grid)
    .on('progress', function () {
        msnry.layout();
    })

    if(search_form.getAttribute('selectable')) {
        makeImagesSelectable();
    }else {
        makeImagesClickable();
    }

}

function makeImagesSelectable(){
    const images = document.querySelectorAll('.grid-item');

    Array.from(images).forEach((image)=> {
        image.addEventListener('click', (event)=>{
            event.target.classList.toggle('selected'); //which is the clicked image
            image.classList.toggle('selected-box'); //to apply design 
        })
    }); 

}

function makeImagesClickable(){
     //making the images clickable
     const images = document.querySelectorAll('.grid-item');
    
     Array.from(images).forEach((image)=> {
         let image_id;
 
         image.addEventListener('click', function(event){ 
 
             image_id=event.target.id.replace('image',"");
            
             fetch(`components/imageInfo.php?image_id=${image_id}&action=open`, {
                credentials: 'include', 
             })
             .then(response => {
                  if (!response.ok) {
                      throw new Error(`HTTP error! Status: ${response.status}`);
                  }
             return response.text(); // it's a text containing the content of the html
             })
             .then(data => {
                 let popup = document.getElementsByClassName('popup')[0]
                 popup.style.display='flex'; 
                 popup.innerHTML = data; 
 
                  //event listener for save button 
                 const save_button=document.getElementsByClassName('save-button')[0]; 
                 if(save_button){
                     save_button.addEventListener('click',()=>{
                     handleImageSave(save_button,popup)
                 })}
 
                  //event listener for remove (close) button 
                     const close_button=document.getElementById('image-popup-close');
                     close_button.addEventListener('click',()=>{
                         handlePopupClosure(popup); 
                  }); 
 
                  //add event listener on delete photo button 
                  const delete_button=document.getElementsByClassName('delete-button')[0];
                  if(delete_button){
                     delete_button.addEventListener('click',()=>{
                         handleImageDelete()
                     })
                  }

                  current_popup=popup; 
             })
             .catch(error => {
             console.error('Error fetching data:', error); // Handle any errors
              });
 
  }); 
 
      })
}

//displaying images as user scrolls down 
window.addEventListener('scroll', () => {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 100 && !first_render) { //near the bottom of page
        current_page++;
        loadImages(search_term, order, limit, current_page);
    }
});

//suggestions if available
const suggButton = Array.from(document.getElementsByClassName('sugg')); 
if(suggButton){
    suggButton.forEach(button => {
        button.addEventListener('click', (event) => {
            filter = (button.textContent === 'All') ? "":button.innerText; 
            event.target.classList.add('clicked');
            active_filter_button.classList.remove('clicked');
            active_filter_button = event.target; 
            
            submitSearchForm(); 
        })
    })
}














