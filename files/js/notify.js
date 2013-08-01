function notify() {
	$(".notify").fadeIn("slow");
	setTimeout(function() {
		$(".success").fadeOut("slow");
	}, 4000);
	$(".notify").click(function () {
		$(".notify").fadeOut(200);
	});
}