<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8">
  <title>Scribbl!</title>
  <link rel="stylesheet" href="/css/all.css">
  
  <style>
    #scribbl {
      font-family: "Trebuchet MS", Helvetica, sans-serif;
      font-size: 3em;
      color:black;
    }
    #wb {
      color:white;
    }
    #wb-logout, #nav-menu {
      background-color: rgba(0,0,0,0.2);
    }
    #pinboard {
      position: relative;
      border-style: solid;
      border-width: 5px;
      border-color: #008080;
      background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAKUlEQVQYV2NkIA4YMxKnjoH6ChmItZpohdR3I9Emgt1ojBSWZ6FsDDEAO/0DPTDnTssAAAAASUVORK5CYII=);
    }

    table.center {
      margin-left:auto; 
      margin-right:auto;
    }
    table td { 
      display: table-cell;
      vertical-align: top; 
    }
    #imagebox, #bgimgbox {
      display:none;
      width:800px;
      border:8px solid #666;
      /* for modern browsers use semi-transparent color on the border. nice! */
      border:8px solid rgba(82, 82, 82, 0.698);
      /* hot CSS3 features for mozilla and webkit-based browsers (rounded borders) */
      -moz-border-radius:5px;
      -webkit-border-radius:5px;
    }

    #imagebox div, #bgimgbox div {
      border:1px solid #3B5998;
      background-color:#fff;
    }
    #noPreview, #bgnoPreview {
      display:none;
    }

    #bgimageurl.placeholder {
      text-align: center;
    }

    #selectableBG .ui-selecting { background: #FECA40; }
    #selectableBG .ui-selected { background: #F39814; color: white; }
    #selectableBG { padding-left: 80px; width: 800px; list-style-type: none;}
    #selectableBG li { padding: 1px; width: 200px; height: 140px; line-height: 135px; text-align: center; display: inline-block; vertical-align: middle;}

    #infotable td {
      padding:2px;
      border-spacing:5px;
    }
    #newobjtable td {
      padding:2px;
    }

    .color-box {
      display: inline-block;
      vertical-align: middle;
      width:22px;
      height:22px;
      margin:0px;
      border: 1px solid gray;
    }

    input[type=range] {
      width: 50px;
      display: inline-table;
      vertical-align: middle;
    }

    #btnSwitchBoard {
      float: right;
    }

    .input-group {
      width: 400px;
    }

    #loadingText {
      z-index: -1;
      font-size: 30px;
      margin: auto;
      top: 40%;
      left: 0;
      right: 0;
      position:absolute;
    }
    #sidebarmenu{
      list-style-type: none;
      padding-left:20px;
      margin-left: 0px;
    }

    #sidebarmenu li button{
      height:  40px;
      width:  40px;     
      background-repeat: no-repeat;
      background-size: 100%;
      background-size: 40px;
      margin-bottom: 20px;
      border-style:none;
    }

    #pentool{
      background:url("../images/pen tool.png");
      background-repeat: no-repeat;
      background-size: 100%;
      background-size: 40px;
    }
    #pentool .selected{
      background:url("../images/pen tool selected.png");
      background-repeat: no-repeat;
      background-size: 100%;
      background-size: 40px;
    }
    #eraser{
      background:url("../images/eraser.png");
    }
    .selected{
      background:url("../images/eraser selected.png");
    }
    #clearcanvas{
      background:url("../images/clear canvas.png");
    }
    #undo{
      background:url("../images/undo.png");
    }
    #redo{
      background:url("../images/redo.png");
    }
    #uploadimages{
      background:url("../images/upload image.png");
    }
    .thumb {
      height: 75px;
      border: 1px solid #000;
      margin: 10px 5px 0 0;
    }
    
  </style>
