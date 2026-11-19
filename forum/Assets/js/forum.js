
// =====================================
// ANATOMIA - FORUM
// =====================================


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
