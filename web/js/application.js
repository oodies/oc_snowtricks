/* ####################################
 *  SWAP HEAD / FIGURES HOMEPAGE
 */
var swap_head_figures = swap_head_figures || {};
(function (publics) {
  'use strict';

  /**
   * Privates function
   */
  var privates = {};

  privates.onClickFigures = function () {
    $("a[href='#figures']").click( function (event) {
      $("a[href='#head']").parent('nav').show();
    });
  }

  privates.onClickHead = function () {
    $("a[href='#head']").click( function (event) {
      $(this).parent('nav').hide();
    });
  }

  publics.init = function () {
    privates.onClickFigures();
    privates.onClickHead();
  }
} (swap_head_figures) );

/* ####################################
 *  IMAGE AND VIDEO UPLOAD
 */
var picture_upload = picture_upload || {};

(function (publics) {
  'use strict';

  /**
   * privates attributes
   */
  /** @type (null|jQuery) The ID input of the type file */
  var _inputFileField = null;
  /** @type (null|jQuery) The ID of the container DIV of the photo  */
  var _myPhoto = null;

  /**;
   * privates function
   */
  var privates = {};

  privates.initVar = function () {
    _inputFileField = $('.input_file');
  };

  /**
   *
   */
  privates.onChangeInputFile = function () {
    _inputFileField.change(function (event) {
      _myPhoto = $(this).parents('.image').find('img');
      var domInputFile = this;
      var oFileList = domInputFile.files;
      var oFile = oFileList[0];
      var oReader = new FileReader();
      oReader.onload = function (event) {
        _myPhoto.attr('src', event.target.result);
      };
      oReader.readAsDataURL(oFile);
      event.preventDefault();
    });
  };
  /**
   * Initializer
   */
  publics.init = function () {
    privates.initVar();
    privates.onChangeInputFile();
  };

}(picture_upload) );


/* ####################################
 *  IMAGE HANDLER
 */
var image_handler = image_handler || {};
(function (publics) {
  'use strict'

  var _addImageLink = null
  var _addVideoLink = null;
  // -----------------
  // Privates functions
  //
  var privates = {}

  /**
   *
   */
  privates.initVar = function() {
    _addImageLink = $('<a href="#" class="btn btn-outline-dark"><span class="badge"><i class="fa fa-picture-o fa-2x" aria-hidden="true"></i> Add an image</span></a>');
    _addVideoLink = $('<a href="#" class="btn btn-outline-dark"><span class="badge"><i class="fa fa-file-video-o fa-2x" aria-hidden="true"></i> Add a video</span></a>');
  }

  /**
   *
   * @param $imagePrototype
   */
  privates.addImageForm = function ($imagePrototype) {
    var prototype = $imagePrototype.data('prototype');
    var index = $imagePrototype.data('index');
    var newForm = prototype.replace(/__name__/g, index);
    $imagePrototype.data('index', index + 1);
    // Create view form
    var $newFormImage = $('<div class="card image">' +
      '<img src="/img/image-not-found.png" alt="image-not-found" class="card-img-top" />' +
      '<div class="card-footer">' +
      '<div class="btn-group f-flex">' +
      '<a class="trash btn btn-light" role="button" href="#">' +
      '<i class="remove-card fa fa-trash-o" aria-hidden="true"></i>' +
      '</a>' +
      '<div class="input_file-container w-100 flex-grow-1">' +
      newForm + '</div></div></div></div>');
    // Display the form in the page
    $(".cards").append($newFormImage);
    picture_upload.init();
  }

  /**
   *
   * @param $videoPrototype
   */
  privates.addVideoForm = function($videoPrototype) {
    var prototype = $videoPrototype.data('prototype');
    var index = $videoPrototype.data('index');
    var newForm = prototype.replace(/__name__/g, index);
    $videoPrototype.data('index', index + 1);
    // Create view form
    var $newFormVideo = $('<div class="card video">' +
      '<img src="/img/image-not-found.png" alt="image-not-found" class="card-img-top" />' +
      '<div class="card-footer">' +
      '<div class="btn-group d-flex">' +
      '<a class="trash btn btn-light" role="button" href="#">' +
      '<i class="remove-card fa fa-trash-o" aria-hidden="true"></i>' +
      '</a>' +
      '<div class="w-100 flew-grow-1">' + newForm + '</div></div></div></div>');
    // Display the form in the page
    $(".cards").append($newFormVideo);
    picture_upload.init();
  }

  publics.init = function () {

    privates.initVar();

    // add the "add an image" anchor
    $("#actions").append(_addImageLink);
    // Source image prototype form
    var _imagePrototype = $("#image_prototype");
    _addImageLink.on('click', function(e) {
      e.preventDefault();
      // add a new tag form (see code block below)
      privates.addImageForm(_imagePrototype);
    });

    // add the "add a video" anchor
    $("#actions").append(_addVideoLink);
    // Source video prototype form
    var _videoPrototype = $("#video_prototype");
    _addVideoLink.on('click', function(e) {
      e.preventDefault();
      // add a new tag form (see code block below)
      privates.addVideoForm(_videoPrototype);
    });

    // Remove image or video to the collection
    $(document).on('click', '.remove-card', function (e) {
      e.preventDefault();
      $(this).parents('.card').remove();
      return false;
    });

    picture_upload.init();
  }
} (image_handler));

