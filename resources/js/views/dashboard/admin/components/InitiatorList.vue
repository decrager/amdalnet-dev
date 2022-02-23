<template>
  <el-card class="box-card">
    <div style="margin: 0 0 2em 0; font-weight:bold;">Daftar Pemrakarsa Usaha/Kegiatan</div>
    <transaction-table
      :list="initiators"
      :loading="loading"
      :page="listQuery.page"
      :limit="listQuery.limit"
    />
    <pagination
      v-show="total > 0"
      :total="total"
      :page.sync="listQuery.page"
      :limit.sync="listQuery.limit"
      @pagination="getInitiators"
      @click.prevent
    />
  </el-card>
</template>
<script>
import { mapGetters } from 'vuex';
import axios from 'axios';
import Pagination from '@/components/Pagination';
import TransactionTable from './TransactionTable.vue';

export default {
  name: 'InitiatorList',
  components: {
    TransactionTable,
    Pagination,
  },
  data() {
    return {
      initiators: [],
      loading: false,
      listQuery: {
        page: 1,
        limit: 5,
      },
      total: 0,
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
    this.getInitiators();
  },
  methods: {
    async getInitiators() {
      this.loadingInitiators = true;
      const dataType = this.isExaminerSecretary ? 'tuk' : '';
      axios.get(`/api/dashboard/initiator?type=${dataType}&id_user=${this.$store.getters.userId}&page=${this.listQuery.page}&limit=${this.listQuery.limit}`)
        .then(data => {
          this.initiators = data.data.data;
          this.total = data.data.total;
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
          this.loading = false;
        });
    },
  },
};
</script>
