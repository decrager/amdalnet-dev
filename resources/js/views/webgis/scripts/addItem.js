import * as urlUtils from '@arcgis/core/core/urlUtils';
import esriRequest from '@arcgis/core/request';

async function addPublishItem(token, projectTitle, file, mapItemId, notify) {
  const named = projectTitle.replace(/ /g, '_') + '_' + file.name.replace(/ /g, '_');

  const myHeaders = new Headers();
  myHeaders.append('Authorization', 'Bearer ' + token);

  const formDataArcgis = new FormData();
  formDataArcgis.append('type', 'Shapefile');
  formDataArcgis.append('tags', 'Amdalnet Web');
  formDataArcgis.append('f', 'json');
  formDataArcgis.append('title', named);
  formDataArcgis.append('file', file);

  const formDataPublish = new FormData();
  formDataPublish.append('filetype', 'shapefile');
  formDataPublish.append('f', 'json');

  urlUtils.addProxyRule({
    proxyUrl: 'proxy/proxy.php',
    urlPrefix: 'https://amdalgis.menlhk.go.id/',
  });

  const requestOptions = {
    method: 'POST',
    headers: myHeaders,
    body: formDataArcgis,
  };

  esriRequest('https://amdalgis.menlhk.go.id/portal/sharing/rest/content/users/Amdalnet/addItem', requestOptions)
    .then(response => {
      if (response.data.success === true) {
        mapItemId = response.data.id;

        formDataPublish.append('itemId', mapItemId);
        formDataPublish.append('publishParameters', `{"hasStaticData":true,"name":${named},"maxRecordCount":2000,"layerInfo":{"capabilities":"Query"}}`);

        const requestOptionsPublish = {
          method: 'POST',
          headers: myHeaders,
          body: formDataPublish,
        };

        esriRequest('https://amdalgis.menlhk.go.id/portal/sharing/rest/content/users/Amdalnet/publish', requestOptionsPublish)
          .then(() => notify)
          .catch(error => console.log('error', error));
      }
    })
    .catch(error => console.log('error', error));
}

export default addPublishItem;
