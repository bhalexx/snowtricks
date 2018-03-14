$(document).ready(function() {
	/**
	 * Pictures drag and drop management
	 */
	
	var fileContainer = $('.file-container'),
		droparea = fileContainer.find('.droparea'),
		listPictures = droparea.find('.list-pictures');

	var readFileImage = function (file) {
		if (file) {
			var reader = new FileReader();

			reader.onload = function (e) {
				fileContainer.addClass('filled');
				listPictures.append('<div style="background-image: url(' + e.target.result + ')"></div>');
			};

			reader.readAsDataURL(file);
		} else {
			fileContainer.removeClass('filled');
			listPictures.empty();
		}
	};

	// On file dragenter event
	$('input[type="file"]').on('dragenter', function () {
		$(this).parent().addClass('active');
	});

	// On file dragleave/blur/drop event
	$('input[type="file"]').on('dragleave blur drop', function () {
		$(this).parent().removeClass('active');
	});

	// On file input image change
	$('input[type="file"]').on('change', function() {
		listPictures.empty();
		var files = this.files;
		Object.keys(files).map(function(objectKey, index) {
		    var value = files[objectKey];
		    readFileImage(value);
		});
	});

	//Check if fileContainer is already filled on init
	if (listPictures.children().length > 0) {
		fileContainer.addClass('filled');
	}

	//On profile picture remove
	listPictures.on('click', '.overlay', function() {
		//Get ajax request path
		var url = $(this).data('path'),
			picture = $(this).parent();
		//Ajax request
		$.ajax({
	        url: url,
	        type: "POST",
	        dataType: "json",
	        async: true,
	        success: function (data) {
	            picture.remove();
	            fileContainer.removeClass('filled');
	        },
	        error: function(data) {
	        	console.log(data);
	        }
	    });
    });
});