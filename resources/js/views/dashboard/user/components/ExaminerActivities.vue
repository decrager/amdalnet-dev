<template>
  <el-card type="box-card">
    <div slot="header" class="clearfix">
      <span>Kegiatan</span>
    </div>
    <el-table
      :data="list"
      :stripe="true"
      style="width: 100%"
      class="examiner-activity-list"
    >
      <el-table-column
        label="No."
        type="index"
        width="50"
      />
      <el-table-column
        prop="registration_no"
        label="No. Registrasi"
        align="center"
      />
      <el-table-column
        prop="title"
        label="Nama Kegiatan"
        width="350"
      />
      <el-table-column
        prop="initiator"
        label="Pemrakarsa"
        align="center"
      />
      <el-table-column
        prop="required_doc"
        label="Dokumen"
        align="center"
      />
      <el-table-column
        prop="location"
        label="Lokasi"
        align="center"
      />
      <el-table-column
        align="center"
        label="Tahap"
      >
        <template slot-scope="scope">
          <span style="background: #0C7AC7; color: white; margin:auto; font-weight: 500; font-size: 90%; padding: 0.5em 1em; border-radius: 1em;">
            {{ scope.row.stage }}
          </span>
        </template>
      </el-table-column>
    </el-table>

  </el-card>
</template>
<script>
import Resource from '@/api/Resource';
const activitiesResource = new Resource('latest-activities');
export default {
  name: 'ExaminerActivities',
  data(){
    return {
      list: [],
      /* list: [
        { registration_no: '1613620053CT2021', title: 'Kegiatan Pengembangan Laporan Kaliberau Dalam, Blok Sakakemang', initiator: 'PT Pembangkit Tenaga', required_doc: 'AMDAL', location: 'Sumatera Selatan, Kab. Musi Banyuasin', stage: 'syarat administrasi' },
        { registration_no: '3453620092CT2021', title: 'Instalasi Pengolahan Limbah B3', initiator: 'PLLI', required_doc: 'AMDAL', location: 'Jawa Barat, Kab. Bekasi', stage: 'Andal' },
        { registration_no: '5432620092CT2021', title: 'Budidaya Ternak Sapi', initiator: 'PT Budaya Sejahtera', required_doc: 'AMDAL', location: 'Jawa Tengah, Kota Purwokerto', stage: 'Andal' },
        { registration_no: '0928120092CT2021', title: 'Pembangunan Pabrik Semen', initiator: 'PT Semenku', required_doc: 'AMDAL', location: 'Jawa Timur, Kab. Gresik', stage: 'Andal' },
        { registration_no: '8582020092CT2021', title: 'Perkebunan Kelapa Sawit ', initiator: 'PT Kelapa Maju', required_doc: 'AMDAL', location: 'Kalimantan Selatan, Kota Pontianak', stage: 'Andal' },
      ],*/

    };
  },
  mounted(){
    this.getData();
  },
  methods: {
    async getData() {
      await activitiesResource.list().then((res) => {
        if (res.data){
          const act = [];
          res.data.forEach(d => {
            act.push({
              registration_no: d.registration_no,
              title: d.project_title,
              initiator: d.applicant,
              required_doc: d.required_doc,
              location: d.address[0].prov + ', ' + d.address[0].district,
              stage: '?' });
          });
          this.list = act;
        }
      }).finally();
    },

  },

};
</script>
