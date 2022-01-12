<template>
  <el-dialog
    :visible.sync="show"
    width="40%"
    :before-close="handleClose"
  >
    <span slot="title" style="font-size: 14pt; font-weight: bold;">Edit KBLI</span>
    <business-form
      :is-edit="true"
      :id-business="idBusiness"
      @handleClose="handleClose"
    />
  </el-dialog>
</template>
<script>
import BusinessForm from './forms/BusinessForm.vue';

export default {
  name: 'EditBusiness',
  components: { BusinessForm },
  props: {
    show: Boolean,
    idBusiness: {
      type: Number,
      default: () => 0,
    },
  },
  methods: {
    handleClose() {
      this.$emit('handleClose');
    },
  },
};
</script>
