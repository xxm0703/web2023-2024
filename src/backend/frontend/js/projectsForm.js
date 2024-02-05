import { Http } from './http.js';

function fetchProjects(projectId) {
  const endPoint = 'projects/';

  const projectList = document.getElementById('projects-list');
  while (projectList.firstChild) {
    projectList.removeChild(projectList.firstChild);
  }

  const http = new Http();
  http
    .get(endPoint)
    .then((data) => {
      data.forEach((project) => displayProject(project));
    })
    .catch((error) => console.error('Error:', error));
}

function capitalize(word) {
  return `${word[0].toUpperCase()}${word.slice(1)}`;
}

function displayProject(project) {
  const projectList = document.getElementById('projects-list');

  const box = document.createElement('li');
  box.id = `box-project-${project.id}`;
  box.className = 'project-item';

  const projectElement = document.createElement('pre');
  projectElement.className = 'project';
  projectElement.innerHTML = Object.entries(project)
    .map(([k, v]) => `${capitalize(k)}: ${v ?? '-'}`)
    .join('\n');

  const deleteButton = document.createElement('button');
  deleteButton.innerHTML = 'Delete';
  deleteButton.className = 'delete-button';
  deleteButton.onclick = () => deleteProject(project);

  box.appendChild(projectElement);
  box.appendChild(deleteButton);
  projectList.appendChild(box);
}

function deleteProject(project) {
  const http = new Http();
  const endPoint = `projects/${project.id}`;
  http.delete(endPoint).catch((error) => console.error('Error:', error));

  document.getElementById(`box-project-${project.id}`).remove();
}

function onAddProject(e) {
  e.preventDefault();

  const name = document.getElementById('projectName').value;
  const startDate = document.getElementById('projectStartDate').value;

  let endPoint = 'projects/';
  let body = {
    name,
    startDate
  };
console.log(body);
  const http = new Http();
  http
    .post(endPoint, { body })
    .then((_) => {
      document.getElementById('projectName').value = '';
      document.getElementById('projectStartDate').value = '';
      fetchProjects();
    })
    .catch((error) => console.error('Error:', error));
}

fetchProjects();

document
  .getElementById('projects-form')
  .addEventListener('submit', onAddProject);

