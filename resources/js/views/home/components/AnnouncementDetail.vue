<template>
  <div class="popup__wrapper">
    <el-dialog :close-on-click-modal="false" :show-close="false" :visible.sync="show">
      <div class="body__wrapper">
        <div class="header__init__popup">
          <div>
            <h2><i class="el-icon-info" /> Informasi Rencana Kegiatan</h2>
          </div>
          <div>
            <i class="el-icon-close" title="Tutup" @click="closeDialog()" />
          </div>
        </div>
        <div class="detail__wrapper">
          <div class="information__wrapper">
            <div class="first__info">
              <div class="table__wrapper">
                <table class="table__striped">
                  <tbody>
                    <tr>
                      <td class="title__table">Nama Kegiatan</td>
                      <td class="value__table">{{ details.project.project_title }}</td>
                    </tr>
                    <tr>
                      <td class="title__table">Bidang Usaha / Kegiatan</td>
                      <td class="value__table">{{ details.project.biz_type }}</td>
                    </tr>
                    <tr>
                      <td class="title__table">Skala / Besaran</td>
                      <td class="value__table">{{ details.project_scale }}</td>
                    </tr>
                    <tr>
                      <td class="title__table">Alamat</td>
                      <td class="value__table">{{ details.project.address }}</td>
                    </tr>
                    <tr>
                      <td class="title__table">Pemrakarsa</td>
                      <td class="value__table">{{ details.project_scale }}</td>
                    </tr>
                    <tr>
                      <td class="title__table">Penanggung Jawab</td>
                      <td class="value__table">{{ details.pic_name }}</td>
                    </tr>
                    <tr>
                      <td class="title__table">Alamat Pemrakarsa</td>
                      <td class="value__table">{{ details.pic_address }}</td>
                    </tr>
                    <tr>
                      <td class="title__table">No. Telp Pemrakarsa</td>
                      <td class="value__table">{{ details.pic_address }}</td>
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
                    <td colspan="2"><span v-html="details.project.description" /></td>
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
                      <td class="value__table">{{ details.project.id_project }}</td>
                    </tr>
                    <tr>
                      <td class="title__table">Jenis Dokumen</td>
                      <td class="value__table">{{ details.project.required_doc }}</td>
                    </tr>
                    <tr>
                      <td class="title__table">Kewenangan</td>
                      <td class="value__table">{{ details.project.authority || '-' }}</td>
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

          <div class="map__wrapper">
            Map Canvas
          </div>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: 'AnnouncementDetail',
  props: {
    details: {
      type: Object,
      default: () => {
      },
    },
    show: Boolean,
    announcementId: {
      type: Number,
      default: 0,
    },
  },
  methods: {
    closeDialog() {
      this.show = false;
    },
  },
};
</script>

<style scoped>
.header__init__popup {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.header__init__popup h2 {
  color: white;
}

.header__init__popup .el-icon-close {
  color: white;
  font-weight: 700;
  font-size: 30px;
  cursor: pointer;
}

.header__init__popup .el-icon-close:hover {
  transform: scale(1.2);
  transition: all .2s ease-in;
  cursor: pointer;
}

.body__wrapper {
  background-color: rgba(255, 255, 255, 0.3);
  padding: 20px;
  border-radius: 5px;
}

.popup__wrapper >>> .el-dialog {
  background: #133715 !important;
  overflow: auto;
  width: 80%;
  margin-top: 10vh !important;
}

.popup__wrapper >>> .el-dialog__header {
  display: none;
}

.table__wrapper {
  flex: 1;
}

.table__striped {
  border-collapse: collapse;
  width: 100%;
  color: white;
}

.table__striped td {
  padding: 8px;
  color: #333;
  font-size: 15px;
  font-weight: 700;
}

.title__table {
  width: 40%;
}

.value__table {
  width: 60%;
}

.table__striped tr:nth-child(odd) {
  background-color: white;
}

.table__striped tr:nth-child(even) {
  background-color: #deefe0;
}

.detail__wrapper {
  display: flex;
  justify-content: space-between;
  column-gap: 10px;
  margin-top: 10px;
}

.information__wrapper {
  flex: 1
}

.information__wrapper h2 {
  margin: 10px 0;
  color: white;
}

.map__wrapper {
  flex: 1;
  border: 2px solid greenyellow;
  width: 100%;
  padding: 10px
}

@media screen and (max-width: 450px) {
  .popup__wrapper >>> .el-dialog {
    background: #133715 !important;
    width: 80%;
  }

  .header__init__popup {
    flex-direction: row;
    row-gap: 10px;
    justify-content: space-between;
  }

  .header__init__popup h2 {
    font-size: 15px;
  }

  .header__init__popup .el-icon-close {
    font-size: 15px;
  }

  .detail__wrapper {
    display: flex;
    justify-content: space-between;
    row-gap: 10px;
    margin-top: 10px;
    flex-direction: column;
  }

  .table__striped td {
    padding: 6px;
    color: #333;
    font-size: 11px;
    font-weight: 400;
    line-height: 13px;
  }
}
</style>
