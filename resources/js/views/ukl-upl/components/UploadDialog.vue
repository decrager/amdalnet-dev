<template>
  <el-dialog
    :title="'Upload Berkas'"
    :visible.sync="showDialog"
  >
    <el-upload
      :show-file-list="false"
      action="#"
    >
      <el-button
        size="small"
        type="primary"
      >Upload</el-button>
      <span style="color: red;">File harus berupa PDF dan maksimal berukuran 2MB.</span>
    </el-upload>
  </el-dialog>
</template>

<script>

export default {
  name: 'UploadDialog',
  props: {
    id: {
      type: Number,
      default: () => 0,
    },
  },
  data() {
    return {
      showDialog: false,
    };
  },
};
</script>
