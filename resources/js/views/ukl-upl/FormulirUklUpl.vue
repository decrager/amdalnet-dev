<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <workflow />
      <h2>Formulir Kerangka Acuan</h2>
      <span>
        <el-button
          class="pull-right"
          type="success"
          size="small"
          icon="el-icon-check"
          :disabled="!isSubmitEnabled"
          @click="handleSaveForm()"
        >
          Simpan & Lanjutkan
        </el-button>
      </span>
      <el-collapse v-model="activeName" :accordion="true">
        <el-collapse-item name="1" title="PELINGKUPAN">
          <pelingkupan />
        </el-collapse-item>
        <el-collapse-item name="2" title="MATRIKS IDENTIFIKASI DAMPAK">
          <matrik-identifikasi-dampak
            @handleReloadVsaList="handleReloadVsaList"
          />
        </el-collapse-item>
        <el-collapse-item name="3" title="PETA BATAS WILAYAH STUDI & PETA PENDUKUNG">
          <el-form label-position="top" label-width="100px">
            <el-form-item label="Peta Batas Ekologis" :required="required">
              <el-col :span="6" style="margin-right:1em;">
                <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
                  <el-col :span="17"><el-input placeholder="Versi SHP" /></el-col>
                  <el-col :span="4" style="margin-left:1em;">
                    <el-upload>
                      <el-button size="small" type="info">browse</el-button>
                    </el-upload>
                  </el-col>
                </el-row>
              </el-col>

              <el-col :span="6" style="margin-right:1em;">
                <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
                  <el-col :span="17"><el-input placeholder="Versi PDF" /></el-col>
                  <el-col :span="4" style="margin-left:1em;">
                    <el-upload>
                      <el-button size="small" type="info">browse</el-button>
                    </el-upload>
                  </el-col>
                </el-row>
              </el-col>
            </el-form-item>
            <el-form-item label="Peta Batas Sosial" :required="required">
              <el-col :span="6" style="margin-right:1em;">
                <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
                  <el-col :span="17"><el-input placeholder="Versi SHP" /></el-col>
                  <el-col :span="4" style="margin-left:1em;">
                    <el-upload>
                      <el-button size="small" type="info">browse</el-button>
                    </el-upload>
                  </el-col>
                </el-row>
              </el-col>

              <el-col :span="6" style="margin-right:1em;">
                <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
                  <el-col :span="17"><el-input placeholder="Versi PDF" /></el-col>
                  <el-col :span="4" style="margin-left:1em;">
                    <el-upload>
                      <el-button size="small" type="info">browse</el-button>
                    </el-upload>
                  </el-col>
                </el-row>
              </el-col>
            </el-form-item>

            <el-form-item label="Peta Batas Wilayah Studi" :required="required">
              <el-col :span="6" style="margin-right:1em;">
                <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
                  <el-col :span="17"><el-input placeholder="Versi SHP" /></el-col>
                  <el-col :span="4" style="margin-left:1em;">
                    <el-upload>
                      <el-button size="small" type="info">browse</el-button>
                    </el-upload>
                  </el-col>
                </el-row>
              </el-col>

              <el-col :span="6" style="margin-right:1em;">
                <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
                  <el-col :span="17"><el-input placeholder="Versi PDF" /></el-col>
                  <el-col :span="4" style="margin-left:1em;">
                    <el-upload>
                      <el-button size="small" type="info">browse</el-button>
                    </el-upload>
                  </el-col>
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
        <el-collapse-item name="4" title="DAMPAK POTENSIAL & DAMPAK PENTING HIPOTETIK">
          <dampak-potensial
            @handleReloadVsaList="handleReloadVsaList"
          />
          <dampak-penting-hipotetik
            @handleReloadVsaList="handleReloadVsaList"
          />
        </el-collapse-item>
        <el-collapse-item name="5" title="METODE STUDI">
          <metode-studi
            @handleReloadVsaList="handleReloadVsaList"
            @handleEnableSubmitForm="handleEnableSubmitForm"
          />
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
    </el-card>
  </div>
</template>

