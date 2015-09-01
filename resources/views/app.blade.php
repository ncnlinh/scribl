<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8">
  <title>Scribl!</title>
  <link rel="stylesheet" href="/css/all.css">
</head>
<body>
  <!--************************* POP-UP *************************-->
  <div id="myModaltext" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Input the text</h4>
        </div>
        <div class="modal-body">
          <div>
          <button id = "textBold" class = "btn btn-default">bold</button>
          <button id = "textItalic" class = "btn btn-default">italic</button>
          <button id = "textUnderline" class = "btn btn-default">underline</button>
          </div>
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
  <!--************************* DRAWING AREA *************************-->
  <table><tr>
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
          <li><div class="dropdown">
            <button id = "pentool"  class="btn btn-default dropdown-toggle btn btn-primary active highlight" data-toggle="dropdown" title="Draw"></button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1" style="left: 0px; top: 40px">
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
          <li><button id = "eraser" class="btn btn-primary normal" title="Erase"></button>
          </li>
          <li><button id = "clearcanvas" class="btn btn-primary normal" title="Clear"></button>
          </li> 
          <li><button id = "undo" class="btn btn-primary normal" title="Undo"></button>
          </li>
          <li><button id = "redo" class="btn btn-primary normal" title="Redo"></button>
          </li>
          <li><button id = "uploadimages" class="btn btn-primary normal" title="Insert Image"></button>
          </li>
          <li><button id = "text" class="btn btn-primary normal" data-toggle="modal" data-target="#myModaltext" title="Insert Text">text</button>
          </li>
          <li><button id = "download" class="btn btn-primary normal" title="Download Scribl">DL</button>
          </li> 
          <li>
            <label for="drawing-mode-selector">Pen Mode:</label><br>
            <select id="drawing-mode-selector">
              <option>Pencil</option>
              <option>Circle</option>
              <option>Spray</option>
            </select><br>
            <label for="drawing-color">Line color:</label><br>
            <input type="color" value="#005E7A" id="drawing-color">
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
      <canvas resize="true" id="canvas" width="400px" height="600px">
        This text is displayed if your browser does not support HTML5 Canvas.
      </canvas>
    </td>
  </tr>
  </table>
</body>
<script src="js/app.js" type="text/javascript"></script>
</html>