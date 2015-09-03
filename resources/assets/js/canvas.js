window.fbAsyncInit = function() {
  FB.init({
    appId      : '1480631925592204',
    status     : true,
    xfbml      : true,
    version    : 'v2.4'
  });
};

(function(d, s, id){
   var js, fjs = d.getElementsByTagName(s)[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement(s); js.id = id;
   js.src = "//connect.facebook.net/en_US/sdk.js";
   fjs.parentNode.insertBefore(js, fjs);
 }(document, 'script', 'facebook-jssdk'));

/************************ PAGE INITIALISATION *************************/
$(function(){
	$("#fontFamily").change(function() {
		setActiveProp("fontFamily", $("#fontFamily").val());
	});
	$("#textAlign").change(function() {
		setActiveProp('textAlign', $("#textAlign").val());
	});
});

if (window.location.href.indexOf('#_=_') > 0) {
	window.location = window.location.href.replace(/#.*/, '');
}






/************************ GLOBAL VARIABLES *************************/
var canvas;
var bgselected;				// Image ID setter for background
var fntcolor = '#000000';	// Colour setter for new text
var drawcolor = '#FF8B17';	// Colour setter for new block
var orig;					// Holds last selected/modified object (for clone)
var atLimit;				// Holds last acceptable position
var lastSave;				// Holds last save state (for undo)
var currSave;				// Holds current state (for redo)
var isSaved;				// Counter for whether changes have been made
var idleTime = 0;			// Counter for number of minutes user is idle
var idleInterval;			// Holds ID for setInterval() idle timer (for reset)

var	drawingModeEl,
	drawingOptionsEl,
	drawingColorEl,
	drawingShadowColorEl,
	drawingLineWidthEl,
	drawingShadowWidth,
	drawingShadowOffset,
	eraserModeEl,
	undoEl,
	redoEl,
	clearEl,
	imageBtnEl;

var pointer,
	penTool,
	eraserTool,
	clearCan,
	undoButton,
	redoButton,
	uploadButton,
	textInput,
	downloadEl;



/************************ BOARD INITIALISATION *************************/
// Self Invoking / Init function
(function() {
	var $ = function(id){return document.getElementById(id)};

	canvas = this.__canvas = new fabric.Canvas('canvas', {
		isDrawingMode: true,
		selection: false
	});
	canvas.setWidth(window.innerWidth-200);
	canvas.setHeight(window.innerHeight-100);

	addCanvasListeners();
	addWindowListeners();
	resizeCanvas();
	//promptBoardEmpty();
	initColPickers();
	initHistory();

	fabric.Object.prototype.transparentCorners = false;
	fabric.Object.prototype.perPixelTargetFind = true;
	fabric.Text.prototype.perPixelTargetFind = false;
	fabric.Image.prototype.perPixelTargetFind = false;

	drawingOptionsEl = $('drawing-mode-options'),
	// drawingColorEl = $('drawing-color'),
	drawingShadowColorEl = $('drawing-shadow-color'),
	drawingLineWidthEl = $('drawing-line-width'),
	drawingShadowWidth = $('drawing-shadow-width'),
	drawingShadowOffset = $('drawing-shadow-offset');
	eraserModeEl = $('eraser-mode');
	clearEl = $('clear-canvas');
	undoEl = $('undo-btn');
	redoEl = $('redo-btn');
	imageBtnEl = $('addimage');

	pointer 		= $('pointer');
	penTool 		= $('pentool');
	eraserTool 		= $('eraser');
	clearCan 		= $('clearcanvas');
	undoButton 		= $('undo');
	redoButton 		= $('redo');
	uploadButton 	= $('uploadimages');
	textInput 		= $('inputtext');
	downloadEl 		= $('download');
	boldBtn         = $('textBold');
	italicBtn       = $('textItalic');
	underlineBtn    = $('textUnderline');
	gifmaker        = $('gif');

	postOnFb = $('postOnFacebook');

	pointer.onclick 		= disableDrawAndEraser;
	penTool.onclick 		= drawingModeOn;
	eraserTool.onclick 		= eraserModeOn;
	clearCan.onclick 		= clearCanvas;
	undoButton.onclick 		= undo;
	redoButton.onclick 		= redo;
	uploadButton.onclick 	= openUploader;
	textInput.onclick 		= addText;
	downloadEl.onclick 		= downloadCanvas;
	boldBtn.onclick 		= toggleTextBold;
	italicBtn.onclick 		= toggleTextItalic;
	underlineBtn.onclick 	= toggleTextUnderline;
	postOnFb.onclick = postOnFacebook;
	gifmaker.onclick = gifMake;

	// TODO: CONSIDER REMOVING PATTERN BRUSH
	if (fabric.PatternBrush) {
		var vLinePatternBrush = new fabric.PatternBrush(canvas);
		vLinePatternBrush.getPatternSrc = function() {

			var patternCanvas = fabric.document.createElement('canvas');
			patternCanvas.width = patternCanvas.height = 10;
			var ctx = patternCanvas.getContext('2d');

			ctx.strokeStyle = this.color;
			ctx.lineWidth = 5;
			ctx.beginPath();
			ctx.moveTo(0, 5);
			ctx.lineTo(10, 5);
			ctx.closePath();
			ctx.stroke();

			return patternCanvas;
		};

		var hLinePatternBrush = new fabric.PatternBrush(canvas);
		hLinePatternBrush.getPatternSrc = function() {

			var patternCanvas = fabric.document.createElement('canvas');
			patternCanvas.width = patternCanvas.height = 10;
			var ctx = patternCanvas.getContext('2d');

			ctx.strokeStyle = this.color;
			ctx.lineWidth = 5;
			ctx.beginPath();
			ctx.moveTo(5, 0);
			ctx.lineTo(5, 10);
			ctx.closePath();
			ctx.stroke();

			return patternCanvas;
		};

		var squarePatternBrush = new fabric.PatternBrush(canvas);
		squarePatternBrush.getPatternSrc = function() {

			var squareWidth = 10, squareDistance = 2;

			var patternCanvas = fabric.document.createElement('canvas');
			patternCanvas.width = patternCanvas.height = squareWidth + squareDistance;
			var ctx = patternCanvas.getContext('2d');

			ctx.fillStyle = this.color;
			ctx.fillRect(0, 0, squareWidth, squareWidth);

			return patternCanvas;
		};

		var diamondPatternBrush = new fabric.PatternBrush(canvas);
		diamondPatternBrush.getPatternSrc = function() {

			var squareWidth = 10, squareDistance = 5;
			var patternCanvas = fabric.document.createElement('canvas');
			var rect = new fabric.Rect({
				width: squareWidth,
				height: squareWidth,
				angle: 45,
				fill: this.color
			});

			var canvasWidth = rect.getBoundingRectWidth();

			patternCanvas.width = patternCanvas.height = canvasWidth + squareDistance;
			rect.set({ left: canvasWidth / 2, top: canvasWidth / 2 });

			var ctx = patternCanvas.getContext('2d');
			rect.render(ctx);

			return patternCanvas;
		};

		var img = new Image();
		img.src = '../images/testPattern.png';

		var texturePatternBrush = new fabric.PatternBrush(canvas);
		texturePatternBrush.source = img;
	}

	$('drawing-mode-selector').onchange = function() {

		if (this.value === 'hline') {
			canvas.freeDrawingBrush = vLinePatternBrush;
		}
		else if (this.value === 'vline') {
			canvas.freeDrawingBrush = hLinePatternBrush;
		}
		else if (this.value === 'square') {
			canvas.freeDrawingBrush = squarePatternBrush;
		}
		else if (this.value === 'diamond') {
			canvas.freeDrawingBrush = diamondPatternBrush;
		}
		else if (this.value === 'texture') {
			canvas.freeDrawingBrush = texturePatternBrush;
		}
		else {
			canvas.freeDrawingBrush = new fabric[this.value + 'Brush'](canvas);
		}

		if (canvas.freeDrawingBrush) {
			canvas.freeDrawingBrush.color = drawcolor;
			canvas.freeDrawingBrush.width = parseInt(drawingLineWidthEl.value, 10) || 1;
		}
	};

	// drawingColorEl.onchange = function() {
	// 	canvas.freeDrawingBrush.color = this.value;
	// };
	drawingLineWidthEl.onchange = function() {
		canvas.freeDrawingBrush.width = parseInt(this.value, 10) || 1;
		$('drawing-line-width-px').innerHTML = this.value + "px";
	};
	drawingLineWidthEl.value = 30;


	if (canvas.freeDrawingBrush) {
		canvas.freeDrawingBrush.color = drawcolor;
		canvas.freeDrawingBrush.width = parseInt(drawingLineWidthEl.value, 10) || 1;
		canvas.freeDrawingBrush.shadowBlur = 0;
	}
})();


function initColPickers(){
	// Initialise Colour Pickers
	$('#drawing-col').colpick({
		colorScheme:'dark',
		layout:'hex',
		submit:0,
		color: drawcolor,
		onChange:function(hsb,hex,rgb,el,bySetColor) {
			$(el).css('background-color','#'+hex);
			drawcolor = '#'+hex;
			canvas.freeDrawingBrush.color = drawcolor;
		}
	}).css('background-color', drawcolor);
}



/************************ BOARD LISTENERS *************************/
function addWindowListeners() {
	$(document).on('keydown', function(e){
		if(e.ctrlKey) {  // If ctrl is pressed
			switch(e.which){
				case 89: // Y
				redo();
				e.preventDefault();
				break;
				case 90: // Z
				undo();
				e.preventDefault();
				break;
			}
		} else {
			switch(e.which) {
				case 8: // backspace
				if (!canvas.isDrawingMode && !inEraserMode)
					e.preventDefault();
				break;
				case 46: // del
				deleteActiveObj();
				break;
			}
		}
	});
	window.addEventListener('resize', resizeCanvas, false);
	// window.onbeforeunload = function(e){	// Page-leave functionality
	// 	if(!isSaved)
	// 		saveCanvas(false);
	// 	timedOut(false);
	// };
}

function addCanvasListeners() {
	canvas.on({
		'object:added' : onObjAdded,
		'object:modified': onObjModified,
		'object:scaling': onObjScaling,
		'object:rotating': onObjRotating,
		'object:removed': onObjRemoved,
		'object:selected': onObjSelected,
		'path:created': onPathCreated,
		'selection:cleared': onSelectionCleared,
		'mouse:up': onMouseUp,
		'mouse:down': onMouseDown,
		'mouse:move': onMouseMove,
	});
}

function onObjAdded(e) {
	if (histWorking) {
		// Object "type"s seem to only appear AFTER a canvas
		// is loaded from a save. This section of code is a workaround
		// to ensure that brush strokes are not selectable.
		var curr = canvasLastObj();
		if (curr.type == "path" || curr.type == "group")
			setLastObjUnselectable();
	} else {
		updateHistory();
	}
}

function onObjModified(e) {
	checkOOB(e);
	updateHistory();
}

function onObjRemoved(e) {
	updateHistory();
}

function onObjScaling(e) {
	setLimit(e);
}

function onObjRotating(e) {
	setLimit(e);
}

// Save original state for clone (on selection)
// Triggers switchTab(), updates InfoWindow
function onObjSelected(e) {
	// TODO/NOTE: UNTOUCHED FROM PREV PROJECT
	orig = fabric.util.object.clone(e.target);
	atLimit = {
		top: orig.getTop(),
		left: orig.getLeft(),
		scaleX: orig.getScaleX(),
		scaleY: orig.getScaleY(),
		angle: orig.getAngle(),
	};
	//switchTab();
	//updateInfoWin(orig);
}

function onPathCreated(e) {
	setLastObjUnselectable();
}

function onSelectionCleared() {
	// if (!canvas.isDrawingMode) {
	// 	resetInfoWin();
	// 	switchTab("create");
	// }
}

function onMouseDown(ev) {
	if (inEraserMode) {
		erase(ev);
	}
}

function onMouseMove(ev) {
	if (inEraserMode) {
		erase(ev);
	}
}

function onMouseUp(ev) {
	if (inEraserMode) {
		erase(ev);
	}
}




/************************ FREEDRAW/ERASE FUNCTIONS *************************/
function toggleDrawingMode() {
	if (canvas.isDrawingMode) {
		drawingModeOff();
	} else {
		drawingModeOn();
	}
}

function drawingModeOn() {
	eraserModeOff();
	if($("#pentool").hasClass("normal")){
	removeHightlight();
	$("#pentool").removeClass("normal").addClass("highlight");
	}
	canvas.isDrawingMode = true;
	canvas.discardActiveObject();
}

function drawingModeOff() {
	canvas.isDrawingMode = false;
}


var inEraserMode = false;
function toggleEraserMode() {
	if (inEraserMode) {
		eraserModeOff();
	} else {
		eraserModeOn();
	}
}

function eraserModeOn() {
	drawingModeOff();
	if($("#eraser").hasClass("normal")){
		removeHightlight();
		$("#eraser").removeClass("normal").addClass("highlight");
	}

	inEraserMode = true;
	canvas.defaultCursor = 'cell';

	canvas.discardActiveObject();
	canvas.selection = false;
	canvas.forEachObject(function(o) {
		o.selectable = false;
	});

	// fabric.Object.prototype.perPixelTargetFind = true;
	canvas.renderAll();
}

function eraserModeOff() {
	inEraserMode = false;
		
	canvas.defaultCursor = 'default';

	canvas.forEachObject(function(o) {
		if(o.type !== 'path' && o.type !== 'group')
			o.selectable = true;
	});
	// fabric.Object.prototype.perPixelTargetFind = false;
	canvas.renderAll();
}

function disableDrawAndEraser() {
	drawingModeOff();
	eraserModeOff();
	if($("#pointer").hasClass("normal")){
	removeHightlight();
	$("#pointer").removeClass("normal").addClass("highlight");
	}
}

function removeHightlight(){
	$("#sidebarmenu").find(":button").each(function(){
		if($(this).hasClass("highlight")){
			$(this).removeClass("highlight").addClass("normal");
		}			
	})
	
}


var erasing = false;
function erase(ev) {
	switch(ev.e.type.toLowerCase()) {
		case 'mousedown':
		erasing = true;
		identifyAndErase(ev);
		break;
		case 'mousemove':
		if(erasing)
			identifyAndErase(ev);
		break;
		case 'mouseup':
		erasing = false;
		break;
	}
}

function identifyAndErase(ev) {
	var mouseEv = ev.e;
	var target = canvas.findTarget(mouseEv, true);
	if (target !== undefined) {
		if (target.type =="path") {
			eraseArea(target,mouseEv);
		} else if (target.type == "group") {
			canvas.remove(target);
		}
	}
}

function withinCursorRadius(target, pointX, pointY, mouseEv) {
	var mouseX = mouseEv.layerX;
	var mouseY = mouseEv.layerY;
	var dist = Math.sqrt(Math.pow(mouseX-pointX, 2) + Math.pow(mouseY-pointY,2));
	return dist < target.strokeWidth;
}

function eraseArea(target, mouseEv) {
	// Get where the eraser hits
	var numNodes = target.path.length;
	var toSplit = [0];
	var numDel = 0;
	for (var i=0; i<numNodes; i++) {
		var curr = target.path[i];
		if (withinCursorRadius(target,curr[1],curr[2],mouseEv)) {
			curr[0] = "L";
			if (curr.length>=3)
				curr.splice(3);
			if (i>0) {
				curr[1] = target.path[i-1][1];
				curr[2] = target.path[i-1][2];
			}
			if (i<numNodes-1 && target.path[i+1][0]=="Q"){
				target.path[i+1][0] = "M";
				target.path[i+1].splice(3);
			}
		}
		if (curr[0]=="L") {
			numDel++;
		}
	}
	if (numDel>=numNodes-2)
		canvas.remove(target);
	updateHistory();
	canvas.renderAll();
}



/************************ IMAGE UPLOAD FUNCTIONS *************************/
var uploader;
function initUploader() {
	uploader = document.createElement("input");
	uploader.type = 'file';
	uploader.accept = 'image/*';
	uploader.multiple = false;
	// TODO: Consider allowing multiple
}

// Inits uploader if not already done
// Then opens upload window
function openUploader() {
	if (!uploader) {
		initUploader();
	}
	uploader.onchange = readUploader;
	uploader.click();
}

// Reads file from uploader and acts on it
function readUploader(element) {
	var newImgDataUrl = "";
	var file = uploader.files[0];
	var reader  = new FileReader();

	reader.onloadend = function() {
		var dataUrl = reader.result;
		if (isValidImage(dataUrl)) {
			fabric.Image.fromURL(reader.result, addImageToCanvas);
		} else {
			alert("Invalid file! We only accept png, gif, bmp and jpg!");
		}
	}

	// Calls the above listener when done reading
	if (file) {
		reader.readAsDataURL(file);
	}

	uploader.removeAttribute("onchange");
}

function addImageToCanvas(img) {
	disableDrawAndEraser();

	if (img.getHeight()>img.getWidth()){
		img.scale((canvas.getHeight()/img.getHeight())/2);
	} else {
		img.scale((canvas.getWidth()/img.getWidth())/2);
	}
	img.set("left", canvas.getWidth()/2);
	img.set("top", canvas.getHeight()/2);
	setDefSettings(img); // TODO: check if necessary

	try {
		canvas.add(img);
		canvas.setActiveObject(img);
	} catch(err) {
		undo();
		updateHistory();
		alert("Invalid file!");
	}
}

function isValidImage(dataUrl) {
	var fHeader = getFHeaderFromDataUrl(dataUrl);

	// Values from https://en.wikipedia.org/wiki/List_of_file_signatures
	switch(fHeader) {
		case "89504e47": 	// png
		case "47494638": 	// gif
		case "ffd8ffe0": 	// jpg
		case "ffd8ffe1": 	// jpg
		case "ffd8ffe2": 	// jpg
			return true;
	}

	if (fHeader == "424D")	// bmp
		return true;

	return false;
}

// Adapted from http://stackoverflow.com/questions/6850276/how-to-convert-dataurl-to-file-object-in-javascript
function getFHeaderFromDataUrl(dataUrl) {
	var arr = dataUrl.split(',');
	var mime = arr[0].match(/:(.*?);/)[1];
	var bstr = atob(arr[1]);
	var n = bstr.length;
	// var u8arr = new Uint8Array(n); originally to store bstr.charCodeAt(n)

	var hexStr = "";
	for (var i=0; i<4 && i<n; i++) {
		hexStr += bstr.charCodeAt(i).toString(16);
	}

	return hexStr;
}




/************************ CANVAS/MISC FUNCTIONS *************************/
function setLastObjUnselectable() {
	// Can't seem to determine if an object is a drawing
	// Text objects turned up as 'path's as well.
	canvasLastObj().selectable = false;
}

function canvasLastObj() {
	return canvas._objects[canvas._objects.length-1];
}

function deleteActiveObj() {
	canvas.remove(canvas.getActiveObject());
	canvas.discardActiveObject();
}

var currBgCol;
var currBgImg;
var activeObj;

function clearBackgroundColor(){
	currBgCol = canvas.backgroundColor;
	currBgImg = canvas.backgroundImage;
	
	if (canvas.backgroundColor=='' && canvas.backgroundImage==null)
    canvas.setBackgroundColor('#FFFFFF');

	activeObj = canvas.getActiveObject();
	canvas.discardActiveObject();

}

function resetBackgroundColor(){
	if (currBgCol) 
		canvas.setBackgroundColor(currBgCol);
	else if (currBgImg) 
		canvas.setBackgroundImage(currBgImg);
	else{
	canvas.setBackgroundColor(null);
	}

	if(activeObj)
		canvas.setActiveObject(activeObj);

	canvas.renderAll();
	currBgCol = null;
	currBgImg = null;
	activeObj = null;
}

function downloadCanvas() {
	removeHightlight();
	// Normally transparent because default dataURL is .png
	clearBackgroundColor();
	
	// Create temp link and activate download
	var link = document.createElement("a");
	link.download = "scribl";
	link.href = canvas.toDataURL();
	link.click();
	// alert(link);

	// Restore background state
	// TODO: CONSIDER USING .JPG FORMAT
	resetBackgroundColor();
	// changeHighlight();
}

/** RESIZE CODE FROM http://htmlcheats.com/html/resize-the-html5-canvas-dyamically/ **/
// Display custom canvas.
// In this case it's a blue, 5 pixel border that 
// resizes along with the browser window.					
// function redraw() {
// 	context.strokeStyle = 'blue';
// 	context.lineWidth = '5';
// 	context.strokeRect(0, 0, window.innerWidth, window.innerHeight);
// }

// Runs each time the DOM window resize event fires.
// Resets the canvas dimensions to match window,
// then draws the new borders accordingly.
function resizeCanvas() {
	canvas.setWidth(window.innerWidth - $('#sidebar').width());
	canvas.setHeight(window.innerHeight);
	canvas.calcOffset();
}


function postOnFacebook() {
	$.post('/facebook/post', {
		'_token': $('meta[name=csrf-token]').attr('content'),
		'message': 'Hi',
		'data': canvas.toDataURL()
	}
	).done(function(response) {
        if ((response &&
                response.success == false &&
                response.error.code == "500" &&
                response.error.facebookErrorCode &&
                response.error.facebookErrorCode == "200") ||
            (response &&
                response.success == false &&
                response.error.code == "405" && //not allowed
                response.error.message == "need_authorization_publish_actions"
            )) {
            FB.login(function (response) {
            }, {scope: 'publish_actions'});
        }

        if (response &&
                response.error.code == "403" &&
                response.error.message == "Token mismatch") {
            //handle session expire
        }
    });
}

	function removeHightlight(){
		$("#sidebarmenu").find(":button").each(function(){
			if($(this).hasClass("highlight")){
				$(this).removeClass("highlight").addClass("normal");
			}			
		})
		
	}

	// function changeHighlight(){
	// 		if(canvas.isDrawingMode && $("#pentool").hasClass("normal")){
	// 		$("#pentool").removeClass("normal").addClass("highlight");
	// 		}
	// 		else if(inEraserMode && $("#eraser").hasClass("normal")){
	// 		$("#eraser").removeClass("normal").addClass("highlight");
	// 		}
	// 		else{
	// 		$("#pointer").removeClass("normal").addClass("highlight");
	// 		}
	// }

	function gifMake(){
		var animatedImage;
		gifshot.createGIF(
			{images: gifList, gifWidth: 640, gifHeight: 360, interval: 0.2}
			, function (obj) {
				if (!obj.error) {
					var image = obj.image, animatedImage = document.createElement('img');
					animatedImage.src = image;
        // document.body.appendchild(animatedImage);
        var giflink = document.createElement("a");
        giflink.download = "scribl";
        giflink.href = animatedImage.src;
        giflink.click();

    }
});

	}


/************************ HISTORY FUNCTIONS *************************/
var histList;
var histIndex;
var histMax;
var histWorking;
var gifList;
function initHistory() {
	// May not be necessary, this just makes
	// sure the canvas is initialised first
	histList = [JSON.stringify(canvas)];
	histIndex = 0;
	histMax = 49;
	histWorking = false;

	clearBackgroundColor();
	gifList = [canvas.toDataURL()];
	resetBackgroundColor();	
}


function updateUndoRedoBtn(){

	if (histIndex == 0){
		$("#undo").prop('disabled', true);
	} else{
		$("#undo").prop('disabled', false);
	}
	if(histIndex < histList.length - 1){
		$("#redo").prop('disabled', false);
	} else {
		$("#redo").prop('disabled', true);
	}

}

function updateHistory() {
	// Objects added on canvas load are 
	// considered "object:added"; this is a safety net
	// for when undo or redo is called
	if (histWorking) return;

	// Remove all items after histIndex
	histLast = histList.length - 1;
	if (histLast > histIndex) {
		var numToRemove = histLast - histIndex;
		histList.splice(histIndex+1,numToRemove);
	}

	// Add item to list, taking note
	// to only keep histMax+1 items
	if (histIndex==histMax) {
		histList.shift();
	} else {
		histIndex++;
	}
	histList.push(JSON.stringify(canvas));
	updateUndoRedoBtn();
	clearBackgroundColor();
	if(gifList.length == 100){
		gifList = resizeGifList();
	}
	gifList.push(canvas.toDataURL());
	resetBackgroundColor();
}

    function resizeGifList(){
    	var newGifList = [];
    	for(var i = 0; i < gifList.length; i ++){
    		if(i%2 == 0)
    			newGifList.push(gifList[i]);
    	}
    	return newGifList;
    }

	function undo() {
		// Only run if history is not busy
		if(!histWorking) {
			if (histIndex>0) {
				// Tell updateHistory to ignore actions
				histWorking = true;
				// Callback ensures no other history can run before completion
				canvas.loadFromJSON(histList[--histIndex], function() {
					canvas.renderAll();
					histWorking = false;
				});
				updateUndoRedoBtn();
			}
		}
	}



	function redo() {
		// Only run if history is not busy
		if(!histWorking) {
			histLast = histList.length - 1;
			if (histIndex<histLast) {
				// Tell updateHistory to ignore actions
				histWorking = true;
				// Callback ensures no other history can run before completion
				canvas.loadFromJSON(histList[++histIndex], function() {
					canvas.renderAll();
					histWorking = false;
				});
				updateUndoRedoBtn();
			}
		}
	}



/************************ WIP *************************/
function shareToFb() {
	// TODO: https://developers.facebook.com/docs/sharing/reference/share-dialog
	// TODO: https://developers.facebook.com/docs/sharing/best-practices
	// FB.ui({
	// 	method: 'share',
	// 	href: 'https://developers.facebook.com/docs/',
	// }, function(response){});

	// TODO: Implement friend checks when people visit that link, so that
	// people can't just randomly try finding pages? 
}





/************************ BOARD EDITOR CONTROLLERS *************************/
// function startIdleListener(){
// 	// Increase idle time every minute
// 	idleInterval = setInterval(timerIncrement, 60000);
// 	// Zero the idle timer on mouse or keyboard movement
// 	$(this).mousemove(function (e) {
// 		idleTime = 0;
// 	});
// 	$(this).keypress(function (e) {
// 		idleTime = 0;
// 	});
// }
// function timerIncrement() {
// 	if(++idleTime > 14) // 15 minutes
// 		timedOut(true);
// }
// function timedOut(afk) {

// }






/************************ LOAD/SAVE/CLEAR *************************/
function loadCanvas(){
	// canvas.loadFromJSON(
	// 	lastSave,							// Input data: load from lastSave
	// 	canvas.renderAll.bind(canvas),		// 'Callback'(optional): rerender canvas
	// 	function(o,object){ 				// 'Reviver' (optional): re-set default settings on all objects
	// 	setDefSettings(object);
	// }
	// );
	// isSaved = true;
}
function saveCanvas(wAlert){
	// $.ajax({
	// 	url: 'saveBoard',
	// 	data: {
	// 		boardUser: "{{ currBoard.boardUser }}",
	// 		boardID: {{ currBoard.boardID }},
	// 		data: JSON.stringify(canvas)
	// 	},
	// 	type: "POST",
	// 	async: false
	// });
	// lastSave = JSON.stringify(canvas);
	// if(wAlert)
	// 	alert("Successfully saved!");
	// isSaved = true;
}
	function clearCanvas(){
		var resp=confirm("Are you sure? This will clear everything!");
		if (resp){
			canvas.clear();
		}
	}






/************************ NEW OBJECTS FUNCTIONS *************************/
function setDefSettings(curr){	// *** consider adding these features to toJSON(options)
	curr.set({
		borderColor: 'rgba(67,46,79,0.75)',
		cornerColor: 'rgba(58,174,175,0.85)',
		cornerSize: 9,
		transparentCorners: false,
		originX: "center",
		originY: "center",
	});
	if(curr.height>curr.width)
		curr.set({minScaleLimit: 10.0/curr.height});
	else
		curr.set({minScaleLimit: 10.0/curr.width});
}


var isTextBold = false;
var isTextItalic = false;
var isTextUnderline = false;

function addText() {


	disableDrawAndEraser();

	var input = document.getElementById("textinput");
	var text = input.value;
	if(text == null || text.trim() == ""){
		text = "Click to edit";
	}
	var newText = new fabric.IText(text);
	newText.set({fill: fntcolor});
	newText.set("left", canvas.getWidth()/2);
	newText.set("top", canvas.getHeight()/2);
	setDefSettings(newText);

	if(isTextBold){
		var boldProperty = newText.getSelectionStyles()['fontWeight'] || '';
		var value = boldProperty.indexOf('bold') > -1 ? 
		boldProperty.replace('bold', '')
		: (boldProperty + ' bold');
		newText.set('fontWeight',value).setCoords();
	}
	if(isTextItalic){
		var italicProperty = newText.getSelectionStyles()['fontStyle'] || '';
		var value = italicProperty.indexOf('bold') > -1 ? 
		italicProperty.replace('italic', '')
		: (italicProperty + ' italic');
		newText.set('fontStyle',value).setCoords();
	}
	if(isTextUnderline){
		var underlineProperty = newText.getSelectionStyles()['textDecoration'] || '';
		var value = underlineProperty.indexOf('underline') > -1 ? 
		underlineProperty.replace('underline', '')
		: (underlineProperty + ' underline');
		newText.set('textDecoration',value).setCoords();
	}
	canvas.add(newText);
	canvas.setActiveObject(canvasLastObj());
	isSaved = false;

	clearText();
}

function clearText(){
		isTextBold = false;
    isTextItalic = false;
    isTextUnderline = false;

    if($("#textBold").hasClass("btn btn-success")){
    	$("#textBold").removeClass("btn btn-success").addClass("btn btn-default");
    }
   if($("#textItalic").hasClass("btn btn-success")){
    	$("#textItalic").removeClass("btn btn-success").addClass("btn btn-default");
    }
   if($("#textUnderline").hasClass("btn btn-success")){
    	$("#textUnderline").removeClass("btn btn-success").addClass("btn btn-default");
    }

    $("#textinput").value ="";
}

function toggleTextBold(){
	if(isTextBold){
		isTextBold = false;
		$("#textBold").removeClass("btn btn-success").addClass("btn btn-default");
	}else{
		isTextBold = true;
		$("#textBold").removeClass("btn btn-default").addClass("btn btn-success");
	}
	
}

function toggleTextItalic(){
	if(isTextItalic){
		isTextItalic = false;
		$("#textItalic").removeClass("btn btn-success").addClass("btn btn-default");
	}else{
		isTextItalic = true;
		$("#textItalic").removeClass("btn btn-default").addClass("btn btn-success");
	}
	
}

function toggleTextUnderline(){
	if(isTextUnderline){
		isTextUnderline = false;
		$("#textUnderline").removeClass("btn btn-success").addClass("btn btn-default");
	}else{
		isTextUnderline = true;
		$("#textUnderline").removeClass("btn btn-default").addClass("btn btn-success");
	}
	
}

// IMAGE FUNCTIONS
// function imageBox(e) {
// 	$("#imagebox").overlay().load();
// }
// function preloadImage() {
// 	var url = document.getElementById("imageurl").value;
// 	var image = document.getElementById("imageHolder");
// 	// image.crossOrigin = 'anonymous';
// 	image.src = url;
// 	$("#noPreview").hide();
// 	$("#imageHolder").show();
// 	$("img").error(function(){
// 		$(this).hide();
// 		$("#noPreview").show();
// 	});
// }
// function addImage(){
// 	var url = document.getElementById("imageHolder").src;
// 	fabric.Image.fromURL(url, function(oImg){
// 		if(oImg.getHeight()>oImg.getWidth()){
// 			oImg.scale((canvas.getHeight()/oImg.getHeight())/2);
// 		}else{
// 			oImg.scale((canvas.getWidth()/oImg.getWidth())/2);
// 		}
// 		setDefSettings(oImg);
// 		canvas.add(oImg);
// 		oImg.center();
// 		oImg.setCoords();
// 		canvas.setActiveObject(oImg);
// 	}, {crossOrigin: 'anonymous'});
// 	$("#imagebox").overlay().close();
// }





// TEXT FUNCTIONS
function textFont(){
	var curr = canvas.getActiveObject();
	if(!curr) return;
	var txt = document.getElementById("infoFont").value;
}
function setActiveStyle(styleName, value, object) {
	object = object || canvas.getActiveObject();
	if (!object) return;

	if (object.setSelectionStyles || value == "clear") {
		if (value == "clear")
			value = '';
		if (!object.isEditing)
			object.selectAll();
		var style = { };
		style[styleName] = value;
		if (styleName == "strokeWidth") {
			if (value == 0)
				style["stroke"] = null;
			else if (style["stroke"] == null)
				style["stroke"] = "#FFFFFF";
		}
		object.setSelectionStyles(style);
		object.setCoords();
	} else {
		object[styleName] = value;
		if (styleName == "strokeWidth") {
			if (value == 0)
				object["stroke"] = null;
			else if (object["stroke"] == null)
				object["stroke"] = "#FFFFFF";
		}
	}

	object.setCoords();
	canvas.renderAll();
}
function setActiveProp(name, value) {
	var object = canvas.getActiveObject();
	if (!object) return;

	if (name == "backgroundColor") {
		setActiveProp("darkerShade", tinycolor.darken(value, 20).toHexString());
	}
	object.set(name, value).setCoords();
	canvas.renderAll();
}
function getActiveStyle(styleName, object) {
	object = object || canvas.getActiveObject();
	if (!object) return '';
	if (!object.isEditing)
		object.selectAll();
	return (object.getSelectionStyles && object.isEditing)
	? (object.getSelectionStyles()[styleName] || '')
	: (object.getSelectionStyles()[styleName] || '');
}
function isBold() {
	return getActiveStyle('fontWeight').indexOf('bold') > -1;
}
function toggleBold() {
	var value = isBold()
	? getActiveStyle('fontWeight').replace('bold', '')
	: (getActiveStyle('fontWeight') + ' bold');

	setActiveStyle('fontWeight', value);
}
function isItalic() {
	return getActiveStyle('fontStyle').indexOf('italic') > -1;
}
function toggleItalic() {
	var value = isItalic()
	? getActiveStyle('fontStyle').replace('italic', '')
	: (getActiveStyle('fontStyle') + ' italic');

	setActiveStyle('fontStyle', value);
}
function isUnderline() {
	return getActiveStyle('textDecoration').indexOf('underline') > -1;
}
function toggleUnderline() {
	var value = isUnderline()
	? getActiveStyle('textDecoration').replace('underline', '')
	: (getActiveStyle('textDecoration') + ' underline');

	setActiveStyle('textDecoration', value);
}
function isLinethrough() {
	return getActiveStyle('textDecoration').indexOf('line-through') > -1;
}
function toggleLinethrough() {
	var value = isLinethrough()
	? getActiveStyle('textDecoration').replace('line-through', '')
	: (getActiveStyle('textDecoration') + ' line-through');

	setActiveStyle('textDecoration', value);
}
function isOverline() {
	return getActiveStyle('textDecoration').indexOf('overline') > -1;
}
function toggleOverline() {
	var value = isOverline()
	? getActiveStyle('textDecoration').replace('overline', '')
	: (getActiveStyle('textDecoration') + ' overline');

	setActiveStyle('textDecoration', value);
}
function clearHighlight() {
	var obj = canvas.getActiveObject();
	setActiveStyle("textBackgroundColor", 'clear');
}





/************************ TAB-RELATED *************************/
// function switchTab(tab){
// 	if (tab == "create") {
// 		$("#myTab > .active").removeClass("active");
// 		$("#myTabContent > .active").removeClass("active");
// 		$("#newObjTab").addClass("active");
// 		$("#createobjects").addClass("in active");
// 	} else {
// 		$("#myTab > .active").removeClass("active");
// 		$("#myTabContent > .active").removeClass("active");
// 		$("#currObjTab").addClass("active");
// 		$("#modifycurrent").addClass("in active");
// 	}
// }
// function updateInfoWin(curr){
// 	// Change fields for infowin
// 	var activeObj = canvas.getActiveObject();
// 	if(activeObj){
// 		if(activeObj.type=='i-text'){
// 			activeObj.lockUniScaling = true;
// 			document.getElementById("fontSize").value = activeObj.fontSize;
// 			document.getElementById("strokeWidth").value = activeObj.strokeWidth;
// 			document.getElementById("itext-controls").style.display = "block";
// 			if(activeObj.getStroke()) $('#stroke').colpickSetColor(activeObj.getStroke(), true);
// 			else $('#stroke').colpickSetColor('#ffffff', true)
// 		}else{
// 			document.getElementById("itext-controls").style.display = "none";
// 			$('#fillColor').colpickSetColor(activeObj.getFill(),true);
// 		}
// 	}
// }
function toTwoDP(num){
	return Math.round(num*100)/100;
}
// function resetInfoWin(){
// 	// Deselect effects
// 	atLimit=null;
// 	orig=null;
// 	document.getElementById("itext-controls").style.display = "none";
// }






/************************ BOARD BOUNDARIES *************************/
// Save last acceptable state (on scale/rotate)
function setLimit(e){
	var curr=e.target;
	curr.setCoords();
	// if not out of bounds, save state
	// (the interval is not very fast, so this method is not so precise if you move fast)
	if(!isOOB(curr)){
		atLimit = {
			top: curr.getTop(),
			left: curr.getLeft(),
			scaleX: curr.getScaleX(),
			scaleY: curr.getScaleY(),
			angle: curr.getAngle(),
		};
	}
}
// Check if out of bounds (on modified)
function checkOOB(e){
	var curr = e.target;
	if(!curr) return;
	// if scale/angle has been modified, checkScaleAngle for OOB
	if(((orig.getScaleX() != curr.getScaleX()) || (orig.getScaleY() != curr.getScaleY())) || (orig.getAngle() != curr.getAngle()))
		checkScaleAngle(curr);
	// otherwise check position
	else
		checkPos(curr);
	// save new state
	orig = fabric.util.object.clone(curr);
	atLimit = {
		top: curr.getTop(),
		left: curr.getLeft(),
		scaleX: curr.getScaleX(),
		scaleY: curr.getScaleY(),
		angle: curr.getAngle(),
	};
	// console.log("After checkOOB: " + JSON.stringify(atLimit));
	//updateInfoWin(orig);
	setDefSettings(curr);
	curr.setCoords();
	isSaved = false;
}
// Returns object holding whether curr is OOB in the four canvas edges
function getOOBObj(curr){
	var bounds = curr.getBoundingRect(),
	maxWidth = canvas.getWidth(),
	maxHeight = canvas.getHeight();
	// Additional 1px accounts for bounding rect.
	// Without this checkPos will always trigger, even after correction
	var outOf = {
		top: bounds.top < -1,
		left: bounds.left < -1,
		bottom: bounds.top+bounds.height > maxHeight+1,
		right: bounds.left+bounds.width > maxWidth+1,
	};
	return outOf;
}
// Returns boolean true if object is out of bounds in any edge(s)
function isOOB(curr){
	var outOf = getOOBObj(curr);
	return (outOf.right || outOf.left) || (outOf.bottom || outOf.top);
}
// If object is out of bounds reset to last atLimit state
function checkScaleAngle(curr){
	// console.log("InsideCheckAngle: " + JSON.stringify(atLimit));
	if(isOOB(curr)){
		curr.set(atLimit);
		curr.setCoords();
		return true;
		// console.log("New curr: " + JSON.stringify(curr));
	}
	return false;
}
// If object is out of bounds push it back in (requires object originX/Y to be "center")
function checkPos(curr){
	var updateCoords = false,
	bounds = curr.getBoundingRect(),
	maxWidth = canvas.getWidth(),
	maxHeight = canvas.getHeight();
	var outOf = getOOBObj(curr);

	if(outOf.right){
		updateCoords = true;
		curr.set({left: maxWidth-bounds.width/2});
	}
	if(outOf.bottom){
		updateCoords = true;
		curr.set({top: maxHeight-bounds.height/2});
	}
	if(outOf.left){
		updateCoords = true;
		curr.set({left: bounds.width/2});
	}
	if(outOf.top){
		updateCoords = true;
		curr.set({top: bounds.height/2});
	}

	// Implement new coordinates
	if(updateCoords){
		curr.setCoords();
		return true;
	}
	return false;
}
