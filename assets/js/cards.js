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
var $addImageLink = $('<a href="#" class="btn btn-outline-dark"><span class="badge"><i class="fa fa-picture-o fa-2x" aria-hidden="true"></i> Add an image</span></a>');
var $addVideoLink = $('<a href="#" class="btn btn-outline-dark"><span class="badge"><i class="fa fa-file-video-o fa-2x" aria-hidden="true"></i> Add a video</span></a>');

$(document).ready(function() {

  // add the "add an image" anchor
  $("#actions").append($addImageLink);
  // Source image prototype form
  var $imagePrototype = $("#image_prototype");
  $addImageLink.on('click', function(e) {
    e.preventDefault();
    // add a new tag form (see code block below)
    addImageForm($imagePrototype);
  });

  // add the "add a video" anchor
  $("#actions").append($addVideoLink);
  // Source video prototype form
  var $videoPrototype = $("#video_prototype");
  $addVideoLink.on('click', function(e) {
    e.preventDefault();
    // add a new tag form (see code block below)
    addVideoForm($videoPrototype);
  });

  // Remove image or video to the collection
  $(document).on('click', '.remove-card', function (e) {
    e.preventDefault();
    $(this).parents('.st_card').remove();
    return false;
  });
});

function addImageForm($imagePrototype) {
  var prototype = $imagePrototype.data('prototype');
  var index = $imagePrototype.data('index');
  var newForm = prototype.replace(/__name__/g, index);
  $imagePrototype.data('index', index + 1);
  // Create view form
  var $newFormImage = $('<div class="st_card image"><img src="/web/img/image-not-found.png" alt="image-not-found" class="ing-fluid" /><a class="trash btn btn-sm btn-light" role="button" href="#"><i class="remove-card fa fa-trash-o" aria-hidden="true"></i></a><div class="input_file-container">'+ newForm + '</div></div>');
  // Display the form in the page
  $(".cards").append($newFormImage);
}

function addVideoForm($videoPrototype) {
  var prototype = $videoPrototype.data('prototype');
  var index = $videoPrototype.data('index');
  var newForm = prototype.replace(/__name__/g, index);
  $videoPrototype.data('index', index + 1);
  // Create view form
  var $newFormVideo = $('<div class="st_card video">' +
    '<div class="embed-responsive embed-responsive-16by9 w-100"></div>' +
    '<a class="trash btn btn-sm btn-light" role="button" href="#">' +
    '<i class="remove-card fa fa-trash-o" aria-hidden="true"></i>' +
    '</a>' +
    '<div>' +
    '<div class="input-group">' +
    '<div class="input-group-prepend">' +
    '<div class="input-group-text"><i class="fa fa-external-link-square" aria-hidden="true"></i>' +
    '</div>' +
    '</div>'+ newForm +
    '</div>');
  // Display the form in the page
  $(".cards").append($newFormVideo);
}
