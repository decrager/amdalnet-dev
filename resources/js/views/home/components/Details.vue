<template>
  <div v-if="showDetails">
    <div class="detailPengumuman">
      <div class="wrapInDetail">
        <!-- <div class="titleDetail">
          <el-row>
            <el-col :span="24">
              <h1><i class="el-icon-info" /> Rencana Kegiatan</h1>
            </el-col>
          </el-row>
        </div>
        <el-row>
          <el-col :span="12">
            <table class="table__striped">
              <tbody>
                <tr>
                  <td class="title__table">Nama Kegiatan</td>
                  <td class="value__table">
                    {{ selectedAnnouncement.project_title }}
                  </td>
                </tr>
                <tr>
                  <td class="title__table">Bidang Usaha / Kegiatan</td>
                  <td class="value__table">
                    {{ selectedAnnouncement.biz_type }}
                  </td>
                </tr>
                <tr>
                  <td class="title__table">Skala / Besaran</td>
                  <td class="value__table">
                    {{ selectedAnnouncement.project_scale }}
                  </td>
                </tr>
                <tr>
                  <td class="title__table">Alamat</td>
                  <td class="value__table">
                    {{ selectedAnnouncement.address }}
                  </td>
                </tr>
                <tr>
                  <td class="title__table">Pemrakarsa</td>
                  <td class="value__table">
                    {{ selectedAnnouncement.project_scale }}
                  </td>
                </tr>
                <tr>
                  <td class="title__table">Penanggung Jawab</td>
                  <td class="value__table">
                    {{ selectedAnnouncement.pic_name }}
                  </td>
                </tr>
                <tr>
                  <td class="title__table">Alamat Pemrakarsa</td>
                  <td class="value__table">
                    {{ selectedAnnouncement.pic_address }}
                  </td>
                </tr>
              </tbody>
            </table>
          </el-col>
        </el-row>
        <div class="titleDetail">
          <el-row>
            <el-col :span="24">
              <h1><i class="el-icon-document" /> Deskripsi</h1>
            </el-col>
          </el-row>
        </div>
        <el-row>
          <el-col :span="12">
            <table class="table__striped">
              <tbody>
                <tr>
                  <td class="title__table" colspan="2"><span v-html="selectedAnnouncement.description" /></td>
                </tr>
              </tbody>
            </table>
          </el-col>
        </el-row>
        <div class="titleDetail">
          <el-row>
            <el-col :span="24">
              <h1><i class="el-icon-circle-check" /> Penapisan</h1>
            </el-col>
          </el-row>
        </div>
        <el-row>
          <el-col :span="12">
            <table class="table__striped">
              <tbody>
                <tr>
                  <td class="title__table">No Registrasi</td>
                  <td class="value__table">
                    {{ selectedAnnouncement.id_project }}
                  </td>
                </tr>
                <tr>
                  <td class="title__table">Jenis Dokumen</td>
                  <td class="value__table">
                    {{ selectedAnnouncement.required_doc }}
                  </td>
                </tr>
              </tbody>
            </table>
          </el-col>
        </el-row> -->
        <!-- <div class="information__wrapper">
            <div class="first__info">
              <div class="table__wrapper">
                <table class="table__striped">
                  <tbody>
                    <tr>
                      <td class="title__table">Nama Kegiatan</td>
                      <td class="value__table">
                        {{ selectedAnnouncement.project_title }}
                      </td>
                    </tr>
                    <tr>
                      <td class="title__table">Bidang Usaha / Kegiatan</td>
                      <td class="value__table">
                        {{ selectedAnnouncement.biz_type }}
                      </td>
                    </tr>
                    <tr>
                      <td class="title__table">Skala / Besaran</td>
                      <td class="value__table">
                        {{ selectedAnnouncement.project_scale }}
                      </td>
                    </tr>
                    <tr>
                      <td class="title__table">Alamat</td>
                      <td class="value__table">
                        {{ selectedAnnouncement.address }}
                      </td>
                    </tr>
                    <tr>
                      <td class="title__table">Pemrakarsa</td>
                      <td class="value__table">
                        {{ selectedAnnouncement.project_scale }}
                      </td>
                    </tr>
                    <tr>
                      <td class="title__table">Penanggung Jawab</td>
                      <td class="value__table">
                        {{ selectedAnnouncement.pic_name }}
                      </td>
                    </tr>
                    <tr>
                      <td class="title__table">Alamat Pemrakarsa</td>
                      <td class="value__table">
                        {{ selectedAnnouncement.pic_address }}
                      </td>
                    </tr>
                    <tr>
                      <td class="title__table">No. Telp Pemrakarsa</td>
                      <td class="value__table">
                        {{ selectedAnnouncement.pic_address }}
                      </td>
                    </tr>
                    <tr>
                      <td class="title__table">Email Pemrakarsa</td>
                      <td class="value__table">-</td>
                    </tr>
                    <tr>
                      <td class="title__table">Provinsi Pemrakarsa</td>
                      <td class="value__table">-</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="second__info">
              <div class="hasil__penapisan">
                <div>
                  <h2><i class="el-icon-document" /> Deskripsi</h2>
                </div>
              </div>
              <table class="table__striped">
                <tbody>
                  <tr>
                    <td colspan="2">
                      <span v-html="selectedAnnouncement.description" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="third__info">
              <div class="hasil__penapisan">
                <div>
                  <h2><i class="el-icon-circle-check" /> Hasil Penapisan</h2>
                </div>
              </div>
              <div class="table__wrapper">
                <table class="table__striped">
                  <tbody>
                    <tr>
                      <td class="title__table">No Registrasi</td>
                      <td class="value__table">
                        {{ selectedAnnouncement.id_project }}
                      </td>
                    </tr>
                    <tr>
                      <td class="title__table">Jenis Dokumen</td>
                      <td class="value__table">
                        {{ selectedAnnouncement.required_doc }}
                      </td>
                    </tr>
                    <tr>
                      <td class="title__table">Kewenangan</td>
                      <td class="value__table">
                        {{ selectedAnnouncement.authority || '-' }}
                      </td>
                    </tr>
                    <tr>
                      <td class="title__table">Tim Penyusun</td>
                      <td class="value__table" />
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="map__wrapper">Map Canvas</div> -->
        <el-row :gutter="20">
          <el-col :span="16">
            <div class="wrapDetail">
              <img src="https://placeimg.com/150/150/arch/grayscale" alt="">
              <div class="wrapDetailRight">
                <p style="margin-bottom: 1rem">
                  Kegiatan Pembangunan Kilang Minyak Blok Cepu Kabupaten Cepu,
                  Prov Jawa Tengah 2022
                </p>
                <h4 class="fw-bold">PT. Pertamina</h4>
                <h4 class="fw-bold">Jl. Gambir No.39, Jakarta Pusat</h4>
              </div>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="wrapDetailRightRow">
              <el-button type="success" plain>AMDAL</el-button>
              <h4 class="fw-bold">Reg No. 781224242</h4>
              <h4 class="fw-bold">[Terpadu, Rencana, Baru]</h4>
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="12">
            <div style="padding-left: 2rem">
              <h4 class="fw-bold">Pengumuman</h4>
              <p>12 November 2021 - 12 Desember 2021</p>
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
              <p style="margin-bottom: 1rem">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam
                labore inventore aperiam. Rem et in assumenda, sit Lorem ipsum
                dolor, sit amet consectetur adipisicing elit. Repellendus est
                commodi, libero omnis sit nostrum aliquam. Veniam quia
                accusantium quos vel doloribus exercitationem animi nobis, eos
                a, iste id incidunt!
              </p>
              <h4 class="fw-bold">Dampak Potensi</h4>
              <p style="margin-bottom: 1.5rem">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam
                labore inventore aperiam. Rem et in assumenda, sit
              </p>
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="14">
            <div style="padding-left: 2rem">
              <div style="display: flex; margin-bottom: 1rem">
                <h4 class="fw-bold" style="">Sifat Kegiatan:</h4>
                <h4>Terpadu,Rencana, Baru</h4>
              </div>
              <h4 class="fw-bold">Dampak Potensi</h4>
              <table style="margin-bottom: 1rem">
                <tr>
                  <td>1232353454</td>
                  <td>:</td>
                  <td>Pembangunan</td>
                </tr>
              </table>
              <h4 class="fw-bold">Kegiatan Pendukung</h4>
              <table style="margin-bottom: 1rem">
                <tr>
                  <td>9877 - Perkebunan</td>
                  <td>:</td>
                  <td>Perkebunan Tebu</td>
                </tr>
              </table>
              <h4 class="fw-bold">Dampak Potensi</h4>
              <p style="margin-bottom: 1.5rem">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam
                labore inventore aperiam. Rem et in assumenda, sit
              </p>
            </div>
          </el-col>
          <el-col :span="10">
            <div style="padding-right: 2rem">
              <div class="maps" />
            </div>
          </el-col>
        </el-row>
      </div>
    </div>
    <div class="detailPengumuman">
      <el-row>
        <el-col :span="24">
          <h1>Saran/Tanggapan untuk Ke giatan</h1>
        </el-col>
      </el-row>
      <el-form
        ref="form"
        enctype="multipart/form-data"
        @submit.prevent="saveFeedback"
      >
        <input v-model="announcementId" type="text">
        <el-row :gutter="20">
          <el-col :span="12">
            <el-row :gutter="20">
              <el-col :span="12">
                <el-form-item>
                  <div class="text-white fw-bold">Nama</div>
                  <el-input v-model="form.name" placeholder="Nama" />
                </el-form-item>
                <el-form-item>
                  <div class="text-white fw-bold">Nik</div>
                  <el-input v-model="form.id_card_number" placeholder="Nik" />
                </el-form-item>
                <el-form-item>
                  <div class="text-white fw-bold">No. Telepon/Handphone</div>
                  <el-input
                    v-model="form.phone"
                    placeholder="No. Telepon/Handphone"
                  />
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item>
                  <div class="text-white fw-bold">Peran</div>
                  <el-form-item label="">
                    <el-radio
                      v-model="form.responder_type_id"
                      label="1"
                    >
                      Terkena Dampak Langsung</el-radio>
                    <el-radio
                      v-model="form.responder_type_id"
                      label="2"
                    >Pemerhati Lingkungan Hidup</el-radio>
                    <el-radio
                      v-model="form.responder_type_id"
                      label="3"
                    >LSM</el-radio>
                    <el-radio
                      v-model="form.responder_type_id"
                      label="4"
                    >Masyarakat Berkepentingan Lainya</el-radio>
                  </el-form-item>
                </el-form-item>
                <el-form-item>
                  <div class="text-white fw-bold">Unggah Foto Selfie</div>
                  <input
                    ref="file"
                    type="file"
                    class="el-input__inner"
                    @change="handleFileUpload()"
                  >
                </el-form-item>
                <el-form-item>
                  <div class="text-white fw-bold">Email</div>
                  <el-input
                    v-model="form.email"
                    type="email"
                    placeholder="Email"
                  />
                </el-form-item>
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
            <div class="text-white fw-bold" style="margin: 2rem 0 1rem 0">
              Berikan rating Anda untuk Rencana Usaha/Kegiatan ini:
            </div>
            <div class="rating">
              <el-radio v-model="form.rating" value="1" label="1">1</el-radio>
              <el-radio v-model="form.rating" value="2" label="2">2</el-radio>
              <el-radio v-model="form.rating" value="3" label="3">3</el-radio>
              <el-radio v-model="form.rating" value="4" label="4">4</el-radio>
              <el-radio v-model="form.rating" value="5" label="5">5</el-radio>
            </div>
          </el-col>
        </el-row>
      </el-form>
      <el-row>
        <el-col :span="24">
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
</template>
<script>
import axios from 'axios';
import _ from 'lodash';

export default {
  name: 'Details',
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
    };
  },
  async created() {
    await this.getResponderType();
  },
  methods: {
    getAnnouncement() {
      axios.get('/api/announcements').then((response) => {
        return response.data.data;
      });
    },
    handleFileUpload() {
      this.photo_filepath = this.$refs.file.files[0];
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
          alert('Successfully create a feedback');
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
.el-form-item {
  margin-bottom: 0;
}
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
</style>
