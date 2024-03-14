$(document).ready(function(){navbar_menu();App();});function navbar_menu(){$(".icon").click(function(){if($(".items").is(":visible")){$(".items").removeClass("showitems");$(".menu_rectangle").removeClass("showitems");}
else{$(".items").addClass("showitems");$(".menu_rectangle").addClass("showitems");}});}
$(function Navbar_appendix(){$('.items li a').each(function(){var location=window.location.href
var link=this.href
var result=location.match(link);if(result!=null){$(this).addClass('select');}});});;