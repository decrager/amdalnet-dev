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
        'Kirim Undangan Rapat'
      }}</el-button>
    </div>
    <el-row :gutter="32">
      <el-col :sm="12" :md="12">
        <FormKelengkapan
          :list="verifications"
          :loadingverification="loadingverification"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const verifikasiRapatResource = new Resource('testing-verification');
const undanganRapatResource = new Resource('testing-meeting');
import FormKelengkapan from '@/views/pengujian/components/verifikasiRapat/formKelengkapan';
// import UndanganRapat from '@/views/pengujian/components/verifikasiRapat/undanganRapat';

export default {
  name: 'VerifikasiRapat',
  components: {
    FormKelengkapan,
  },
  data() {
    return {
      idProject: this.$route.params.id,
      verifications: {},
      loadingverification: false,
      loadingSubmit: false,
    };
  },
  created() {
    this.getVerifications();
  },
  methods: {
    async getVerifications() {
      this.loadingverification = true;
      const data = await verifikasiRapatResource.list({
        verification: 'true',
        idProject: this.idProject,
      });

      this.verifications = data;
      this.loadingverification = false;
    },
    async getMeetings() {
      const data = await undanganRapatResource.list({
        idProject: this.idProject,
      });
      this.meetings = data;
    },
    async handleSubmit() {
      if (this.idProject == null) {
        return;
      }

      this.loadingSubmit = true;
      await verifikasiRapatResource.store({
        idProject: this.idProject,
        verifications: this.verifications,
      });
      await undanganRapatResource.store({
        idProject: this.idProject,
        meetings: this.meetings,
      });
      await this.handleChange(this.idProject);
      this.$message({
        message: 'Data is saved Successfully',
        type: 'success',
        duration: 5 * 1000,
      });
      this.loadingSubmit = false;
    },
  },
};
</script>
