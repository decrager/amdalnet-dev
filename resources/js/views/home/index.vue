<template>
  <div>
    <header-home />
    <hero-home />
    <steps-home />
    <materi />
    <kebijakan />
    <action-home />
    <counter-home />
    <announcement-home />
    <footer-home />
    <div class="footer__rights">
      <p class="footer__copy">&#169; AMDALNET 2021. Direktorat Jenderal Planologi Kehutanan dan Tata Lingkungan.</p>
    </div>
  </div>
</template>

<script>
import HeaderHome from './section/Header.vue';
import HeroHome from './section/HeroTop.vue';
import StepsHome from './section/Steps.vue';
import Kebijakan from './section/Kebijakan.vue';
import Materi from './section/Materi.vue';
import ActionHome from './section/Action.vue';
import CounterHome from './section/Counter.vue';
import AnnouncementHome from './section/Announce.vue';
import FooterHome from './section/Footer.vue';

export default {
  name: 'HomeIndex',
  components: {
    'header-home': HeaderHome,
    'hero-home': HeroHome,
    'steps-home': StepsHome,
    'kebijakan': Kebijakan,
    'materi': Materi,
    'action-home': ActionHome,
    'counter-home': CounterHome,
    'announcement-home': AnnouncementHome,
    'footer-home': FooterHome,
  },
  created() {
    this.$store.dispatch('getKblis', { kblis: true });
  },
};
</script>

<style scoped>
@import './assets/css/style.css';
</style>
