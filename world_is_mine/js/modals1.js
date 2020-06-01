const radarChartModal = document.getElementById('radarChartModal');
const closeRadarchartModal = document.getElementById('closeRadarChartModal');
const userMsgModal = document.getElementById('userMsgModal');
const closeUserMsgModal = document.getElementById('closeUserMsgModal');
const cancel = document.getElementById('cancel');
const cancelModal = document.getElementById('cancelModal');
const closeCancelModal = document.getElementById('closeCancelModal');

closeRadarChartModal.addEventListener('click',()=>{
  radarChartModal.classList.add('hidden');
});
closeUserMsgModal.addEventListener('click',()=>{
  userMsgModal.classList.add('hidden');
});
cancel.addEventListener('click',()=>{
  cancelModal.classList.remove('hidden');
});
closeCancelModal.addEventListener('click',()=>{
  cancelModal.classList.add('hidden');
});
