<template>
  <el-dialog
    :title="'Simulasi Dokumen Amdal'"
    :visible.sync="show"
    :close-on-click-modal="false"
  >
    <sub-project-table :list="listSubProject" :list-kbli="getListKbli" @showparentdialog="handleChangeParentDialog" />
    <el-button
      type="primary"
      @click="handleAddSubProjectTable"
    >+</el-button>
  </el-dialog>
</template>

<script>
import SubProjectTable from '../../project/components/SubProjectTable.vue';
export default {
  name: 'AmdalSimulationDialog',
  components: { SubProjectTable },
  props: {
    show: Boolean,
  },
  data() {
    return {
      listSubProject: [],
    };
  },
  computed: {
    getListKbli() {
      return this.$store.getters.kblis;
    },
  },
  methods: {
    handleAddSubProjectTable(){
      this.listSubProject.push({});
    },
    handleChangeParentDialog(e){
      this.show = e;
    },
  },
};
</script>
