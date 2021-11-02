<template>
  <div class="tabset">
    <el-tabs type="card">
      <el-tab-pane label="AMDAL">
        <div v-for="amdal in amdals" :key="amdal.id" class="announce__box__wrapper" @click="openDetails(amdal.id)">
          <div class="announce__box__icon">
            <img alt="" src="/images/list.svg">
          </div>
          <div class="announce__box__desc">
            <p class="announce__box__desc__content">{{ amdal.project_type }}</p>
            <p class="announce__box__desc__content text__special">{{ amdal.pic_name }}</p>
            <p class="announce__box__desc__content">Dampak Potensial : {{ amdal.potential_impact }}</p>
            <p class="announce__box__desc__content">{{ formatDate(amdal.start_date) }} | {{ amdal.feedbacks_count }}
              Tanggapan</p>
          </div>
          <div class="announce__box__button">
            <button class="button__tanggapan" @click="createFeedback(amdal.id)"><i class="el-icon-document" /> Berikan
              Tanggapan
            </button>
          </div>
        </div>
      </el-tab-pane>

      <el-tab-pane label="UKL - UPL">
        <div v-for="uklupl in uklupls" :key="uklupl.id" class="announce__box__wrapper" @click="openDetails(uklupl.id)">
          <div class="announce__box__icon">
            <img alt="" src="/images/list.svg">
          </div>
          <div class="announce__box__desc">
            <p class="announce__box__desc__content">{{ uklupl.project_type }}</p>
            <p class="announce__box__desc__content text__special">{{ uklupl.pic_name }}</p>
            <p class="announce__box__desc__content">Dampak Potensial : {{ uklupl.potential_impact }}</p>
            <p class="announce__box__desc__content">{{ formatDate(uklupl.start_date) }} | {{ uklupl.feedbacks_count }}
              Tanggapan</p>
          </div>
          <div class="announce__box__button">
            <button class="button__tanggapan" @click="createFeedback(uklupl.id)"><i class="el-icon-document" /> Berikan
              Tanggapan
            </button>
          </div>
        </div>
      </el-tab-pane>

    </el-tabs>
    <CreateFeedback
      :announcement-id="selectedId"
      :details="selectedFeedback"
      :show="showIdDialog"
    />
    <AnnouncementDetail
      :announcement-id="selectedId"
      :details="selectedAnnouncement"
      :show="showDetailsDialog"
    />
  </div>
</template>

<script>
import axios from 'axios';
import dayjs from 'dayjs';
import 'dayjs/locale/id';
import CreateFeedback from '../components/CreateFeedback.vue';
import AnnouncementDetail from './AnnouncementDetail';
import { mapGetters } from 'vuex';

export default {
  name: 'AnnouncementTabs',
  components: {
    CreateFeedback,
    AnnouncementDetail,
  },
  data() {
    return {
      selectedFeedback: {},
      selectedAnnouncement: {},
      showIdDialog: false,
      showDetailsDialog: false,
      selectedId: 0,
      showInitPopup: false,
    };
  },
  computed: {
    ...mapGetters(['amdals', 'uklupls']),
  },
  created() {
    this.$store.dispatch('getAmdal');
    this.$store.dispatch('getUklupl');
  },
  methods: {
    formatDate(value) {
      if (value) {
        dayjs.locale('id');
        return dayjs(String(value)).format('DD MMMM YYYY');
      }
    },
    createFeedback(id) {
      this.selectedId = id;
      this.selectedFeedback = {};
      this.showIdDialog = true;
    },
    openDetails(id) {
      this.selectedId = id;
      this.selectedAnnouncement = {};
      this.showDetailsDialog = true;
      axios.get('/api/announcements/' + this.selectedId)
        .then(response => {
          this.selectedAnnouncement = response.data;
        });
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
  .announce__box__wrapper {
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
