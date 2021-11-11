<template>
  <div>
    <div v-if="showTabs" class="tabset">
      <el-tabs type="card">
        <el-tab-pane style="color:red" label="AMDAL">
          <div v-for="amdal in amdals.data" :key="amdal.id" class="announce__box__wrapper">
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
              <button class="button__tanggapan btn-tanggapan" @click="openDetails(amdal.id,'AMDAL')"><i class="el-icon-document" /> Berikan
                Tanggapan
              </button>
            </div>
          </div>
        </el-tab-pane>
        <el-tab-pane class="tabs-custom" label="UKL - UPL">
          <div v-for="uklupl in uklupls.data" :key="uklupl.id" class="announce__box__wrapper">
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
              <button class="button__tanggapan btn-tanggapan" @click="openDetails(uklupl.id,'UKL')"><i class="el-icon-document" /> Berikan
                Tanggapan
              </button>
            </div>
          </div>
        </el-tab-pane>
      </el-tabs>
      <!-- <AnnouncementDetail
        :announcement-id="selectedId"
        :details="selectedAnnouncement"
        :show="showDetailsDialog"
      /> -->
    </div>
    <Details
      :show-details="showDetails"
      :announcement-id="selectedId"
      :selected-announcement="selectedAnnouncement"
      @handleCancelComponent="handleCancelComponent"
    />
  </div>
</template>

<script>
import axios from 'axios';
import dayjs from 'dayjs';
import 'dayjs/locale/id';
import Details from './Details';
import { mapGetters } from 'vuex';

export default {
  name: 'AnnouncementTabs',
  components: {
    Details,
  },
  data() {
    return {
      selectedAnnouncement: {},
      showIdDialog: false,
      showDetailsDialog: false,
      selectedId: 0,
      showInitPopup: false,
      showDetails: false,
      showTabs: true,
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
    openDetails(id, param) {
      this.showDetails = true;
      this.showTabs = false;
      this.selectedId = id;
      this.selectedAnnouncement = {};
      this.showDetailsDialog = true;
      axios.get('/api/announcements/' + this.selectedId)
        .then(response => {
          this.selectedAnnouncement = response.data.project;
        });
    },
    handleCancelComponent(){
      this.showDetails = false;
      this.showTabs = true;
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
.announce__box__button{align-items: end;}
.btn-tanggapan{background-color: transparent;color: #f9902b;}
.button__tanggapan:hover{background-color: transparent;color: #fff;}
.el-tabs__item.is-top{color: #f6993f;}
.el-tabs--card > .el-tabs__header .el-tabs__item.is-active,.el-tabs--card > .el-tabs__header .el-tabs__item{border-bottom: none;}
</style>
