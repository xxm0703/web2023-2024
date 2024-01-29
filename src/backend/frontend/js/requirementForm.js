import { Http } from './http.js';

function onAddRequirement() {
  const name = document.getElementById('requirementName').value;
  const descriptionValue = document.getElementById('requirementDescription').value;
  const description = descriptionValue === '' ? null : descriptionValue
  const priority = document.getElementById('requirementPriority').value;
  const projectId = document.getElementById('requirementProjectId').value;
  const unit = document.getElementById('requirementUnit').value;
  const value = document.getElementById('requirementValue').value;
  const type = document.getElementById('requirementType').value;

  let endPoint = 'functionalRequirements/';
  let body = { name, description, priority, projectId };

  if (type === 'nonfunctional') {
    endPoint = 'nonfunctionalRequirements/';
    body = { ...body, unit, value };
  }

  const http = new Http();
  http
    .post(endPoint, { body })
    .then((_) => {
      document.getElementById('requirementName').value = undefined;
      document.getElementById('requirementDescription').value = undefined;
      document.getElementById('requirementPriority').value = "1";
      document.getElementById('requirementProjectId').value = undefined;
      document.getElementById('requirementUnit').value = undefined;
      document.getElementById('requirementValue').value = undefined;
      document.getElementById('requirementType').value = type;
    })
    .catch((error) => console.error('Error:', error));
}

document
  .getElementById('requirement-form')
  .addEventListener('submit', onAddRequirement);
