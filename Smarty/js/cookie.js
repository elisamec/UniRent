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

// Function to normalize the URL
function normalizeUrl(url) {
    let segments = url.split('/');
    let lastSegment = segments.pop();
    // Remove status segments like 'success' or 'error'
    if (['success', 'error'].includes(lastSegment)) {
        return segments.join('/');
    }
    return url;
}

// Set and get normalized URL from cookies
function updateCookieWithNormalizedUrl() {
    let normalizedUrl = normalizeUrl(currentPage);
    setCookie('current_page', normalizedUrl, 1); // Expires in 1 day
}

// Get normalized URL from cookie
function getCleanUrlFromCookie() {
    return getCookie("current_page");
}

// Set the normalized URL in cookie
updateCookieWithNormalizedUrl();
let cleanUrl = getCleanUrlFromCookie();
console.log("Clean URL from cookie:", cleanUrl);

// Extract username from URL
function extractUsernameFromUrl(url) {
    const match = url.match(/\/UniRent\/Admin\/profile\/([^\/]+)/);
    return match ? match[1] : 'Guest';
}

// Function to add a username to session storage with indexing
function addUsernameToSessionStorage(url, username) {
    let visitedPages = JSON.parse(sessionStorage.getItem("visitedPages")) || {};
    let index = Object.keys(visitedPages).length;

    // Find existing entry with the same URL and remove it
    for (let key in visitedPages) {
        if (visitedPages[key].url === url) {
            delete visitedPages[key];
            break;
        }
    }

    // Add the new entry
    visitedPages[index] = { url: url, username: username };
    sessionStorage.setItem("visitedPages", JSON.stringify(visitedPages));
}

// Add username for current page
const username = extractUsernameFromUrl(currentPage);
addUsernameToSessionStorage(currentPage, username);

// Define patterns and corresponding names
const customNamesPatterns = {
    '/UniRent/Owner/home': 'Home',
    '/UniRent/User/home': 'Home',
    '/UniRent/Student/home': 'Home',
    '/UniRent/Admin/home': 'Dashboard',
    '/UniRent/Student/about': 'About Us',
    '/UniRent/Student/accommodation/*': 'Accommodation',
    '/UniRent/Student/publicProfile/*': (username) => username + "'s Profile",
    '/UniRent/Owner/accommodationManagement/*': 'Accommodation',
    '/UniRent/Owner/publicProfile/*': (username) => username + "'s Profile",
    '/UniRent/Owner/about': 'About Us',
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
    '/UniRent/Student/viewsOwnerAds': (username) => username + "'s Ads",
    '/UniRent/Visit/viewVisits': (username) => username + "'s Visits",
    '/UniRent/Visit/visits': 'Visits',
    '/UniRent/Student/paymentMethods': 'Payment Methods',
    '/UniRent/Reservation/showStudent/accepted': 'Accepted Reservations',
    '/UniRent/Reservation/showStudent/pending': 'Pending Reservations',
    '/UniRent/Student/reviews': 'Reviews',
    '/UniRent/Student/search': 'Search',
    '/UniRent/User/search': 'Search',
    '/UniRent/Reservation/reservationDetails/*': (reservationDetail) => 'Reservation Details: ' + reservationDetail,
    '/UniRent/Contract/showStudent/finished': 'Past Contracts',
    '/UniRent/Contract/showStudent/onGoing': 'OnGoing Contracts',
    '/UniRent/Contract/showStudent/future': 'Upcoming Contracts',
    '/UniRent/Contract/showOwner/finished': 'Past Contracts',
    '/UniRent/Contract/showOwner/onGoing': 'OnGoing Contracts',
    '/UniRent/Contract/showOwner/future': 'Upcoming Contracts',
    '/UniRent/Contract/contractDetails/*': 'Contract Details',
    '/UniRent/Admin/profile/*': (username) => username + "'s Profile",
    '/UniRent/Admin/readMoreSupportRequest': 'Support Requests',
};

// Function to get custom name based on URL patterns
function getCustomName(url) {
    for (const pattern in customNamesPatterns) {
        if (pattern.includes('*')) {
            const regexPattern = new RegExp('^' + pattern.replace('*', '(.+)') + '$');
            const match = regexPattern.exec(url);
            if (match) {
                const parameter = match[1];
                const nameFunction = customNamesPatterns[pattern];
                return typeof nameFunction === 'function' ? nameFunction(parameter) : nameFunction;
            }
        } else if (pattern === url) {
            return customNamesPatterns[pattern];
        }
    }
    return url;
}

// Function to display breadcrumbs
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
        const entry = visitedPages[keys[i]];
        const page = entry.url;
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
                    if (visitedPages[k].url === page) break;
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