<script>
import Pelingkupan from './components/Pelingkupan.vue';
import MatrikIdentifikasiDampak from './components/MatrikIdentifikasiDampak.vue';
import DampakPotensial from './components/DampakPotensial.vue';
import DampakPentingHipotetik from './components/DampakPentingHipotetik.vue';
import MetodeStudi from './components/MetodeStudi.vue';
import Workflow from '@/components/Workflow';

export default {
  name: 'FormulirUklUpl',
  components: {
    Pelingkupan,
    MatrikIdentifikasiDampak,
    DampakPotensial,
    DampakPentingHipotetik,
    MetodeStudi,
    Workflow,
  },
  data() {
    return {
      idProject: 0,
      isSubmitEnabled: false,
      scopingActive: true,
      matriksActive: false,
      dampakPotensialActive: false,
      dampakPentingHipotetikActive: false,
      metodeStudiActive: false,
      activeName: '1',
    };
  },
  mounted() {
    this.setProjectId();
    this.$store.dispatch('getStep', 3);
  },
  methods: {
    setProjectId(){
      const id = this.$route.params && this.$route.params.id;
      this.idProject = id;
    },
    handleSaveForm() {
      const id = this.$route.params && this.$route.params.id;
      this.$router.push({
        name: 'DokumenUklUpl',
        params: id,
      });
    },
    handleEnableSubmitForm() {
      this.isSubmitEnabled = true;
    },
    handleReloadVsaList(tab) {
      if (tab === 'pelingkupan') {
        this.activeName = '1';
      } else if (tab === 'matriks-identifikasi-dampak') {
        this.activeName = '2';
      } else if (tab === 'peta-batas') {
        this.activeName = '3';
      } else if (tab === 'dampak-penting') {
        this.activeName = '4';
      } else if (tab === 'metode-studi') {
        this.activeName = '5';
      } else if (tab === 'matriks-dph') {
        this.activeName = '6';
      } else if (tab === 'bagan-alir') {
        this.activeName = '7';
      }
    },
  },
};
</script>

<style>
.vsa-list {
  /* CSS Variables */
  --vsa-max-width: 100%;
  --vsa-min-width: 300px;
  --vsa-heading-padding: 1rem 1rem;
  --vsa-text-color: rgba(55, 55, 55, 1);
  --vsa-highlight-color: #1e5128;
  --vsa-bg-color: rgba(255, 255, 255, 1);
  --vsa-border-color: rgba(0, 0, 0, 0.2);
  --vsa-border-width: 1px;
  --vsa-border-style: solid;
  --vsa-border: var(--vsa-border-width) var(--vsa-border-style) var(--vsa-border-color);
  --vsa-content-padding: 1rem 1rem;
  --vsa-default-icon-size: 1;

  display: block;
  max-width: var(--vsa-max-width);
  min-width: var(--vsa-min-width);
  width: 100%;

  /* Reset the list styles */
  padding: 0;
  margin: 0;
  list-style: none;

  border: var(--vsa-border);
  color: var(--vsa-text-color);
  background-color: var(--vsa-bg-color);
}
.vsa-list [hidden]{
  display:none
}

.vsa-item__content{
  margin:0;
  padding:var(--vsa-content-padding);
  overflow: auto;
}

.vsa-item__trigger:focus,.vsa-item__trigger:hover{
    outline:none;
    background-color:var(--vsa-highlight-color);
    color: white;
}

h2 {
  display:inline-block;
  margin-block-start: 0em;
}

.el-collapse-item__header {
  /* background-color: #296d36; */
  background-color: #1E5128;
  padding-left: 10px;
  font-size: large;
  font-weight: bold;
  color: rgb(196, 196, 196);
}
.el-collapse-item__content {
  padding-top: 10px;
}

table th, table td {word-break: normal !important; padding:.5em; line-height:1.2em; border: 1px solid #eee;}
table td { vertical-align: top !important;}
table thead  {background-color:#6cc26f !important; color: white !important;}
table td.title, table tr.title td, table.title td { text-align:left;}
div.div-fka {
  padding: 0.5em;
  margin-bottom: 0.6em;
  background-color: #fafafa;
}
</style>
