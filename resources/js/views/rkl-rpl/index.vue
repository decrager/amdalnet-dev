<template>
  <div class="app-container">
    <el-card>
      <div class="filter-container">
        <el-row :gutter="32">
          <el-col :sm="24" :md="10">
            <el-select
              v-model="idProject"
              placeholder="Pilih Kegiatan"
              style="width: 100%"
            >
              <el-option
                v-for="item in projects"
                :key="item.id"
                :label="item.project_title"
                :value="item.id"
              />
            </el-select>
          </el-col>
        </el-row>
      </div>
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
import Resource from '@/api/resource';
const rklResource = new Resource('matriks-rkl');
import Matriks from '@/views/rkl-rpl/components/Matriks';
import MapList from '@/views/rkl-rpl/components/MapList';
import DocsFrame from '@/views/rkl-rpl/components/DocsFrame';

export default {
  name: 'MatriksRKLRPL',
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
      projects: [],
      idProject: null,
    };
  },
  created() {
    this.getProjects();
  },
  methods: {
    async getProjects() {
      const data = await rklResource.list({ project: 'true' });
      this.projects = data;
    },
  },
};
</script>
