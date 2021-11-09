<template>
  <div v-if="showDetails">
    <div class="detailPengumuman">
      <div class="titleDetail">
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
      </el-row>

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
    </div>
    <div class="detailPengumuman">
      <el-row>
        <el-col :span="24">
          <h1>Saran/Tanggapan untuk Ke giatan</h1>
        </el-col>
      </el-row>
      <el-form ref="form">
        <el-row :gutter="20">
          <el-col :span="12">
            <el-row :gutter="20">
              <el-col :span="12">
                <el-form-item>
                  <div class="text-white fw-bold">Nama</div>
                  <el-input placeholder="Nama" />
                </el-form-item>
                <el-form-item>
                  <div class="text-white fw-bold">Nik</div>
                  <el-input placeholder="Nik" />
                </el-form-item>
                <el-form-item>
                  <div class="text-white fw-bold">No. Telepon/Handphone</div>
                  <el-input placeholder="No. Telepon/Handphone" />
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item>
                  <div class="text-white fw-bold">Peran</div>
                  <el-form-item label="">
                    <el-select v-model="form.region" placeholder="Pilih Peran Anda">
                      <el-option label="Zone one" value="shanghai" />
                      <el-option label="Zone two" value="beijing" />
                    </el-select>
                  </el-form-item>
                </el-form-item>
                <el-form-item>
                  <div class="text-white fw-bold">Unggah Foto Selfie</div>
                  <el-input type="file" placeholder="Unggah Foto" />
                </el-form-item>
                <el-form-item>
                  <div class="text-white fw-bold">Email</div>
                  <el-input type="email" placeholder="Email" />
                </el-form-item>
              </el-col>
            </el-row>
          </el-col>
          <el-col :span="12">
            <el-form-item>
              <div class="text-white fw-bold">Kekhawatiran</div>
              <el-input type="textarea" placeholder="Kekhawatiran" />
            </el-form-item>
            <el-form-item>
              <div class="text-white fw-bold">Harapan</div>
              <el-input type="textarea" placeholder="Harapan" />
            </el-form-item>
            <div class="text-white fw-bold" style="margin:2rem 0 1rem 0">Berikan rating Anda untuk Rencana Usaha/Kegiatan ini:</div>
            <div class="rating">
              <div style="width:25%">
                <p class="text-white">1 = Sangat Tidak </p>
                <p class="text-white">Setuju/Mendukung</p>
              </div>
              <div style="width:50%">
                <h4>Start</h4>
              </div>
              <div style="width:25%">
                <p class="text-white">1 = Sangat Tidak </p>
                <p class="text-white">Setuju/Mendukung</p>
              </div>
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
            <el-button id="kirim" type="primary">Kirim</el-button>
          </div>
        </el-col>
      </el-row>
    </div>
  </div>
</template>
<script>
export default {
  name: 'Details',
  props: {
    // list: {
    //   type: Array,
    //   default: () => [],
    // },
    showDetails: Boolean,
    selectedAnnouncement: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      form: {
        name: '',
        region: '',
        date1: '',
        date2: '',
        delivery: false,
        type: [],
        resource: '',
        desc: '',
      },
    };
  },
  methods: {
    handleCancelComponent() {
      this.$emit('handleCancelComponent');
    },
  },
};
</script>
<style scoped>
.titleDetail h1 {
  font-size: 19px;
  margin-top:15px !important;
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
.text-white{color: #fff;}
.el-form-item {
    margin-bottom: 0;
}
.fw-bold{font-weight: bold;}
.el-select{width: 100%;}
.rating{display: flex;}
.rating p{font-size: 0.8rem;}
</style>
