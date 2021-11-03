<template>
  <el-dialog :title="'Identitas'" :visible.sync="show" :close-on-click-modal="false" :show-close="true">
    <div class="form-container">
      <div><img :src="photo" width="400px"></div>
      <el-table
        ref="feedbackList"
        :data="data"
        style="width: 100%"
        :stripe="true"
        :show-header="false"
      >
        <el-table-column prop="param" />
        <el-table-column prop="value" />
      </el-table>
      <el-button
        type="danger"
        size="mini"
        icon="el-icon-circle-close"
        @click="closeDialog()"
      >
        Tutup
      </el-button>
    </div>
  </el-dialog>
</template>
<script>

export default {
  name: 'IdentityDialog',
  props: {
    data: {
      type: Array,
      default: () => [],
    },
    photo: {
      type: String,
      default: () => '',
    },
    show: Boolean,
  },
  methods: {
    closeDialog() {
      this.$emit('handleCloseDialog');
    },
  },
};
</script>
