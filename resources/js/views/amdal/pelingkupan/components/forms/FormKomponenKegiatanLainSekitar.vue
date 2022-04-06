<template>
  <div>
    <el-dialog
      :title="'Master Data Komponen Kegiatan Lain Sekitar'"
      :visible.sync="show"
      width="40%"
      height="450"
      :destroy-on-close="true"
      :before-close="handleClose"
      :close-on-click-modal="false"
      @open="onOpen"
    >
      <div v-loading="isSaving">
        <el-form label-position="top">
          <el-form-item v-if="mode === 0" label="Nama Kegiatan">
            <el-select
              v-model="data.id"
              style="width:100%"
              clearable
              filterable
              :disabled="noMaster"
              :loading="loadingMaster"
              loading-text="Memuat data..."
              @change="onSelectActivity"
              @clear="onClearActivity"
            >
              <el-option
                v-for="item in master"
                :key="item.value"
                :label="item.name"
                :value="item.id"
              >
                <span>{{ item.name }} &nbsp;<i v-if="item.is_master" class="el-icon-success" style="color:#2e6b2e;" /></span>
              </el-option>
            </el-select>

            <el-checkbox v-model="noMaster" @change="onChangeInput"><span style="font-size: 90%;">Menambahkan Komponen Kegiatan Lain Sekitar</span></el-checkbox>

            <el-input
              v-if="noMaster"
              v-model="data.name"
              placeholder="Nama Komponen Kegiatan Lain Sekitar..."
            />
          </el-form-item>
          <div v-else>
            <div><strong>Kegiatan Lain Sekitar</strong></div>
            <div style="font-size:120%; color:#202020;">
              {{ data.name }} &nbsp;<span><i v-if="data.is_master" class="el-icon-success" style="color:#2e6b2e;" /></span>
            </div>
          </div>
          <div style="font-weight:bold;margin:1.5em 0 0.5em;">Lokasi</div>
          <div style="padding: 1em 1.5em; border:1px solid #e0e0e0; border-radius: 0.3em; margin-bottom:1em;">
            <el-row :gutter="20">
              <el-col :span="12">
                <el-form-item label="Provinsi">
                  <el-select
                    v-model="data.province_id"
                    style="width:100%"
                    clearable
                    filterable
                    :loading="loadingProvinces"
                    loading-text="Memuat data..."
                    placeholder="Pilih Provinsi"
                    @change="onSelectProvince"
                  >
                    <el-option
                      v-for="item in provinces"
                      :key="item.value"
                      :label="item.name"
                      :value="item.id"
                    />
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="Kabupaten/Kota">
                  <el-select
                    v-model="data.district_id"
                    style="width:100%"
                    clearable
                    filterable
                    :loading="loadingDistricts"
                    loading-text="Memuat data..."
                    placeholder="Pilih Kabupaten/Kota"
                  >
                    <el-option
                      v-for="item in districts"
                      :key="item.value"
                      :label="item.name"
                      :value="item.id"
                    />
                  </el-select>
                </el-form-item>
              </el-col>
            </el-row>
            <el-form-item label="Alamat" prop="address">
              <el-input
                v-model="data.address"
                type="textarea"
                :autosize="{ minRows: 3, maxRows: 5}"
                maxlength="256"
                show-word-limit
              />
            </el-form-item>
          </div>
          <el-form-item label="Deskripsi" prop="description">
            <klseditor
              :key="'master_kegiatan_lain_scoping_113'"
              v-model="data.description"
              output-format="html"
              :menubar="''"
              :image="false"
              :height="100"
              :toolbar="['bold italic underline bullist numlist  preview undo redo fullscreen']"
              style="width:100%"
            />
          </el-form-item>
          <el-form-item label="Besaran" prop="measurement">
            <el-input
              v-model="data.measurement"
              type="textarea"
              :autosize="{ minRows: 3, maxRows: 5}"
            />
          </el-form-item>
        </el-form>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button type="default" @click="handleClose">Batal</el-button>
        <el-button type="primary" @click="handleSaveForm">Simpan</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
import KLSEditor from '@/components/Tinymce';
import Resource from '@/api/resource';
const klsResource = new Resource('kegiatan-lain-sekitar');
const provinceResource = new Resource('provinces');
const districtResource = new Resource('districts');
const projectKSLResource = new Resource('project-kegiatan-lain-sekitar');

