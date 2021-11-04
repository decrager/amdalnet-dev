<template>
  <el-dialog :title="'Parameter Detail'" :visible.sync="show" :close-on-click-modal="false" :show-close="false">
    <h3>Nama Parameter : {{ listViewTitle }}</h3>
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
      <el-table-column label="Nilai">
        <template slot-scope="scope">
          <span>{{ scope.row.value }}</span>
        </template>
      </el-table-column>
    </el-table>
    <div slot="footer" class="dialog-footer">
      <el-button @click="handleCancelComponent()">Tutup</el-button>
    </div>
  </el-dialog>
</template>

<script>
export default {
  name: 'ComponentDialog',
  props: {
    show: Boolean,
    listView: {
      type: Array,
      default: () => [],
    },
    listViewTitle: String,
  },
  data(){
    return {
    };
  },
  methods: {
    handleCancelComponent() {
      this.$emit('handleCancelComponent');
    },
  },
};
</script>
