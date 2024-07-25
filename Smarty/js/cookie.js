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

// Get custom names from the data attribute or session storage
const breadcrumbElement = document.getElementById('breadcrumb');
let accommodationName = breadcrumbElement ? breadcrumbElement.getAttribute('data-accommodation-name') || sessionStorage.getItem('accommodationName') || 'Student Accommodation' : 'Student Accommodation';
let username = breadcrumbElement ? breadcrumbElement.getAttribute('data-user-name') || sessionStorage.getItem('username') || 'Guest' : 'Guest';

// Save the accommodation name and username to session storage
sessionStorage.setItem('accommodationName', accommodationName);
sessionStorage.setItem('username', username);

// Define patterns and corresponding names
const customNamesPatterns = {
    '/UniRent/Owner/home': 'Home',
    '/UniRent/User/home': 'Home',
    '/UniRent/Student/home': 'Home',
    '/UniRent/Student/about': 'About Us',
    '/UniRent/Student/accommodation/*': accommodationName,
    '/UniRent/Student/publicProfile/*': username, // New pattern for username
    '/UniRent/Owner/accommodationManagement/*': accommodationName,
    '/UniRent/Owner/publicProfile/*': username,
    'UniRent/Owner/about': 'About Us',
    '/UniRent/User/about': 'About Us',
    '/UniRent/Owner/addAccommodation': 'Add Accommodation',
    '/UniRent/Owner/contact': 'Contact Us',
    '/UniRent/User/contact': 'Contact Us',
    '/UniRent/Student/contact': 'Contact Us',
    '/UniRent/Owner/editAccommodation/*': 'Edit Accommodation',
    '/UniRent/Owner/editProfile': 'Edit Profile',
    '/UniRent/Student/editProfile': 'Edit Profile',
    '/UniRent/Owner/profile': 'Profile',
    '/UniRent/Student/profile': 'Profile',
    '/UniRent/Owner/postedReview': 'Posted Reviews',
    '/UniRent/Student/postedReview': 'Posted Reviews',
    '/UniRent/Reservation/showOwner': 'Reservations',
    '/UniRent/Owner/reviews': 'Reviews',
    '/UniRent/Owner/tenants/current': 'Current Tenants',
    '/UniRent/Owner/tenants/past': 'Past Tenants',
    '/UniRent/Owner/tenants/future': 'Future Tenants',
    '/UniRent/Student/viewsOwnerAds': username + "'s Ads",
    '/UniRent/Visit/viewVisits': username + "'s Visits",
    '/UniRent/Visit/visits': 'Visits',
    '/UniRent/Student/paymentMethods': 'Payment Methods',
    '/UniRent/Reservation/showStudent/accepted': 'Accepted Reservations',
    '/UniRent/Reservation/showStudent/pending': 'Pending Reservations',
    '/UniRent/Student/reviews': 'Reviews',
    '/UniRent/Student/search': 'Search',
    '/UniRent/User/search': 'Search'
};

// Function to get custom name based on URL patterns
function getCustomName(url) {
    for (const pattern in customNamesPatterns) {
        if (pattern.includes('*')) {
            const regexPattern = new RegExp('^' + pattern.replace('*', '.*') + '$');
            if (regexPattern.test(url)) {
                return customNamesPatterns[pattern];
            }
        } else if (pattern === url) {
            return customNamesPatterns[pattern];
        }
    }
    return url;
}

if (currentPage) {
    // Check if currentPage matches any of the specified URLs
    const resetPages = ['/UniRent/Owner/home', '/UniRent/User/home', '/UniRent/Student/home'];
    if (resetPages.includes(currentPage)) {
        console.log("Current page matches reset criteria. Clearing visitedPages.");
        // Only keep the current page in visitedPages
        let visitedPages = {};
        visitedPages[0] = currentPage;
        sessionStorage.setItem("visitedPages", JSON.stringify(visitedPages));
        console.log("Visited Pages:", visitedPages);
    } else {
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

        // Update and display the breadcrumb
        displayBreadcrumb(newVisitedPages);
    }
}

function displayBreadcrumb(visitedPages) {
    // Ensure no breadcrumb is shown on reset pages
    const resetPages = ['/UniRent/Owner/home', '/UniRent/User/home', '/UniRent/Student/home'];
    if (resetPages.includes(currentPage)) {
        return;
    }

    const breadcrumbContainer = document.getElementById('breadcrumb');
    if (!breadcrumbContainer) {
        console.log("Breadcrumb element not found. Skipping breadcrumb display.");
        return;
    }

    breadcrumbContainer.innerHTML = ''; // Clear existing breadcrumb

    const keys = Object.keys(visitedPages);
    for (let i = 0; i < keys.length; i++) {
        const page = visitedPages[keys[i]];
        const linkName = getCustomName(page); // Use custom name or default to URL

        if (i < keys.length - 1) {
            // Create clickable breadcrumb item
            const breadcrumbItem = document.createElement('a');
            breadcrumbItem.href = page;
            breadcrumbItem.innerText = linkName;
            breadcrumbItem.addEventListener('click', (event) => {
                // Update visited pages before navigating
                const newVisitedPages = {};
                for (let k in visitedPages) {
                    newVisitedPages[k] = visitedPages[k];
                    if (visitedPages[k] === page) break;
                }
                sessionStorage.setItem("visitedPages", JSON.stringify(newVisitedPages));
            });
            breadcrumbContainer.appendChild(breadcrumbItem);
        } else {
            // Create non-clickable breadcrumb item for the current page
            const breadcrumbItem = document.createElement('span');
            breadcrumbItem.innerText = linkName;
            breadcrumbContainer.appendChild(breadcrumbItem);
        }

        if (i < keys.length - 1) {
            breadcrumbContainer.appendChild(document.createTextNode(' > ')); // Separator
        }
    }
}

// Initial breadcrumb setup
const visitedPages = JSON.parse(sessionStorage.getItem("visitedPages")) || {};
displayBreadcrumb(visitedPages);
