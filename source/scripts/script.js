/* changing content ***********************************************/

// find all navigation points
const categories = document.querySelectorAll("nav ul li");
const nav = document.querySelector("nav");
const main = document.querySelector("main");

// loop throughout ...
for( let i=0; i<categories.length; i++ ) {
	let thatCategorie = categories[i];
	//... to add an event
	categories[i].addEventListener("click", function lookCat(thatCategorie){
		// get all sliding content ...
		let content = document.querySelectorAll(".slider");
		for( let j=0; j<content.length; j++ ) {
			// and compare navigation-class and content-ids
			if(this.className == content[j].id) {
				// success, activate content
				content[j].className = "slider active";
			} else {
				// false, disable content
				content[j].className = "slider disabled";
			}
		}


		nav.className = (nav.className == 'menu-active') ? '' : 'menu-active';
		main.className = (main.className == 'blur') ? '' : 'blur';

	});
}

/* animate mobile navigation ***********************************************/
const trigger = document.querySelector(".menu-trigger");

trigger.addEventListener("click", doSlider);

function doSlider(){
	nav.className = (nav.className == 'menu-active') ? '' : 'menu-active';
	main.className = (main.className == 'blur') ? '' : 'blur';
};


/* ***********************************************/














