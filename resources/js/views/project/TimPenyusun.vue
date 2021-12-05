<template>
  <div class="form-container" style="margin: 24px">
    <el-card>
      <h3>Daftar Tim Penyusun Kegiatan</h3>
      <el-form
        ref="categoryForm"
        label-position="top"
        label-width="200px"
        style="max-width: 100%"
      >
        <el-row :gutter="32">
          <el-col :sm="12" :md="14">
            <el-form-item prop="teamType">
              <el-select
                v-model="teamType"
                filterable
                placeholder="Pilih Tim Penyusun"
                style="width: 100%"
                :disabled="isTeamExist"
              >
                <el-option
                  v-for="team in timPenyusun"
                  :key="team.value"
                  :label="team.label"
                  :value="team.value"
                />
              </el-select>
            </el-form-item>
            <el-form-item v-if="teamType == 'mandiri'" prop="teamName">
              <el-input v-model="teamName" :readonly="true" />
            </el-form-item>
            <el-form-item v-if="teamType === 'mandiri'">
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
            <el-form-item v-if="teamType === 'lpjp'">
              <el-row :gutter="32">
                <el-col :sm="12" :md="isTeamExist ? 24 : 20">
                  <el-select
                    v-model="selectedLPJP"
                    filterable
                    placeholder="Lembaga Tim LPJP"
                    style="width: 100%"
                    :disabled="isTeamExist"
                  >
                    <el-option
                      v-for="member in lpjp"
                      :key="member.id"
                      :label="member.name"
                      :value="member.id"
                    />
                  </el-select>
                </el-col>
                <el-col :sm="12" :md="2">
                  <el-button
                    v-if="!isTeamExist"
                    type="primary"
                    @click.prevent="handleSaveLPJP"
                  >
                    Simpan
                  </el-button>
                </el-col>
              </el-row>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <h4>Daftar Penyusun</h4>
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
            v-if="teamType === 'mandiri'"
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
        <el-button
          :loading="loadingSubmit"
          type="warning"
          @click="handleSubmit"
        >
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
import TimPenyusunTable from './table/TimPenyusunTable.vue';
import TimAhliTable from './table/TimAhliTable.vue';
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
      teamType: '',
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
      isTeamExist: false,
      deletedPenyusun: [],
      deletedAhli: [],
      membersAhli: [],
      formulators: [],
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
  created() {
    this.getProjectName();
    this.getLpjp();
    this.getTimPenyusun();
    this.getTimAhli();
  },
  methods: {
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
      if (data.type_team) {
        this.teamType = data.type_team;
        this.isTeamExist = true;
        this.selectedLPJP = data.id_lpjp;
      }
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
    async handleSaveLPJP() {
      await formulatorTeamsResource.store({
        idProject: this.$route.params.id,
        lpjp: this.selectedLPJP,
        type: 'lpjp',
      });
      this.isTeamExist = true;
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
    async handleSubmit() {
      const formData = new FormData();
      formData.append('members', JSON.stringify(this.members));
      formData.append('membersAhli', JSON.stringify(this.membersAhli));
      formData.append('idProject', this.$route.params.id);
      formData.append('team_type', this.teamType);
      formData.append('deletedPenyusun', JSON.stringify(this.deletedPenyusun));
      formData.append('deletedAhli', JSON.stringify(this.deletedAhli));
      let num = 0;
      this.membersAhli.map((mem) => {
        formData.append(`file-${num}`, mem.cv);
        num++;
      });
      this.loadingSubmit = true;
      await formulatorTeamsResource.store(formData);
      this.isTeamExist = true;
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
      if (typeMember === 'update') {
        const idx = this.members.findIndex((mem) => mem.num === num);
        this.deletedPenyusun.push(this.members[idx].id);
      }
      const oldData = [...this.members];
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
