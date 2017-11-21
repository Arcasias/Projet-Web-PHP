function setbulle() {	
	lft = $('#'+menu).offset().left - 10;
	wth = $('#'+menu).width() + 20;
	$('#bulle').stop().animate({
			left: lft,
			width: wth }, 150);
	$('#'+menu).css('color', '#771616');
}

$('body').ready( function() { setbulle(); });

$('.menu').mouseenter(
	function () {
		lft = $(this).offset().left - 10;
		wth = $(this).width() + 20;
		$('#bulle').stop().animate({
			left: lft,
			width: wth 
			}, 150);
		$('.menu').css({ 'color': '#FFFFFF' });
		$(this).css({ 'color': '#771616' });
	});
	
$('nav').mouseleave(
	function () {		
		$('.menu').css({ 'color': "#FFFFFF" });
		setbulle();
	});