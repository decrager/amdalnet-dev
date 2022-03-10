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
      idProject: this.$route.params.id,
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
  created() {
    this.$store.dispatch('getStep', 5);
  },
};
</script>
