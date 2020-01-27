$(document).ready(function() {
	
	/* scroll */
	
	$("a[href^='#']").click(function(){
		var targer = $(this).attr("href");
		var target_offset = $(targer).offset().top-$(".fixed_section").innerHeight();
		$("html, body").animate({scrollTop: target_offset+"px"});
		return false;
	});

	/* timer */

	function update() {
		var Now = new Date(), Finish = new Date();
		Finish.setHours( 23);
		Finish.setMinutes( 59);
		Finish.setSeconds( 59);
		if( Now.getHours() === 23  &&  Now.getMinutes() === 59  &&  Now.getSeconds === 59) {
			Finish.setDate( Finish.getDate() + 1);
		}
		var sec = Math.floor( ( Finish.getTime() - Now.getTime()) / 1000);
		var hrs = Math.floor( sec / 3600);
		sec -= hrs * 3600;
		var min = Math.floor( sec / 60);
		sec -= min * 60;
		$(".timer .hours").text( pad(hrs));
		$(".timer .minutes").text( pad(min));
		$(".timer .seconds").text( pad(sec));
		setTimeout( update, 200);
	}
	function pad(s) { return ("00"+s).substr(-2) }
	update();

	/* vote */

	var voice_count = $(".voice_count b").text().replace(/\D/g,"");
	$(".questions_list").one("click", function(){
		$(this).addClass("active");
		voice_count++;
		$(this).children().each(function(){
			var percents = parseInt($(this).find(".percents").text().replace(/\D/g,""), 10);
			$(this).find(".value").text((Math.round(voice_count * percents / 100)+"").replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, "$1 "));
            $(this).find(".line").animate({width: percents + "%"}, 700);
		});
		$(".voice_count b").text((voice_count+"").replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, "$1 "));
		$.cookie("voice_cookie", voice_count);
	});
	if ($.cookie("voice_cookie") != null) {
        voice_count = $.cookie("voice_cookie")-1;
        $(".questions_list").click();
    }

	/* validate form */

	$(".order_form").submit(function(){
		if ($(this).find("input[name='name']").val() == "" && $(this).find("input[name='phone']").val() == "") {
			alert("Введите Ваши имя и телефон");
			$(this).find("input[name='name']").focus();
			return false;
		}
		else if ($(this).find("input[name='name']").val() == "") {
			alert("Введите Ваше имя");
			$(this).find("input[name='name']").focus();
			return false;
		}
		else if ($(this).find("input[name='phone']").val() == "") {
			alert("Введите Ваш телефон");
			$(this).find("input[name='phone']").focus();
			return false;
		}
		return true;
	});

	/* sliders */

	$(".owl-carousel").owlCarousel({
		items: 1,
		loop: true,
		smartSpeed: 300,
		mouseDrag: false,
		pullDrag: false,
		dots: false,
		nav: true,
		navText: ""
	});
    
     $('.cat-item a').click(function (){
        var title = $(this).parent().find(".cat-title").text();
        var cena = $(this).parent().find(".cat-cost .new-cost").text();
        var itogo =  (title+' '+cena);
        $(".select-status").text(itogo); 
        $(".order_form input[name='comment']").val(itogo);
    });

	// -----------------------

// Contact Form Phone Mask
});