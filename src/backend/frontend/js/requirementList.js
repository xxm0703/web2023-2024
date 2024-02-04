import { Http } from './http.js';

function removeRequirementsFromList() {
  const element = document.getElementById('requirement-list');
  while (element.firstChild) {
    element.firstChild.remove();
  }
}

function filterRequirements() {
  const list = document.getElementById('requirement-list');
  const showFunc = document.getElementById('func-checkbox').checked;
  const showNonfunc = document.getElementById('non-func-checkbox').checked;

  for (let i = 0; i < list.children.length; i++) {
    const child = list.children[i];
    const isNonfunctional = child.id.includes('nonfunctional');

    child.classList.add('hidden');
    if ((showFunc && !isNonfunctional) || (showNonfunc && isNonfunctional)) {
      child.classList.remove('hidden');
    }
  }
}

function onFormSubmit(event) {
  event.preventDefault();
  const projectId = document.getElementById('projectId').value;

  removeRequirementsFromList();
  fetchRequirements('functional', projectId);
  fetchRequirements('nonfunctional', projectId);

  document.getElementById('func-checkbox').checked = true;
  document.getElementById('non-func-checkbox').checked = true;
}

function fetchRequirements(type, projectId) {
  const endPoint =
    type === 'functional'
      ? 'functionalRequirements/'
      : 'nonfunctionalRequirements/';

  const http = new Http();
  http
    .get(endPoint, { query: { projectId } })
    .then((data) => {
      data.forEach((requirement) => displayRequirement(requirement, type));
    })
    .catch((error) => console.error('Error:', error));
}

function capitalize(word) {
  return `${word[0].toUpperCase()}${word.slice(1)}`;
}

function displayRequirement(requirement, type) {
  const requirementList = document.getElementById('requirement-list');

  const box = document.createElement('li');
  box.id = `box-${type}-requirement-${requirement.id}`;
  box.className = 'requirement-item';

  const requirementElement = document.createElement('pre');
  requirementElement.className = 'requirement';
  requirementElement.innerHTML = Object.entries(requirement)
    .map(([k, v]) => `${capitalize(k)}: ${v ?? '-'}`)
    .join('\n');

  const deleteButton = document.createElement('button');
  deleteButton.innerHTML = 'Delete';
  deleteButton.className = 'delete-button';
  deleteButton.onclick = () => deleteRequirement(requirement, type);

  box.appendChild(requirementElement);
  box.appendChild(deleteButton);
  requirementList.appendChild(box);
}

function deleteRequirement(requirement, type) {
  const http = new Http();
  const endPoint = requirement?.unit
    ? `nonfunctionalRequirements/${requirement.id}`
    : `functionalRequirements/${requirement.id}`;
  http.delete(endPoint).catch((error) => console.error('Error:', error));

  document.getElementById(`box-${type}-requirement-${requirement.id}`).remove();
}

document
  .getElementById('requirement-form')
  .addEventListener('submit', onFormSubmit);

document
  .getElementById('func-checkbox')
  .addEventListener('change', filterRequirements);

document
  .getElementById('non-func-checkbox')
  .addEventListener('change', filterRequirements);
