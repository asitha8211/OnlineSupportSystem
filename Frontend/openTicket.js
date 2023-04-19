function showForm() {
    const form = document.getElementById("ticket");
    if (form.style.display === "none") {
        form.style.display = "block";
    } else {
        form.style.display = "none";
    }
}