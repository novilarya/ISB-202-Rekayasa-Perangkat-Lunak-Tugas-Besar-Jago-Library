function toggleNrpNidn() {
    const role = document.getElementById("role").value;
    const nrp_nidnLabel = document.querySelector("label[for='nrp_nidn']");
    const nrp_nidnInput = document.getElementById("nrp_nidn");

    if (role === "mahasiswa") {
        nrp_nidnLabel.textContent = "NRP";
        nrp_nidnInput.placeholder = "Masukkan NRP";
    } else if (role === "dosen") {
        nrp_nidnLabel.textContent = "NIDN";
        nrp_nidnInput.placeholder = "Masukkan NIDN";
    }
}

window.onload = function() {
    toggleNrpNidn();
};
