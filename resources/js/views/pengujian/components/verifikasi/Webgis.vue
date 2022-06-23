<template>
  <div id="mapView" />
</template>

<script>
import axios from 'axios';
import Map from '@arcgis/core/Map';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
import MapView from '@arcgis/core/views/MapView';
import Legend from '@arcgis/core/widgets/Legend';
import LayerList from '@arcgis/core/widgets/LayerList';
import shp from 'shpjs';

export default {
  name: 'WebgisVerifikasi',
  data() {
    return {
      readonly: true,
      geomFromGeojson: {},
      geomProperties: {},
      mapUpload: null,
      idProject: 0,
    };
  },
  mounted() {
    this.idProject = parseInt(this.$route.params && this.$route.params.id);
    this.loadMap();
  },
  methods: {
    loadMap() {
      // let layerTapak = null;
      axios.get('api/map/' + this.idProject).then((response) => {
        if (response.data.length > 1) {
          const map = new Map({
            basemap: 'satellite',
          });

          const projects = response.data;
          for (let i = 0; i < projects.length; i++) {
            // Map Ekologi
            if (projects[i].attachment_type === 'ecology') {
              shp(
                window.location.origin +
                  '/storage/map/' +
                  projects[i].stored_filename
              ).then((data) => {
                const blob = new Blob([JSON.stringify(data)], {
                  type: 'application/json',
                });
                const url = URL.createObjectURL(blob);
                axios.get('api/projects/' + this.idProject).then((response) => {
                  const rendererTapak = {
                    type: 'simple',
                    field: '*',
                    symbol: {
                      type: 'simple-fill',
                      color: [0, 0, 0, 0.0],
                      outline: {
                        color: 'red',
                        width: 2,
                      },
                    },
                  };
                  const geojsonLayerArray = new GeoJSONLayer({
                    url: url,
                    outFields: ['*'],
                    visible: true,
                    title: 'Layer Batas Ekologi',
                    renderer: rendererTapak,
                  });
                  map.add(geojsonLayerArray);
                });
              });
            }

            // Map Sosial
            if (projects[i].attachment_type === 'social') {
              shp(
                window.location.origin +
                  '/storage/map/' +
                  projects[i].stored_filename
              ).then((data) => {
                const blob = new Blob([JSON.stringify(data)], {
                  type: 'application/json',
                });
                const url = URL.createObjectURL(blob);
                axios.get('api/projects/' + this.idProject).then((response) => {
                  const rendererTapak = {
                    type: 'simple',
                    field: '*',
                    symbol: {
                      type: 'simple-fill',
                      color: [0, 0, 0, 0.0],
                      outline: {
                        color: 'blue',
                        width: 2,
                      },
                    },
                  };
                  const geojsonLayerArray = new GeoJSONLayer({
                    url: url,
                    outFields: ['*'],
                    visible: true,
                    title: 'Layer Batas Sosial',
                    renderer: rendererTapak,
                  });
                  map.add(geojsonLayerArray);
                });
              });
            }

            // Map Studi
            if (projects[i].attachment_type === 'study') {
              shp(
                window.location.origin +
                  '/storage/map/' +
                  projects[i].stored_filename
              ).then((data) => {
                const blob = new Blob([JSON.stringify(data)], {
                  type: 'application/json',
                });
                const url = URL.createObjectURL(blob);
                axios.get('api/projects/' + this.idProject).then((response) => {
                  const rendererTapak = {
                    type: 'simple',
                    field: '*',
                    symbol: {
                      type: 'simple-fill',
                      color: [0, 0, 0, 0.0],
                      outline: {
                        color: 'green',
                        width: 2,
                      },
                    },
                  };
                  const geojsonLayerArray = new GeoJSONLayer({
                    url: url,
                    outFields: ['*'],
                    visible: true,
                    title: 'Layer Batas Wilayah Studi',
                    renderer: rendererTapak,
                  });
                  mapView.on('layerview-create', (event) => {
                    mapView.goTo({
                      target: geojsonLayerArray.fullExtent,
                    });
                  });
                  map.add(geojsonLayerArray);
                });
              });
            }

            // Map Tapak
            if (projects[i].attachment_type === 'tapak') {
              shp(
                window.location.origin +
                  '/storage/map/' +
                  projects[i].stored_filename
              ).then((data) => {
                const blob = new Blob([JSON.stringify(data)], {
                  type: 'application/json',
                });
                const url = URL.createObjectURL(blob);
                axios.get('api/projects/' + this.idProject).then((response) => {
                  const rendererTapak = {
                    type: 'simple',
                    field: '*',
                    symbol: {
                      type: 'simple-fill',
                      color: [0, 0, 0, 0.0],
                      outline: {
                        color: '#964B00',
                        width: 2,
                      },
                    },
                  };
                  const geojsonLayerArray = new GeoJSONLayer({
                    url: url,
                    outFields: ['*'],
                    visible: true,
                    title: 'Layer Tapak',
                    renderer: rendererTapak,
                  });
                  // if (layerTapak !== null) {
                  //   map.layers.remove(layerTapak);
                  // }
                  map.add(geojsonLayerArray);
                  // layerTapak = geojsonLayerArray;
                });
              });
            }
          }

          const mapView = new MapView({
            container: 'mapView',
            map: map,
            center: [115.287, -1.588],
            zoom: 5,
          });
          this.$parent.mapView = mapView;

          const layerList = new LayerList({
            view: mapView,
            container: document.createElement('div'),
            listItemCreatedFunction: this.defineActions,
          });

          layerList.on('trigger-action', (event) => {
            const id = event.action.id;
            if (id === 'full-extent') {
              mapView.goTo({
                target: event.item.layer.fullExtent,
              });
            }
          });

          const legend = new Legend({
            view: mapView,
            container: document.createElement('div'),
          });

          mapView.ui.add(layerList, 'top-right');
          mapView.ui.add(legend, 'bottom-left');
        }
      });
    },
    defineActions(event) {
      const item = event.item;

      item.actionsSections = [
        [
          {
            title: 'Go to full extent',
            className: 'esri-icon-zoom-in-magnifying-glass',
            id: 'full-extent',
          },
        ],
      ];
    },
  },
};
</script>

<style scoped>
#mapView {
  width: 85%;
  height: 90%;
  padding: 0;
  margin: 0 10px;
  position: absolute;
}
</style>
