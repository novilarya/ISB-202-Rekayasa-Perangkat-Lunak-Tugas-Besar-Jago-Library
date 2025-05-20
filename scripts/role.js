function toggleNRP() {
    const role = document.getElementById("role").value;
    const nrpGroup = document.getElementById("nrp-group");

    if (role === "dosen") {
        nrpGroup.style.display = "none";
    } else {
        nrpGroup.style.display = "block";
    }
}

window.onload = function() {
    toggleNRP();
};
