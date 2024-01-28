import { Http } from './http.js';

function onPageLoad() {
  fetchRequirements('functional');
  fetchRequirements('nonfunctional');
}

function fetchRequirements(type) {
  const endPoint =
    type === 'functional'
      ? 'functionalRequirements/'
      : 'nonfunctionalRequirements/';

  const http = new Http();
  http
    .get(endPoint)
    .then((data) => {
      data.forEach((requirement) => displayRequirement(requirement));
    })
    .catch((error) => console.error('Error:', error));
}

function displayRequirement(requirement) {
  const requirementList = document.getElementById('requirement-list');

  const box = document.createElement('li');
  box.id = `box-requirement-${requirement.id}`;
  box.style.display = 'flex';
  box.style.gap = '4px';

  const requirementElement = document.createElement('div');
  requirementElement.className = 'requirement';
  requirementElement.innerHTML = Object.values(requirement).join(' - ');

  const deleteButton = document.createElement('button');
  deleteButton.innerHTML = 'Delete';
  deleteButton.onclick = () => deleteRequirement(requirement);

  box.appendChild(requirementElement);
  box.appendChild(deleteButton);
  requirementList.appendChild(box);
}

function deleteRequirement(requirement) {
  const http = new Http();
  const endPoint = requirement?.unit
    ? `nonfunctionalRequirements/${requirement.id}`
    : `functionalRequirements/${requirement.id}`;
  http.delete(endPoint).catch((error) => console.error('Error:', error));

  document.getElementById(`box-requirement-${requirement.id}`).remove();
}

onPageLoad();
