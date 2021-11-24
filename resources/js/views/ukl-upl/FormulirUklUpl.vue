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
      <el-collapse :key="accordionKey" v-model="activeName" :accordion="true">
        <el-collapse-item name="1" title="PELINGKUPAN">
          <pelingkupan
            @handleReloadVsaList="handleReloadVsaList"
          />
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
          <matriks-dampak-penting-hipotetik
            @handleReloadVsaList="handleReloadVsaList"
          />
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
import MatriksDampakPentingHipotetik from './components/MatriksDampakPentingHipotetik.vue';
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
    MatriksDampakPentingHipotetik,
    Workflow,
  },
  data() {
    return {
      accordionKey: 1,
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
      this.accordionKey = this.accordionKey + 1;
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
