
// =====================================
// ANATOMIA - FORUM
// =====================================


document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.querySelector(".nav-toggle");
    const nav = document.getElementById("mainNav");
    if (toggle && nav) {
        toggle.addEventListener("click", function () {
            nav.classList.toggle("open");
        });
    }

    const role = document.getElementById("role");
    const specialite = document.getElementById("specialite-field");
    function refreshRole() {
        if (!role || !specialite) {
            return;
        }
        specialite.hidden = role.value !== "medecin";
    }
    if (role) {
        role.addEventListener("change", refreshRole);
        refreshRole();
    }
});

// Afficher ou cacher les commentaires
function toggleComments(postId) {

    const section = document.getElementById("comments-" + postId);

    if (section) {
        section.hidden = !section.hidden;
    }

}


// Confirmation avant un signalement
document.addEventListener("DOMContentLoaded", function () {

    const reportForms = document.querySelectorAll(
        ".report-section form"
    );

    reportForms.forEach(function (form) {

        form.addEventListener("submit", function (event) {

            const confirmation = confirm(
                "Voulez-vous envoyer ce signalement ?"
            );

            if (!confirmation) {
                event.preventDefault();
            }

        });

    });


    // Confirmation avant la suppression
    const deleteForms = document.querySelectorAll(
        ".delete-form"
    );

    deleteForms.forEach(function (form) {

        form.addEventListener("submit", function (event) {

            const confirmation = confirm(
                "Voulez-vous vraiment supprimer cette publication ?"
            );

            if (!confirmation) {
                event.preventDefault();
            }

        });

    });

});
