<template>
  <div class="form-container" style="margin: 24px">
    <el-card v-loading="loading">
      <h3>Daftar Penyusun {{ lsp.lsp_name }}</h3>
      <div style="text-align: right; margin-bottom: 7px">
        <el-button v-if="checkRole(['lsp'])" type="primary" @click="handleAdd">Tambah Anggota</el-button>
      </div>
      <MemberTable
        :list="members"
        :options="componentOptions"
        @membershipStatusFilter="onMembershipStatusFilter"
        @handleDelete="handleDelete($event)"
        @sort="onTableSort"
      />
      <div style="text-align: right; margin-top: 12px">
        <el-button
          v-if="checkRole(['lsp'])"
          :loading="loadingSubmit"
          type="warning"
          @click="handleSubmit"
        >
          Simpan
        </el-button>
      </div>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import MemberTable from '@/views/lsp/components/MemberTable.vue';
import checkRole from '@/utils/role';
const lspResource = new Resource('lsp');

export default {
  components: {
    MemberTable,
  },
  props: {
    componentOptions: {
      type: Array,
      default: () => [
        {
          value: 'ATPA',
          label: 'ATPA',
        },
        {
          value: 'KTPA',
          label: 'KTPA',
        },
      ],
    },
  },
  data() {
    return {
      lsp: {},
      members: [],
      deletedMembers: [],
      loading: false,
      loadingSubmit: false,
      listQuery: {},
    };
  },
  created() {
    this.getData();
  },
  methods: {
    checkRole,
    handleFilter() {
      this.getData();
    },
    async getData() {
      this.loading = true;
      const data = await lspResource.list({
        member: 'true',
        idLsp: this.$route.params.id,
        order: this.listQuery.order,
        orderBy: this.listQuery.orderBy,
        membershipStatus: this.listQuery.membershipStatus,
      });
      this.lsp = data.lsp;
      this.members = data.members;
      this.loading = false;
    },
    handleAdd() {
      this.members.push({
        num:
          this.members.length === 0
            ? 1
            : this.members[this.members.length - 1].num + 1,
        id: null,
        name: null,
        reg_no: null,
        cert_no: null,
        membership_status: null,
        cert_file: null,
        cv_file: null,
        type: 'new',
        date_start: null,
        date_end: null,
      });
    },
    handleDelete({ id, type, num }) {
      if (type === 'update') {
        this.deletedMembers.push({
          id,
        });
      }
      const newMember = this.members.filter((x) => x.num !== num);
      this.members = newMember;
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      const members = this.members
        .filter((x) => x.type === 'new' && x.id !== null)
        .map((y) => y.id);
      await lspResource.store({
        member: 'true',
        members,
        deletedMembers: this.deletedMembers,
        idLsp: this.$route.params.id,
      });
      this.getData();
      this.$message({
        message: 'Data sukses disimpan',
        type: 'success',
        duration: 5 * 1000,
      });
      this.loadingSubmit = false;
      this.deletedMembers = [];
    },
    onMembershipStatusFilter(val) {
      // console.log('membershipStatus: ', val);
      // this.listQuery.filter = val; idProjectStage
      this.listQuery.membershipStatus = val;
      this.handleFilter();
    },
    onTableSort(sort) {
      this.listQuery.order = sort.order;
      this.listQuery.orderBy = sort.prop;
      this.handleFilter();
    },
  },
};
</script>

<style scoped>
.card-footer {
  margin-top: 2rem;
}
</style>
