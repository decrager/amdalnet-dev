<template>
  <div>
    <div class="filter-container" align="right">
      <el-button
        :loading="loadingSubmit"
        type="primary"
        style="font-size: 0.8rem"
        @click="handleSubmit"
      >
        {{ 'Simpan Perubahan' }}
      </el-button>
      <el-button type="info" style="font-size: 0.8rem">{{
        'Download File DOCX'
      }}</el-button>
    </div>
    <el-row :gutter="32">
      <el-col :sm="12" :md="12">
        <FormBerita :reports="reports" :loadingtuk="loadingTuk" />
      </el-col>
      <el-col :sm="12" :md="12">
        <DaftarHadir
          :invitations="reports.invitations"
          :loadingtuk="loadingTuk"
          @deleteinvitation="deleteInvitation($event)"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const meetingReportResource = new Resource('meeting-report');
import FormBerita from '@/views/pengujian/components/beritaAcara/FormBerita';
import DaftarHadir from '@/views/pengujian/components/beritaAcara/DaftarHadir';

export default {
  name: 'BeritaAcara',
  components: {
    FormBerita,
    DaftarHadir,
  },
  data() {
    return {
      // projects: [],
      idProject: this.$route.params.id,
      loadingSubmit: false,
      loadingTuk: false,
      reports: {},
    };
  },
  created() {
    this.handleChange(this.idProject);
  },
  methods: {
    // async getProjects() {
    //   const data = await meetingReportResource.list({
    //     project: 'true',
    //   });
    //   this.projects = data;
    // },
    async getReports() {
      const data = await meetingReportResource.list({
        idProject: this.idProject,
      });
      if (data.type !== 'notexist') {
        this.reports = data;
      }
    },
    async handleChange(val) {
      this.idProject = val;
      this.loadingTuk = true;
      await this.getReports();
      this.loadingTuk = false;
    },
    async handleSubmit() {
      if (this.reports.type === undefined) {
        return;
      }

      this.loadingSubmit = true;
      await meetingReportResource.store({
        idProject: this.idProject,
        reports: this.reports,
      });
      await this.handleChange(this.idProject);
      this.$message({
        message: 'Data is saved Successfully',
        type: 'success',
        duration: 5 * 1000,
      });
      this.loadingSubmit = false;
    },
    deleteInvitation({ id, personType }) {
      this.reports.invitations = this.reports.invitations.filter((inv) => {
        return !(inv.id === id && inv.type === personType);
      });
    },
  },
};
</script>
