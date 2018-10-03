/* changing content ***********************************************/

// find all navigation points
const categories = document.querySelectorAll("nav ul li");
const nav = document.querySelector("nav");
const main = document.querySelector("main");

// loop throughout ...
for( let i=0; i<categories.length; i++ ) {
	//let thatCategorie = categories[i];
	//... to add an event
	categories[i].addEventListener("click", function lookCat(){
		// get all sliding content ...
		let content = document.querySelectorAll(".slider");
		for( let j=0; j<content.length; j++ ) {
			// and compare navigation-class and content-ids
			if(this.className === content[j].id) {
				// success, activate content
				content[j].className = "slider active";
			} else {
				// false, disable content
				content[j].className = "slider disabled";
			}
		}

		nav.className = (nav.className === 'menu-active') ? '' : 'menu-active';
		main.className = (main.className === 'blur') ? '' : 'blur';

	});
}

/* animate mobile navigation ***********************************************/
const trigger = document.querySelector(".menu-trigger");

trigger.addEventListener("click", doSlider);

function doSlider(){
	nav.className = (nav.className === 'menu-active') ? '' : 'menu-active';
	main.className = (main.className === 'blur') ? '' : 'blur';
}


/* portfolio lightbox ***********************************************/

const posts = document.querySelectorAll("article .post");
console.log(posts);

for(let i=0; i<posts.length; i++) {
	posts[i].addEventListener("click", function() {
		let img = posts[i].querySelector("figure").firstElementChild.attributes;
		let title = posts[i].innerText;
		let section = posts[i].querySelector("h4").innerText;
		let description = posts[i].querySelector("p").innerText;

		console.log(img[0], title, section, description);

		let showPost = document.createElement("div");
		let postContent = document.createElement("div");
		let imgContainer = document.createElement("div");
		let bigimg = document.createElement("div");
		let imgs = document.createElement("div");
		let textContainer = document.createElement("div");

		showPost.className = "showPost";
		postContent.className ="postContent";
		imgContainer.className ="imgContainer";
		bigimg.className ="bigimg";
		imgs.className ="imgs";
		textContainer.className ="textContainer";

		document.body.appendChild(showPost);
		showPost.appendChild(postContent);
		postContent.appendChild(imgContainer);
		postContent.appendChild(textContainer);
		imgContainer.appendChild(bigimg);
		imgContainer.appendChild(imgs);
		bigimg.innerHTML = "<img src='" + img[0].value + "' alt='"+ img[1].value +"'>";
		textContainer.innerHTML = "<h2>"+ title +"</h2><h4>"+section+"</h4><p>"+description+"</p>";
		postContent.innerHTML += "<div class=\"closeIcon\">&times;</div>";

		let closeIcon = document.querySelector(".closeIcon");
		closeIcon.addEventListener("click", function closePost() {
				if (showPost.parentNode) {
					showPost.parentNode.removeChild(showPost);
				}
			})
	});
}



/*

*/



/* ***********************************************/














