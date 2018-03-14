$(document).ready(function() {
	/**
	 * Videos management
	 */
	var videoCount = $('#trick_videos').find('input').length;
	//Adds a video field
	var addVideoField = function (container) {
		if (!videoCount) {
	    	videoCount = container.children().length;
	    }
	    var prototype = container.attr('data-prototype');
	    var item = prototype.replace(/__name__/g, videoCount);
	    
	    container.append(item);
	    
	    videoCount++;
	};

	//On "add" button click
	$(document).on('click', '.collection-add', function () {
	    var $collectionContainer = $('#' + $(this).data('collection'));
	    addVideoField($collectionContainer);
	});

	//Add an empty video field if none existing
	if ($('.collection-add').length > 0 && videoCount === 0) {
		addVideoField($('#trick_videos'));
	}

	// Toggle videos help
    $('#showHelp').click(function () {
    	$('#help').toggleClass('shown well');
    });

    // On "delete" button click
    $('#trick_videos').on('click', '.collection-remove', function () {
    	$(this).parent().parent().remove();
    });

	//On remove picture button click
	$('.pictures').find('.picture').click(function () {
		//Get ajax request path
		var url = $(this).find('.remove-picture').data('path'),
			picture = $(this);
		//Ajax request
		$.ajax({
	        url: url,
	        type: "POST",
	        dataType: "json",
	        async: true,
	        success: function (data) {
	            picture.remove();
	        },
	        error: function(data) {
	        	console.log(data);
	        }
	    });
	});
});
