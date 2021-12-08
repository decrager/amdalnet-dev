<template>
  <div class="app-container">
    <el-card>
      <WorkFlow />
      <h3 align="center">Surat Keputusan Kelayakan Lingkungan</h3>
      <el-row :gutter="32">
        <el-col :sm="24" :md="12">
          <h4>Informasi Rencana Usaha/Kegiatan</h4>
          <Information />
        </el-col>
        <el-col :sm="24" :md="12">
          <h4>Dokumen Persetujuan Lingkungan</h4>
          <DokumenPersetujuan />
        </el-col>
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
import DokumenPersetujuan from '@/views/skkl/components/DokumenPersetujuan';
import WorkFlow from '@/components/Workflow';

import axios from 'axios';

// Map-related
import shp from 'shpjs';
import Map from '@arcgis/core/Map';
import MapView from '@arcgis/core/views/MapView';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
import Legend from '@arcgis/core/widgets/Legend';
import LayerList from '@arcgis/core/widgets/LayerList';

export default {
  name: 'SKKL',
  components: {
    Information,
    DokumenPersetujuan,
    WorkFlow,
  },
  data() {
    return {
      idProject: this.$route.params.id,
      readonly: true,
    };
  },
  mounted() {
    this.loadMap();
  },
  created() {
    this.$store.dispatch('getStep', 7);
  },
  methods: {
    async loadMap() {
      axios.get('api/map/' + this.idProject)
        .then((response) => {
          const map = new Map({
            basemap: 'topo',
          });

          const projects = response.data;
          for (let i = 0; i < projects.length; i++) {
            // Map Ekologi
            if (projects[i].attachment_type === 'ecology') {
              shp(window.location.origin + '/storage/map/' + projects[i].stored_filename).then(data => {
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
              shp(window.location.origin + '/storage/map/' + projects[i].stored_filename).then(data => {
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
              shp(window.location.origin + '/storage/map/' + projects[i].stored_filename).then(data => {
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
              shp(window.location.origin + '/storage/map/' + projects[i].stored_filename).then(data => {
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
                  map.add(geojsonLayerArray);

                  mapView.on('layerview-create', (event) => {
                    mapView.goTo({
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

          mapView.ui.add(layerList, 'top-right');
          mapView.ui.add(legend, 'bottom-left');
        });
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
