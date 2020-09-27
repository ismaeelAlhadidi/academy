
var navButton = document.getElementById('navButton'),
    menuOfNavList = document.getElementById('menuOfNavList'),
    searchInput = document.getElementById('searchInput'),
    searchForm = document.getElementById('searchForm'),
    listOverflowButton = document.getElementById('listOverflowButton'),
    listOverflow = document.getElementById('listOverflow');

var backgroudColor = '#0a304e',
    color = '#FFFFFF';
if(navButton != null) {
    navButton.width = 50;
    navButton.height = 50;
    drawNavButtonOpenButton(navButton,backgroudColor,color);
    if(menuOfNavList != null) {
        navButton.onclick = function () {
            if(menuOfNavList.style.display == "none") {
                menuOfNavList.style = "display: block;";
                drawNavButtonCloseButton(navButton,backgroudColor,color);
            }
            else {
                menuOfNavList.style = "display: none;";
                drawNavButtonOpenButton(navButton,backgroudColor,color);
            }
        };
    }
}
if(listOverflowButton != null) {
    listOverflowButton.width = 40;
    listOverflowButton.height = 50;
    drawlistOverflowButton(listOverflowButton,backgroudColor,color);
    if(listOverflow != null) {
        listOverflow.class = "list-overflow";
        listOverflow.setAttribute('class','list-overflow');
        listOverflowButton.onclick = function () {
            if(listOverflow.class == "list-overflow") {
                listOverflow.class = "list-overflow open-list-overflow";
                listOverflow.setAttribute('class','list-overflow open-list-overflow');
            } else {
                listOverflow.class = "list-overflow";
                listOverflow.setAttribute('class','list-overflow');
            }
        };
    }
}
if(searchInput != null && searchForm != null) {
    searchInput.onfocus = function() {
        makeFocusEfectOfInput(searchForm);
    };
    searchInput.onblur = function () {
        removeFocusEfectOfInput(searchForm);
    };
}