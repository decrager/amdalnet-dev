<template>
  <div class="popup__wrapper">
    <el-dialog :close-on-click-modal="false" :show-close="false" :visible.sync="show">
      <!--      <input v-model="details.id" type="text">-->

      <div class="body__wrapper">
        <div class="header__init__popup">
          <div>
            <h2>Informasi Rencana Kegiatan</h2>
          </div>
          <div>
            <i class="el-icon-close" title="Tutup" @click="closeDialog()" />
          </div>
        </div>
        <div class="detail__wrapper">
          <div class="table__wrapper">
            <table class="table__striped">
              <tbody>
                <tr>
                  <td>No. Barcode</td>
                  <td>{{ details.project.id_project }}</td>
                </tr>
                <tr>
                  <td>Jenis Dokumen</td>
                  <td>{{ details.project.required_doc }}</td>
                </tr>
                <tr>
                  <td>Nama Kegiatan</td>
                  <td>{{ details.project.project_title }}</td>
                </tr>
                <tr>
                  <td>Bidang Usaha/Kegiatan</td>
                  <td>{{ details.project.sector }}</td>
                </tr>
                <tr>
                  <td>Skala/Besaran</td>
                  <td>{{ details.project_scale }}</td>
                </tr>
                <tr>
                  <td>Alamat</td>
                  <td>{{ details.project_location }}</td>
                </tr>
                <tr>
                  <td>Kewenangan</td>
                  <td>{{ details.project.authority || '-' }}</td>
                </tr>
                <tr>
                  <td>Pemrakarsa</td>
                  <td>{{ details.pic_name }}</td>
                </tr>
                <tr>
                  <td>Penanggung Jawab</td>
                  <td>{{ details.cs_name }}</td>
                </tr>
                <tr>
                  <td>Alamat Pemrakarsa</td>
                  <td>{{ details.pic_address }}</td>
                </tr>
                <tr>
                  <td>No. Telepon Pemrakarsa</td>
                  <td>-</td>
                </tr>
                <tr>
                  <td>Email Pemrakarsa</td>
                  <td>-</td>
                </tr>
                <tr>
                  <td>Provinsi/Kota</td>
                  <td>-</td>
                </tr>
                <tr>
                  <td colspan="2">Deskripsi Kegiatan</td>
                </tr>
                <tr>
                  <td colspan="2"><span v-html="details.project.description" /></td>
                </tr>
              </tbody>
            </table>
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
  width: 50%;
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

.map__wrapper {
  flex: 1;
  border: 2px solid greenyellow;
  width: 100%;
  padding: 10px
}

@media screen and (max-width: 450px) {
  .popup__wrapper >>> .el-dialog {
    background: #133715 !important;
    overflow: auto;
    width: 90%;
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
