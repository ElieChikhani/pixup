let current_page = 1;
const limit = 20; // Number of images to load per request
let search_term=""; 


const grid = document.querySelector('.users-grid');
const search_form = document.getElementById("search-form");//used to load images as the user scrolls
let first_render = true; 
let more_users = true; 



loadUsers();  
search_form.addEventListener('submit', (event) => {
    event.preventDefault();
    submitSearchForm(); 
} )


function submitSearchForm(){
    more_users=true; 
    grid.innerHTML = ''; //to reset the grid
    current_page = 1; //current page is reseted to 1 for new query results
    grid.style.height = 'auto';//reset the hieght of the grids
    first_render=true; 
    search_term = document.getElementById("search-bar").value;
    loadUsers(); 

}

function loadUsers() {
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

function fetchUsers(url){
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


//displaying images as user scrolls down 
window.addEventListener('scroll', () => {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 100 && !first_render) { //near the bottom of page
        current_page++;
        loadUsers(search_term, order, limit, current_page);
    }
});















