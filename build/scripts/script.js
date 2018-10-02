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

/* form validation *********************************************************/

window.onload = function () {

    var firstname = document.getElementsByName('firstname')[0];
    var lastname = document.getElementsByName('lastname')[0];
    var email = document.getElementsByName('email')[0];
    var message = document.getElementsByName('message')[0];
    var phone = document.getElementsByName('phone')[0];
    var error = document.querySelectorAll('.error');
    var nameREGEX = /^(?=.{2,50}$)[a-zßäöüA-Z]+(?:['-.\s][a-zßäöüA-Z]+)*$/i;
    var emailREGEX = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/i;
    var phoneREGEX = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;

    firstname.addEventListener("focusout", function () {
        if (firstname.value === "") {
            error[0].innerHTML = "Dies ist ein Pflichtfeld";
        } else if (!nameREGEX.test(this.value)) {
            error[0].innerHTML = "Bitte überprüfen Sie Ihre Eingabe";
        } else {
            error[0].innerHTML = "";
        }
    }, false);

    lastname.addEventListener("focusout", function () {
        if (lastname.value === "") {
            error[1].innerHTML = "Dies ist ein Pflichtfeld";
        } else if (!nameREGEX.test(this.value)) {
            error[1].innerHTML = "Bitte überprüfen Sie Ihre Eingabe";
        } else {
            error[1].innerHTML = "";
        }
    }, false);

    email.addEventListener("focusout", function () {
        if (email.value === "") {
            error[2].innerHTML = "Dies ist ein Pflichtfeld";
        } else if (!emailREGEX.test(this.value)) {
            error[2].innerHTML = "Das ist keine gültigte E-Mail Adresse";
        } else {
            error[2].innerHTML = "";
        }
    }, false);

    phone.addEventListener("focusout", function () {
        if (phone.value === "") {
            error[3].innerHTML = "Dies ist ein Pflichtfeld";
        } else if (!phoneREGEX.test(this.value)) {
            error[3].innerHTML = "Das ist keine gültige Telefonnummer";
        } else {
            error[3].innerHTML = "";
        }
    }, false);

    message.addEventListener("focusout", function () {
        if (message.value === "") {
            error[4].innerHTML = "Dies ist ein Pflichtfeld";
        } else {
            error[4].innerHTML = "";
        }
    }, false);
};
/* portfolio lightbox ***********************************************/

var posts = document.querySelectorAll("article .post");
console.log(posts);

/* ***********************************************/
