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


// Normalize the current page URL
function normalizeUrl(url) {
    const endingsToStrip = [
        '/success', '/error', '/sent', '/fail', '/null/sent', '/null/full',
        '/false', '/null/false', '/true'
    ];
    
    endingsToStrip.forEach(ending => {
        if (url.endsWith(ending)) {
            url = url.substring(0, url.length - ending.length);
        }
    });
    
    return url;
}

currentPage = normalizeUrl(currentPage);

var closeSuccess = document.getElementById('closeSuccess');
var successClose = document.getElementById('successClose');
if (closeSuccess) {
    closeSuccess.addEventListener('click', function() {
        window.location.href = currentPage;
    });
}
if (successClose) {
    successClose.addEventListener('click', function() {
        window.location.href = currentPage;
    });
}


// Set the cookie with the normalized current page URL
setCookie('current_page', currentPage, 1); // Expires in 1 day

// Get the normalized current page URL from the cookie
currentPage = getCookie("current_page");
console.log("Current Page in cookie:", currentPage);

// Get custom names from the data attribute or session storage
const breadcrumbElement = document.getElementById('breadcrumb');
let accommodationName = breadcrumbElement ? breadcrumbElement.getAttribute('data-accommodation-name') || sessionStorage.getItem('accommodationName') || 'Accommodation' : 'Accommodation';
let username = breadcrumbElement ? breadcrumbElement.getAttribute('data-user-name') || sessionStorage.getItem('username') || 'Guest' : 'Guest';
let reservationDetail = accommodationName === 'Accommodation' ? username : accommodationName;

// Save the accommodation name and username to session storage
sessionStorage.setItem('accommodationName', accommodationName);
sessionStorage.setItem('username', username);

// Define patterns and corresponding names
const customNamesPatterns = {
    '/UniRent/Owner/home': 'Home',
    '/UniRent/User/home': 'Home',
    '/UniRent/Student/home': 'Home',
    '/UniRent/Admin/home': 'Dashboard',
    '/UniRent/Student/about': 'About Us',
    '/UniRent/Student/accommodation/*': accommodationName,
    '/UniRent/Student/publicProfile/*': username, // New pattern for username
    '/UniRent/Owner/accommodationManagement/*': accommodationName,
    '/UniRent/Owner/publicProfile/*': username,
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
    '/UniRent/Student/viewsOwnerAds': username + "'s Ads",
    '/UniRent/Visit/viewVisits': username + "'s Visits",
    '/UniRent/Visit/visits': 'Visits',
    '/UniRent/Student/paymentMethods': 'Payment Methods',
    '/UniRent/Reservation/showStudent/accepted': 'Accepted Reservations',
    '/UniRent/Reservation/showStudent/pending': 'Pending Reservations',
    '/UniRent/Student/reviews': 'Reviews',
    '/UniRent/Student/search': 'Search',
    '/UniRent/User/search': 'Search',
    '/UniRent/Reservation/reservationDetails/*': 'Reservation Details: ' + reservationDetail,
    '/UniRent/Contract/showStudent/finished': 'Past Contracts',
    '/UniRent/Contract/showStudent/onGoing': 'OnGoing Contracts',
    '/UniRent/Contract/showStudent/future': 'Upcoming Contracts',
    '/UniRent/Contract/showOwner/finished': 'Past Contracts',
    '/UniRent/Contract/showOwner/onGoing': 'OnGoing Contracts',
    '/UniRent/Contract/showOwner/future': 'Upcoming Contracts',
    '/UniRent/Contract/contractDetails/*': 'Contract Details',
    '/UniRent/Admin/profile/*': username + '\'s Profile',
    '/UniRent/Admin/readMoreSupportRequest': 'Support Requests',
    '/UniRent/SupportRequest/readMoreSupportReplies': 'Support Replies'
};

// Initialize or retrieve `urlDisplayNames` from sessionStorage
const urlDisplayNames = JSON.parse(sessionStorage.getItem("urlDisplayNames")) || {};

// Function to update `urlDisplayNames`
function updateUrlDisplayNames(url, displayName) {
    urlDisplayNames[url] = displayName;
    sessionStorage.setItem("urlDisplayNames", JSON.stringify(urlDisplayNames));
}

// Function to remove `urlDisplayNames`
function removeUrlDisplayNames(url) {
    delete urlDisplayNames[url];
    sessionStorage.setItem("urlDisplayNames", JSON.stringify(urlDisplayNames));
}

// Function to get the display name for a URL
function getCustomName(url) {
    for (const pattern in customNamesPatterns) {
        // Check if the URL matches the pattern
        const regex = new RegExp('^' + pattern.replace(/\*/g, '.*') + '$');
        if (regex.test(url)) {
            return customNamesPatterns[pattern];
        }
    }
    return url; // Default to the URL itself if no match found
}

if (currentPage) {
    const resetPages = ['/UniRent/Owner/home', '/UniRent/User/home', '/UniRent/Student/home', '/UniRent/Admin/home'];
    if (resetPages.includes(currentPage)) {
        console.log("Current page matches reset criteria. Clearing visitedPages and urlDisplayNames.");
        sessionStorage.setItem("visitedPages", JSON.stringify({0: currentPage}));
        sessionStorage.setItem("urlDisplayNames", JSON.stringify({}));
        console.log("Visited Pages: {0: " + currentPage + "}");
        console.log("urlDisplayNames: {}");
    } else {
        let visitedPages = JSON.parse(sessionStorage.getItem("visitedPages")) || {};
        console.log("Visited Pages from sessionStorage:", visitedPages);
        
        if (String(window.performance.getEntriesByType("navigation")[0].type) === "back_forward") {
            let lastPage = visitedPages[Object.keys(visitedPages).length - 1];
            for (let key in visitedPages) {
                if (visitedPages[key] === lastPage) {
                    console.log("Removing last occurrence of current page URL back_forward");
                    delete visitedPages[key];
                    break;
                }
            }
        }

        for (let key in visitedPages) {
            if (visitedPages[key] === currentPage) {
                console.log("Removing old occurrence of current page URL");
                delete visitedPages[key];
            }
        }

        let newVisitedPages = {};
        let index = 0;
        for (let key in visitedPages) {
            newVisitedPages[index] = visitedPages[key];
            index++;
        }

        newVisitedPages[index] = currentPage;
        sessionStorage.setItem("visitedPages", JSON.stringify(newVisitedPages));
        console.log("Visited Pages:", newVisitedPages);

        // Update urlDisplayNames for the current page
        const displayName = getCustomName(currentPage);
        updateUrlDisplayNames(currentPage, displayName);
        displayBreadcrumb(newVisitedPages);
    }
}

function displayBreadcrumb(visitedPages) {
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
        const linkName = urlDisplayNames[page] || getCustomName(page); // Use urlDisplayNames or default to getCustomName

        if (i < keys.length - 1) {
            const breadcrumbItem = document.createElement('a');
            breadcrumbItem.href = page;
            breadcrumbItem.innerText = linkName;
            breadcrumbItem.addEventListener('click', (event) => {
                const newVisitedPages = {};
                for (let k in visitedPages) {
                    newVisitedPages[k] = visitedPages[k];
                    if (visitedPages[k] === page) break;
                }
                sessionStorage.setItem("visitedPages", JSON.stringify(newVisitedPages));
            });
            breadcrumbContainer.appendChild(breadcrumbItem);
        } else {
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
