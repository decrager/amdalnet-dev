<template>
  <div>
    <iframe
      :src="iframeSrc"
      width="100%"
      height="100%"
      frameborder="0"
      scrolling="no"
    />
  </div>
</template>

<script>
export default {
  data() {
    return {
      showIframe: false,
      iframeSrc: 'https://amdalnet-dev.menlhk.go.id/dashboard_mr',
    };
  },
  // created() {
  //   if (window.location.href.includes('amdalnet-dev')) {
  //     this.iframeSrc = 'https://amdalnet-dev.menlhk.go.id/dashboard_mr';
  //   } else {
  //     this.iframeSrc = 'https://amdalnet.menlhk.go.id/dashboard_mr';
  //   }
  // },
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
