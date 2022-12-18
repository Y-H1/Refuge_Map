$(function(){
 
//メニュー
//SET
if($(".Header__btn").css("display") == "block") {
    windowWidth = $(window).width();
    $(".Globalnav").css({"margin-left":windowWidth, "width":windowWidth,"display":"block"});
}
//risize
var setwidth = $(window).width();
var timer = false;
$(window).resize(function() {
    if (timer !== false) {
        clearTimeout(timer);
    }
    timer = setTimeout(function() {
    var ww = $(window).width();
    windowWidth = $(window).width();
    if(windowWidth == setwidth){
    }else if(windowWidth<640){
    $(".Header__btn p").addClass("close").removeClass("open");
    $(".Header__btn p img").attr("src","common/img/icon_menu.png");
    $(".Globalnav").css({"margin-left":windowWidth, "width":windowWidth,"display":"block"});
    }else{
    $(".Globalnav").css({"margin":"0 auto", "width":950});
    };
    }, 50);
});
 
//orientationchange
$(function(){
var timer = false;
    //$(window).on("orientationchange", function() {
    $(window).on("orientationchange", function() {
    if(timer !== false){
        clearTimeout(timer);
    }
    timer = setTimeout(function() {
    //window.onorientationchange = function(){
    windowWidth = $(window).width();
    //alert(windowWidth);
    if(windowWidth<640){
    $(".Header__btn p").addClass("close").removeClass("open");
    $(".Header__btn p img").attr("src","common/img/icon_menu.png");
    $(".Globalnav").css({"margin-left":windowWidth, "width":windowWidth,"display":"block"});
    }else{
    $(".Globalnav").css({"margin":"0 auto", "width":950});
    }
    }, 1);
    });
});
 
//click
  $(".Header__btn p").click(function() {
if($(this).hasClass("open")) {
    $(this).addClass("close").removeClass("open");
    //var windowWidth = $(window).width();
    $(".Header__btn p img").attr("src","common/img/icon_menu.png");
    $(".Globalnav").animate({"marginLeft":windowWidth+"px"},500);
    //alert(windowWidth);
} else {
    $(this).addClass("open").removeClass("close");
    $(".Header__btn p img").attr("src","common/img/icon_menu-close.png");
    $(".Globalnav").animate({"marginLeft":"0px"},500);
}
 
});
 
});