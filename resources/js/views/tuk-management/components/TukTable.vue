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
            <p><b>Nomor Telepon: </b>{{ scope.row.phone }}</p>
            <p><b>Email: </b>{{ scope.row.email }}</p>
            <p><b>Ketua: </b>{{ getChairman(scope.row) }}</p>
          </div>
          <div class="expand-container__right">
            <el-button type="primary" @click="handleKelolaTuk(scope.row.id)">
              Kelola TUK
            </el-button>
          </div>
        </div>
      </template>
    </el-table-column>

    <el-table-column align="center" label="No" width="100">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Nama TUK" prop="name" sortable />

    <el-table-column align="center" label="Nomor Penetapan">
      <template slot-scope="scope">
        <span>{{ scope.row.assignment_number }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Alamat">
      <template slot-scope="scope">
        <span>
          {{ scope.row.address }}
        </span>
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
          @click="
            handleDelete(
              scope.row.id,
              'Tim Uji Kelayakan ' +
                checkAuthority(
                  scope.row.authority,
                  scope.row.province_authority,
                  scope.row.district_authority
                )
            )
          "
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
      if (mySentence) {
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
      }

      return '';
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
      this.$router.push({ name: 'editTuk', params: { id } });
    },
    handleDelete(id, nama) {
      const capitalizeName = this.capitalizeMethod(nama);
      this.$emit('handleDelete', { id, nama: capitalizeName });
    },
    download(url) {
      window.open(url, '_blank').focus();
    },
    checkAuthority(authority, province, district) {
      if (authority === 'Pusat') {
        return 'Pusat';
      } else if (authority === 'Provinsi') {
        if (province !== null) {
          return 'Provinsi ' + province.name;
        }
      } else if (authority === 'Kabupaten/Kota') {
        if (district !== null) {
          return district.name;
        }
      }

      return '';
    },
    capitalizeMethod(mySentence) {
      if (mySentence) {
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
      }

      return '';
    },
    handleKelolaTuk(id) {
      // eslint-disable-next-line object-curly-spacing
      this.$router.push({ name: 'kelolaTuk', params: { id } });
    },
    getChairman(tuk) {
      if (tuk.member && tuk.member.length > 0) {
        if (tuk.member[0].id_expert_bank) {
          return tuk.member[0].expert_bank.name;
        } else if (tuk.member[0].id_luk_member) {
          return tuk.member[0].luk_member.name;
        }
      }

      return '';
    },
  },
};
</script>

<style scoped>
.expand-container {
  display: flex;
  justify-content: space-around;
}
.expand-container div {
  width: 50%;
  padding: 1rem 3rem;
}
.expand-container__right {
  text-align: right;
}
</style>
