$(document).ready(function() {
    $("#ArtistInfo").change(function() {
        if (this.checked) $("#ArtistDiv").fadeIn("slow");
        else $("#ArtistDiv").fadeOut("slow");
    });

    $("#ArtworkInfo").change(function() {
        if (this.checked) $("#ArtworkDiv").fadeIn("slow");
        else $("#ArtworkDiv").fadeOut("slow");
    });
});

function SearchTabs(pageId, id) {
    document.getElementById("1").className = "";
    document.getElementById("2").className = "";
    document.getElementById(id).className = "is-active";

    var pageToActivate = document.getElementById(pageId);
    var tabCtrl = document.getElementById("SearchTabs");
    for (var i = 0; i < tabCtrl.childNodes.length; i++) {
        var node = tabCtrl.childNodes[i];
        if (node.nodeType == 1) {
            node.style.display = node == pageToActivate ? "block" : "none";
        }
    }
}