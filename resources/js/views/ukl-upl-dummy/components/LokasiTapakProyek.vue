<template>
  <div class="app-container">
    <el-row class="form-container">
      <el-col
        :span="12"
      >
        <el-table
          ref="dataLeft"
          :data="dataLeft"
          style="width: 100%"
          :stripe="true"
          :show-header="false"
        >
          <el-table-column width="150" prop="label" />
          <el-table-column prop="value" />
        </el-table>
      </el-col>
      <el-col :span="12">
        <el-table
          ref="dataRight"
          :data="dataRight"
          style="width: 100%"
          :stripe="true"
          :show-header="false"
        >
          <el-table-column width="150" prop="label" />
          <el-table-column prop="value" />
        </el-table>
      </el-col>
    </el-row>
    <el-row :gutter="4">
      <el-col :span="12" :xs="24" align="left">
        <el-form
          ref="postForm"
          :model="postForm"
          style="max-width: 100%"
          label-position="top"
          label-width="200px"
        >
          <el-form-item label="SHP Tapak Proyek" prop="shp">
            <el-upload
              class="upload-demo"
              :auto-upload="false"
              :on-change="handleUploadSHP"
              action="#"
              :show-file-list="false"
            >
              <el-button
                size="small"
                type="primary"
              >Upload</el-button>
            </el-upload>
          </el-form-item>
        </el-form>
      </el-col>
      <el-col :span="12" :xs="24" align="left">
        <el-button
          size="small"
          type="success"
        >Simpan SHP</el-button>
      </el-col>
    </el-row>
    <el-row :gutter="4">
      <el-col :span="12" :xs="24" align="left">
        <el-form
          ref="postForm"
          :model="postForm"
          style="max-width: 100%"
          label-position="top"
          label-width="200px"
        >
          <el-form-item label="PDF Tapak Proyek" prop="pdf">
            <el-upload
              class="upload-demo"
              :auto-upload="false"
              :on-change="handleUploadPDF"
              action="#"
              :show-file-list="false"
            >
              <el-button
                size="small"
                type="primary"
              >Upload</el-button>
            </el-upload>
          </el-form-item>
        </el-form>
      </el-col>
      <el-col :span="12" :xs="24" align="left">
        &nbsp;
      </el-col>
    </el-row>
    <el-row :gutter="4">
      <el-col :span="12" :xs="24" align="left">
        <el-button type="warning">
          List File Upload
        </el-button>
        <el-button type="primary">
          Lihat SHP Peta
        </el-button>
        <el-button type="success">
          Print
        </el-button>
      </el-col>
      <el-col :span="12" :xs="24" align="right">
        <el-button type="success">
          Simpan
        </el-button>
        <el-button type="primary">
          Next
        </el-button>
      </el-col>
    </el-row>
  </div>
</template>

<script>

const defaultForm = {
  pdf: {},
  shp: {},
};

export default {
  name: 'LokasiTapakProyek',
  data() {
    return {
      postForm: Object.assign({}, defaultForm),
      dataLeft: [],
      dataRight: [],
    };
  },
  mounted() {
    this.dataLeft = [
      { label: 'Barcode', value: '1610922263CT2021' },
      { label: 'Jenis Dokumen', value: 'UKL-UPL' },
      { label: 'Nama Kegiatan', value: 'Kegiatan Pembangunan Gedung Jl Pramuka, Jakarta Timur' },
      { label: 'Bidang Usaha/Kegiatan', value: 'Bidang Multisektor' },
      { label: 'Skala/Besaran', value: 'Ha/m/m3/liter' },
      { label: 'Alamat', value: 'Jl. Ir. H. Djuanda No. 78 Bandung' },
      { label: 'Lokasi', value: 'Gedung Jl Pramuka, Jakarta Timur' },
      { label: 'Deskripsi Lokasi', value: 'Pembangunan Gedung Jl Pramuka, Jakarta Timur untuk memberikan manfaat yang besar bagi kemajuan ekonomi suatu daerah dengan terbukanya peluang usaha baru.' },
    ];
    this.dataRight = [
      { label: 'Pemrakarsa', value: 'PT. Wilujeng Sumping' },
      { label: 'Penanggung Jawab', value: 'Suyang Hartanto' },
      { label: 'Alamat Pemrakarsa', value: 'Jl. Ir. H. Djuanda No. 78 Bandung' },
      { label: 'No Telepon Pemrakarsa', value: '081223608250' },
      { label: 'Email Pemrakarsa', value: 'pemrakarsa001@gmail.com' },
      { label: 'Provinsi/Kota', value: 'Bandung' },
      { label: 'Kewenangan', value: 'Pusat' },
      { label: 'Deskripsi Kegiatan', value: 'Saat ini pemrakarsa telah memiliki izin lokasi yang dikeluarkan oleh Gubernur DKI Nomor xxx/123/345.0.0.1/20xx tanggal 01 Januari 2000 untuk keperluan pembangunan Gedung pada lahan seluas ± 2.000 m2. contoh' },
    ];
  },
  methods: {
    handleUploadPDF() {
    },
    handleUploadSHP() {
    },
  },
};
</script>
