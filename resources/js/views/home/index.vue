<template>
  <div>
    <disclaimer @closeDialog="onCloseDisclaimer" />
    <header-home @handleSetMenu="handleSetMenu" />
    <hero-home v-if="toggleMenu" />
    <steps-home v-if="toggleMenu" />
    <materi v-if="toggleMenuMateri" />
    <kebijakan v-if="toggleMenuKebijakan" />
    <izin v-if="toggleIzin" />
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
import Izin from './section/Izin.vue';
import Materi from './section/Materi.vue';
import ActionHome from './section/Action.vue';
import CounterHome from './section/Counter.vue';
import AnnouncementHome from './section/Announce.vue';
import FooterHome from './section/Footer.vue';
import Disclaimer from './components/Disclaimer.vue';
import Cookies from 'js-cookie';

export default {
  name: 'HomeIndex',
  components: {
    'header-home': HeaderHome,
    'hero-home': HeroHome,
    'steps-home': StepsHome,
    kebijakan: Kebijakan,
    materi: Materi,
    izin: Izin,
    'action-home': ActionHome,
    'counter-home': CounterHome,
    'announcement-home': AnnouncementHome,
    'footer-home': FooterHome,
    Disclaimer,
  },
  data() {
    return {
      toggleMenuMateri: false,
      toggleMenuKebijakan: false,
      toggleIzin: false,
      toggleMenu: true,
    };
  },
  created() {
    this.$store.dispatch('getKblis', { kblis: true });
  },
  mounted() {},
  methods: {
    handleSetMenu(e) {
      this.toggleMenuMateri = false;
      this.toggleMenuKebijakan = false;
      this.toggleIzin = false;
      this.toggleMenu = true;

      if (e === 'MATERI') {
        this.toggleMenuMateri = true;
        this.toggleMenuKebijakan = false;
        this.toggleIzin = false;
        this.toggleMenu = false;
      }
      if (e === 'KEBIJAKAN') {
        this.toggleMenuKebijakan = true;
        this.toggleMenuMateri = false;
        this.toggleIzin = false;
        this.toggleMenu = false;
      }
      if (e === 'IZIN') {
        this.toggleMenuKebijakan = false;
        this.toggleMenuMateri = false;
        this.toggleMenu = false;
        this.toggleIzin = true;
      }
    },
    isFirstVisit() {
      const recurring = Cookies.get('recurring');

      if (recurring) {
        return false;
      }
      return true;
    },
    onCloseDisclaimer(val) {
      Cookies.set('recurring', true, { expires: 30 * 6, path: '' });
      console.log('closing disclaimer....');
    },
  },
};
</script>

<style scoped>
@import './assets/css/style.css';
</style>
