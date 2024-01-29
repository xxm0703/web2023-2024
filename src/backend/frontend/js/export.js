import { Http } from './http.js';

function copyContent(element) {
  return () => {
    const exportString = element.innerText;
    var item = new ClipboardItem({
      'text/plain': new Blob([exportString], { type: 'text/plain' }),
    });

    navigator.clipboard.write([item]).then(function () {
      alert('Content copied to clipboard!');
    });
  };
}

function onExportWBSClick(event) {
  event.preventDefault();

  const projectId = document.getElementById('project-id').value;
  const exportType = document.getElementById('export-type').value;

  const http = new Http();
  http
    .get(`projects/${exportType}/${projectId}`)
    .then((result) => {
      const container = document.getElementById('container');

      const id = 'export-string';
      const exportString = document.createElement('pre');
      exportString.id = id;
      exportString.innerHTML = result;

      const prev = document.getElementById(id);
      const prevButton = document.getElementById(`${id}-button`);
      if (prev) {
        container.removeChild(prev);
        container.removeChild(prevButton);
      }

      const copyButton = document.createElement('button');
      copyButton.id = `${id}-button`;
      copyButton.innerText = 'Copy';
      copyButton.addEventListener('click', copyContent(exportString));

      container.appendChild(exportString);
      container.appendChild(copyButton);
    })
    .catch((error) => console.error('Error:', error));
}

document
  .getElementById('export-form')
  .addEventListener('submit', onExportWBSClick);
