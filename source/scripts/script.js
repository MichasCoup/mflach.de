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

/* hard coded version for the changing content
const start = document.querySelector(".start").addEventListener("click", showStart);
const design = document.querySelector(".design").addEventListener("click", showDesign);
const coding = document.querySelector(".coding").addEventListener("click", showCoding);
const about = document.querySelector(".about").addEventListener("click", showAbout);
const contact = document.querySelector(".contact").addEventListener("click", showContact);

function showStart() {
	let sliderStart = document.querySelector(".sliderStart");
	let sliderDesign = document.querySelector(".sliderDesign");
	let sliderCoding = document.querySelector(".sliderCoding");
	let sliderAbout = document.querySelector(".sliderAbout");
	let sliderContact = document.querySelector(".sliderContact");

	sliderStart.className = "sliderStart active";
	sliderDesign.className = "sliderDesign disabled";
	sliderCoding.className = "sliderCoding disabled";
	sliderAbout.className = "sliderAbout disabled";
	sliderContact.className = "sliderContact disabled";

	console.log("showStart");
}

function showDesign() {
	let sliderStart = document.querySelector(".sliderStart");
	let sliderDesign = document.querySelector(".sliderDesign");
	let sliderCoding = document.querySelector(".sliderCoding");
	let sliderAbout = document.querySelector(".sliderAbout");
	let sliderContact = document.querySelector(".sliderContact");

	sliderStart.className = "sliderStart disabled";
	sliderDesign.className = "sliderDesign active";
	sliderCoding.className = "sliderCoding disabled";
	sliderAbout.className = "sliderAbout disabled";
	sliderContact.className = "sliderContact disabled";

	console.log("showDesign");
}

function showCoding() {
	let sliderStart = document.querySelector(".sliderStart");
	let sliderDesign = document.querySelector(".sliderDesign");
	let sliderCoding = document.querySelector(".sliderCoding");
	let sliderAbout = document.querySelector(".sliderAbout");
	let sliderContact = document.querySelector(".sliderContact");

	sliderStart.className = "sliderStart disabled";
	sliderDesign.className = "sliderDesign disabled";
	sliderCoding.className = "sliderCoding active";
	sliderAbout.className = "sliderAbout disabled";
	sliderContact.className = "sliderContact disabled";

	console.log("showCoding");
}

function showAbout() {
	let sliderStart = document.querySelector(".sliderStart");
	let sliderDesign = document.querySelector(".sliderDesign");
	let sliderCoding = document.querySelector(".sliderCoding");
	let sliderAbout = document.querySelector(".sliderAbout");
	let sliderContact = document.querySelector(".sliderContact");

	sliderStart.className = "sliderStart disabled";
	sliderDesign.className = "sliderDesign disabled";
	sliderCoding.className = "sliderCoding disabled";
	sliderAbout.className = "sliderAbout active";
	sliderContact.className = "sliderContact disabled";

	console.log("showAbout");
}

function showContact() {
	let sliderStart = document.querySelector(".sliderStart");
	let sliderDesign = document.querySelector(".sliderDesign");
	let sliderCoding = document.querySelector(".sliderCoding");
	let sliderAbout = document.querySelector(".sliderAbout");
	let sliderContact = document.querySelector(".sliderContact");

	sliderStart.className = "sliderStart disabled";
	sliderDesign.className = "sliderDesign disabled";
	sliderCoding.className = "sliderCoding disabled";
	sliderAbout.className = "sliderAbout disabled";
	sliderContact.className = "sliderContact active";

	console.log("showContact");
}



/* animate mobile navigation ***********************************************/
const trigger = document.querySelector(".menu-trigger");

trigger.addEventListener("click", doSlider);

function doSlider(){
	nav.className = (nav.className == 'menu-active') ? '' : 'menu-active';
	main.className = (main.className == 'blur') ? '' : 'blur';
};


/* ***********************************************/














