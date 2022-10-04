<template>
  <div style="padding: 2em;">
    <p class="header">Unggah Tapak Proyek</p>
    <classic-upload :name="fileMapName" :fid="'fileMap'" @handleFileUpload="handleFileTapakProyekMapUpload" />
    <p class="header">Tapak Proyek</p>
    <project-map v-if="data !== null" :id="data.id" />
    <p class="header">Alamat Kegiatan Utama</p>
    <p class="data">{{ data.project_address }}</p>

    <p class="header">Lokasi Kegiatan</p>
    <template v-for="addr in data.address">
      <p :key="'ph_add_' + addr.id" class="data">{{ addr.address || '-' }}, {{ addr.district || '-' }}, {{ addr.prov || '-' }} </p>
    </template>

    <p class="header">Deskripsi Lokasi</p>
    <Tinymce
      v-model="data.location_desc"
      output-format="html"
      :menubar="''"
      :image="false"
      :toolbar="[
        'bold italic underline bullist numlist  preview undo redo fullscreen',
      ]"
      :height="150"
    />

    <p class="header">Surat Kesesuaian Tata Ruang</p>
    <p class="data"><el-link :href="data.ktr" target="_blank" icon="el-icon-download" type="primary">{{ file_ktr }}</el-link></p>

    <div style="text-align: right;">
      <el-button :loading="loading" type="primary" @click="handleSave">Simpan</el-button>
    </div>

  </div>
</template>
<script>
import Tinymce from '@/components/Tinymce';
import ClassicUpload from '@/components/ClassicUpload';
import ProjectMap from './sections/Map.vue';
import Resource from '@/api/resource';
import esriConfig from '@arcgis/core/config.js';
import MapImageLayer from '@arcgis/core/layers/MapImageLayer';
import GroupLayer from '@arcgis/core/layers/GroupLayer';
import Map from '@arcgis/core/Map';
import shp from 'shpjs';
import centroid from '@turf/centroid';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
import popupTemplate from '../../../webgis/scripts/popupTemplate';
import MapView from '@arcgis/core/views/MapView';
import Legend from '@arcgis/core/widgets/Legend';
import Expand from '@arcgis/core/widgets/Expand';
import LayerList from '@arcgis/core/widgets/LayerList';
import Print from '@arcgis/core/widgets/Print';

const projectResource = new Resource('projects');

