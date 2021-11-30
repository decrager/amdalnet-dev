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
            <el-form-item prop="name">
              <el-select v-model="selectedFormulatorTeams" filterable placeholder="Pilih Tim Penyusun" style="width: 100%" @change="getLpjp()">
                <el-option
                  v-for="team in formulatorTeams"
                  :key="team.id"
                  :label="team.name"
                  :value="team.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item>
              <el-row :gutter="32">
                <el-col :sm="12" :md="20">
                  <el-select v-model="selectedMember" filterable placeholder="Lembaga Tim LPJP" style="width: 100%">
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
      <h4>Daftar Kegiatan</h4>
      <project-lpjp-table />

    </el-card>
  </div>
</template>

<script>
import ProjectLpjpTable from './components/ProjectLpjpTable.vue';
import axios from 'axios';

export default {
  components: {
    ProjectLpjpTable,

  },
  data() {
    return {
      selectedFormulatorTeams: null,
      selectedMember: null,
      formulatorTeams: [],
      lpjp: [],
    };
  },
  created() {
    this.getFormulatorTeam();
  },
  methods: {
    getFormulatorTeam() {
      axios.get('api/form-teams')
        .then((response) => {
          this.formulatorTeams = response.data;
        });
    },
    getLpjp() {
      axios.get('api/lpjp', {
        params: { id: this.formulatorTeams },
      })
        .then((response) => {
          this.lpjp = response.data;
        }).bind(this);
    },
  },
};
</script>

<style scoped>
.card-footer {
  margin-top: 2rem;
}
</style>
