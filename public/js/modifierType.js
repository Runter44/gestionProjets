"use strict";

window.onbeforeunload = function() {
    if ($("#tachesModifierTypeProjet li").length !== parseInt(twigNombreTaches, 10)) {
        return "Les modifications que vous avez apporté ne seront pas enregistrées.";
    } else {
        return null;
    }
};
var collectionHolder;
var ajouterTache = $('<a href="#" class="btn btn-success mt-3"><i class="fa fa-plus"></i> Ajouter une tâche</a>');
$(document).ready(function() {
    collectionHolder = $("ul.typeTaches");
    collectionHolder.append(ajouterTache);
    collectionHolder.data("index", collectionHolder.find(":input").length);
    ajouterTache.click(function(e) {
        e.preventDefault();
        addTacheForm(collectionHolder, ajouterTache);
    });
    $("#tachesModifierTypeProjet li").each(function() {
        ajouterLienDelete($(this));
    });
    $("#invalidAucuneTache").hide();
    $("#formModifierTypeProj").submit(function(event) {
        window.onbeforeunload = null;
        event.preventDefault();

        var doublons = false;
        var vals = [];
        $("#tachesModifierTypeProjet li div div select").each(function(element) {
            if (vals.indexOf($(this).val()) !== -1) {
                doublons = true;
                $(this).addClass("is-invalid");
                $(this).change(function() {
                    $(this).removeClass("is-invalid");
                });
            }
            vals.push($(this).val());
        });

        if (doublons) {
            alert("Vous avez sélectionné plusieurs fois la même tâche !");
            return;
        }

        if ($("#type_projet_nom").val() === "" ||
        $("#tachesModifierTypeProjet li").length === 0
        ) {
            $("#invalidAucuneTache").show();
        } else {
            $.ajax({
                context: this,
                url: "/ajax/nom-type-existe/",
                type: "POST",
                data: {
                    "nomTypeProjet": $("#type_projet_nom").val()
                },
                success: function(data) {
                    if (data !== "" && data !== twigNomProjet) {
                        $("#invalidNomTypeUtilise").show();
                        $("#type_projet_nom").addClass("is-invalid");
                    } else {
                        this.submit();
                    }
                }
            });
        }
    });

    $("#type_projet_nom").change(function() {
        $("#invalidNomUtilise").hide();
        $("#type_projet_nom").removeClass("is-invalid");
    });
});

/**
 * Fonction qui ajoute le select à la liste des tâches
 * @param {HTMLElement} collectionHolder La liste ul des tâches contenant le data-prototype
 * @param {HTMLElement} ajouterTache Le lien pour ajouter une nouvelle tâche
 * @return {null}
 */
function addTacheForm(collectionHolder, ajouterTache) {
    var prototype = collectionHolder.data("prototype");
    var index = collectionHolder.data("index");
    var newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    collectionHolder.data("index", index + 1);
    var liTache = $('<li class="mt-3 list-style-type-none"></li>').append(newForm);
    ajouterLienDelete(liTache);
    ajouterTache.before(liTache);
}

/**
 * Fonction qui ajoute le select à la liste des tâches
 * @param {HTMLElement} liTacheForm Le li contenant le select
 * @return {null}
 */
function ajouterLienDelete(liTacheForm) {
    var bouton = $('<i class="far fa-trash-alt btnSupprimerTache"></i>');
    liTacheForm.find("div div").append(bouton);
    bouton.click(function(e) {
        e.preventDefault();
        liTacheForm.remove();
    });
}
