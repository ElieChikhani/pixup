const msnry = new Masonry(grid, {
    itemSelector: '.grid-item',
    columnWidth: '.grid-item',
    percentPosition: true

});

imagesLoaded(grid)
.on('progress', function () {
    msnry.layout();
})

makeImagesClickable();