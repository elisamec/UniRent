populateContractsContainer(data);

   function populateContractsContainer(data) {
        const contractsContainer = document.getElementById("contractsContainer");
        contractsContainer.innerHTML = '';

            if (data.length === 0) {
                const noContractsDiv = document.createElement("div");
                noContractsDiv.classList.add("container");
                noContractsDiv.classList.add("bottomPadding");
                noContractsDiv.textContent = "You have no contracts yet.";
                contractsContainer.appendChild(noContractsDiv);
            } else if (Object.keys(data).length === 0) {
                const noContractsDiv = document.createElement("div");
                noContractsDiv.classList.add("container");
                  noContractsDiv.classList.add("bottomPadding");
                noContractsDiv.textContent = "You have no contracts yet.";
                contractsContainer.appendChild(noContractsDiv);
            }

        data.forEach(item => {
            const accommodationDiv = document.createElement("div");
            accommodationDiv.classList.add("accommodation");
            const title = document.createElement("h1");
            title.classList.add("titleAccomm");
            title.textContent = item.accommodation;
            accommodationDiv.appendChild(title);

            const rowDiv = document.createElement("div");
            rowDiv.classList.add("row");
            
            // Check if item.contracts is an array
            if (Array.isArray(item.contracts)) {
                item.contracts.forEach(contract => {
                    const colDiv = document.createElement("div");
                    colDiv.classList.add("col-md-3");

                    const userSectionDiv = document.createElement("div");
                    userSectionDiv.classList.add("userSection");

                    const userIconDiv = document.createElement("div");
                    userIconDiv.classList.add("userIcon");

                    const userLink = document.createElement("a");
                    userLink.href = `/UniRent/Contract/contractDetails/${contract.idContract}`;

                    const userImage = document.createElement("img");
                    userImage.src = contract.image;

                    userLink.appendChild(userImage);
                    userIconDiv.appendChild(userLink);
                    userSectionDiv.appendChild(userIconDiv);

                    const usernameDiv = document.createElement("div");
                    usernameDiv.classList.add("username");

                    const usernameLink = document.createElement("a");
                    usernameLink.href = `/UniRent/Contract/contractDetails/${contract.idContract}`;
                    usernameLink.textContent = contract.username;
                    usernameDiv.appendChild(usernameLink);

                    // Display period under username
                    const periodDiv = document.createElement("div");
                    periodDiv.textContent = `Period: ${contract.period}`;
                    usernameDiv.appendChild(periodDiv);

                    userSectionDiv.appendChild(usernameDiv);

                    colDiv.appendChild(userSectionDiv);
                    rowDiv.appendChild(colDiv);
                });
            } else if (typeof item.contracts === 'object') {
                // Handle case where contracts is an object (not an array)
                const contract = item.contracts;

                const colDiv = document.createElement("div");
                colDiv.classList.add("col-md-3");

                const userSectionDiv = document.createElement("div");
                userSectionDiv.classList.add("userSection");

                const userIconDiv = document.createElement("div");
                userIconDiv.classList.add("userIcon");

                const userLink = document.createElement("a");
                userLink.href = `/UniRent/Contract/contractDetails/${contract.idContract}`;

                const userImage = document.createElement("img");
                userImage.src = contract.image;

                userLink.appendChild(userImage);
                userIconDiv.appendChild(userLink);
                userSectionDiv.appendChild(userIconDiv);

                const usernameDiv = document.createElement("div");
                usernameDiv.classList.add("username");

                const usernameLink = document.createElement("a");
                usernameLink.href = `/UniRent/Contract/contractDetails/${contract.idContract}`;
                    usernameLink.textContent = contract.username;
                usernameDiv.appendChild(usernameLink);

                // Display period under username
                const periodDiv = document.createElement("div");
                periodDiv.textContent = `Period: ${contract.period}`;
                usernameDiv.appendChild(periodDiv);

                userSectionDiv.appendChild(usernameDiv);

                colDiv.appendChild(userSectionDiv);
                rowDiv.appendChild(colDiv);
            } else {
                console.error("Unexpected format for item.contracts:", item.contracts);
            }

            accommodationDiv.appendChild(rowDiv);
            contractsContainer.appendChild(accommodationDiv);
        });
    }