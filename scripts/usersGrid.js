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
    let url = `dbModule/searchUser.php?keyword=${encodeURIComponent(search_term)}&limit=${encodeURIComponent(limit)}&current_page=${encodeURIComponent(current_page)}`;

    //hiding the error message if it exists 
    let error_message = document.getElementsByClassName("error-message")[0];
    if(error_message && more_users) grid.innerHTML = ''; //if more images is resetd to true and the error_message is still appearing we should empty the grid to accept new images
    if(!more_users) return; 
   
    fetchUsers(url); 
  
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
            if(data.success){
               data.users.forEach((user) =>  {
                 let username = user.username; 
                 let user_id = user.user_id;
                 let user_imageCount = user.imageCount;

                 grid.innerHTML += `
                  <div class="col-md-4 col-lg-3">
                    <div class="card user-card p-3">
                        <div class="d-flex align-items-center">
                            <img src="webPictures/profile.png" alt="User Profile" class="user-profile me-3">
                            <div>
                                <h5 class="mb-1">@${username}</h5>
                                <p class="text-muted mb-0">${user_imageCount} images</p>
                            </div>
                        </div>
                        <div class="mt-3 action-buttons text-center">
                            <a class="btn btn-primary btn-sm view-button" href='userprofile.php?user_id=${user_id}'><i class="fas fa-eye"></i> View</a>
                        </div>
                    </div>
                </div>
                 `;

                })

                first_render=false; 
                 
            }else {
                more_users= false; 
                if(grid.innerHTML==='' && current_page === 1){
                grid.innerHTML=`
                     <div class='error-message'>
                     <img src='webPictures/notFound.png' width=300px>
                        <h3> Oops ! No users found </h3>
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
        loadUsers();
    }
});















