<template>
  <div class="form-container" style="margin: 24px">
    <el-card>
      <h3>Tambah Tim Penyusun LPJP</h3>
      <el-form
        ref="categoryForm"
        label-position="top"
        label-width="200px"
        style="max-width: 100%"
      >
        <el-row :gutter="32">
          <el-col :sm="12" :md="14">
            <el-form-item prop="teamName">
              <el-input v-model="teamName" :readonly="true" />
            </el-form-item>
            <el-form-item>
              <el-row :gutter="32">
                <el-col :sm="12" :md="20">
                  <el-select
                    v-model="selectedMember"
                    filterable
                    placeholder="Pilih Penyusun"
                    style="width: 100%"
                  >
                    <el-option
                      v-for="item in formulators"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"
                    />
                  </el-select>
                </el-col>
                <el-col :sm="12" :md="2">
                  <el-button
                    type="primary"
                    icon="el-icon-plus"
                    @click.prevent="handleAdd"
                  >
                    Tambah
                  </el-button>
                </el-col>
              </el-row>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <h4>Daftar Penyusun</h4>
      <el-alert
        v-if="compositionError"
        title="Tim Penyusun harus terdiri dari 1 Ketua dan minimal 2 Anggota"
        type="error"
        effect="dark"
        :closable="false"
        style="margin-bottom: 10px"
      />
      <TimPenyusunTable
        :loading="loadingTimPenyusun"
        :list="members"
        :teamtype="teamType"
        @handleDelete="handleDeletePenyusun($event)"
      />
      <div style="display: flex; align-items: center">
        <h4>Daftar Tim Ahli</h4>
        <div style="margin-left: 2rem">
          <el-button
            size="mini"
            type="primary"
            icon="el-icon-plus"
            @click.prevent="handleAddAhli"
          >
            Tambah
          </el-button>
        </div>
      </div>
      <TimAhliTable
        :loading="loadingTimAhli"
        :list="membersAhli"
        :teamtype="teamType"
        @handleDelete="handleDeleteAhli($event)"
      />
      <div
        v-if="teamType === 'mandiri'"
        style="text-align: right; margin-top: 12px"
      >
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
const formulatorTeamsResource = new Resource('formulator-teams');
import TimPenyusunTable from '@/views/project/table/TimPenyusunTable.vue';
import TimAhliTable from '@/views/project/table/TimAhliTable.vue';
// import ProjectLpjpTable from './components/ProjectLpjpTable.vue';
// import axios from 'axios';

