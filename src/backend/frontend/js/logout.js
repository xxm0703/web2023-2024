import { Http } from './http.js';

const onLogoutButtonClick = () => {
  const http = new Http();
  http
    .delete('/logout')
    .then((_) => (window.location.href = '/frontend/pages/login.php'));
};

const logoutButton = document.getElementById('logout-button');
logoutButton.addEventListener('click', onLogoutButtonClick);
