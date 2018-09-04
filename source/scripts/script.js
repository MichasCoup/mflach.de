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
	doSlider();
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
	doSlider();
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
	doSlider();
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
	doSlider();
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
	doSlider();
}



/************************************************/
const trigger = document.querySelector(".menu-trigger");
const nav = document.querySelector("nav");
const content = document.querySelector("main");

trigger.addEventListener("click", doSlider);

function doSlider(){
	nav.className = (nav.className == 'menu-active') ? '' : 'menu-active';
	content.className = (content.className == 'blur') ? '' : 'blur';
	console.log("doSlider()");
};

















