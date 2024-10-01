function toggleStatus(callId) {
    var dropdown = document.getElementById(callId).getElementsByClassName("status-dropdown")[0];
    dropdown.style.opacity = dropdown.style.opacity === "1" ? "0" : "1";
    dropdown.style.pointerEvents = dropdown.style.pointerEvents === "all" ? "none" : "all";
}

function changeStatus(select, callId) {
    var newStatus = select.value;
    var statusSpan = document.getElementById(callId).getElementsByClassName("status")[0];
    statusSpan.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
    statusSpan.classList.remove("pending", "assigned", "completed", "closed");
    statusSpan.classList.add(newStatus.toLowerCase());
    toggleStatus(callId); // Hide dropdown
}
