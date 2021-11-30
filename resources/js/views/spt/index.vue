<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <el-steps :active="6" align-center>
        <el-step description="Rencana Usaha/Kegiatan" />
        <el-step description="Hasil Penapisan" />
        <el-step description="SPT dari Masyarakat" />
        <el-step description="Formulir Kerangka Acuan" />
        <el-step description="Penyusunan andal" />
        <el-step description="Penyusunan RKL RPL" />
        <el-step description="Surat Keputusan" />
      </el-steps>
      <div style="display:flex; justify-content: space-between; align-items: center;margin-top:3rem">
        <div>
          <h3 class="fw-bold">Daftar SPT (Saran, Pendapat, Tanggapan) Masyarakat</h3>
        </div>
        <div>
          <el-button type="warning" round @click="outerVisible = true">Tambah SPT Baru</el-button>
        </div>
      </div>
      <el-table
        :data="tableData"
        fit
        highlight-current-row
        :header-cell-style="{ background: '#3AB06F', color: 'white', border:'0' }"
      >
        <el-table-column type="expand">
          <template slot-scope="props">
            <p class="pl"><strong>Harapan : </strong> {{ props.row.harapan }}</p>
            <div class="pl" style="display:flex">
              <div><strong>Rating : </strong></div>
              <div><el-rate v-model="rating" /></div>
            </div>
            <p class="pl">
              <strong>Relevansi : </strong>
              <el-radio v-model="radio" label="1">Relevan</el-radio>
              <el-radio v-model="radio" label="2">Tidak Relevan</el-radio>
            </p>
            <p class="pl"><strong>Identitas : </strong> <span class="link">Lihat Identitas</span></p>
          </template>
        </el-table-column>
        <el-table-column
          label="No"
          prop="no"
          width="54px"
        />
        <el-table-column
          label="Waktu Input"
          prop="date"
        />
        <el-table-column
          label="Nama"
          prop="name"
        />
        <el-table-column
          label="Peran"
          prop="peran"
        />
        <el-table-column
          label="Kekawatiran"
          prop="kekawatiran"
        />
      </el-table>
    </el-card>

    <el-dialog title="Tambah Saran/Pendapat/Tanggapan dari Masyarakat" :visible.sync="outerVisible">
      <el-dialog
        width="30%"
        title="Inner Dialog"
        :visible.sync="innerVisible"
        append-to-body
      />
      <el-form
        ref="form"
        enctype="multipart/form-data"
      >
        <el-row :gutter="20">
          <el-col :span="12">
            <el-form-item>
              <div class="text-white fw-bold">Nama</div>
              <el-input placeholder="Masukkan Nama Anda" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item>
              <div class="text-white fw-bold">Peran</div>
              <el-form-item label="">
                <el-select placeholder="Pilih Peran" style="width:100%">
                  <el-option label="Masyarakat Terkena Dampak Langsung" value="1" />
                  <el-option label="Pemerhati Lingkungan Hidup" value="2" />
                  <el-option label="LSM" value="3" />
                  <el-option label="Masyarakat Berkepentingan Lainya" value="4" />
                </el-select>
              </el-form-item>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item>
              <div class="text-white fw-bold">NIK</div>
              <el-input placeholder="Masukkan NIK Anda" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item>
              <div class="text-white fw-bold">Unggah Foto Selfie</div>
              <el-input type="file" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item>
              <div class="text-white fw-bold">No. Telepon/Handphone</div>
              <el-input placeholder="Masukkan No. Telepon/Handphone Anda" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item>
              <div class="text-white fw-bold">Email</div>
              <el-input placeholder="Masukkan Email Anda" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="24">
            <el-form-item>
              <div class="text-white fw-bold">Saran/Komentar</div>
              <el-input type="textarea" placeholder="Saran/Komentar" />
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item>
              <div class="text-white fw-bold">Kekhawatiran</div>
              <el-input type="textarea" placeholder="Kekhawatiran" />
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item>
              <div class="text-white fw-bold">Harapan</div>
              <el-input type="textarea" placeholder="Harapan" />
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <h4 class="fw-bold" style="margin-bottom:0">Berikan rating Anda untuk Rencana Usaha/Kegiatan ini</h4>
            <div style="display: flex;align-items: center;">
              <div>
                <p class="fw-bold">1 = Tidak Relevan</p>
              </div>
              <div style="margin:0 1rem">
                <el-rate v-model="ratingCreate" />
              </div>
              <div>
                <p class="fw-bold">5  = Relevan</p>
              </div>
            </div>
          </el-col>
        </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="outerVisible = false">Batal</el-button>
        <el-button type="primary" @click="innerVisible = true">Tambah</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: 'SPT',
  components: {},
  data() {
    return {
      tableData: [
        {
          no: 1,
          date: '2016-05-03',
          name: 'Tom',
          peran: 'California',
          kekawatiran: 'Los Angeles',
          harapan: 'No. 189, Grove St, Los Angeles',
          rating: 'CA 90036',
          relevansi: 'CA 90036',
        },
      ],
      radio: '1',
      rating: 5,
      ratingCreate: 0,
      outerVisible: false,
      innerVisible: false,
    };
  },
  methods: {},
};
</script>
<style scoped>
  .link{text-decoration:underline; color:#50b0e3; cursor:pointer;font-size: 0.7rem;font-weight: bold;}
  .pl{padding-left: 2rem;}
  .fw-bold {font-weight: bold;}
  .el-form-item{margin-bottom: 0;}
  .el-dialog__body{padding-top: 0;}
</style>
