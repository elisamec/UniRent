var tenantContainer = document.getElementById('tenantCont');

    // Function to create tenant section
    function createTenantSection(username, expiryDate, profilePic, status) {
        if (status === 'banned') {
            var style = 'class="disabled"';
        } else {
            var style = '';
        }
        
        return `
            <div class="col-md-4">
                <div class="userSection">
                    <div class="userIcon">
                        <a href="/UniRent/Owner/publicProfile/${username}" ${style}><img src="${profilePic}" alt="User Profile Picture" onerror="this.onerror=null;this.src='/UniRent/Smarty/images/defaultProfilePic.png';"></a>
                    </div>
                    <div class="username"><a href="/UniRent/Owner/publicProfile/${username}" ${style}>${username}</a></div>
                    <div class="username">Expiry Date: ${expiryDate}</div>
                </div>
            </div>
        `;
    }

    // Function to create free space section
    function createFreeSpaceSection() {
        return `
            <div class="col-md-4">
                <div class="userSection">
                    <div class="userIcon">
                        <a><img src="/UniRent/Smarty/images/FreeBadge.png" alt="Free Badge"></a>
                    </div>
                    <div class="username"></div>
                </div>
            </div>
        `;
    }

    // Generate tenant sections
    for (var i = 0; i < tenants.length; i++) {
        var tenant = tenants[i];
        tenantContainer.innerHTML += createTenantSection(tenant.username, tenant.expiryDate, tenant.profilePic, tenant.status);
    }

    // Fill remaining places with free space sections
    for (var j = tenants.length; j < numPlaces; j++) {
        tenantContainer.innerHTML += createFreeSpaceSection();
    }