export default {
  name: 'FormKomponenKegiatanLainSekitar',
  components: {
    'klseditor': KLSEditor,
  },
  props: {
    show: {
      type: Boolean,
      default: true,
    },
    mode: {
      type: Number,
      default: 0,
    },
    input: {
      type: Object,
      default: () => null,
    },
  },
  data(){
    /* var validateComponentName = (rule, value, callback) => {
      if (((this.noMaster === true) && (value === ''))) {
        callback(new Error('Nama Kegiatan Lain Sekitar wajib diisi'));
      } else {
        callback();
      }
    };
    var validateComponent = (rule, value, callback) => {
      if ((this.noMaster === false) && ((this.data.id === null) || (this.data.id <= 0))) {
        callback(new Error('Nama Kegiatan Lain Sekitar wajib dipilih'));
      } else {
        callback();
      }
    };*/
    return {
      isSaving: false,
      data: null,
      noMaster: false,
      loadingMaster: false,
      loadingProvinces: false,
      loadingDistricts: false,
      provinces: [],
      districts: [],
      master: [],
      /* rules: {
        id: [{validator: validateComponent, trigger: 'change'}],
        name: [{ validator: validateComponentName, trigger: 'blur'}],
        address:  [
            { required: true, message: 'Alamat wajib diisi', trigger: 'blur' }
          ],
        province_id: [
          { required: true, message: 'Provinsi wajib diisi', trigger: 'change' }
        ],
        district_id: [
          { required: true, message: 'Kabupaten/Kota wajib diisi', trigger: 'change' }
        ],
        description: [
          { required: true, message: 'Deskripsi wajib diisi', trigger: 'blur' }
        ],
        measurement: [
          { required: true, message: 'Besaran wajib diisi', trigger: 'blur' }
        ]
      },*/
    };
  },
  mounted(){
    this.initData();
    this.getMaster();
    this.getProvinces();
  },
  methods: {
    initData(){
      this.data = {
        id: null,
        name: '',
        value: '',
        description: '',
        measurement: '',
        is_master: false,
        province_id: null,
        district_id: null,
        address: '',
        originator_id: null,
        id_project: parseInt(this.$route.params && this.$route.params.id),
        id_project_kegiatan_lain_sekitar: null,
      };
      this.noMaster = false;
    },
    async getMaster(){
      this.loadingMaster = false;
      this.master = [];
      await klsResource.list({
        id_project: parseInt(this.$route.params && this.$route.params.id),
      }).then((res) => {
        this.master = res.data;
      }).finally(() => {
        this.loadingMaster = false;
      });
    },
    async getProvinces(){
      this.loadingProvinces = true;
      await provinceResource.list()
        .then((res) => {
          this.provinces = res.data;
        }).finally(() => {
          this.loadingProvinces = false;
        });
    },
    async getDistricts(){
      this.loadingDistricts = true;
      await districtResource.list({
        idProv: this.data.province_id,
      })
        .then((res) => {
          this.districts = res.data;
        })
        .finally(() => {
          this.loadingDistricts = false;
        });
    },
    async handleSaveForm(){
      this.isSaving = true;
      await projectKSLResource.store(this.data)
        .then((res) => {
          this.data.id_project_kegiatan_lain_sekitar = res.id;
          this.data.id = res.kegiatan_lain_sekitar_id;
          this.$emit('onSave', this.data);
        })
        .finally(() => {
          this.isSaving = false;
          this.handleClose();
        });
    },
    onOpen(){
      switch (this.mode){
        case 0:
          this.initData();
          this.getMaster();
          break;
        case 1:
          // edit mode
          this.data = this.input;
          this.data.id_project = this.data.originator_id;
          this.getDistricts();
          break;
      }

      console.log(this.data);
    },
    handleClose(){
      this.$emit('onClose', true);
    },
    onSelectActivity(val){
      const activity = this.master.find(a => a.id === val);
      if (activity){
        this.data.name = activity.name;
        this.data.value = activity.name;
        this.data.is_master = activity.is_master;
        this.data.originator_id = activity.originator_id;
      }
    },
    onClearActivity(){
      this.data.name = '';
      this.data.value = '';
      this.data.is_master = false;
      this.data.originator_id = null;
      this.data.id = null;
    },
    onSelectProvince(sel){
      this.getDistricts();
    },
  },
};
</script>
