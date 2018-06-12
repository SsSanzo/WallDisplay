function initiateNavigationmenu(){
	$(".headerIcon").click(function(){
		document.location = window.location.pathname + "?pt=" + $(this).attr("data-page");
	});
}

function initiateRssPopup(){
	$(".rssStory").click(function(){
		var content = $(this).find(".storyContent").html();
		$("body").append("<div class='rssPopupBackground'></div><div class='rssPopup'><a class='rssPopupClose'>Close</a>" + content + "</div>");
		$(".rssPopupClose").click(function(){
			$(".rssPopup").remove();
			$(".rssPopupBackground").remove();
		});
	});
}

$(document).ready(function(){
	//initiate stuff
	initiateNavigationmenu();
	initiateRssPopup();
});