<template>
  <div>
    <el-alert
      style="margin-top: 5px; margin-bottom: 5px"
      title="Silahkan melanjutkan proses penapisan proyek yang diajukan"
      type="warning"
      effect="dark"
      :closable="false"
      show-icon
    />
    <el-table
      v-loading="loading"
      max-height="800"
      :header-cell-style="{ background: '#099C4B', color: 'white' }"
      :data="
        list.filter(
          (data) =>
            !search ||
            (data.id_proyek ? data.id_proyek : '')
              .toLowerCase()
              .includes(search.toLowerCase()) ||
            (data.idizin ? data.idizin : '')
              .toLowerCase()
              .includes(search.toLowerCase()) ||
            (data.name ? data.name : '')
              .toLowerCase()
              .includes(search.toLowerCase()) ||
            (data.kbli ? data.kbli : '')
              .toLowerCase()
              .includes(search.toLowerCase()) ||
            (data.skala_resiko ? data.skala_resiko : '')
              .toLowerCase()
              .includes(search.toLowerCase()) ||
            JSON.stringify(data.lokasi ? data.lokasi : [])
              .toLowerCase()
              .includes(search.toLowerCase())
        )
      "
      fit
      highlight-current-row
      :default-sort="{ prop: 'name', order: 'descending' }"
    >
      <el-table-column align="center" label="No." width="50px">
        <template slot-scope="scope">
          {{ scope.$index + 1 }}
        </template>
      </el-table-column>
      <el-table-column
        align="left"
        header-align="center"
        label="Project"
        width="400px"
        prop="name"
        sortable
      >
        <template slot-scope="scope">
          <div><b>ID Proyek :</b> {{ scope.row.id_proyek }}</div>
          <div><b>ID Izin :</b> {{ scope.row.idizin }}</div>
          <div><b>Nama Kegiatan :</b> {{ scope.row.name }}</div>
          <div><b>KBLI :</b> {{ scope.row.kbli }}</div>
          <div><b>Tingkat Resiko :</b> {{ scope.row.skala_resiko }}</div>
          <div>
            <b>Kewenangan :</b> {{ getKewenangan(scope.row.kewenangan) }}
          </div>
        </template>
      </el-table-column>
      <el-table-column
        align="left"
        header-align="center"
        label="Alamat"
        sortable
        prop="province"
      >
        <template slot-scope="scope">
          <ul style="margin-block-start: 0px">
            <li v-for="(lokasi, index) in scope.row.lokasi" :key="index">
              Provinsi {{ lokasi.province.toLowerCase() }},
              {{ lokasi.regency.toLowerCase() }},
              {{ lokasi.alamat_usaha.toLowerCase() }}
            </li>
          </ul>
        </template>
      </el-table-column>
      <el-table-column align="center">
        <template slot="header">
          <el-input
            v-model="search"
            size="mini"
            placeholder="Cari berdasarkan Project atau Alamat"
          />
        </template>
        <router-link to="/project/create">
          <el-button type="primary">Proses Penapisan</el-button>
        </router-link>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
  data() {
    return {
      loading: false,
      list: [],
      search: '',
    };
  },
  computed: {
    ...mapGetters({
      userInfo: 'user',
      userId: 'userId',
    }),
  },
  async created() {
    this.loading = true;
    await this.$store.dispatch('getInitiator', this.userInfo.email);
    await this.$store.dispatch(
      'getOssByKbli',
      this.$store.getters.pemrakarsa[0].nib
    ); // get data from ossnib

    const ossProjects = this.$store.getters.ossByNib.data_proyek?.filter(
      (e) => (e.file_izin === '-' || !e.file_izin) && !e.status_tapis
    );
    console.log(ossProjects);
    console.log(this.$store.getters.ossByNib);
    if (ossProjects) {
      ossProjects.forEach((e) => {
        // check kbli or not
        if (e.nonKbli === 'Y') {
          this.list.push({
            nonKbli: true,
            kbli: e.kbli,
            name: e.uraian_usaha,
            id_proyek: e.id_proyek,
            kewenangan: e.kewenangan,
            lokasi: e.data_lokasi_proyek,
            skala_resiko: e.skala_resiko,
            idizin: e.id_izin,
          });
        } else {
          this.list.push({
            kbli: e.kbli,
            name: e.uraian_usaha,
            id_proyek: e.id_proyek,
            kewenangan: e.kewenangan,
            lokasi: e.data_lokasi_proyek,
            skala_resiko: e.skala_resiko,
            idizin: e.id_izin,
          });
        }
      });
    }
    this.loading = false;
  },
  methods: {
    getKewenangan(val) {
      if (val === '00') {
        return 'pusat';
      } else if (val === '01') {
        return 'provinsi';
      } else {
        return 'kabupaten';
      }
    },
  },
};
</script>
