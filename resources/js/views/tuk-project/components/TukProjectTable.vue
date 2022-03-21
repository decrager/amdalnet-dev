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

    <el-table-column align="center" label="Nomor Registrasi">
      <template slot-scope="scope">
        <span>{{ scope.row.registration_no }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Nama Rencana Usaha/Kegiatan">
      <template slot-scope="scope">
        <span>{{ scope.row.project_title }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Pemrakarsa">
      <template slot-scope="scope">
        <span>{{ scope.row.initiator.name }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Lokasi Kegiatan">
      <template slot-scope="scope">
        <span>{{ getAddress(scope.row.address) }}</span>
      </template>
    </el-table-column>

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
    getAddress(address) {
      if (address) {
        if (address.length > 0) {
          return `${address[0].address}, ${this.capitalize(
            address[0].district
          )}, Provinsi ${this.capitalize(address[0].prov)}`;
        }
      }

      return '';
    },
    capitalize(mySentence) {
      const words = mySentence.split(' ');

      const newWords = words
        .map((word) => {
          return (
            word.toLowerCase()[0].toUpperCase() +
            word.toLowerCase().substring(1)
          );
        })
        .join(' ');
      return newWords;
    },
    handleTukProjectMember(id) {
      // eslint-disable-next-line object-curly-spacing
      this.$router.push({ name: 'tukProjectMember', params: { id } });
    },
  },
};
</script>
