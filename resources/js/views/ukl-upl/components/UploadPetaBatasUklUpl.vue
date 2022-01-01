<template>
  <el-form label-position="top" label-width="100px">
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

    <el-form-item label="Peta Titik Penataan" :required="required">
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
const uploadMaps = new Resource('project-map');
// const unggahMaps = new Resource('upload-map');
// const unduhMaps = new Resource('download-map');

export default {
  name: 'UploadPetaBatasUklUpl',
  data() {
    return {
      data: [],
      idProject: 0,
      currentMaps: [],
      petaPengelolaanPDF: '',
      petaPemantauanSHP: '',
      petaPemantauanPDF: '',
      petaPengelolaanSHP: '',
      files: [],
      idPengelolaanSHP: 0,
      idPengelolaanPDF: 0,
      idPemantauanPDF: 0,
      idPemantauanSHP: 0,
      index: 0,
      param: [],
      required: true,
      fileMap: [],
      mapShpFile: [],
      projectTitle: '',
      token: '',
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
      'username': 'haris3',
      'password': 'amdal123',
      'client': 'requestip',
      'expiration': 20160,
      'f': 'json',
    });

    var config = {
      method: 'post',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Keep-Alive': 'timeout=60',
        'X-Content-Type-Options': 'nosniff',
        'Connection': 'keep-alive',
        'Content-Encoding': 'gzip',
        'Strict-Transport-Security': 'max-age=31536000',
      },
      body: data,
    };

    esriRequest('https://amdalgis.menlhk.go.id/portal/sharing/rest/generateToken', config)
      .then(response => response.json())
      .then(data => {
        this.token = data.token;
      });

    this.idProject = parseInt(this.$route.params && this.$route.params.id);
    this.getData();
    this.getMap();
  },
  methods: {
    getMap() {
      axios.get('api/map/' + this.idProject)
        .then((response) => {
          if (response.data.length > 1) {
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
                          color: [168, 112, 0, 1],
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
                          color: [0, 76, 115, 1],
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
                          color: [255, 0, 255, 1],
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

              // Map Pengelolaan
              if (projects[i].attachment_type === 'pengelolaan') {
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
                        type: 'picture-marker', // autocasts as new SimpleMarkerSymbol()
                        url: '/titik_kelola.png',
                        width: '24px',
                        height: '24px',
                      },
                    };
                    const geojsonLayerArray = new GeoJSONLayer({
                      url: url,
                      outFields: ['*'],
                      visible: true,
                      title: 'Layer Titik Pengelolaan',
                      renderer: rendererTapak,
                    });
                    map.add(geojsonLayerArray);
                  });
                });
              }

              // Map Pemantauan
              if (projects[i].attachment_type === 'pemantauan') {
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
                        type: 'picture-marker', // autocasts as new SimpleMarkerSymbol()
                        url: '/titik_pantau.png',
                        width: '24px',
                        height: '24px',
                      },
                    };
                    const geojsonLayerArray = new GeoJSONLayer({
                      url: url,
                      outFields: ['*'],
                      visible: true,
                      title: 'Layer Titik Pengelolaan',
                      renderer: rendererTapak,
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
                  var rendererTapakType = null;
                  axios.get('api/projects/' + this.idProject).then((response) => {
                    if (data.features[0].geometry.type === 'Polygon') {
                      rendererTapakType = {
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
                    } else if (data.features[0].geometry.type === 'Point') {
                      rendererTapakType = {
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
                    }
                    const geojsonLayerArray = new GeoJSONLayer({
                      url: url,
                      outFields: ['*'],
                      visible: true,
                      title: 'Layer Tapak',
                      renderer: rendererTapakType,
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
          }
        });
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
    handleSubmit(){
      const formData = new FormData();
      formData.append('id_project', this.idProject);

      urlUtils.addProxyRule({
        proxyUrl: 'proxy/proxy.php',
        urlPrefix: 'https://amdalgis.menlhk.go.id/',
      });

      this.files.forEach((e, i) => {
        formData.append('files[]', e[0]);
        formData.append('params[]', JSON.stringify(this.param[i]));

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
    uploadMap(){
      const map = new Map({
        basemap: 'topo',
      });

      //  Map Pengelolaan
      const mapPengelolaan = this.$refs.refPengelolaanSHP.files[0];
      const readerPengelolaan = new FileReader();
      readerPengelolaan.onload = (event) => {
        const base = event.target.result;
        shp(base).then(function(data) {
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
          });

          map.add(layerPengelolaan);
          mapView.on('layerview-create', (event) => {
            mapView.goTo({
              target: layerPengelolaan.fullExtent,
            });
          });
        });
      };
      readerPengelolaan.readAsArrayBuffer(mapPengelolaan);

      //  Map Pemantauan
      const mapPemantauan = this.$refs.refPemantauanSHP.files[0];
      const readerPemantauan = new FileReader();
      readerPemantauan.onload = (event) => {
        const base = event.target.result;
        shp(base).then(function(data) {
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
            title: 'Layer Batas Sosial',
            renderer: renderer,
          });

          map.add(layerPemantauan);
          mapView.on('layerview-create', (event) => {
            mapView.goTo({
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

      mapView.ui.add(layerList, 'top-right');
      mapView.ui.add(legend, 'bottom-left');
    },
    showMap(id){
      this.visible[id] = true;
      this.isVisible = true;
      document.getElementById('map' + id).style.display = 'block';
    },
  },
};
</script>
