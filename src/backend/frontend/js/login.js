import { Http } from './http.js';

const onPageLoad = () => {
  const http = new Http();

  http.get('/session').then((result) => {
    if (result) {
      console.log('here')
      window.location.href = '/frontend/pages/dashboard.php';
    }
  });
};

function onLoginFormSubmit(event) {
  event.preventDefault();

  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  const http = new Http();

  http
    .post('/login', {
      body: { email, password },
    })
    .then((result) => {
      if (result) {
        onPageLoad();
      } else {
        alert('Invalid credentials');
      }
    });
}

onPageLoad();

const form = document.getElementById('login-form');
form.addEventListener('submit', onLoginFormSubmit);
