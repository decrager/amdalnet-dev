<template>
  <div class="app-container">
    <el-card>
      <WorkFlow />
      <el-tabs v-model="activeName" type="card">
        <el-tab-pane label="Rekap uji Kelayakan" name="rekap">
          <Rekap
            v-if="activeName === 'rekap'"
            @updateIsFeasib="updateIsFeasib($event)"
          />
        </el-tab-pane>
        <el-tab-pane
          v-if="isFeasib"
          label="Surat Rekomendasi Kelayakan"
          name="suratrekomendasi"
        >
          <SuratRekomendasi v-if="activeName === 'suratrekomendasi'" />
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const feasibilityTestResource = new Resource('feasibility-test');
import WorkFlow from '@/components/Workflow';
import Rekap from '@/views/pengujian-rkl-rpl/components/rekomendasi/Rekap';
import SuratRekomendasi from '@/views/pengujian-rkl-rpl/components/rekomendasi/SuratRekomendasi';

export default {
  name: 'RekomendasiUjiKelayakan',
  components: {
    WorkFlow,
    Rekap,
    SuratRekomendasi,
  },
  data() {
    return {
      activeName: 'rekap',
      isFeasib: false,
      isAmdal: false,
    };
  },
  created() {
    this.isAmdal = this.$route.name === 'ujiKelayakanAmdal';
    this.$store.dispatch('getStep', 6);
    this.checkRecap();
  },
  methods: {
    async checkRecap() {
      this.isFeasib = await feasibilityTestResource.list({
        checkRecap: 'true',
        idProject: this.$route.params.id,
      });
    },
    updateIsFeasib({ accept }) {
      this.isFeasib = accept;
    },
  },
};
</script>
