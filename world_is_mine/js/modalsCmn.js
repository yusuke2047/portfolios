const about = document.getElementById('about');
const aboutModal = document.getElementById('aboutModal');
const closeAboutModal = document.getElementById('closeAboutModal');
const rankingModal = document.getElementById('rankingModal');
const closeRankingModal = document.getElementById('closeRankingModal');
const sendMsgModal = document.getElementById('sendMsgModal');
const closeSendMsgModal = document.getElementById('closeSendMsgModal');

about.addEventListener('click',()=>{
  aboutModal.classList.remove('hidden');
});
closeAboutModal.addEventListener('click',()=>{
  aboutModal.classList.add('hidden');
});
closeRankingModal.addEventListener('click',()=>{
  rankingModal.classList.add('hidden');
});
closeSendMsgModal.addEventListener('click',()=>{
  sendMsgModal.classList.add('hidden');
});
