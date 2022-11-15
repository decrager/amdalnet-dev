<template>
  <div class="app-container">
    <div id="etherpad-wrapper" style="height: 80%;">
      <div id="placeholder" />
    </div>
    <div class="uji-collab" style="margin-bottom: 10vh;">
      <div style="width: 100%; margin: 1vh;">
        <h2>Saran & Masukan</h2>
      </div>
      <div style="display: flex; flex-direction: row; margin-bottom: 1vh; justify-content: space-between;">
        <el-select
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
        <el-button>Simpan</el-button>
      </div>
      <div style="display: flex;">
        <el-collapse v-model="activeNames" accordion style="width: 100%;">
          <el-collapse-item :title="this.$store.getters.user.name">
            <el-button v-if="rekaps.length === 0" @click="addNewRekap">Tambah</el-button>
            <div
              v-for="(rekap, index) in rekaps"
              :key="rekap.id"
              style="display: flex;"
            >
              <div>
                <span>Saran/ Pendapat/ Tanggapan</span>
                <TextEditor
                  v-model="rekaps[index].saran_pendapat_tanggapan"
                  output-format="html"
                  :menubar="''"
                  :image="false"
                  :toolbar="['bold italic underline bullist numlist fullscreen']"
                  :height="50"
                />
              </div>
              <div>
                <span>Halaman</span>
                <el-input v-model="rekaps[index].halaman_tuk" />
              </div>
              <div>
                <span>Perbaikan/ Tanggapan Pemrakarsa</span>
                <TextEditor
                  v-model="rekaps[index].perbaikan"
                  output-format="html"
                  :menubar="''"
                  :image="false"
                  :toolbar="['bold italic underline bullist numlist fullscreen']"
                  :height="50"
                />
              </div>
              <div>
                <span>Halaman</span>
                <el-input v-model="rekaps[index].halaman_perbaikan" />
              </div>
              <div style="display: flex; flex-direction: column-reverse;">
                <el-button @click="addNewRekap">Tambah</el-button>
                <el-button @click="rekaps.splice(index, 1)">Hapus</el-button>
              </div>
              <!-- <el-button
                @click="rekaps.splice(index, 1)"
              >
                IKI endi tombol e
              </el-button> -->
            </div>
          </el-collapse-item>
        </el-collapse>
        <!-- <el-button @click="addNewRekap">Tambah</el-button> -->
      </div>
      <!-- <div>
        <div style="display: flex;">
          <el-collapse v-model="activeNames" accordion style="width: 90%;">
            <el-collapse-item name="5" title="">
              <template slot="title">
                <el-input v-model="input" class="test" placeholder="Please input" style="width: 50%;" />
              </template>
              <div style="display: flex;">
                <div>
                  <span>Saran/ Pendapat/ Tanggapan</span>
                  <TextEditor
                    v-model="comment"
                    output-format="html"
                    :menubar="''"
                    :image="false"
                    :toolbar="['bold italic underline bullist numlist fullscreen']"
                    :height="50"
                  />
                </div>
                <div>
                  <span>Halaman</span>
                  <el-input />
                </div>
                <div>
                  <span>Perbaikan/ Tanggapan Pemrakarsa</span>
                  <TextEditor
                    v-model="comment"
                    output-format="html"
                    :menubar="''"
                    :image="false"
                    :toolbar="['bold italic underline bullist numlist fullscreen']"
                    :height="50"
                  />
                </div>
                <div>
                  <span>Halaman</span>
                  <el-input />
                </div>
                <el-button>Tambah</el-button>
              </div>
              <div style="display: flex;">
                <div>
                  <span>Saran/ Pendapat/ Tanggapan</span>
                  <TextEditor
                    v-model="comment"
                    output-format="html"
                    :menubar="''"
                    :image="false"
                    :toolbar="['bold italic underline bullist numlist fullscreen']"
                    :height="50"
                  />
                </div>
                <div>
                  <span>Halaman</span>
                  <el-input />
                </div>
                <div>
                  <span>Perbaikan/ Tanggapan Pemrakarsa</span>
                  <TextEditor
                    v-model="comment"
                    output-format="html"
                    :menubar="''"
                    :image="false"
                    :toolbar="['bold italic underline bullist numlist fullscreen']"
                    :height="50"
                  />
                </div>
                <div>
                  <span>Halaman</span>
                  <el-input />
                </div>
              </div>
              <div style="display: flex;">
                <div>
                  <span>Saran/ Pendapat/ Tanggapan</span>
                  <TextEditor
                    v-model="comment"
                    output-format="html"
                    :menubar="''"
                    :image="false"
                    :toolbar="['bold italic underline bullist numlist fullscreen']"
                    :height="50"
                  />
                </div>
                <div>
                  <span>Halaman</span>
                  <el-input />
                </div>
                <div>
                  <span>Perbaikan/ Tanggapan Pemrakarsa</span>
                  <TextEditor
                    v-model="comment"
                    output-format="html"
                    :menubar="''"
                    :image="false"
                    :toolbar="['bold italic underline bullist numlist fullscreen']"
                    :height="50"
                  />
                </div>
                <div>
                  <span>Halaman</span>
                  <el-input />
                </div>
              </div>
            </el-collapse-item>
          </el-collapse>
          <el-button>Tambah</el-button>
        </div>
        <el-collapse v-model="activeNames" accordion style="width: 80%;">
          <el-collapse-item name="3" title="">
            <template slot="title">
              <el-input v-model="input" class="test" placeholder="Please input" style="width: 50%;" />
            </template>
            <div>
              <strong>
                Consistent with real life: in line with the process and logic of real
                life, and comply with languages and habits that the users are used to;
              </strong>
            </div>
          </el-collapse-item>
          <el-collapse-item name="7" title="Bab 2">
            <div>
              <strong>
                Consistent with real life: in line with the process and logic of real
                life, and comply with languages and habits that the users are used to;
              </strong>
            </div>
          </el-collapse-item>
        </el-collapse>
      </div> -->
    </div>
  </div>
</template>

<script>
import WorkspaceResource from '@/api/workspace';
import TextEditor from '@/components/Tinymce';
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
    };
  },
  computed: {
    padSrc() {
      console.log('src:', process.env.MIX_OFFICE_URL, this.selectedTreeId);
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
    addNewRekap: function() {
      this.rekaps.push({
        saran_pendapat_tanggapan: null,
        halaman_tuk: null,
        perbaikan: null,
        halaman_perbaikan: null,
      });
    },
    resize() {
      console.log('resize');
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

      workspaceResource
        .getConfig(this.$route.params.id, filename)
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
    overflow: scroll;
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
