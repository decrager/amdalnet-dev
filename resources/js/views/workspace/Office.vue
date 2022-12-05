<template>
  <div class="app-container" style="position: relative; display: flex; flex-direction: row;">
    <div id="etherpad-wrapper" style="height: 100%; width: 100%;">
      <div id="placeholder" />
    </div>
    <div class="uji-collab" style="margin-bottom: 10vh; max-width: 20%; height: 100%; background-color: #eeeee5;">
      <div style="align-content: center; justify-content: center; display: flex; position: absolute; right: 1.5rem; top: 1.7rem;">
        <el-button v-if="!showForm" style="border: 0; color: #363636; background-color: #bababa;" @click="showHide">&lt;</el-button>
        <el-button v-if="showForm" style="border: 0; color: #363636; background-color: #bababa;" @click="showHide">&gt;</el-button>
      </div>
      <div v-if="showTable">
        <el-table :data="tableData" style="width: 100%">
          <el-table-columm prop="no" label="no" width="180" />
          <el-table-columm prop="no" label="no" width="180" />
          <el-table-columm prop="no" label="no" width="180" />
        </el-table>
      </div>
      <!-- <div v-if="showForm" style="background-color: #eeeee5; padding-top: 0.5rem; padding-right: 1rem; padding-left: 1rem;">
        <div style="display: flex; flex-direction: row; margin-bottom: 1vh; justify-content: space-between;">
          <el-select
            v-if="isTUK"
            v-model="idCat"
            placeholder="Pilih Kategori"
          >
            <el-option
              v-for="item in categories"
              :key="item.id"
              :label="item.title"
              :value="item.id"
            />
          </el-select>
          <el-button @click="simpanRekap">Simpan</el-button>
        </div>
        <div style="max-height: 25rem; overflow: auto;">
          <div style="display: flex;">
            <el-collapse v-model="activeNames" accordion style="width: 100%;">
              <el-collapse-item :title="(!isTUK ? 'TODO Pakar/Instansi/Pusat/Daerah ' : '') + this.$store.getters.user.name">
                <el-button v-if="rekaps.length === 0" @click="addNewRekap">Tambah</el-button>
                <div
                  v-for="(rekap, index) in rekaps"
                  :key="rekap.id"
                >
                  <div style="border: 1px solid; border-radius: 1rem; padding: 1em 1.5em 2.5em; display: flex; justify-content: space-around;">
                    <div style="background-color: #f5f5f5;">
                      <div style="padding: 5px; background-color: #39773b; color: #ffffff; font-weight: bold; text-align: center;">
                        <span>Saran/ Pendapat/ Tanggapan</span>
                      </div>
                      <div style="padding: 5px;">
                        <TextEditor
                          v-model="rekaps[index].saran_pendapat_tanggapan"
                          output-format="html"
                          :menubar="''"
                          :image="false"
                          :toolbar="['bold italic underline bullist numlist fullscreen']"
                          :height="50"
                          :readonly="isTUK ? 0 : 1"
                        />
                      </div>
                    </div>
                    <div style="background-color: #f5f5f5;">
                      <div style="padding: 5px; background-color: #39773b; color: #ffffff; font-weight: bold; text-align: center;">
                        <span>Halaman</span>
                      </div>
                      <div>
                        <el-input v-model="rekaps[index].halaman_tuk" :disabled="isTUK ? false : true" />
                      </div>
                    </div>
                    <div style="background-color: #f5f5f5;">
                      <div style="padding: 5px; background-color: #39773b; color: #ffffff; font-weight: bold; text-align: center;">
                        <span>Perbaikan/ Tanggapan Pemrakarsa</span>
                      </div>
                      <div style="padding: 5px;">
                        <TextEditor
                          v-model="rekaps[index].perbaikan"
                          output-format="html"
                          :menubar="''"
                          :image="false"
                          :toolbar="['bold italic underline bullist numlist fullscreen']"
                          :height="50"
                          :readonly="isTUK ? 1 : 0"
                        />
                      </div>
                    </div>
                    <div style="background-color: #f5f5f5;">
                      <div style="padding: 5px; background-color: #39773b; color: #ffffff; font-weight: bold; text-align: center;">
                        <span>Halaman</span>
                      </div>
                      <div>
                        <el-input v-model="rekaps[index].halaman_perbaikan" :disabled="isTUK ? true : false" />
                      </div>
                    </div>
                  </div>
                  <div v-if="isTUK" style="display: flex; align-content: center; justify-content: flex-end; padding: 0.5rem;">
                    <el-button type="danger" icon="el-icon-delete" @click="rekaps.splice(index, 1)">Hapus</el-button>
                    <el-button type="success" icon="el-icon-edit" @click="addNewRekap">Tambah</el-button>
                  </div>
                </div>
              </el-collapse-item>
            </el-collapse>
          </div>
        </div>
      </div> -->
      <div v-if="showForm" style="background-color: #404040; padding-top: 3rem; padding-right: 1rem; padding-left: 1rem; margin-left: 1px; height: 100%;">
        <div style="background-color: #555555;">
          <div style="display: flex; align-items: center; padding: 5px; justify-content: space-between;">
            <div style="max-width: 50%; padding-right: 10px; color: #fff; font-weight: bold;">
              <span>Halaman</span>
            </div>
            <div style="max-width: 50%;">
              <el-input v-model="rekaps.halaman_tuk" />
            </div>
          </div>
        </div>
        <div style="background-color: #555555; padding-top: 5px;">
          <div style="padding: 5px; background-color: #39773b; color: #ffffff; font-weight: bold; text-align: center;">
            <span>Saran/ Pendapat/ Tanggapan</span>
          </div>
          <div style="padding: 5px;">
            <TextEditor
              v-model="rekaps.saran_pendapat_tanggapan"
              output-format="html"
              :menubar="''"
              :image="false"
              :toolbar="['bold italic underline bullist numlist fullscreen']"
              :height="50"
            />
          </div>
        </div>
        <div style="background-color: #555555; padding-top: 1rem;">
          <el-button v-if="showForm" style="border: 0; color: #363636; background-color: #bababa; width: 100%;" @click="showHide">Simpan</el-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import WorkspaceResource from '@/api/workspace';
