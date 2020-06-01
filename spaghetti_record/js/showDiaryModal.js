const diaryDay = document.getElementsByClassName('diaryDay');
const diaryModal = document.getElementsByClassName('diaryModal');
const diaryModalHidden = document.getElementsByClassName('diaryModalHidden');

// カレンダ内の各日にちごとにイベント設定
for(let i = 0;i < diaryDay.length;i++){
  // 日にちをクリックした場合
  diaryDay[i].addEventListener('click',function(){
    // モーダルを表示
    diaryModal[i].classList.remove('hidden');
  });
  // モーダル内の閉じるをクリックした場合
  diaryModalHidden[i].addEventListener('click',function(){
    // モーダルを非表示
    diaryModal[i].classList.add('hidden');
  });
}
