<template>
  <div class="app-container">
    <h1>Formulir Kerangka Acuan</h1>

    <el-collapse v-model="activeName" accordion>
      <el-collapse-item name="1" title="Pelingkupan">

        <div>

          <el-tabs type="card" @tab-click="handleClick">
            <el-tab-pane label="Pra Konstruksi">
              <div class="fka-tab-content">

                <el-row>
                  <el-col :span="6"><div class="grid-content bg-purple">
                    <el-table
                      :data="kegiatanUtama"
                      border
                      style="width: 100%"
                    >
                      <el-table-column
                        prop="no"
                        label="No."
                        width="50"
                      />
                      <el-table-column
                        prop="kegiatan"
                        label="Kegiatan Utama"
                      />
                    </el-table>
                    <p>&nbsp;</p>
                    <el-table
                      :data="komponenPendukung"
                      border
                      style="width: 100%"
                    >
                      <el-table-column
                        prop="no"
                        label="No."
                        width="50"
                      />
                      <el-table-column
                        prop="komponen"
                        label="Komponen Pendukung"
                      />
                    </el-table>

                  </div>
                  </el-col>
                  <el-col :span="18"><div class="grid-content bg-purple-light">

                    <table class="title" style="border-collapse: collapse; width:100%;">
                      <thead>
                        <tr>
                          <th rowspan="2">Komponen Kegiatan</th>
                          <th colspan="5">Komponen Lingkungan</th>
                        </tr>
                        <tr>
                          <th>Geofisika Kimia</th>
                          <th>Biologi</th>
                          <th>Sosial Budaya</th>
                          <th>Kesehatan Masyarakat</th>
                          <th>Lainnya</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <el-button icon="el-icon-plus" circle @click="kKDialogueVisible = true" />
                          </td>
                          <td>
                            <el-button icon="el-icon-plus" circle />
                          </td>
                          <td>
                            <el-button icon="el-icon-plus" circle />
                          </td>
                          <td><el-button icon="el-icon-plus" circle />
                          </td>
                          <td>
                            <el-button icon="el-icon-plus" circle />
                          </td>
                          <td>
                            <el-button icon="el-icon-plus" circle />
                          </td>
                        </tr>
                      </tbody>

                    </table>

                    <el-dialog
                      title="Tips"
                      :visible.sync="kKDialogueVisible"
                      width="30%"
                      :before-close="handleClose"
                    >
                      <span>This is a message</span>
                      <span slot="footer" class="dialog-footer">
                        <el-button @click="kKDialogueVisible = false">Cancel</el-button>
                        <el-button type="primary" @click="kKDialogueVisible = addKK">Confirm</el-button>
                      </span>
                    </el-dialog>

                  </div></el-col>
                </el-row>

              </div>

            </el-tab-pane>
            <el-tab-pane label="Konstruksi" :disabled="disabled">Konstruksi</el-tab-pane>
            <el-tab-pane label="Operasi" :disabled="disabled">Operasi</el-tab-pane>
            <el-tab-pane label="Pasca Operasi" :disabled="disabled">Pasca Operasi</el-tab-pane>
          </el-tabs>
        </div>
      </el-collapse-item>
      <!-- -->
      <el-collapse-item name="2" title="Matriks Identifikasi Dampak">

        <el-button size="medium" type="primary">Simpan Perubahan</el-button>

        <table style="margin:2em 0; border-collapse: collapse;">
          <thead>
            <tr>
              <th rowspan="2"> Komponen Lingkungan/ Sumber Dampak</th>
              <th colspan="3">Pra Konstruksi</th>
              <th colspan="2">Konstruksi</th>
              <th colspan="2">Operasi</th>
            </tr>
            <tr>
              <th>Pembebasan Lahan</th>
              <th>Sosialisasi...</th>
              <th>Pengamanan Perairan</th>
              <th>Penerimaan Tenaga...</th>
              <th>Mobilisasi Alat dan Bahan</th>
              <th>Operasional Unit/F...</th>
              <th>Operasional Unit/F...</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="8" class="title"><strong>Geofisik Kimia</strong></td>
            </tr>
            <tr>
              <td class="title">1. Kualitas Udara</td>
              <td> <el-checkbox :checked="checked" /></td>
              <td><el-checkbox /></td>
              <td><el-checkbox /></td>
              <td><el-checkbox :checked="checked" /></td>
              <td><el-checkbox /></td>
              <td><el-checkbox /></td>
              <td><el-checkbox :checked="checked" /></td>
            </tr>
            <tr>
              <td colspan="8" class="title"><strong>Biologi</strong></td>
            </tr>
            <tr>
              <td class="title">1. Flora</td>
              <td> <el-checkbox /></td>
              <td><el-checkbox /></td>
              <td><el-checkbox :checked="checked" /></td>
              <td><el-checkbox :checked="checked" /></td>
              <td><el-checkbox /></td>
              <td><el-checkbox /></td>
              <td><el-checkbox :checked="checked" /></td>
            </tr>
            <tr>
              <td class="title">2. Fauna</td>
              <td> <el-checkbox :checked="checked" /></td>
              <td><el-checkbox :checked="checked" /></td>
              <td><el-checkbox :checked="checked" /></td>
              <td><el-checkbox :checked="checked" /></td>
              <td><el-checkbox :checked="checked" /></td>
              <td><el-checkbox :checked="checked" /></td>
              <td><el-checkbox :checked="checked" /></td>
            </tr>
            <tr>
              <td colspan="8" class="title"><strong>Sosial, Ekonomi, Budaya</strong></td>
            </tr>
            <tr>
              <td class="title">1. Demografi</td>
              <td> <el-checkbox :checked="checked" /></td>
              <td><el-checkbox :checked="checked" /></td>
              <td><el-checkbox :checked="checked" /></td>
              <td><el-checkbox :checked="checked" /></td>
              <td><el-checkbox :checked="checked" /></td>
              <td><el-checkbox :checked="checked" /></td>
              <td><el-checkbox :checked="checked" /></td>
            </tr>
            <tr>
              <td colspan="8" class="title"><strong>Kesehatan Masyarakat</strong></td>
            </tr>
            <tr>
              <td class="title">1. Fasilitas Kesehatan</td>
              <td> <el-checkbox /></td>
              <td><el-checkbox :checked="checked" /></td>
              <td><el-checkbox :checked="checked" /></td>
              <td><el-checkbox /></td>
              <td><el-checkbox :checked="checked" /></td>
              <td><el-checkbox :checked="checked" /></td>
              <td><el-checkbox :checked="checked" /></td>
            </tr>
          </tbody>
        </table>

      </el-collapse-item>
      <!-- -->
      <el-collapse-item title="Peta Batas Wilayah Studi & Peta Pendukung" name="3">
        <el-form label-position="top" label-width="100px">
          <el-form-item label="Peta Batas Ekologis" :required="required">
            <el-col :span="6" style="margin-right:1em;">
              <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
                <el-col :span="17"><el-input placeholder="Versi SHP" /></el-col>
                <el-col :span="4" style="margin-left:1em;"><el-button size="small" type="info">browse</el-button></el-col>
              </el-row>
            </el-col>

            <el-col :span="6" style="margin-right:1em;">
              <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
                <el-col :span="17"><el-input placeholder="Versi PDF" /></el-col>
                <el-col :span="4" style="margin-left:1em;"><el-button size="small" type="info">browse</el-button></el-col>
              </el-row>
            </el-col>
          </el-form-item>
          <el-form-item label="Peta Batas Sosial" :required="required">
            <el-col :span="6" style="margin-right:1em;">
              <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
                <el-col :span="17"><el-input placeholder="Versi SHP" /></el-col>
                <el-col :span="4" style="margin-left:1em;"><el-button size="small" type="info">browse</el-button></el-col>
              </el-row>
            </el-col>

            <el-col :span="6" style="margin-right:1em;">
              <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
                <el-col :span="17"><el-input placeholder="Versi PDF" /></el-col>
                <el-col :span="4" style="margin-left:1em;"><el-button size="small" type="info">browse</el-button></el-col>
              </el-row>
            </el-col>
          </el-form-item>

          <el-form-item label="Peta Batas Wilayah Studi" :required="required">
            <el-col :span="6" style="margin-right:1em;">
              <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
                <el-col :span="17"><el-input placeholder="Versi SHP" /></el-col>
                <el-col :span="4" style="margin-left:1em;"><el-button size="small" type="info">browse</el-button></el-col>
              </el-row>
            </el-col>

            <el-col :span="6" style="margin-right:1em;">
              <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
                <el-col :span="17"><el-input placeholder="Versi PDF" /></el-col>
                <el-col :span="4" style="margin-left:1em;"><el-button size="small" type="info">browse</el-button></el-col>
              </el-row>
            </el-col>
          </el-form-item>

          <el-row style="margin: 1em 0;">
            <el-col :span="12">
              <el-button size="medium" type="warning">Unggah Peta</el-button>
            </el-col>
            <el-col :span="12" style="text-align: right;">
              <el-button size="medium" type="danger">Batal</el-button>
              <el-button size="medium" type="primary">Simpan</el-button>
            </el-col>
          </el-row>
        </el-form>

      </el-collapse-item>
      <el-collapse-item title="Dampak Potensial & Dampak Penting Hipotetik" name="4">
        <el-button size="medium" type="primary">Simpan Perubahan</el-button>
        <table style="margin: 2em 0; border-collapse: collapse;">
          <thead>
            <tr>
              <th colspan="3">Rencana Usaha dan/atau Kegiatan yang Berpotensi Menimbulkan Dampak Lingkungan</th>
              <th style="font-size:80%" rowspan="2">Pengelolaan Lingkungan yang sudah
                direncanakan sejak awal sebagai bagian
                dari rencana kegiatan</th>
              <th rowspan="2">Evaluasi Dampak Potensial</th>
              <th rowspan="2">Dampak Penting Hipotetik</th>
              <th rowspan="2">Wilayah Studi</th>
              <th rowspan="2">Batas Waktu Kajian</th>
              <th rowspan="2">Kesimpulan</th>
            </tr>
            <tr>
              <th>Komponen Dampak</th>
              <th>Komponen Lingkungan</th>
              <th>Sumber Dampak</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="9" class="title"><strong>A. Pra Konstruksi</strong></td>
            </tr>
            <tr class="title">
              <td>
                <el-select v-model="valueA" placeholder="Select">
                  <el-option
                    v-for="item in kejadian"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"
                  />
                </el-select>
              </td>
              <td>Kebisingan</td>
              <td>akibat Mobilisasi Alat Berat</td>
              <td>Pengelolaan Lingkungan yang
                sudah direncanakan sejak awal
                sebagai bagian dari rencana
                kegiatan</td>
              <td>
                <div class="div-fka formA">
                  <p><strong>A. Besaran rencana Usaha dan/atau Kegiatan</strong> (yang
                    menyebabkan dampak tersebut dan rencana pengelolaan
                    lingkungan awal yang menjadi bagian rencana Usaha
                    dan/atau Kegiatan untuk menanggulangi dampak)</p>
                  <el-input
                    v-model="textAA"
                    type="textarea"
                    :rows="2"
                    placeholder="Please input"
                  />
                </div>
                <div class="div-fka formA">
                  <p><strong>B. Kondisi rona lingkungan</strong> (yang ada termasuk kemampuan
                    mendukung Usaha dan/atau Kegiatan tersebut atau tidak)</p>
                  <el-input
                    v-model="textAB"
                    type="textarea"
                    :rows="2"
                    placeholder="Please input"
                  />
                </div>
                <div class="div-fka formA">
                  <p><strong>C. Pengaruh rencana Usaha dan/atau Kegiatan</strong> (terhadap
                    kondisi Usaha dan/atau Kegiatan lain di sekitar lokasi</p>
                  <el-input
                    v-model="textAC"
                    type="textarea"
                    :rows="2"
                    placeholder="Please input"
                  />
                </div>
                <div class="div-fka formA">
                  <p><strong>D. Intensitas perhatian masyarakat</strong> (terhadap rencana Usaha
                    dan/atau Kegiatan, baik harapan, kekhawatiran,
                    persetujuan
                    atau penolakan terhadap rencana Usaha
                    dan/atau Kegiatan)</p>
                  <el-input
                    v-model="textAD"
                    type="textarea"
                    :rows="2"
                    placeholder="Please input"
                  />
                </div>
                <div class="div-fka formA">
                  <p><strong>E. Kesimpulan</strong></p>
                  <el-input
                    v-model="textAE"
                    type="textarea"
                    :rows="2"
                    placeholder="Please input"
                  />
                </div>
              </td>
              <td>
                <el-select v-model="vDPHs" placeholder="Select">
                  <el-option
                    v-for="item in dPHs"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"
                    :disabled="item.disabled"
                  />
                </el-select>
              </td>
              <td />
              <td>
                <p><el-input-number v-model="tahunA" :min="0" :max="10" size="mini" /> tahun</p>
                <p><el-input-number v-model="bulanA" :min="0" :max="12" size="mini" /> bulan</p>

              </td>
              <td />
            </tr>

            <tr>
              <td colspan="9" class="title"><strong>B. Konstruksi</strong></td>
            </tr>
            <tr>
              <td colspan="9" class="title"><strong>C. Operasi</strong></td>
            </tr>
          </tbody>
        </table>

      </el-collapse-item>
      <el-collapse-item title="Metode Studi" name="5">
        <el-button size="medium" type="primary">Simpan Perubahan</el-button>
        <table style="margin:2em 0; border-collapse: collapse;">
          <thead>
            <tr>
              <th>Dampak Penting Hipotetik</th>
              <th>Data dan Informasi yang Relevan dan Dibutuhkan</th>
              <th>Metoda Pengumpulan Data untuk Prakiraan</th>
              <th>Metoda Analisis Data untuk Prakiraan</th>
              <th>Metoda Prakiraan Dampak Penting</th>
              <th>Metoda Evaluasi Dampak Penting</th>
            </tr>
          </thead>
          <tbody>
            <tr><td colspan="6" class="title"><strong>A. Pra Konstruksi</strong></td></tr>
            <tr class="title">
              <td>1. Penurunan Kualitas Udara akibat Pengadaan Lahan</td>
              <td>Peningkatan Debu dan Partikulat dalam Udara Ambien</td>
              <td>Tabulasi
                deskriptif dan
                persamaan matematika</td>
              <td />
              <td>Penentuan Besaran Dampak</td>
              <td>Evaluasi secara
                holistik untuk
                semua
                dampak
                akan menggunakan metode bagan
                alir</td>
            </tr>
            <!-- -->
            <tr><td colspan="6" class="title"><strong>B. Konstruksi</strong></td></tr>
            <tr class="title">
              <td>1.Penurunan Mata Pencaharian Penduduk akibat Mobilisasi Peralatan dan Material Konstruksi</td>
              <td />
              <td />
              <td />
              <td />
              <td />
            </tr>
            <!-- -->
            <tr><td colspan="6" class="title"><strong>C. Operasi</strong></td></tr>
            <tr class="title">
              <td>1. Penurunan Keberadaan Biota Air akibat Pengoperasian
                Kawasan Industri dan Sarana Pendukungnya</td>
              <td />
              <td />
              <td />
              <td />
              <td />
            </tr>
            <tr class="title">
              <td>2. Penurunan Kualitas Air akibat Pengoperasian Kawasan
                Industri dan Sarana Pendukungnya</td>
              <td />
              <td />
              <td />
              <td />
              <td />
            </tr>
          </tbody>
        </table>
      </el-collapse-item>
      <el-collapse-item title="Matriks Dampak Penting Hipotetik" name="6">

        <el-button size="medium" type="primary">Simpan Perubahan</el-button>

        <table style="margin:2em 0; border-collapse: collapse;">
          <thead>
            <tr>
              <th rowspan="2"> Komponen Lingkungan/ Sumber Dampak</th>
              <th colspan="3">Pra Konstruksi</th>
              <th colspan="2">Konstruksi</th>
              <th colspan="2">Operasi</th>
            </tr>
            <tr>
              <th>Pembebasan Lahan</th>
              <th>Sosialisasi...</th>
              <th>Pengamanan Perairan</th>
              <th>Penerimaan Tenaga...</th>
              <th>Mobilisasi Alat dan Bahan</th>
              <th>Operasional Unit/F...</th>
              <th>Operasional Unit/F...</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="8" class="title"><strong>Geofisik Kimia</strong></td>
            </tr>
            <tr>
              <td class="title">1. Kualitas Udara</td>
              <td>DPH</td>
              <td />
              <td>DPH</td>
              <td>DPH</td>
              <td>DPH</td>
              <td>DPH</td>
              <td>DPH</td>
            </tr>
            <tr>
              <td colspan="8" class="title"><strong>Biologi</strong></td>
            </tr>
            <tr>
              <td class="title">1. Flora</td>
              <td />
              <td />
              <td>DPH</td>
              <td />
              <td />
              <td />
              <td>DPH</td>
            </tr>
            <tr>
              <td class="title">2. Fauna</td>
              <td />
              <td>DPH</td>
              <td />
              <td />
              <td>DPH</td>
              <td />
              <td />
            </tr>
            <tr>
              <td colspan="8" class="title"><strong>Sosial, Ekonomi, Budaya</strong></td>
            </tr>
            <tr>
              <td class="title">1. Demografi</td>
              <td />
              <td />
              <td />
              <td>DPH</td>
              <td>DPH</td>
              <td />
              <td />
            </tr>
            <tr>
              <td colspan="8" class="title"><strong>Kesehatan Masyarakat</strong></td>
            </tr>
            <tr>
              <td class="title">1. Fasilitas Kesehatan</td>
              <td>DPH</td>
              <td />
              <td />
              <td />
              <td>DPH</td>
              <td>DPH</td>
              <td />
            </tr>
          </tbody>
        </table>

      </el-collapse-item>
      <el-collapse-item title="Bagan Alir Pelingkupan" name="7">
        <div>Bagan</div>

        <el-col :span="24" style="text-align:right; margin:2em 0;"><el-button size="small" type="warning">Export PDF</el-button></el-col>
      </el-collapse-item>
    </el-collapse>
  </div>

</template>

<style scoped>
.fka-accordion-content, .fka-tab-content {
    padding: 2em;
}

.el-row {
    margin-bottom: 20px;
    &:last-child {
      margin-bottom: 0;
    }
  }
  .el-col {
    border-radius: 4px;
  }

  .grid-content {
    border-radius: 4px;
    min-height: 36px;
    margin: 0.5em;
  }
  .row-bg {
    padding: 10px 0;
    background-color: #f9fafc;
  }

  table.el-table__header {
      background-color: #6cc26f !important;
  }

  table th, table td {word-break: normal !important; padding:.5em; line-height:1.2em; border: 1px solid #eee; text-align:center;}
  table td { vertical-align: top !important;}
  table thead  {background-color:#6cc26f !important; color: white !important;}
  table td.title, table tr.title td, table.title td { text-align:left;}
div.div-fka {
    padding: 0.5em;
    margin-bottom: 0.6em;
    background-color: #fafafa;
}
</style>

<script>
export default {
  data() {
    return {
      activeName: '1',
      activeTabName: 'first',
      disabled: true,
      checked: true,
      required: true,
      tableData: [],
      kegiatanUtama: [{
        no: '1',
        kegiatan: 'Pembangunan Pabrik',
      },
      {
        no: '2',
        kegiatan: 'Pembangunan Tambang',
      },
      {
        no: '3',
        kegiatan: 'Pembangunan Dermaga',
      }],
      komponenPendukung: [{
        no: '1',
        komponen: 'Pembangunan Pabrik',
      },
      {
        no: '2',
        komponen: 'Pembangunan Tambang',
      },
      {
        no: '3',
        komponen: 'Pembangunan Dermaga',
      }],
      kejadian: [{
        value: 0,
        label: 'Penurunan',
      },
      {
        value: 1,
        label: 'Peningkatan',
      }],
      valueA: 1,
      dPHs: [{
        value: 0,
        label: 'DPH',
      },
      {
        value: 1,
        label: 'DTPH',
      },
      ],
      vDPHs: 0,
      tahunA: 2,
      bulanA: 0,
      textAA: '',
      textAB: '',
      textAC: '',
      textAD: '',
      textAE: '',
      kKDialogueVisible: false,
    };
  },
  methods: {
    handleClick(tab, event) {
      console.log(tab, event);
    },
    addKK(event){
      console.log(event);
      return false;
    },
  },
};
</script>
