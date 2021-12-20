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
      <el-col :sm="12" :md="12">
        <UndanganRapat
          :meetings="meetings"
          :loadingverification="loadingverification"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const verifikasiRapatResource = new Resource('test-verif-rkl-rpl');
const undanganRapatResource = new Resource('test-meet-rkl-rpl');
import FormKelengkapan from '@/views/pengujian-rkl-rpl/components/verifikasiRapat/formKelengkapan';
import UndanganRapat from '@/views/pengujian-rkl-rpl/components/verifikasiRapat/undanganRapat';

export default {
  name: 'VerifikasiRapat',
  components: {
    FormKelengkapan,
    UndanganRapat,
  },
  data() {
    return {
      kelengkapan: [
        {
          data: 'Komponen Kegiatan',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
        {
          data: 'Rona Lingkungan Awal',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
        {
          data: 'Matriks Identifikasi Dampak',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
        {
          data: 'Matriks UKL',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
        {
          data: 'Matriks UPL',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
        {
          data: 'SPH Peta Tapak Proyek',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
        {
          data: 'SPH Peta Batas Wilayah Studi',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
        {
          data: 'SPH Peta Batas Ekologi',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
        {
          data: 'SPH Peta Batas Sosial',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
      ],
      // projects: [],
      idProject: this.$route.params.id,
      verifications: {},
      loadingverification: false,
      meetings: {},
      loadingSubmit: false,
    };
  },
  created() {
    // this.getProjects();
    this.handleChange(this.idProject);
  },
  methods: {
    // async getProjects() {
    //   const data = await verifikasiRapatResource.list({
    //     project: 'true',
    //   });
    //   this.projects = data;
    // },
    async getVerifications() {
      const data = await verifikasiRapatResource.list({
        verification: 'true',
        idProject: this.idProject,
      });

      this.verifications = data;
    },
    async getMeetings() {
      const data = await undanganRapatResource.list({
        idProject: this.idProject,
      });
      this.meetings = data;
    },
    async handleChange(val) {
      this.idProject = val;
      this.loadingverification = true;
      await this.getVerifications();
      await this.getMeetings();
      this.loadingverification = false;
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
