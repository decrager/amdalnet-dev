<template>
  <el-form label-position="top" label-width="100px">
    <div style="margin-bottom: 10px;">
      <a href="/sample_map/Sample_Peta_Kelola_Pantau.zip" class="download__sample" target="_blank" rel="noopener noreferrer"><i class="ri-road-map-line" /> Download Contoh Shp</a>
    </div>
    <!-- ekologis -->
    <el-form-item label="Peta Titik Pengelolaan" :required="required">
      <el-col :span="11" style="margin-right:1em;">

        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi SHP
            <div v-if="petaPengelolaanSHP != ''" class="current">tersimpan: <span style="color: green" @click="download(idPengelolaanSHP)"><strong>{{ petaPengelolaanSHP }}<i class="el-icon-circle-check" /></strong></span>
              <!-- &nbsp;<i class="el-icon-delete"></i>-->
            </div>
          </legend>
          <form v-if="isFormulator" @submit.prevent="handleSubmit">
            <input ref="refPengelolaanSHP" type="file" class="form-control-file" @change="onChangeFiles(1)">
            <!-- <button type="submit">Unggah</button> -->
          </form>
        </fieldset>

      </el-col>
      <el-col :span="11" style="margin-right:1em;">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi PDF
            <div v-if="petaPengelolaanPDF != ''" class="current">tersimpan: <span style="color: green" @click="download(idPengelolaanPDF)"><strong>{{ petaPengelolaanPDF }}<i class="el-icon-circle-check" /></strong></span></div>
          </legend>
          <form v-if="isFormulator" @submit.prevent="handleSubmit">
            <input ref="refPengelolaanPDF" type="file" class="form-control-file" accept="application/pdf" @change="onChangeFiles(2)">
            <!-- <button type="submit">Unggah</button> -->
          </form>
        </fieldset>
      </el-col>
    </el-form-item>

    <el-form-item label="Peta Titik Pemantauan" :required="required">
      <el-col :span="11" style="margin-right:1em;">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi SHP
            <div v-if="petaPemantauanSHP != ''" class="current">tersimpan: <span style="color: green" @click="download(idPemantauanSHP)"><strong>{{ petaPemantauanSHP }}<i class="el-icon-circle-check" /></strong></span></div>
          </legend>

          <form v-if="isFormulator" @submit.prevent="handleSubmit">
            <input ref="refPemantauanSHP" type="file" class="form-control-file" @change="onChangeFiles(3)">
            <!-- <button type="submit">Unggah</button> -->
          </form>
        </fieldset>

      </el-col>

      <el-col :span="11" style="margin-right:1em;">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi PDF
            <div v-if="petaPemantauanPDF != ''" class="current">tersimpan: <span style="color: green" @click="download(idPemantauanPDF)"><strong>{{ petaPemantauanPDF }}<i class="el-icon-circle-check" /></strong></span></div>
          </legend>

          <form v-if="isFormulator" @submit.prevent="handleSubmit">
            <input ref="refPemantauanPDF" type="file" class="form-control-file" accept="application/pdf" @change="onChangeFiles(4)">
            <!-- <button type="submit">Unggah</button> -->
          </form>
        </fieldset>
      </el-col>
    </el-form-item>

    <div id="mapView" class="map-wrapper" />

    <el-row v-if="isFormulator" style="text-align:right;">
      <el-button size="medium" type="primary" @click="handleSubmit">Unggah Peta</el-button>
    </el-row>

  </el-form>

</template>
<style scoped>
 legend {line-height: 1.5em; margin: .5em 0 2em;}
 div.map-wrapper { height: 400px;}
 fieldset {
   padding:1em;
 }
</style>
<script>
import Resource from '@/api/resource';
import request from '@/utils/request';
import axios from 'axios';

