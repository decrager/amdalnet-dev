<template>
  <div>
    <disclaimer @closeDialog="onCloseDisclaimer" />
    <header-home @handleSetMenu="handleSetMenu" />
    <hero-home v-if="toggleMenu" />
    <steps-home v-if="toggleMenu" />
    <materi v-if="toggleMenuMateri" />
    <kebijakan v-if="toggleMenuKebijakan" />
    <izin v-if="toggleIzin" />
    <persetujuan v-if="togglePersetujuan" />
    <ukl-menengah v-if="toggleUklMenengah" />
    <ukl-spesifik v-if="toggleUklSpesifik" />
    <Sop v-if="toggleSop" />
    <Cluster v-if="toggleCluster" />
    <action-home v-if="toggleMenu" @showAmdalDigital="handleSetMenu('AMDALDigital')" />
    <proposal-counter v-if="toggleMenu" />
    <announcement-home v-if="toggleMenu" />
    <amdal-digital v-if="toggleAD" />
    <LPJP v-if="toggleLPJP" />
    <Tentang v-if="toggleTentang" />
    <TUK v-if="toggleTUK" />
    <Penyusun v-if="togglePenyusun" />
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
import Uklm from './section/Uklm.vue';
import Ukls from './section/Ukls.vue';
import Sop from './section/Sop.vue';
import Cluster from './section/Cluster.vue';
import Persetujuan from './section/Persetujuan.vue';
import ActionHome from './section/Action.vue';
import ProposalCounter from './section/ProposalCounter.vue';
import AnnouncementHome from './section/Announce.vue';
import AmdalDigital from './amdal-digital/index.vue';
import LPJP from './lpjp/index.vue';
import TUK from './tuk/index.vue';
import Tentang from './section/Tentang.vue';
import Penyusun from './penyusun/index.vue';
import FooterHome from './section/Footer.vue';
import Disclaimer from './components/Disclaimer.vue';
import Cookies from 'js-cookie';

export default {
  name: 'HomeIndex',
  components: {
    'header-home': HeaderHome,
    'hero-home': HeroHome,
    'steps-home': StepsHome,
    'kebijakan': Kebijakan,
    'materi': Materi,
    'ukl-menengah': Uklm,
    'ukl-spesifik': Ukls,
    'Sop': Sop,
    'Cluster': Cluster,
    'izin': Izin,
    'persetujuan': Persetujuan,
    'action-home': ActionHome,
    ProposalCounter,
    'announcement-home': AnnouncementHome,
    'footer-home': FooterHome,
    AmdalDigital,
    Disclaimer,
    LPJP,
    TUK,
    Penyusun,
    Tentang,

  },
  data() {
    return {
      toggleMenuMateri: false,
      toggleMenuKebijakan: false,
      toggleIzin: false,
      toggleMenu: true,
      togglePersetujuan: false,
      toggleUklMenengah: false,
      toggleUklSpesifik: false,
      toggleSop: false,
      toggleCluster: false,
      toggleAD: false,
      toggleLPJP: false,
      togglePenyusun: false,
      toggleTentang: false,
      toggleTUK: false,
    };
  },
  created() {
    this.$store.dispatch('getKblis', { kblis: true });
  },
  mounted() {},
  methods: {
    handleSetMenu(e) {
      // going back to home
      console.log(e);
      if (e === 'LOGO') {
        this.toggleMenu = true;
        this.toggleAD = false;
        this.toggleMenuMateri = false;
        this.toggleMenuKebijakan = false;
        this.toggleLPJP = false;
        this.toggleTUK = false;
        this.togglePenyusun = false;
      } else {
        this.setAllState(e);
      }
    },
    setAllState(e){
      const materi = (e === 'MATERI') ? 'true' : 'false';
      const kebijakan = (e === 'KEBIJAKAN') ? 'true' : 'false';
      const izin = (e === 'IZIN') ? 'true' : 'false';
      const template = (e === 'TEMPLATE') ? 'true' : 'false';
      const men = (e === 'UKL_UPL_M') ? 'true' : 'false';
      const spec = (e === 'UKL_UPL_S') ? 'true' : 'false';
      const sop = (e === 'SOP') ? 'true' : 'false';
      const cluster = (e === 'CLUSTER') ? 'true' : 'false';
      const ad = (e === 'AMDALDigital') ? 'true' : 'false';
      const lpjp = (e === 'LPJP') ? 'true' : 'false';
      const tuk = (e === 'TUK') ? 'true' : 'false';
      const penyusun = (e === 'Penyusun') ? 'true' : 'false';
      const tentang = (e === 'TENTANG') ? 'true' : 'false';
      this.toggleMenuMateri = JSON.parse(materi);
      this.toggleMenuKebijakan = JSON.parse(kebijakan);
      this.toggleIzin = JSON.parse(izin);
      this.toggleMenu = false;
      this.togglePersetujuan = JSON.parse(template);
      this.toggleUklMenengah = JSON.parse(men);
      this.toggleUklSpesifik = JSON.parse(spec);
      this.toggleSop = JSON.parse(sop);
      this.toggleCluster = JSON.parse(cluster);
      this.toggleAD = JSON.parse(ad);
      this.toggleLPJP = JSON.parse(lpjp);
      this.togglePenyusun = JSON.parse(penyusun);
      this.toggleTentang = JSON.parse(tentang);
      this.toggleTUK = JSON.parse(tuk);
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
      // console.log('closing disclaimer....');
    },
  },
};
</script>

<style scoped>
@import './assets/css/style.css';
</style>
