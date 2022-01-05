import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
import popupTemplate from './popupTemplate';
import urlBlob from './urlBlob';
import axios from 'axios';

function layerOnload(map, mapArray, layerProject, checkedPantau, checkedKelola, checkedEcology, checkedSosial, checkedStudi, checkedTapak) {
  axios.get(`api/map-geojson`)
    .then((response) => {
      response.data.forEach((item) => {
        const getType = JSON.parse(item.feature_layer);
        const propType = getType.features[0].properties.type;
        const propFields = getType.features[0].properties.field;
        const propStyles = getType.features[0].properties.styles;

        // Pemantauan
        if (propType === 'pemantauan') {
          const geojsonLayerArray = new GeoJSONLayer({
            url: urlBlob(item.feature_layer),
            outFields: ['*'],
            visible: true,
            title: 'Layer Titik Pemantauan',
            renderer: propStyles,
            popupTemplate: popupTemplate(propFields),
          });

          mapArray.push(geojsonLayerArray);
          const toggle = document.getElementById('layerPantauCheckBox');

          toggle.addEventListener('change', () => {
            if (checkedPantau === true) {
              geojsonLayerArray.visible = true;
            } else {
              map.removeMany(layerProject);
              geojsonLayerArray.visible = false;
            }
          });
        }

        // Pengelolaan
        if (propType === 'study') {
          const geojsonLayerArray = new GeoJSONLayer({
            url: urlBlob(item.feature_layer),
            outFields: ['*'],
            visible: true,
            title: 'Layer Titik Pengelolaan',
            renderer: propStyles,
            popupTemplate: popupTemplate(propFields),
          });

          mapArray.push(geojsonLayerArray);
          const toggle = document.getElementById('layerKelolaCheckBox');

          toggle.addEventListener('change', () => {
            if (checkedKelola === true) {
              geojsonLayerArray.visible = true;
            } else {
              map.removeMany(layerProject);
              geojsonLayerArray.visible = false;
            }
          });
        }

        // Ecology
        if (propType === 'ecology') {
          const geojsonLayerArray = new GeoJSONLayer({
            url: urlBlob(item.feature_layer),
            outFields: ['*'],
            title: 'Layer Batas Ekologis',
            visible: false,
            renderer: propStyles,
            popupTemplate: popupTemplate(propFields),
          });

          mapArray.push(geojsonLayerArray);
          const ecologyToggle = document.getElementById('layerEcologyCheckBox');

          ecologyToggle.addEventListener('change', () => {
            if (checkedEcology === true) {
              geojsonLayerArray.visible = true;
            } else {
              map.removeMany(layerProject);
              geojsonLayerArray.visible = false;
            }
          });
        }

        // Social
        if (propType === 'social') {
          const geojsonLayerArray = new GeoJSONLayer({
            url: urlBlob(item.feature_layer),
            outFields: ['*'],
            visible: false,
            title: 'Layer Batas Sosial',
            renderer: propStyles,
            popupTemplate: popupTemplate(propFields),
          });

          mapArray.push(geojsonLayerArray);
          const toggle = document.getElementById('layerSosialCheckBox');

          toggle.addEventListener('change', () => {
            if (checkedSosial === true) {
              geojsonLayerArray.visible = true;
            } else {
              map.removeMany(layerProject);
              geojsonLayerArray.visible = false;
            }
          });
        }

        // Study
        if (propType === 'study') {
          const geojsonLayerArray = new GeoJSONLayer({
            url: urlBlob(item.feature_layer),
            outFields: ['*'],
            visible: false,
            title: 'Layer Batas Studi',
            renderer: propStyles,
            popupTemplate: popupTemplate(propFields),
          });

          mapArray.push(geojsonLayerArray);
          const toggle = document.getElementById('layerStudiCheckBox');

          toggle.addEventListener('change', () => {
            if (checkedStudi === true) {
              geojsonLayerArray.visible = true;
            } else {
              map.removeMany(layerProject);
              geojsonLayerArray.visible = false;
            }
          });
        }

        // Tapak
        if (propType === 'tapak') {
          const geojsonLayerArray = new GeoJSONLayer({
            url: urlBlob(item.feature_layer),
            outFields: ['*'],
            visible: false,
            title: 'Layer Tapak Proyek',
            renderer: propStyles,
            popupTemplate: popupTemplate(propFields),
          });

          mapArray.push(geojsonLayerArray);
          const toggle = document.getElementById('layerTapakCheckBox');

          toggle.addEventListener('change', () => {
            if (checkedTapak === true) {
              geojsonLayerArray.visible = true;
            } else {
              map.removeMany(layerProject);
              geojsonLayerArray.visible = false;
            }
          });
        }

        map.addMany(mapArray);
      });
    });
}

export default layerOnload;
