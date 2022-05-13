<template>
  <div class="form-container" style="margin: 24px">
    <el-card v-loading="loading">
      <h3>Daftar Penyusun {{ lpjp.name }}</h3>
      <div style="text-align: right; margin-bottom: 7px">
        <el-button type="primary" @click="handleAdd">Tambah Anggota</el-button>
      </div>
      <MemberTable :list="members" @handleDelete="handleDelete($event)" />
      <div style="text-align: right; margin-top: 12px">
        <el-button
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
const lpjpResource = new Resource('lpjp');
import MemberTable from '@/views/lpjp/components/MemberTable.vue';

export default {
  components: {
    MemberTable,
  },
  props: {},
  data() {
    return {
      lpjp: {},
      members: [],
      deletedMembers: [],
      loading: false,
      loadingSubmit: false,
    };
  },
  created() {
    this.getData();
  },
  methods: {
    async getData() {
      this.loading = true;
      const data = await lpjpResource.list({
        member: 'true',
        idLpjp: this.$route.params.id,
      });
      this.lpjp = data.lpjp;
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
      await lpjpResource.store({
        member: 'true',
        members,
        deletedMembers: this.deletedMembers,
        idLpjp: this.$route.params.id,
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
  },
};
</script>

<style scoped>
.card-footer {
  margin-top: 2rem;
}
</style>
