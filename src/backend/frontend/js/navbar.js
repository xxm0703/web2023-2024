import { Http } from './http.js';

const onLogoutButtonClick = () => {
  const http = new Http();
  http
    .delete('/logout')
    .then((_) => (window.location.href = '/frontend/pages/login.php'));
};

function onDashboardButtonClick() {
  window.location.href = '/frontend/pages/dashboard.php'
}

document
  .getElementById('logout-button')
  .addEventListener('click', onLogoutButtonClick);

document
  .getElementById('dashboard-button')
  .addEventListener('click', onDashboardButtonClick);
