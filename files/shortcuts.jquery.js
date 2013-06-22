$(document).ready(function(){
	$(document).keypress(function(event) {
		var tag = event.target.tagName.toLowerCase();
		if (tag != 'input' && tag != 'textarea') {
			switch (event.keyCode) {
				case 110: $("input[name='topic']").focus(); break;
				case 47: $("input[name='search']").focus(); break;
				case 94: $("html, body").animate({ scrollTop: 0 }, "slow"); break;
			}
		}
	});
});

function notify() {
	$(".notify").fadeIn("slow");
	setTimeout(function() {
		$(".success").fadeOut("slow");
	}, 4000);
	$(".notify").click(function () {
		$(".notify").fadeOut(200);
	});
}
