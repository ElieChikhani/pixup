document.addEventListener("DOMContentLoaded", function() {
    var elem = document.querySelector('.photo-gallery');
    var msnry = new Masonry(elem, {
        itemSelector: '.grid-item',
        columnWidth: '.grid-item',
        percentPosition: true
    });
});

//the search query should be submitted automatically when the ordering changes 

document.getElementById("order-select").addEventListener('change',()=>{
    localStorage.setItem("scrollPosition", window.scrollY); //save position since submit event does not catch this case
    document.getElementById("search-form").submit(); 
})



//saving the scolling position so at subkit it doesn't go back tot he top of the page :
document.getElementById("search-form").addEventListener('submit', ()=> {
    localStorage.setItem("scrollPosition", window.scrollY);
} )

window.addEventListener("load", () => {
    if (localStorage.getItem("scrollPosition")) {
        window.scrollTo(0, localStorage.getItem("scrollPosition"));
        localStorage.removeItem("scrollPosition"); 
    }
});