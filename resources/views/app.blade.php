<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8">

  <meta name="csrf-token" content="{{csrf_token()}}">
  <title>Scribl!</title>
  <link rel="stylesheet" href="/css/all.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<body>

  <!--************************* POP-UP *************************-->
  <div id="myModaltext" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Input text</h4>
        </div>
        <div class="modal-body">
          <button id = "textBold" class = "btn btn-default">
          <i class="fa fa-bold"></i></button>
          <button id = "textItalic" class = "btn btn-default">
          <i class="fa fa-italic"></i></button>
          <button id = "textUnderline" class = "btn btn-default">
          <i class="fa fa-underline"></i></button>
          </div>
           <div class="modal-body">
          <input id="textinput" name="files[]" multiple autofocus/>
          <output id="list"></output>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="inputtext">OK</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
  <!--************************* DRAWING AREA *************************-->
  <table class="content"><tr>
    <!--************************* BUTTONS *************************-->
    <td id = "sidebar" align="right">
      <div id="sidebarcontent">
        <h2 id="title">Scribl</h2>
        <hr id="titleline">
          <ul id ="sidebarmenu" class="btn-group" data-toggle="buttons">
            <li>
              <button id = "pointer" class="btn btn-default normal" title="Select">
              </button>
            </li>
            <li>
              <div class="dropdown">
              <button id = "pentool"  class="btn btn-default dropdown-toggle btn btn-primary active highlight" data-toggle="dropdown" title="Draw"></button>
              <ul class="dropdown-menu" role="menu" aria-labelledby="menu1" style="left: 0px; top: 40px">
                <li role="presentation" class="dropdown-header">
                  <label for="drawing-line-width">Width: </label>
                  <input type="range" value="30" min="1" max="150" id="drawing-line-width">
                  <span id="drawing-line-width-px">22px</span>
                </li>
                <li role="presentation" class="divider"></li>
                <li role="presentation" class="dropdown-header">
                  <label for="drawing-col">Color:</label>
                  <span class="color-box" id="drawing-col" />
                </li>
              </ul>
              </div>
            </li>
            <li><button id = "eraser" class="btn btn-primary normal" title="Erase"></button>
            </li>
            <li><button id = "clearcanvas" class="btn btn-primary normal" title="Clear"></button>
            </li> 
            <li><button id = "undo" class="btn btn-primary normal" title="Undo" disabled></button>
            </li>
            <li><button id = "redo" class="btn btn-primary normal" title="Redo" disabled></button>
            </li>
            <li><button id = "uploadimages" class="btn btn-primary normal" title="Insert Image"></button>
            </li>
            <li><button id = "text" class="btn btn-primary normal" data-toggle="modal" data-target="#myModaltext" title="Insert Text"></button>
            </li>
            <li><button id = "download" class="btn btn-primary normal" title="Download Scribl"></button>
            </li> 
            <li>
              <label for="drawing-mode-selector" style="color:white">Pen Mode:</label><br>
              <select id="drawing-mode-selector">
                <option>Pencil</option>
                <option>Circle</option>
                <option>Spray</option>
              </select><br>
            </li>
          </ul>
        <br><br>
        <a href={{url('logout')}}>
          <button type="button" class="fb-btn">
              <img src="../images/FB-f-Logo__blue_29.png" />Logout
          </button>
        </a>
      </div>
    </td>
    <!--************************* CANVAS *************************-->
    <td id="drawboard" style="vertical-align: top; text-align: center;">
      <!--div dimensions must be canvas dim + border width-->
      <!--canvas id must be consistent with js script-->
      <canvas resize="true" id="canvas" width="800px" height="600px">
        This text is displayed if your browser does not support HTML5 Canvas.
      </canvas>
      <button type="button" id="postOnFacebook" class="btn btn-primary">Send</button>
    </td>
  </tr>
  <script src="js/app.js" type="text/javascript"></script></table>
</body>
</html>