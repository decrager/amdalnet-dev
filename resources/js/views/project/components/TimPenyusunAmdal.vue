<template>
  <div class="form-container" style="margin: 24px">
    <el-card v-if="userInfo.roles !== undefined">
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
              <label>Unggah SK Penunjukan Tim Penyusun</label>
              <el-row :gutter="32">
                <el-col :sm="12" :md="20">
                  <el-input v-model="sk.name" :readonly="true" />
                </el-col>
                <el-col :sm="12" :md="2">
                  <el-upload
                    action="#"
                    :auto-upload="false"
                    :on-change="handleUploadSk"
                    :show-file-list="false"
                  >
                    <el-button type="primary"> Unggah </el-button>
                  </el-upload>
                </el-col>
                <el-col :sm="24" :md="24">
                  <small v-if="skError" style="color: red">
                    SK Penunjukan Tim Penyusun Wajib Diunggah
                  </small>
                </el-col>
              </el-row>
            </el-form-item>
            <el-form-item v-if="teamType === 'mandiri'">
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
            <el-form-item v-if="teamType === 'lpjp'">
              <el-row :gutter="32">
                <el-col :sm="12" :md="isTeamExist ? 24 : 20">
                  <el-select
                    v-model="selectedLPJP"
                    v-loading="loadingSelectLpjp"
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
                    :loading="loadingLpjp"
                    type="primary"
                    @click.prevent="checkSaveLPJP"
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
        <el-button :loading="loadingSubmit" type="warning" @click="checkSubmit">
          Simpan
        </el-button>
      </div>
      <!-- <project-lpjp-table :selected-member="idLpjp" /> -->
    </el-card>
    <el-card v-else v-loading="true" />
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Resource from '@/api/resource';
const formulatorTeamsResource = new Resource('formulator-teams');
import TimPenyusunTable from '../table/TimPenyusunTable.vue';
import TimAhliTable from '../table/TimAhliTable.vue';
// import ProjectLpjpTable from './components/ProjectLpjpTable.vue';
// import axios from 'axios';

export default {
  components: {
    // ProjectLpjpTable,
    TimPenyusunTable,
    TimAhliTable,
  },
  props: {
    project: {
      type: Object,
      default: () => {},
    },
  },
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
      sk: {
        file: null,
        name: null,
      },
      skError: false,
      loadingTimPenyusun: false,
      loadingTimAhli: false,
      loadingSubmit: false,
      loadingLpjp: false,
      loadingSelectLpjp: false,
      idLpjp: 0,
      members: [],
      isTeamExist: false,
      deletedPenyusun: [],
      deletedAhli: [],
      membersAhli: [],
      formulators: [],
      compositionError: false,
      timPenyusun: [
        {
          label: 'Lembaga Penyedia Jasa Penyusun',
          value: 'lpjp',
        },
        {
          label: 'Penyusun Perseorangan',
          value: 'mandiri',
        },
      ],
    };
  },
  computed: {
    ...mapGetters({
      userInfo: 'user',
      userId: 'userId',
    }),
  },
  async created() {
    this.loadingSelectLpjp = true;
    this.loadingTimPenyusun = true;
    this.loadingTimAhli = true;
    this.getProjectName();
    // await this.getUserInfo();
    await this.getFormulators();
    await this.getLpjp();
    await this.getTimPenyusun();
    await this.getTimAhli();
  },
  methods: {
    async getFormulators() {
      this.formulators = await formulatorTeamsResource.list({
        type: 'formulator',
        idProject: this.$route.params.id,
      });
    },
    async getTimPenyusun() {
      this.loadingTimPenyusun = true;
      const timPenyusun = await formulatorTeamsResource.list({
        type: 'tim-penyusun',
        idProject: this.$route.params.id,
        pemrakarsa: 'true',
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
    getProjectName() {
      this.teamName = this.project.name;
      this.sk.name = this.project.sk_letter;
      if (this.project.type_team) {
        this.teamType = this.project.type_team;
        this.isTeamExist = true;
        this.selectedLPJP = this.project.id_lpjp;
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
      this.loadingSelectLpjp = true;
      this.lpjp = await formulatorTeamsResource.list({ type: 'lpjp' });
      this.loadingSelectLpjp = false;
    },
    checkSaveLPJP() {
      if (!this.selectedLPJP) {
        this.$alert('Silahkan pilih LPJP terlebih dahulu', '', {
          center: true,
        });
        return false;
      }

      this.$confirm(
        'Apakah anda yakin ? Data yang sudah disimpan, tidak dapat diubah lagi.',
        'Warning',
        {
          confirmButtonText: 'Ya',
          cancelButtonText: 'Tidak',
          type: 'warning',
        }
      )
        .then(() => {
          this.handleSaveLPJP();
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: 'Simpan data dibatalkan',
          });
        });
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
          db_id: member.id,
          email: member.email,
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
        id_formulator: null,
        type: 'new',
        position: 'Tenaga Ahli',
        expertise: '',
        cv_file: null,
      };
      this.membersAhli.push(newAhli);
    },
    checkSubmit() {
      this.skError = false;

      if (this.teamType === 'mandiri') {
        if (!this.sk.name) {
          this.skError = true;
          return false;
        }
      }

      this.compositionError = false;
      const ketua = this.members.filter((mem) => mem.position === 'Ketua');
      const anggota = this.members.filter((mem) => mem.position === 'Anggota');

      if (ketua.length === 1 && anggota.length >= 2) {
        this.$confirm(
          'Apakah anda yakin ? Data yang sudah disimpan, tidak dapat diubah lagi.',
          'Warning',
          {
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            type: 'warning',
          }
        )
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
      const members = this.members.map((x) => {
        const member = x;
        delete member.file;
        return member;
      });
      const ahliMembers = this.membersAhli.map((x) => {
        const member = x;
        delete member.cv_file;
        return member;
      });

      const formData = new FormData();
      formData.append('members', JSON.stringify(members));
      formData.append(
        'membersAhli',
        JSON.stringify(ahliMembers.filter((x) => x.id_formulator !== null))
      );
      formData.append('idProject', this.$route.params.id);
      formData.append('team_type', this.teamType);
      formData.append('deletedPenyusun', JSON.stringify(this.deletedPenyusun));
      formData.append('deletedAhli', JSON.stringify(this.deletedAhli));

      if (this.teamType === 'mandiri' && this.sk.file) {
        formData.append('skFile', await this.convertBase64(this.sk.file));
      }
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
    handleUploadSk(file, filelist) {
      if (file.raw.size > 2097152) {
        this.showFileAlert();
        return;
      }

      this.sk.file = file.raw;
      this.sk.name = file.name;
    },
    showFileAlert() {
      this.$alert('Ukuran file tidak boleh lebih dari 2 MB', '', {
        center: true,
      });
    },
    convertBase64(file) {
      return new Promise((resolve, reject) => {
        const fileReader = new FileReader();
        fileReader.readAsDataURL(file);

        fileReader.onload = () => {
          resolve(fileReader.result);
        };

        fileReader.onerror = (error) => {
          reject(error);
        };
      });
    },
  },
};
</script>

<style scoped>
.card-footer {
  margin-top: 2rem;
}
</style>
