<template>
  <div>
    <div class="detailPengumuman" style="margin-top:3.8rem;">
      <div class="wrapInDetail">
        <el-row :gutter="20">
          <el-col :span="24">
            <h1 style="color:#fff; margin-bottom:0">Informasi Rencana Kegiatan</h1>
          </el-col>
          <el-col :span="12">
            <table class="tableDetail tableDetail1" cellspacing="0" cellpadding="0" style="border:none">
              <tr>
                <td style="width:40%">No. Registrasi</td>
                <td v-html="selectedAnnouncement.project ? selectedAnnouncement.project.registration_no : ''" />
              </tr>
              <tr>
                <td style="width:40%">Jenis Dokumen</td>
                <td v-html="selectedAnnouncement.project ? selectedAnnouncement.project.required_doc : ''" />
              </tr>
              <tr>
                <td style="width:40%">Nama Kegiatan</td>
                <td v-html="selectedAnnouncement.project ? selectedAnnouncement.project.project_title : ''" />
              </tr>
              <tr>
                <td style="width:40%">Bidang Usaha/Kegiatan</td>
                <td v-html="selectedAnnouncement.project ? sector_name : ''" />
              </tr>
              <tr>
                <td style="width:40%">Skala/Besaran</td>
                <td v-html="selectedAnnouncement.project ? selectedAnnouncement.project.scale +' '+ selectedAnnouncement.project.scale_unit : ''" />
              </tr>
              <tr>
                <td style="width:40%">Alamat</td>
                <td v-html="selectedAnnouncement.project.address ? selectedAnnouncement.project.address[0].address : ''" />
              </tr>
              <tr>
                <td style="width:40%">Kewenangan</td>
                <td v-html="selectedAnnouncement.project && selectedAnnouncement.project.authority ? selectedAnnouncement.project.authority : 'Pusat'" />
              </tr>
              <tr>
                <td style="width:40%">Pemrakarsa</td>
                <td v-html="selectedAnnouncement.initiator ? selectedAnnouncement.initiator.name : ''" />
              </tr>
              <tr>
                <td style="width:40%">Penanggung Jawab</td>
                <td v-html="selectedAnnouncement.initiator ? selectedAnnouncement.initiator.pic : ''" />
              </tr>
              <tr>
                <td style="width:40%">Alamat Pemrakarsa</td>
                <td v-html="selectedAnnouncement.initiator ? selectedAnnouncement.initiator.address : ''" />
              </tr>
              <tr>
                <td style="width:40%">No Telepon Pemrakarsa</td>
                <td v-html="selectedAnnouncement.initiator ? selectedAnnouncement.initiator.phone : ''" />
              </tr>
              <tr>
                <td style="width:40%">Email Pemrakarsa</td>
                <td v-html="selectedAnnouncement.initiator ? selectedAnnouncement.initiator.email : ''" />
              </tr>
              <tr>
                <td style="width:40%">Provinsi/Kota</td>
                <td v-html="selectedAnnouncement.project ? selectedAnnouncement.project.address[0].prov + '/' + selectedAnnouncement.project.address[0].district : ''" />
              </tr>
              <tr>
                <td colspan="2">Deskripsi Kegiatan</td>
              </tr>
              <tr>
                <td colspan="2" v-html="selectedAnnouncement.project ? selectedAnnouncement.project.description : ''" />
              </tr>
            </table>
          </el-col>
          <el-col :span="12">
            <table class="tableDetail" cellspacing="0" cellpadding="0" style="border:none; width: 100%">
              <tr>
                <td colspan="2" class="bg-white-custom">Lokasi</td>
              </tr>
              <tr class="bg-white-custom">
                <td colspan="2">
                  <div class="detailLokasi">
                    <div id="mapView" style="width: 100%" />
                  </div>
                </td>
              </tr>
              <tr class="bg-blue-custom">
                <td colspan="2">Deskripsi Lokasi</td>
              </tr>
              <tr class="bg-white-custom">
                <td colspan="2" v-html="selectedAnnouncement.project ? selectedAnnouncement.project.location_desc : ''" />
              </tr>
            </table>
          </el-col>
          <el-col :span="24">
            <table class="tableDetail" cellspacing="0" cellpadding="0" style="border:none; margin-top:1rem;">
              <tr class="bg-blue-custom">
                <td colspan="2">Dampak Potensial</td>
              </tr>
              <tr class="bg-white-custom">
                <td colspan="2" v-html="selectedAnnouncement.potential_impact" />
              </tr>
            </table>
          </el-col>
        </el-row>
      </div>
    </div>
    <div class="detailPengumuman">
      <div class="wrapInDetail wrapInDetailBottom">
        <el-form
          ref="form"
          :model="form"
          :rules="rules"
          enctype="multipart/form-data"
          @submit.prevent="saveFeedback"
        >
          <input v-model="selectedAnnouncement.id" type="hidden">
          <el-row :gutter="20">
            <el-col :span="12">
              <h3 style="text-align:center; color:#fff;">Saran, Pendapat, dan Tanggapan untuk Kegiatan</h3>
              <el-row :gutter="20">
                <el-col :span="12">
                  <el-form-item prop="name">
                    <div class="text-white fw-bold">Nama</div>
                    <el-input v-model="form.name" placeholder="Nama" />
                  </el-form-item>
                </el-col>
                <el-col :span="12">
                  <el-form-item prop="peran">
                    <div class="text-white fw-bold">Peran</div>
                    <el-form-item label="">
                      <el-select v-model="form.peran" placeholder="Pilih Peran" @change="handleChangeModal">
                        <el-option
                          v-for="item in responders"
                          :key="item.id"
                          :label="item.name"
                          :value="item.id"
                        />
                      </el-select>
                    </el-form-item>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-row :gutter="20">
                <el-col :span="12">
                  <el-form-item prop="id_card_number">
                    <div class="text-white fw-bold">NIK</div>
                    <el-input v-model.number="form.id_card_number" placeholder="NIK" />
                  </el-form-item>
                </el-col>
                <el-col :span="12">
                  <el-form-item
                    prop="phone"
                  >
                    <div class="text-white fw-bold">No. Telepon/Handphone</div>
                    <el-input
                      v-model.number="form.phone"
                      placeholder="No. Telepon/Handphone"
                    >
                      <template slot="prepend">+62</template>
                    </el-input>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-row :gutter="20">
                <el-col :span="12">
                  <el-form-item prop="email" label="">
                    <div class="text-white fw-bold">Email</div>
                    <el-input
                      v-model="form.email"
                      placeholder="Email"
                    />
                  </el-form-item>
                </el-col>
                <el-col :span="12">
                  <el-form-item prop="comunityGender">
                    <div class="text-white fw-bold">Gender</div>
                    <el-form-item label="">
                      <el-select v-model="form.comunityGender" placeholder="Pilih Gender">
                        <el-option
                          v-for="item in genders"
                          :key="item.id"
                          :label="item.name"
                          :value="item.id"
                        />
                      </el-select>
                    </el-form-item>
                  </el-form-item>
                </el-col>
              </el-row>
            </el-col>
            <el-col :span="12">
              <el-form-item prop="file">
                <div style="margin-top:2rem; display:block;">
                  <div class="text-white fw-bold" style="text-align:center">Unggah Foto Selfie<small style="color: red; display: block; margin-top: -1.5em;">(Ukuran Maks 1 MB)</small></div>
                  <div style="width:200px; height:200px; background:#d0d0d0 none repeat scroll 0% 0%; display:block; margin:auto; line-height: 307px;text-align: center;">
                    <img v-if="url" :src="url" style="width:96%;height: 96%;object-fit: cover;">
                  </div>
                  <div style="text-align:center;">
                    <input
                      ref="file"
                      style="margin-left:6rem"
                      type="file"
                      class=""
                      @change="handleFileUpload()"
                    >
                    <p v-if="errorSelfie" style="color: red;">{{ errorSelfie }}</p>
                  </div>
                </div>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="20">
            <el-col :span="12">
              <el-row>
                <el-col :span="24">
                  <el-form-item>
                    <div class="text-white fw-bold">Kondisi Lingkungan di Dalam dan Sekitar Lokasi Tapak Proyek</div>
                    <el-input
                      v-model="form.envyCondition"
                      type="textarea"
                      placeholder="Kondisi Lingkungan di Dalam dan Sekitar Lokasi Tapak Proyek"
                    />
                  </el-form-item>
                  <el-form-item>
                    <div class="text-white fw-bold">Nilai Lokal  yang Berpotensi akan Terkena Dampak</div>
                    <el-input
                      v-model="form.localImpact"
                      type="textarea"
                      placeholder="Nilai Lokal  yang Berpotensi akan Terkena Dampak"
                    />
                  </el-form-item>
                  <!-- <h3 class="fw-bold text-white">Rating</h3> -->

                  <div class="text-white fw-bold" style="margin-top:2em; margin-bottom: 1em;">
                    Tingkat Kekhawatiran atau Harapan terhadap Kegiatan Ini
                    <!-- Tingkat kesetujuan Anda terhadap Kegiatan/Proyek Ini -->
                  </div>
                  <div style="display:flex">
                    <div class="rating">
                      <span>Khawatir </span>
                      <el-rate v-model="ratings" @change="handleChange(ratings)" />
                      <span> Harapan</span>
                    </div>
                  </div>
                </el-col>
              </el-row>
            </el-col>
            <el-col :span="12">
              <el-form-item>
                <div class="text-white fw-bold">Kekhawatiran</div>
                <el-input
                  v-model="form.concern"
                  type="textarea"
                  placeholder="Kekhawatiran"
                />
              </el-form-item>
              <el-form-item>
                <div class="text-white fw-bold">Harapan</div>
                <el-input
                  v-model="form.expectation"
                  type="textarea"
                  placeholder="Harapan"
                />
              </el-form-item>
            </el-col>
          </el-row>
          <el-dialog
            title="Masyarakat Terkena Dampak Langsung"
            :visible.sync="centerDialogVisible"
            width="30%"
            center
          >
            <el-checkbox-group v-model="form.comunityType">
              <el-checkbox label="Kelompok Masyarakat Rentan" />
              <el-checkbox label="Kelompok Masyarakat Adat" />
              <el-checkbox label="Kelompok Ke setaraan Gender" />
            </el-checkbox-group>
            <template>
              <el-alert
                v-show="warningDialog"
                title="Silahkan Di Isi Salah Satu"
                type="warning"
                show-icon
              />

            </template>
            <span slot="footer" class="dialog-footer">
              <el-button type="primary" @click="handleCloseModal()">Simpan</el-button>
            </span>
          </el-dialog>
        </el-form>
        <el-row :gutter="20">
          <el-col :span="18">
            <div class="detailFoot">
              <el-button
                id="batal"
                type="danger"
                @click="handleCancelComponent()"
              >Batal</el-button>
              <el-button
                id="kirim"
                :loading="loadingSend"
                type="primary"
                @click="handleSubmit()"
              >Kirim</el-button>
            </div>
          </el-col>
        </el-row>
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios';
import Resource from '@/api/resource';
import _ from 'lodash';
import Map from '@arcgis/core/Map';
import MapView from '@arcgis/core/views/MapView';
// import Attribution from '@arcgis/core/widgets/Attribution';
// import Expand from '@arcgis/core/widgets/Expand';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
// import shp from 'shpjs';
import urlBlob from '@/views/webgis/scripts/urlBlob';
import popupTemplate from '@/views/webgis/scripts/popupTemplate';
import LayerList from '@arcgis/core/widgets/LayerList';
import Legend from '@arcgis/core/widgets/Legend';
import Expand from '@arcgis/core/widgets/Expand';
const kbliResource = new Resource('business');
// import { LMap, LTileLayer, LMarker } from 'vue2-leaflet';

