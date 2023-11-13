function addSession() {
    var sessionDiv = document.getElementById("sessions");
    var newSession = document.createElement("div");
    newSession.classList.add("session");
    newSession.innerHTML = `
        <input type="date" name="session_start_date[]" required>
        <input type="date" name="session_end_date[]" required>
        <input type="number" name="available_spots_adult[]" placeholder="Available Spots adult" required>
        <input type="number" name="available_spots_child[]" placeholder="Available Spots child" required>
        <input type="number" name="price_package_adult[]" placeholder="price_package_adult" required>
        <input type="number" name="price_package_child[]" placeholder="price_package_child" required>
        <button type="button" onclick="addSession()">Add Session</button>
        <button type="button" onclick="removeSession(this)">Remove</button>
    `;
    sessionDiv.appendChild(newSession);
}

function removeSession(button) {
    button.parentElement.remove();
}