export default {
  components: {
    // ProjectLpjpTable,
    TimPenyusunTable,
    TimAhliTable,
  },
  props: {},
  data() {
    return {
      teamType: 'mandiri',
      teamName: null,
      selectedFormulatorTeams: null,
      selectedMember: null,
      selectedLPJP: null,
      formulatorTeams: [],
      formulatorMember: [],
      searchResult: '',
      lpjp: [],
      loadingTimPenyusun: false,
      loadingTimAhli: false,
      loadingSubmit: false,
      idLpjp: 0,
      members: [],
      deletedPenyusun: [],
      deletedAhli: [],
      membersAhli: [],
      formulators: [],
      compositionError: false,
      timPenyusun: [
        {
          label: 'LPJP',
          value: 'lpjp',
        },
        {
          label: 'Mandiri',
          value: 'mandiri',
        },
      ],
    };
  },
  async created() {
    this.loadingTimPenyusun = true;
    this.loadingTimAhli = true;
    await this.getProjectName();
    await this.getFormulators();
    await this.getLpjp();
    await this.getTimPenyusun();
    await this.getTimAhli();
  },
  methods: {
    async getFormulators() {
      this.formulators = await formulatorTeamsResource.list({
        type: 'formulator',
        lpjp: 'true',
        idLpjp: this.selectedLPJP,
      });
    },
    async getTimPenyusun() {
      this.loadingTimPenyusun = true;
      const timPenyusun = await formulatorTeamsResource.list({
        type: 'tim-penyusun',
        idProject: this.$route.params.id,
      });
      this.members = timPenyusun;
      this.loadingTimPenyusun = false;
    },
    async getTimAhli() {
      this.loadingTimAhli = true;
      const timPenyusun = await formulatorTeamsResource.list({
        type: 'tim-ahli',
        idProject: this.$route.params.id,
      });
      this.membersAhli = timPenyusun;
      this.loadingTimAhli = false;
    },
    async getProjectName() {
      const data = await formulatorTeamsResource.list({
        type: 'project',
        idProject: this.$route.params.id,
      });
      this.teamName = data.name;
      this.selectedLPJP = data.id_lpjp;
    },
    async querySearch(queryString, cb) {
      const results = await formulatorTeamsResource.list({
        type: 'search',
        search: queryString,
      });
      const finalResults = results.map((dat) => {
        return {
          num:
            this.members.length === 0
              ? 1
              : this.members[this.members.length - 1].num + 1,
          value: dat.name,
          name: dat.name,
          id: dat.id,
          type: 'new',
          position: 'Anggota',
          expertise: dat.expertise,
          file: dat.cv ? dat.cv : dat.cv_file,
          reg_no: dat.reg_no,
          membership_status: dat.membership_status,
        };
      });
      cb(finalResults);
    },
    async getLpjp() {
      this.lpjp = await formulatorTeamsResource.list({ type: 'lpjp' });
    },
    handleSelect(item) {
      this.selectedMember = item;
    },
    handleAdd() {
      if (this.selectedMember) {
        const member = this.formulators.find(
          (form) => form.id === this.selectedMember
        );
        this.members.push({
          num:
            this.members.length === 0
              ? 1
              : this.members[this.members.length - 1].num + 1,
          value: member.name,
          name: member.name,
          id: member.id,
          type: 'new',
          position: 'Anggota',
          expertise: member.expertise,
          file: member.cv ? member.cv : member.cv_file,
          reg_no: member.reg_no,
          membership_status: member.membership_status,
        });
        this.formulators = this.formulators.filter((x) => {
          return x.id !== this.selectedMember;
        });
        this.selectedMember = null;
      }
    },
    handleAddAhli() {
      const newAhli = {
        num:
          this.membersAhli.length === 0
            ? 1
            : this.membersAhli[this.membersAhli.length - 1].num + 1,
        id: null,
        name: '',
        type: 'new',
        status: 'Tenaga Ahli',
        expertise: '',
        cv: null,
      };
      this.membersAhli.push(newAhli);
    },
    checkSubmit() {
      this.compositionError = false;
      const ketua = this.members.filter((mem) => mem.position === 'Ketua');
      const anggota = this.members.filter((mem) => mem.position === 'Anggota');

      if (ketua.length === 1 && anggota.length >= 2) {
        this.$confirm('Apakah anda yakin ?', 'Warning', {
          confirmButtonText: 'Ya',
          cancelButtonText: 'Tidak',
          type: 'warning',
        })
          .then(() => {
            this.handleSubmit();
          })
          .catch(() => {
            this.$message({
              type: 'info',
              message: 'Simpan data dibatalkan',
            });
          });
      } else {
        this.$alert(
          'Tim Penyusun harus terdiri dari 1 Ketua dan minimal 2 Anggota',
          '',
          {
            center: true,
          }
        );
      }
    },
    async handleSubmit() {
      const formData = new FormData();
      formData.append('members', JSON.stringify(this.members));
      formData.append('membersAhli', JSON.stringify(this.membersAhli));
      formData.append('idProject', this.$route.params.id);
      formData.append('team_type', 'lpjp');
      formData.append('deletedPenyusun', JSON.stringify(this.deletedPenyusun));
      formData.append('deletedAhli', JSON.stringify(this.deletedAhli));
      let num = 0;
      this.membersAhli.map((mem) => {
        formData.append(`file-${num}`, mem.cv);
        num++;
      });
      this.loadingSubmit = true;
      await formulatorTeamsResource.store(formData);
      this.loadingSubmit = false;
      this.$message({
        message: 'Data sukses disimpan',
        type: 'success',
        duration: 5 * 1000,
      });
      this.getTimPenyusun();
      this.getTimAhli();
    },
    handleDeletePenyusun({ typeMember, num }) {
      const idx = this.members.findIndex((mem) => mem.num === num);
      if (typeMember === 'update') {
        this.deletedPenyusun.push(this.members[idx].id);
      }
      const oldData = [...this.members];
      const member = this.members[idx];
      this.formulators.push({
        id: member.id,
        name: member.name,
        expertise: member.expertise,
        cv: member.file,
        cv_file: member.file,
        reg_no: member.reg_no,
        membership_status: member.membership_status,
      });
      this.formulators.sort((a, b) => {
        const nameA = a.name.toUpperCase();
        const nameB = b.name.toUpperCase();
        if (nameA < nameB) {
          return -1;
        }
        if (nameA > nameB) {
          return 1;
        }

        return 0;
      });
      this.members = oldData.filter((old) => old.num !== num);
    },
    handleDeleteAhli({ typeMember, num }) {
      if (typeMember === 'update') {
        const idx = this.membersAhli.findIndex((mem) => mem.num === num);
        this.deletedAhli.push(this.membersAhli[idx].id);
      }
      const oldData = [...this.membersAhli];
      this.membersAhli = oldData.filter((old) => old.num !== num);
    },
  },
};
</script>

<style scoped>
.card-footer {
  margin-top: 2rem;
}
</style>
