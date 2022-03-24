<template>
  <div>
    <el-dialog
      :title="operations[mode] + ' Pelingkupan'"
      :visible.sync="show"
      width="60%"
      height="450"
      :destroy-on-close="true"
      :before-close="handleClose"
      :close-on-click-modal="false"
      @open="onOpen"
    >
      <div v-if="input.project_stage !== null">
        Tahap <br><span style="font-weight:bold">{{ input.project_stage.name }}</span>
      </div>

      <el-card v-if="input.component !== null" style="margin-top:1em;" shadow="never">
        <div slot="header" style="text-align:center; line-height: 1.15em;">
          <span style="font-size:86%">Komponen Kegiatan</span> <br><strong>{{ input.component.name }}</strong>
        </div>
        <el-row :gutter="20" style="margin-top: 1 em;">
          <el-col :span="12">
            <p><strong>Deskripsi</strong></p>
            <p style="padding:10px 8px; border: 1px solid #eee;">{{ input.component.description || 'Belum terisi' }}</p>
          </el-col>
          <el-col :span="12">
            <p><strong>Besaran</strong></p>
            <p style="padding:10px 8px; border: 1px solid #eee;">{{ input.component.measurement || 'Belum terisi' }}</p>
          </el-col>
        </el-row>
        <el-form>
          <div v-for="sub in input.sub_projects" :key="'sub_'+sub.id">
            <p>Kegiatan {{ sub.type }} <strong>{{ sub.name }}</strong></p>
            <el-row :gutter="20" style="margin-top: 1 em;">
              <el-col :span="12">
                <el-form-item label="Deskripsi">
                  <el-input
                    v-model="sub.com_description"
                    type="textarea"
                    :autosize="{ minRows: 3, maxRows: 5}"
                    placeholder="Deskripsi..."
                  />
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="Besaran">
                  <el-input
                    v-model="sub.com_measurement"
                    type="textarea"
                    :autosize="{ minRows: 3, maxRows: 5}"
                    placeholder="Besaran..."
                  />
                </el-form-item>
              </el-col>
            </el-row>
          </div>
        </el-form>
      </el-card>

      <el-card v-if="input.component !== null" style="margin-top:1em;" shadow="never">
        <div slot="header" style="text-align:center; line-height: 1.15em;">
          <span style="font-size:86%">Komponen Lingkungan</span> <br><strong>{{ input.rona_awal.name }}</strong>
        </div>
        <el-row :gutter="20" style="margin-top: 1 em;">
          <el-col :span="12">
            <p><strong>Deskripsi</strong></p>
            <p style="padding:10px 8px; border: 1px solid #eee;">{{ input.rona_awal.description || 'Belum terisi' }}</p>
          </el-col>
          <el-col :span="12">
            <p><strong>Besaran</strong></p>
            <p style="padding:10px 8px; border: 1px solid #eee;">{{ input.rona_awal.measurement || 'Belum terisi' }}</p>
          </el-col>
        </el-row>
        <el-form>
          <div v-for="sub in input.sub_projects" :key="'sub_'+sub.id">
            <p>Kegiatan {{ sub.type }} <strong>{{ sub.name }}</strong></p>
            <el-row :gutter="20" style="margin-top: 1 em;">
              <el-col :span="12">
                <el-form-item label="Deskripsi">
                  <el-input
                    v-model="sub.hue_description"
                    type="textarea"
                    :autosize="{ minRows: 3, maxRows: 5}"
                    placeholder="Deskripsi..."
                  />
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="Besaran">
                  <el-input
                    v-model="sub.hue_measurement"
                    type="textarea"
                    :autosize="{ minRows: 3, maxRows: 5}"
                    placeholder="Besaran..."
                  />
                </el-form-item>
              </el-col>
            </el-row>
          </div>
        </el-form>
      </el-card>

      <span slot="footer" class="dialog-footer">
        <el-button @click="handleClose">Batal</el-button>
        <el-button type="primary" @click="handleSaveForm">Simpan</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
export default {
  name: 'FormPelingkupan',
  props: {
    input: {
      type: Object,
      default: function() {
        return null;
      },
    },
    show: {
      type: Boolean,
      default: false,
    },
    mode: {
      type: Number,
      default: 0,
    },
  },
  data(){
    return {
      operations: ['Tambah', 'Edit'],
    };
  },
  methods: {
    handleSaveForm(){
      this.$emit('onSave', this.input);
      this.close();
    },
    onOpen() {

    },
    handleClose() {
      this.close();
    },
    close(){
      this.$emit('onClose', true);
    },
  },
};
</script>
