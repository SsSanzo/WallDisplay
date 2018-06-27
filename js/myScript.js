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

function checkBoxhandler(){
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
}

function initiateListFormColorChange(){
	$(".listForm input[type='color']").change(function(){
		$(".listSettings .header").css("color", $("#TextColor").val());
		$(".listSettings .header").css("background-color", $("#BackgroundColor").val());
	});
}

function initiateListFormSubmitButton(action){
	if(typeof action != "string"){
		return false;
	}
	$(".ListSettingsSubmit").click(function(){
		var listName = $(".listForm #name").val();
		var listTColor = $(".listForm #TextColor").val();
		var listBColor = $(".listForm #BackgroundColor").val();
		var listId = $(".listForm #id").val();
		var newURL = '?pt=' + GetURLParameter("pt") + "&lid=" + listId;
		$.ajax({
			url: 'ajax.php?pt=' + GetURLParameter("pt"),
			data : {
				id: listId,
				name: listName,
				TColor: listTColor,
				BColor: listBColor,
				aQuery: "addList",
				aSubQuery: action,
				page: GetURLParameter("pt")
			},
			success : function(data, textStatus){
				document.location = newURL;
			},
			error : function(data, textStatus){
				$(".listSettingsContainer").detach();
				alert("Could not update the list")
			}
		});
	});
}

function initiateLists(){
	//Navigation
	$(".list").click(function(){
		document.location = window.location.pathname + "?pt=" + GetURLParameter("pt") + "&lid=" + $(this).attr("data-id");
	});

	//check boxes
	$(".listItem input[type='checkbox']").change(checkBoxhandler);

	//Add Item
	$(".newItem button").click(function(){
		var itemText = $(".newItem input").val();
		var newOrder = $("#uncheckedItems").length + 1;
		$(".newItem input").val(null);
		$.ajax({
			url: 'ajax.php?pt=' + GetURLParameter("pt"),
			data : {
				id: GetURLParameter("lid"),
				aQuery: "addItem",
				page: GetURLParameter("pt"),
				name: itemText,
				order: newOrder
			},
			success : function(data, textStatus){
				$("#uncheckedItems").append(data);
				$(".listItem input[type='checkbox']").change(checkBoxhandler);
			}
		});
	});
	//purge List
	$("#listPurge").click(function (){
		var id = $(this).attr("data-list");
		$.ajax({
			url: 'ajax.php?pt=' + GetURLParameter("pt"),
			data : {
				id: id,
				aQuery: "purgeList",
				page: GetURLParameter("pt")
			},
			success : function(data, textStatus){
				$("#checkedItems").empty();
			}
		});
	});
	//archive List
	$("#archiveList").click(function (){
		var id = $(this).attr("data-list");
		var newURL = '?pt=' + GetURLParameter("pt");
		$.ajax({
			url: 'ajax.php?pt=' + GetURLParameter("pt"),
			data : {
				id: id,
				aQuery: "archiveList",
				page: GetURLParameter("pt")
			},
			success : function(data, textStatus){
				document.location = newURL;
			}
		});
	});

	//restore List
	$("#restoreList").click(function (){
		var id = $(this).attr("data-list");
		var newURL = '?pt=' + GetURLParameter("pt");
		$.ajax({
			url: 'ajax.php?pt=' + GetURLParameter("pt"),
			data : {
				id: id,
				aQuery: "restoreList",
				page: GetURLParameter("pt")
			},
			success : function(data, textStatus){
				document.location = newURL;
			}
		});
	});
	//Add a list
	$(".listAdd").click(function (){
		$.ajax({
			url: 'ajax.php?pt=' + GetURLParameter("pt"),
			data : {
				aQuery: "addList",
				aSubQuery: "open",
				page: GetURLParameter("pt")
			},
			success : function(data, textStatus){
				$("body").append(data);
				initiateListFormColorChange();
				initiateListFormSubmitButton("add");
			}
		});
	});
	//Settings List
	$("#listSettings").click(function (){
		$.ajax({
			url: 'ajax.php?pt=' + GetURLParameter("pt"),
			data : {
				id: GetURLParameter("lid"),
				aQuery: "addList",
				aSubQuery: "open",
				page: GetURLParameter("pt")
			},
			success : function(data, textStatus){
				$("body").append(data);
				initiateListFormColorChange();
				initiateListFormSubmitButton("update");
			}
		});
	});

}


$(document).ready(function(){
	//initiate stuff
	initiateNavigationmenu();
	initiateRssPopup();
	initiateLists();
});