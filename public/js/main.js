$(document).ready(function() {


    // ACCUEIL

    // Si aucun projet n'est sélectionné dans l'écran d'accueil
    $("#formVoirAvancement").click(function(event) {
        if ($("#projetVoir").val() === "aucun") {
            $(".invalid-feedback").show();
            $("#projetVoir").addClass("is-invalid");
        } else {
            document.location = "/avancement/"+$("#projetVoir").val();
        }
    });

    $("#projetVoir").change(function() {
        $(".invalid-feedback").hide();
        $("#projetVoir").removeClass("is-invalid");
    });



    // NOUVEAU PROJET

    // Quand on change le type de liste à générer on affiche ou cache la sélection de projets
    $("input[type=radio][name=liste]").change(function() {
        if (this.value === "import") {
            $("#projetImport").show();
        } else {
            $("#projetImport").hide();
            $("#projetImport").removeClass("is-invalid");
            $("#invalidProjet").hide();
        }
    });

    // Quand on clique pour générer la liste
    $("#genererListe").click(function(event) {
        $("#boutonCreerProjet").addClass("d-block");
        if ($("input[name=liste]:checked").val() === "import") {
            if ($("#projetImport").val() === "aucun") {
                $("#invalidProjet").show();
                $("#projetImport").addClass("is-invalid");
                $("#boutonCreerProjet").removeClass("d-block");
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
        $("#projetImport").removeClass("is-invalid");
    });

    // Quand on ajoute une nouvelle tâche
    $("#btnAjoutTache").click(function() {
        if ($("#selectAjoutTache").val() === "aucun") {
            $("#invalidTache").show();
            $("#selectAjoutTache").addClass("is-invalid");
        } else {
            ajoutTacheProjet($("#selectAjoutTache").val());
            $("#invalidAucuneTache").hide();
        }
    });

    $("#selectAjoutTache").change(function() {
        $("#invalidTache").hide();
        $("#selectAjoutTache").removeClass("is-invalid");
    });

    $("#formNouveauProj").submit(function(event) {
        window.onbeforeunload = null;
        event.preventDefault();
        if ($("#projet_name").val() === "" ||  $("#nbTachesProjet").val() === "0") {
            $("#invalidAucuneTache").show();
        } else {
            $.ajax({
                context: this,
                url: '/ajax/nom-projet-existe/',
                type: "POST",
                data: {
                    "nomProjet": $("#projet_name").val()
                },
                success: function(data) {
                    if (data !== "") {
                      $("#invalidNomUtilise").show();
                      $("#projet_name").addClass("is-invalid");
                    } else {
                      this.submit();
                    }
                }
            });
        }
    });

    $("#projet_name").change(function() {
      $("#invalidNomUtilise").hide();
      $("#projet_name").removeClass("is-invalid");
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
