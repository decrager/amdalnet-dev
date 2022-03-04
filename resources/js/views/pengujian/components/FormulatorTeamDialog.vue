<template>
  <el-table
    v-loading="loading"
    :data="formulators"
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
        <el-button
          type="text"
          size="medium"
          icon="el-icon-download"
          style="color: blue"
          @click.prevent="download(scope.row.certificate)"
        >
          Sertifikat
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import Resource from '@/api/resource';
const formulatorTeamsResource = new Resource('formulator-teams');

export default {
  name: 'FormulatorTeamDialog',
  data() {
    return {
      formulators: [],
      loading: false,
    };
  },
  created() {
    this.getFormulators();
  },
  methods: {
    async getFormulators() {
      this.loading = true;
      const timPenyusun = await formulatorTeamsResource.list({
        type: 'tim-penyusun',
        idProject: this.$route.params.id,
      });
      this.formulators = timPenyusun;
      this.loading = false;
    },
    download(url) {
      window.open(url, '_blank').focus();
    },
  },
};
</script>
