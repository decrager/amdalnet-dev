<template>
  <div id="PelingkupanUKLUPL">
    <el-tabs type="card" @tab-click="handleClick">
      <el-tab-pane label="Master">
        <el-row>
          <el-col span="12"> <komponen-kegiatan /></el-col>
          <el-col span="12"> <komponen-lingkungan /></el-col>
        </el-row>
      </el-tab-pane>
      <template v-for="stage in stages">
        <el-tab-pane :key="'pane_' + stage" :label="stage">
          <tahap-pelingkupan :activities="data.find(s => s.stage === stage)" />
        </el-tab-pane>
      </template>
    </el-tabs>
  </div>
</template>

<script>
import KomponenKegiatan from './tables/KomponenKegiatan';
import KomponenLingkungan from './tables/KomponenLingkungan';
import TahapPelingkupan from './tables/TahapPelingkupan.vue';

export default {
  name: 'Pelingkupan',
  components: {
    KomponenKegiatan,
    KomponenLingkungan,
    TahapPelingkupan,
  },
  props: {
    stages: {
      type: Array,
      default: function(){
        return {};
      },
    },
  },
  data() {
    return {
      data: [
        {
          stage: 'Pra Konstruksi',
          kegiatanUtama: [
            {
              name: 'Pembangunan Pabrik',
              komponenKegiatan: {
                name: 'Pengadaan Lahan',
                komponenLingkungan: {
                  geofisikKimia: ['Kondisi Tanah', 'Udara'],
                  biologi: ['Vegetasi/Flora', 'Fauna'],
                  sosBud: ['Situs Arkeolog'],
                  kesMas: [],
                  lainnya: [],
                },
              },
            },
            { name: 'Pembangunan Tambang' },
            { name: 'Pembangunan Dermaga' },
          ],
          kegiatanPendukung: ['Pembuatan Jalan Raya', 'Pengeboran Sumur', 'Pembangunan Gedung'],
        },
      ],
    };
  },
  methods: {
    handleClick() {

    },
  },
};
</script>
