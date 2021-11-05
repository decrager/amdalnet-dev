<template>
  <div class="form-container" style="margin: 24px">
    <el-card v-if="!project || project.project_title">
      <h3>Tim Penyusun Mandiri</h3>
      <el-form
        ref="categoryForm"
        :model="currentFormulatorTeam"
        label-position="top"
        label-width="200px"
        style="max-width: 100%"
      >
        <el-row :gutter="32">
          <el-col :sm="12" :md="14">
            <el-form-item label="Nama Tim Penyusun" prop="name">
              <el-input
                v-model="currentFormulatorTeam.name"
                placeholder="Nama Tim"
                :readonly="true"
              />
            </el-form-item>
            <el-form-item>
              <el-row :gutter="32">
                <el-col :sm="12" :md="20">
                  <el-autocomplete
                    v-model="searchResult"
                    class="inline-input"
                    :fetch-suggestions="querySearch"
                    placeholder="Please Input"
                    :trigger-on-focus="false"
                    :debounce="1000"
                    style="width: 100%"
                    @select="handleSelect"
                  />
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
      <h4>Daftar Anggota Tenaga Ahli</h4>
      <members-table :list="members" @handleDelete="handleDelete($event)" />
      <div class="card-footer" style="text-align: right">
        <el-button type="primary" @click="handleSubmit()"> Simpan </el-button>
      </div>
    </el-card>
    <el-card v-else style="text-align: center">
      <h3>Silahkan Tambah Kegiatan Baru Terlebih Dahulu</h3>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import MembersTable from '@/views/independent-formulator-team/member/components/member-table';
const formulatorTeamsResource = new Resource('formulator-teams');
const projectResource = new Resource('projects');

export default {
  name: 'DaftarAnggota',
  components: {
    MembersTable,
  },
  data() {
    return {
      currentFormulatorTeam: { name: '' },
      lpjp: [],
      teams: [],
      projects: [],
      members: [],
      searchResult: '',
      project: null,
      selectedMember: {},
    };
  },
  created() {
    this.getLastNewProject();
  },
  methods: {
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
          type: dat.status ? 'expert' : 'formulator',
          position: 'Anggota',
          expertise: dat.expertise,
          file: dat.cv ? dat.cv : dat.cv_file,
        };
      });
      cb(finalResults);
    },
    async getTeams() {
      const { data } = await formulatorTeamsResource.list({});
      this.teams = data;
    },
    async getLastNewProject() {
      const data = await projectResource.list({ lastInput: 'true' });
      this.project = data;
      if (data.project_title) {
        this.currentFormulatorTeam.name = `Tim Penyusun ${data.project_title}`;
      }
    },
    handleCancel() {
      //   this.$router.push('/master/formulator');
    },
    handleSubmit() {
      const submitData = {
        id_project: this.project.id,
        name: this.currentFormulatorTeam.name,
        members: this.members,
      };
      formulatorTeamsResource
        .store(submitData)
        .then((response) => {
          this.$message({
            message: this.currentFormulatorTeam.name + ' Berhasil Dibuat',
            type: 'success',
            duration: 5 * 1000,
          });
          this.show = false;
          this.members = [];
          this.getLastNewProject();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    handleSelect(item) {
      this.selectedMember = item;
    },
    handleAdd() {
      if (this.selectedMember.id) {
        this.members.push(this.selectedMember);
        this.selectedMember = {};
        this.searchResult = '';
      }
    },
    handleDelete({ num }) {
      const newMembers = this.members.filter((mem) => {
        return mem.num !== num;
      });
      this.members = newMembers;
    },
  },
};
</script>

<style scoped>
.card-footer {
  margin-top: 2rem;
}
</style>
