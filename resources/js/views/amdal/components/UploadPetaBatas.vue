<template>
  <div>
    <el-form label-position="top" label-width="100px">
      <div style="margin-bottom: 10px;">
        <a href="/sample_map/Peta_Batas_Sample.zip" class="download__sample" target="_blank" rel="noopener noreferrer"><i class="ri-road-map-line" /> Download Contoh Shp</a>
        <a href="/amdalnet-juknis-penyiapan-peta.pdf" class="download__juknis" title="Download Juknis Peta" target="_blank" rel="noopener noreferrer"><i class="ri-file-line" /> Download Juknis Peta</a>

      </div>

      <!-- ekologis -->
      <el-form-item label="Peta Batas Ekologis" :required="required">
        <el-col :span="11" style="margin-right:1em;">

          <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
            <legend style="margin:0 2em;">Versi SHP
              <div v-if="petaEkologisSHP != ''" class="current">tersimpan: <span style="color: green" @click="download(idPES)"><strong>{{ petaEkologisSHP }}<i class="el-icon-circle-check" /></strong></span>
                <!-- &nbsp;<i class="el-icon-delete"></i>-->
              </div>
            </legend>
            <form v-if="isFormulator" @submit.prevent="handleSubmit">
              <input ref="peSHP" type="file" class="form-control-file" @change="onChangeFiles(1)">
              <!-- <button type="submit">Unggah</button> -->
            </form>
          </fieldset>

        </el-col>
        <el-col :span="11" style="margin-right:1em;">
          <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
            <legend style="margin:0 2em;">Versi PDF
              <div v-if="petaEkologisPDF != ''" class="current">tersimpan: <span style="color: green" @click="download(idPEP)"><strong>{{ petaEkologisPDF }}<i class="el-icon-circle-check" /></strong></span></div>
            </legend>
            <form v-if="isFormulator" @submit.prevent="handleSubmit">
              <input ref="pePDF" type="file" class="form-control-file" accept="application/pdf" @change="onChangeFiles(2)">
              <!-- <button type="submit">Unggah</button> -->
            </form>
          </fieldset>
        </el-col>
      </el-form-item>

      <el-form-item label="Peta Batas Sosial" :required="required">
        <el-col :span="11" style="margin-right:1em;">
          <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
            <legend style="margin:0 2em;">Versi SHP
              <div v-if="petaSosialSHP != ''" class="current">tersimpan: <span style="color: green" @click="download(idPSS)"><strong>{{ petaSosialSHP }}<i class="el-icon-circle-check" /></strong></span></div>
            </legend>

            <form v-if="isFormulator" @submit.prevent="handleSubmit">
              <input ref="psSHP" type="file" class="form-control-file" @change="onChangeFiles(3)">
              <!-- <button type="submit">Unggah</button> -->
            </form>
          </fieldset>

        </el-col>

        <el-col :span="11" style="margin-right:1em;">
          <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
            <legend style="margin:0 2em;">Versi PDF
              <div v-if="petaSosialPDF != ''" class="current">tersimpan: <span style="color: green" @click="download(idPSP)"><strong>{{ petaSosialPDF }}<i class="el-icon-circle-check" /></strong></span></div>
            </legend>

            <form v-if="isFormulator" @submit.prevent="handleSubmit">
              <input ref="psPDF" type="file" class="form-control-file" accept="application/pdf" @change="onChangeFiles(4)">
              <!-- <button type="submit">Unggah</button> -->
            </form>
          </fieldset>
        </el-col>
      </el-form-item>

      <el-form-item label="Peta Batas Wilayah Studi" :required="required">
        <el-col :span="11" style="margin-right:1em;">
          <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
            <legend style="margin:0 2em;">Versi SHP
              <div v-if="petaStudiSHP != ''" class="current">tersimpan: <span style="color: green" @click="download(idPSuS)"><strong>{{ petaStudiSHP }}<i class="el-icon-circle-check" /></strong></span></div>
            </legend>

            <form v-if="isFormulator" @submit.prevent="handleSubmit">
              <input ref="pwSHP" type="file" class="form-control-file" @change="onChangeFiles(5)">
              <!-- <button type="submit">Unggah</button> -->
            </form>
          </fieldset>
        </el-col>

        <el-col :span="11" style="margin-right:1em;">
          <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
            <legend style="margin:0 2em;">Versi PDF
              <div v-if="petaStudiPDF != ''" class="current">tersimpan: <span style="color: green" @click="download(idPSuP)"><strong>{{ petaStudiPDF }}<i class="el-icon-circle-check" /></strong></span></div>
            </legend>

            <form v-if="isFormulator" @submit.prevent="handleSubmit">
              <input ref="pwPDF" type="file" class="form-control-file" accept="application/pdf" @change="onChangeFiles(6)">
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

    <el-row :gutter="32">
      <el-col :sm="24" :md="24">
        <Comment
          commenttype="peta-batas-ka"
          :kolom="kolom"
        />
      </el-col>
    </el-row>
  </div>
