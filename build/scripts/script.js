"use strict";

/* changing content ***********************************************/

// find all navigation points
var categories = document.querySelectorAll("nav ul li");
var nav = document.querySelector("nav");
var main = document.querySelector("main");

// loop throughout ...
for (var i = 0; i < categories.length; i++) {
    var thatCategorie = categories[i];
    //... to add an event
    categories[i].addEventListener("click", function lookCat(thatCategorie) {
        // get all sliding content ...
        var content = document.querySelectorAll(".slider");
        for (var j = 0; j < content.length; j++) {
            // and compare navigation-class and content-ids
            if (this.className == content[j].id) {
                // success, activate content
                content[j].className = "slider active";
            } else {
                // false, disable content
                content[j].className = "slider disabled";
            }
        }

        nav.className = nav.className == 'menu-active' ? '' : 'menu-active';
        main.className = main.className == 'blur' ? '' : 'blur';
    });
}

/* animate mobile navigation ***********************************************/
var trigger = document.querySelector(".menu-trigger");

trigger.addEventListener("click", doSlider);

function doSlider() {
    nav.className = nav.className == 'menu-active' ? '' : 'menu-active';
    main.className = main.className == 'blur' ? '' : 'blur';
}

/* form validation *********************************************************/

window.onload = function () {
    console.log("Validierungs-Funktion geladen");

    var form = document.getElementsByTagName('form')[0];
    var firstname = document.getElementsByName('firstname')[0];
    var lastname = document.getElementsByName('lastname')[0];
    var email = document.getElementsByName('email')[0];
    var message = document.getElementsByName('message')[0];
    var phone = document.getElementsByName('phone')[0];
    var error = document.querySelectorAll('.error');
    var nameREGEX = /^(?=.{2,50}$)[a-zßäöüA-Z]+(?:['-_.\s][a-zßäöüA-Z]+)*$/i;
    var emailREGEX = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
    var phoneREGEX = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;

    firstname.addEventListener("focusout", function () {
        console.log("focusout firstname");
        if (firstname.value == "") {
            error[1].innerHTML = "Dies ist ein Pflichtfeld, mit Javascript!";
        } else if (!nameREGEX.test(this.value)) {
            console.log(this.id + ": " + this.value);
            error[1].innerHTML = "Bitte überprüfen Sie Ihre Eingabe";
        } else {
            console.log(this.id + ": " + this.value);
            error[1].innerHTML = "";
        }
    }, false);

    lastname.addEventListener("focusout", function () {
        console.log("focusout lastname");
        if (lastname.value == "") {
            error[2].innerHTML = "Dies ist ein Pflichtfeld, mit Javascript!";
        } else if (!nameREGEX.test(this.value)) {
            console.log(this.id + ": " + this.value);
            error[2].innerHTML = "Bitte überprüfen Sie Ihre Eingabe";
        } else {
            console.log(this.id + ": " + this.value);
            error[2].innerHTML = "";
        }
    }, false);

    email.addEventListener("focusout", function () {
        console.log("focusout email");
        if (email.value == "") {
            error[3].innerHTML = "Dies ist ein Pflichtfeld, mit Javascript!";
        } else if (!emailREGEX.test(this.value)) {
            console.log(this.id + ": " + this.value);
            error[3].innerHTML = "Das ist keine gültigte E-Mail Adresse";
        } else {
            console.log(this.id + ": " + this.value);
            error[3].innerHTML = "";
        }
    }, false);

    phone.addEventListener("focusout", function () {
        console.log("focusout phone");
        if (phone.value == "") {
            error[4].innerHTML = "Dies ist ein Pflichtfeld, mit Javascript!";
        } else if (!phoneREGEX.test(this.value)) {
            console.log(this.id + ": " + this.value);
            error[4].innerHTML = "Das ist keine gültige Telefonnummer";
        } else {
            console.log(this.id + ": " + this.value);
            error[4].innerHTML = "";
        }
    }, false);

    message.addEventListener("focusout", function () {
        console.log("focusout message");
        if (!message.validity.valid) {
            error[5].innerHTML = "Dies ist ein Pflichtfeld, mit Javascript!";
        } else {
            console.log(this.id + ": " + this.value);
            error[5].innerHTML = "";
        }
    }, false);

    form.addEventListener("submit", function (event) {
        if (!firstname || !lastname || !email || !phone || !message) {
            event.preventDefault();
        }
    });
};
/* ***********************************************/
