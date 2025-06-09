document.addEventListener("DOMContentLoaded", function () {
    const tipeField = document.querySelector('[name="tipe"]');
    const prioritasWrapper = document.getElementById("prioritas-wrapper");

    function togglePrioritas() {
        if (tipeField && prioritasWrapper) {
            const value = tipeField.value;
            if (value === "masuk") {
                prioritasWrapper.style.display = "block";
            } else {
                prioritasWrapper.style.display = "none";
            }
        }
    }

    // Initial state
    togglePrioritas();

    // Update on change
    if (tipeField) {
        tipeField.addEventListener("change", togglePrioritas);
    }
});
