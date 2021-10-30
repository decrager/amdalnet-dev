<template>
  <div class="popup__wrapper">
    <el-dialog :visible.sync="show" :close-on-click-modal="false" :show-close="false">
      <div class="header__init__popup">
        <div style="flex: 1">
          <img src="/images/logo-amdal-white.png" alt="">
        </div>
        <div style="flex: 2">
          <h2>Pengumuman & Informasi Terbaru</h2>
        </div>
        <div>
          <i class="el-icon-close" title="Tutup" @click="closeDialog()" />
        </div>
      </div>
      <div class="tabset">
        <input id="tab1-popup" type="radio" name="tabset" aria-controls="amdal-popup" checked>
        <label for="tab1-popup">AMDAL</label>
        <input id="tab2-popup" type="radio" name="tabset" aria-controls="ukl-upl-popup">
        <label for="tab2-popup">UKL / UPL</label>

        <div class="tab-panels">
          <section id="amdal-popup" class="tab-panel">
            <div v-for="init_amdals in init_amdal" :key="init_amdals.id" class="announce__box__wrapper">
              <div class="announce__box__icon">
                <img src="/images/list.svg" alt="">
              </div>
              <div class="announce__box__desc">
                <p class="announce__box__desc__content">{{ init_amdals.project_type }}</p>
                <p class="announce__box__desc__content text__special">{{ init_amdals.pic_name }}</p>
              </div>
              <div class="announce__box__dampak">
                <p class="announce__box__desc__content">Dampak Potensial : {{ init_amdals.potential_impact }}</p>
              </div>
              <div class="announce__box__date">
                <p class="announce__box__desc__content">{{ formatDate(init_amdals.start_date) }}</p>
              </div>
              <div class="announce__box__button">
                <button class="button__tanggapan" @click="createFeedback(amdals.id)">Berikan Tanggapan</button>
              </div>
            </div>
          </section>
          <section id="ukl-upl-popup" class="tab-panel">
            <div v-for="init_uklupls in init_uklupl" :key="init_uklupls.id" class="announce__box__wrapper">
              <div class="announce__box__icon">
                <img src="/images/list.svg" alt="">
              </div>
              <div class="announce__box__desc">
                <p class="announce__box__desc__content">{{ init_uklupls.project_type }}</p>
                <p class="announce__box__desc__content text__special">{{ init_uklupls.pic_name }}</p>
              </div>
              <div class="announce__box__dampak">
                <p class="announce__box__desc__content">Dampak Potensial : {{ init_uklupls.potential_impact }}</p>
              </div>
              <div class="announce__box__date">
                <p class="announce__box__desc__content">{{ formatDate(init_uklupls.start_date) }}</p>
              </div>
              <div class="announce__box__button">
                <button class="button__tanggapan" @click="createFeedback(init_uklupls.id)">Berikan Tanggapan</button>
              </div>
            </div>
          </section>
        </div>
      </div>

      <div class="dialog__footer">
        <el-button @click="closeDialog()"><i class="el-icon-close" /> Tutup</el-button>
        <el-button type="primary" @click="closeDialog()"><i class="el-icon-menu" /> Lihat Semua Pengumuman</el-button>
      </div>

    </el-dialog>
  </div>
</template>

<script>
import axios from 'axios';
import dayjs from 'dayjs';
import 'dayjs/locale/id';

export default {
  name: 'InitPopup',
  props: {
    show: Boolean,
  },
  data() {
    return {
      init_announcement: [],
      init_amdal: [],
      init_uklupl: [],
    };
  },
  async created() {
    await this.getInitAnnouncement();
  },
  methods: {
    async getInitAnnouncement() {
      await axios.get('/api/announcements')
        .then(response => {
          this.init_announcement = response.data.data;
          this.init_announcement.forEach(initAmdalData => {
            if (initAmdalData.project_result === 'AMDAL') {
              this.init_amdal.push(initAmdalData);
            }
            if (initAmdalData.project_result === 'UKL-UPL') {
              this.init_uklupl.push(initAmdalData);
            }
          });
        });
    },
    formatDate(value) {
      if (value) {
        dayjs.locale('id');
        return dayjs(String(value)).format('DD-MMMM-YYYY');
      }
    },
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

.header__init__popup img {
    width: 150px;
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

.popup__wrapper >>> .el-dialog {
    background: #133715 !important;
    overflow: auto;
    width: 80%;
}

.popup__wrapper >>> .el-dialog__header {
    display: none;
}
.tabset {
  margin-top: 30px;
}

.tabset > input[type="radio"] {
  position: absolute;
  left: -200vw;
}

.tabset .tab-panel {
  display: none;
}

.tabset > input:first-child:checked ~ .tab-panels > .tab-panel:first-child,
.tabset > input:nth-child(3):checked ~ .tab-panels > .tab-panel:nth-child(2),
.tabset > input:nth-child(5):checked ~ .tab-panels > .tab-panel:nth-child(3),
.tabset > input:nth-child(7):checked ~ .tab-panels > .tab-panel:nth-child(4),
.tabset > input:nth-child(9):checked ~ .tab-panels > .tab-panel:nth-child(5),
.tabset > input:nth-child(11):checked ~ .tab-panels > .tab-panel:nth-child(6) {
  display: block;
}

.tabset > label {
  position: relative;
  display: inline-block;
  padding: 15px 15px;
  border: 1px solid transparent;
  cursor: pointer;
  font-weight: 600;
  color: white;
}

.tabset > label::after {
  content: "";
  position: absolute;
  left: 15px;
  bottom: 10px;
  width: 22px;
  height: 4px;
}

.tabset > label:hover,
.tabset > input:focus + label {
  color: white;
  font-weight: 700;
}

.tabset > input:checked + label {
  border-color: #ccc;
  margin-bottom: -1px;
  background-color: #062307
}

.tab-panel {
  padding: 10px 0;
  border-top: 1px solid #ccc;
}

.dialog__footer {
  display: flex;
  align-items: flex-end;
  justify-content: flex-end;
}

@media screen and (max-width: 450px) {
  .popup__wrapper >>> .el-dialog {
    background: #133715 !important;
    overflow: auto;
    width: 90%;
  }
  .header__init__popup {
    flex-direction: column;
    row-gap: 10px;
  }
  .header__init__popup h2 {
    font-size: 17px;
  }
  .el-icon-close {
    display: none;
  }
  .announce__box__wrapper {
    flex-direction: column;
  }

  .announce__box__icon {
    display: none;
  }
  .announce__box__desc__content{
    font-size: 12px;
  }
  .announce__box__button {
      justify-content: flex-end;
      align-items: flex-end;
      display: flex;
      margin-top: 10px;
      border-top: 1px solid;
  }

  .button__tanggapan {
      font-size: 12px;
      margin-top: 10px;
      width: 100%;
  }

  .tabset {
    height: 410px;
  }

  .tab-panels {
    overflow: auto;
    max-height: 400px;
    margin-bottom: 20px;
  }

  .popup__wrapper >>> .el-dialog__body {
    height: 650px;
    overflow: auto;
  }

  .button__tanggapan:hover {
      transition: none;
  }
  .dialog__footer {
    flex-direction: column;
    row-gap: 10px
  }

  .dialog__footer >>> .el-button {
    width: 100%;
  }
}
</style>