/* ####################################
 *  INFINITE SCROLL FOR POSTS LIST
 */
var infinite_scroll = infinite_scroll || {};
(function (publics) {
  'use strict'

  // ----------------------
  // PRIVATE MEMBERS
  // ----------------------

  /** @type (null|jQuery|HTMLElement) */
  var _infiniteScrollBtn = null
  /** @type {string} */
  var _postsListSelector = null
  /** @type {null|jQuery|HTMLElement} */
  var _page = null
  /** @type (null|jQuery|HTMLElement} */
  var _searchPostsForm = null

  // -----------------
  // Privates functions
  //
  var privates = {}

  privates.initVar = function () {
    _infiniteScrollBtn = $('.js-infiniteScroll')
    _postsListSelector = '.postsList'

    _searchPostsForm = $('#searchPostsForm')
  }

  /**
   * Handler pagination
   *
   * @private
   */
  privates.infiniteScroll = function () {

    /** @var Number _totalPosts Number total of posts to display*/
    var totalPosts = parseInt(_infiniteScrollBtn.data('total'))
    /** @var Number _itemByPage Maximum number of results from index */
    var itemPerPage = parseInt(_infiniteScrollBtn.data('itemByPage'))

    var restOfPosts = totalPosts - (parseInt(_page.val()) * itemPerPage)
    var nextItems = (restOfPosts > itemPerPage) ? itemPerPage : restOfPosts

    if (restOfPosts <= 0) {
      _infiniteScrollBtn.hide()
    } else {
      _infiniteScrollBtn.attr('data-restof', restOfPosts)
      $('#numberItemNext').text(nextItems)
    }
  }

  privates.onClick = function () {
    _infiniteScrollBtn.click(function (event) {
      _page = $('#searchPostsForm_page')
      // increment number page
      _page.val(parseInt(_page.val()) + 1)
      var vUrl = jQuery(this).attr('href')
      var data = _searchPostsForm.serialize()
      var jqXHR =$.post(vUrl, data)
      jqXHR.done(function (response) {
        privates.infiniteScroll()
        $(response).appendTo(_postsListSelector)
      }, 'html')
      event.preventDefault()
    })
  }

  publics.init = function () {
    privates.initVar()
    privates.onClick()
  }
}(infinite_scroll));


/* ####################################
 *  POST REMOVE
 */
var post_remove = post_remove || {};
(function (publics) {
    'use strict'

    /** @type {string} */
    var _linkRemovePostSelector = null;

    // -----------------
    // Privates functions
    //
    var privates = {};

    /**
     * Init var
     */
    privates.initVar = function () {
      _linkRemovePostSelector = '.js-remove-post';
    }

    /**
     * LinkRemovePost onClick Event
     */
    privates.onClick_linkRemovePost = function () {
      $(document).on('click', _linkRemovePostSelector , function (e) {
        e.preventDefault();

        var oConfirm = confirm("voulez-vous supprimer cette article ?");

        if (oConfirm === true) {
          var scope = $(this);
          var url = scope.attr('href');
          var jqXHR = $.get(url);

          jqXHR.done(function (response) {
            scope.parents('.card').remove();
          });
        }
        return false;
      });
    }

    publics.init = function() {
      privates.initVar();
      privates.onClick_linkRemovePost();
    }
  }(post_remove)
);


