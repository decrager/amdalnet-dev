<template>
  <div class="popup__wrapper">
    <el-dialog :close-on-click-modal="false" :show-close="false" :visible.sync="show">
      <div class="header__init__popup">
        <div style="flex: 1">
          <img alt="" src="/images/logo-amdal-white.png">
        </div>
        <div style="flex: 2">
          <h2>Pengumuman & Informasi Terbaru</h2>
        </div>
        <div>
          <i class="el-icon-close" title="Tutup" @click="closeDialog()" />
        </div>
      </div>
      <div class="tabset">
        <input id="tab1-popup" aria-controls="amdal-popup" checked name="tabset" type="radio">
        <label for="tab1-popup">AMDAL</label>
        <input id="tab2-popup" aria-controls="ukl-upl-popup" name="tabset" type="radio">
        <label for="tab2-popup">UKL / UPL</label>

        <div class="tab-panels">
          <section id="amdal-popup" class="tab-panel">
            <div v-for="amdal in amdals" :key="amdal.id" class="announce__box__wrapper">
              <div class="announce__box__icon">
                <img alt="" src="/images/list.svg">
              </div>
              <div class="announce__box__desc">
                <p class="announce__box__desc__content">{{ amdal.project_type }}</p>
                <p class="announce__box__desc__content text__special">{{ amdal.pic_name }}</p>
              </div>
              <div class="announce__box__dampak">
                <p class="announce__box__desc__content">Dampak Potensial : {{ amdal.potential_impact }}</p>
              </div>
              <div class="announce__box__date">
                <p class="announce__box__desc__content">{{ formatDate(amdal.start_date) }}</p>
              </div>
              <div class="announce__box__button">
                <button class="button__tanggapan" @click="createFeedback(amdal.id)"><i class="el-icon-document" />
                  Berikan Tanggapan
                </button>
              </div>
            </div>
          </section>
          <section id="ukl-upl-popup" class="tab-panel">
            <div v-for="uklupl in uklupls" :key="uklupl.id" class="announce__box__wrapper">
              <div class="announce__box__icon">
                <img alt="" src="/images/list.svg">
              </div>
              <div class="announce__box__desc">
                <p class="announce__box__desc__content">{{ uklupl.project_type }}</p>
                <p class="announce__box__desc__content text__special">{{ uklupl.pic_name }}</p>
              </div>
              <div class="announce__box__dampak">
                <p class="announce__box__desc__content">Dampak Potensial : {{ uklupl.potential_impact }}</p>
              </div>
              <div class="announce__box__date">
                <p class="announce__box__desc__content">{{ formatDate(uklupl.start_date) }}</p>
              </div>
              <div class="announce__box__button">
                <button class="button__tanggapan" @click="createFeedback(uklupl.id)"><i class="el-icon-document" />
                  Berikan Tanggapan
                </button>
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
import dayjs from 'dayjs';
import 'dayjs/locale/id';
import { mapGetters } from 'vuex';

export default {
  name: 'InitPopup',
  data() {
    return {
      show: true,
    };
  },
  computed: {
    ...mapGetters(['amdals', 'uklupls']),
  },
  methods: {
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

.tab-panels {
  overflow: auto;
  max-height: 400px;
  margin-bottom: 20px;
}

.announce__box__icon img {
  height: 41px;
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

  .announce__box__desc__content {
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
    height: 700px;
    overflow: auto;
  }

  .button__tanggapan:hover {
    transition: none;
  }

  .dialog__footer {
    flex-direction: column;
    row-gap: 10px;
    margin-top: 50px;
  }

  .dialog__footer >>> .el-button {
    width: 100%;
  }
}
</style>
