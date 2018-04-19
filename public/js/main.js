$(document).ready(function() {


    // ACCUEIL

    // Si aucun projet n'est sélectionné dans l'écran d'accueil
    $("#formVoirAvancement").submit(function(event) {
        if ($("#projetVoir").val() === "aucun") {
            $(".invalid-feedback").show();
            $("#projetVoir").addClass("bordureRouge");
            event.preventDefault();
        }
    });

    $("#projetVoir").change(function() {
        $(".invalid-feedback").hide();
        $("#projetVoir").removeClass("bordureRouge");
    });



    // NOUVEAU PROJET

    // Quand on change le type de liste à générer on affiche ou cache la sélection de projets
    $("input[type=radio][name=liste]").change(function() {
        if (this.value === "import") {
            $("#projetImport").show();
        } else {
            $("#projetImport").hide();
            $("#projetImport").removeClass("bordureRouge");
            $("#invalidProjet").hide();
        }
    });

    // Quand on clique pour générer la liste
    $("#genererListe").click(function(event) {
        if ($("input[name=liste]:checked").val() === "import") {
            if ($("#projetImport").val() === "aucun") {
                $("#invalidProjet").show();
                $("#projetImport").addClass("bordureRouge");
                event.preventDefault();
                return;
            }

            // Ici le traitement pour générer l'import
            $("#emplacementGenererListe").empty();
            $("#emplacementGenererListe").append("<input readonly class=\"form-control-plaintext\" type=\"text\" value=\"Importation d'un modèle de projet " + $("#projetImport").val() + "\">");
        } else {
            // Ici le traitement pour nouvelle liste
            $("#emplacementGenererListe").empty();
            $("#typeDeListe").html("Nouvelle liste de tâches");
            $("#formAjoutTache").show();
        }
    });

    $("#projetImport").change(function() {
        $("#invalidProjet").hide();
        $("#projetImport").removeClass("bordureRouge");
    });

    // Quand on ajoute une nouvelle tâche
    $("#btnAjoutTache").click(function() {
        if ($("#selectAjoutTache").val() === "aucun") {
            $("#invalidTache").show();
            $("#selectAjoutTache").addClass("bordureRouge");
        } else {
            ajoutTacheProjet($("#selectAjoutTache").val());
        }
    });

    $("#selectAjoutTache").change(function() {
        $("#invalidTache").hide();
        $("#selectAjoutTache").removeClass("bordureRouge");
    });
});

function ajoutTacheProjet(tacheValue) {
    var selectValue = tacheValue;
    var tache = tacheValue.split("|")[0];
    var categorie = tacheValue.split("|")[1];
    var id = tacheValue.split("|")[2];
    // On enlève les accents et les espaces de la catégorie
    var normalizeCategorie = categorie.normalize('NFD').replace(/[\u0300-\u036f]/g, "").replace(" ", "-");

    // Ensuite on regarde si la div de la catégorie existe ou pas
    // Pour trier les tâches dans les catégories
    if ($("#" + normalizeCategorie).length !== 0) {
        // Si la tâche n'est pas déjà ajoutée
        if ($("#" + normalizeCategorie + ":contains(\"" + tache + "\")").length === 0) {
            // On ajoute l'icône et le nom de la tâche
            $("#" + normalizeCategorie).append("<p><i id=\"btnSupprimerTache" + id + "\" class=\"far fa-trash-alt btnSupprimerTache\"></i> " + tache + "</p>");
            // Set le onclick pour supprimer
            $("#btnSupprimerTache" + id).click(function() {
                if ($(this).parent().parent().text() === categorie + " " + tache) {
                    $(this).parent().parent().remove();
                } else {
                    $(this).parent().remove();
                }
            });
        } else {
            alert("Vous avez déjà ajouté cette tâche !");
        }
    } else {
        // Si la catégorie n'est pas dejà présente
        // On crée la div de la catégorie
        $("#emplacementGenererListe").append("<div id=\"" + normalizeCategorie + "\">");
        $("#" + normalizeCategorie).append("<h4>" + categorie + "</h4>");
        // Si la tâche n'est pas déjà ajoutée
        if ($("#" + normalizeCategorie + ":contains(\"" + tache + "\")").length === 0) {
            // On ajoute l'icône et le nom de la tâche
            $("#" + normalizeCategorie).append("<p><i id=\"btnSupprimerTache" + id + "\" class=\"far fa-trash-alt btnSupprimerTache\"></i> " + tache + "</p>");
            // Set le onclick pour supprimer
            $("#btnSupprimerTache" + id).click(function() {
                if ($(this).parent().parent().text() === categorie + " " + tache) {
                    $(this).parent().parent().remove();
                } else {
                    $(this).parent().remove();
                }
            });
        } else {
            alert("Vous avez déjà ajouté cette tâche !");
        }
    }
}
