<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Kegiatan Terbaru</span>
      </div>
      <template v-for="(project, idx) in list">
        <el-row :key="'user_project_' + idx" class="item">
          <el-col :span="20">
            <span class="title">{{ project.title }}</span>
            <span class="location">{{ project.location }}</span>
            <span :class="'doctype ' + project.doc">{{ project.doc }}</span>
          </el-col>
          <el-col :span="4" style="text-align:right;">
            <el-button :class="'button-'+project.doc" circle />
          </el-col>
        </el-row>
      </template>
    </el-card>
  </div>
</template>
<script>
export default {
  name: 'UserActivities',
  data(){
    return {
      list: [
        { title: 'Pembangunan Pabrik Pupuk Organik', location: 'Semarang - Jawa Tengah', doc: 'AMDAL' },
        { title: 'Pembangunan Gudang Pupuk Organik', location: 'Sidoarjo - Jawa Timur', doc: 'UKL-UPL' },
        { title: 'Pembangunan Lahan Parkir', location: 'Semarang - Jawa Tengah', doc: 'SPPL' },
      ],
    };
  },

};
</script>
<style lang="scss" scoped>
.item {
  padding: 1em ;
  border: 1px solid #e0e0e0;
  margin: 1em 0;
  border-radius: 0.5em;

  span {
    display: block;
    line-height: 150%;
  }
  .title { font-weight: bold; }
  .location { font-size:80%; margin-bottom: 1em;}
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
}
</style>
