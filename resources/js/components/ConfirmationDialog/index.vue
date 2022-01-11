<template>
  <el-dialog
    :visible.sync="show"
    width="40%"
    :before-close="handleClose"
  >
    <span slot="title" style="font-size: 14pt; font-weight: bold;">{{ title }}</span>
    <p style="color: red"><b>Peringatan!</b></p>
    <p style="color: red">{{ warningMessage }}</p>
    <p>Anda yakin {{ message }}?</p>
    <span slot="footer" class="dialog-footer">
      <el-button type="danger" @click="handleClose">Tidak</el-button>
      <el-button type="primary" @click="handleSubmit">Ya</el-button>
    </span>
  </el-dialog>
</template>

<script>
export default {
  name: 'ConfirmationDialog',
  props: {
    show: Boolean,
    message: {
      type: String,
      default: () => '',
    },
    warningMessage: {
      type: String,
      default: () => '',
    },
    idObject: {
      type: Number,
      default: () => 0,
    },
  },
  data() {
    return {
      title: 'Konfirmasi Aksi',
    };
  },
  mounted() {
  },
  methods: {
    handleSubmit() {
      this.$emit('handleSubmitConfirmation', this.idObject);
    },
    handleClose() {
      this.$emit('handleCloseConfirmation');
    },
  },
};
</script>
