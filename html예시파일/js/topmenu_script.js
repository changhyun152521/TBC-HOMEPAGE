// top_nav
$(document).ready(function() {	
	$('#top_nav li').hover(function() {
		$('ul', this).stop().slideDown(200);
	}, function() {
		$('ul', this).slideUp(100);
	});

    m = 0;  
    function navClose() { 
        $("#pfBtn").removeClass("active");
        $("#allWrap").fadeOut(600).removeClass("active");	
    }
    $("#pfBtn").click(function(){
        m++;
        if(m%2 == 1){
            $("#pfBtn").addClass("active");
            $("#allWrap").fadeIn(600).addClass("active");	
        }else{
            navClose(); 
        }; 
    });

    $("#allWrap .mn_img>li").hover(function(){
        var num = $(this).index()+1;
        if($(".right_img img").data("num")!=num){
            $(".right_img img").css('opacity','0').stop().attr("src","img/common/all_bg0"+num+".jpg").animate({opacity:1},500).data("num",num);
        }
    })

	/* 반응형 [s] */
	$("#m_navBtn").click(function(){
		m++;
		if(m%2 == 1){
			$("#m_navBtn").addClass("on");
			$("#navWrap").fadeIn(300).addClass("on");
		}else{
			m_navClose(); 
		}; 
	});	
	$("#topmenuM .m_bmenu").click(function(){
		$('.m_smenu').not($(this).next()).slideUp(200);
		$('.m_bmenu').removeClass('on');
		$(this).addClass('on')
		$(this).next().slideDown(200);
	});	

	m = 0;  	
	function m_navClose() { 
		$("#m_navBtn").removeClass("on");
		$("#navWrap").fadeOut(300).removeClass("on");	
	}	
	/* 반응형 [e] */

});