import { Http } from './http.js';

const onPageLoad = () => {
  const http = new Http();

  http.get('/session').then((result) => {
    if (result) {
      window.location.href = '/frontend/pages/dashboard.php';
    }
  });
};

function onRegisterFormSubmit(event) {
  event.preventDefault();

  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  const http = new Http();

  http
    .post('/register', {
      body: { email, password },
    })
    .then((result) => {
      if (result) {
        window.location.href = '/frontend/pages/login.php';
      } else {
        alert('Something went wrong');
      }
    });
}

onPageLoad();

document
  .getElementById('register-form')
  .addEventListener('submit', onRegisterFormSubmit);
