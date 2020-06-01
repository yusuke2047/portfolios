const images = [
  'images/slot0.png',
  'images/slot1.png',
  'images/slot2.png',
  'images/slot3.png',
  'images/slot4.png',
  'images/slot5.png',
  'images/slot6.png',
  'images/slot7.png',
  'images/slot8.png',
  'images/slot9.png',
];
let timerId = [];
let isStart = false;
let isClick = [false,false,false];

function shuffle(col) {
  let length = images.length;
  let rand = Math.floor(Math.random() * length);
  document.getElementById('slot' + col).src = images[rand];
}

function start() {
  isStart = true;
  timerId[1] = setInterval(function() {
    shuffle(1)
  }, 60);
  timerId[2] = setInterval(function() {
    shuffle(2)
  }, 60);
  timerId[3] = setInterval(function() {
    shuffle(3)
  }, 60);
};

function stop(col) {
  if(isStart == true){
    clearInterval(timerId[col]);
    isClick[col] = true;
    if (isClick[1] == true && isClick[2] == true && isClick[3] == true) {
      judge();
    }
  }
}

function judge() {
  let result = 0;
  if (document.getElementById('slot1').src == document.getElementById('slot2').src && document.getElementById('slot1').src == document.getElementById('slot3').src) {
    result = 'win';
  } else if (document.getElementById('slot1').src == document.getElementById('slot2').src || document.getElementById('slot1').src == document.getElementById('slot3').src || document.getElementById('slot2').src == document.getElementById('slot3')
    .src) {
    result = 'draw';
  } else {
    result = 'lose';
  }
  location.href = 'slot.php?result=' + result;
}

function undo() {
  location.reload();
}
