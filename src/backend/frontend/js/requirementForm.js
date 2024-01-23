import { Http } from './http.js';

function onAddRequirement() {
  const name = document.getElementById('requirementName').value;
  const description = document.getElementById('requirementDescription').value;
  const projectId = document.getElementById('requirementProjectId').value;
  const unit = document.getElementById('requirementUnit').value;
  const value = document.getElementById('requirementValue').value;
  const type = document.getElementById('requirementType').value;

  let endPoint = 'functionalRequirements/';
  let body = { name, description, projectId };

  if (type === 'nonfunctional') {
    endPoint = 'nonfunctionalRequirements/';
    body = { ...body, unit, value };
  }

  const http = new Http();
  http
    .post(endPoint, { body })
    .then((data) => {
      displayRequirement(data);

      document.getElementById('requirementName').value = '';
      document.getElementById('requirementDescription').value = '';
      document.getElementById('requirementProjectId').value = undefined;
      document.getElementById('requirementUnit').value = '';
      document.getElementById('requirementValue').value = '';
      document.getElementById('requirementType').value = 'functional';
    })
    .catch((error) => console.error('Error:', error));
}

document
  .getElementById('add-req-button')
  .addEventListener('click', onAddRequirement);
