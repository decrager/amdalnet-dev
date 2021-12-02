<template>
  <el-form label-position="top" label-width="100px">
    <!-- ekologis -->
    <el-form-item label="Peta Batas Ekologis" :required="required">
      <el-col :span="11" style="margin-right:1em;">

        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi SHP
            <div v-if="petaEkologisSHP != ''" class="current">tersimpan: <span @click="download(idPES)"><strong>{{ petaEkologisSHP }}</strong></span>
              <!-- &nbsp;<i class="el-icon-delete"></i>-->
            </div>
          </legend>
          <form @submit.prevent="handleSubmit">
            <input ref="peSHP" type="file" class="form-control-file" multiple accept="x-gis/x-shapefile" @change="onChangeFiles(1)">
            <button type="submit">Unggah</button>
          </form>
          <div id="map1" class="map-wrapper" />
        </fieldset>

      </el-col>
      <el-col :span="11" style="margin-right:1em;">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi PDF
            <div v-if="petaEkologisPDF != ''" class="current">tersimpan: <span @click="download(idPEP)"><strong>{{ petaEkologisPDF }}</strong></span></div>
          </legend>
          <form @submit.prevent="handleSubmit">
            <input ref="pePDF" type="file" class="form-control-file" multiple accept="application/pdf" @change="onChangeFiles(2)">
            <button type="submit">Unggah</button>
          </form>
        </fieldset>
      </el-col>
    </el-form-item>

    <el-form-item label="Peta Batas Sosial" :required="required">
      <el-col :span="11" style="margin-right:1em;">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi SHP
            <div v-if="petaSosialSHP != ''" class="current">tersimpan: <span @click="download(idPSS)"><strong>{{ petaSosialSHP }}</strong></span></div>
          </legend>

          <form @submit.prevent="handleSubmit">
            <input ref="psSHP" type="file" class="form-control-file" multiple accept="x-gis/x-shapefile" @change="onChangeFiles(3)">
            <button type="submit">Unggah</button>
          </form>
          <div id="map3" class="map-wrapper" />
        </fieldset>

      </el-col>

      <el-col :span="11" style="margin-right:1em;">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi PDF
            <div v-if="petaSosialPDF != ''" class="current">tersimpan: <span @click="download(idPSP)"><strong>{{ petaSosialPDF }}</strong></span></div>
          </legend>

          <form @submit.prevent="handleSubmit">
            <input ref="psPDF" type="file" class="form-control-file" multiple accept="application/pdf" @change="onChangeFiles(4)">
            <button type="submit">Unggah</button>
          </form>
        </fieldset>
      </el-col>
    </el-form-item>

    <el-form-item label="Peta Batas Wilayah Studi" :required="required">
      <el-col :span="10" style="margin-right:1em;">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi SHP
            <div v-if="petaStudiSHP != ''" class="current">tersimpan: <span @click="download(idPSuS)"><strong>{{ petaStudiSHP }}</strong></span></div>
          </legend>

          <form @submit.prevent="handleSubmit">
            <input ref="pwSHP" type="file" class="form-control-file" multiple accept="x-gis/x-shapefile" @change="onChangeFiles(5)">
            <button type="submit">Unggah</button>
          </form>
          <div id="map5" class="map-wrapper" />
        </fieldset>
      </el-col>

      <el-col :span="10" style="margin-right:1em;">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi PDF
            <div v-if="petaStudiPDF != ''" class="current">tersimpan: <span @click="download(idPSuP)"><strong>{{ petaStudiPDF }}</strong></span></div>
          </legend>

          <form @submit.prevent="handleSubmit">
            <input ref="pwPDF" type="file" class="form-control-file" multiple accept="application/pdf" @change="onChangeFiles(6)">
            <button type="submit">Unggah</button>
          </form>
        </fieldset>
      </el-col>

    </el-form-item>

    <!--
    <el-row style="margin: 1em 0;">
      <el-col :span="12">
        <el-button size="medium" type="warning">Unggah Peta</el-button>
      </el-col>
      <el-col :span="12" style="text-align: right;">
        <el-button size="medium" type="danger">Batal</el-button>
        <el-button size="medium" type="primary">Simpan</el-button>
      </el-col>
    </el-row> -->
  </el-form>

</template>
<style scoped>
@import url('../../../../../node_modules/leaflet/dist/leaflet.css');

 legend {line-height: 1.5em; margin: .5em 0 2em;}
 div.map-wrapper { height: 400px;}
 fieldset {
   padding:1em;
 }
</style>
<script>
import Resource from '@/api/resource';
import request from '@/utils/request';

// Map-related
import shp from 'shpjs';
import Map from '@arcgis/core/Map';
import MapView from '@arcgis/core/views/MapView';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';

