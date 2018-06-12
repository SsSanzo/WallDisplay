function initiateNavigationmenu(){
		$(".headerIcon").click(function(){
			document.location = window.location.pathname + "?pt=" + $(this).attr("data-page");
		});
}

function initiateRssPopup(){

}

$(document).ready(function(){
	//initiate stuff
	initiateNavigationmenu();
	initiateRssPopup();
});