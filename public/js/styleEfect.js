var popUpMassageDiv = document.getElementById('popUpMassageDiv');

function makeFocusEfectOfInput(element) {
    "use strict"
    if(element != null) {
        element.setAttribute('class',element.getAttribute('class') + ' input-focus');
    }
}
function removeFocusEfectOfInput(element) {
    "use strict"
    if(element != null) element.setAttribute('class',element.getAttribute('class').replace(' input-focus',''));
}

function createAndReturnGlobalProgressPopUpTemplate() {
    "use strict"
    var ParentDiv = document.createElement('div');
    var ChildDiv = document.createElement('div');
    ParentDiv.setAttribute('id','globalProgressPopUpTemplate');
    ParentDiv.setAttribute('class','default-pop-up-ok-massege no-select');
    ParentDiv.setAttribute('style','display:none !important;');
    ParentDiv.style = 'display:none !important;';

    var header = document.createElement('header');
    var section = document.createElement('section');
    var footer = document.createElement('footer');

    var headerTitle = document.createElement('span');
    headerTitle.setAttribute('style', 'display: block;width: 100%;color: #ffffff;text-align: center;padding-bottom: 7px;');
    header.appendChild(headerTitle);

    var tempProgress = document.createElement('div');
    tempProgress.innerHTML = '<div style="width: 0%;"></div><span>0%</span>';
    section.setAttribute('id', 'globalProgressPopUpTemplateProgressElement');
    section.setAttribute('class', 'default-progress');
    section.appendChild(tempProgress);

    ChildDiv.appendChild(header);
    ChildDiv.appendChild(section);
    ChildDiv.appendChild(footer);

    ParentDiv.appendChild(ChildDiv);
    if(document.body.children.length > 0) document.body.insertBefore(ParentDiv,document.body.children[0]);
    else document.body.appendChild(ParentDiv);
    return ParentDiv;
}
function showGlobalProgressPopUpTemplate(title = null) {
    var tempGlobalProgressPopUpTemplate = document.getElementById("globalProgressPopUpTemplate");
    if(tempGlobalProgressPopUpTemplate == null) {
        tempGlobalProgressPopUpTemplate = createAndReturnGlobalProgressPopUpTemplate();
        if(tempGlobalProgressPopUpTemplate == null) return null;
    }
    if(title != null) {
        if(tempGlobalProgressPopUpTemplate.children.length > 0) {
            if(tempGlobalProgressPopUpTemplate.children[0].children.length > 0) {
                if(tempGlobalProgressPopUpTemplate.children[0].children[0].children.length > 0) {
                    tempGlobalProgressPopUpTemplate.children[0].children[0].children[0].textContent = title;
                }   
            }
        }
    }

    tempGlobalProgressPopUpTemplate.setAttribute('style', '');

    var tempGlobalProgressPopUpTemplateProgressElement = document.getElementById("globalProgressPopUpTemplateProgressElement");
    return tempGlobalProgressPopUpTemplateProgressElement;
}
function closeGlobalProgressPopUpTemplate() {
    var tempGlobalProgressPopUpTemplate = document.getElementById("globalProgressPopUpTemplate");
    if(tempGlobalProgressPopUpTemplate != null) {
        tempGlobalProgressPopUpTemplate.setAttribute('style', 'display: none !important;');
        tempGlobalProgressPopUpTemplate.style = 'display:none !important;';
    }
}

