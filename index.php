<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TwoHills TV</title>
    <link rel="stylesheet" href="css/hills.style.css">
  </head>
  <body>

    <div class="overlay"></div>

    <div class="background">
      <video playsinline autoplay muted loop>
          <source src="http://thenewcode.com/assets/videos/polina.mp4" type="video/mp4">
      </video>
    </div>

    <?php require_once 'classes/Request.php'; require_once 'classes/Visits.php'; new Request(); $visits = new Visits(); $visits->visitors('TwoHillsTV');?>

    <footer>
      <div class="trademark">
        TwoHills TV <small style="font-size:10px;">Sic Parvis Magna</small>
      </div>
      <img class="logo" src="img/logo.png" alt="">
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="js/hills.player.js"></script>
  </body>
</html>

<?php
/*ffmpeg -i input.mkv -strict experimental -c:v copy -c:a aac -b:a 192k output.mp4
MP4 is indeed the best format for Apple devices and software. DTS is also indeed not supported, many MP4 video files contain two audio tracks, one DTS and one AAC.

There are multiple encoders available, all of them documented on the ffmpeg wiki. Which codec is available depends on how ffmpeg was compiled. libfdk_aac will give you the best results, but due to this codec being non-free it's not always available.

Things you can try (I put them in the order of my perceived quality, best first)

ffmpeg -i input.mkv -c:v copy -c:a libfdk_aac -b:a 128k output.mp4
ffmpeg -i input.mkv -strict experimental -c:v copy -c:a aac -b:a 192k output.mp4
ffmpeg -i input.mkv -c:v copy -c:a libfaac -b:a 192k output.mp4
If you want to retain the DTS track too, use the -map flag.

Not directly of use for OP, but the OS X program subler makes this process very easy.

EDIT: Comments tl;dr? OP solved problem with the following command

ffmpeg -i input.mkv -strict experimental -map 0:0 -map 0:1 -map 0:2 -map 0:3 -c:v copy -c:a aac -b:a 384 -c:s copy output.mp4
TIP: if -c:s copy for subtitles doesn't work, try -c:s mov_text.
Saved me on multiple occasions.*/
