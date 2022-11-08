<template>
  <div id="mapView" />
</template>

<script>
import axios from 'axios';
import Map from '@arcgis/core/Map';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
import esriConfig from '@arcgis/core/config.js';
import MapView from '@arcgis/core/views/MapView';
import Legend from '@arcgis/core/widgets/Legend';
import LayerList from '@arcgis/core/widgets/LayerList';
import shp from 'shpjs';
import Expand from '@arcgis/core/widgets/Expand';
import GroupLayer from '@arcgis/core/layers/GroupLayer';
import MapImageLayer from '@arcgis/core/layers/MapImageLayer';

export default {
  name: 'WebgisVerifikasi',
  data() {
    return {
      readonly: true,
      geomFromGeojson: {},
      geomProperties: {},
      mapUpload: null,
      idProject: 0,
      urlKa: null,
    };
  },
  mounted() {
    this.idProject = parseInt(this.$route.params && this.$route.params.id);
    this.loadDocumentType();
    this.loadMap();
  },
  methods: {
    async loadDocumentType() {
      const data = this.$route.name;
      this.urlKa = data;
    },
    async loadMap() {
      axios.get('api/map/' + this.idProject).then((response) => {
        const map = new Map({
          basemap: 'satellite',
        });

        esriConfig.request.proxyUrl = 'https://sigap.menlhk.go.id/proxy/proxy.php';
        const penutupanLahan2020 = new MapImageLayer({
          url: 'https://sigap.menlhk.go.id/server/rest/services/KLHK/A_Penutupan_Lahan_2020/MapServer',
          sublayers: [
            {
              id: 0,
              visible: true,
            },
          ],
        });

        const kawasanHutanB = new MapImageLayer({
          url: 'https://sigap.menlhk.go.id/server/rest/services/KLHK/B_Kawasan_Hutan/MapServer',
          sublayers: [
            {
              id: 0,
              visible: true,
            },
          ],
        });

        const pippib2022Periode1 = new MapImageLayer({
          url: 'https://sigap.menlhk.go.id/server/rest/services/KLHK/D_PIPPIB_2022_Periode_1/MapServer',
          sublayers: [
            {
              id: 0,
              visible: true,
            },
          ],
        });

        const sigapLayer = new GroupLayer({
          title: 'Peta Tematik Status',
          visible: true,
          layers: [penutupanLahan2020, kawasanHutanB, pippib2022Periode1],
          opacity: 0.90,
        });

        map.add(sigapLayer);

        const projects = response.data;
        let tapakAdded = false;
        let socialAdded = false;
        let studyAdded = false;
        let ecologyAdded = false;
        let pemantuanAdded = false;
        let areaPemantuanAdded = false;
        let pengelolaanAdded = false;
        let areaPengelolaanAdded = false;
        for (let i = 0; i < projects.length; i++) {
          let layerTitle = '';
          let layerOutlineColor = '';
          if (projects[i].attachment_type === 'tapak' && !tapakAdded && projects[i].step === 'ka') {
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
          } else if (projects[i].attachment_type === 'pengelolaan' && !pengelolaanAdded && this.urlKa !== 'ujiBerkasAdministrasiKA') {
            layerTitle = 'Layer Batas Pengelolaan';
            layerOutlineColor = 'purple';
            pengelolaanAdded = true;
          } else if (projects[i].attachment_type === 'pemantauan' && !pemantuanAdded && this.urlKa !== 'ujiBerkasAdministrasiKA') {
            layerTitle = 'Layer Batas Pemantuan';
            layerOutlineColor = 'yellow';
            pemantuanAdded = true;
          } else if (projects[i].attachment_type === 'area-pengelolaan' && !areaPengelolaanAdded && this.urlKa !== 'ujiBerkasAdministrasiKA') {
            layerTitle = 'Layer Batas Area Pengelolaan';
            layerOutlineColor = 'black';
            areaPengelolaanAdded = true;
          } else if (projects[i].attachment_type === 'area-pemantauan' && !areaPemantuanAdded && this.urlKa !== 'ujiBerkasAdministrasiKA') {
            layerTitle = 'Layer Batas Area Pemantuan';
            layerOutlineColor = 'brown';
            areaPemantuanAdded = true;
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

        const layerListExpand = new Expand({
          expandIconClass: 'esri-icon-layer-list',
          expandTooltip: 'Layer List',
          view: mapView,
          content: layerList,
        });

        const legendExpand = new Expand({
          expandIconClass: 'esri-icon-basemap',
          expandTooltip: 'Legend Layer',
          view: mapView,
          content: legend,
        });

        mapView.ui.add(layerListExpand, 'top-right');
        mapView.ui.add(legendExpand, 'top-right');
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
