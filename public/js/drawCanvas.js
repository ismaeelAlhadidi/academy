function drawNavButtonOpenButton(navButton,backgroundColor,color) {
    "use strict"
    var navButtonContext = navButton.getContext('2d');
    var padding = (navButton.width/3.7),w = navButton.width-padding, h = navButton.height-padding;
    navButtonContext.fillStyle = backgroundColor;
    navButtonContext.fillRect(0,0,w+padding,h+padding);
    navButtonContext.fillStyle = color;
    for(var i = 0; i < 7; i++)if(i%2 != 0)navButtonContext.fillRect((w/10)+padding/2,((h/7)*(i))+padding/2,w*0.8,h/7);
}
function drawNavButtonCloseButton(navButton,backgroundColor,color) {
    "use strict"
    var navButtonContext = navButton.getContext('2d');
    var padding = (navButton.width/3.7),w = navButton.width-padding, h = navButton.height-padding;
    navButtonContext.fillStyle = backgroundColor;
    navButtonContext.fillRect(0,0,w+padding,h+padding);
    navButtonContext.strokeStyle = color;
    navButtonContext.beginPath();
    navButtonContext.moveTo(padding,padding);
    navButtonContext.lineTo(w,h);
    navButtonContext.moveTo(padding,h);
    navButtonContext.lineTo(w,padding);
    navButtonContext.stroke();
}
function drawlistOverflowButton(listOverflowButton,backgroundColor,color) {
    "use strict"
    var context = listOverflowButton.getContext('2d');
    var padding = (listOverflowButton.width/3.7), temp = (listOverflowButton.height/6),w = listOverflowButton.width-padding, h = listOverflowButton.height-padding;
    context.fillStyle = backgroundColor;
    context.fillRect(0,0,w+padding,h+padding);
    context.fillStyle = color;
    for(var i = 2; i <= 4; i++) {
        context.beginPath();
        context.arc((w+padding)/2,temp*i,2.5,0,Math.PI*2);
        context.fill();
    }
}
function drawRemoveIconCanvas(canvas,color) {
    "use strict"
    if(canvas != null) {
        var padding = canvas.width/8;
        var w = canvas.width-2*padding, h = canvas.height-2*padding;
        var context = canvas.getContext('2d');
        context.strokeStyle = color;
        context.lineWidth = 3;
        context.beginPath();
        context.moveTo(padding,padding);
        context.lineTo(w,h);
        context.moveTo(w,padding);
        context.lineTo(padding,h);
        context.stroke();
    }
}
function drawShowMoreButton(canvas,color) {
    "use strict"
    if(canvas != null) {
        var padding = canvas.width/8,w = canvas.width-2*padding, h = canvas.height-2*padding;
        var context = canvas.getContext('2d');
        context.clearRect(0,0,w+2*padding,h+2*padding);
        context.strokeStyle = color;
        context.lineWidth = 3;
        context.beginPath();
        context.moveTo(padding,(h/4)*3);
        context.lineTo((w+(2*padding))/2,h/4);
        context.lineTo(w+padding,(h/4)*3);
        context.stroke();
    }
}
function drawShowLessButton(canvas,color) {
    "use strict"
    if(canvas != null) {
        var padding = canvas.width/9,w = canvas.width-2*padding, h = canvas.height-2*padding;
        var context = canvas.getContext('2d');
        context.clearRect(0,0,w+2*padding,h+2*padding);
        context.strokeStyle = color;
        context.lineWidth = 3;
        context.beginPath();
        context.moveTo(padding,h/4);
        context.lineTo((w+(2*padding))/2,(h/4)*3);
        context.lineTo(w+padding,h/4);
        context.stroke();
    }
}
function drawCorrectSign(canvas, color, withCircel) {
    "use strict"
    if(canvas != null) {
        var padding = canvas.width/9,w = canvas.width-2*padding, h = canvas.height-2*padding;
        var context = canvas.getContext('2d');
        context.strokeStyle = color;
        context.lineWidth = 2;
        context.beginPath();
        context.moveTo(padding+w/4,h-h/2+padding);
        context.lineTo(padding+(w/4)*2,h-padding/2);
        context.lineTo(padding+(w/4)*3.3,padding*2.8);
        context.stroke();
        if(withCircel) {
            context.beginPath();
            context.arc(canvas.width/2, canvas.height/2, w/2, 0, Math.PI*2, false);
            context.stroke();
        }
    }
}
function drawChart(canvas,title,values,borderColor,valuesColor) {
    "use strict"
    if(canvas != null && Array.isArray(values)) {
        var wPadding = canvas.width*0.1, hPadding = canvas.height*0.1,
            w = canvas.width*0.8, h = canvas.height*0.8;
        var context = canvas.getContext('2d');
        var max = getMaxOfArray(values),
            length = values.length;
        var min = getMinOfArray(values),
            different = max - min,
            increment = 1;
        
        if(different > 20) {
            increment = different/10;
        }
        var top = (max+max/6),
            space = h/top;
        context.strokeStyle = borderColor;
        for(var i = 0; i <= top; i+=increment) {
            context.fillText(Math.floor(i),wPadding/1.2,(hPadding + h)-i*space);
            context.moveTo(wPadding,(hPadding + h)-i*space);
            context.lineTo(w+wPadding,(hPadding + h)-i*space);
            context.stroke();
        }
        var xSpace = w/length;
    
        for(var i = 1; i < length; i++) {
            context.fillText(i,wPadding+i*xSpace,(hPadding + h) + hPadding/1.5);
        }
        context.beginPath();
        context.strokeStyle = valuesColor;
        context.fillStyle = valuesColor;
        for(var i = 0; i < length-1; i++) {
            context.beginPath();
            context.moveTo(wPadding+i*xSpace,(hPadding + h)-values[i]*space);
            context.lineTo(wPadding+(i+1)*xSpace,(hPadding + h)-(values[i+1])*space);
            context.stroke();
        }
        context.beginPath();
        for(var i = 0; i < length; i++) {
            context.moveTo(wPadding+i*xSpace,(hPadding + h)-values[i]*space);
            context.arc(wPadding+i*xSpace,(hPadding + h)-values[i]*space,5,0,Math.PI*2,false);
            context.fill();
        }

        context.beginPath();
        context.fillStyle = '#000000';
        context.font = "20px Arial";
        context.textAlign = 'center';
        context.fillText(title,(wPadding*2+w)/2,hPadding/1.3);
    }
}

function getMaxOfArray(array) {
    if(array.length > 0) {
        var max = array[0];
        for(var i = 0; i < array.length; i++) {
            if(array[i] > max) max = array[i];
        }
        return max;
    }
    return false;
}

function getMinOfArray(array) {
    if(array.length > 0) {
        var min = array[0];
        for(var i = 0; i < array.length; i++) {
            if(array[i] < min) min = array[i];
        }
        return min;
    }
    return false;
}