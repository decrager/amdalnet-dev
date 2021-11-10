<template>
  <div class="app-container">
    <el-card>
      <el-tabs type="card">
        <el-tab-pane label="Analisa Dampak Lingkungan">
          <div class="filter-container">
            <el-button
              class="filter-item"
              type="primary"
              style="font-size: 0.8rem"
            >
              {{ 'Simpan Perubahan' }}
            </el-button>
            <span style="font-size: 0.8rem">
              <em>Terakhir diperbarui beberapa detik yang lalu</em>
            </span>
          </div>
          <DumpTable :loading="loading" :list="dampakLingkungan" />
        </el-tab-pane>
        <el-tab-pane label="Dokumen ANDAL">
          <el-row :gutter="32">
            <el-col :sm="12" :md="7">
              <MapList />
            </el-col>
            <el-col :sm="12" :md="12">
              <DocsFrame />
            </el-col>
            <el-col :sm="12" :md="5">
              <el-button
                type="primary"
                style="font-size: 0.8rem; display: block"
              >
                {{ 'Simpan Perubahan' }}
              </el-button>
              <small>
                <em>Terakhir diperbarui beberapa detik yang lalu</em>
              </small>
            </el-col>
          </el-row>
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import DumpTable from '@/views/penyusunan-andal/components/Table';
import MapList from '@/views/penyusunan-andal/components/MapList';
import DocsFrame from '@/views/penyusunan-andal/components/DocsFrame';

export default {
  name: 'Dump',
  components: {
    DumpTable,
    MapList,
    DocsFrame,
  },
  data() {
    return {
      loading: false,
      dampakLingkungan: [
        {
          hipotetik: 'Pra Konstruksi',
        },
        {
          hipotetik: 'Penurunan Kualitas Udara akibat Pengadaan Lahan',
          komponen: '',
          ronaAwal: '',
          besaranDampak: '',
          sifatPenting: '',
          hasilEvaluasi: '',
        },
        {
          hipotetik: 'Konstruksi',
        },
        {
          hipotetik:
            'Penururan Mata Pencaharian Penduduk akibat Mobilisasi Peralatan dan Material Konstruksi',
          komponen: '',
          ronaAwal: '',
          besaranDampak: '',
          sifatPenting: '',
          hasilEvaluasi: '',
        },
        {
          hipotetik: 'Operasi',
        },
        {
          hipotetik:
            'Penurunan Keberadaan Biota Air akibat Pengoperasian Kawasan Industri dan Sarana Pendukungnya',
          komponen: '',
          ronaAwal: '',
          besaranDampak: '',
          sifatPenting: '',
          hasilEvaluasi: '',
        },
        {
          hipotetik:
            'Penurunan Kualitas Air akibat Pengoperasian Kawasan Industri dan Sarana Pendukungnya',
          komponen: '',
          ronaAwal: '',
          besaranDampak: '',
          sifatPenting: '',
          hasilEvaluasi: '',
        },
      ],
    };
  },
  created() {},
  methods: {},
};
</script>
