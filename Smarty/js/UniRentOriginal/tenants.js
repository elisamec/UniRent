function populateTenantsContainer(tenants) {
    const tenantsContainer = document.getElementById("tenantsContainer");
    const yearSelect = document.getElementById("year");

    // Clear existing options in year select
    if(yearSelect) {
        yearSelect.innerHTML = '<option value="" disabled selected>Select a year</option>';
    }
    tenantsContainer.innerHTML = '';

    if (!tenants || tenants.length === 0 || Object.keys(tenants).length === 0) {
        const noTenantsDiv = document.createElement("div");
        noTenantsDiv.classList.add("container");
        noTenantsDiv.textContent = "No tenants found.";
        tenantsContainer.appendChild(noTenantsDiv);
        return;
    }

    const uniqueYears = new Set();

    tenants.forEach(item => {
        const accommodationDiv = document.createElement("div");
        accommodationDiv.classList.add("accommodation");

        const title = document.createElement("h1");
        title.classList.add("titleAccomm");
        title.textContent = item.accommodation;
        accommodationDiv.appendChild(title);

        const rowDiv = document.createElement("div");
        rowDiv.classList.add("row");

        const tenantList = Array.isArray(item.tenants) ? item.tenants : [item.tenants];

        tenantList.forEach(tenant => {
            if (tenant && typeof tenant === 'object') {
                const colDiv = document.createElement("div");
                colDiv.classList.add("col-md-3");

                const userSectionDiv = document.createElement("div");
                userSectionDiv.classList.add("userSection");

                const userIconDiv = document.createElement("div");
                userIconDiv.classList.add("userIcon");

                const userLink = document.createElement("a");
                userLink.href = `/UniRent/Owner/publicProfile/${tenant.username}`;
                if (tenant.status === 'banned') {
                    userLink.classList.add("disabled");
                }

                const userImage = document.createElement("img");
                userImage.src = tenant.image;

                userLink.appendChild(userImage);
                userIconDiv.appendChild(userLink);
                userSectionDiv.appendChild(userIconDiv);

                const usernameDiv = document.createElement("div");
                usernameDiv.classList.add("username");

                const usernameLink = document.createElement("a");
                usernameLink.href = `/UniRent/Owner/publicProfile/${tenant.username}`;
                if (tenant.status === 'banned') {
                    usernameLink.classList.add("disabled");
                }
                usernameLink.textContent = tenant.username;
                usernameDiv.appendChild(usernameLink);

                const expiryDateDiv = document.createElement("div");
                expiryDateDiv.textContent = `Expiry Date: ${tenant.expiryDate}`;
                usernameDiv.appendChild(expiryDateDiv);

                userSectionDiv.appendChild(usernameDiv);
                colDiv.appendChild(userSectionDiv);
                rowDiv.appendChild(colDiv);

                const year2 = new Date(tenant.expiryDate).getFullYear();
                const year1 = new Date(tenant.expiryDate).getFullYear()-1;
                uniqueYears.add(year1 + "-" + year2);
            } else {
                console.error("Unexpected format for item.tenants:", tenant);
            }
        });

        accommodationDiv.appendChild(rowDiv);
        tenantsContainer.appendChild(accommodationDiv);
    });
    if(yearSelect) {
        uniqueYears.forEach(year => {
            const option = document.createElement("option");
            if (year === selectedYear) {
                option.selected = true;
            }
            option.value = year;
            option.textContent = year;
            yearSelect.appendChild(option);
        });
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const accommodationSelect = document.getElementById("accommodationSelect");

    if (Array.isArray(accommodationTitles)) {
        accommodationTitles.forEach(item => {
            const option = document.createElement("option");
            option.value = item.accommodation;
            if (item.accommodation === selectedAccommodation) {
                option.selected = true;
            }
            option.textContent = item.accommodation;
            accommodationSelect.appendChild(option);
        });
    } else if (typeof accommodationTitles === 'object') {
        Object.keys(accommodationTitles).forEach(key => {
            const option = document.createElement("option");
            option.value = key;
            if (key === selectedAccommodation) {
                option.selected = true;
            }
            option.textContent = accommodationTitles[key];
            accommodationSelect.appendChild(option);
        });
    } else {
        console.error("Unexpected format for accommodationTitles:", accommodationTitles);
    }

    populateTenantsContainer(tenants);
});
