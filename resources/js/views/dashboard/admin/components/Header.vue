<template>
  <div>
    <el-button style="font-weight: bold;" :loading="loading" type="warning" @click="showHide">Daftar Rekap SPPL dan UKL-UPL Menengah Rendah</el-button>
    <div v-if="showIframe" style="position: absolute; background-color: rgb(238 238 229); z-index: 100; left: 0; right: 0; padding-top: 1rem; padding-right: 1rem; padding-left: 1rem; margin-left: 1px; height: 100%;">
      <iframe
        :src="iframeSrc"
        width="100%"
        height="100%"
        frameborder="0"
      />
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showIframe: false,
      iframeSrc: '',
    };
  },
  created() {
    if (window.location.href.includes('amdalnet-dev')) {
      this.iframeSrc = 'https://amdalnet-dev.menlhk.go.id/dashboard_mr';
    } else {
      this.iframeSrc = 'https://amdalnet.menlhk.go.id/dashboard_mr';
    }
  },
  methods: {
    showHide() {
      this.showIframe = !this.showIframe;
    },
  },
};
</script>

<style scoped>
/* .iframe-container{
   min-height: 100px;
   background: url("public/assets/gif/loader.gif") no-repeat 50% top !important;
} */
</style>
