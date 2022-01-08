import esriRequest from '@arcgis/core/request';
import FeatureLayer from '@arcgis/core/layers/FeatureLayer';
import Field from '@arcgis/core/layers/support/Field';
import Graphic from '@arcgis/core/Graphic';

function generateFeatureCollection(fileName, map, mapView) {
  let name = fileName.split('.');
  name = name[0].replace('c:\\fakepath\\', '');

  document.getElementById('upload-status').innerHTML =
        '<b>Loading </b>' + name;

  const params = {
    name: name,
    targetSR: mapView.spatialReference,
    maxRecordCount: 1000,
    enforceInputFileSizeLimit: true,
    enforceOutputJsonSizeLimit: true,
  };

  params.generalize = true;
  params.maxAllowableOffset = 10;
  params.reducePrecision = true;
  params.numberOfDigitsAfterDecimal = 0;

  const myContent = {
    filetype: 'shapefile',
    publishParameters: JSON.stringify(params),
    f: 'json',
  };

  const portalUrl = 'https://www.arcgis.com';
  esriRequest(portalUrl + '/sharing/rest/content/features/generate', {
    query: myContent,
    body: document.getElementById('uploadForm'),
    responseType: 'json',
  })
    .then((response) => {
      const layerName = response.data.featureCollection.layers[0].layerDefinition.name;
      document.getElementById('upload-status').innerHTML = '<b>Loaded: </b>' + layerName;
      addShapefileToMap(response.data.featureCollection, map, mapView);
    })
    .catch(errorHandler);
}

function errorHandler(error) {
  document.getElementById('upload-status').innerHTML =
        "<p style='color:red;max-width: 500px;'>" + error.message + '</p>';
}

var layerIn = [];

function addShapefileToMap(featureCollection, map, mapView) {
  let sourceGraphics = [];

  const layers = featureCollection.layers.map((layer) => {
    const graphics = layer.featureSet.features.map((feature) => {
      return Graphic.fromJSON(feature);
    });
    sourceGraphics = sourceGraphics.concat(graphics);
    const featureLayer = new FeatureLayer({
      objectIdField: 'FID',
      source: graphics,
      fields: layer.layerDefinition.fields.map((field) => {
        return Field.fromJSON(field);
      }),
    });
    return featureLayer;
  });
  map.addMany(layers);
  mapView.goTo(sourceGraphics).catch((error) => {
    if (error.name !== 'AbortError') {
      console.error(error);
    }
  });

  layerIn.push(layers);
  document.getElementById('upload-status').innerHTML = '';
}

export { generateFeatureCollection, layerIn };