const uploadMaps = new Resource('project-map');
const unggahMaps = new Resource('upload-map');
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
      files: '',
      idPES: 0,
      idPEP: 0,
      idPSP: 0,
      idPSS: 0,
      idPSuP: 0,
      idPSuS: 0,
      index: 0,
      param: [],

    };
  },
  mounted() {
    this.idProject = parseInt(this.$route.params && this.$route.params.id);
    this.getData();
  },
  methods: {
    async getData(){
      this.data = [];
      const files = await uploadMaps.list({
        id_project: this.idProject,
      });
      this.data = files.data;
      this.process(files.data);
    },
    process(files){
      console.log(files);
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
      console.log(this.files, this.param);

      const formData = new FormData();
      formData.append('id_project', this.idProject);
      formData.append('file', this.files[0]);
      Object.entries(this.param).forEach(([key, value]) => {
        formData.append(key, value);
      });

      unggahMaps.store(formData).then((res) => {
        console.log(res);
        this.getData();
        this.$message({
          message: 'Berhasil menyimpan file ' + this.files[0].name,
          type: 'success',
        });
      });
    },
    async doSubmit(load){
      console.log(load);
      return request(load);
    },
    download(id){
      return;
      /* const filename = this.data.find((e) => e.id === id);
      unduhMaps.get(id)
        .then((response) => {
          console.log(response);

          // const header = response.headers['content-disposition'].split('; ');
          // const filename = header[1].split('=');

          // console.log(header);

          const blob = new Blob([response], { type: 'application/octet-stream' });
          const url = window.URL.createObjectURL(blob);

          const link = document.createElement('a');
          link.href = url;
          link.download = filename.original_filename;
          link.click();

          const getHeaders = response.headers['content-disposition'].split('; ');
          const getFileName = getHeaders[1].split('=');
          const getName = getFileName[1].split('=');
          var fileURL = window.URL.createObjectURL(new Blob([response.data]));
          var fileLink = document.createElement('a');
          fileLink.href = fileURL;
          let newName = getName[0].slice(1);
          newName = newName.slice(0, -1);
          fileLink.setAttribute('download', `${newName}`);
          document.body.appendChild(fileLink);
          fileLink.click();
        });*/
    },
    onChangeFiles(index){
      this.files = '';
      this.index = index;
      this.param = [];
      switch (this.index) {
        case 1: // ekologis SHP
          this.files = this.$refs.peSHP.files;
          this.param['attachment_type'] = 'ecology';
          this.param['file_type'] = 'SHP';
          break;
        case 2:
          // ekologis PDF
          this.files = this.$refs.pePDF.files;
          this.param['attachment_type'] = 'ecology';
          this.param['file_type'] = 'PDF';
          break;
        case 3:
          this.files = this.$refs.psSHP.files;
          this.param['attachment_type'] = 'social';
          this.param['file_type'] = 'SHP';
          // sosial SHP
          break;
        case 4:
          this.files = this.$refs.psPDF.files;
          this.param['attachment_type'] = 'social';
          this.param['file_type'] = 'PDF';

          // sosial PDF
          break;
        case 5:
          this.files = this.$refs.pwSHP.files;
          this.param['attachment_type'] = 'study';
          this.param['file_type'] = 'SHP';
          // studi SHP
          break;
        case 6:
          this.files = this.$refs.pwPDF.files;
          this.param['attachment_type'] = 'study';
          this.param['file_type'] = 'PDF';
          // studi PDF
          break;
        default:
      }
      this.loadMap(index);
    },
    loadMap(id){
      const index = [1, 3, 5];
      if (index.indexOf(id) < 0) {
        return;
      }

      const map = new Map({
        basemap: 'topo',
      });

      const reader = new FileReader();
      reader.onload = (event) => {
        const base = event.target.result;
        shp(base).then(function(data) {
          const blob = new Blob([JSON.stringify(data)], {
            type: 'application/json',
          });

          const url = URL.createObjectURL(blob);
          const geojsonLayer = new GeoJSONLayer({
            url: url,
            visible: true,
            outFields: ['*'],
            opacity: 0.75,
          });

          map.add(geojsonLayer);
          mapView.on('layerview-create', (event) => {
            mapView.goTo({
              target: geojsonLayer.fullExtent,
            });
          });
        });

        // const map = L.map('map' + id);
        // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //   attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        // }).addTo(map);

      // const reader = new FileReader(); // instantiate a new file reader
      // reader.onload = (event) => {
      //   const base = event.target.result;
      //   shp(base).then(function(data) {
      //     const geo = L.geoJSON(data).addTo(map);
      //     console.log(geo);
      //     map.fitBounds(geo.getBounds());
      //   });
      // };
      };
      reader.readAsArrayBuffer(this.files[0]);

      const mapView = new MapView({
        container: 'map' + id,
        map: map,
        center: [115.287, -1.588],
        zoom: 4,
      });
      this.$parent.mapView = mapView;
    },
  },
};
</script>
