<!doctype html>
<html>
  <head>
    <title>Twittersaic</title>
    <meta charset="utf-8">
    <style>
      canvas {
        border:1px solid #333;
        display:block;
        margin:50px auto;
      }
      p {
        text-align:center;
      }
    </style>
  </head>
  <body>
    <div></div>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
    <script>
      $(document).ready(function(){
        var avatars = [];
        function getAvatars() {
          $.ajax({
            url: 'twitter.php',
            dataType: 'json',
            success: function(d){
              for (var i = 0; i < d.length; ++i) {
                avatars[i] = d[i];
              }
              buildMosaic(avatars);
            }
          });
        };
        getAvatars();
        function drawAvatar(ctx, dpi, src, x, y, r, g, b, a) {
          var img = new Image();
          img.onload = function(){
            ctx.drawImage(img, x*dpi, y*dpi, dpi, dpi);
            ctx.fillStyle = 'rgba(' + r + ',' + g + ',' + b + ',' + a + ')';
            ctx.fillRect(x*dpi, y*dpi, dpi, dpi);
          };
          img.onerror = function (evt){
            ctx.fillStyle = 'rgba(' + r + ',' + g + ',' + b + ',' + a + ')';
            ctx.fillRect(x*dpi, y*dpi, dpi, dpi);
          }
          img.src = src;
        }
        function buildMosaic(avatars) {
          $.ajax({
            url: 'json.php',
            dataType: 'json',
            success: function(d){
              var dpi = 10;
              <?php if (isset($_GET['dpi'])) { echo 'dpi = ' . $_GET['dpi'] . ';'; } ?>
              var w = d['w'] * dpi;
              var h = d['h'] * dpi;
              var pixels = d['pixels'];

              var canvas = document.createElement('canvas'),
              ctx = canvas.getContext('2d');
              canvas.width = w;
              canvas.height = h;

              var avatar_cnt = avatars.length;
              var avatar_idx = 0;
              var avatar;

              for (var i = 0; i < pixels.length; ++i) {
                var pixel = pixels[i];
                var x = pixel['x'];
                var y = pixel['y'];
                var r = pixel['r'];
                var g = pixel['g'];
                var b = pixel['b'];
                var a = pixel['a'] * .8;

                if (avatar_idx < avatar_cnt) {
                  avatar = avatars[avatar_idx++];
                } else {
                  avatar = avatars[0];
                  avatar_idx = 1;
                }
                drawAvatar(ctx, dpi, avatar, x, y, r, g, b, a);
                
              }
              $('div').append(canvas);
              var p = $('<p>');
              $('div').append(p.html('Debugging: Built w/ ' + pixels.length + ' Avatars'));
            }
          });
        }
      });
    </script>
  </body>
</html>