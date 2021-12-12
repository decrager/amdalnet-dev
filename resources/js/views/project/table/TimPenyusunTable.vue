<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column label="No." width="54px">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Nama">
      <template slot-scope="scope">
        <span>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Nomor Registrasi">
      <template slot-scope="scope">
        <span>{{ scope.row.reg_no }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Sertifikat">
      <template slot-scope="scope">
        <span>{{ scope.row.membership_status }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Jabatan">
      <template slot-scope="scope">
        <el-select
          v-if="
            teamtype === 'mandiri' && scope.row.membership_status !== 'ATPA'
          "
          v-model="scope.row.position"
          placeholder="Pilih Jabatan"
          style="width: 100%"
        >
          <el-option
            v-for="item in position"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
        <span v-else>{{ scope.row.position }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Keahlian">
      <template slot-scope="scope">
        <span>{{ scope.row.expertise }}</span>
      </template>
    </el-table-column>

    <el-table-column label="File">
      <template slot-scope="scope">
        <el-button
          type="text"
          size="medium"
          icon="el-icon-download"
          style="color: blue"
          @click.prevent="download(scope.row.file)"
        >
          CV
        </el-button>
      </template>
    </el-table-column>

    <el-table-column
      v-if="teamtype === 'mandiri'"
      label=""
      width="80px"
      align="center"
    >
      <template slot-scope="scope">
        <el-button
          type="danger"
          size="mini"
          href="#"
          icon="el-icon-close"
          @click.prevent="handleDelete(scope.row.type, scope.row.num)"
        />
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'TimPenyusunTable',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
    teamtype: {
      type: String,
      default: () => '',
    },
  },
  data() {
    return {
      position: [
        {
          value: 'Ketua',
          label: 'Ketua',
        },
        {
          value: 'Anggota',
          label: 'Anggota',
        },
      ],
    };
  },
  methods: {
    download(url) {
      window.open(url, '_blank').focus();
    },
    handleDelete(typeMember, num) {
      this.$emit('handleDelete', { typeMember, num });
    },
  },
};
</script>
