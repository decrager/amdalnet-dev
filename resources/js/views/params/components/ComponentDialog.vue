<template>
  <el-dialog :title="'Buat Komponen'" :visible.sync="show" :close-on-click-modal="false" :show-close="false">
    <el-table
      :data="listView"
      border
      style="width: 100%"
    >
      <el-table-column label="No." width="180">
        <template slot-scope="scope">
          <span>{{ scope.$index + 1 }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Judul" width="180">
        <template slot-scope="scope">
          <span>{{ scope.row.title }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Address">
        <template slot-scope="scope">
          <span>{{ scope.row.value }}</span>
        </template>
      </el-table-column>
    </el-table>
    <div slot="footer" class="dialog-footer">
      <el-button @click="handleCancelComponent()"> Cancel </el-button>
      <el-button type="primary" @click="handleSubmitComponent()"> Confirm </el-button>
    </div>
  </el-dialog>
</template>

<script>
export default {
  name: 'ComponentDialog',
  props: {
    component: {
      type: Object,
      default: () => {},
    },
    show: Boolean,
    options: {
      type: Array,
      default: () => [],
    },
    listView: {
      type: Array,
      default: () => [],
    },
  },
  data(){
    return {
      fileName: 'File Not Found',
    };
  },
  methods: {
    handleSubmitComponent() {
      this.$emit('handleSubmitComponent');
    },
    handleCancelComponent() {
      this.$emit('handleCancelComponent');
    },
  },
};
</script>
