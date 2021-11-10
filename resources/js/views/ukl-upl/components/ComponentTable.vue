<template>
  <div>
    <el-table
      :data="data"
      :show-header="false"
      border
      fit
      highlight-current-row
    >
      <el-table-column align="left">
        <template slot-scope="scope">
          <el-row :gutter="4">
            <el-col :span="20" :xs="24">
              {{ scope.row.name }}
            </el-col>
            <el-col :span="4" :xs="24" align="right">
              <el-button type="danger" size="mini" icon="el-icon-close" @click="handleDelete(scope.row.id)" />
            </el-col>
          </el-row>
        </template>
      </el-table-column>
    </el-table>
    <el-row :gutter="4" style="margin-top:5px;">
      <el-col :span="24" :xs="24" align="left">
        <el-button type="success" size="mini" icon="el-icon-plus" @click="handleAddDialog()" />
      </el-col>
    </el-row>
    <component-dialog
      :component="component"
      :show="showAddDialog"
      :options="componentOptions"
      @handleSubmitComponent="handleSubmitComponent"
      @handleCancelComponent="handleCancelComponent"
    />
  </div>
</template>

<script>
import Resource from '@/api/resource';
// import Tinymce from '@/components/Tinymce';
import ComponentDialog from '@/views/component/components/ComponentDialog.vue';

const projectStageResource = new Resource('project-stages');

export default {
  name: 'ComponentTable',
  components: { ComponentDialog },
  props: {
    data: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      showAddDialog: false,
      componentOptions: [],
      component: {},
    };
  },
  mounted() {
    this.getProjectStage();
  },
  methods: {
    async getProjectStage() {
      const { data } = await projectStageResource.list({});
      this.componentOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
    handleAddDialog() {
      this.showAddDialog = true;
    },
    handleDelete(id_component) {
      this.$emit('handleDeleteComponent', id_component);
    },
    handleCancelComponent(){
      this.showAddDialog = false;
    },
    handleSubmitComponent(){
      this.component.id = null;
      this.$emit('handleUpdateComponents', this.component);
      this.showAddDialog = false;
    },
  },
};
</script>
