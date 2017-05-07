var index = 0;
var captionLength = 0;
var captionOptions = ["Estamos a ponto de mudar o futuro!", "E já estamos liberando novos acessos", "Caso esteja interessado", "deixe seu email", "Entraremos em contato em breve =)", "Twohills TV"]

// this will make the cursor blink at 400ms per cycle
function cursorAnimation() {
  $('#cursor').animate({
      opacity: 0
  }, 400).animate({
      opacity: 1
  }, 400);
}

// this types the caption
function type() {
    $caption.html(caption.substr(0, captionLength++));
    if(captionLength < caption.length+1) {
        setTimeout('type()', 70);
    }
}

// this erases the caption
function erase() {
    $caption.html(caption.substr(0, captionLength--));
    if(captionLength >= 0) {
        setTimeout('erase()', 50);
    }
}

// this instigates the cycle of typing the captions
function showCaptions() {
  caption = captionOptions[index];
  type();
  if (index < (captionOptions.length - 1)) {
    index++
    setTimeout('erase()', 4000);
    setTimeout('showCaptions()', 6000)
  }
}


$(document).ready(function(){
  // use setInterval so that it will repeat itself
  setInterval('cursorAnimation()', 400);
  $caption = $('#caption');

  // use setTimeout so that it only gets called once
  setTimeout('showCaptions()', 1000);
})
