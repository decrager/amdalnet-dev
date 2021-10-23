<template>
  <section id="counter" class="counter section_data">
    <div class="counter__container container">
      <div class="counter__data">
        <div class="counter__box">
          <h1 class="counter__number">{{ amdal }}</h1>
          <p class="counter__first__line">Persetujuan</p>
          <p class="counter__second__line">AMDAL</p>
        </div>

        <div class="counter__box">
          <h1 class="counter__number">{{ uklupl }}</h1>
          <p class="counter__first__line">Persetujuan</p>
          <p class="counter__second__line">UKL/UPL</p>
        </div>

        <div class="counter__box">
          <h1 class="counter__number">{{ allcount }}</h1>
          <p class="counter__first__line">Persetujuan</p>
          <p class="counter__second__line">Sedang Diproses</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from 'axios';

export default {
  name: 'CounterHome',
  data() {
    return {
      announcement: [],
      amdal: 0,
      uklupl: 0,
      allcount: 0,
    };
  },
  async created() {
    await this.getData();
  },
  methods: {
    async getData() {
      await axios.get('api/announcements')
        .then(response => {
          this.announcement = response.data.data;
          var countAmdal = this.announcement.filter(function(element){
            return element.project_result === 'AMDAL';
          }).length;
          this.amdal += countAmdal;

          var countUklupl = this.announcement.filter(function(element){
            return element.project_result === 'UKL-UPL';
          }).length;
          this.uklupl += countUklupl;
          this.allcount += this.announcement.length;
        });
    },
  },
};
</script>
