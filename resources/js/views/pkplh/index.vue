<template>
  <div class="app-container">
    <el-card>
      <WorkflowUkl />
      <h3 align="center">
        Persetujuan Pernyataan Kesanggupan Pengelolaan Lingkungan Hidup
      </h3>
      <el-row :gutter="32">
        <el-col :sm="24" :md="12">
          <h4>Informasi Rencana Usaha/Kegiatan</h4>
          <Information />
        </el-col>
        <el-col :sm="24" :md="12">
          <h4>Dokumen Persetujuan Lingkungan</h4>
          <DokumenPersetujuan />
          <h4>Dokumen Persetujuan Lingkungan Final</h4>
          <PkplhFinal />
        </el-col>
        <el-alert
          v-if="isPublish"
          :title="'Persetujuan Lingkungan Diterbitkan'"
          type="success"
          description="Terimakasih"
          show-icon
          center
          :closable="false"
        />
      </el-row>
      <el-row :gutter="32">
        <el-col :sm="24" :md="24">
          <h4>Peta Lokasi</h4>
          <div class="map-container">
            <div id="mapView" />
          </div>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import Information from '@/views/skkl/components/Information';
import DokumenPersetujuan from '@/views/pkplh/components/DokumenPersetujuan';
import PkplhFinal from '@/views/pkplh/components/PkplhFinal';
import WorkflowUkl from '@/components/WorkflowUkl';

import axios from 'axios';

// Map-related
import shp from 'shpjs';
import Map from '@arcgis/core/Map';
import MapView from '@arcgis/core/views/MapView';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
import Legend from '@arcgis/core/widgets/Legend';
import LayerList from '@arcgis/core/widgets/LayerList';
import Expand from '@arcgis/core/widgets/Expand';

export default {
  name: 'SKKL',
  components: {
    Information,
    DokumenPersetujuan,
    PkplhFinal,
    WorkflowUkl,
  },
  data() {
    return {
      idProject: this.$route.params.id,
      readonly: true,
    };
  },
  computed: {
    isPublish(){
      return this.$store.getters.markingStatus === 'uklupl-mt.pkplh-published';
    },
  },
  mounted() {
    console.log(this.$store.getters);
    this.loadMap();
    this.getMarking();
  },
  created() {
    this.$store.dispatch('getStep', 6);
  },
  methods: {
    async getMarking() {
      await this.$store.dispatch('getMarking', this.idProject);
    },
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
        let titikPemantuanAdded = false;
        let titikPengelolaanAdded = false;
        let areaPemantuanAdded = false;
        let areaPengelolaanAdded = false;
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
          } else if (projects[i].attachment_type === 'pengelolaan' && !titikPengelolaanAdded) {
            layerTitle = 'Layer Batas Titik Pengelolaan';
            layerOutlineColor = 'purple';
            titikPengelolaanAdded = true;
          } else if (projects[i].attachment_type === 'pemantauan' && !titikPemantuanAdded) {
            layerTitle = 'Layer Batas Titik Pemantuan';
            layerOutlineColor = 'yellow';
            titikPemantuanAdded = true;
          } else if (projects[i].attachment_type === 'area-pengelolaan' && !areaPengelolaanAdded) {
            layerTitle = 'Layer Batas Area Pengelolaan';
            layerOutlineColor = 'black';
            areaPengelolaanAdded = true;
          } else if (projects[i].attachment_type === 'area-pemantauan' && !areaPemantuanAdded) {
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

                map.addMany(geojsonLayerArray);
                mapView.on('layerview-create', async(event) => {
                  await mapView.goTo({
                    target: geojsonLayerArray.fullExtent,
                  });
                });
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
        mapView.ui.add(legendExpand, 'bottom-left');
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
.map-container {
  position: relative;
  height: 500px;
}
#mapView {
  width: 100%;
  height: 100%;
  padding: 0;
  margin: 0 10px;
  position: absolute;
}
</style>
