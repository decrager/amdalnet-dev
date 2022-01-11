<template>
  <el-form label-position="top" label-width="100px">
    <!-- ekologis -->
    <el-form-item label="Peta Batas Ekologis" :required="required" style="display: none;">
      <el-col :span="11" style="margin-right:1em;">

        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi SHP
            <div v-if="petaEkologisSHP != ''" class="current">tersimpan: <span style="color: green" @click="download(idPES)"><strong>{{ petaEkologisSHP }}<i class="el-icon-circle-check" /></strong></span>
              <!-- &nbsp;<i class="el-icon-delete"></i>-->
            </div>
          </legend>
          <form @submit.prevent="handleSubmit">
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
          <form @submit.prevent="handleSubmit">
            <input ref="pePDF" type="file" class="form-control-file" accept="application/pdf" @change="onChangeFiles(2)">
            <!-- <button type="submit">Unggah</button> -->
          </form>
        </fieldset>
      </el-col>
    </el-form-item>

    <el-form-item label="Peta Batas Sosial" :required="required" style="display:none;">
      <el-col :span="11" style="margin-right:1em;">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi SHP
            <div v-if="petaSosialSHP != ''" class="current">tersimpan: <span style="color: green" @click="download(idPSS)"><strong>{{ petaSosialSHP }}<i class="el-icon-circle-check" /></strong></span></div>
          </legend>

          <form @submit.prevent="handleSubmit">
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

          <form @submit.prevent="handleSubmit">
            <input ref="psPDF" type="file" class="form-control-file" accept="application/pdf" @change="onChangeFiles(4)">
            <!-- <button type="submit">Unggah</button> -->
          </form>
        </fieldset>
      </el-col>
    </el-form-item>

    <el-form-item label="Peta Batas Wilayah Studi" :required="required" style="display:none;">
      <el-col :span="11" style="margin-right:1em;">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi SHP
            <div v-if="petaStudiSHP != ''" class="current">tersimpan: <span style="color: green" @click="download(idPSuS)"><strong>{{ petaStudiSHP }}<i class="el-icon-circle-check" /></strong></span></div>
          </legend>

          <form @submit.prevent="handleSubmit">
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

          <form @submit.prevent="handleSubmit">
            <input ref="pwPDF" type="file" class="form-control-file" accept="application/pdf" @change="onChangeFiles(6)">
            <!-- <button type="submit">Unggah</button> -->
          </form>
        </fieldset>
      </el-col>

    </el-form-item>
    <div id="mapView" class="map-wrapper" />

    <el-row style="text-align:right; display:none;">
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

const uploadMaps = new Resource('project-map');
// const unggahMaps = new Resource('upload-map');
// const unduhMaps = new Resource('download-map');

export default {
  name: 'UploadPetaBatas',
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
      // isVisible: false,
      // visible: [false, false, false, false, false, false, false],
    };
  },
  mounted() {
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
              basemap: 'satellite',
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
      // console.log(files);
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
      console.log('submitting files....');

      const formData = new FormData();
      formData.append('id_project', this.idProject);

      this.files.forEach((e, i) => {
        formData.append('files[]', e[0]);
        formData.append('params[]', JSON.stringify(this.param[i]));
      });

      /* Object.entries(this.param).forEach(([key, value]) => {
        formData.append(key, value);
      }); */
      console.log(this.files);

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
      console.log('index' + index);
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
      console.log('handling on change', this.param);
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
        shp(base).then(function(data) {
          console.log('map: ' + data);

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
                color: 'red',
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
          });

          map.add(layerEkologis);
          mapView.on('layerview-create', (event) => {
            mapView.goTo({
              target: layerEkologis.fullExtent,
            });
          });
        });
      };
      readerEkologis.readAsArrayBuffer(mapBatasEkologis);

      //  Map Batas Sosial
      const mapBatasSosial = this.$refs.psSHP.files[0];
      const readerSosial = new FileReader();
      readerSosial.onload = (event) => {
        const base = event.target.result;
        shp(base).then(function(data) {
          console.log('map: ' + data);

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
                color: 'blue',
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
          });

          map.add(layerBatasSosial);
          mapView.on('layerview-create', (event) => {
            mapView.goTo({
              target: layerBatasSosial.fullExtent,
            });
          });
        });
      };
      readerSosial.readAsArrayBuffer(mapBatasSosial);

      //  Map Batas Wilayah Studi
      const mapBatasWilayahStudi = this.$refs.pwSHP.files[0];
      const readerWilayahStudi = new FileReader();
      readerWilayahStudi.onload = (event) => {
        const base = event.target.result;
        shp(base).then(function(data) {
          console.log('map: ' + data);

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
                color: 'green',
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
          });

          map.add(layerWilayahStudi);
          mapView.on('layerview-create', (event) => {
            mapView.goTo({
              target: layerWilayahStudi.fullExtent,
            });
          });
        });
      };
      readerWilayahStudi.readAsArrayBuffer(mapBatasWilayahStudi);

      // Map Tapak
      const projId = this.$route.params && this.$route.params.id;
      console.log(projId);
      axios.get('api/map/' + projId)
        .then(response => {
          const projects = response.data;
          for (let i = 0; i < projects.length; i++) {
            if (projects[i].stored_filename) {
              shp(window.location.origin + '/storage/map/' + projects[i].stored_filename).then(data => {
                const blob = new Blob([JSON.stringify(data)], {
                  type: 'application/json',
                });
                const url = URL.createObjectURL(blob);
                axios.get('api/projects/' + projId).then((response) => {
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
                  mapView.on('layerview-create', (event) => {
                    mapView.goTo({
                      target: geojsonLayerArray.fullExtent,
                    });
                  });
                  map.add(geojsonLayerArray);
                });
              });
            }
          }
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
    showMap(id){
      this.visible[id] = true;
      this.isVisible = true;
      console.log('showing Map...', this.visible);
      document.getElementById('map' + id).style.display = 'block';
    },
  },
};
</script>