import TextEditor from '@/components/Tinymce';
import { mapGetters } from 'vuex';
const workspaceResource = new WorkspaceResource();

export default {
  components: {
    TextEditor,
  },
  props: {
    project: {
      type: Object,
      default: null,
    },
    filename: {
      type: String,
      default: 'sample.docx',
    },
    createTime: {
      type: String,
      default: '00:00:00',
    },
  },
  data() {
    return {
      input: '',
      comment: null,
      activeNames: '',
      officeUrl: '',
      selectedTreeId: 1,
      sessionID: null,
      loading: false,
      docEditor: null,
      idCat: null,
      categories: [
        {
          id: 1,
          title: 'Pakar Ahli',
        },
        {
          id: 2,
          title: 'Instansi Pusat',
        },
        {
          id: 3,
          title: 'Instansi Daerah',
        },
      ],
      rekaps: [
        {
          saran_pendapat_tanggapan: 'Saran pendapat tanggapan',
          halaman_tuk: 5,
          perbaikan: 'Perbaikan',
          halaman_perbaikan: 7,
        },
      ],
      showForm: false,
    };
  },
  computed: {
    ...mapGetters({
      'userInfo': 'user',
      'userId': 'userId',
    }),
    isPenyusun() {
      return this.userInfo.roles.includes('formulator');
    },
    isPemrakarsa() {
      return this.userInfo.roles.includes('initiator');
    },
    isTUK() {
      return this.userInfo.roles.includes('examiner-substance');
    },
    padSrc() {
      if (this.sessionID) {
        return process.env.MIX_OFFICE_URL + '/auth_session?sessionID=' + this.sessionID + '&padName=' + this.selectedTreeId;
      }
      return '';
    },
  },
  watch: {
    activeNames: function(val) {
      console.log({ activeName: val });
      console.log({ data: this.$store.getters });
      this.activeName = val[1];
    },
  },
  async mounted() {
    console.log('props:', this.$route.params.id, this.project, process.env.MIX_BASE_API);
    // console.log(process.env.MIX_OFFICE_URL);
    this.officeUrl = process.env.MIX_OFFICE_URL;
    this.addOfficeScript();
  },
  methods: {
    // checkTUK() {
    //   // TODO: beresin gun
    //   this.isTUK = true;
    // },
    addNewRekap: function() {
      this.rekaps.push({
        saran_pendapat_tanggapan: null,
        halaman_tuk: null,
        perbaikan: null,
        halaman_perbaikan: null,
      });
    },
    simpanRekap(){
      console.log({
        send: this.rekaps,
        email: this.$store.getters.user.email,
      });
    },
    resize() {
      console.log('resize');
    },
    showHide() {
      this.showForm = !this.showForm;
    },
    handleTemplateUploadChange(file, fileList) {
      // add file to multipart
      this.loading = true;
      const formData = new FormData();
      formData.append('file', file.raw);

      workspaceResource
        .importTemplate(formData)
        .then(response => {
          console.log(response);
          this.$message({
            message: 'Berhasil Load Template',
            type: 'success',
            duration: 5 * 1000,
          });
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
          this.loading = false;
        });
    },

    beforeTemplateUpload(file) {
      console.log('file', file.type);
      // const isJPG = file.type === 'image/jpeg';
      const isLt2M = file.size / 1024 / 1024 < 2;

      // if (!isJPG) {
      //   this.$message.error('Avatar picture must be JPG format!');
      // }
      // if (!isLt2M) {
      //   this.$message.error('Avatar picture size can not exceed 2MB!');
      // }
      return isLt2M;
    },

    addOfficeScript() {
      const officeScript = document.createElement('script');
      console.log('x', process.env.MIX_OFFICE_URL, process.env.MIX_ETHERPAD_URL);
      officeScript.setAttribute('src', process.env.MIX_OFFICE_URL + '/web-apps/apps/api/documents/api.js');
      document.head.appendChild(officeScript);
      officeScript.onload = () => {
        this.createOfficeEditor();
      };
    },

    createOfficeEditor() {
      console.log('create office');
      let filename = this.filename;
      if (this.$route.params.filename) {
        filename = this.$route.params.filename;
      }

      let createTime = this.createTime;
      if (this.$route.params.createTime) {
        createTime = this.$route.params.createTime;
      }
      workspaceResource
        .getConfig(this.$route.params.id, filename, createTime)
        .then(resp => {
          console.log(resp);
          this.docEditor = new window.DocsAPI.DocEditor('placeholder', resp);
        });
    },

    etherpadAuth() {
      workspaceResource
        .sessionInit()
        .then(response => {
          console.log(response, response.data.sessionID);
          this.sessionID = response.data.sessionID;
        });
    },
  },
};
</script>

<style lang="scss">
  body {
    overflow: hidden;
  }
  .vtl {
    .vtl-tree-margin {
      margin-left: 1em;
    }
    .vtl-drag-disabled {
      background-color: #d0cfcf;
      &:hover {
        background-color: #d0cfcf;
      }
    }
    .vtl-disabled {
      background-color: #d0cfcf;
    }
    .vtl-node {
      .vtl-node-content {
        padding: 0 3px;
      }
      .vtl-node-main {
        font-size: 14px;
      }
    }
  }
</style>

<style scoped lang="scss">
  iframe {
    height: calc(100vh - 94px);
  }
  .app-container {
    height: 100vh;
    width: 100%;
  }

  .left-container {
    /* background-color: #F38181; */
    height: 100%;
    // overflow: scroll;
  }

  .right-container {
    /* background-color: #FCE38A; */
    height: 100%;
  }

  .icon {
    &:hover {
      cursor: pointer;
    }
  }

  .muted {
    color: gray;
    font-size: 80%;
  }
</style>
