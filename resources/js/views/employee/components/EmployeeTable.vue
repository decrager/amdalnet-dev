<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column type="expand">
      <template slot-scope="scope">
        <div class="expand-container">
          <div>
            <p><b>NIK: </b>{{ scope.row.nik }}</p>
            <p><b>Email: </b>{{ scope.row.email }}</p>
            <p><b>Jenis Kelamin: </b>{{ scope.row.sex }}</p>
            <p><b>No. Telepon: </b>{{ scope.row.phone }}</p>
            <p>
              <b>Alamat: </b>{{ scope.row.address }},
              {{ scope.row.district.name | capitalize }},
              {{ scope.row.province.name | capitalize }}
            </p>
          </div>
        </div>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Nama">
      <template slot-scope="scope">
        <span>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Jabatan">
      <template slot-scope="scope">
        <span>{{ scope.row.position }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="NIP">
      <template slot-scope="scope">
        <span>{{ scope.row.nip }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="File">
      <template slot-scope="scope">
        <span v-if="scope.row.cv">
          <el-button
            type="text"
            icon="el-icon-download"
            @click="download(scope.row.cv)"
          >
            CV
          </el-button>
        </span>
        <span v-else>-</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Aksi">
      <template slot-scope="scope">
        <el-button
          type="text"
          href="#"
          icon="el-icon-edit"
          @click="handleEdit(scope.row.id)"
        >
          Ubah
        </el-button>
        <el-button
          type="text"
          href="#"
          icon="el-icon-delete"
          @click="handleDelete(scope.row.id, scope.row.name)"
        >
          Hapus
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'EmployeeTable',
  filters: {
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
  },
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
  },
  methods: {
    handleEdit(id) {
      // eslint-disable-next-line object-curly-spacing
      this.$router.push({ name: 'editEmployeeTuk', params: { id } });
    },
    handleDelete(id, nama) {
      this.$emit('handleDelete', { id, nama });
    },
    download(url) {
      window.open(url, '_blank').focus();
    },
  },
};
</script>

<style scoped>
/* .expand-container {
  display: flex;
  justify-content: space-around;
} */
.expand-container div {
  width: 50%;
  padding: 1rem 3rem;
}
.expand-container__right {
  text-align: right;
}
</style>
