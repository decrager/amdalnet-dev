<template>
  <el-dialog
    :visible.sync="show"
    width="40%"
    :before-close="handleClose"
  >
    <span slot="title" style="font-size: 14pt; font-weight: bold;">Tambah KBLI</span>
    <business-form
      :is-edit="false"
      @handleClose="handleClose"
    />
  </el-dialog>
</template>
<script>
import BusinessForm from './forms/BusinessForm.vue';

export default {
  name: 'CreateBusiness',
  components: { BusinessForm },
  props: {
    show: Boolean,
  },
  methods: {
    handleClose() {
      this.$emit('handleClose');
    },
  },
};
</script>
