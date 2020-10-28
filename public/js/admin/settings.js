( function setEvents() {
    var editImageButton = document.getElementById('editImageButton'),
        inputFileElement = document.getElementById('inputFileElement'),
        imageElement = document.getElementById('imageElement');

    if(editImageButton != null && inputFileElement != null) {
        editImageButton.onclick = function () {
            inputFileElement.click();
        };
    }
    if(inputFileElement != null && imageElement != null) {
        inputFileElement.onchange = function () {
            if(inputFileElement.files.length == 1) {
                if(window.File && window.FileList && window.FileReader) {
                    if(inputFileElement.files[0].type.match('image')) {
                        var fileReader = new FileReader();
                        fileReader.addEventListener("load",function(event) {
                            var picFile = event.target;
                            imageElement.setAttribute('src',picFile.result);
                        });
                        fileReader.readAsDataURL(inputFileElement.files[0]);
                    } else {
                        showPopUpMassage(lang.selectImage);
                    }
                } else {
                    showPopUpMassage(lang.yourBrowserNotSupport);
                }
            }
        };
    }
})();