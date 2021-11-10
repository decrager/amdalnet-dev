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
              v-model="tanggapan"
              type="textarea"
              :rows="2"
              placeholder="Isi Tanggapan"
            />
          </el-form-item>
        </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="handleCancelTanggapan()"> Batal </el-button>
        <el-button type="primary" @click="handleSubmitTanggapn()">
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
  methods: {
    handleSubmitTanggapan() {
      this.$emit('handleSubmitTanggapan');
    },
    handleCancelTanggapan() {
      this.$emit('handleCancelTanggapan');
    },
  },
};
</script>
