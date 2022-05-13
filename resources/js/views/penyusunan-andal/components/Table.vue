<template>
  <div>
    <div class="filter-container">
      <el-button
        v-if="isFormulator"
        :loading="loadingSubmit"
        class="filter-item"
        type="primary"
        style="font-size: 0.8rem"
        @click="handleSubmit"
      >
        {{ 'Simpan Perubahan' }}
      </el-button>
      <span style="font-size: 0.8rem">
        <em>{{ lastTime }}</em>
      </span>
    </div>
    <el-table
      v-loading="loading"
      :data="list"
      :span-method="arraySpanMethod"
      :row-class-name="tableRowClassName"
      border
      fit
      highlight-current-row
      :header-cell-style="{
        background: 'rgb(58, 176, 111)',
        color: 'white',
        textAlign: 'center',
        border: '0px',
      }"
      :cell-style="{
        verticalAlign: 'top',
      }"
      style="width: 100%; font-size: 12px"
    >
      <el-table-column label="Dampak Penting Hipotetik">
        <template slot-scope="scope">
          <span :class="{ 'row-title': scope.row.type === 'title' }">
            {{ scope.row.name }}
          </span>
          <div
            v-if="scope.row.type === 'subtitle'"
            class="btn-comment-container"
          >
            <el-button
              type="text"
              size="medium"
              @click.stop="showOrHideComment(scope.row.id)"
            >
              <i class="el-icon-s-comment" />
            </el-button>
          </div>
          <div
            v-if="scope.row.type === 'comments'"
            class="comment-container"
            @click.stop
          >
            <h4>MASUKAN/SARAN PERBAIKAN</h4>
            <div class="comment-list">
              <div v-if="!isFormulator" class="comment-card">
                <el-card style="margin-bottom: 10px">
                  <div class="comment-body" style="padding-top: 20px">
                    <el-select
                      v-model="impactColumnType"
                      placeholder="Pilih Kolom"
                      style="width: 100%; margin-bottom: 10px"
                    >
                      <el-option
                        v-for="item in kolom"
                        :key="item.label"
                        :label="item.label"
                        :value="item.value"
                      />
                    </el-select>
                    <el-input
                      v-model="impactComment"
                      type="textarea"
                      :rows="2"
                      placeholder="Tulis Masukan..."
                    />
                    <div class="send-btn-container">
                      <el-button
                        :loading="loadingSubmitComment"
                        type="primary"
                        size="mini"
                        @click="handleSubmitComment"
                      >
                        {{ 'Kirim' }}
                      </el-button>
                    </div>
                  </div>
                </el-card>
              </div>
              <div
                v-for="(com, index) in list[scope.$index - 1].comments"
                :key="com.id"
                class="comment-card"
              >
                <el-card style="margin-bottom: 10px">
                  <div class="comment-header">
                    <div>
                      <p>{{ com.user }}</p>
                      <p>{{ com.column_type }}</p>
                      <p>{{ com.created_at }}</p>
                    </div>
                    <el-checkbox
                      v-model="
                        list[scope.$index - 1].comments[index].is_checked
                      "
                      :disabled="!isFormulator"
                      @change="
                        handleCheckedComment(
                          list[scope.$index - 1].comments[index].id
                        )
                      "
                    />
                  </div>
                  <div class="comment-body">{{ com.description }}</div>
                  <div
                    v-for="rep in list[scope.$index - 1].comments[index]
                      .replies"
                    :key="rep.id"
                  >
                    <div class="comment-header reply">
                      <div>
                        <p>Catatan Balasan Penyusun</p>
                        <p>
                          {{ rep.created_at }}
                        </p>
                      </div>
                    </div>
                    <div class="comment-body reply">
                      {{ rep.description }}
                    </div>
                  </div>
                  <div v-if="isFormulator" class="comment-reply">
                    <el-input
                      v-model="
                        list[scope.$index - 1].comments[index].reply_desc
                      "
                      type="textarea"
                      :rows="2"
                      placeholder="Tulis Catatan Balasan..."
                    />
                    <div class="send-btn-container">
                      <el-button
                        type="primary"
                        size="mini"
                        @click="
                          handleSubmitReply(
                            list[scope.$index - 1].comments[index].id,
                            list[scope.$index - 1].comments[index].reply_desc
                          )
                        "
                      >
                        {{ 'Kirim' }}
                      </el-button>
                    </div>
                  </div>
                </el-card>
              </div>
            </div>
          </div>
        </template>
      </el-table-column>

      <!-- <el-table-column label="Komponen Lingkungan">
        <template slot-scope="scope">
          <span>{{ scope.row.component ? scope.row.component : '' }}</span>
        </template>
      </el-table-column> -->

      <el-table-column label="Komponen Rona Awal Lingkungan">
        <template slot-scope="scope">
          <span>{{ scope.row.ronaAwal ? scope.row.ronaAwal : '' }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Hasil Prakiraan Dampak">
        <el-table-column label="Besaran Dampak">
          <template slot-scope="scope">
            <el-input
              v-if="scope.row.component != undefined"
              v-model="scope.row.impact_size"
              :readonly="!isFormulator"
            />
            <span v-else>{{ '' }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Sifat Penting">
          <template slot-scope="scope">
            <div v-if="scope.row.component != undefined">
              <div
                v-for="(trait, index) in scope.row.important_trait"
                :key="trait.id"
              >
                <span>{{ index + 1 }}. {{ trait.description }}</span>
                <el-input
                  v-model="scope.row.important_trait[index].desc"
                  :readonly="!isFormulator"
                />
                <span>-Pilih Sifat Penting-</span>
                <el-radio-group
                  v-model="scope.row.important_trait[index].important_trait"
                  :disabled="!isFormulator"
                >
                  <el-radio label="+P">+P</el-radio>
                  <el-radio label="-P">-P</el-radio>
                  <el-radio label="+TP">+TP</el-radio>
                  <el-radio label="-TP">-TP</el-radio>
                </el-radio-group>
              </div>
            </div>
            <span v-else>{{ '' }}</span>
          </template>
        </el-table-column>
      </el-table-column>

      <el-table-column
        label="Perubahan Kondisi Dengan dan Tanpa Rencana Kegiatan"
      >
        <template slot-scope="scope">
          <div v-if="scope.row.component != undefined">
            <span>1. Kondisi Saat studi dilakukan</span>
            <el-input
              v-model="scope.row.studies_condition"
              type="textarea"
              :rows="2"
              :readonly="!isFormulator"
            />
            <span>
              2. A. Perkembangan Kondisi TANPA Adanya Rencana Kegiatan
            </span>
            <el-input
              v-model="scope.row.condition_dev_no_plan"
              type="textarea"
              :rows="2"
              :readonly="!isFormulator"
            />
            <span>
              &nbsp;&nbsp;&nbsp;B. Perkembangan Kondisi DENGAN Adanya Rencana
              Kegiatan
            </span>
            <el-input
              v-model="scope.row.condition_dev_with_plan"
              type="textarea"
              :rows="2"
              :readonly="!isFormulator"
            />
            <span> 3. Selisih Besaran Dampak </span>
            <el-input
              v-model="scope.row.impact_size_difference"
              type="textarea"
              :rows="2"
              :readonly="!isFormulator"
            />
          </div>
          <span v-else>{{ '' }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Jenis Dampak">
        <template slot-scope="scope">
          <el-select
            v-if="scope.row.component != undefined"
            v-model="scope.row.impact_type"
            placeholder="Pilih Jenis Dampak"
            style="width: 100%"
            :disabled="!isFormulator"
          >
            <el-option
              v-for="item in jenisDampak"
              :key="item.label"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
          <span v-else>{{ '' }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Hasil Evaluasi Dampak">
        <template slot-scope="scope">
          <span v-if="scope.row.component != undefined">{{
            dampakPentingConclusion(scope.row.important_trait)
          }}</span>
          <el-select
            v-if="scope.row.component != undefined"
            v-model="scope.row.impact_eval_result"
            placeholder="Pilih"
            style="width: 100%"
            :disabled="!isFormulator"
          >
            <el-option
              v-for="item in hasilEvaluasiDampak"
              :key="item.label"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
          <span v-else>{{ '' }}</span>
        </template>
      </el-table-column>

      <!-- <el-table-column label="Tanggapan" align="center">
        <template
          v-if="!Boolean(scope.row.component == undefined)"
          slot-scope="scope"
        >
          <el-button
            type="primary"
            size="small"
            @click.prevent="showFormTanggapan(scope.row.id)"
          >
            Tanggapan
          </el-button>
        </template>
      </el-table-column> -->
    </el-table>
    <!-- <TanggapanDialog
      :tanggapan="tanggapan"
      :show="show"
      @handleSubmitTanggapan="handleSubmitTanggapan($event)"
      @handleCancelTanggapan="handleCancelTanggapan"
    /> -->
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Resource from '@/api/resource';
const andalComposingResource = new Resource('andal-composing');
// import TanggapanDialog from '@/views/penyusunan-andal/components/TanggapanDialog.vue';

export default {
  name: 'TableAndal',
  components: {
    // TanggapanDialog,
  },
  data() {
    return {
      loadingSubmit: false,
      loadingSubmitComment: false,
      tanggapan: '',
      show: false,
      selectedTanggapanid: null,
      list: [],
      loading: false,
      idProject: this.$route.params.id,
      lastTime: null,
      selectedImpactCommentId: null,
      impactComment: null,
      impactColumnType: null,
      // userInfo: {},
      hasilEvaluasiDampak: [
        {
          label: 'Positif',
          value: 'Positif',
        },
        {
          label: 'Negatif',
          value: 'Negatif',
        },
      ],
      jenisDampak: [
        {
          label: 'Primer',
          value: 'Primer',
        },
        {
          label: 'Sekunder',
          value: 'Sekunder',
        },
        {
          label: 'Tersier',
          value: 'Tersier',
        },
      ],
      kolom: [
        {
          label: 'Dampak Penting Hipotetik',
          value: 'Dampak Penting Hipotetik',
        },
        {
          label: 'Komponen Rona Awal Lingkungan',
          value: 'Komponen Rona Awal Lingkungan',
        },
        {
          label: 'Besaran Dampak',
          value: 'Besaran Dampak',
        },
        {
          label: 'Sifat Penting',
          value: 'Sifat Penting',
        },
        {
          label: 'Perubahan Kondisi Dengan dan Tanpa Rencana Kegiatan',
          value: 'Perubahan Kondisi Dengan dan Tanpa Rencana Kegitan',
        },
        {
          label: 'Jenis Dampak',
          value: 'Jenis Dampak',
        },
        {
          label: 'Hasil Evaluasi Dampak',
          value: 'Hasil Evaluasi Dampak',
        },
      ],
    };
  },
  computed: {
    ...mapGetters({
      'userInfo': 'user',
      'userId': 'userId',
    }),
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
  },
  created() {
    this.getCompose();
    this.getLastTime();
    // this.getUserInfo();
  },
  methods: {
    async getCompose() {
      this.loading = true;
      this.list = await andalComposingResource.list({
        idProject: this.idProject,
      });
      this.loading = false;
    },
    async getLastTime() {
      this.lastTime = await andalComposingResource.list({
        lastTime: 'true',
        idProject: this.idProject,
      });
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      const sendForm = this.list.filter((com) => com.type === 'subtitle');
      const time = await andalComposingResource.store({
        analysis: sendForm,
        type: this.lastTime ? 'update' : 'new',
      });
      this.loadingSubmit = false;
      this.lastTime = time;
      await this.getCompose();
      this.$message({
        message: 'Data is saved Successfully',
        type: 'success',
        duration: 5 * 1000,
      });
    },
    async handleSubmitComment() {
      this.loadingSubmitComment = true;

      const newComment = await andalComposingResource.store({
        type: 'impact-comment',
        description: this.impactComment,
        id_impact_identification: this.selectedImpactCommentId,
        id_user: this.userInfo.id,
        column_type: this.impactColumnType,
      });

      const indexImpact = this.list.findIndex((ide) => {
        return (
          ide.id === this.selectedImpactCommentId && ide.type === 'subtitle'
        );
      });

      this.list[indexImpact].comments.unshift(newComment);

      this.loadingSubmitComment = false;
      this.impactComment = null;
      this.impactColumnType = null;
    },
    async handleSubmitReply(id, description) {
      const newCommentReply = await andalComposingResource.store({
        type: 'impact-comment-reply',
        description: description,
        id_comment: id,
        id_user: this.userInfo.id,
        id_impact_identification: this.selectedImpactCommentId,
      });

      const indexImpact = this.list.findIndex((ide) => {
        return (
          ide.id === this.selectedImpactCommentId && ide.type === 'subtitle'
        );
      });

      const indexComment = this.list[indexImpact].comments.findIndex(
        (com) => com.id === id
      );

      this.list[indexImpact].comments[indexComment].replies.push(
        newCommentReply
      );
      this.list[indexImpact].comments[indexComment].reply_desc = null;
    },
    async handleCheckedComment(id) {
      await andalComposingResource.store({
        type: 'checked-comment',
        id,
      });
    },
    // async getUserInfo() {
    //   this.userInfo = await this.$store.dispatch('user/getInfo');
    // },
    handleEditForm(id) {
      this.$emit('handleEditForm', id);
    },
    handleDelete(id, nama) {
      this.$emit('handleDelete', { id, nama });
    },
    arraySpanMethod({ row, column, rowIndex, columnIndex }) {
      if (row.type === 'title' && columnIndex === 0) {
        return [1, 7];
      }

      if (row.type === 'comments' && columnIndex === 0) {
        return [1, 7];
      }
    },
    tableRowClassName({ row, rowIndex }) {
      if (row.type === 'comments') {
        return `beige comment-${row.id} comment-hide`;
      }
      return '';
    },
    showOrHideComment(id) {
      this.selectedImpactCommentId = id;
      document.querySelectorAll('.beige').forEach((e) => {
        if (!e.classList.contains(`comment-${id}`)) {
          e.classList.add('comment-hide');
        }
      });
      if (
        document
          .querySelector(`.comment-${id}`)
          .classList.contains('comment-hide')
      ) {
        document
          .querySelector(`.comment-${id}`)
          .classList.remove('comment-hide');
      } else {
        document.querySelector(`.comment-${id}`).classList.add('comment-hide');
      }
    },
    dampakPentingConclusion(importantTrait) {
      if (importantTrait.length > 0) {
        const dampakPenting = importantTrait.filter((im) => {
          return im.important_trait === '+P' || im.important_trait === '-P';
        });
        if (dampakPenting.length > 0) {
          return 'Dampak Penting';
        } else {
          return 'Dampak Tidak Penting';
        }
      }

      return '';
    },
    // showFormTanggapan(id) {
    //   this.selectedTanggapanid = id;
    //   this.tanggapan = this.list.find((li) => li.id === id).response;
    //   this.show = true;
    // },
    // handleSubmitTanggapan({ tanggapan }) {
    //   const indexList = this.list.findIndex(
    //     (li) => li.id === this.selectedTanggapanid
    //   );
    //   this.list[indexList].response = tanggapan;
    //   this.$emit('handleSubmitTanggapan');
    //   this.show = false;
    //   this.selectedTanggapanid = null;
    //   this.tanggapan = '';
    // },
    // handleCancelTanggapan() {
    //   this.show = false;
    //   this.selectedTanggapanInd = null;
    // },
  },
};
</script>

<style>
.row-title {
  font-weight: bold;
}
.el-table .cell {
  word-break: break-word;
}
.comment-hide {
  display: none;
}
.btn-comment-container button {
  font-size: 20px;
}
.comment-card {
  width: 300px;
  margin-right: 30px;
  display: inline-block;
  margin-bottom: 13px;
  vertical-align: top;
}
.send-btn-container {
  text-align: right;
  margin-top: 10px;
}
.beige {
  background: beige !important;
}
.beige td {
  padding-top: 0 !important;
}
.comment-body {
  font-size: 15px;
  padding-right: 20px;
  padding-left: 20px;
  padding-bottom: 20px;
}
.comment-header {
  padding-top: 20px;
  padding-left: 20px;
  padding-right: 20px;
  display: flex;
  justify-content: space-between;
}
.comment-header p {
  font-size: 12px;
  padding: 0;
  margin: 0;
  margin-bottom: 1px;
  font-weight: lighter;
}
.comment-card .el-card__body {
  padding: 0 !important;
}
.comment-reply {
  background: #ceeccb;
  padding: 20px;
  border-top: 1px solid #ccc;
}
.comment-header.reply,
.comment-body.reply {
  background: #ceeccb;
}
</style>
