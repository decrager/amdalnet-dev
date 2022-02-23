<template>
  <el-row :gutter="20">
    <el-col :span="6">
      <el-card v-loading="loading" class="box-card" style="background: #0A2F08; color: white;">
        <div class="header">Total Permohonan</div>
        <div class="value">{{ stats.total }}</div>
      </el-card>
    </el-col>
    <el-col :span="6">
      <!--  #216221 -->
      <el-card v-loading="loading" class="box-card" style="background: #2B743C; color: white;">
        <div class="header">Disetujui</div>
        <div class="value">{{ stats.accepted }}</div>
      </el-card>
    </el-col>
    <el-col :span="6">
      <!-- #D99F24 -->
      <el-card v-loading="loading" class="box-card" style="background: #4c9b4f; color: white;">
        <div class="header">Diproses</div>
        <div class="value">{{ stats.on_progress }}</div>
      </el-card>
    </el-col>
    <el-col :span="6">
      <!--  #7B9A76 #6F0000 -->
      <el-card v-loading="loading" class="box-card" style="background: #97B093; color: white;">
        <div class="header">Ditolak</div>
        <div class="value">{{ stats.rejected }}</div>
      </el-card>
    </el-col>
  </el-row>
</template>
<script>
import { mapGetters } from 'vuex';
import axios from 'axios';

export default {
  name: 'SubmissionStats',
  data() {
    return {
      loading: false,
      stats: {},
      period: null,
      date_start: null,
      date_end: null,
    };
  },
  computed: {
    ...mapGetters([
      'roles',
      'userId',
    ]),
    isExaminerSecretary() {
      return this.$store.getters.roles.includes('examiner-secretary');
    },
  },
  created() {
    this.getStats();
  },
  methods: {
    getStats() {
      this.loading = true;
      const dataType = this.isExaminerSecretary ? 'tuk' : '';
      let search = '';
      if (this.period) {
        search += `&period=${this.period}`;
      }
      if (this.date_start) {
        search += `&start=${this.date_start}`;
      }
      if (this.date_end) {
        search += `&end=${this.date_end}`;
      }
      axios.get(`/api/dashboard/status?type=${dataType}&id_user=${this.$store.getters.userId}${search}`)
        .then(data => {
          this.stats = data.data;
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
          this.loading = false;
        });
    },
    filterStats(filter) {
      this.period = filter.periode;
      if (filter.date_range) {
        this.date_start = filter.date_range[0];
        this.date_end = filter.date_range[1];
      }
      this.getStats();
    },
  },
};
</script>
<style scoped>
.header {
  font-weight: bold;
  letter-spacing: 0.05em;
}
.value {
  font-size:350%;
  line-height: 130%;
  margin-top: 10px;
}

</style>
