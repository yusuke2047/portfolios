// ハンバーガメニュのアイコン
const hamburgerIcon = document.getElementById('hamburgerIcon');
// ハンバーガのリスト
const hamburgerList = document.getElementById('hamburgerList');
// リスト内の商品検索の項目
const showSearch = document.getElementById('showSearch');
// 検索フォーム
const searchForHamburger = document.getElementById('searchForHamburger');
// 検索フォーム内の閉じるボタン
const hiddenSearch = document.getElementById('hiddenSearch');

// ハンバーガのアイコンをクリックした場合
hamburgerIcon.addEventListener('click',function(){
  // ハンバーガのリストを表示
  hamburgerList.classList.add('slideIn');
});
// ハンバーガのリストをクリックした場合
hamburgerList.addEventListener('click',function(){
  // ハンバーガリストを非表示
  hamburgerList.classList.remove('slideIn');
});
// ハンバーガリスト内の商品検索の項目をクリックした場合
showSearch.addEventListener('click',function(){
  // 検索フォームを表示
  searchForHamburger.classList.add('slideIn');
});
// 検索フォーム内の閉じるボタンをクリックした場合
hiddenSearch.addEventListener('click',function(){
  // 検索フォームを非表示
  searchForHamburger.classList.remove('slideIn');
});