</head>
<body>
  <!--************************* HEADER *************************-->
  <div id="header">
    <div class="jumbotron">
      <h2 id="scribbl" class="title" style="text-align: center">Welcome to Scribbl!</h2>
    </div>
  </div>
  
  <!--************************* LEFT: Sidebar *************************-->
  <div id="myModaltext" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Input the text</h4>
        </div>
        <div class="modal-body">
          <input id="textinput" name="files[]" multiple />
          <output id="list"></output>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="inputtext">OK</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  <div id = "sidebar" align = "left" class="col-md-4">
    <ul id ="sidebarmenu" class="btn-group" data-toggle="buttons">
      <li><div class="dropdown">
        <button id = "pentool"  class="btn btn-default dropdown-toggle btn btn-primary active" data-toggle="dropdown"></button>
        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1" style="left: 40px; top: 0">
          <li role="presentation">
            <label for="drawing-line-width">Line width:</label>
            <span class="info">22</span><input type="range" value="30" min="0" max="150" id="drawing-line-width"><br>
          </li>
          <li role="presentation" class="divider"></li>
          <li role="presentation" class="dropdown-header">Dropdown header 2</li>

          <li role="presentation">  
            
          </li>
        </ul>
        
      </div>
    </li>
    <li><button id = "eraser" class="btn btn-primary"></button>
    </li>
    <li><button id = "clearcanvas" class="btn btn-primary"></button>
    </li> 
    <li><button id = "undo" class="btn btn-primary"></button>
    </li>
    <li><button id = "redo" class="btn btn-primary"></button>
    </li>
    <li><button id = "uploadimages" class="btn btn-primary"></button>
    </li>
    <li><button id = "text" class="btn btn-primary" data-toggle="modal" data-target="#myModaltext">text</button>
    </li>     
  </ul>

</div>


<!--************************* BODY *************************-->
<table class="col-md-8">
  <tr>
    <!--************************* LEFT: Pinboard *************************-->
    <td>
      <div id="boardInfo" align="center">
        [Name Here?]
      </div>
      <div id="pinboard" style="width: 410px; height:610px; vertical-align: middle; text-align: center;">
        <!--div dimensions must be canvas dim + border width-->
        <!--canvas id must be consistent with js script-->
        <canvas id="canvas" width="400" height="600">
          This text is displayed if your browser does not support HTML5 Canvas.
        </canvas>
      </div>
    </td>

    <!--************************* RIGHT: Info++ *************************-->
    <td id="rightside" width="1px" style="padding:10px">
      <!--************************* TABS *************************-->
      <ul id="myTab" class="nav nav-pills">
        <li id="newObjTab" class="active"><a href="#createobjects" data-toggle="tab">New Object</a></li>
        <li id="currObjTab"><a href="#modifycurrent" data-toggle="tab">Current Object</a></li>
      </ul>
      <hr>
      <div id="myTabContent" class="tab-content">
        <!--************************* New Objects *************************-->
        <div class="tab-pane fade in active" id="createobjects">
          <table id="newobjtable">
            <tr>
              <button id="drawing-mode" class="btn btn-success">Stop Drawing</button>
              <button id="eraser-mode" class="btn btn-warning">Eraser</button>
            </tr>
            <tr><p></tr>
            <tr><strong>Drawing Options</strong></tr>
            <tr><div id="drawing-mode-options">
              <label for="drawing-mode-selector">Mode:</label>
              <select id="drawing-mode-selector">
                <option>Pencil</option>
                <option>Circle</option>
                <option>Spray</option>
                <option>Pattern</option>

                <option>hline</option>
                <option>vline</option>
                <option>square</option>
                <option>diamond</option>
                <option>texture</option>
              </select><br>

