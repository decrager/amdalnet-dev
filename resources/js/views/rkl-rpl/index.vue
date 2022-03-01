<template>
  <div class="app-container">
    <el-card>
      <WorkFlow />
      <el-tabs v-model="activeName" type="card">
        <el-tab-pane v-if="!isInitiator" label="Matriks RKL RPL" name="matriks">
          <Matriks v-if="activeName === 'matriks'" />
        </el-tab-pane>
        <el-tab-pane
          v-if="showDocument && (isFormulator || isInitiator)"
          label="Dokumen RKL RPL"
          name="dokumen-rkl-rpl"
        >
          <Dokumen v-if="activeName === 'dokumen-rkl-rpl'" />
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import Matriks from '@/views/rkl-rpl/components/Matriks';
import Dokumen from '@/views/rkl-rpl/components/Dokumen';
import WorkFlow from '@/components/Workflow';
const rklResource = new Resource('matriks-rkl');

export default {
  name: 'MatriksRKLRPL',
  components: {
    Matriks,
    WorkFlow,
    Dokumen,
  },
  data() {
    return {
      idProject: this.$route.params.id,
      userInfo: {
        roles: [],
      },
      activeName: 'matriks',
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
    this.checkInitiator();
    this.checkDocument();
    this.$store.dispatch('getStep', 5);
  },
  methods: {
    checkInitiator() {
      const initiator = this.$store.getters.roles.includes('initiator');
      if (initiator) {
        this.activeName = 'dokumen-rkl-rpl';
      }
    },
    async checkDocument() {
      this.showDocument = await rklResource.list({
        checkDocument: 'true',
        idProject: this.$route.params.id,
      });
    },
  },
};
</script>
