<template>
  <div style="border: 1px solid #ccc; border-radius: 4px; height: 36px">
    <el-button
      icon="el-icon-document-copy"
      type="primary"
      size="mini"
      style="margin-left: 15px"
      @click="checkFile"
    >Upload</el-button>
    <el-tooltip :content="getContent" placement="top">
      <span>
        {{ name }}
      </span>
    </el-tooltip>
    <input
      :id="fid"
      type="file"
      style="display: none"
      @change="handleFileUpload"
    >
  </div>
</template>

<script>
export default {
  name: 'ClassicUpload',
  props: {
    name: {
      type: String,
      default: () => 'No File Choosen',
    },
    fid: {
      type: String,
      default: () => (Math.random() + 1).toString(36).substring(7),
    },
  },
  data() {
    return {};
  },
  computed: {
    getContent(){
      return this.name === 'No File Choosen' ? 'Mohon Unggah Data Anda' : 'Data Anda Sudah Diunggah';
    },
  },
  methods: {
    handleFileUpload(e){
      this.$emit('handleFileUpload', e);
    },
    checkFile(){
      document.querySelector('#' + this.fid).click();
    },
  },
};
</script>
