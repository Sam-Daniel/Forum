// funksjoner.js - inneholder alle hjelpefunksjoner for javascript

// sjekker inndata på registrering
function registrerSjekk() {
    var username = document.forms["registrerForm"]["user_name"].value;
    var mail = document.forms["registrerForm"]["user_email"].value;
    var password1 = document.forms["registrerForm"]["user_pass"].value;
    var password2 = document.forms["registrerForm"]["user_pass_check"].value;

    var error = "";

    // Ingen felt tomme
    if(username == "" || mail == "" || password1 == "" || password2 == "")
        error += "<p>Alle felt må fylles ut.</p>";

    // Brukernavn mellom 2-15 i lengde
    if(username.length < 2 || username.length > 15)
    {
        error += "Navnet må være mellom 2 og 15 tegn langt.";
    }

    // Må inneholde noe tekst
    if(!isNaN(username))
     {
        error += "<p>Navnet må inneholde tall og bokstaver.</p>";
     }

    // Kalle på funskjon som sjekker inntastet mail
    if(!isMail(mail))
        error += "<p>Ugyldig email</p>";

    // Passordlengde mellom 5 og 20
    if(password1.length < 5 || password1.length > 20)
        error += "<p>Passordet må være mellom 6 og 20 tegn.</p>";

    // Passordene må være like
    if(password1 != password2)
        error += "<p>Passordene matchet ikke.</p>";

    // Er ikke errorvariabelen tom, skriv ut melding og return false
    if(error != "")
    {
        var last = document.getElementById('last');
        last.innerHTML = error;
        return false;
    }
    else
        return true;
}

// funksjon som sjekker om inndata for kategori er korrekt
function kategoriSjekk() {
    var katNavn = document.forms["kategoriForm"]["cat_name"].value;
    var katDesc = document.forms["kategoriForm"]["cat_description"].value;
    var error = "";

    // Ingen felt tomme
    if(katNavn == "" || katDesc == "")
        error += "<p>Alle felt må fylles ut.</p>";

    // Brukernavn mellom 3-30 i lengde
    if(katNavn.length < 3 || username.length > 30)
    {
        error += "Navnet må være på mellom 3 og 30 tegn.";
    }

    // Må inneholde noe tekst
    if(!isNaN(katNavn) || !isNaN(katDesc))
     {
        error += "<p>Kategorinavn og beskrivelse må inneholde tekst.</p>";
     }

    // Er ikke errorvariabelen tom, skriv ut melding og return false
    if(error != "")
    {
        var last = document.getElementById('last');
        last.innerHTML = error;
        return false;
    }
    else
        return true;
}

// funksjon som sjekker om inndata for tråd er korrekt
function threadSjekk() {
    var threadNavn = document.forms["threadForm"]["topic_subject"].value;
    var threadContent = document.forms["threadForm"]["post_content"].value;

    var error = "";

    // Ingen felt tomme
    if(threadNavn == "" || threadContent == "")
        error += "<p>Alle felt må fylles ut.</p>";

    // Brukernavn mellom 2-15 i lengde
    if(threadNavn.length < 10 || username.length > 100)
    {
        error += "Tittelen må være mellom 10 og 100 tegn.";
    }

    if (threadContent < 10) {
        error += "Teksten må være minst 10 tegn.";
    }

    // Må inneholde noe tekst
    if(!isNaN(threadNavn) || !isNaN(threadContent))
     {
        error += "<p>Kategorinavn og beskrivelse må inneholde tekst.</p>";
     }

    // Er ikke errorvariabelen tom, skriv ut melding og return false
    if(error != "")
    {
        var last = document.getElementById('last');
        last.innerHTML = error;
        return false;
    }
    else
        return true;
}

// funksjon for å sjekke at inndata til reply er riktig
function replySjekk() {
    var postTekst = document.forms["replyForm"]["reply-content"].value;

    var error = "";

    // Ingen felt tomme
    if(postTekst == "") {
        error += "<p>Feltet må må fylles ut.</p>";
    }

    // Må inneholde noe tekst
    if(!isNaN(postTekst)) {
        error += "<p>Posten må inneholde noe tekst.</p>";
    }

    // Er ikke errorvariabelen tom, skriv ut melding og return false
    if(error != "") {
        var last = document.getElementById("last");
        last.innerHTML = error;
        return false;
        alert("Hei");
    }
    else {
        return true;
        alert("Nei");
    }
}

// funksjon som sjekker om inndata for redigering av post er korrekt
function redigerSjekk() {
    var postTekst = document.forms["redigerForm"]["post_text"].value;

    var error = "";

    // Ingen felt tomme
    if(postTekst == "") {
        error += "<p>Feltet må må fylles ut.</p>";
    }

    // Må inneholde noe tekst
    if(!isNaN(postTekst)) {
        error += "<p>Posten må inneholde noe tekst.</p>";
    }

    // Er ikke errorvariabelen tom, skriv ut melding og return false
    if(error != "") {
        var last = document.getElementById("last");
        last.innerHTML = error;
        return false;
    }
    else {
        return true;
    }
}

// funksjon som sjekker om en mail er gyldig via regex
function isMail(value)
{
    if(value == null)
        value = document.getElementById('newemail').value;
    var pattern = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    return pattern.test(value);
}

// funksjon for å forandre hvilken CSS som brukes med et AJAX-kall. brukes av knapper i header.php
// krever refresh
function setCSS(str) {
    $.ajax({
        type: 'POST',
        url: 'set_style.php',
        data: "style="+str,
    });
}