export default {
  name: 'Details',
  props: {
    selectedAnnouncement: {
      type: Object,
      default: () => null,
    },
    selectedProject: {
      type: Object,
      default: () => {},
    },
    announcementId: {
      type: Number,
      default: 0,
    },
  },
  data() {
    return {
      data: {},
      form: {
        name: null,
        id_card_number: null,
        phone: null,
        email: null,
        responder_type_id: null,
        concern: null,
        expectation: null,
        announcement_id: 0,
        rating: null,
        peran: '',
        envyCondition: null,
        localImpact: null,
        comunityType: [],
        comunityGender: null,
      },
      rules: {
        name: [{ required: true, message: 'Nama wajib diisi', trigger: 'blur' }],
        peran: [{ required: true, message: 'Peran wajib diisi', trigger: 'blur' }],
        comunityGender: [{ required: true, message: 'Gender wajib dipilih', trigger: 'blur' }],
        id_card_number: [{ required: true, message: 'NIK wajib diisi' }, { pattern: /([1-9][0-9])(\d{4})(\d{6})(\d{4})/, message: 'NIK tidak valid', trigger: ['blur', 'change'] }],
        email: [
          { required: true, message: 'Alamat email wajib diisi', trigger: 'blur' },
          { type: 'email', message: 'Format alamat email tidak benar', trigger: ['blur', 'change'] }],
        phone: [{ required: true, message: 'Nomor Telepon wajib diisi' }, { pattern: /\D*([2-9]\d{2})(\D*)([2-9]\d{2})(\D*)(\d{2})\D*/, message: 'Nomor Telepon tidak valid', trigger: ['blur', 'change'] }],
      },
      responders: [],
      errorMessage: null,
      photo_filepath: null,
      ratings: null,
      url: '/images/avatar.png',
      zoom: 12,
      center: [-6.93, 107.60],
      bounds: null,
      basePath: window.location.origin,
      centerDialogVisible: false,
      warningDialog: false,
      mapGeojsonArrayProject: [],
      checkList: [],
      radio: '',
      selectedProject2: {},
      sector_name: '',
      errorSelfie: null,
      loadingSend: false,
      genders: [
        {
          id: '1',
          name: 'Laki-laki',
        },
        {
          id: '2',
          name: 'Perempuan',
        },
      ],
    };
  },
  async created() {
    await this.getResponderType();
    await this.loadBusiness();
  },
  mounted() {
    this.loadMap();
  },
  methods: {
    async loadBusiness(){
      if (isNaN(this.selectedAnnouncement.project.sector)) {
        console.log(this.selectedAnnouncement.project.sector);
        const business = await kbliResource.list({
          data: this.selectedAnnouncement.project.sector,
        });
        this.sector_name = business.data[0].value;
      } else {
        const business = await kbliResource.list({
          id: this.selectedAnnouncement.project.sector,
        });
        this.sector_name = business.data[0].value;
      }
    },
    loadMap() {
      // console.log(this.selectedAnnouncement.project_id);
      const map = new Map({
        basemap: 'satellite',
      });

      // const mapView = new MapView({
      //   container: 'mapView',
      //   map: map,
      //   center: [115.287, -1.588],
      //   zoom: 5,
      // });
      // this.$parent.mapView = mapView;

      // axios.get('api/map/' + this.selectedAnnouncement.project_id)
      //   .then(response => {
      //     const projects = response.data;
      //     for (let i = 0; i < projects.length; i++) {
      //       if (projects[i].attachment_type === 'tapak') {
      //         shp(window.location.origin + '/storage/map/' + projects[i].stored_filename).then(data => {
      //           const blob = new Blob([JSON.stringify(data)], {
      //             type: 'application/json',
      //           });
      //           const url = URL.createObjectURL(blob);

      //           const renderer = {
      //             type: 'simple',
      //             field: '*',
      //             symbol: {
      //               type: 'simple-fill',
      //               color: [0, 0, 0, 0.0],
      //               outline: {
      //                 color: 'red',
      //                 width: 2,
      //               },
      //             },
      //           };

      //           const geojsonLayer = new GeoJSONLayer({
      //             url: url,
      //             outFields: ['*'],
      //             title: 'Peta Tapak',
      //             renderer: renderer,
      //           });
      //           map.add(geojsonLayer);
      //           mapView.on('layerview-create', (event) => {
      //             mapView.goTo({
      //               target: geojsonLayer.fullExtent,
      //             });
      //           });
      //         });
      //       }
      //     }
      //   });

      axios.get(`api/map-geojson?id=${this.selectedAnnouncement.project_id}`)
        .then((response) => {
          response.data.forEach((item) => {
            const getType = JSON.parse(item.feature_layer);
            const propType = getType.features[0].properties.type;
            const propFields = getType.features[0].properties.field;
            const propStyles = getType.features[0].properties.styles;
            const step = getType.features[0].properties.step;

            // Tapak
            if (propType === 'tapak' && step === 'ka') {
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
            map.addMany(this.mapGeojsonArrayProject);
          });
          this.petaLoading = false;
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
    },
    formatDateStr(date) {
      const today = new Date(date);
      var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

      // Getting required values
      const year = today.getFullYear();
      const month = today.getMonth();
      const day = today.getDate();

      const monthID = bulan[month];

      // Creating a new Date (with the delta)
      const finalDate = `${day} ${monthID} ${year}`;

      return finalDate;
    },
    handleChange(i){
      this.form.rating = i;
    },
    getAnnouncement() {
      axios.get('/api/announcements').then((response) => {
        return response.data.data;
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
    handleFileUpload(e) {
      this.errorSelfie = null;
      this.url = '/images/avatar.png';
      if (this.$refs.file.files[0].size > 1048576) {
        this.errorSelfie = 'Ukuran File Tidak Boleh Melebihi 1 MB';
        this.photo_filepath = null;
        return;
      }
      if (!this.isFileImage(this.$refs.file.files[0])) {
        this.errorSelfie = 'File yang diunggah Wajib Berformat Gambar';
        this.photo_filepath = null;
        return;
      }
      this.photo_filepath = this.$refs.file.files[0];
      this.url = URL.createObjectURL(this.$refs.file.files[0]);
    },
    handleSubmit() {
      this.$refs['form'].validate((valid) => {
        if (valid && (this.errorSelfie === null)) {
          this.saveFeedback();
        } else {
          this.$message({
            type: 'info',
            message: 'Masukan dalam Formulir Tanggapan tidak lengkap',
            duration: 5 * 1000,
          });
          return false;
        }
      });
    },
    async saveFeedback() {
      if (this.$refs.form) {
        // console.log('saveFeedback');
        if (this.form.peran === 1 && this.form.comunityType.length === 0) {
          this.centerDialogVisible = true;
        } else {
          this.loadingSend = true;
          this.centerDialogVisible = false;
          const formData = new FormData();
          formData.append('photo_filepath', this.photo_filepath);
          formData.append('name', this.form.name);
          formData.append('id_card_number', this.form.id_card_number);
          formData.append('phone', this.form.phone);
          formData.append('email', this.form.email);
          formData.append('responder_type_id', this.form.peran);
          formData.append('concern', this.form.concern);
          formData.append('expectation', this.form.expectation);
          formData.append('rating', this.form.rating);
          formData.append('announcement_id', this.selectedAnnouncement.id);
          formData.append('environment_condition', this.form.envyCondition);
          formData.append('local_impact', this.form.localImpact);
          formData.append('community_type', this.form.comunityType);
          formData.append('community_gender', this.form.comunityGender);

          _.each(this.formData, (value, key) => {
            formData.append(key, value);
          });

          const headers = { 'Content-Type': 'multipart/form-data' };
          await axios
            .post('api/feedbacks', formData, { headers })
            .then((data) => {
              this.$message({
                type: 'success',
                message: 'Successfully create a feedback',
                duration: 5 * 1000,
              });
              this.loadingSend = false;
              this.$emit('handleSetTabs', 'TABS');
              this.getAnnouncement();
            })
            .catch((error) => {
              this.loadingSend = false;
              this.errorMessage = error.message;
              this.$message({
                type: 'error',
                message: error.message,
                duration: 5 * 1000,
              });
            });
        }
      }
    },
    async getResponderType() {
      await axios.get('api/responder-types').then((response) => {
        this.responders = response.data.data;
      });
    },
    handleCancelComponent() {
      this.$emit('handleCancelComponent', 'TABS');
    },
    handleChangeModal(){
      if (this.form.peran === 1){
        this.centerDialogVisible = true;
      }
    },
    handleCloseModal(){
      if (this.form.comunityType.length === 0 && this.form.comunityGender === null) {
        this.warningDialog = true;
        this.centerDialogVisible = true;
      } else {
        this.warningDialog = false;
        this.centerDialogVisible = false;
      }
    },
    isFileImage(file) {
      return file && file['type'].split('/')[0] === 'image';
    },
  },
};
</script>
<style scoped>
.titleDetail h1 {
  font-size: 19px;
  margin-top: 15px !important;
}
.detailPengumuman {
  background: #062307;
  padding: 1.5rem;
  border-radius: 10px;
  margin-top: 2rem;
}
h1 {
  color: #fff !important;
  font-weight: bold !important;
  margin-bottom: 1.5rem !important;
  margin-top: 0 !important;
}
table.table__striped {
  border-collapse: collapse;
  width: 100%;
}

table.table__striped th,
table.table__striped td {
  text-align: left;
  padding: 8px;
  font-size: 0.8rem !important;
  font-weight: bold !important;
}

table.table__striped tr:nth-child(even) {
  background-color: #aec8af;
  color: #383732;
}
table.table__striped tr:nth-child(odd) {
  background-color: #fff;
  color: #383732;
}
.detailFoot {
  display: flex;
  justify-content: flex-end;
  margin-top: 2rem;
}
#batal {
  background-color: #da5b4a !important;
}
#kirim {
  background-color: #1bbf66 !important;
}
.text-white {
  color: #fff;
}
/* .el-form-item {
  margin-bottom: 0;
} */
.fw-bold {
  font-weight: bold;
}
.el-select {
  width: 100%;
}
.rating {
  display: flex;
}
.rating p {
  font-size: 0.8rem;
}
.wrapDetail {
  display: flex;
  padding: 2rem 1.5rem;
  border-radius: 10px;
  align-items: center;
}
.wrapInDetail {
  background-color: #062307;
  padding-bottom: 2rem;
}
.wrapDetailRight {
  padding-left: 1rem;
  margin-top: -2rem;
}
.fw-bold {
  font-weight: bold;
}
.wrapDetailRightRow {
  margin-top: 3rem;
  text-align: right;
  padding-right: 2rem;
}
.el-button--success.is-plain {
  color: #fff;
  background: #13ce66;
  border-color: #13ce66;
  font-weight: bold;
  border-radius: 1rem;
  margin-bottom: 1rem;
}
.el-button--warning {
  float: right;
}
.maps {
  width: 100%;
  height: 300px;
  background-color: red;
}
.wrapInDetailBottom {
  padding:2rem;
}
.fz8{font-size: 0.8rem;}
#header {
    z-index: 99999;
}
#mapView {
  width: 100%;
  height: 23.7rem;
  max-height: 100vh;
  /* padding: 0;
  margin: 0 10px;
  position: absolute; */
}
.tableDetail1 tr:nth-child(even) {background: #aec7af}
.tableDetail1 tr:nth-child(odd) {background: #FFF}
.bg-blue-custom {background: #aec7af}
.bg-white-custom {background: #FFF}
.tableDetail td{color: #3c3f3c; padding:0.5rem; width: 50%; border:0}
.detailLokasi{background-color: #fff;padding: 0.5rem;}
.mapsDetail{width: 100%; height: 20rem;}
.el-rate__item i{font-size: 3rem;}
</style>
