document.addEventListener("DOMContentLoaded", function () {
    // --- LOGIKA UTAMA FAVORIT ---

    function getFavorites() {
        const favs = localStorage.getItem("eventKitaFavorites");
        return favs ? JSON.parse(favs) : {};
    }

    function saveFavorites(favs) {
        localStorage.setItem("eventKitaFavorites", JSON.stringify(favs));
    }

    function handleFavoriteClick(e) {
        const button = e.currentTarget;
        const eventId = button.dataset.id;

        const currentFavorites = getFavorites();

        if (currentFavorites[eventId]) {
            delete currentFavorites[eventId];
            updateButtonUI(button, false);
        } else {
            currentFavorites[eventId] = {
                id: eventId,
                judul: button.dataset.judul,
                gambar: button.dataset.gambar,
                tanggal: button.dataset.tanggal,
                kategori: button.dataset.kategori,
                linkSitus: button.dataset.linkSitus,
            };
            updateButtonUI(button, true);
        }

        saveFavorites(currentFavorites);
    }

    function updateButtonUI(button, isFavorite) {
        const textElement = button.querySelector(".text-favorit");
        if (isFavorite) {
            button.classList.add("active");
            if (textElement) textElement.textContent = "Difavoritkan";
        } else {
            button.classList.remove("active");
            if (textElement) textElement.textContent = "Favoritkan";
        }
    }

    function checkModalFavoriteStatus(modalElement) {
        const starButton = modalElement.querySelector(".btn-favorit");
        if (!starButton) return;

        const eventId = starButton.dataset.id;
        const currentFavorites = getFavorites();

        if (currentFavorites[eventId]) {
            updateButtonUI(starButton, true);
        } else {
            updateButtonUI(starButton, false);
        }
    }

    const allFavoriteButtons = document.querySelectorAll(".btn-favorit");
    allFavoriteButtons.forEach((button) => {
        button.addEventListener("click", handleFavoriteClick);
    });

    const allModals = document.querySelectorAll(".modal");
    allModals.forEach((modal) => {
        modal.addEventListener("show.bs.modal", function () {
            checkModalFavoriteStatus(modal);
        });
    });

    function initEventFilters() {
        const filterKota = document.getElementById("filter-kota");
        const filterTanggal = document.getElementById("filter-tanggal");
        const container = document.getElementById("event-card-container");
        if (!filterKota || !filterTanggal || !container) {
            return;
        }

        const cards = Array.from(
            container.querySelectorAll(".event-col-filterable")
        );
        if (!cards.length) {
            return;
        }

        cards.forEach((card, index) => {
            if (!card.dataset.originalOrder) {
                card.dataset.originalOrder = index;
            }
        });

        function applyFilters() {
            const selectedKota = filterKota.value;
            const selectedSort = filterTanggal.value;

            cards.forEach((card) => {
                const cardKota = card.dataset.kota || "semua";
                const shouldShow =
                    selectedKota === "semua" || cardKota === selectedKota;
                card.classList.toggle("d-none", !shouldShow);
            });

            const sortedCards = [...cards].sort((a, b) => {
                if (selectedSort === "terdekat") {
                    return (
                        new Date(a.dataset.tanggal) -
                        new Date(b.dataset.tanggal)
                    );
                }
                if (selectedSort === "terjauh") {
                    return (
                        new Date(b.dataset.tanggal) -
                        new Date(a.dataset.tanggal)
                    );
                }
                return (
                    Number(a.dataset.originalOrder) -
                    Number(b.dataset.originalOrder)
                );
            });

            sortedCards.forEach((card) => container.appendChild(card));
        }

        filterKota.addEventListener("change", applyFilters);
        filterTanggal.addEventListener("change", applyFilters);
        applyFilters();
    }

    function initCategoryDelete() {
        const deleteButtons = document.querySelectorAll(
            "[data-confirm-delete]"
        );
        if (!deleteButtons.length) {
            return;
        }

        deleteButtons.forEach((button) => {
            button.addEventListener("click", () => {
                if (
                    confirm("Apakah kamu yakin ingin menghapus kategori ini?")
                ) {
                    const row = button.closest("tr");
                    if (row) {
                        row.remove();
                    }
                    alert("Kategori berhasil dihapus!");
                }
            });
        });
    }

    initEventFilters();
    initCategoryDelete();
});
