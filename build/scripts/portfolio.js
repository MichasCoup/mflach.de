"use strict";

/* changing content ***********************************************/

// find all navigation points
var categories = document.querySelectorAll("nav ul li");
var nav = document.querySelector("nav");
var main = document.querySelector("main");

// loop throughout ...
for (var i = 0; i < categories.length; i++) {
	//let thatCategorie = categories[i];
	//... to add an event
	categories[i].addEventListener("click", function lookCat() {
		// get all sliding content ...
		var content = document.querySelectorAll(".slider");
		for (var j = 0; j < content.length; j++) {
			// and compare navigation-class and content-ids
			if (this.className === content[j].id) {
				// success, activate content
				content[j].className = "slider active";
			} else {
				// false, disable content
				content[j].className = "slider disabled";
			}
		}

		nav.className = nav.className === 'menu-active' ? '' : 'menu-active';
		main.className = main.className === 'blur' ? '' : 'blur';
	});
}

/* animate mobile navigation ***********************************************/
var trigger = document.querySelector(".menu-trigger");

trigger.addEventListener("click", doSlider);

function doSlider() {
	nav.className = nav.className === 'menu-active' ? '' : 'menu-active';
	main.className = main.className === 'blur' ? '' : 'blur';
}

/* portfolio lightbox ***********************************************/

var posts = document.querySelectorAll("article .post");
console.log(posts);

var _loop = function _loop(_i) {
	posts[_i].addEventListener("click", function () {
		var img = posts[_i].querySelector("figure").firstElementChild.attributes;
		var title = posts[_i].innerText;
		var section = posts[_i].querySelector("h4").innerText;
		var description = posts[_i].querySelector("p").innerText;

		console.log(img[0], title, section, description);

		var showPost = document.createElement("div");
		var postContent = document.createElement("div");
		var imgContainer = document.createElement("div");
		var bigimg = document.createElement("div");
		var imgs = document.createElement("div");
		var textContainer = document.createElement("div");

		showPost.className = "showPost";
		postContent.className = "postContent";
		imgContainer.className = "imgContainer";
		bigimg.className = "bigimg";
		imgs.className = "imgs";
		textContainer.className = "textContainer";

		document.body.appendChild(showPost);
		showPost.appendChild(postContent);
		postContent.appendChild(imgContainer);
		postContent.appendChild(textContainer);
		imgContainer.appendChild(bigimg);
		imgContainer.appendChild(imgs);
		bigimg.innerHTML = "<img src='" + img[0].value + "' alt='" + img[1].value + "'>";
		textContainer.innerHTML = "<h2>" + title + "</h2><h4>" + section + "</h4><p>" + description + "</p>";
		postContent.innerHTML += "<div class=\"closeIcon\">&times;</div>";

		var closeIcon = document.querySelector(".closeIcon");
		closeIcon.addEventListener("click", function closePost() {
			if (showPost.parentNode) {
				showPost.parentNode.removeChild(showPost);
			}
		});
	});
};

for (var _i = 0; _i < posts.length; _i++) {
	_loop(_i);
}

/*

*/

/* ***********************************************/
