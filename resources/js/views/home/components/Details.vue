<template>
  <div v-if="showDetails">
    <div class="detailPengumuman">
      <div class="wrapInDetail">
        <el-row :gutter="20">
          <el-col :span="16">
            <div class="wrapDetail">
              <img src="https://placeimg.com/150/150/arch/grayscale" alt="">
              <div class="wrapDetailRight">
                <p style="margin-bottom: 1rem">{{ selectedAnnouncement.project.project_title }}</p>
                <h4 class="fw-bold">{{ selectedAnnouncement.pic_name }}</h4>
                <h4 class="fw-bold">{{ selectedAnnouncement.project.address }}, Jakarta Pusat</h4>
              </div>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="wrapDetailRightRow">
              <el-button type="success" plain>{{ selectedAnnouncement.project.required_doc }}</el-button>
              <h4 class="fw-bold">Reg No. {{ selectedAnnouncement.project.id_project }}</h4>
              <h4 class="fw-bold">[{{ selectedAnnouncement.project.project_type }}]</h4>
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="12">
            <div style="padding-left: 2rem">
              <h4 class="fw-bold">Pengumuman</h4>
              <p>{{ formatDateStr(selectedAnnouncement.start_date) }} - {{ formatDateStr(selectedAnnouncement.end_date) }}</p>
            </div>
          </el-col>
          <el-col :span="12">
            <div style="padding-right: 2rem">
              <el-button type="warning">Unduh bukti pengumuman</el-button>
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="24">
            <div style="padding-left: 2rem; margin-top: 2rem">
              <p style="margin-bottom: 1rem" v-html="selectedAnnouncement.project.description" />
              <h4 class="fw-bold">Dampak Potensi</h4>
              <p style="margin-bottom: 1.5rem" v-html="selectedAnnouncement.project.potential_impact" />
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="14">
            <div style="padding-left: 2rem">
              <div style="display: flex; margin-bottom: 1rem">
                <h4 class="fw-bold" style="">Sifat Kegiatan:</h4>
                <h4>&nbsp;{{ selectedAnnouncement.project.project_type }}</h4>
              </div>
              <h4 class="fw-bold">Project Location</h4>
              <p style="margin-bottom: 1.5rem">{{ selectedAnnouncement.project_location }}</p>
            </div>
          </el-col>
          <el-col :span="10">
            <div style="padding-right: 2rem">
              <div style="height: 400px; width: 100%">
                <l-map :zoom="zoom" :center="center">
                  <l-marker :lat-lng="center" />
                  <l-tile-layer :url="urlMap" :attribution="attribution" />
                </l-map>
              </div>
            </div>
          </el-col>
        </el-row>
      </div>
    </div>
    <div class="detailPengumuman">
      <div class="wrapInDetail wrapInDetailBottom">
        <el-row>
          <el-col :span="24">
            <h1 style="text-align:center">SPT (Saran, Pendapat & Tanggapan</h1>
          </el-col>
        </el-row>
        <el-form
          ref="form"
          enctype="multipart/form-data"
          @submit.prevent="saveFeedback"
        >
          <input v-model="announcementId" type="hidden">
          <el-row :gutter="20">
            <el-col :span="15">
              <el-row :gutter="20">
                <el-col :span="24">
                  <el-form-item>
                    <div class="text-white fw-bold">Nama</div>
                    <el-input v-model="form.name" placeholder="Nama" />
                  </el-form-item>
                  <el-form-item>
                    <div class="text-white fw-bold">Nik</div>
                    <el-input v-model="form.id_card_number" placeholder="Nik" />
                  </el-form-item>
                  <el-row :gutter="20">
                    <el-col :span="12">
                      <el-form-item>
                        <div class="text-white fw-bold">Email</div>
                        <el-input
                          v-model="form.email"
                          type="email"
                          placeholder="Email"
                        />
                      </el-form-item>
                    </el-col>
                    <el-col :span="12">
                      <el-form-item>
                        <div class="text-white fw-bold">No. Telepon</div>
                        <el-input
                          v-model="form.phone"
                          placeholder="No. Telepon/Handphone"
                        />
                      </el-form-item>
                    </el-col>
                  </el-row>
                </el-col>
              </el-row>
            </el-col>
            <el-col :span="7">
              <el-form-item>
                <div style="margin-top:2rem; display:block;">
                  <div style="width:200px; height:200px; background:white; display:block; margin:auto">
                    <img v-if="url" :src="url" style="width:100%;height: 100%;object-fit: contain;">
                  </div>
                  <div class="text-white fw-bold" style="text-align:center">Unggah Foto Selfie dengan ktp</div>
                  <input
                    ref="file"
                    style="margin-left:3rem"
                    type="file"
                    class=""
                    @change="handleFileUpload()"
                  >
                </div>
              </el-form-item>
            </el-col>
            <el-col :span="24">
              <el-form-item>
                <h4 class="text-white fw-bold">Kelompok Masyarakat</h4>
                <div style="padding-left:2rem">
                  <div>
                    <el-radio
                      v-model="form.responder_type_id"
                      label="1"
                    >
                      <span style="color:white">Dampak Langsung</span></el-radio>
                  </div>
                  <div>
                    <el-radio
                      v-model="form.responder_type_id"
                      label="2"
                    ><span style="color:white">Pemerhati Lingkungan Hidup</span></el-radio>
                  </div>
                  <div>
                    <el-radio
                      v-model="form.responder_type_id"
                      label="3"
                    ><span style="color:white">LSM</span></el-radio>
                  </div>
                  <div>
                    <el-radio
                      v-model="form.responder_type_id"
                      label="4"
                    ><span style="color:white">Masyarakat Berkepentingan Lainya</span></el-radio>
                  </div>
                </div>
              </el-form-item>
              <el-row>
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
                  <h3 class="fw-bold text-white">Rating</h3>
                  <div class="text-white fw-bold" style="margin-bottom:1rem">
                    Tingkat kesetujuan Anda terhadap Kegiatan/Proyek Ini
                  </div>
                  <div style="display:flex">
                    <div>
                      <ul>
                        <li class="fz8">1 Bintang : Sangat tidak setuju</li>
                        <li class="fz8">2 Bintang : Tidak setuju</li>
                        <li class="fz8">3 Bintang : Netral</li>
                        <li class="fz8">4 Bintang : Setuju</li>
                        <li class="fz8">5 Bintang : Sangat Setuju</li>
                      </ul>
                    </div>
                    <div style="padding-left:1rem">
                      <el-rate v-model="ratings" @change="handleChange(ratings)" />
                    </div>
                  </div>
                </el-col>
              </el-row>
            </el-col>
          </el-row>
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
                type="primary"
                @click="saveFeedback()"
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
import _ from 'lodash';
import { LMap, LTileLayer, LMarker } from 'vue2-leaflet';

export default {
  name: 'Details',
  components: {
    LMap,
    LTileLayer,
    LMarker,
  },
  props: {
    showDetails: Boolean,
    selectedAnnouncement: {
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
      },
      responders: [],
      errorMessage: null,
      photo_filepath: null,
      ratings: null,
      url: null,
      urlMap: 'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
      attribution: '© <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
      zoom: 12,
      center: [-6.93, 107.60],
      bounds: null,
    };
  },
  async created() {
    await this.getResponderType();
  },
  methods: {
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
    handleFileUpload(e) {
      this.photo_filepath = this.$refs.file.files[0];
      this.url = URL.createObjectURL(this.$refs.file.files[0]);
    },
    async saveFeedback() {
      const formData = new FormData();
      formData.append('photo_filepath', this.photo_filepath);
      formData.append('name', this.form.name);
      formData.append('id_card_number', this.form.id_card_number);
      formData.append('phone', this.form.phone);
      formData.append('email', this.form.email);
      formData.append('responder_type_id', this.form.responder_type_id);
      formData.append('concern', this.form.concern);
      formData.append('expectation', this.form.expectation);
      formData.append('rating', this.form.rating);
      formData.append('announcement_id', this.announcementId);

      _.each(this.formData, (value, key) => {
        formData.append(key, value);
      });

      const headers = { 'Content-Type': 'multipart/form-data' };
      await axios
        .post('api/feedbacks', formData, { headers })
        .then(() => {
          this.$message({
            type: 'success',
            message: 'Successfully create a feedback',
            duration: 5 * 1000,
          });
          this.$emit('handleSetTabs');
          this.getAnnouncement();
        })
        .catch((error) => {
          this.errorMessage = error.message;
          console.error('There was an error!', error);
        });
    },
    async getResponderType() {
      await axios.get('api/responder-types').then((response) => {
        this.responders = response.data.data;
      });
    },
    handleCancelComponent() {
      this.$emit('handleCancelComponent');
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
  background: #365337;
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
  background-color: #133715;
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
.el-rate__icon{font-size: 2.5rem !important;}
</style>