/* ####################################
 *  INFINITE SCROLL FORM COMMENTS
 */
var comments_infinite_scroll = comments_infinite_scroll || {};
(function (publics) {
  'use strict'

  // ----------------------
  // PRIVATE MEMBERS
  // ----------------------

  /** @type (null|jQuery|HTMLElement) */
  var _infiniteScrollBtn = null
  /** @type {string} */
  var _commentsListSelector = null
  /** @type {null|jQuery|HTMLElement} */
  var _page = null
  /** @type {null|jQuery|HTMLElement} */
  var _searchCommentsForm = null

  // -----------------
  // Privates functions
  //
  var privates = {}

  privates.initVar = function () {
    _infiniteScrollBtn = $('.js-infiniteScroll')
    _commentsListSelector = '#comments_list'
    _searchCommentsForm = $('#searchCommentsForm')
  }

  /**
   * Handler pagination
   *
   * @private
   */
  privates.infiniteScroll = function () {

    /** @var Number _totalComments Number total of comments to display*/
    var totalComments = parseInt(_infiniteScrollBtn.data('total'))
    /** @var Number _itemByPage Maximum number of results from index */
    var itemPerPage = parseInt(_infiniteScrollBtn.data('itemByPage'))

    var restOfComments = totalComments - (parseInt(_page.val())) * itemPerPage
    var nextItems = (restOfComments > itemPerPage) ? itemPerPage : restOfComments

    if (restOfComments <= 0) {
      _infiniteScrollBtn.hide();
    } else {
      _infiniteScrollBtn.attr('data-restof', restOfComments);
      $('#numberItemNext').text(nextItems);
    }
  }

  /**
   * @private
   */
  privates.onClickInfiniteScrollBtn = function (scope) {
    _page = $('#searchCommentsForm_page');
    // increment number page
    _page.val(parseInt(_page.val()) + 1);
    var vUrl = scope.attr('href');
    var data = _searchCommentsForm.serialize();
    var jqXHR = $.post(vUrl, data);
    jqXHR.done(function (response) {
      privates.infiniteScroll()
      $(response).appendTo(_commentsListSelector)
    }, 'html');
  };

  /**
   * Init
   *
   * @public
   */
  publics.init = function () {
    privates.initVar();
    _infiniteScrollBtn.unbind('click').click ( function (event) {
      event.preventDefault();
      privates.onClickInfiniteScrollBtn($(this));
      return false;
    });
  }
}(comments_infinite_scroll));


/* ####################################
 *  ADD COMMENT
 */
var addComment = addComment || {};
(function (publics) {
  'use strict';

  // ------------------------
  // Privates attributes
  //
  /** @var type (jQuery|null) Id Form */
  var _form = null;
  /** @var type (jQuery|null) Id Comment body of the form */
  var _commentBody = null;
  /** @var type (jQuery|null) Id Submit form */
  var _submit = null;
  /** @var type (jQuery|null) Id Comment list wrapper */
  var _commentsList = null;

  // ----------------------
  // Privates functions
  //
  var privates = {};

  /**
   * Init privates attributes
   * @private
   */
  privates.initVar = function () {
    _form = $('#form_comment');
    _commentBody = $("#comment_body");
    _submit = $("#comment_submit");
    _commentsList = $("#comments_list");
  };

  /**
   * Submit add comment form
   * @private
   */
  privates.submitForm = function () {
    var jqXhr = $.ajax({
      url: _form.attr('action'),
      type: 'POST',
      data: _form.serialize()
    });

    // Done
    jqXhr.done(function(response) {
      if (response.hasError) {
        _form.replaceWith(response.form);
      } else {
        _commentsList.prepend(response);
        _commentBody.val('').empty();
      }
    });
  }

  /**
   * Init
   *
   * @public
   */
  publics.init = function () {
    privates.initVar();
    _submit.unbind('click').click ( function (event) {
      event.preventDefault();
      privates.submitForm();
      return false;
    });
  }
} (addComment) );
