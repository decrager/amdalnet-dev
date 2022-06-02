<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white', border:'0' }"
    @sort-change="onTableSort"
  >
    <el-table-column label="No." width="54px">
      <template slot-scope="scope">
        <span>{{ ((page - 1) * limit) + scope.$index + 1 }}</span>
      </template>
    </el-table-column>

    <el-table-column
      column-key="project_stage"
      label="Tahap Kegiatan"
    >

      <template slot="header">
        <el-select
          v-model="projectStageFilter"
          class="filter-header"
          clearable
          placeholder="Tahap Kegiatan"
          @change="onProjectStageFilter"
        >
          <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </template>

      <template slot-scope="scope">
        <span>{{ scope.row.project_stage }}</span>
      </template>
    </el-table-column>

    <el-table-column prop="name" label="Komponen Kegiatan" sortable="custom">
      <template slot-scope="scope">
        <span>{{ scope.row.name }}</span>
      </template>
    </el-table-column>
    <el-table-column prop="project_title" label="Asal" name="project_title" />

    <el-table-column label="Aksi">
      <template slot-scope="scope">
        <el-button
          type="text"
          href="#"
          icon="el-icon-edit"
          @click="standardise(scope.row.id)"
        >
          Bakukan
        </el-button>
        <!--  <el-button
          type="text"
          href="#"
          icon="el-icon-delete"
          @click="handleDelete(scope.row.id, scope.row.name)"
        >
          Hapus
        </el-button> -->
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'ComponentTableTdakBaku',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
    page: {
      type: Number,
      default: 1,
    },
    limit: {
      type: Number,
      default: 0,
    },
    options: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      projectStageFilter: '',
    };
  },
  methods: {
    handleEditForm(id) {
      this.$emit('handleEditForm', id);
    },
    handleDelete(id, nama) {
      this.$emit('handleDelete', { id, nama });
    },
    onProjectStageFilter(val){
      this.$emit('projectStageFilter', this.projectStageFilter);
    },
    onTableSort(sort){
      // console.log(sort);
      const order = (sort.order === 'ascending') ? 'ASC' : 'DESC';
      const prop = sort.prop;
      this.$emit('sort', { order: order, prop: prop });
    },
    standardise(id){
      this.$emit('setMaster', id);
    },
  },
};
</script>
<style rel="stylesheet/scss" lang="scss" scoped>
  .el-table-column{border: none;}
</style>
