/* changing content ***********************************************/

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

/* sildemenu **************************************************************/

(function() {
	let nav = document.querySelector("nav");
	menu_trigger = document.getElementsByClassName('menu-trigger')[0];

	if ( typeof menu_trigger !== 'undefined' ) {
		menu_trigger.addEventListener('click', function() {
			nav.className = ( nav.className == 'menu-active' )? '' : 'menu-active';
			console.log("open-slider");
		});
	}


}).call(this);


/************************************************/



















