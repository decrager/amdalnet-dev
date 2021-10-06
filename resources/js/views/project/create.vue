<template>
  <div class="form-container" style="padding: 24px">
    <el-form
      ref="categoryForm"
      :model="currentProject"
      label-position="top"
      label-width="200px"
      style="max-width: 100%"
    >
      <el-row :gutter="4">
        <el-col :span="12">
          <el-col :span="16">
            <el-form-item
              label="Pilih Kegiatan Yang Sudah Diproses di OSS"
              prop="titleProject"
            >
              <el-select
                v-model="currentProject.title_project"
                placeholder="Select"
                style="width: 100%"
              >
                <el-option
                  v-for="item in projectOptions"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="8">
            <el-form-item label="Bidang Kegiatan" prop="project">
              <el-select
                v-model="currentProject.project_field"
                placeholder="Select"
                style="width: 100%"
              >
                <el-option
                  v-for="item in projectFieldOptions"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
            </el-form-item>
          </el-col>
        </el-col>
        <el-col :span="12">
          <el-col :span="12">
            <el-form-item label="Provinsi" prop="provinsi">
              <el-select
                v-model="currentProject.id_prov"
                placeholder="Select"
                style="width: 100%"
              >
                <el-option
                  v-for="item in provinceOptions"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="Kab / Kota" prop="kabkot">
              <el-select
                v-model="currentProject.id_district"
                placeholder="Select"
                style="width: 100%"
              >
                <el-option
                  v-for="item in cityOptions"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
            </el-form-item>
          </el-col>
        </el-col>
      </el-row>
      <el-row :gutter="4">
        <el-col :span="12">
          <el-col :span="8">
            <el-form-item label="KBLI" prop="kbli">
              <el-input v-model="currentProject.kbli" />
            </el-form-item>
          </el-col>
          <el-col :span="8">
            <el-form-item label="Tingkat Resiko" prop="riskLevel">
              <el-input v-model="currentProject.risk_level" />
            </el-form-item>
          </el-col>
          <el-col :span="8">
            <el-form-item label="Jenis Kegiatan" prop="jenisKegiatan">
              <el-select
                v-model="currentProject.project_type"
                placeholder="Select"
                style="width: 100%"
              >
                <el-option
                  v-for="item in projectTypeOptions"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
            </el-form-item>
          </el-col>
        </el-col>
        <el-col :span="12">
          <el-col :span="24">
            <el-form-item label="Alamat" prop="address">
              <el-input v-model="currentProject.location" />
            </el-form-item>
          </el-col>
        </el-col>
      </el-row>
      <el-row :gutter="4">
        <el-col :span="12">
          <el-row :gutter="4">
            <el-col :span="8">
              <el-form-item label="Tahun Kegiatan" prop="projectYear">
                <el-select
                  v-model="currentProject.projectYear"
                  placeholder="Select"
                  style="width: 100%"
                >
                  <el-option
                    v-for="item in projectYearOptions"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"
                  />
                </el-select>
              </el-form-item>
            </el-col>
            <el-col :span="8">
              <el-form-item label="Skala/Besaran Kegiatan" prop="projectScale">
                <el-col
                  :span="12"
                ><el-input
                  v-model="currentProject.projectScale"
                /></el-col>
                <el-col
                  :span="12"
                ><el-select
                  v-model="currentProject.unit"
                  placeholder="Select"
                  style="width: 100%"
                >
                  <el-option
                    v-for="item in unitOptions"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"
                  /> </el-select></el-col>
              </el-form-item>
            </el-col>
            <el-col :span="8">
              <el-form-item
                label="Berita Pengumuman"
                prop="announcement"
                style="padding-right: 2px"
              >
                <div
                  style="
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    height: 36px;
                  "
                >
                  <el-button
                    icon="el-icon-document-copy"
                    type="primary"
                    size="mini"
                    style="margin-left: 15px"
                    @click="'#';"
                  >Upload</el-button>
                  <span>{{ fileName }}</span>
                </div>
                <!-- <el-input v-model="currentProject.announcement" /> -->
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="4">
            <el-col :span="8">
              <el-form-item label="Skala/Besaran Peta" prop="mapScale">
                <el-col
                  :span="12"
                ><el-input
                  v-model="currentProject.mapScale"
                /></el-col>
                <el-col
                  :span="12"
                ><el-select
                  v-model="currentProject.project_type"
                  placeholder="Select"
                  style="width: 100%"
                >
                  <el-option
                    v-for="item in projectTypeOptions"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"
                  />
                </el-select></el-col>
              </el-form-item>
            </el-col>
            <el-col :span="16">
              <el-form-item label="Upload Peta" prop="uploadPeta">
                <el-col :span="8">
                  <el-select
                    v-model="currentProject.project_type"
                    placeholder="Select"
                    style="width: 100%"
                  >
                    <el-option
                      v-for="item in projectTypeOptions"
                      :key="item.value"
                      :label="item.label"
                      :value="item.value"
                    />
                  </el-select>
                </el-col>
                <el-col :span="16">
                  <div
                    style="
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    height: 36px;
                  "
                  >
                    <el-button
                      icon="el-icon-document-copy"
                      type="primary"
                      size="mini"
                      style="margin-left: 15px"
                      @click="'#';"
                    >Upload</el-button>
                    <span>{{ fileName }}</span>
                  </div></el-col>
              </el-form-item>
            </el-col>
          </el-row>
        </el-col>
        <el-col :span="12" style="text-align: center;">
          <h1>Map will be here!!</h1>
        </el-col>
      </el-row>
      <el-row :gutter="16">
        <el-col :span="12">
          <el-form-item
            prop="locationDesc"
            style="margin-bottom: 30px"
            label="Deskripsi Lokasi"
          >
            <tinymce
              ref="editor"
              v-model="currentProject.content"
              :height="200"
            />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item
            prop="locationDesc"
            style="margin-bottom: 30px"
            label="Deskripsi Kegiatan"
          >
            <tinymce
              ref="editor"
              v-model="currentProject.content"
              :height="200"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="8">
        <el-col :span="12">
          <el-form-item label="Kewenangan" prop="kewenangan">
            <el-select
              v-model="currentProject.project_type"
              placeholder="Select"
              style="width: 100%"
            >
              <el-option
                v-for="item in projectTypeOptions"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Tim Penyusun" prop="timPenyusun">
            <el-select
              v-model="currentProject.project_type"
              placeholder="Select"
              style="width: 100%"
            >
              <el-option
                v-for="item in projectTypeOptions"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <div slot="footer" class="dialog-footer">
      <el-button @click="handleCancel()"> Cancel </el-button>
      <el-button type="primary" @click="handleSubmit()"> Confirm </el-button>
    </div>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';

