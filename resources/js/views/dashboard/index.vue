<template>
  <div class="dashboard-container">
    <!-- <component :is="currentRole" /> -->
    <admin-dashboard v-if="isAdmin || isExaminerSecretary || isExaminer" />
    <user-dashboard v-else-if="isFormulator || isInitiator || isLPJP" />
    <pustanling-dashboard v-else-if="isPustanling" />
    <lsp-dashboard v-else-if="isLSP" />
    <!-- <lsp-dashboard v-else-if="isLSP" /> -->
    <!--
    <examiner-dashboard v-if="isExaminer" />
      -->
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
// import adminDashboard from './admin';
// import editorDashboard from './editor';
import UserDashboard from './user';
import LspDashboard from './lsp';
import AdminDashboard from './admin';
import PustanlingDashboard from './pustanling';

export default {
  name: 'Dashboard',
  // components: { adminDashboard, editorDashboard },
  components: { UserDashboard, AdminDashboard, PustanlingDashboard, LspDashboard },
  data() {
    return {
      currentRole: 'adminDashboard',
    };
  },
  computed: {
    ...mapGetters(['roles']),
    isAdmin() {
      return this.$store.getters.roles[0].split('-')[0] === 'admin';
    },
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
    isLSP() {
      return this.$store.getters.roles.includes('lsp');
    },
    isInitiator() {
      return this.$store.getters.roles.includes('initiator');
    },
    isExaminer() {
      return this.$store.getters.roles[0].split('-')[0] === 'examiner';
    },
    isExaminerSecretary() {
      return this.$store.getters.roles.includes('examiner-secretary');
    },
    isLPJP() {
      return this.$store.getters.roles.includes('lpjp');
    },
    isPustanling() {
      return this.$store.getters.roles.includes('pustanling');
    },
  },
  created() {
    // if (!this.roles.includes('admin')) {
    //   this.currentRole = 'editorDashboard';
    // }
  },
  mounted() {
    console.log('dashboard: ', this.isExaminer);
  },
};
</script>
