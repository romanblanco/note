	Mousetrap.bind('g h', function() {
		window.location.replace("./index.php");
	});
	Mousetrap.bind('/', function() {
		$("input[name='search']").focus();
	});
	Mousetrap.bind('n', function() {
		$("input[name='topic']").focus();
	});
	Mousetrap.bind('^', function() {
		$("html, body").animate({ scrollTop: 0 }, "slow");
	});
