window.onbeforeunload = function() {
  if ($("#tachesModifierProjet li").length != parseInt(twigNombreTaches)) {
    return "Les modifications que vous avez apporté ne seront pas enregistrées.";
  } else {
    return null;
  }
}
var collectionHolder;
var ajouterTache = $('<a href="#" class="btn btn-success mt-3"><i class="fa fa-plus"></i> Ajouter une tâche</a>');
$(document).ready(function() {
  collectionHolder = $('ul.tacheProjets');
  collectionHolder.append(ajouterTache);
  collectionHolder.data('index', collectionHolder.find(':input').length);
  ajouterTache.click(function(e) {
      e.preventDefault();
      addTacheForm(collectionHolder, ajouterTache);
  });
  $("#tachesModifierProjet li").each(function() {
      ajouterLienDelete($(this));
  })
  $("#invalidAucuneTache").hide();
  $("#formModifierProj").submit(function(event) {
    window.onbeforeunload = null;
    event.preventDefault();

    var doublons = false;
    var vals = [];
    $("#tachesModifierProjet li div div select").each(function(element) {
        if (vals.indexOf($(this).val()) !== -1) {
            doublons = true;
            $(this).addClass("is-invalid");
            $(this).change(function() {
                $(this).removeClass("is-invalid");
            });
        }
        vals.push($(this).val());
    })

    if (doublons) {
        alert("Vous avez sélectionné plusieurs fois la même tâche !");
        return;
    }

    if ($("#projet_name").val() === "" ||  $("#tachesModifierProjet li").length === 0) {
        $("#invalidAucuneTache").show();
        return;
    }
    this.submit();
  });

  $("#projet_name").change(function() {
    $("#invalidNomUtilise").hide();
    $("#projet_name").removeClass("is-invalid");
  });
});
function addTacheForm(collectionHolder, ajouterTache) {
    var prototype = collectionHolder.data('prototype');
    var index = collectionHolder.data('index');
    var newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    collectionHolder.data('index', index + 1);
    var liTache = $('<li class="mt-3 list-style-type-none"></li>').append(newForm);
    ajouterLienDelete(liTache);
    ajouterTache.before(liTache);
}
function ajouterLienDelete(liTacheForm) {
    var bouton = $('<i class="far fa-trash-alt btnSupprimerTache"></i>');
    liTacheForm.find("div div").append(bouton);
    bouton.click(function(e) {
        e.preventDefault();
        liTacheForm.remove();
    });
}
