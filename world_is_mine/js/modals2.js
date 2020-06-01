const login = document.getElementById('login');
const loginModal = document.getElementById('loginModal');
const closeLoginModal = document.getElementById('closeLoginModal');
const signup = document.getElementById('signup');
const signupModal = document.getElementById('signupModal');
const closeSignupModal = document.getElementById('closeSignupModal');

login.addEventListener('click',()=>{
  loginModal.classList.remove('hidden');
});
closeLoginModal.addEventListener('click',()=>{
  loginModal.classList.add('hidden');
});
signup.addEventListener('click',()=>{
  signupModal.classList.remove('hidden');
});
closeSignupModal.addEventListener('click',()=>{
  signupModal.classList.add('hidden');
});
