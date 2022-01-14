<template>
  <el-form label-position="top" label-width="200px" style="max-width: 100%">
    <el-row :gutter="32">
      <el-col :sm="24" :md="24">
        <el-form-item label="Nama Usaha/Kegiatan">
          <el-input v-model="reports.project_name" readonly />
        </el-form-item>
      </el-col>
      <el-col :sm="24" :md="24">
        <el-form-item label="Pemrakarsa">
          <el-select
            v-model="reports.id_initiator"
            placeholder="Pilih Tim"
            style="width: 100%"
            :disabled="true"
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
        <el-form-item label="Penanggung Jawab">
          <el-input v-model="reports.person_responsible" readonly />
        </el-form-item>
      </el-col>
      <el-col :sm="12" :md="12">
        <el-form-item label="Jabatan">
          <el-input v-model="reports.position" />
        </el-form-item>
      </el-col>
      <el-col :sm="24" :md="24">
        <el-form-item label="Tempat Rapat">
          <el-input v-model="reports.location" />
        </el-form-item>
      </el-col>
      <el-col :sm="12" :md="12">
        <el-form-item label="Tanggal Rapat">
          <el-date-picker
            v-model="reports.meeting_date"
            type="date"
            value-format="yyyy-MM-dd"
            placeholder="Pilih tanggal"
            style="width: 100%"
          />
        </el-form-item>
      </el-col>
      <el-col :sm="12" :md="12">
        <el-form-item label="Waktu Rapat">
          <el-time-picker
            v-model="reports.meeting_time"
            placeholder="Waktu Rapat"
            format="HH:mm"
            value-format="HH:mm"
            style="width: 100%"
          />
        </el-form-item>
      </el-col>
      <el-col :sm="24" :md="24">
        <el-form-item label="Tim Uji Kelayakan">
          <el-select
            v-model="reports.id_feasibility_test_team"
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
    </el-row>
  </el-form>
</template>

<script>
import Resource from '@/api/resource';
const meetingReportResource = new Resource('meeting-report');

export default {
  name: 'FormBerita',
  props: {
    reports: {
      type: Object,
      default: () => {},
    },
    loadingtuk: Boolean,
  },
  data() {
    return {
      timUjiKelayakan: [],
      pemrakarsa: [],
    };
  },
  created() {
    this.getTimUjiKelayakan();
    this.getPemrakarsa();
  },
  methods: {
    async getTimUjiKelayakan() {
      const data = await meetingReportResource.list({
        expert_bank_team: 'true',
      });
      this.timUjiKelayakan = data.map((x) => {
        let name = '';
        const team_number = x.team_number ? x.team_number : '';

        if (x.authority === 'Pusat') {
          name = 'Tim Uji Kelayakan Pusat';
        } else if (x.authority === 'Provinsi') {
          if (x.province_authority) {
            name = `Tim Uji Kelayakan Provinsi ${this.capitalize(
              x.province_authority.name
            )} ${team_number}`;
          }
        } else if (x.authority === 'Kabupaten/Kota') {
          if (x.district_authority) {
            name = `Tim Uji Kelayakan ${this.capitalize(
              x.district_authority.name
            )} ${team_number}`;
          }
        }

        return {
          id: x.id,
          name,
        };
      });
    },
    async getPemrakarsa() {
      const data = await meetingReportResource.list({ pemrakarsa: 'true' });
      this.pemrakarsa = data;
    },
    async handleChangeTimUji(val) {
      this.loadingtuk = true;
      const data = await meetingReportResource.list({
        tuk_member: 'true',
        tuk_id: val,
      });
      this.reports.invitations = data;
      this.loadingtuk = false;
    },
    capitalize(mySentence) {
      const words = mySentence.split(' ');

      const newWords = words
        .map((word) => {
          return (
            word.toLowerCase()[0].toUpperCase() +
            word.toLowerCase().substring(1)
          );
        })
        .join(' ');
      return newWords;
    },
  },
};
</script>
