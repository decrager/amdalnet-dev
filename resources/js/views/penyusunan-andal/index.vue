<!-- eslint-disable vue/html-indent -->
<template>
  <div class="app-container">
    <el-card>
      <WorkFlow />
      <el-tabs v-model="activeName" v-loading="loading" type="card">
        <el-tab-pane
          v-if="!isInitiator"
          label="Formulir Kerangka Acuan"
          name="formulir-ka"
        >
          <FormulirKA v-if="activeName === 'formulir-ka' && !loading" />
        </el-tab-pane>
        <el-tab-pane
          v-if="!isInitiator"
          label="Analisa Dampak Lingkungan"
          name="andal"
        >
          <Andal v-if="activeName === 'andal' && !loading" />
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const cloneResource = new Resource('andal-clone');
const andalComposingResource = new Resource('andal-composing');
import Andal from '@/views/penyusunan-andal/components/Andal';
import FormulirKA from '@/views/penyusunan-andal/components/FormulirKA';
import WorkFlow from '@/components/Workflow';

export default {
  name: 'PenyusunanAndal',
  components: {
    Andal,
    WorkFlow,
    FormulirKA,
  },
  data() {
    return {
      idProject: this.$route.params.id,
      compose: [],
      lastTime: null,
      activeName: 'formulir-ka',
      loading: false,
      showDocument: false,
    };
  },
  computed: {
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
    isInitiator() {
      return this.$store.getters.roles.includes('initiator');
    },
  },
  created() {
    this.checkExist();
    this.$store.dispatch('getStep', 4);
  },
  methods: {
    async checkExist() {
      this.loading = true;
      await cloneResource.list({
        exist: 'true',
        idProject: this.$route.params.id,
      });
      const isInitiator = this.$store.getters.roles.includes('initiator');
      if (isInitiator) {
        this.activeName = 'dokumen-andal';
      }
      this.loading = false;
    },
    handleSubmitTanggapan() {
      this.handleSubmit();
    },
    async checkDocument() {
      this.showDocument = await andalComposingResource.list({
        checkDocument: 'true',
        idProject: this.$route.params.id,
      });
    },
  },
};
</script>
