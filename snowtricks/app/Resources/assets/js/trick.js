var lightgallery = require("lightgallery");
var swal = require("sweetalert");

$(document).ready(function() {
	/**
	 * Pictures management: lightgallery
	 */
    $("#lightgallery").lightGallery(); 

    /**
     * Comments management
     */
    var page = 0,
    	isProcessing = false,
    	controller = $('#loadMore').data('controller'),
    	controller = controller + '/' + page;
    	$commentContainer = $('#commentsContainer'),
    	$button = $('#loadMore'),
        firstLoad = true;
    
    //On button click
    $button.click(function () {
    	loadComments();
    });

    //Loads comments
    var loadComments = function () {
    	toggleButtonState(true);

    	//Replace page value
    	controller = controller.replace(/[0-9]+$/, page);

    	//Ajax request
		$.ajax({
	        url: controller,
	        type: "GET",
	        dataType: "json",
	        async: true,
	        success: function (data) {
    			toggleButtonState(false);
	            $commentContainer.append(data.view);
	            page++;
	            //Remove button if last page
	            if (data.lastPage) {
	            	$button.remove();
	            }
                firstLoad = false;
	        },
	        error: function(data) {
    			toggleButtonState(false);
                if (!firstLoad) {
    	        	//Feedback
                    swal({
                        title: 'Oops',
                        text: 'Une erreur est survenue...',
                        icon: 'error',
                        button: {
                            className: 'btn-primary'
                        }
                    });                    
                }
                firstLoad = false;
	        }
	    });
    };

    // Toggles button state
    var toggleButtonState = function (processing) {
    	isProcessing = processing;

    	//Button CSS class
    	if (isProcessing) {
    		$button.addClass('processing');
    	} else {
    		$button.removeClass('processing');
    	}

    	//Disable button while processing
    	$button.attr('disabled', isProcessing);
    };

    //On edit comment option button click
    $commentContainer.on('click', '.options .btn-edit', function () {
        var $btn = $(this),
            url = $(this).data('path'),
            $parent = $(this).closest('.comment');

        $btn.addClass('processing');

        //Ajax request
        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            async: true,
            success: function (data) {
                $parent.html(data.view);
            },
            error: function(data) {
                $btn.removeClass('processing');
                //Feedback
                swal({
                    title: 'Oops',
                    text: 'Une erreur est survenue...',
                    icon: 'error',
                    button: {
                        className: 'btn-primary'
                    }
                });
            }
        });
    });

    //On edit comment submit    
    $commentContainer.on('submit', '.form-edit-comment', function (e) {
        e.preventDefault();

        var $btn = $(this).find('.btn-edit'),
            url = $('.form-edit-comment').attr('action'),
            $parent = $(this).closest('.comment'),
            data = $(this).serializeArray();

        $btn.addClass('processing');

        //Ajax request
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: data,
            async: true,
            success: function (data) {
                $parent.html(data.view);
                swal({
                    title: '',
                    text: 'Commentaire modifié !',
                    icon: 'success',
                    button: {
                        className: 'btn-primary'
                    }
                });
            },
            error: function(data) {
                $btn.removeClass('processing');
                //Feedback
                swal({
                    title: 'Oops',
                    text: 'Une erreur est survenue...',
                    icon: 'error',
                    button: {
                        className: 'btn-primary'
                    }
                });
            }
        });
    });

    //On comment delete button click
    $commentContainer.on('click', '.options .btn-delete', function () {
        var url = $(this).data('path'),
            $parent = $(this).closest('.comment');

        //Confirmation
        swal({
            title: 'Attention',
            text: 'Êtes-vous sûr(e) de vouloir supprimer ce commentaire ?',
            icon: 'error',
            buttons: {
                cancel: {
                    text: "Annuler",
                    value: null,
                    visible: true,
                    className: "",
                    closeModal: true,
                },
                confirm: {
                    text: "Oui, supprimer",
                    value: true,
                    visible: true,
                    className: "btn-danger",
                    closeModal: true
                }
            }
        })
        .then(function(confirm) {
            if (confirm) {
                //Ajax request
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    async: true,
                    success: function (data) {
                        if (data.status === 'OK') {
                            //Remove comment from list
                            $parent.remove();
                            //Feedback
                            swal({
                                title: '',
                                text: data.feedback,
                                icon: 'success',
                                button: {
                                    className: 'btn-primary'
                                }
                            });
                        } else {
                            //Feedback
                            swal({
                                title: 'Oops',
                                text: 'Une erreur est survenue...',
                                icon: 'error',
                                button: {
                                    className: 'btn-primary'
                                }
                            });
                        }                
                    },
                    error: function(data) {
                        //Feedback
                        swal({
                            title: 'Oops',
                            text: 'Une erreur est survenue...',
                            icon: 'error',
                            button: {
                                className: 'btn-primary'
                            }
                        });
                    }
                });
            }            
        });
    });

    loadComments();
});