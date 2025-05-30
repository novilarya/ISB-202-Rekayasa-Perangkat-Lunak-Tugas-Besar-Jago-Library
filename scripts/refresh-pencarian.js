const input = document.getElementById("searchInput");

input.addEventListener("input", function () {
    if (this.value.trim() === "") {
        window.location.href = window.location.pathname;
    }
});

input.addEventListener("search", function () {
    if (this.value.trim() === "") {
        window.location.href = window.location.pathname;
    }
});