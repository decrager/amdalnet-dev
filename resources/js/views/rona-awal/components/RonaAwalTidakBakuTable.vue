<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column label="No." width="54px">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 + page * limit - limit }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Jenis Komponen" prop="component" sortable />
    <el-table-column label="Komponen Lingkungan" prop="name" sortable />
    <el-table-column label="Asal Kegiatan" prop="project_title" />

    <el-table-column label="Aksi" align="right">
      <template slot-scope="scope">
        <el-button
          type="text"
          href="#"
          icon="el-icon-edit"
          @click="formalise(scope.row.id)"
        >
          Bakukan
        </el-button>

      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'RonaAwalTidakBakuTable',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
    page: {
      type: Number,
      default: () => 1,
    },
    limit: {
      type: Number,
      default: () => 10,
    },
  },
  methods: {
    handleEditForm(id) {
      this.$emit('handleEditForm', id);
    },
    handleDelete(id, nama) {
      this.$emit('handleDelete', { id, nama });
    },
    formalise(id){
      this.$emit('setMaster', id);
    },
  },
};
</script>
