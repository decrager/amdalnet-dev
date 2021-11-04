<template>
  <section id="counter" class="counter section_data">
    <div class="counter__container container">
      <div class="counter__data">
        <div class="counter__box">
          <h1 class="counter__number">{{ countAmdal }}</h1>
          <p class="counter__first__line">Persetujuan</p>
          <p class="counter__second__line">AMDAL</p>
        </div>

        <div class="counter__box">
          <h1 class="counter__number">{{ countUklupl }}</h1>
          <p class="counter__first__line">Persetujuan</p>
          <p class="counter__second__line">UKL/UPL</p>
        </div>

        <div class="counter__box">
          <h1 class="counter__number">{{ countAll }}</h1>
          <p class="counter__first__line">Persetujuan</p>
          <p class="counter__second__line">Sedang Diproses</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { mapGetters } from 'vuex';

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
  computed: {
    ...mapGetters(['countAmdal', 'countUklupl', 'countAll']),
  },
  created() {
    this.$store.dispatch('countAmdal');
    this.$store.dispatch('countUklupl');
    this.$store.dispatch('countAll');
  },
};
</script>
