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

                    <el-table
                      :data="tableData"
                      style="width: 100%"
                    >
                      <el-table-column
                        prop="kegiatan"
                        label="Komponen Kegiatan"
                      />
                      <el-table-column label="Komponen Lingkungan">
                        <el-table-column
                          prop="gK"
                          label="Geofisika Kimia"
                        />
                        <el-table-column
                          prop="bio"
                          label="Biologi"
                        />
                        <el-table-column
                          prop="sosBud"
                          label="Sosial Budaya"
                        />
                        <el-table-column
                          prop="kesMas"
                          label="Kesehatan Masyarakat"
                        />
                        <el-table-column
                          prop="etc"
                          label="Lainnya"
                        />
                      </el-table-column>
                    </el-table>

                  </div></el-col>
                </el-row>

              </div>

            </el-tab-pane>
            <el-tab-pane label="Konstruksi" disabled="true">Konstruksi</el-tab-pane>
            <el-tab-pane label="Operasi" disabled="true">Operasi</el-tab-pane>
            <el-tab-pane label="Pasca Operasi" disabled="true">Pasca Operasi</el-tab-pane>
          </el-tabs>
        </div>
      </el-collapse-item>
      <!-- -->
      <el-collapse-item name="2" title="Matriks Identifikasi Dampak">
        <table>
          <thead>
            <tr class="row100 head">
              <th class="column100 column1" data-column="column1" rowspan="2">No</th>
              <th class="column100 column2" data-column="column2" rowspan="2">Kegiatan Utama</th>
              <th class="column100 column3" data-column="column3" rowspan="2">Komponen Kegiatan</th>
              <th class="column100 column4" data-column="column4" colspan="5">Komponen Lingkungan</th>
            </tr>
            <tr class="row100 head">
              <th class="column100 column4" data-column="column4">Geofisika Kimia</th>
              <th class="column100 column5" data-column="column5">Biologi</th>
              <th class="column100 column6" data-column="column6">Sosial Budaya</th>
              <th class="column100 column7" data-column="column7">Kesehatan Masyarakat</th>
              <th class="column100 column8" data-column="column8">Lainnya</th>
            </tr>

          </thead>
          <tbody>
            <tr class="row100">
              <td class="column100 column1" data-column="column1">1</td>
              <td class="column100 column2" data-column="column2">
                <input class="light-grey-bg" type="text" placeholder="Pembangunan Pabrik">
                <button class="small-btn"><i class="fas fa-caret-right" /></button>
              </td>
              <td class="column100 column3" data-column="column3" rowspan="5">
                <div class="small-content-box">
                  1.1 Pengadaan Lahan &nbsp;
                  <i class="fas fa-times" />
                </div>
                <input type="text" placeholder="Deskripsi">
                <div class="small-content-box">
                  1.2 Land Clearing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <i class="fas fa-times" />
                </div>
                <input type="text" placeholder="Deskripsi">
                <a href="#"><i class="fa fa-plus-circle fa-lg" /></a>
              </td>

              <td class="column100 column3" data-column="column3" rowspan="5">
                <div class="small-content-box">
                  1.1 Pengadaan Lahan &nbsp;
                  <i class="fas fa-times" />
                </div>
                <input type="text" placeholder="Deskripsi">
                <div class="small-content-box">
                  1.2 Land Clearing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <i class="fas fa-times" />
                </div>
                <input type="text" placeholder="Deskripsi">
                <i class="fas fa-plus-circle" />
              </td>

              <td class="column100 column3" data-column="column3" rowspan="5">
                <div class="small-content-box">
                  1.1 Pengadaan Lahan &nbsp;
                  <i class="fas fa-times" />
                </div>
                <input type="text" placeholder="Deskripsi">
                <div class="small-content-box">
                  1.2 Land Clearing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <i class="fas fa-times" />
                </div>
                <input type="text" placeholder="Deskripsi">
                <i class="fas fa-plus-circle" />
              </td>

              <td class="column100 column3" data-column="column3" rowspan="5">
                <div class="small-content-box">
                  1.1 Pengadaan Lahan &nbsp;
                  <i class="fas fa-times" />
                </div>
                <input type="text" placeholder="Deskripsi">
                <div class="small-content-box">
                  1.2 Land Clearing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <i class="fas fa-times" />
                </div>
                <input type="text" placeholder="Deskripsi">
                <i class="fas fa-plus-circle" />
              </td>

              <td style="text-align: start;" class="column100 column3" data-column="column3" rowspan="5">
                <div class="small-content-box">
                  Sumber Daya Geologi &nbsp;
                  <i class="fas fa-times" />
                </div>
                <input type="text" placeholder="Deskripsi">
                <div class="small-content-box">
                  Air Permukaan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <i class="fas fa-times" />
                </div>
                <input type="text" placeholder="Deskripsi">
                <div class="small-content-box">
                  Udara &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <i class="fas fa-times" />
                </div>
                <input type="text" placeholder="Deskripsi">

              </td>
              <td style="text-align: start;" class="column100 column3" data-column="column3" rowspan="5">
                <div class="small-content-box">
                  Sumber Daya Geologi &nbsp;
                  <i class="fas fa-times" />
                </div>
                <input type="text" placeholder="Deskripsi">
                <div class="small-content-box">
                  Air Permukaan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <i class="fas fa-times" />
                </div>
                <input type="text" placeholder="Deskripsi">
                <div class="small-content-box">
                  Udara &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <i class="fas fa-times" />
                </div>
                <input type="text" placeholder="Deskripsi">
              </td>

            </tr>

            <tr class="row100">
              <td class="column100 column1" data-column="column1">2</td>
              <td class="column100 column2" data-column="column2">
                <input class="light-grey-bg" type="text" placeholder="Pembangunan Tambang">
                <button class="small-btn-grey"><i class="fas fa-caret-right" /></button>
              </td>

            </tr>
            <tr class="row100">
              <td class="column100 column1" data-column="column1">3</td>
              <td class="column100 column2" data-column="column2">
                <input class="light-grey-bg" type="text" placeholder="Pembangunan Dermaga">
                <button class="small-btn-grey"><i class="fas fa-caret-right" /></button>
              </td>

            </tr>

            <tr>
              <td />
              <td />
            </tr>
            <tr>
              <td />
              <td />
            </tr>

          </tbody>
        </table>

      </el-collapse-item>
      <!-- -->
      <el-collapse-item title="Peta Batas Wilayah Studi & Peta Pendukung" name="3">
        <el-form label-position="top" label-width="100px">
          <el-form-item label="Peta Batas Ekologis" required="true">
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
          <el-form-item label="Peta Batas Sosial" required="true">
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

          <el-form-item label="Peta Batas Wilayah Studi" required="true">
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
        <div>Decision making: giving advices about operations is acceptable, but do not make decisions for the users;</div>
        <div>Controlled consequences: users should be granted the freedom to operate, including canceling, aborting or terminating current operation.</div>
      </el-collapse-item>
      <el-collapse-item title="Metode Studi" name="5">
        <div>Pelingkupan
          Matriks Identifikasi Dampak
          Peta Batas Wilayah Studi & Peta Pendukung
          Dampak Potensial & Dampak Penting Hipotetik
          Metode Studi
          Matriks Dampak Penting Hipotetik
          Bagan Alir Pelingkupan</div>
      </el-collapse-item>
      <el-collapse-item title="Matriks Dampak Penting Hipotetik" name="6">
        <div>Pelingkupan
          Matriks Identifikasi Dampak
          Peta Batas Wilayah Studi & Peta Pendukung
          Dampak Potensial & Dampak Penting Hipotetik
          Metode Studi
          Matriks Dampak Penting Hipotetik
          Bagan Alir Pelingkupan</div>
      </el-collapse-item>
      <el-collapse-item title="Bagan Alir Pelingkupan" name="7">
        <div>Bagan</div>
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
</style>

<script>
export default {
  data() {
    return {
      activeName: '1',
      activeTabName: 'first',
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
    };
  },
  methods: {
    handleClick(tab, event) {
      console.log(tab, event);
    },
  },
};
</script>
