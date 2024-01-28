import { Http } from './http.js';

function copyContent(element) {
  return () => {
    const wbsString = element.innerText;
    var item = new ClipboardItem({
      'text/plain': new Blob([wbsString], { type: 'text/plain' }),
    });

    navigator.clipboard.write([item]).then(function () {
      alert('Content copied to clipboard!');
    });
  };
}

function onExportWBSClick(event) {
  event.preventDefault();

  const projectId = document.getElementById('wbs-project-id').value;
  const http = new Http();
  http
    .get(`projects/wbs/${projectId}`)
    .then((result) => {
      const wbsContainer = document.getElementById('wbs-container');

      const id = 'wbs-string';
      const wbsString = document.createElement('pre');
      wbsString.id = id;
      wbsString.innerHTML = result;

      const prev = document.getElementById(id);
      const prevButton = document.getElementById(`${id}-button`);
      if (prev) {
        wbsContainer.removeChild(prev);
        wbsContainer.removeChild(prevButton);
      }

      const copyButton = document.createElement('button');
      copyButton.id = `${id}-button`;
      copyButton.innerText = 'Copy';
      copyButton.addEventListener('click', copyContent(wbsString));

      wbsContainer.appendChild(wbsString);
      wbsContainer.appendChild(copyButton);
      document.getElementById('wbs-project-id').value = undefined;
    })
    .catch((error) => console.error('Error:', error));
}

document
  .getElementById('wbs-form')
  .addEventListener('submit', onExportWBSClick);
