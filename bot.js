// Check if jQuery is in the page, and if not, inject it in the page
if(typeof jQuery === "undefined"){
	// Yes, you really need jQuery for this script to work
	var script = document.createElement('script'); 
	script.src = 'https://code.jquery.com/jquery-3.0.0.min.js'; // the URL to the jQuery library
	document.documentElement.firstChild.appendChild(script) // now append the script into HEAD, it will fetch and be executed
}

engine.on('msg', function(data) {
	message = data.message;
	if (message.indexOf("!cointrust") >= 0) {
		tokens = message.split(" ", 2);
		console.log(tokens);
		console.log(tokens[1]);
		$.ajax({
			dataType: "json",
			url: "https://cointrust.pw/wp-json/wp/v2/profile?slug=" + tokens[1],
			data1: data,
			success: function (data1){
				console.log(data1);
				array = data1[0];
				if(typeof array !== "undefined"){
					engine.chat("Username: "+ array.uname +" | Suspicion Level: " + array.suspicion + " | Read the full profile at: " + array.link);
				}else{
					
					engine.chat("User not found!");
				}
			}
		});
	}
});
