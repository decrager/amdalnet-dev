<template>
  <div class="dashboard-container">
    <!-- <component :is="currentRole" /> -->
    <admin-dashboard v-if="isAdmin || isExaminerSecretary || isExaminer" />
    <user-dashboard v-else-if="isFormulator || isInitiator" />
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
import AdminDashboard from './admin';

export default {
  name: 'Dashboard',
  // components: { adminDashboard, editorDashboard },
  components: { UserDashboard, AdminDashboard },
  data() {
    return {
      currentRole: 'adminDashboard',
    };
  },
  computed: {
    ...mapGetters([
      'roles',
    ]),
    isAdmin(){
      return (this.$store.getters.roles[0].split('-')[0] === 'admin');
    },
    isFormulator(){
      return this.$store.getters.roles.includes('formulator');
    },
    isInitiator(){
      return this.$store.getters.roles.includes('initiator');
    },
    isExaminer(){
      return (this.$store.getters.roles[0].split('-')[0] === 'examiner');
    },
    isExaminerSecretary() {
      return this.$store.getters.roles.includes('examiner-secretary');
    },
  },
  created() {
    // if (!this.roles.includes('admin')) {
    //   this.currentRole = 'editorDashboard';
    // }
  },
  mounted(){
    console.log('dashboard: ', this.isExaminer);
  },
};
</script>