<!--                  <label for="drawing-line-width">Line width:</label>
                  <span class="info">22</span><input type="range" value="30" min="0" max="150" id="drawing-line-width"><br>
                -->
                <label for="drawing-color">Line color:</label>
                <input type="color" value="#005E7A" id="drawing-color"><br>

                <label for="drawing-shadow-color">Shadow color:</label>
                <input type="color" value="#005E7A" id="drawing-shadow-color"><br>

                <label for="drawing-shadow-width">Shadow width:</label>
                <span class="info">6</span><input type="range" value="0" min="0" max="50" id="drawing-shadow-width"><br>

                <label for="drawing-shadow-offset">Shadow offset:</label>
                <span class="info">6</span><input type="range" value="0" min="0" max="50" id="drawing-shadow-offset"><br>
              </div>
            </tr>
            <tr><p></tr>
            <tr><strong>Properties:</strong></tr>
            <tr><p></tr>
            <tr>
              <td align="right">Colour:</td>
              <td><span class="color-box" id="newblkcol" /></td>
            </tr>
            <tr>
              <td align="right">Text(optional):</td>
              <td><textarea id="newtext" maxlength="255" rows="3" placeholder="<insert text>"></textarea></td>
            </tr>
          </table>
          <p></p>
          <p id="newobjbuttons">
            <strong>Create a new</strong>
            <p></p>
            <span class="btn-group">
              <button type="button" class="btn btn-default" id="addtext" onclick="addText()">Textbox</button>
              <button type="button" class="btn btn-default" id="addimage">Image</button>
            </span>
          </p>
          <div id="imagebox" style="position: fixed;">
            <div align="center">
              <p class="close">x</p>
              <h2>Insert Picture</h2>
              <p class="input-group"> 
                <input type="text" class="form-control" id="imageurl" onblur="preloadImage()" size="35" placeholder="<Image Url>">
                <span class="input-group-btn"><button class="btn btn-default" onclick="addImage()">Add it!</button></span>
              </p>
              <hr>
              <center><p id="noPreview">No preview available</p>
                <img id="imageHolder" src="" width="780px"></img></center>
                <p></p>
              </div>
            </div>
          </div>
          <!--************************* Current Object *************************-->
          <div class="tab-pane fade" id="modifycurrent">
            <!--ITEXT CONTROLS-->
            <div id="itext-controls">
              <strong> Text Controls: </strong>
              <p></p>
              <div>
                <span class="btn-group">
                  <!--Not sure what the ng-class should do and whether it works-->
                  <button class="btn-sm btn btn-default" id="btntextColor"><del><span class="color-box" id="textColor" /></span></del></button>
                  <button type="button" class="btn btn-default" id="text-cmd-bold" onclick="toggleBold()" ng-class="{'btn-inverse': isBold()}"><b>B</b></button>
                  <button type="button" class="btn btn-default" id="text-cmd-italic" onclick="toggleItalic()" ng-class="{'btn-inverse': isItalic()}"><i>I</i></button>
                  <button type="button" class="btn btn-default" id="text-cmd-underline" onclick="toggleUnderline()" ng-class="{'btn-inverse': isUnderline()}"><u>U</u></button>
                  <button type="button" class="btn btn-default" id="text-cmd-linethrough" onclick="toggleLinethrough()" ng-class="{'btn-inverse': isLinethrough()}"><del>S</del></button>
                </span>
                <span>
                  <select id="fontFamily" name="fontFamily">
                    <option value="arial" selected="">Arial</option>
                    <option value="comic sans ms">Comic Sans MS</option>
                    <option value="consolas">Consolas</option>
                    <option value="courier">Courier</option>
                    <option value="georgia">Georgia</option>
                    <option value="helvetica">Helvetica</option>
                    <option value="impact">Impact</option>
                    <option value="lucida console">Lucida Console</option>
                    <option value="myriad pro">Myriad Pro</option>
                    <option value="segoe ui">Segoe UI</option>
                    <option value="tahoma">Tahoma</option>
                    <option value="times new roman">Times New Roman</option>
                    <option value="verdana">Verdana</option>
                  </select>
                </span>
              </div>
              <p></p>
              <div>
                Font Size:
                <input type="range" value="20" min="20" max="120" step="1" id="fontSize"  onchange="setActiveStyle(id, this.value)">
                Align:
                <select id="textAlign" name="textAlign">
                  <option value="left">Left</option>
                  <option value="center">Center</option>
                  <option value="right">Right</option>
                  <option value="justify">Justify</option>
                </select>
              </div>
              <p></p>
              <div>
                Stroke (Width/Color):
                <input type="range" value="0" min="0" max="5" id="strokeWidth" onchange="setActiveStyle(id, parseInt(this.value,10))">
                <span class="color-box" id="stroke" />
              </div>
            </div>
            <!--END ITEXT CONTROLS-->
            <p></p>
          </div>
        </div>
        <div id="boardButtons">
          <p>
            <button id="undo-btn" class="btn btn-primary">Undo</button>
            <button id="redo-btn" class="btn btn-info">Redo</button>
            <button id="clear-canvas" class="btn btn-danger">Clear</button>
          </p>
          <p>
            <button id="download-btn" class="btn btn-success">Download</button>
          </p>
        </div>
      </td>
      <!--END OF RIGHT COLUMN-->
    </tr>
  </table>
  <!--END BODY-->
  <script src="/js/app.js" type="text/javascript"></script>
</body>
</html>