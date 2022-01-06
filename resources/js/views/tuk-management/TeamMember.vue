<template>
  <div class="form-container" style="margin: 24px">
    <el-card>
      <h3>Tim Uji Kelayakan Dokumen Lingkungan Hidup</h3>
      <el-form
        ref="categoryForm"
        label-position="top"
        label-width="200px"
        style="max-width: 100%"
      >
        <el-row :gutter="32">
          <el-col :sm="12" :md="14">
            <el-form-item label="Nama Tim Uji Kelayakan">
              <el-input
                v-model="teamName"
                :readonly="true"
                style="width: 100%"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="32">
          <el-col :sm="12" :md="14">
            <el-form-item label="Kewenangan">
              <el-input
                v-model="authority"
                :readonly="true"
                style="width: 100%"
              />
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <div
        style="
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 5px;
        "
      >
        <h4>Daftar Anggota Tim Uji Kelayakan</h4>
        <div>
          <el-button type="primary" icon="el-icon-plus" @click="handleAdd">
            Tambah Anggota
          </el-button>
        </div>
      </div>
      <MemberTable
        :loading="loading"
        :list="members"
        :expertemployee="expertEmployee"
        :deletedtuk="deletedTuk"
        @handleDelete="handleDelete($event)"
      />
      <div style="text-align: right; margin-top: 12px">
        <el-button :loading="loadingSubmit" type="warning" @click="checkSubmit">
          Simpan
        </el-button>
      </div>
      <!-- <project-lpjp-table :selected-member="idLpjp" /> -->
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const tukManagementResource = new Resource('tuk-management');
const formulatorTeamsResource = new Resource('formulator-teams');
import MemberTable from '@/views/tuk-management/components/MemberTable.vue';

export default {
  components: {
    MemberTable,
  },
  props: {},
  data() {
    return {
      teamName: null,
      authority: null,
      expertEmployee: [],
      loadingSubmit: false,
      loading: false,
      members: [],
      deletedTuk: [],
      compositionError: false,
    };
  },
  created() {
    this.getTeamData();
    this.getExpertAndEmployee();
    this.getTeamMember();
  },
  methods: {
    async getTeamData() {
      const data = await tukManagementResource.list({
        type: 'teamDataMember',
        idTeam: this.$route.params.id,
      });
      this.teamName = data.team_name;
      this.authority = data.authority;
    },
    async getExpertAndEmployee() {
      this.expertEmployee = await tukManagementResource.list({
        type: 'expertEmployee',
        idTUK: this.$route.params.id,
      });
    },
    async getTeamMember() {
      this.loading = true;
      this.members = await tukManagementResource.list({
        type: 'members',
        id: this.$route.params.id,
      });
      this.loading = false;
    },
    async handleSaveLPJP() {
      this.loadingLpjp = true;
      await formulatorTeamsResource.store({
        idProject: this.$route.params.id,
        lpjp: this.selectedLPJP,
        type: 'lpjp',
      });
      this.loadingLpjp = false;
      this.isTeamExist = true;
      this.$message({
        message: 'Data sukses disimpan',
        type: 'success',
        duration: 5 * 1000,
      });
    },
    handleSelect(item) {
      this.selectedMember = item;
    },
    handleAdd() {
      this.members.push({
        num:
          this.members.length === 0
            ? 1
            : this.members[this.members.length - 1].num + 1,
        id: null,
        id_member: null,
        idTeam: this.$route.params.id,
        type: null,
        nik: null,
        role: null,
        position: null,
        institution: null,
        cv: null,
      });
    },
    checkSubmit() {
      //   this.compositionError = false;
      //   const ketua = this.members.filter((mem) => mem.position === 'Ketua');
      //   const anggota = this.members.filter((mem) => mem.position === 'Anggota');

      //   if (ketua.length === 1 && anggota.length >= 2) {
      //     this.handleSubmit();
      //   } else {
      //     this.$alert(
      //       'Tim Penyusun harus terdiri dari 1 Ketua dan minimal 2 Anggota',
      //       '',
      //       {
      //         center: true,
      //       }
      //     );
      //   }

      const emptyMember = this.members.filter((mem) => mem.id_member === null);
      const emptyRole = this.members.filter((mem) => mem.role === null);

      if (emptyMember.length > 0) {
        this.$message({
          message: 'Anggota Wajib Dipilih',
          type: 'error',
          duration: 5 * 1000,
        });
        return;
      }

      if (emptyRole.length > 0) {
        this.$message({
          message: 'Peran Wajib Dipilih',
          type: 'error',
          duration: 5 * 1000,
        });
        return;
      }

      this.handleSubmit();
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      await tukManagementResource.store({
        member: true,
        members: this.members,
        deleted: this.deletedTuk,
      });
      this.loadingSubmit = false;
      this.deletedTuk = [];
      this.getTeamMember();
      this.$message({
        message: 'Data sukses disimpan',
        type: 'success',
        duration: 5 * 1000,
      });
    },
    handleDelete({ id, num }) {
      if (id) {
        this.deletedTuk.push(id);
      }
      const oldData = [...this.members];
      this.members = oldData.filter((old) => old.num !== num);
    },
  },
};
</script>

<style scoped>
.card-footer {
  margin-top: 2rem;
}
</style>
