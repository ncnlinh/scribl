<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8">

  <meta name="csrf-token" content="{{csrf_token()}}">
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
          <input id="textinput" name="text" autofocus/>
          <output id="list"></output>
        </div>
        <div class="modal-footer">
          <button id="inputtext" type="button" class="btn btn-default" data-dismiss="modal">OK</button>
          <button id="closetext" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
              <button id = "pointer" class="btn btn-default normal" data-toggle="popover" des="Select">
              </button>
            </li>
            <li>
              <div class="dropdown">
                <button id = "pentool"  class="btn btn-default dropdown-toggle btn btn-primary active highlight" data-toggle="dropdown"></button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1" style="left: 44px; top: -4px">
                  <li role="presentation" class="dropdown-header">
                    <label for="drawing-line-width">Width: </label>
                    <input type="range" value="30" min="1" max="150" id="drawing-line-width">
                    <span id="drawing-line-width-px">30px</span>
                  </li>
                  <li role="presentation" class="divider"></li>
                  <li role="presentation" class="dropdown-header">
                    <label for="pen-modes">Stroke: </label><br>
                    <span id="pen-modes">
                      <button id="pen-pencil" class="btn btn-primary highlight brush-sel" title="Pencil"></button>
                      <button id="pen-circle" class="btn btn-primary normal brush-sel" title="Circles"></button> 
                      <button id="pen-spray" class="btn btn-primary normal brush-sel" title="Spray Can"></button>
                    </span>
                  </li>
                  <li role="presentation" class="divider"></li>
                  <li role="presentation" class="dropdown-header">
                    <label for="drawing-col">Color:</label>
                    <span class="color-box" id="drawing-col" />
                  </li>
                </ul>
              </div>
            </li>
            <li><button id = "eraser" class="btn btn-primary normal"></button>
            </li>
            <li><button id = "clearcanvas" class="btn btn-primary normal"></button>
            </li> 
            <li><button id = "undo" class="btn btn-primary normal" disabled></button>
            </li>
            <li><button id = "redo" class="btn btn-primary normal" disabled></button>
            </li>
            <li><button id = "uploadimages" class="btn btn-primary normal"></button>
            </li>
            <li><button id = "text" class="btn btn-primary normal" data-toggle="modal" data-target="#myModaltext"></button>
            </li>
            <li><button id = "download" class="btn btn-primary normal"></button>
            </li> 
            <li><button id = "gif" class="btn btn-primary normal"></button>
            </li> 
          </ul>
        <br><br>
      </div>
      <div align="center">
        @if (Auth::check())
          <a href={{url('logout')}}>
            <button type="button" class="fb-btn">
                <img src="../images/FB-f-Logo__blue_29.png" />Logout
            </button>
          </a>
        @endif
      </div>
    </td>
    <!--************************* CANVAS *************************-->
    <td id="drawboard" style="vertical-align: top; text-align: center;">
      <!--div dimensions must be canvas dim + border width-->
      <!--canvas id must be consistent with js script-->
      <canvas resize="true" id="canvas" width="800px" height="600px">
        This text is displayed if your browser does not support HTML5 Canvas.
      </canvas>
      <button type="button" id="postOnFacebookPromptBtn" class="btn btn-primary">Share this drawing!</button>
    </td>
  </tr>
  </table>

  <!--************************* POST MODAL **************************-->
  @if (isset($post))
    <div id="showPostModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            Post
            @if (Auth::check())
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            @endif
          </div>
          <div class="modal-body">
            @if (isset($image))
              <img class="postThumb" src="data:image/png;base64,{{$image}}">
            @endif
              <a href="{{env('APP_URL') . '/scribbl/'. $post->tag }}">Link to this</a>
          </div>
          @if (Auth::check())
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          @else
            <div class="modal-footer">
              <a href={{url('login')}}>
                <button type="button" class="fb-btn">
                  <img src="../images/FB-f-Logo__blue_29.png" />Login to continue
                </button>
              </a>
            </div>
          @endif
        </div>

      </div>
    </div>


  @endif

  <!--************************* BEFORE POST PROMPT MODAL **************************-->
  <div id="beforePostModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <span id="postModalHeader">
            Post on Facebook
          </span>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">

          <div id="postModalHeader">
            <div id="postModalAlertPlaceholder"></div>
            <div class="center">
              <img class="postThumb" id="postModalImage">
            </div>
            {!! Form::open(array(
              'action' => 'PostController@post',
              'method' => 'post',
              'id' => 'formPostOnFacebook'
            )) !!}
              <div class="form-group">
                {!! Form::label('message', 'Message:') !!}
                {!! Form::textarea('message', null, ['class'=>'form-control']) !!}
              </div>
              {!! Form::submit('Share on Facebook', ['class'=>'btn btn-primary form-control']) !!}
            {!! Form::close() !!}
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
  <script src="/js/app.js" type="text/javascript"></script>
  @if (isset($post))
    <script type="text/javascript">
        $(window).load(function(){
          $('#showPostModal').modal({backdrop: 'static', keyboard: false});
        });
    </script>
  @endif
</body>
</html>