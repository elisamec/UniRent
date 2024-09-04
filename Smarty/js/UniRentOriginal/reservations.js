function populateReservationsContainer(reservationData) {
    const reservationsContainer = document.getElementById("reservationsContainer");
    reservationsContainer.innerHTML = '';

    if (!reservationData || reservationData.length === 0 || Object.keys(reservationData).length === 0) {
        const noReservationsDiv = document.createElement("div");
        noReservationsDiv.classList.add("container", "bottomPadding");
        noReservationsDiv.textContent = "You have no reservations yet.";
        reservationsContainer.appendChild(noReservationsDiv);
        return;
    }

    reservationData.forEach(item => {
        const accommodationDiv = document.createElement("div");
        accommodationDiv.classList.add("accommodation");

        const title = document.createElement("h1");
        title.classList.add("titleAccomm");
        title.textContent = item.accommodation;
        accommodationDiv.appendChild(title);

        const rowDiv = document.createElement("div");
        rowDiv.classList.add("row");

        const reservations = Array.isArray(item.reservations) ? item.reservations : [item.reservations];
        
        reservations.forEach(reservation => {
            if (reservation && typeof reservation === 'object') {
                const colDiv = document.createElement("div");
                colDiv.classList.add("col-md-3");

                const userSectionDiv = document.createElement("div");
                userSectionDiv.classList.add("userSection");

                const userIconDiv = document.createElement("div");
                userIconDiv.classList.add("userIcon");

                const userLink = document.createElement("a");
                userLink.href = `/UniRent/Reservation/reservationDetails/${reservation.idReservation}`;

                const userImage = document.createElement("img");
                userImage.src = reservation.image;

                userLink.appendChild(userImage);
                userIconDiv.appendChild(userLink);
                userSectionDiv.appendChild(userIconDiv);

                const usernameDiv = document.createElement("div");
                usernameDiv.classList.add("username");

                const usernameLink = document.createElement("a");
                usernameLink.href = `/UniRent/Reservation/reservationDetails/${reservation.idReservation}`;
                usernameLink.textContent = reservation.username;
                usernameDiv.appendChild(usernameLink);

                const periodDiv = document.createElement("div");
                periodDiv.textContent = `Period: ${reservation.period}`;
                usernameDiv.appendChild(periodDiv);

                const expiresDiv = document.createElement("div");
                expiresDiv.textContent = `You have ${reservation.expires} left before the reservation is automatically accepted.`;
                usernameDiv.appendChild(expiresDiv);

                userSectionDiv.appendChild(usernameDiv);
                colDiv.appendChild(userSectionDiv);
                rowDiv.appendChild(colDiv);
            } else {
                console.error("Unexpected format for item.reservations:", item.reservations);
            }
        });

        accommodationDiv.appendChild(rowDiv);
        reservationsContainer.appendChild(accommodationDiv);
    });
}

populateReservationsContainer(reservationData)