</template>
<style scoped>
 legend {line-height: 1.5em; margin: .5em 0 2em;}
 div.map-wrapper { height: 400px;}
 fieldset {
   padding:1em;
 }
</style>
<script>
import Comment from '@/views/amdal/components/Comment.vue';
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
import urlBlob from '../../webgis/scripts/urlBlob';
import popupTemplate from '../../webgis/scripts/popupTemplate';
import popupTemplateBatas from '../../webgis/scripts/popupTemplateBatas';
import Expand from '@arcgis/core/widgets/Expand';
const uploadMaps = new Resource('project-map');
// const unggahMaps = new Resource('upload-map');
// const unduhMaps = new Resource('download-map');
import generateArcgisToken from '../../webgis/scripts/arcgisGenerateToken';
import addItem from '../../webgis/scripts/addItem';

export default {
  name: 'UploadPetaBatas',
  components: {
    Comment,
  },
  data() {
    return {
      data: [],
      idProject: 0,
      currentMaps: [],
      petaEkologisPDF: '',
      petaSosialPDF: '',
      petaStudiPDF: '',
      petaEkologisSHP: '',
      petaSosialSHP: '',
      petaStudiSHP: '',
      files: [],
      idPES: 0,
      idPEP: 0,
      idPSP: 0,
      idPSS: 0,
      idPSuP: 0,
      idPSuS: 0,
      index: 0,
      param: [],
      required: true,
      fileMap: [],
      mapShpFile: [],
      projectTitle: '',
      token: '',
      geomEcologyGeojson: {},
      geomSocialGeojson: {},
      geomStudyGeojson: {},
      geomEcologyProperties: {},
      geomSocialProperties: {},
      geomStudyProperties: {},
      geomEcologyStyles: null,
      geomSocialStyles: null,
      geomStudyStyles: null,
      mapItemId: null,
      mapGeojsonArrayProject: [],
      kolom: [
        {
          label: 'Peta Batas Ekologis',
          value: 'Peta Batas Ekologis',
        },
        {
          label: 'Peta Batas Sosial',
          value: 'Peta Batas Sosial',
        },
        {
          label: 'Peta Batas Wilayah Studi',
          value: 'Peta Batas Wilayah Studi',
        },
      ],
      // isVisible: false,
      // visible: [false, false, false, false, false, false, false],
    };
  },
  computed: {
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
  },
  async mounted() {
    generateArcgisToken(this.token);

    this.idProject = parseInt(this.$route.params && this.$route.params.id);

    axios.get(`api/projects/${this.idProject}`)
      .then((response) => {
        console.log(response);
        this.projectTitle = response.data.project_title;
      });

    this.getData();
    this.getMap();
  },
  methods: {
    getMap() {
      const map = new Map({
        basemap: 'satellite',
      });

      const mapView = new MapView({
        container: 'mapView',
        map: map,
        center: [115.287, -1.588],
        zoom: 5,
      });
      this.$parent.mapView = mapView;

      axios.get(`api/map-geojson?id=${this.idProject}`)
        .then((response) => {
          response.data.forEach((item) => {
            const getType = JSON.parse(item.feature_layer);
            const propType = getType.features[0].properties.type;
            const propFields = getType.features[0].properties.field;
            const propStyles = getType.features[0].properties.styles;

            // Tapak
            if (propType === 'tapak') {
              const geojsonLayerArray = new GeoJSONLayer({
                url: urlBlob(item.feature_layer),
                outFields: ['*'],
                visible: true,
                title: 'Layer Tapak Proyek',
                renderer: propStyles,
                popupTemplate: popupTemplate(propFields),
              });

              mapView.on('layerview-create', async(event) => {
                await mapView.goTo({
                  target: geojsonLayerArray.fullExtent,
                });
              });

              this.mapGeojsonArrayProject.push(geojsonLayerArray);
            }

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
              mapView.on('layerview-create', async(event) => {
                await mapView.goTo({
                  target: geojsonLayerArray.fullExtent,
                });
              });
            }

            map.addMany(this.mapGeojsonArrayProject);
          });
        });

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
          case 'ecology':
            if (e.file_type === 'SHP') {
              this.petaEkologisSHP = e.original_filename;
              this.idPES = e.id;
            } else {
              this.petaEkologisPDF = e.original_filename;
              this.idPEP = e.id;
            }
            break;
          case 'social':
            if (e.file_type === 'SHP') {
              this.petaSosialSHP = e.original_filename;
              this.idPSS = e.id;
            } else {
              this.petaSosialPDF = e.original_filename;
              this.idPSP = e.id;
            }
            break;
          case 'study':
            if (e.file_type === 'SHP') {
              this.petaStudiSHP = e.original_filename;
              this.idPSuS = e.id;
            } else {
              this.petaStudiPDF = e.original_filename;
              this.idPSuP = e.id;
            }
            break;
        }
      });
    },
    handleSubmit(){
      const formData = new FormData();
      formData.append('id_project', this.idProject);

      this.files.forEach((e, i) => {
        formData.append('files[]', e[0]);
        formData.append('params[]', JSON.stringify(this.param[i]));
        formData.append('geomEcologyGeojson', JSON.stringify(this.geomEcologyGeojson));
        formData.append('geomSocialGeojson', JSON.stringify(this.geomSocialGeojson));
        formData.append('geomStudyGeojson', JSON.stringify(this.geomStudyGeojson));
        formData.append('geomEcologyProperties', JSON.stringify(this.geomEcologyProperties));
        formData.append('geomSocialProperties', JSON.stringify(this.geomSocialProperties));
        formData.append('geomStudyProperties', JSON.stringify(this.geomStudyProperties));
        formData.append('geomEcologyStyles', JSON.stringify(this.geomEcologyStyles));
        formData.append('geomSocialStyles', JSON.stringify(this.geomSocialStyles));
        formData.append('geomStudyStyles', JSON.stringify(this.geomStudyStyles));
      });

      const notify =
            this.$notify({
              type: 'success',
              title: 'Berhasil!',
              message: 'Berhasil Publish Peta!!',
              duration: 2000,
            });

      if (this.files[0]) {
        addItem(this.token, this.projectTitle, this.files[0][0], this.mapItemId, notify);
      }

      if (this.files[2]) {
        addItem(this.token, this.projectTitle, this.files[2][0], this.mapItemId, notify);
      }

      if (this.files[4]) {
        addItem(this.token, this.projectTitle, this.files[4][0], this.mapItemId, notify);
      }

      axios.post('api/upload-maps', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        }})
        .then((response) => {
          if (response) {
            this.$message({
              message: 'Berhasil menyimpan peta', //  + this.files[0].name,
              type: 'success',
            });
            this.$emit('handleReloadVsaList', 'metode-studi');
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
          this.files[index] = this.$refs.peSHP.files;
          this.param[index] = {
            attachment_type: 'ecology',
            file_type: 'SHP',
          };
          // this.param[index]['file_type'] = 'SHP';
          break;
        case 2:
          // ekologis PDF
          this.files[index] = this.$refs.pePDF.files;
          this.param[index] = {
            attachment_type: 'ecology',
            file_type: 'PDF',
          };
          // this.param[index]['file_type'] = 'PDF';
          // this.embedSrc = window.URL.createObjectURL(this.$refs.pePDF.files);
          break;
        case 3:
          this.files[index] = this.$refs.psSHP.files;
          this.param[index] = {
            attachment_type: 'social',
            file_type: 'SHP',
          };
          // sosial SHP
          break;
        case 4:
          this.files[index] = this.$refs.psPDF.files;
          this.param[index] = {
            attachment_type: 'social',
            file_type: 'PDF',
          };

          // sosial PDF
          break;
        case 5:
          this.files[index] = this.$refs.pwSHP.files;
          this.param[index] = {
            attachment_type: 'study',
            file_type: 'SHP',
          };
          // studi SHP
          break;
        case 6:
          this.files[index] = this.$refs.pwPDF.files;
          this.param[index] = {
            attachment_type: 'study',
            file_type: 'PDF',
          };
          // studi PDF
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
    uploadMap(){
      const map = new Map({
        basemap: 'satellite',
      });

      //  Map Ekologis
      const mapBatasEkologis = this.$refs.peSHP.files[0];
      const readerEkologis = new FileReader();
      readerEkologis.onload = (event) => {
        const base = event.target.result;

        shp(base).then((data) => {
          const valid = [
            'PEMRAKARSA',
            'KEGIATAN',
            'TAHUN',
            'PROVINSI',
            'KETERANGAN',
            'LAYER',
            'TIPE_DOKUM',
          ];

          const uploaded = Object.keys(data.features[0].properties);

          const checker = (arr, target) => target.every(v => arr.includes(v));
          const validDataSet = valid.map(element => element.toLowerCase());
          const uploadDataSet = uploaded.map(element => element.toLowerCase());
          const checkShapefile = checker(uploadDataSet, validDataSet);

          if (!checkShapefile) {
            return this.$alert('Atribut .shp yang dimasukkan tidak sesuai dengan format yang benar.', 'Format Salah', {
              confirmButtonText: 'Confirm',
              callback: action => {
                this.$notify({
                  type: 'warning',
                  title: 'Perhatian!',
                  message: 'Download Sample Peta Yang Telah Disediakan!!',
                  duration: 5000,
                });
              },
            });
          }

          this.geomEcologyGeojson = data.features[0].geometry;
          this.geomEcologyProperties = data.features[0].properties;

          var propFields = Object.entries(this.geomEcologyProperties).reduce(function(a, _a) {
            var key = _a[0], value = _a[1];
            a[key.toUpperCase()] = value;
            return a;
          }, {});
          this.geomEcologyStyles = 2;

          const blob = new Blob([JSON.stringify(data)], {
            type: 'application/json',
          });

          const renderer = {
            type: 'simple',
            field: '*',
            symbol: {
              type: 'simple-fill',
              color: [0, 0, 0, 0.0],
              outline: {
                color: [168, 112, 0, 1],
                width: 2,
              },
            },
          };
          const url = URL.createObjectURL(blob);
          const layerEkologis = new GeoJSONLayer({
            url: url,
            visible: true,
            outFields: ['*'],
            opacity: 0.75,
            title: 'Layer Batas Ekologis',
            renderer: renderer,
            popupTemplate: popupTemplateBatas(propFields),
          });

          map.add(layerEkologis);
        });
      };
      readerEkologis.readAsArrayBuffer(mapBatasEkologis);

      //  Map Batas Sosial
      const mapBatasSosial = this.$refs.psSHP.files[0];
      const readerSosial = new FileReader();
      readerSosial.onload = (event) => {
        const base = event.target.result;
        shp(base).then((data) => {
          const valid = [
            'PEMRAKARSA',
            'KEGIATAN',
            'TAHUN',
            'PROVINSI',
            'KETERANGAN',
            'LAYER',
            'TIPE_DOKUM',
          ];

          const uploaded = Object.keys(data.features[0].properties);
          const checker = (arr, target) => target.every(v => arr.includes(v));
          const validDataSet = valid.map(element => element.toLowerCase());
          const uploadDataSet = uploaded.map(element => element.toLowerCase());
          const checkShapefile = checker(uploadDataSet, validDataSet);

          if (!checkShapefile) {
            return this.$alert('Atribut .shp yang dimasukkan tidak sesuai dengan format yang benar.', 'Format Salah', {
              confirmButtonText: 'Confirm',
              callback: action => {
                this.$notify({
                  type: 'warning',
                  title: 'Perhatian!',
                  message: 'Download Sample Peta Yang Telah Disediakan!!',
                  duration: 5000,
                });
              },
            });
          }

          this.geomSocialGeojson = data.features[0].geometry;
          this.geomSocialProperties = data.features[0].properties;

          var propFields = Object.entries(this.geomSocialProperties).reduce(function(a, _a) {
            var key = _a[0], value = _a[1];
            a[key.toUpperCase()] = value;
            return a;
          }, {});

          this.geomSocialStyles = 3;

          const blob = new Blob([JSON.stringify(data)], {
            type: 'application/json',
          });

          const renderer = {

            type: 'simple',
            field: '*',
            symbol: {
              type: 'simple-fill',
              color: [0, 0, 0, 0.0],
              outline: {
                color: [0, 76, 115, 1],
                width: 2,
              },
            },
          };
          const url = URL.createObjectURL(blob);
          const layerBatasSosial = new GeoJSONLayer({
            url: url,
            visible: true,
            outFields: ['*'],
            opacity: 0.75,
            title: 'Layer Batas Sosial',
            renderer: renderer,
            popupTemplate: popupTemplateBatas(propFields),
          });

          map.add(layerBatasSosial);
        });
      };
      readerSosial.readAsArrayBuffer(mapBatasSosial);

      //  Map Batas Wilayah Studi
      const mapBatasWilayahStudi = this.$refs.pwSHP.files[0];
      const readerWilayahStudi = new FileReader();
      readerWilayahStudi.onload = (event) => {
        const base = event.target.result;
        shp(base).then((data) => {
          const valid = [
            'PEMRAKARSA',
            'KEGIATAN',
            'TAHUN',
            'PROVINSI',
            'KETERANGAN',
            'LAYER',
            'TIPE_DOKUM',
          ];

          const uploaded = Object.keys(data.features[0].properties);
          const checker = (arr, target) => target.every(v => arr.includes(v));
          const validDataSet = valid.map(element => element.toLowerCase());
          const uploadDataSet = uploaded.map(element => element.toLowerCase());
          const checkShapefile = checker(uploadDataSet, validDataSet);

          if (!checkShapefile) {
            return this.$alert('Atribut .shp yang dimasukkan tidak sesuai dengan format yang benar.', 'Format Salah', {
              confirmButtonText: 'Confirm',
              callback: action => {
                this.$notify({
                  type: 'warning',
                  title: 'Perhatian!',
                  message: 'Download Sample Peta Yang Telah Disediakan!!',
                  duration: 5000,
                });
              },
            });
          }

          this.geomStudyGeojson = data.features[0].geometry;
          this.geomStudyProperties = data.features[0].properties;

          var propFields = Object.entries(this.geomStudyProperties).reduce(function(a, _a) {
            var key = _a[0], value = _a[1];
            a[key.toUpperCase()] = value;
            return a;
          }, {});

          this.geomStudyStyles = 4;

          const blob = new Blob([JSON.stringify(data)], {
            type: 'application/json',
          });

          const renderer = {
            type: 'simple',
            field: '*',
            symbol: {
              type: 'simple-fill',
              color: [0, 0, 0, 0.0],
              outline: {
                color: [255, 0, 255, 1],
                width: 2,
              },
            },
          };
          const url = URL.createObjectURL(blob);
          const layerWilayahStudi = new GeoJSONLayer({
            url: url,
            visible: true,
            outFields: ['*'],
            opacity: 0.75,
            title: 'Layer Batas Wilayah Studi',
            renderer: renderer,
            popupTemplate: popupTemplateBatas(propFields),
          });

          map.add(layerWilayahStudi);
          mapView.on('layerview-create', async(event) => {
            await mapView.goTo({
              target: layerWilayahStudi.fullExtent,
            });
          });
        });
      };
      readerWilayahStudi.readAsArrayBuffer(mapBatasWilayahStudi);

      // Map Tapak
      const projId = this.$route.params && this.$route.params.id;
      axios.get(`api/map-geojson?id=${projId}&type=tapak`)
        .then((response) => {
          response.data.forEach((item) => {
            const getType = JSON.parse(item.feature_layer);
            const propFields = getType.features[0].properties.field;

            const blob = new Blob([item.feature_layer], {
              type: 'application/json',
            });
            const rendererTapak = {
              type: 'simple',
              field: '*',
              symbol: {
                type: 'simple-fill',
                color: [200, 0, 0, 1],
                outline: {
                  color: [200, 0, 0, 1],
                  width: 2,
                },
              },
            };
            const url = URL.createObjectURL(blob);
            const geojsonLayerArray = new GeoJSONLayer({
              url: url,
              outFields: ['*'],
              visible: true,
              title: 'Layer Tapak',
              renderer: rendererTapak,
              popupTemplate: popupTemplate(propFields),
            });
            map.add(geojsonLayerArray);
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
      mapView.ui.add(legend, 'top-right');
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

.download__juknis {
  color: white;
  padding: 7px;
  background-color: #39773B;
  border-radius: 4px;
  font-weight: 500;
  font-size: 13px;
}

.download__juknis:hover {
  background-color: #124e14;
  color: white;
}
</style>