function showPopUpMassage(content,exitButtonListener = null,okButtonListener = null, buttonContent = 'ok',data = null) {
    if(popUpMassageDiv == null) {
        popUpMassageDiv = createAndReturnPopUpMassageDiv();
        if(popUpMassageDiv == null) return;
    }
    if(popUpMassageDiv.children.length > 0) {
        if(popUpMassageDiv.children[0].children.length > 2) {
            popUpMassageDiv.children[0].children[1].textContent = content;
            var exitThis = function (popUpMassageDiv) {
                popUpMassageDiv.setAttribute('style','display:none !important;');
                popUpMassageDiv.style = 'display:none !important;';
            };
            var exitButtonListenerDefault = function (){
                exitThis(popUpMassageDiv);
            };
            var okButtonListenerDefault = function() {
                exitThis(popUpMassageDiv);
            };
            if(exitButtonListener != null) {
                exitButtonListenerDefault = function() {
                    exitButtonListener(exitThis,popUpMassageDiv,data);
                };
            }
            if(okButtonListener != null) {
                okButtonListenerDefault = function() {
                    okButtonListener(exitThis,popUpMassageDiv,data);
                };
            }
            if(popUpMassageDiv.children[0].children[0].children.length > 0) {
                popUpMassageDiv.children[0].children[0].children[0].onclick = exitButtonListenerDefault;
            }
            if(popUpMassageDiv.children[0].children[2].children.length > 0) {
                popUpMassageDiv.children[0].children[2].children[0].textContent = buttonContent;
                popUpMassageDiv.children[0].children[2].children[0].onclick = okButtonListenerDefault;
            }
            popUpMassageDiv.setAttribute('style','');
            popUpMassageDiv.style = '';
        }
    }
}
function createAndReturnPopUpMassageDiv() {
    "use strict"
    var ParentDiv = document.createElement('div');
    var ChildDiv = document.createElement('div');
    ParentDiv.setAttribute('id','popUpMassageDiv');
    ParentDiv.setAttribute('class','default-pop-up-ok-massege no-select');
    ParentDiv.setAttribute('style','display:none !important;');
    ParentDiv.style = 'display:none !important;';

    var header = document.createElement('header');
    var section = document.createElement('section');
    var footer = document.createElement('footer');
    var exitThis = function () {
        ParentDiv.setAttribute('style','display:none !important;');
        ParentDiv.style = 'display:none !important;';
    };

    var headerExitButton = document.createElement('div');
    var canvas = makeRemoveIconCanvas('#ffffff');
    headerExitButton.appendChild(canvas);
    headerExitButton.onclick = exitThis;
    header.appendChild(headerExitButton);

    var footerExitButton = document.createElement('button');
    footerExitButton.textContent = 'OK';
    footerExitButton.onclick = exitThis;
    footer.appendChild(footerExitButton);

    ChildDiv.appendChild(header);
    ChildDiv.appendChild(section);
    ChildDiv.appendChild(footer);

    ParentDiv.appendChild(ChildDiv);
    if(document.body.children.length > 0) document.body.insertBefore(ParentDiv,document.body.children[0]);
    else document.body.appendChild(ParentDiv);
    return ParentDiv;
}
function makeRemoveIconCanvas(color) {
    "use strict"
    var canvas = document.createElement('canvas');
    canvas.width = 20;
    canvas.height = 20;
    drawRemoveIconCanvas(canvas,color);
    return canvas;
}
function closeBobUpTemplate(template) {
    "use strict"
    if(template != null) {
        template.setAttribute('style','display:none !important;');
        template.style = 'display:none !important;';
    }
}
function makeGeneralPaginationLinks(paginator, linksClickHandler) {
    if( ! paginator.hasOwnProperty('last_page') || 
        ! paginator.hasOwnProperty('path') || 
        ! paginator.hasOwnProperty('current_page') || 
        ! paginator.hasOwnProperty('first_page_url') ||
        ! paginator.hasOwnProperty('last_page_url') ) return null;
    
    if(paginator.last_page == 1) return null;
    var linkElement = document.createElement('div');
    linkElement.setAttribute('class','pagination no-select');
    var buttonText = '<';
    var active = false;
    var disabled = false;
    var element = null;
    for(var i = paginator.last_page + 1; i >= 0; i--) {
        var path = paginator.path + '?page=';
        disabled = false;
        if(i == paginator.current_page) active = true;
        else {
            active = false;
        }
        if(i == 0) {
            path = paginator.first_page_url;
            buttonText = '<';
            if(paginator.current_page == 1)disabled = true;
        }
        else if(i == paginator.last_page + 1) {
            path = paginator.last_page_url;
            buttonText = '>';
            if(paginator.current_page == paginator.last_page)disabled = true;
        } else {
            buttonText = i;
            path += i;
        }
        element = createGeneralLinkPageItem(path,buttonText,active,disabled,linksClickHandler);
        if(element != null && element != false) linkElement.appendChild(element);
    }
    return linkElement;
}
function createGeneralLinkPageItem(path,buttonText,active,disabled,linksClickHandler) {
    var span = document.createElement('span'),
        a = document.createElement('a');
    span.setAttribute('class','page-item');
    if(active)span.setAttribute('class','page-item active');
    if(disabled)span.setAttribute('class','page-item disabled');
    a.setAttribute('class','page-link');
    if(! disabled) {
        a.onclick = function () {
            linksClickHandler(path);
        };
    }
    a.textContent = buttonText;
    span.appendChild(a);
    return span;
}
function makeTopRightSmallNotifcation(content,title = null, image = null, time = null) {
    console.log(content);
}