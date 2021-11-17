<template>
  <!-- <el-input
    v-model="textarea"
    type="textarea"
    :rows="25"
    placeholder="Please input"
  /> -->
  <iframe
    src="/document/Template_Dokumen_RKL_RPL.pdf"
    frameborder="0"
    width="100%"
    height="630px"
  />
</template>

<script>
export default {
  name: 'DocsFrame',
  data() {
    return {
      textarea: '',
    };
  },
};
</script>