export default {
  name: 'ProjectLocation',
  components: {
    ClassicUpload,
    ProjectMap,
    Tinymce,
  },
  props: {
    data: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      file_ktr: '',
      isMapUploaded: false,
      loading: false,
      fileMap: null,
      fileMapName: 'No File Selected',
      map: null,
      currentTapakProyekLayer: null,
      geomFromGeojson: null,
      geomProperties: null,
    };
  },
  mounted(){
    const splice = (this.data.ktr).split('/');
    this.file_ktr = splice[splice.length - 1];
  },
  created(){
    console.log({ dataProp: this.data });
  },
  methods: {
    handleFileTapakProyekMapUpload(e){
      this.map = new Map({
        basemap: 'satellite',
      });

      if (!e.target){
        return;
      }

      if (e.target.files[0].size > 10485760){
        this.showFileAlert();
        return;
      }

      if (e.target.files[0].type !== 'application/x-zip-compressed'){
        this.$alert('File yang diterima hanya .zip', 'Format Salah');
        return;
      }

      if (e !== 'a'){
        this.fileMap = e.target.files[0];
        this.fileMapName = e.target.files[0].name;
      }

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

      this.map.add(sigapLayer);

      const fr = new FileReader();
      fr.onload = (event) => {
        const base = event.target.result;
        shp(base).then((datas) => {
          this.geomFromGeojson = datas.features[0].geometry;
          this.geomProperties = datas.features[0].properties;
          const mapSampleProperties = [
            'PEMRAKARSA',
            'KEGIATAN',
            'TAHUN',
            'PROVINSI',
            'KETERANGAN',
            'LAYER',
          ];

          const mapUploadProperties = Object.keys(datas.features[0].properties);
          var getPropFields = datas.features[0].properties;

          var propFields = Object.entries(getPropFields).reduce(function(a, _a) {
            var key = _a[0], value = _a[1];
            a[key.toUpperCase()] = value;
            return a;
          }, {});

          var centroids = centroid(datas.features[0]);
          var getCoordinates = centroids.geometry.coordinates;

          fetch(`https://nominatim.openstreetmap.org/reverse?lat=${getCoordinates[1]}&lon=${getCoordinates[0]}&format=json`)
            .then(response => response.json())
            .then(data => {
              this.full_address = data.display_name;
            });

          if (datas.features[0].geometry.type !== 'Polygon') {
            document.getElementById('fileMap').value = '';
            this.fileMapName = 'No File Selected';
            return this.$alert('SHP yang dimasukkan harus Polygon!', 'Format Salah', {
              confirmButtonText: 'Confirm',
            });
          }

          const checker = (arr, target) => target.every(v => arr.includes(v));
          const validDataSet = mapSampleProperties.map(element => element.toLowerCase());
          const uploadDataSet = mapUploadProperties.map(element => element.toLowerCase());
          const checkShapefile = checker(uploadDataSet, validDataSet);

          if (!checkShapefile) {
            document.getElementById('fileMap').value = '';
            this.fileMapName = 'No File Selected';
            return this.$alert('Atribut .shp yang dimasukkan tidak sesuai dengan format yang benar.', 'Format Salah', {
              confirmButtonText: 'Confirm',
              callback: action => {
                this.$notify({
                  type: 'warning',
                  title: 'Perhatian!',
                  message: 'Download Sample Peta Yang Telah Disediakan!!',
                  duration: 5000,
                });
                window.open('sample_map/Peta_Tapak_Sample_Amdalnet.zip', '_blank');
              },
            });
          }

          const blob = new Blob([JSON.stringify(datas)], {
            type: 'application/json',
          });

          const renderer = {
            type: 'simple',
            field: '*',
            symbol: {
              type: 'simple-fill',
              color: [200, 0, 0, 1],
              outline: {
                color: [200, 0, 0, 1],
              },
            },
          };
          const url = URL.createObjectURL(blob);

          const geojsonLayer = new GeoJSONLayer({
            url: url,
            visible: true,
            outFields: ['*'],
            opacity: 0.75,
            title: 'Peta Tapak Proyek',
            renderer: renderer,
            popupTemplate: popupTemplate(propFields),
          });

          this.map.add(geojsonLayer);
          this.currentTapakProyekLayer = geojsonLayer;
          mapView.on('layerview-create', async(event) => {
            await mapView.goTo({
              target: geojsonLayer.fullExtent,
            });
          });
        });
      };
      fr.readAsArrayBuffer(this.fileMap);

      const mapView = new MapView({
        container: 'mapView',
        map: this.map,
        center: [115.287, -1.588],
        zoom: 5,
      });

      const legend = new Legend({
        view: mapView,
        container: document.createElement('div'),
      });

      const legendListExpand = new Expand({
        expandIconClass: 'esri-icon-basemap', // see https://developers.arcgis.com/javascript/latest/guide/esri-icon-font/
        expandTooltip: 'Layer Legend', // optional, defaults to "Expand" for English locale
        view: mapView,
        content: legend,
      });

      const layerList = new LayerList({
        view: mapView,
        container: document.createElement('div'),
        listItemCreatedFunction: this.defineActions,
      });

      const layerListExpand = new Expand({
        expandIconClass: 'esri-icon-layers', // see https://developers.arcgis.com/javascript/latest/guide/esri-icon-font/
        expandTooltip: 'Daftar Layer', // optional, defaults to "Expand" for English locale
        view: mapView,
        content: layerList,
      });

      const print = new Print({
        view: mapView,
        printServiceUrl:
                  'https://amdalgis.menlhk.go.id/server/rest/services/Utilities/PrintingTools/GPServer/Export%20Web%20Map%20Task',
      });

      const printExpand = new Expand({
        view: mapView,
        content: print,
      });

      layerList.on('trigger-action', (event) => {
        const id = event.action.id;
        if (id === 'full-extent') {
          mapView.goTo({
            target: event.item.layer.fullExtent,
          }).catch(function(error) {
            console.error(error);
          });
        }
      });

      mapView.ui.add(layerListExpand, 'top-right');
      mapView.ui.add(legendListExpand, 'top-right');
      mapView.ui.add(printExpand, 'top-left');
      this.isMapUploaded = true;
    },
    async handleSave() {
      this.loading = false;
      const formData = new FormData();
      formData.append('projectHome', true);
      formData.append('type', 'locationDesc');
      formData.append('locationDesc', this.data.location_desc);
      formData.append('fileMap', this.fileMap);
      formData.append('fileMapName', this.fileMapName);
      formData.append('geomFromGeojson', JSON.stringify(this.geomFromGeojson));
      formData.append('geomProperties', JSON.stringify(this.geomProperties));
      formData.append('geomStyles', JSON.stringify(1));

      await projectResource.updateMultipart(this.$route.params.id, formData);

      this.$message({
        message: 'Data berhasil disimpan',
        type: 'success',
        duration: 5 * 1000,
      });
      this.loading = false;
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
