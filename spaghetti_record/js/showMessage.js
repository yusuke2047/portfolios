// アイコンにカーソルがあたるとメッセージを表示
const logo = document.getElementById('logo');
const msg = document.getElementById('msg');
// アイコンにカーソルがあたっている場合、メッセージを表示
logo.addEventListener('mouseover',function(){
  msg.classList.remove('hidden');
});
// アイコンにカーソルがあたっていない場合、メッセージを非表示
logo.addEventListener('mouseout',function(){
  msg.classList.add('hidden');
});
