<template>
  <div style="padding: 24px" class="app-container">
    <el-row class="form-container">
      <el-col
        :span="12"
      ><h2>Informasi rencana Usaha/Kegiatan</h2>
        <el-table
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
    <el-table
      :data="feedbacks"
      style="width: 100%"
      :stripe="true"
      :show-header="false"
    >
      <el-table-column prop="id" />
      <el-table-column prop="created_at" />
      <el-table-column prop="name" />
      <el-table-column prop="responder_type_id" />
      <el-table-column prop="description" />
      <el-table-column prop="is_relevant" />
    </el-table>
  </div>
</template>

<script>
import Resource from '@/api/resource';
// import { fetchList } from '@/api/feedback';
const districtResource = new Resource('districts');
const feedbackResource = new Resource('feedbacks');
const projectResource = new Resource('projects');

export default {
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      project: {},
      list: [],
      feedbacks: [],
    };
  },
  mounted() {
    const id = this.$route.params && this.$route.params.id;
    this.getAllData(id);
  },
  methods: {
    async getAllData(id){
      // this.project = {
      //   id: id,
      //   description: 'Dummy Description',
      //   location_desc: 'Dummy Location Desc',
      //   id_district: 35,
      // };
      this.getKegiatan(id);
      this.getFeedbacks(id);
    },
    async getFeedbacks(id){
      // filter by project ID
      const { data } = await feedbackResource.list({
        projectId: id,
      });
      this.feedbacks = data;
      // fetchList({})
      //   .then(response => {
      //     console.log(response.data);
      //     this.feedbacks = response.data;
      //   })
      //   .catch(err => {
      //     console.log(err);
      //   });
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
  },
};
</script>
