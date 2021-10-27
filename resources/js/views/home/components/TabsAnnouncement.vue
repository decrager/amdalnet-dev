<template>
  <div class="tabset">
    <input id="tab1" type="radio" name="tabset" aria-controls="amdal" checked>
    <label for="tab1">AMDAL</label>
    <input id="tab2" type="radio" name="tabset" aria-controls="ukl-upl">
    <label for="tab2">UKL / UPL</label>

    <div class="tab-panels">
      <section id="amdal" class="tab-panel">
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
            <button class="button__tanggapan" @click="createFeedback(amdals.id)">Berikan Tanggapan</button>
          </div>
        </div>
      </section>
      <section id="ukl-upl" class="tab-panel">
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
            <button class="button__tanggapan" @click="createFeedback(uklupls.id)">Berikan Tanggapan</button>
          </div>
        </div>
      </section>
    </div>
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

<style>

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
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
  cursor: pointer;
  font-weight: 600;
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
</style>
