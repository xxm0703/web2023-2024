import { Http } from './http.js';

function onPageLoad() {
  document.getElementById('requirementUnit').classList.add('hidden');
  document.getElementById('requirementValue').classList.add('hidden');
}

function nullIfEmpty(string) {
  return string === '' ? null : string;
}

function onAddRequirement(e) {
  e.preventDefault();

  const name = document.getElementById('requirementName').value;
  const description = document.getElementById('requirementDescription').value;
  const priority = document.getElementById('requirementPriority').value;
  const projectId = document.getElementById('requirementProjectId').value;
  const estimate = document.getElementById('requirementEstimate').value;
  const unit = document.getElementById('requirementUnit').value;
  const value = document.getElementById('requirementValue').value;
  const type = document.getElementById('requirementType').value;

  let endPoint = 'functionalRequirements/';
  let body = {
    name,
    description: nullIfEmpty(description),
    priority,
    projectId,
  };

  if (type === 'nonfunctional') {
    endPoint = 'nonfunctionalRequirements/';
    body = { ...body, unit: nullIfEmpty(unit), value: nullIfEmpty(value) };
  } else {
    body = { ...body, estimate: nullIfEmpty(estimate) };
  }

  const http = new Http();
  http
    .post(endPoint, { body })
    .then((_) => {
      document.getElementById('requirementName').value = '';
      document.getElementById('requirementDescription').value = '';
      document.getElementById('requirementPriority').value = '1';
      document.getElementById('requirementProjectId').value = projectId;
      document.getElementById('requirementEstimate').value = '';
      document.getElementById('requirementUnit').value = '';
      document.getElementById('requirementValue').value = '';
      document.getElementById('requirementType').value = type;
    })
    .catch((error) => console.error('Error:', error));
}

function onRequirementTypeChange(event) {
  if (event.target.value === 'functional') {
    document.getElementById('requirementUnit').classList.add('hidden');
    document.getElementById('requirementValue').classList.add('hidden');
    document.getElementById('requirementEstimate').classList.remove('hidden');
  } else {
    document.getElementById('requirementUnit').classList.remove('hidden');
    document.getElementById('requirementValue').classList.remove('hidden');
    document.getElementById('requirementEstimate').classList.add('hidden');
  }
}

document
  .getElementById('requirement-form')
  .addEventListener('submit', onAddRequirement);

document
  .getElementById('requirementType')
  .addEventListener('change', onRequirementTypeChange);

onPageLoad();
