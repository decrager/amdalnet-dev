<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column align="center" label="No." width="54px">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 + page * limit - limit }}</span>
      </template>
    </el-table-column>

    <el-table-column
      align="center"
      label="Nomor Registrasi"
      prop="registration_no"
      sortable
    />
    <el-table-column
      align="center"
      label="Nama Rencana Usaha/Kegiatan"
      prop="project_title"
      sortable
    />
    <el-table-column
      align="center"
      label="Pemrakarsa"
      prop="initiator_name"
      sortable
    />
    <el-table-column
      align="center"
      label="Lokasi Kegiatan"
      prop="complete_address"
      sortable
    />

    <el-table-column align="center" label="Anggota Uji Kelayakan">
      <template slot-scope="scope">
        <el-button type="warning" @click="handleTukProjectMember(scope.row.id)">
          Ubah
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'TukProjectTable',
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
    handleTukProjectMember(id) {
      // eslint-disable-next-line object-curly-spacing
      this.$router.push({ name: 'tukProjectMember', params: { id } });
    },
  },
};
</script>
