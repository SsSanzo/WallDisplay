function GetURLParameter(sParam){
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++){
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam){
            return sParameterName[1];
        }
    }
}

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

function initiateLists(){
	$(".list").click(function(){
		document.location = window.location.pathname + "?pt=" + GetURLParameter("pt") + "&lid=" + $(this).attr("data-id");
	});
	$(".listItem input[type='checkbox']").change(function(e){
		var id = $(this).attr("data-id");
		var item = $(".listItem[data-id=" + id + "]");
		$.ajax({
			url: 'ajax.php?pt=' + GetURLParameter("pt"),
			data : {
				id: id,
				aQuery: "updateItem",
				page: GetURLParameter("pt"),
				checked: !item.hasClass("checked") ? "1": "0"
			}
		});
		if(item.hasClass("checked")){
			item.detach();
			item.toggleClass("checked");
			item.appendTo("#uncheckedItems");
		}else{
			item.detach();
			item.toggleClass("checked");
			item.appendTo("#checkedItems");
		}
	});

}


$(document).ready(function(){
	//initiate stuff
	initiateNavigationmenu();
	initiateRssPopup();
	initiateLists();
});