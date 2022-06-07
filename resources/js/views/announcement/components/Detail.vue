<template>
  <div>
    <div
      style="display: flex; align-items: center; justify-content: space-between"
    >
      <h2 v-if="isInitiator">SPT Dari Masyarakat</h2>
      <h2 v-else>SPT Masyarakat {{ projectTitle }}</h2>
      <el-input
        v-if="!isInitiator"
        v-model="search"
        style="flex-grow: 0; flex-shrink: 0; flex-basis: 30%"
        suffix-icon="el-icon search"
        placeholder="Pencarian..."
        @input="inputSearch"
      >
        <el-button slot="append" icon="el-icon-search" />
      </el-input>
    </div>
    <!-- <div v-if="showProjectDetail">
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
    </div> -->
    <div>
      <FeedbackList ref="feedbacklist" :disable-rating="true" />
    </div>
    <div v-if="isInitiator">
      <el-alert
        v-if="publicConst.id"
        title="Konsultasi Publik Telah Diterima"
        type="success"
        description="Terimakasih Sudah Mengirimkan Konsultasi Publik"
        show-icon
        center
        :closable="false"
      />
      <PublicConsultationForm v-else />
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import { mapGetters } from 'vuex';
import FeedbackList from '@/views/feedback/components/List.vue';
import PublicConsultationForm from '@/views/public-consultation/components/Form.vue';
const announcementResource = new Resource('announcements');
const publicConsultations = new Resource('public-consultations');
// const districtResource = new Resource('districts');

export default {
  name: 'AnnouncementDetail',
  components: { FeedbackList, PublicConsultationForm },
  // props: {
  //   showProjectDetail: Boolean,
  //   showFeedbackList: Boolean,
  //   showPublicConsultation: Boolean,
  // },
  data() {
    return {
      id: 0,
      announcement: {},
      announcementDetails: [],
      publicConst: {},
      projectTitle: null,
      search: null,
      timeoutId: null,
    };
  },
  computed: {
    ...mapGetters({
      userInfo: 'user',
    }),
    isInitiator() {
      return this.userInfo.roles.includes('initiator');
    },
  },
  mounted() {
    const id = this.$route.params && this.$route.params.id;
    this.id = parseInt(id);
    this.getAnnouncement();
  },
  methods: {
    async getAnnouncement() {
      const data = await announcementResource.get(this.id);
      this.publicConst = await publicConsultations.list({
        idProject: data.project_id,
      });
      console.log(this.publicConst);
      // const district = await districtResource.get(data.project.id_district);
      // data.district = district;
      this.announcement = data;
      this.projectTitle = data.project.project_title;
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
          value: data.district,
        },
      ];
    },
    inputSearch(val) {
      if (this.timeoutId) {
        clearTimeout(this.timeoutId);
      }
      this.timeoutId = setTimeout(() => {
        this.$refs.feedbacklist.getFeedbacks(this.search);
      }, 500);
    },
  },
};
</script>