// Map-related
import shp from 'shpjs';
import Map from '@arcgis/core/Map';
import MapView from '@arcgis/core/views/MapView';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
import Legend from '@arcgis/core/widgets/Legend';
import LayerList from '@arcgis/core/widgets/LayerList';
import * as urlUtils from '@arcgis/core/core/urlUtils';
import qs from 'qs';
import esriRequest from '@arcgis/core/request';
import urlBlob from '../../webgis/scripts/urlBlob';
import popupTemplate from '../../webgis/scripts/popupTemplate';
import popupTemplateKelolaPantau from '../../webgis/scripts/popupTemplateKelolaPantau';
import Expand from '@arcgis/core/widgets/Expand';
const uploadMaps = new Resource('project-map');
// const unggahMaps = new Resource('upload-map');
// const unduhMaps = new Resource('download-map');

export default {
  name: 'UploadPetaBatasRklRpl',
  data() {
    return {
      data: [],
      idProject: 0,
      currentMaps: [],
      petaPengelolaanPDF: '',
      petaPemantauanSHP: '',
      petaPemantauanPDF: '',
      petaPengelolaanSHP: '',
      petaTapakPDF: '',
      petaTapakSHP: '',
      petaEkologisPDF: '',
      petaEkologisSHP: '',
      petaSosialPDF: '',
      petaSosialSHP: '',
      petaStudiPDF: '',
      petaStudiSHP: '',
      files: [],
      idPengelolaanSHP: 0,
      idPengelolaanPDF: 0,
      idPemantauanPDF: 0,
      idPemantauanSHP: 0,
      idTapakSHP: 0,
      idTapakPDF: 0,
      idEkologisSHP: 0,
      idEkologisPDF: 0,
      idSosialSHP: 0,
      idSosialPDF: 0,
      idStudiSHP: 0,
      idStudiPDF: 0,
      index: 0,
      param: [],
      required: true,
      fileMap: [],
      mapShpFile: [],
      projectTitle: '',
      token: '',
      geomKelolaGeojson: {},
      geomPantauGeojson: {},
      geomKelolaProperties: {},
      geomPantauProperties: {},
      geomKelolaStyles: null,
      geomPantauStyles: null,
      mapGeojsonArrayProject: [],
      // isVisible: false,
      // visible: [false, false, false, false, false, false, false],
    };
  },
  computed: {
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
  },
  mounted() {
    urlUtils.addProxyRule({
      proxyUrl: 'proxy/proxy.php',
      urlPrefix: 'https://amdalgis.menlhk.go.id/',
    });

    var data = qs.stringify({
      'username': 'Amdalnet',
      'password': 'Amdalnet123',
      'client': 'requestip',
      'expiration': 20160,
      'f': 'json',
    });

    var config = {
      method: 'post',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Connection': 'keep-alive',
        'Content-Encoding': 'gzip',
      },
      body: data,
      responseType: 'json',
    };

    esriRequest('https://amdalgis.menlhk.go.id/portal/sharing/rest/generateToken', config)
      .then(response => {
        this.token = response.data.token;
      });

    this.idProject = parseInt(this.$route.params && this.$route.params.id);
    this.getData();
    this.getMap();
  },
  methods: {
    getMap() {
      const map = new Map({
        basemap: 'satellite',
      });
      // Tapak
      axios.get('api/map/' + this.idProject)
        .then(response => {
          const projects = response.data;
          for (let i = 0; i < projects.length; i++) {
            if (projects[i].attachment_type === 'tapak') {
              shp(window.location.origin + '/storage/map/' + projects[i].stored_filename).then(data => {
                const blob = new Blob([JSON.stringify(data)], {
                  type: 'application/json',
                });
                const url = URL.createObjectURL(blob);

                const renderer = {
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

                const geojsonLayer = new GeoJSONLayer({
                  url: url,
                  outFields: ['*'],
                  title: 'Peta Tapak',
                  renderer: renderer,
                });
                map.add(geojsonLayer);
              });
            }
          }
        });

      axios.get(`api/map-geojson?id=${this.idProject}`)
        .then((response) => {
          response.data.forEach((item) => {
            const getType = JSON.parse(item.feature_layer);
            const propType = getType.features[0].properties.type;
            const propFields = getType.features[0].properties.field;
            const propStyles = getType.features[0].properties.styles;
            // Ecology
            if (propType === 'ecology') {
              const geojsonLayerArray = new GeoJSONLayer({
                url: urlBlob(item.feature_layer),
                outFields: ['*'],
                title: 'Layer Batas Ekologis',
                visible: true,
                renderer: propStyles,
                popupTemplate: popupTemplate(propFields),
              });

              this.mapGeojsonArrayProject.push(geojsonLayerArray);
            }

            // Social
            if (propType === 'social') {
              const geojsonLayerArray = new GeoJSONLayer({
                url: urlBlob(item.feature_layer),
                outFields: ['*'],
                visible: true,
                title: 'Layer Batas Sosial',
                renderer: propStyles,
                popupTemplate: popupTemplate(propFields),
              });

              this.mapGeojsonArrayProject.push(geojsonLayerArray);
            }

            // Study
            if (propType === 'study') {
              const geojsonLayerArray = new GeoJSONLayer({
                url: urlBlob(item.feature_layer),
                outFields: ['*'],
                visible: true,
                title: 'Layer Batas Studi',
                renderer: propStyles,
                popupTemplate: popupTemplate(propFields),
              });

              this.mapGeojsonArrayProject.push(geojsonLayerArray);
            }

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

              this.mapGeojsonArrayProject.push(geojsonLayerArray);
            }

            // Pengelolaan
            if (propType === 'pengelolaan') {
              const geojsonLayerArray = new GeoJSONLayer({
                url: urlBlob(item.feature_layer),
                outFields: ['*'],
                visible: true,
                title: 'Layer Titik Pengelolaan',
                renderer: propStyles,
                popupTemplate: popupTemplate(propFields),
              });

              this.mapGeojsonArrayProject.push(geojsonLayerArray);
            }
            map.addMany(this.mapGeojsonArrayProject);
          });
        });

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
    },
    async getData(){
      this.data = [];
      const files = await uploadMaps.list({
        id_project: this.idProject,
      });
      this.data = files.data;
      this.process(files.data);
    },
    process(files){
      files.forEach((e) => {
        switch (e.attachment_type){
          case 'tapak':
            if (e.file_type === 'SHP') {
              this.petaTapakSHP = e.original_filename;
              this.idTapakSHP = e.id;
            } else {
              this.petaTapakPDF = e.original_filename;
              this.idTapakPDF = e.id;
            }
            break;
          case 'ecology':
            if (e.file_type === 'SHP') {
              this.petaEkologisSHP = e.original_filename;
              this.idEkologisSHP = e.id;
            } else {
              this.petaEkologisPDF = e.original_filename;
              this.idEkologisPDF = e.id;
            }
            break;
          case 'social':
            if (e.file_type === 'SHP') {
              this.petaSosialSHP = e.original_filename;
              this.idSosialSHP = e.id;
            } else {
              this.petaSosialPDF = e.original_filename;
              this.idSosialPDF = e.id;
            }
            break;
          case 'study':
            if (e.file_type === 'SHP') {
              this.petaStudiSHP = e.original_filename;
              this.idStudiSHP = e.id;
            } else {
              this.petaStudiPDF = e.original_filename;
              this.idStudiPDF = e.id;
            }
            break;
          case 'pengelolaan':
            if (e.file_type === 'SHP') {
              this.petaPengelolaanSHP = e.original_filename;
              this.idPengelolaanSHP = e.id;
            } else {
              this.petaPengelolaanPDF = e.original_filename;
              this.idPengelolaanPDF = e.id;
            }
            break;
          case 'pemantauan':
            if (e.file_type === 'SHP') {
              this.petaPemantauanSHP = e.original_filename;
              this.idPemantauanSHP = e.id;
            } else {
              this.petaPemantauanPDF = e.original_filename;
              this.idPemantauanPDF = e.id;
            }
            break;
        }
      });
    },
    uploadMap(){
      const map = new Map({
        basemap: 'satellite',
      });

      //  Map Pengelolaan
      const mapPengelolaan = this.$refs.refPengelolaanSHP.files[0];
      const readerPengelolaan = new FileReader();
      readerPengelolaan.onload = (event) => {
        const base = event.target.result;
        shp(base).then((data) => {
          const valid = [
            'ID',
            'KETERANGAN',
            'PEMRAKARSA',
            'KEGIATAN',
            'TAHUN',
            'LAYER',
            'TIPE_DOKUM',
            'PROVINSI',
            'KODE',
          ];

          const uploaded = Object.keys(data.features[0].properties);
          const checker = (arr, target) => target.every(v => arr.includes(v));
          const checkShapefile = checker(uploaded, valid);

          if (!checkShapefile) {
            alert('Atribut .shp yang dimasukkan tidak sesuai dengan format yang benar. Download sample diatas!');
            return;
          }

          this.geomKelolaGeojson = data.features[0].geometry;
          this.geomKelolaProperties = data.features[0].properties;
          this.geomKelolaStyles = 6;

          const blob = new Blob([JSON.stringify(data)], {
            type: 'application/json',
          });

          const renderer = {
            type: 'simple',
            field: '*',
            symbol: {
              type: 'picture-marker', // autocasts as new SimpleMarkerSymbol()
              url: '/titik_kelola.png',
              width: '24px',
              height: '24px',
            },
          };
          const url = URL.createObjectURL(blob);
          const layerPengelolaan = new GeoJSONLayer({
            url: url,
            visible: true,
            outFields: ['*'],
            opacity: 0.75,
            title: 'Layer Titik Pengelolaan',
            renderer: renderer,
            popupTemplate: popupTemplateKelolaPantau(this.geomKelolaProperties),
          });

          map.add(layerPengelolaan);
        });
      };
      readerPengelolaan.readAsArrayBuffer(mapPengelolaan);

      //  Map Pemantauan
      const mapPemantauan = this.$refs.refPemantauanSHP.files[0];
      const readerPemantauan = new FileReader();
      readerPemantauan.onload = (event) => {
        const base = event.target.result;
        shp(base).then((data) => {
          const valid = [
            'ID',
            'KETERANGAN',
            'PEMRAKARSA',
            'KEGIATAN',
            'TAHUN',
            'LAYER',
            'TIPE_DOKUM',
            'PROVINSI',
            'KODE',
          ];

          const uploaded = Object.keys(data.features[0].properties);

          const checker = (arr, target) => target.every(v => arr.includes(v));
          const checkShapefile = checker(uploaded, valid);

          if (!checkShapefile) {
            alert('Atribut .shp yang dimasukkan tidak sesuai dengan format yang benar. Download sample diatas!');
            return;
          }

          this.geomPantauGeojson = data.features[0].geometry;
          this.geomPantauProperties = data.features[0].properties;
          this.geomPantauStyles = 5;

          const blob = new Blob([JSON.stringify(data)], {
            type: 'application/json',
          });

          const renderer = {
            type: 'simple',
            field: '*',
            symbol: {
              type: 'picture-marker', // autocasts as new SimpleMarkerSymbol()
              url: '/titik_pantau.png',
              width: '24px',
              height: '24px',
            },
          };
          const url = URL.createObjectURL(blob);
          const layerPemantauan = new GeoJSONLayer({
            url: url,
            visible: true,
            outFields: ['*'],
            opacity: 0.75,
            title: 'Layer Titik Pemantauan',
            renderer: renderer,
            popupTemplate: popupTemplateKelolaPantau(this.geomPantauProperties),
          });

          map.add(layerPemantauan);
          mapView.on('layerview-create', async(event) => {
            await mapView.goTo({
              target: layerPemantauan.fullExtent,
            });
          });
        });
      };
      readerPemantauan.readAsArrayBuffer(mapPemantauan);

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
    },
    handleSubmit(){
      const formData = new FormData();
      formData.append('id_project', this.idProject);
      formData.append('step', 'rkl-rpl');

      urlUtils.addProxyRule({
        proxyUrl: 'proxy/proxy.php',
        urlPrefix: 'https://amdalgis.menlhk.go.id/',
      });

      this.files.forEach((e, i) => {
        formData.append('files[]', e[0]);
        formData.append('params[]', JSON.stringify(this.param[i]));
        formData.append('geomKelolaGeojson', JSON.stringify(this.geomKelolaGeojson));
        formData.append('geomPantauGeojson', JSON.stringify(this.geomPantauGeojson));
        formData.append('geomKelolaProperties', JSON.stringify(this.geomKelolaProperties));
        formData.append('geomPantauProperties', JSON.stringify(this.geomPantauProperties));
        formData.append('geomKelolaStyles', JSON.stringify(this.geomKelolaStyles));
        formData.append('geomPantauStyles', JSON.stringify(this.geomPantauStyles));

        var projectTitle = '';

        axios.get(`api/projects/${this.idProject}`)
          .then(response => {
            projectTitle = response.data.project_title;
            console.log(response.data);
            console.log(response.data.project_title);
          });

        console.log(projectTitle);

        var myHeaders = new Headers();
        myHeaders.append('Authorization', 'Bearer ' + this.token);

        var formdatas = new FormData();
        formdatas.append('url', 'https://amdalgis.menlhk.go.id/server/rest/services/Hosted/Test/FeatureServer');
        formdatas.append('type', 'Feature Service');
        formdatas.append('title', projectTitle + this.idProject);
        formdatas.append('file', e[0]);

        var requestOptions = {
          method: 'POST',
          headers: myHeaders,
          body: formdatas,
          redirect: 'follow',
        };

        esriRequest('https://amdalgis.menlhk.go.id/portal/sharing/rest/content/users/Amdalnet/addItem', requestOptions)
          .then(response => response.text())
          .then(result => console.log(result))
          .catch(error => console.log('error', error));
      });

      axios.post('api/upload-maps', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        }})
        .then((response) => {
          if (response) {
            this.$message({
              message: 'Berhasil menyimpan file ', //  + this.files[0].name,
              type: 'success',
            });
            this.$emit('handlePetaBatasUploaded');
            this.$emit('handleEnableSimpanLanjutkan');
          }
        });
    },
    async doSubmit(load){
      return request(load);
    },
    download(id){
      return;
    },
    onChangeFiles(idx){
      const index = idx - 1;
      switch (idx) {
        case 1: // ekologis SHP
          this.files[index] = this.$refs.refPengelolaanSHP.files;
          this.param[index] = {
            attachment_type: 'pengelolaan',
            file_type: 'SHP',
          };

          // this.param[index]['file_type'] = 'SHP';
          break;
        case 2:
          // ekologis PDF
          this.files[index] = this.$refs.refPengelolaanPDF.files;
          this.param[index] = {
            attachment_type: 'pengelolaan',
            file_type: 'PDF',
          };
          // this.param[index]['file_type'] = 'PDF';
          // this.embedSrc = window.URL.createObjectURL(this.$refs.refPengelolaanPDF.files);
          break;
        case 3:
          this.files[index] = this.$refs.refPemantauanSHP.files;
          this.param[index] = {
            attachment_type: 'pemantauan',
            file_type: 'SHP',
          };
          // sosial SHP
          break;
        case 4:
          this.files[index] = this.$refs.refPemantauanPDF.files;
          this.param[index] = {
            attachment_type: 'pemantauan',
            file_type: 'PDF',
          };

          // sosial PDF
          break;
        default:
      }
      // this.showMap(idx);
      this.uploadMap();
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
    showMap(id){
      this.visible[id] = true;
      this.isVisible = true;
      document.getElementById('map' + id).style.display = 'block';
    },
  },
};
</script>

<style scoped>
.download__sample {
  color: white;
  padding: 7px;
  background-color: orange;
  border-radius: 4px;
  font-weight: 500;
  font-size: 13px;
}

.download__sample:hover {
  background-color: orangered;
  color: white;
}
</style>
