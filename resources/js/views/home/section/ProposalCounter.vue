<template>
  <section id="counter" class="counter section_data">
    <div v-loading="loading" lass="counter__container container">
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
          <h1 class="counter__number">{{ onprogress }}</h1>
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
  name: 'ProposalCounter',
  data(){
    return {
      amdal: 0,
      uklupl: 0,
      onprogress: 0,
      loading: false,
    };
  },
  mounted() {
    this.getCount();
  },
  methods: {
    getCount(){
      this.loading = true;
      axios.get(`/api/activities`)
        .then(res => {
          this.amdal = res.data.amdal;
          this.uklupl = res.data.uklupl;
          this.onprogress = res.data.onprogress;
        })
        .finally(() => {
          this.loading = false;
        });
      return;
    },
  },
};
</script>
