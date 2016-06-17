$(function(){
	$(".top_two ul li").click(function(){
		$(this).addClass("active").siblings().removeClass("active")
	})
	
	//无缝轮播
	var i = 0;
	var bannerClone =  $(".banner ul li").eq(0).clone();
	$(".banner ul").append(bannerClone);
	//配置
	$(".banner ul li").width($(window).width())
	$(".banner ul").width($(".banner ul li").eq(0).width() * $(".banner ul li").length)
	var aLi = $(".banner ul li").eq(0).width();
	var bannerLeng = $(".banner ul li").length;
	
	/* 动态添加ol li */
	for(var j =0 ;i<bannerLeng - 1; i++){
		$(".banner ol").append("<li></li>")
	}
	$(".banner ol li").first().addClass("active")
	
	//选项卡
	$(".banner ol li").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		i = $(this).index();
		$(".banner ul").animate({left:i * -aLi})
	})
	//两侧点击
	$(".banner a.right").click(function(){
		//alert(1)
		i++;
		if(i == bannerLeng){
			//i = 0;
			
			//无缝轮播图解决办法
			$(".banner ul").css({left:0})
			i = 1;
		}
		//无缝轮播图解决办法
		if(i == bannerLeng - 1){
			$(".banner ol li").eq(0).addClass("active").siblings().removeClass("active");
		}else{
			$(".banner ol li").eq(i).addClass("active").siblings().removeClass("active");
		}
		$(".banner ul").animate({left:i * -aLi})
	})
	$(".banner a.left").click(function(){
		i--;
		if(i == -1){
			//i = bannerLeng - 1;
			/*$(".box ul").css({left:-(aLi - 1) * 600});
			i = aLi - 2;*/
			$(".banner ul").css({left:-(bannerLeng - 1) * aLi})
			i = bannerLeng - 2
		}
		$(".banner ol li").eq(i).addClass("active").siblings().removeClass("active");
		$(".banner ul").animate({left:i * -aLi})
	})
	
	//定时器自动播放
	bannerTime = setInterval(move,3000)
	function move(){
		i++;
		if(i == bannerLeng){
			//i = 0;
			
			//无缝轮播图解决办法
			$(".banner ul").css({left:0})
			i = 1;
		}
		if(i == bannerLeng - 1){
			$(".banner ol li").eq(0).addClass("active").siblings().removeClass("active");
		}else{
			$(".banner ol li").eq(i).addClass("active").siblings().removeClass("active");
		}
		$(".banner ul").animate({left:i * -aLi})
	}
	
	//定时器的控制
	$(".banner").hover(function(){
		clearInterval(bannerTime)	
	},function(){
		 bannerTime = setInterval(move,3000)
	})
	
	//产品页动画
	$(window).scroll(function(){
		//alert(1)	
		//alert($("#vis1").offset().top)	//580
		if($(window).scrollTop() >= $("#vis1").offset().top - 400){
			$(".visiblex").addClass("visible1")
		}
		//alert($(".visibley").offset().top)	//1080
		if($(window).scrollTop() >= $("#vis2").offset().top -400){
			$(".visibley").addClass("visible2")
		}
		//alert($(".visibley").offset().top)	//1080
		if($(window).scrollTop() >= $("#vis3").offset().top -400){
			$(".visiblez").addClass("visible3")
		}
	})
})
