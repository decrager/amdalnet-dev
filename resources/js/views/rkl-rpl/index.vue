<template>
  <div class="app-container">
    <el-card>
      <WorkFlow />
      <Matriks />
    </el-card>
  </div>
</template>

<script>
import Matriks from '@/views/rkl-rpl/components/Matriks';
import WorkFlow from '@/components/Workflow';

export default {
  name: 'MatriksRKLRPL',
  components: {
    Matriks,
    WorkFlow,
  },
  data() {
    return {
      idProject: 0,
      userInfo: {
        roles: [],
      },
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
  mounted() {
    this.setProjectId();
    this.getMarking();
  },
  created() {
    this.$store.dispatch('getStep', 5);
  },
  methods: {
    async getMarking() {
      await this.$store.dispatch('getMarking', this.idProject);
    },
    setProjectId(){
      const id = this.$route.params && this.$route.params.id;
      this.idProject = id;
    },
  },
};
</script>
