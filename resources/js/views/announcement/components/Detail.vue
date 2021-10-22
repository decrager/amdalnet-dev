<template>
  <div style="padding: 24px" class="app-container">
    <div v-if="showProjectDetail">
      <el-row class="form-container">
        <el-col
          :span="12"
        ><h2>Informasi rencana Usaha/Kegiatan</h2>
          <el-table
            ref="announcementDetails"
            :data="announcementDetails"
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
          <div v-html="announcement.project.description" />
        </el-col>
        <el-col :span="12">
          <div><h3>Deskripsi Lokasi</h3></div>
          <div v-html="announcement.project.location_desc" />
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
    </div>
    <div v-if="showFeedbackList">
      <div><h2>Saran/Pendapat/Tanggapan</h2></div>
      <FeedbackList
        :announcement="announcement"
        :feedbacks="feedbacks"
        :responder-types="responder_types"
      />
    </div>
    <div v-if="showPublicConsultation">
      <PublicConsultationForm />
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import FeedbackList from '@/views/feedback/components/List.vue';
import PublicConsultationForm from '@/views/public-consultation/components/Form.vue';
const announcementResource = new Resource('announcements');
const feedbackResource = new Resource('feedbacks');
const responderTypeResource = new Resource('responder_types');
const districtResource = new Resource('districts');

export default {
  name: 'AnnouncementDetail',
  components: { FeedbackList, PublicConsultationForm },
  props: {
    showProjectDetail: Boolean,
    showFeedbackList: Boolean,
    showPublicConsultation: Boolean,
  },
  data() {
    return {
      id: 0,
      announcement: {},
      announcementDetails: [],
      feedbacks: [],
      responder_types: [],
    };
  },
  mounted() {
    const id = this.$route.params && this.$route.params.id;
    this.id = parseInt(id);
    this.getAnnouncement();
    this.getResponderTypes();
    this.getFeedbacks(this.id);
  },
  methods: {
    async getAnnouncement() {
      const data = await announcementResource.get(this.id);
      const district = await districtResource.get(data.project.id_district);
      data.district = district;
      this.announcement = data;
      this.announcementDetails = [
        {
          param: 'Nama Kegiatan',
          value: data.project.project_title,
        },
        {
          param: 'Bidang Usaha/Kegiatan',
          value: data.project_type,
        },
        {
          param: 'Skala/Besaran',
          value: data.project.scale_unit,
        },
        {
          param: 'Alamat',
          value: data.project_location,
        },
        {
          param: 'Pemrakarsa',
          value: 'Dummy Pemrakarsa',
        },
        {
          param: 'Penanggung Jawab',
          value: data.pic_name,
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
          value: data.district.name,
        },
      ];
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
        item.is_relevant_str = item.is_relevant ? 'Relevan' : 'Tidak Relevan';
      });
      this.feedbacks = data;
    },
    async getResponderTypes(){
      var { data } = await responderTypeResource.list({});
      this.responder_types = data;
    },
  },
};
</script>
