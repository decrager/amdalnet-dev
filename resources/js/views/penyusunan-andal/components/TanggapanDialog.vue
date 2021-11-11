<template>
  <el-dialog
    :title="'Tanggapan'"
    :visible.sync="show"
    :close-on-click-modal="false"
    :show-close="false"
  >
    <div class="form-container">
      <el-form ref="categoryForm">
        <el-row>
          <el-form-item label="Tanggapan" prop="tanggapan">
            <el-input
              v-model="newTanggapan"
              type="textarea"
              :rows="2"
              placeholder="Isi Tanggapan"
            />
          </el-form-item>
        </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="handleCancelTanggapan()"> Batal </el-button>
        <el-button type="primary" @click="handleSubmitTanggapan()">
          Simpan
        </el-button>
      </div>
    </div>
  </el-dialog>
</template>

<script>
export default {
  name: 'TanggapanDialog',
  props: {
    tanggapan: {
      type: String,
      default: () => '',
    },
    show: Boolean,
  },
  data() {
    return {
      newTanggapan: '',
    };
  },
  watch: {
    tanggapan() {
      this.newTanggapan = this.tanggapan;
    },
  },
  methods: {
    handleSubmitTanggapan() {
      this.$emit('handleSubmitTanggapan', {
        tanggapan: this.newTanggapan,
      });
    },
    handleCancelTanggapan() {
      this.$emit('handleCancelTanggapan');
    },
  },
};
</script>
