<template>
  <div class="tabset">
    <el-tabs type="card">
      <el-tab-pane label="AMDAL">
        <div v-for="amdals in amdal" :key="amdals.id" class="announce__box__wrapper">
          <div class="announce__box__icon">
            <img src="/images/list.svg" alt="">
          </div>
          <div class="announce__box__desc">
            <p class="announce__box__desc__content">{{ amdals.project_type }}</p>
            <p class="announce__box__desc__content text__special">{{ amdals.pic_name }}</p>
            <p class="announce__box__desc__content">Dampak Potensial : {{ amdals.potential_impact }}</p>
            <p class="announce__box__desc__content">{{ formatDate(amdals.start_date) }} | {{ amdals.feedbacks_count }} Tanggapan</p>
          </div>
          <div class="announce__box__button">
            <button class="button__tanggapan" @click="createFeedback(amdals.id)">Berikan<br>Tanggapan</button>
          </div>
        </div>
      </el-tab-pane>

      <el-tab-pane label="UKL - UPL">
        <div v-for="uklupls in uklupl" :key="uklupls.id" class="announce__box__wrapper">
          <div class="announce__box__icon">
            <img src="/images/list.svg" alt="">
          </div>
          <div class="announce__box__desc">
            <p class="announce__box__desc__content">{{ uklupls.project_type }}</p>
            <p class="announce__box__desc__content text__special">{{ uklupls.pic_name }}</p>
            <p class="announce__box__desc__content">Dampak Potensial : {{ uklupls.potential_impact }}</p>
            <p class="announce__box__desc__content">{{ formatDate(uklupls.start_date) }} | {{ uklupls.feedbacks_count }} Tanggapan</p>
          </div>
          <div class="announce__box__button">
            <button class="button__tanggapan" @click="createFeedback(uklupls.id)">Berikan<br>Tanggapan</button>
          </div>
        </div>
      </el-tab-pane>

    </el-tabs>
    <CreateFeedback
      :feedback="selectedFeedback"
      :show="showIdDialog"
      :announcement-id="selectedId"
    />
  </div>
</template>

<script>
import axios from 'axios';
import dayjs from 'dayjs';
import 'dayjs/locale/id';
import CreateFeedback from '../components/CreateFeedback.vue';

export default {
  name: 'AnnouncementTabs',
  components: {
    CreateFeedback,
  },
  data() {
    return {
      announcement: [],
      amdal: [],
      uklupl: [],
      selectedFeedback: {},
      showIdDialog: false,
      selectedId: 0,
      showInitPopup: false,
      seen: true,
    };
  },
  async created() {
    await this.getAnnouncement();
  },
  methods: {
    async getAnnouncement() {
      await axios.get('/api/announcements')
        .then(response => {
          this.announcement = response.data.data;
          this.announcement.forEach(amdalData => {
            if (amdalData.project_result === 'AMDAL') {
              this.amdal.push(amdalData);
            }
            if (amdalData.project_result === 'UKL-UPL') {
              this.uklupl.push(amdalData);
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
    createFeedback(id) {
      this.selectedId = id;
      var toShow = {};
      this.selectedFeedback = toShow;
      this.showIdDialog = true;
    },
  },
};
</script>

<style scoped>

.tabset {
  margin-top: 30px;
}
.tabset >>> .el-tabs--card > .el-tabs__header .el-tabs__item.is-active {
    border-bottom-color: #FFFFFF;
    color: #F6993F;
    font-weight: 800;
    background-color: #062307;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
}

.tabset >>> .el-tabs--card > .el-tabs__header .el-tabs__item {
    border-bottom-color: #FFFFFF;
    color: white;
    font-weight: 400;
    border-left: none
}

.tabset >>> .el-button.el-button--primary.el-button--medium {
  float: right;
}

@media screen and (max-width: 450px) {
  .announce__box__wrapper{
    flex-direction: column;
    row-gap: 10px;
    align-items: flex-start;
  }
  .announce__box__icon {
    display: none;
  }
  .announce__box__desc__content {
    font-size: 12px;
    line-height: 20px;
  }
  .button__tanggapan {
    font-size: 14px;
    font-weight: 600;
  }
  .button__tanggapan:hover {
    transform: none;
    transition: none;
  }
}
</style>
