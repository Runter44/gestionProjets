"use strict";

$(document).ready(function() {
    // ACCUEIL

    // Si aucun projet n'est sélectionné dans l'écran d'accueil
    $("#formVoirAvancement").click(function(event) {
        if ($("#projetVoir").val() === "aucun") {
            $(".invalid-feedback").addClass("d-sm-block");
            $("#projetVoir").addClass("is-invalid");
        } else {
            document.location = "/avancement/" + $("#projetVoir").val();
        }
    });

    $("#projetVoir").change(function() {
        $(".invalid-feedback").removeClass("d-sm-block");
        $("#projetVoir").removeClass("is-invalid");
    });

    // VOIR L'AVANCEMENT
    $(".ulVoirAvancement").each(function() {
        // On supprime tous les ul vides
        if ($(this).has("li").length === 0) {
            $(this).remove();
        }
    });
});