export default {
  name: 'CreateProject',
  components: { Tinymce },
  data() {
    return {
      currentProject: {},
      fileName: 'No File Selected.',
      projectOptions: [
        {
          value: 'Pabrik Pupuk',
          label: 'Pabrik Pupuk',
        },
        {
          value: 'Pabrik Makanan',
          label: 'Pabrik Makanan',
        },
      ],
      projectFieldOptions: [
        {
          value: 'Bidang Perindustrian',
          label: 'Bidang Perindustrian',
        },
        {
          value: 'Bidang Makanan',
          label: 'Bidang Makanan',
        },
      ],
      provinceOptions: [
        {
          value: '1',
          label: 'Jawa Timur',
        },
        {
          value: '2',
          label: 'Jakardah',
        },
      ],
      cityOptions: [
        {
          value: '1',
          label: 'Surabaya',
        },
        {
          value: '2',
          label: 'Jaksel',
        },
      ],
      projectTypeOptions: [
        {
          value: 'Baru',
          label: 'Baru',
        },
        {
          value: 'Lama',
          label: 'Lama',
        },
      ],
      projectYearOptions: [
        {
          value: '2021',
          label: '2021',
        },
        {
          value: '2022',
          label: '2022',
        },
      ],
      unitOptions: [
        {
          value: 'cm',
          label: 'cm',
        },
        {
          value: 'ha',
          label: 'ha',
        },
      ],
    };
  },
  methods: {
    handleCancel() {
      this.$router.push('/project');
    },
    handleSubmit() {
      console.log(this.currentProject);
    },
  },
};
</script>
