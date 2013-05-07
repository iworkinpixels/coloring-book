function componentToHex(c) {
  var hex = Number(c).toString(16);
  return hex.length == 1 ? "0" + hex : hex;
}

function rgbToHex(r, g, b) {
  return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
  if ((new Date().getTime() - start) > milliseconds){
    break;
  }
  }
}

var serial;

function setup()
{
  serial = (document.getElementById("seriality")).Seriality();
  serial.begin(serial.ports[2], 9600);
  setInterval(loop, 5);
}

function loop() {
  var json;
  var data;
  serial.write('.');
  if (serial.available()) {
    while(serial.available()){
      json = serial.readLine();
    }
    if(json!=''){
      try{
        data = eval('('+json+')');
      } catch(e){
        data = undefined;
      }
      if (undefined != data) {
        dump(data);
        if(!isNaN(data.red) & !isNaN(data.green) && !isNaN(data.blue)) {
          colorCode = rgbToHex(data.red, data.green, data.blue);
          $('#preview-box').css('backgroundColor',colorCode);
          $('#preview-box').html(colorCode);
        }
      }
    }
  }
}

function cfn(cn){
  // Returns a properly formatted component name
  cn = cn.toLowerCase();
  cn = cn.replace(' ','_');
  return cn;
}

function nextComponent(){
  // Set the color!
  color = $('#preview-box').html();
  textbox = $('#'+cfn(currentComponent));
  textbox.css('backgroundColor', color);
  textbox.val(color);

  // Update everything and set a new timeout!
  currentIndex = components.indexOf(currentComponent);
  if (currentIndex < components.length - 1) {
    currentComponent = components[currentIndex+1];
    $('div#msg-box').html('You have 10 seconds to choose a '+currentComponent+' color!');
    setTimeout(nextComponent,10000);
  } else {
    $('div#msg-box').html('Congratulations, you have chosen all of your colors!  Please click on the \'Draw Picture!\' button to see your finished work of art!');
  }
}

