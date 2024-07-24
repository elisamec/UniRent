// Function to set a cookie
function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

// Function to get the value of a cookie by name
function getCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1); // Remove leading spaces
        if (c.indexOf(nameEQ) === 0) {
            return c.substring(nameEQ.length, c.length); // Return cookie value
        }
    }
    console.log(`Cookie with name "${name}" not found`); // Debugging line
    return null;
}

// Function to delete a cookie
function eraseCookie(name) {
    document.cookie = name + '=; Max-Age=-99999999;';
}

// Get the current page URL
let currentPage = window.location.pathname;
console.log("Current Page:", currentPage);

// Set the cookie with the current page URL
setCookie('current_page', currentPage, 1); // Expires in 1 day

// Get the current page URL from the cookie
currentPage = getCookie("current_page");
console.log("Current Page in cookie:", currentPage);

if (currentPage) {
    // Get the existing visited pages from sessionStorage
    let visitedPages = JSON.parse(sessionStorage.getItem("visitedPages")) || {};
    console.log("Visited Pages from sessionStorage:", visitedPages);

    // Remove the old occurrence of the current page URL, if it exists
    for (let key in visitedPages) {
        if (visitedPages[key] === currentPage) {
            console.log("Removing old occurrence of current page URL");
            delete visitedPages[key];
        }
    }

    // Reindex visited pages to shift keys if necessary
    let newVisitedPages = {};
    let index = 0;
    for (let key in visitedPages) {
        newVisitedPages[index] = visitedPages[key];
        index++;
    }

    // Add the current page URL with the next available index
    newVisitedPages[index] = currentPage;

    // Save the updated visited pages back to sessionStorage
    sessionStorage.setItem("visitedPages", JSON.stringify(newVisitedPages));

    // Display the visited pages (for debugging purposes)
    console.log("Visited Pages:", newVisitedPages);
}