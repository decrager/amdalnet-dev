<template>
  <div class="form-container" style="padding: 24px">
    <el-row>
      <el-col
        :span="12"
      ><h2>Informasi rencana Usaha/Kegiatan</h2>
        <el-table
          :data="list"
          style="width: 100%"
          :stripe="true"
          :show-header="false"
        >
          <el-table-column prop="param" />
          <el-table-column prop="value" />
        </el-table>
      </el-col>
      <el-col :span="12">
        <h1>Peta Will Be Here Soon</h1>
      </el-col>
    </el-row>
    <el-row style="padding-top: 32px">
      <el-col :span="12">
        <div><h3>Deskripsi Kegiatan</h3></div>
        <div v-html="project.description" />
      </el-col>
      <el-col :span="12">
        <div><h3>Deskripsi Lokasi</h3></div>
        <div v-html="project.location_desc" />
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="12">
        <h2>Hasil Penapisan</h2>
        <el-row style="padding-bottom: 16px"><el-col :span="12">No Registrasi</el-col>
          <el-col :span="12">123456789</el-col></el-row>
        <el-row style="padding-bottom: 16px"><el-col :span="12">Jenis Dokumen</el-col>
          <el-col :span="12">{{ project.required_doc + ' ' + project.result_risk }}</el-col></el-row>
        <el-row style="padding-bottom: 16px"><el-col :span="12">Kewenangan</el-col>
          <el-col :span="12">Pusat</el-col></el-row>
        <el-row style="padding-bottom: 16px"><el-col :span="12">Pilih Tim Penyusun</el-col>
          <el-col :span="12">
            <el-form
              ref="project"
              :model="project"
              :rules="projectRules"
              label-position="top"
              label-width="200px"
              style="max-width: 100%"
            >
              <el-form-item prop="id_formulator_team">
                <el-select
                  v-model="project.id_formulator_team"
                  placeholder="Pilih"
                  style="width: 100%"
                  :disabled="readonly"
                >
                  <el-option
                    v-for="item in teamOptions"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"
                    style="width: 200px"
                  />
                </el-select>
              </el-form-item>
            </el-form>
          </el-col></el-row>
      </el-col>
    </el-row>
    <div slot="footer" class="dialog-footer">
      <el-button :disabled="readonly" @click="handleCancel()"> Batal </el-button>
      <el-button type="primary" :disabled="readonly" @click="handleSubmit()"> Publikasi </el-button>
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const kbliEnvParamResource = new Resource('kbli-env-params');
const districtResource = new Resource('districts');
const formulatorTeamResource = new Resource('formulator-teams');
const projectResource = new Resource('projects');
const supportDocResource = new Resource('support-docs');
const initiatorResource = new Resource('initiatorsByEmail');

