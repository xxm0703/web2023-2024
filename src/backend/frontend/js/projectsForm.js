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
  http.delete(endPoint).then((resp) => {
    if (resp)
      document.getElementById(`box-project-${project.id}`).remove();
  }).catch((error) => console.error('Error:', error));

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

function importProjects(event) {
  event.preventDefault();
  var file = event.target.files[0];
  var reader = new FileReader();

  reader.onload = function(e) {
    let endPoint = 'projects/import/';
    var body = JSON.parse(e.target.result);
    console.log(body);
    console.log(e.target.result);
    const http = new Http();
    http
      .post(endPoint, { body })
      .then((_) => {
        fetchProjects();
      })
      .catch((error) => console.error('Error:', error));
  };

  reader.readAsText(file);
}

fetchProjects();
document.getElementById('jsonFile').addEventListener('change', importProjects);
document
  .getElementById('projects-form')
  .addEventListener('submit', onAddProject);

