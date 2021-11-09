<template>
  <div v-if="showDetails" class="detailPengumuman">
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
</style>
