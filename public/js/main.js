$(document).ready(function() {
    // ACCUEIL

    // Si aucun projet n'est sélectionné dans l'écran d'accueil
    $("#formVoirAvancement").click(function(event) {
        if ($("#projetVoir").val() === "aucun") {
            $(".invalid-feedback").addClass('d-sm-block');
            $("#projetVoir").addClass("is-invalid");
        } else {
            document.location = "/avancement/"+$("#projetVoir").val();
        }
    });

    $("#projetVoir").change(function() {
        $(".invalid-feedback").removeClass('d-sm-block');
        $("#projetVoir").removeClass("is-invalid");
    });

    // VOIR L'AVANCEMENT
    $(".ulVoirAvancement").each(function() {
      // On supprime tous les ul vides
      if ($(this).has("li").length === 0) {
        $(this).remove();
      }
    })
});

// FONCTIONS GLOBALES

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
            // On incrémente le nombre de taches du projet
            $('#nbTachesProjet').val(function(i, oldval) {
                return ++oldval;
            });
            var numero = $("#nbTachesProjet").val();
            // On ajoute l'icône et le nom de la tâche
            $("#" + normalizeCategorie).append("<p class=\"texteTacheProjet\"><i id=\"btnSupprimerTache" + id + "\" class=\"far fa-trash-alt btnSupprimerTache mr-3\"></i> " + tache + "</p>");
            // On ajoute le input hidden pour l'ajout des taches
            $("#" + normalizeCategorie).append("<input type=\"hidden\" name=\"tache" + id + "\" id=\"tache" + id + "\" value=\"" + id + "\">");
            // Set le onclick pour supprimer
            $("#btnSupprimerTache" + id).click(function() {
                $('#nbTachesProjet').val(function(i, oldval) {
                    return --oldval;
                });
                $("#tache"+id).remove();
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
            // On incrémente le nombre de taches du projet
            $('#nbTachesProjet').val(function(i, oldval) {
                return ++oldval;
            });
            var numero = $("#nbTachesProjet").val();
            // On ajoute l'icône et le nom de la tâche
            $("#" + normalizeCategorie).append("<p class=\"texteTacheProjet\"><i id=\"btnSupprimerTache" + id + "\" class=\"far fa-trash-alt btnSupprimerTache mr-3\"></i> " + tache + "</p>");
            // On ajoute le input hidden pour l'ajout des taches
            $("#" + normalizeCategorie).append("<input type=\"hidden\" name=\"tache" + id + "\" id=\"tache" + id + "\" value=\"" + id + "\">");
            // Set le onclick pour supprimer
            $("#btnSupprimerTache" + id).click(function() {
                $('#nbTachesProjet').val(function(i, oldval) {
                    return --oldval;
                });
                $("#tache"+id).remove();
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
