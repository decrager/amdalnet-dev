<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Kegiatan Terbaru</span>
      </div>
      <div v-if="((list === null) || (list.length === 0) && !isLoading)">
        <el-empty :image-size="200" />
      </div>
      <div v-else>
        <el-skeleton v-if="isLoading" :rows="6" animated />
        <template v-for="(project, idx) in list" v-else>
          <el-row :key="'user_project_' + idx" class="item">
            <el-col :span="20">
              <span class="title">{{ project.title }}</span>
              <span class="location">{{ project.location.toLowerCase() }}</span>
              <span class="registration">{{ project.registration_no }}</span>
              <span :class="'doctype ' + project.doc">{{ project.doc }}</span>

            </el-col>
            <el-col :span="4" style="text-align:right;">
              <el-button :class="'button-'+project.doc" circle />
            </el-col>
          </el-row>
        </template>
      </div>
    </el-card>
  </div>
</template>
<script>
import Resource from '@/api/resource';
const activitiesResource = new Resource('latest-activities');
export default {
  name: 'UserActivities',
  props: {
    user: {
      type: Object,
      default: null,
    },
  },
  data(){
    return {
      isLoading: false,
      list: [],
    };
  },
  computed: {
    isFormulator(){
      return this.$store.getters.roles.includes('formulator');
    },
    isInitiator(){
      return this.$store.getters.roles.includes('initiator');
    },
    isExaminer(){
      return this.$store.getters.roles[0].split('-')[0] === 'examiner';
    },
  },
  watch: {
    user: function(val) {
      console.log('user? ', val);
      this.getData();
    },
  },
  mounted(){
    // this.getData();
  },
  methods: {
    async getData(){
      let param = null;
      if (this.isInitiator) {
        param = { initiatorId: this.user.id };
      }
      if (this.isFormulator) {
        param = { formulatorId: this.user.id };
      }
      await activitiesResource.list(param).then((res) => {
        if (res.data){
          const activities = [];
          res.data.forEach((d, idx) => {
            console.log(d);
            activities.push({
              title: d.project_title,
              location: (d.address.length > 0) ? d.address[0].district + ', ' + d.address[0].prov : d.district + ', ' + d.prov,
              doc: d.required_doc,
              registration_no: d.registration_no,
            });
          });
          this.list = activities;
        }
      }).finally({

      });
    },
  },
};
</script>
<style lang="scss" scoped>
.item {
  padding: 0.5em ;
  border: 1px solid #e0e0e0;
  margin: 0 0 1em;
  border-radius: 0.5em;

  span {
    display: block;
    line-height: 150%;
  }
  .title { font-weight: bold; }
  .location { font-size:80%; text-transform: capitalize;}
  .registration { font-size:76%; font-weight:bold; margin-bottom: 1em; text-transform: uppercase;}
  .doctype {
    display: inline-block;
    font-weight: 500;
    color: white;
    padding: 0.5em;
    border-radius: 0.5em;
    font-size:80%;
    letter-spacing: 0.08em;
  }

  .button-AMDAL, .AMDAL{
    background: #347437;
  }
  .button-UKL-UPL, .UKL-UPL{
    background: #449748;
  }
  .button-SPPL, .SPPL {
    background: #EB8A00;
  }
  &:last-child {
    margin-bottom: 0;
  }
}
</style>
