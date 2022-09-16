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
    async loadMap() {
      axios.get('api/map/' + this.idProject).then((response) => {
        const map = new Map({
          basemap: 'satellite',
        });

        const projects = response.data;
        let tapakAdded = false;
        let socialAdded = false;
        let studyAdded = false;
        let ecologyAdded = false;
        for (let i = 0; i < projects.length; i++) {
          let layerTitle = '';
          let layerOutlineColor = '';
          if (projects[i].attachment_type === 'tapak' && !tapakAdded) {
            layerTitle = 'Layer Tapak Proyek';
            layerOutlineColor = '#964B00';
            tapakAdded = true;
          } else if (projects[i].attachment_type === 'study' && !studyAdded) {
            layerTitle = 'Layer Batas Wilayah Studi';
            layerOutlineColor = 'green';
            studyAdded = true;
          } else if (projects[i].attachment_type === 'ecology' && !ecologyAdded) {
            layerTitle = 'Layer Batas Ekologi';
            layerOutlineColor = 'red';
            ecologyAdded = true;
          } else if (projects[i].attachment_type === 'social' && !socialAdded) {
            layerTitle = 'Layer Batas Sosial';
            layerOutlineColor = 'blue';
            socialAdded = true;
          }
          if (layerTitle !== '') {
            axios({
              url: 'api/download-map-by-name?filename=' + projects[i].stored_filename,
              method: 'GET',
              responseType: 'blob',
            }).then((response) => {
              shp(
                window.location.origin +
                  '/storage/map/' +
                  projects[i].stored_filename
              ).then((data) => {
                const blob = new Blob([JSON.stringify(data)], {
                  type: 'application/json',
                });
                const url = URL.createObjectURL(blob);
                const rendererLayer = {
                  type: 'simple',
                  field: '*',
                  symbol: {
                    type: 'simple-fill',
                    color: [0, 0, 0, 0.0],
                    outline: {
                      color: layerOutlineColor,
                      width: 2,
                    },
                  },
                };
                const geojsonLayerArray = new GeoJSONLayer({
                  url: url,
                  outFields: ['*'],
                  visible: true,
                  title: layerTitle,
                  renderer: rendererLayer,
                });

                map.add(geojsonLayerArray);
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
