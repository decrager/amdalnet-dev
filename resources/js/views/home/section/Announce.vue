<template>
  <section id="announce" ref="announce" class="announce section_data">
    <div class="announce__container container">
      <div class="announce__data">
        <h2 class="section__title announce__title">Daftar Pengumuman & Informasi</h2>
        <span class="announce__description">Daftar Pengumuman dan Informasi terkait kegiatan yang mengajukan izin lingkungan</span>
      </div>
      <AnnouncementTabs />
      <InitPopup :show="showInitPopup" />
    </div>
  </section>
</template>

<script>
import AnnouncementTabs from '../components/TabsAnnouncement.vue';
import InitPopup from '../components/InitPopup.vue';

export default {
  name: 'AnnouncementHome',
  components: {
    AnnouncementTabs,
    InitPopup,
  },
  data() {
    return {
      showInitPopup: false,
    };
  },
  mounted() {
    this.showPengumumanOnInit();
  },
  methods: {
    showPengumumanOnInit() {
      setTimeout(() => {
        this.showInitPopup = true;
      }, 3000);
    },
  },
};
</script>

