<template>
  <div style="padding: 24px" class="app-container">
    <el-row class="form-container">
      <el-col
        :span="12"
      ><h2>Informasi rencana Usaha/Kegiatan</h2>
        <el-table
          ref="feedbackList"
          :data="list"
          style="width: 100%"
          :stripe="true"
          :show-header="false"
        >
          <el-table-column prop="param" />
          <el-table-column prop="value" />
        </el-table>
      </el-col>
      <el-col :span="12">
        <h1>Peta Will Be Here Soon</h1>
      </el-col>
    </el-row>
    <el-row style="padding-top: 32px">
      <el-col :span="12">
        <div><h3>Deskripsi Kegiatan</h3></div>
        <div v-html="project.description" />
      </el-col>
      <el-col :span="12">
        <div><h3>Deskripsi Lokasi</h3></div>
        <div v-html="project.location_desc" />
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="12">
        <h2>Hasil Penapisan</h2>
        <el-row
          style="padding-bottom: 16px"
        ><el-col :span="12">No Registrasi</el-col>
          <el-col :span="12">123456789</el-col></el-row>
        <el-row
          style="padding-bottom: 16px"
        ><el-col :span="12">Jenis Dokumen</el-col>
          <el-col :span="12">Dummy Jenis Dokumen</el-col></el-row>
        <el-row
          style="padding-bottom: 16px"
        ><el-col :span="12">Kewenangan</el-col>
          <el-col :span="12">Pusat</el-col></el-row>
        <el-row
          style="padding-bottom: 16px"
        ><el-col :span="12">No Registrasi</el-col>
          <el-col :span="12">Dummy No Registrasi</el-col></el-row>
      </el-col>
    </el-row>
    <div><h2>Saran/Pendapat/Tanggapan</h2></div>
    <el-table
      :data="feedbacks"
      border
      fit
      highlight-current-row
    >
      <el-table-column align="center" label="ID" width="40">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Tanggal Dibuat">
        <template slot-scope="scope">
          <span>{{ scope.row.created_at | parseTime('{y}-{m}-{d} {h}:{i}') }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Judul">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Peran">
        <template slot-scope="scope">
          <span>{{ scope.row.responder_type_name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Saran/Pendapat/Tanggapan">
        <template slot-scope="scope">
          <span v-html="scope.row.concern" />
        </template>
      </el-table-column>
      <el-table-column align="center" label="Relevan">
        <template slot-scope="scope">
          <el-select
            v-model="scope.row.is_relevant"
            placeholder="Select"
            style="width: 100%"
            @change="onChangeRelevant(scope.row.id, $event)"
          >
            <el-option
              v-for="item in relevantChoices"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Identitas">
        <template slot-scope="scope">
          <el-button
            type="info"
            size="mini"
            icon="el-icon-view"
            @click="showIdentity(scope.row.id)"
          >
            Lihat Identitas
          </el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const userResource = new Resource('users');
const districtResource = new Resource('districts');
const feedbackResource = new Resource('feedbacks');
const projectResource = new Resource('projects');
const responderTypeResource = new Resource('responder_types');

export default {
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      id: 0,
      user: {},
      project: {},
      list: [],
      feedbacks: [],
      responder_types: [],
      relevantChoices: [],
    };
  },
  mounted() {
    const id = this.$route.params && this.$route.params.id;
    this.id = id;
    this.relevantChoices = [
      { value: true, label: 'Ya' },
      { value: false, label: 'Tidak' },
    ];
    this.getAllData(id);

    // event handler
    this.$bus.$on('updateFeedbackList', event => {
      this.getFeedbacks(this.id);
    });
  },
  methods: {
    async getAllData(id){
      this.getUser();
      this.getResponderTypes();
      this.getKegiatan(id);
      this.getFeedbacks(id);
    },
    async getUser() {
      const { data } = await userResource.get(this.$store.getters.userId);
      this.user = data;
    },
    async getResponderTypes(){
      var { data } = await responderTypeResource.list({});
      this.responder_types = data;
    },
    async getFeedbacks(id){
      // filter by project ID
      var { data } = await feedbackResource.list({
        announcement_id: id,
        deleted: false,
      });
      data.map((item) => {
        const key = item.responder_type_id;
        var responder_type_name = '';
        this.responder_types.map((item) => {
          if (item.id === key){
            responder_type_name = item.name;
          }
        });
        item.responder_type_name = responder_type_name;
        item.is_relevant_str = item.is_relevant ? 'Ya' : 'Tidak';
      });
      this.feedbacks = data;
    },
    async getKegiatan(id) {
      const project = await projectResource.get(id);
      const district = await districtResource.get(project.id_district);
      this.list = [
        {
          param: 'Nama Kegiatan',
          value: project.project_title,
        },
        {
          param: 'Bidang Usaha/Kegiatan',
          value: project.field,
        },
        {
          param: 'Skala/Besaran',
          value: 'Dummy Skala Besaran',
        },
        {
          param: 'Alamat',
          value: 'Dummy alamat',
        },
        {
          param: 'Pemrakarsa',
          value: 'Dummy Pemrakarsa',
        },
        {
          param: 'Penanggung Jawab',
          value: 'Dummy Penanggung Jawab',
        },
        {
          param: 'Alamat Pemrakarsa',
          value: 'Dummy Alamat Pemrakarsa',
        },
        {
          param: 'No. Telp Pemrakarsa',
          value: '0812121212',
        },
        {
          param: 'Email Pemrakarsa',
          value: 'Pemrakarsa@Pemrakarsa.com',
        },
        {
          param: 'Provinsi/Kota',
          value: district.name,
        },
      ];
    },
    handleEdit(id) {
      this.$router.push('/feedback/edit/' + id);
    },
    showIdentity(id) {
      console.log('feedback id = ' + id);
    },
    async onChangeRelevant(feedbackId, event) {
      var toUpdate = {};
      this.feedbacks.map((item) => {
        if (item.id === feedbackId){
          toUpdate = item;
        }
      });
      feedbackResource
        .update(feedbackId, toUpdate)
        .then(response => {
          const is_relevant_str = response.data.is_relevant ? 'RELEVAN' : 'TIDAK RELEVAN';
          const message = 'SPT \'' + response.data.name + '\' berhasil diupdate sebagai ' + is_relevant_str;
          this.$message({
            message: message,
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
        });
    },
  },
};
</script>
