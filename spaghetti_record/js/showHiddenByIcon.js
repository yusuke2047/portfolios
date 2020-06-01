const iconLeft = document.getElementById('iconLeft');
const iconRight = document.getElementById('iconRight');
const forIconLeft = document.getElementById('forIconLeft');
const forIconRight = document.getElementById('forIconRight');

// 左アイコンによる表示制御
iconLeft.addEventListener('click',function(){
  // 左アイコンをクリックしたとき、対応する要素が表示されていれば非表示、表示されていなければ表示
  forIconLeft.classList.toggle('hidden');
  // 右アイコンに対応する要素が表示されている場合、非表示
  if(!forIconRight.classList.contains('hidden')){
    forIconRight.classList.add("hidden");
  }
});
// 右アイコンによる表示制御
iconRight.addEventListener('click',function(){
  // 右アイコンをクリックしたとき、対応する要素が表示されていれば非表示、表示されていなければ表示
  forIconRight.classList.toggle('hidden');
  // 左アイコンに対応する要素が表示されている場合、非表示
  if(!forIconLeft.classList.contains('hidden')){
    forIconLeft.classList.add("hidden");
  }
});
