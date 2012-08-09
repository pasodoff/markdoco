/*
* Markdoco helpers
*/

function getArticle(location) {

	
	//populate the raw md file in the edit tab
	getMarkdown(location);
	
	//get markdown HTML formated version for content tab
	getMarkdownHTML(location);
	
	getDirectory(location)
	// get directory listing
	
	CURRENTLOCATION = location;
	
}

/*
* Function retrieves raw md file
*/
function getMarkdown(location) {	
	
	$.ajax({
	   async: false,
	   type: 'GET',
	   url: '/markdoco/a/down/' + location+"?pseudoParam=" +new Date().getTime(),
	   success: function(data) {
			//get markdown HTML formated version for content tab
			$('#wmd-input').val(data);
		}
	});
}

/*
* Function retrieves md file parsed into HTML format
*/
function getMarkdownHTML(location) {
	
	
	$.ajax({
	   async: false,
	   type: 'GET',
	   url: '/markdoco/a/up/'+location,
	   success: function(data) {
			//get markdown HTML formated version for content tab
			$("#wiki_content").html(data);

			//apply highlight javascript to pre code blocks
			$('pre code').each(function(i, e) {hljs.highlightBlock(e)}); 
		}
	});
}

/*
* Function retrieves directory listing for location
* expects location is directory
*/
function getDirectory(location) {

	//get current directory
	location = location.substring(0, location.lastIndexOf("/"));
	//clean up double '//'
	location = location.replace(/\/\//g,'/');	
	
	parentDir = location.substring(0,location.lastIndexOf("/",(location.length -1)));
	
	var transition = true;
	var navDirection = "down";
	
	if ( 	JUSTARRIVED || 
			location.length == CURRENTDIRECTORY.length ) {
		var transition = false;
	}
	
	// check if we are going up a directory
	if (location.length < CURRENTDIRECTORY.length) {
		navDirection = "up";
	}

	
	$.ajax({
	   async: false,
	   type: 'GET',
	   url: DOCSLOCATION + location,
	   success: function(data) {
			//clean up apache directory listing:
			data = data.substring(data.indexOf("<ul>"),data.lastIndexOf("</ul>"));
			
			data = data.replace(/href="/g,'href="#'+location+'/');
			
			// replace page up list item
			data = data.replace(/<ul>(.*)<\/li>/g,
								'<ul><li><a href="#'+parentDir+'/index.md">..</a><//li>');
			// remove .md extension
			data = data.replace(/.md</g,'<');
			
			if (transition) {
				if (navDirection == "down") {
					$("#nav").hide("slide",{ direction: "left" }, 200);
					$("#nav").html('<h3 id="logo">Skeleton</h3>'+ data);
					$("#nav").show("slide",{ direction: "right" }, 200);
				} else {
					$("#nav").hide("slide",{ direction: "right" }, 200);
					$("#nav").html('<h3 id="logo">Skeleton</h3>'+ data);
					$("#nav").show("slide",{ direction: "left" }, 200);
				}
			} else {
				$("#nav").html('<h3 id="logo">Skeleton</h3>'+ data);
			}
			CURRENTDIRECTORY = location;
			JUSTARRIVED = false;
		 }
	});
}

/*
* Save
*
*/
function setArticle(location) {
	
	var data = "data="+$("#wmd-input").val();

	data = data + "&file="+location;
	
	
	$.ajax({
	   async: false,
	   type: 'POST',
	   data: data,
	   url: "/markdoco/a/down/",
	   success: function(data) {
			
			if (data == "") {
				file = location;
				file = file.replace(/%20/g," ");
				file = file.substring(file.lastIndexOf("/",file.length))
				file = file.replace(/\//," ");
				file = file.replace(/\.md/,"");
				
				var msg = file +" saved successfully";
				$("#save-msg").html(msg);
				$("#save-msg").show("slide",{ direction: "down" }, 2000);
				getArticle(location);
				$("#save-msg").hide("slide",{ direction: "down" }, 2000);			
			} else {
				
				var msg = "Bugger: " + data;
				$("#save-msg").html(msg);
				$("#save-msg").show("slide",{ direction: "down" }, 2000);
				$("#save-msg").hide("slide",{ direction: "down" }, 2000);			
			}
		}
	});
}