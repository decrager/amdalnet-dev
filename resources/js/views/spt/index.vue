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
        v-loading="loading"
        :data="tableData"
        fit
        highlight-current-row
        :header-cell-style="{ background: '#3AB06F', color: 'white', border:'0' }"
      >
        <el-table-column type="expand">
          <template slot-scope="props">
            <p class="pl"><strong>Harapan : </strong> {{ props.row.hope }}</p>
            <div class="pl" style="display:flex">
              <div><strong>Rating : </strong></div>
              <div><el-rate v-model="props.row.relevance" /></div>
            </div>
            <p class="pl">
              <strong>Relevansi : </strong>
              <!-- <el-radio v-model="radio" label="1">Relevan</el-radio>
              <el-radio v-model="radio" label="2">Tidak Relevan</el-radio> -->
              <span v-if="checkRelevance(props.row.relevance)">Relevan</span>
              <span v-else>Tidak Relevan</span>
            </p>
            <!-- <p class="pl"><strong>Identitas : </strong> <span class="link">Lihat Identitas</span></p> -->
            <p class="pl"><strong>NIK : </strong> <span>{{ props.row.nik }}</span></p>
            <p class="pl"><strong>Email : </strong> <span>{{ props.row.email }}</span></p>
            <p class="pl"><strong>No. Telepon : </strong> <span>{{ props.row.phone }}</span></p>
          </template>
        </el-table-column>
        <el-table-column
          label="No"
        >
          <template slot-scope="props">
            <span>{{ props.$index + 1 }}</span>
          </template>
        </el-table-column>
        <el-table-column
          label="Waktu Input"
        >
          <template slot-scope="props">
            <span>{{ props.row.created_at }}</span>
          </template>
        </el-table-column>
        <el-table-column
          label="Nama"
        >
          <template slot-scope="props">
            <span>{{ props.row.name }}</span>
          </template>
        </el-table-column>
        <el-table-column
          label="Peran"
        >
          <template slot-scope="props">
            <span>{{ props.row.role }}</span>
          </template>
        </el-table-column>
        <el-table-column
          label="Kekhawatiran"
        >
          <template slot-scope="props">
            <span>{{ props.row.worries }}</span>
          </template>
        </el-table-column>
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
              <el-input v-model="formData.name" placeholder="Masukkan Nama Anda" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item>
              <div class="text-white fw-bold">Peran</div>
              <el-form-item label="">
                <el-select v-model="formData.role" placeholder="Pilih Peran" style="width:100%">
                  <el-option label="Masyarakat Terkena Dampak Langsung" value="Masyarakat Terkena Dampak Langsung" />
                  <el-option label="Pemerhati Lingkungan Hidup" value="Pemerhati Lingkungan Hidup" />
                  <el-option label="LSM" value="LSM" />
                  <el-option label="Masyarakat Berkepentingan Lainya" value="Masyarakat Berkepentingan Lainya" />
                </el-select>
              </el-form-item>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item>
              <div class="text-white fw-bold">NIK</div>
              <el-input v-model="formData.nik" placeholder="Masukkan NIK Anda" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item>
              <div class="text-white fw-bold">Unggah Foto Selfie</div>
              <el-upload
                type="primary"
                class="upload-demo"
                :auto-upload="false"
                :on-change="handleUploadChange"
                action="#"
                :show-file-list="true"
              >
                <el-button size="small" type="primary">
                  Unggah foto
                </el-button>
              </el-upload>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item>
              <div class="text-white fw-bold">No. Telepon/Handphone</div>
              <el-input v-model="formData.phone" placeholder="Masukkan No. Telepon/Handphone Anda" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item>
              <div class="text-white fw-bold">Email</div>
              <el-input v-model="formData.email" placeholder="Masukkan Email Anda" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="24">
            <el-form-item>
              <div class="text-white fw-bold">Saran/Komentar</div>
              <el-input v-model="formData.comment" type="textarea" placeholder="Saran/Komentar" />
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item>
              <div class="text-white fw-bold">Kekhawatiran</div>
              <el-input v-model="formData.worries" type="textarea" placeholder="Kekhawatiran" />
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item>
              <div class="text-white fw-bold">Harapan</div>
              <el-input v-model="formData.hope" type="textarea" placeholder="Harapan" />
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <h4 class="fw-bold" style="margin-bottom:0">Berikan rating Anda untuk Rencana Usaha/Kegiatan ini</h4>
            <div style="display: flex;align-items: center;">
              <div>
                <p class="fw-bold">1 = Tidak Relevan</p>
              </div>
              <div style="margin:0 1rem">
                <el-rate v-model="formData.relevance" />
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
        <el-button :loading="loadingSubmit" type="primary" @click="handleSubmit">Tambah</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const publicSptResource = new Resource('public-spt');

export default {
  name: 'SPT',
  components: {},
  data() {
    return {
      tableData: [],
      radio: '1',
      rating: 5,
      ratingCreate: 0,
      outerVisible: false,
      innerVisible: false,
      loading: false,
      loadingSubmit: false,
      formData: {
        name: null,
        role: null,
        nik: null,
        photo: null,
        phone: null,
        email: null,
        comment: null,
        worries: null,
        hope: null,
        relevance: null,
      },
    };
  },
  created() {
    this.getData();
  },
  methods: {
    async getData() {
      this.loading = true;
      this.tableData = await publicSptResource.list({});
      this.loading = false;
    },
    handleUploadChange(file, fileList) {
      this.formData.photo = file.raw;
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      const formData = new FormData();
      formData.append('name', this.formData.name);
      formData.append('role', this.formData.role);
      formData.append('nik', this.formData.nik);
      formData.append('photo', this.formData.photo);
      formData.append('phone', this.formData.phone);
      formData.append('email', this.formData.email);
      formData.append('comment', this.formData.comment);
      formData.append('worries', this.formData.worries);
      formData.append('hope', this.formData.hope);
      formData.append('relevance', this.formData.relevance);

      try {
        await publicSptResource.store({
          formData: this.formData,
        });
        this.outerVisible = false;
        this.formData = {};
      } catch (error) {
        this.loadingSubmit = false;
      }

      this.loadingSubmit = false;
      this.getData();
    },
    checkRelevance(rate) {
      if (+rate >= 3) {
        return true;
      }

      return false;
    },
  },
};
</script>
<style scoped>
  .link{text-decoration:underline; color:#50b0e3; cursor:pointer;font-size: 0.7rem;font-weight: bold;}
  .pl{padding-left: 2rem;}
  .fw-bold {font-weight: bold;}
  .el-form-item{margin-bottom: 0;}
  .el-dialog__body{padding-top: 0;}
</style>