export default {
  name: 'Publish',
  props: {
    project: {
      type: Object,
      default: null,
    },
    readonly: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      kbliEnvParams: null,
      doc_req: 'SPPL',
      risk_level: 'Rendah',
      teamOptions: null,
      kabkot: null,
      list: [],
      projectRules: {
        id_drafting_team: [{ required: true, trigger: 'change', message: 'Data Belum Dipilih' }],
      },
      initiatorData: {},
    };
  },
  async mounted() {
    console.log(this.project);
    await this.getKbliEnvParams();
    await this.getTeamOptions();
    await this.getInitiatorData();
    await this.updateList();
  },
  methods: {
    async getInitiatorData(){
      const data = await this.$store.dispatch('user/getInfo');
      this.project.initiatorData = await initiatorResource.list({ email: data.email });
    },
    async getTeamOptions() {
      const { data } = await formulatorTeamResource.list({});
      this.teamOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
    async getKabKotName(id) {
      const data = await districtResource.get(id);
      console.log(data);
      this.kabkot = data.name;
    },
    calculatedProjectDoc() {
      this.kbliEnvParams.forEach((item) => {
        let tempStatus = true;
        item.conditions.forEach((element) => {
          if (this.checkIfTrue(element)) {
            tempStatus = tempStatus && true;
          } else {
            tempStatus = tempStatus && false;
          }
        });
        if (tempStatus) {
          this.project.required_doc = item.doc_req;
          this.project.result_risk = item.risk_level;
        }
      });

      // check for any condition that not in kbli param
      if (!this.project.required_doc) {
        this.project.required_doc = 'SPPL';
        this.project.result_risk = 'Rendah';
      }
    },
    checkIfTrue(item) {
      const [data1, operator, data2] = item.split(/\s+/);

      switch (operator) {
        case '<=':
          return parseFloat(data1) <= parseFloat(data2);
        case '>=':
          return parseFloat(data1) >= parseFloat(data2);
        case '<':
          return parseFloat(data1) < parseFloat(data2);
        case '>':
          return parseFloat(data1) > parseFloat(data2);

        default:
          break;
      }
    },
    async getKbliEnvParams() {
      const { data } = await kbliEnvParamResource.list({
        kbli: this.project.kbli,
        businessType: this.project.biz_type,
        unit: this.project.scale_unit,
      });
      this.kbliEnvParams = data.map((item) => {
        const items = item.condition.replace(/[\[\"\]]/g, '').split(',');
        item.conditions = items.map((e) => this.project.scale + ' ' + e);
        return item;
      });

      this.calculatedProjectDoc();
    },
    handleCancel() {
      this.$router.push({
        name: 'createProject',
        params: { project: this.project },
      });
    },
    handleSubmit() {
      this.project.id_applicant = 1;
      console.log(this.project);

      // make form data because we got file
      const formData = new FormData();

      // eslint-disable-next-line no-undef
      _.each(this.project, (value, key) => {
        formData.append(key, value);
      });

      this.$refs.project.validate(valid => {
        if (valid) {
          if (this.project.id !== undefined) {
            // update
            projectResource.updateMultipart(this.project.id, formData).then(response => {
              const { data } = response;
              this.saveSupportDocs(data.id);
              this.$message({
                type: 'success',
                message: 'Project info has been updated successfully',
                duration: 5 * 1000,
              });
              this.$router.push('/project');
            }).catch(error => {
              console.log(error);
            });
          } else {
            projectResource
              .store(formData)
              .then((response) => {
                // save supportdocs
                const { data } = response;

                this.saveSupportDocs(data.id);
                this.$message({
                  message:
                    'New Project ' +
                    this.project.project_title +
                    ' has been created successfully.',
                  type: 'success',
                  duration: 5 * 1000,
                });
                this.$router.push('/project');
              })
              .catch((error) => {
                console.log(error);
              });
          }
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    async saveSupportDocs(id_project){
      this.project.listSupportDoc.forEach(element => {
        element.id_project = id_project;

        // make form data because we got file
        const formData = new FormData();

        // eslint-disable-next-line no-undef
        _.each(element, (value, key) => {
          formData.append(key, value);
        });
        if (element.id === undefined){
          supportDocResource.store(formData);
        } else {
          supportDocResource.updateMultipart(element.id, formData);
        }
      });
    },
    async updateList() {
      await this.getKabKotName(this.project.id_district);
      this.list = [
        {
          param: 'Nama Kegiatan',
          value: this.project.project_title,
        },
        {
          param: 'Bidang Usaha/Kegiatan',
          value: this.project.field,
        },
        {
          param: 'Skala/Besaran',
          value: this.project.scale + ' ' + this.project.scale_unit,
        },
        {
          param: 'Alamat',
          value: this.project.address,
        },
        {
          param: 'Pemrakarsa',
          value: this.project.initiatorData.name,
        },
        {
          param: 'Penanggung Jawab',
          value: this.project.initiatorData.pic,
        },
        {
          param: 'Alamat Pemrakarsa',
          value: this.project.initiatorData.address,
        },
        {
          param: 'No. Telp Pemrakarsa',
          value: this.project.initiatorData.phone,
        },
        {
          param: 'Email Pemrakarsa',
          value: this.project.initiatorData.email,
        },
        {
          param: 'Provinsi/Kota',
          value: this.kabkot,
        },
      ];
    },
  },
};
</script>
