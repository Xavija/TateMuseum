$(document).ready(function() {
    $("#ArtistInfo").change(function() {
        if (this.checked) $("#ArtistDiv").fadeIn("slow");
        else $("#ArtistDiv").fadeOut("slow");
    });

    $("#DatesInfo").change(function() {
        if (this.checked) $("#DatesDiv").fadeIn("slow");
        else $("#DatesDiv").fadeOut("slow");
    });

    $("#ArtworkInfo").change(function() {
        if (this.checked) $("#ArtworkDiv").fadeIn("slow");
        else $("#ArtworkDiv").fadeOut("slow");
    });
});

function activateTab(pageId, id) {
    document.getElementById("1").className = "";
    document.getElementById("2").className = "";
    document.getElementById("3").className = "";
    document.getElementById(id).className = "is-active";

    var pageToActivate = document.getElementById(pageId);
    var tabCtrl = document.getElementById("tabCtrl");
    for (var i = 0; i < tabCtrl.childNodes.length; i++) {
        var node = tabCtrl.childNodes[i];
        if (node.nodeType == 1) {
            node.style.display = node == pageToActivate ? "block" : "none";
        }
    }
}
