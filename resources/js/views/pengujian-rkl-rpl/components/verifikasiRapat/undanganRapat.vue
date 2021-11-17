<template>
  <div>
    <h4 align="center">UNDANGAN RAPAT</h4>
    <el-form label-position="top" label-width="200px" style="max-width: 100%">
      <el-row :gutter="32">
        <el-col :sm="12" :md="12">
          <el-form-item label="Tanggal Rapat">
            <el-date-picker
              v-model="meetings.meeting_date"
              type="date"
              value-format="yyyy-MM-dd"
              placeholder="Pilih tanggal"
              style="width: 100%"
            />
          </el-form-item>
        </el-col>
        <el-col :sm="12" :md="12">
          <el-form-item label="Pemrakarsa">
            <el-select
              v-model="meetings.id_initiator"
              placeholder="Pilih Tim"
              style="width: 100%"
            >
              <el-option
                v-for="item in pemrakarsa"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
        </el-col>
        <el-col :sm="12" :md="12">
          <el-form-item label="Waktu Rapat">
            <el-time-picker
              v-model="meetings.meeting_time"
              placeholder="Waktu Rapat"
              format="HH:mm"
              value-format="HH:mm"
              style="width: 100%"
            />
          </el-form-item>
        </el-col>
        <el-col :sm="12" :md="12">
          <el-form-item label="Penanggung Jawab">
            <el-input v-model="meetings.person_responsible" />
          </el-form-item>
        </el-col>
        <el-col :sm="12" :md="12">
          <el-form-item label="Tempat Rapat">
            <el-input v-model="meetings.location" />
          </el-form-item>
        </el-col>
        <el-col :sm="12" :md="12">
          <el-form-item label="Jabatan">
            <el-input v-model="meetings.position" />
          </el-form-item>
        </el-col>
        <el-col :sm="12" :md="12">
          <el-form-item label="Tim Uji Kelayakan">
            <el-select
              v-model="meetings.expert_bank_team_id"
              placeholder="Pilih Tim"
              style="width: 100%"
              @change="handleChangeTimUji"
            >
              <el-option
                v-for="item in timUjiKelayakan"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
        </el-col>
        <el-col :sm="12" :md="12">
          <el-form-item label="Nama Usaha/Kegiatan">
            <el-input v-model="meetings.project_name" />
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <h5>Daftar Undangan</h5>
    <el-table
      v-loading="loadingTuk"
      :data="meetings.invitations"
      fit
      highlight-current-row
      :header-cell-style="{ background: '#3AB06F', color: 'white' }"
    >
      <el-table-column width="30px">
        <template slot-scope="scope">
          <span>{{ scope.$index + 1 }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Peran">
        <template slot-scope="scope">
          <span v-if="scope.row.type == 'tuk'">{{ scope.row.role }} TUK</span>
          <el-select
            v-else
            v-model="scope.row.role"
            placeholder="Pilih Peran"
            style="width: 100%"
          >
            <el-option
              v-for="item in peran"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </template>
      </el-table-column>

      <el-table-column label="Nama Anggota">
        <template slot-scope="scope">
          <span v-if="scope.row.type == 'tuk'">{{ scope.row.name }} TUK</span>
          <el-input v-else v-model="scope.row.name" />
        </template>
      </el-table-column>

      <el-table-column label="E-mail">
        <template slot-scope="scope">
          <span v-if="scope.row.type == 'tuk'">{{ scope.row.email }}</span>
          <el-input v-else v-model="scope.row.email" />
        </template>
      </el-table-column>
    </el-table>
    <div style="margin-top: 10px">
      <el-button icon="el-icon-plus" circle @click.prevent="addTableRow" />
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const undanganRapatResource = new Resource('test-meet-rkl-rpl');

export default {
  name: 'UndanganRapat',
  props: {
    meetings: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      timUjiKelayakan: [],
      pemrakarsa: [],
      peran: [
        {
          label: 'Tenaga Ahli',
          value: 'Tenaga Ahli',
        },
        {
          label: 'Masyarakat',
          value: 'Masyarakat',
        },
      ],
      loadingTuk: false,
    };
  },
  created() {
    this.getTimUjiKelayakan();
    this.getPemrakarsa();
  },
  methods: {
    async getTimUjiKelayakan() {
      const data = await undanganRapatResource.list({
        expert_bank_team: 'true',
      });
      this.timUjiKelayakan = data;
    },
    async getPemrakarsa() {
      const data = await undanganRapatResource.list({ pemrakarsa: 'true' });
      this.pemrakarsa = data;
    },
    async handleChangeTimUji(val) {
      this.loadingTuk = true;
      const data = await undanganRapatResource.list({
        tuk_member: 'true',
        tuk_id: val,
      });
      this.meetings.invitations = data;
      this.loadingTuk = false;
    },
    addTableRow() {
      this.meetings.invitations.push({
        id: null,
        role: null,
        name: null,
        email: null,
        type: 'other',
      });
    },
  },
};
</script>
