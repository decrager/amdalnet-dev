<template>
  <div class="app-container">
    <el-card>
      <el-tabs type="card">
        <el-tab-pane label="Matriks RKL RPL">
          <Matriks />
        </el-tab-pane>
        <el-tab-pane label="Dokumen RKL RPL">
          <el-row :gutter="32">
            <el-col :sm="12" :md="7">
              <MapList />
            </el-col>
            <el-col :sm="12" :md="11">
              <DocsFrame />
            </el-col>
            <el-col :sm="12" :md="6">
              <div>
                <el-button type="primary" style="font-size: 0.8rem">
                  {{ 'Simpan Perubahan' }}
                </el-button>
                <el-button type="warning" style="font-size: 0.8rem">
                  {{ 'Kirim' }}
                </el-button>
              </div>
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
import Matriks from '@/views/rkl-rpl/components/Matriks';
import MapList from '@/views/rkl-rpl/components/MapList';
import DocsFrame from '@/views/rkl-rpl/components/DocsFrame';

export default {
  name: 'Dump',
  components: {
    Matriks,
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
