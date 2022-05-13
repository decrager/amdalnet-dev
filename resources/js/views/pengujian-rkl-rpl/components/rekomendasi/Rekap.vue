<template>
  <div style="padding: 24px">
    <h3>Daftar Uji Kelayakan</h3>
    <el-table
      v-loading="loading"
      :data="list"
      fit
      highlight-current-row
      :header-cell-style="{ background: '#3AB06F', color: 'white' }"
    >
      <el-table-column label="No" width="50px">
        <template slot-scope="scope">
          <span>{{ scope.$index + 1 }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Peran">
        <template slot-scope="scope">
          <span>{{ scope.row.role }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Nama Anggota">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Nama Instansi">
        <template slot-scope="scope">
          <span>{{ scope.row.institution }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Waktu Input">
        <template slot-scope="scope">
          <span>{{ scope.row.input_time }}</span>
        </template>
      </el-table-column>

      <el-table-column type="expand">
        <template slot-scope="scope">
          <el-table
            :data="scope.row.detail"
            fit
            highlight-current-row
            :header-cell-style="{ background: '#3AB06F', color: 'white' }"
          >
            <el-table-column label="No." width="50px">
              <template slot-scope="detailScope">
                <span>{{ detailScope.$index + 1 }}</span>
              </template>
            </el-table-column>

            <el-table-column label="Kriteria Kelayakan">
              <template slot-scope="detailScope">
                <span v-html="detailScope.row.criteria" />
              </template>
            </el-table-column>

            <el-table-column label="Rekomendasi Ahli">
              <template slot-scope="detailScope">
                <span>{{ detailScope.row.recommendation }}</span>
              </template>
            </el-table-column>
          </el-table>
          <div>
            <h4>Kesimpulan</h4>
            <div>{{ scope.row.conclusion }}</div>
          </div>
        </template>
      </el-table-column>
    </el-table>
    <el-row v-loading="loadingRecap" :gutter="32">
      <el-col :sm="24" :md="24">
        <h4>Rekap Uji Kelayakan</h4>
        <Tinymce
          v-if="!recap.id"
          v-model="recap.recap"
          output-format="html"
          :menubar="''"
          :image="false"
          :toolbar="[
            'bold italic underline bullist numlist  preview undo redo fullscreen',
          ]"
          :height="200"
          :readonly="Boolean(recap.id)"
        />
        <div v-else v-html="recap.recap" />
        <p
          v-if="error > 0"
          style="color: #f56c6c; display: block; font-weight: bold"
        >
          Rekap Uji Kelayakan Wajib Diisi
        </p>
      </el-col>
    </el-row>
    <el-row v-loading="loadingRecap" :gutter="32" style="margin-top: 20px">
      <el-col :span="16" :offset="4" align="center">
        <div
          v-if="!recap.id"
          style="background-color: #e1e1e1; padding: 10px 0; margin-top: 5px"
        >
          <h3>Apakah Rencana Usaha dan/atau Kegiatan Dinyatakan Layak ?</h3>
          <div style="text-align: center">
            <el-button
              :loading="loadingAccept"
              type="primary"
              @click="acceptOrNot(true)"
            >
              Ya
            </el-button>
            <el-button
              :loading="loadingAccept"
              type="danger"
              @click="acceptOrNot(false)"
            >
              Tidak
            </el-button>
          </div>
        </div>
        <el-alert
          v-else
          :title="acceptedTitle"
          type="success"
          description="Terimakasih"
          show-icon
          center
          :closable="false"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';
import Resource from '@/api/resource';
const feasibilityTestResource = new Resource('feasibility-test');

export default {
  name: 'RekapUjiKelayakan',
  components: {
    Tinymce,
  },
  data() {
    return {
      list: [],
      recap: {},
      loading: false,
      loadingAccept: false,
      loadingRecap: false,
      acceptedTitle: '',
      error: 0,
    };
  },
  created() {
    this.getFeasibilityTest();
    this.getFinalRecap();
  },
  methods: {
    async getFinalRecap() {
      this.loadingRecap = true;
      this.recap = await feasibilityTestResource.list({
        finalRecap: 'true',
        idProject: this.$route.params.id,
      });

      if (this.recap.is_feasib) {
        this.acceptedTitle = 'Usaha dan/atau Kegiatan Telah Dinyatakan Layak';
      } else {
        this.acceptedTitle =
          'Usaha dan/atau Kegiatan Telah Dinyatakan Tidak Layak';
      }

      this.loadingRecap = false;
    },
    async getFeasibilityTest() {
      this.loading = true;
      const { tuk_member, tuk_secretary_member, tuk_invitations } =
        await feasibilityTestResource.list({
          recap: 'true',
          idProject: this.$route.params.id,
        });

      const tuk_member_final = tuk_member.map((x) => {
        const detail = x.feasibility_test[0].detail.map((y) => {
          return {
            criteria: y.eligibility.description,
            recommendation: y.expert_notes,
          };
        });

        return {
          role: x.position + ' Tim Uji kelayakan',
          name: x.luk_member ? x.luk_member.name : x.expert_bank.name,
          institution: x.luk_member
            ? x.luk_member.institution
            : x.expert_bank.institution,
          input_time: x.feasibility_test[0].input_time,
          conclusion: x.feasibility_test[0].conclusion,
          detail,
        };
      });

      const tuk_secretary_member_final = tuk_secretary_member.map((x) => {
        const detail = x.feasibility_test[0].detail.map((y) => {
          return {
            criteria: y.eligibility.description,
            recommendation: y.expert_notes,
          };
        });

        return {
          role: 'Anggota Sekretariat Tim Uji Kelayakan',
          name: x.name,
          institution: x.institution,
          input_time: x.feasibility_test[0].input_time,
          conclusion: x.feasibility_test[0].conclusion,
          detail,
        };
      });

      const tuk_invitations_final = tuk_invitations.invitations.map((x) => {
        const feasibility_test = tuk_invitations.data.find(
          (y) => y.email === x.email
        );
        const detail = feasibility_test.detail.map((y) => {
          return {
            criteria: y.eligibility.description,
            recommendation: y.expert_notes,
          };
        });

        return {
          role: x.role,
          name: x.name,
          institution: x.government_institution
            ? x.government_institution.name
            : x.institution,
          input_time: feasibility_test.input_time,
          conclusion: feasibility_test.conclusion,
          detail,
        };
      });

      this.list = [
        ...tuk_member_final,
        ...tuk_secretary_member_final,
        ...tuk_invitations_final,
      ];
      this.loading = false;
    },
    acceptOrNot(accept) {
      this.error = 0;

      if (this.recap.recap) {
        this.$confirm('Apakah anda yakin ?', 'Warning', {
          confirmButtonText: 'Ya',
          cancelButtonText: 'Tidak',
          type: 'warning',
        })
          .then(() => {
            this.loadingAccept = true;
            feasibilityTestResource
              .store({
                idProject: this.$route.params.id,
                recap: this.recap.recap,
                isFeasib: accept,
              })
              .then((data) => {
                this.getFinalRecap();
                this.loadingAccept = false;
                this.$emit('updateIsFeasib', { accept });
              })
              .catch(() => {
                this.loadingAccept = false;
              });
          })
          .catch(() => {
            this.loadingAccept = false;
          });
      } else {
        this.error++;
      }
    },
  },
};
</script>
