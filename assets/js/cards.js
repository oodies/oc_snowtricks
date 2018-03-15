/*!
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 * application.js
 *
 * source : https://symfony.com/doc/current/form/form_collections.html
 *
 */
// setup an "add a tag" link
var $addImageLink = $('<a href="#" class="btn btn-warning">Add a image</a>');

$(document).ready(function() {
  // Get the that holds the collection of images
  var $collectionHolder = $('.cards');
  // count the current form inputs we have (e.g. 2), use that as the new
  // index when inserting a new item (e.g. 2)
  $collectionHolder.data('index', $collectionHolder.find('input[type="file"]').length+1);

  // add the "add a image" anchor
  $("#images-new").append($addImageLink);

  $addImageLink.on('click', function(e) {
    e.preventDefault();
    // add a new tag form (see code block below)
    addImageForm($collectionHolder, $addImageLink);
  });

  // Remove image to the collection
  $(document).on('click', '.remove-card', function (e) {
    e.preventDefault();
    $(this).parents('.st_card').remove();
    return false;
  });
});

function addImageForm($collectionHolder, $addImageLink) {
  // Get the data-prototype explained earlier
  var prototype = $collectionHolder.data('prototype');

  // get the new index
  var index = $collectionHolder.data('index');

  // Replace '$$name$$' in the prototype's HTML to
  // instead be a number based on how many items we have
  var newForm = prototype.replace(/__name__/g, index);

  // increase the index with one for the next item
  $collectionHolder.data('index', index + 1);

  // Display the form in the page
  var $newFormImage = $('<div class="st_card"><img src="/web/img/image-not-found.png" alt="image-not-found" class="ing-fluid" /><a class="trash btn btn-sm btn-light" role="button" href="#"><i class="remove-card fa fa-trash-o" aria-hidden="true"></i></a><div class="input_file-container">'+ newForm + '</div></div>');

  $collectionHolder.append($newFormImage);
}








