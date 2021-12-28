<template>
  <div>
    <header-home @handleSetMenu="handleSetMenu" />
    <hero-home v-if="toggleMenu" />
    <steps-home v-if="toggleMenu" />
    <materi v-if="toggleMenuMateri" />
    <kebijakan v-if="toggleMenuKebijakan" />
    <action-home v-if="toggleMenu" />
    <counter-home v-if="toggleMenu" />
    <announcement-home v-if="toggleMenu" />
    <footer-home />
    <div class="footer__rights">
      <p class="footer__copy">
        &#169; AMDALNET 2021. Direktorat Jenderal Planologi Kehutanan dan Tata
        Lingkungan.
      </p>
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
    kebijakan: Kebijakan,
    materi: Materi,
    'action-home': ActionHome,
    'counter-home': CounterHome,
    'announcement-home': AnnouncementHome,
    'footer-home': FooterHome,
  },
  data() {
    return {
      toggleMenuMateri: false,
      toggleMenuKebijakan: false,
      toggleMenu: true,
    };
  },
  created() {
    this.$store.dispatch('getKblis', { kblis: true });
  },
  methods: {
    handleSetMenu(e) {
      this.toggleMenuMateri = false;
      this.toggleMenuKebijakan = false;
      this.toggleMenu = true;

      if (e === 'MATERI') {
        this.toggleMenuMateri = true;
        this.toggleMenuKebijakan = false;
        this.toggleMenu = false;
      }
      if (e === 'KEBIJAKAN') {
        this.toggleMenuKebijakan = true;
        this.toggleMenuMateri = false;
        this.toggleMenu = false;
      }
    },
  },
};
</script>

<style scoped>
@import './assets/css/style.css';
</style>
