$(document).ready(function () {
	//Make groups checkboxes act like radio buttons...
	$checkboxes = $('input[name="st_user_edit[groups][]"');
	$checkboxes.click(function () {
		$checkboxes.prop('checked', false);
		$(this).prop('checked', true);
	});
});