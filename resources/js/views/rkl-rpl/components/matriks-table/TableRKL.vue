<template>
  <div>
    <div
      class="filter-container"
      style="display: flex; justify-content: space-between"
    >
      <div>
        <el-button
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
      <el-upload
        :loading="loadingMap"
        class="filter-item upload-demo"
        style="font-size: 0.8rem"
        :auto-upload="false"
        :on-change="handleUploadChange"
      >
        Unggah Peta
      </el-upload>
    </div>
    <el-table
      v-loading="loading"
      :data="list"
      :span-method="arraySpanMethod"
      :row-class-name="tableRowClassName"
      fit
      border
      highlight-current-row
      :header-cell-style="{
        background: 'rgb(58, 176, 111)',
        color: 'white',
        textAlign: 'center',
        border: '0px',
      }"
      style="font-size: 12px"
    >
      <el-table-column label="No" width="50px">
        <template slot-scope="scope">
          <span
            v-if="
              scope.row.type === 'subtitle' || scope.row.type === 'master-title'
            "
          >
            {{ scope.row.no }}
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
              <div class="comment-card">
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
                      @change="
                        handleCheckedComment(
                          list[scope.$index - 1].comments[index].id
                        )
                      "
                    />
                  </div>
                  <div class="comment-body">{{ com.description }}</div>
                  <div
                    v-if="
                      list[scope.$index - 1].comments[index].replies.id !== null
                    "
                    class="comment-header reply"
                  >
                    <div>
                      <p>Catatan Balasan Penyusun</p>
                      <p>
                        {{
                          list[scope.$index - 1].comments[index].replies
                            .created_at
                        }}
                      </p>
                    </div>
                  </div>
                  <div
                    v-if="
                      list[scope.$index - 1].comments[index].replies.id !== null
                    "
                    class="comment-body reply"
                  >
                    {{
                      list[scope.$index - 1].comments[index].replies.description
                    }}
                  </div>
                  <div
                    v-if="
                      list[scope.$index - 1].comments[index].replies.id === null
                    "
                    class="comment-reply"
                  >
                    <el-input
                      v-model="
                        list[scope.$index - 1].comments[index].replies
                          .description
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
                            list[scope.$index - 1].comments[index].replies
                              .description
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

      <el-table-column label="Jenis Dampak yang Timbul">
        <template slot-scope="scope">
          <span
            :class="{
              'row-title': Boolean(scope.row.type === 'title'),
            }"
          >
            {{ scope.row.name }}
          </span>
        </template>
      </el-table-column>

      <el-table-column label="Sumber Dampak">
        <template slot-scope="scope">
          <el-input
            v-if="scope.row.type == 'subtitle'"
            v-model="scope.row.impact_source"
            type="textarea"
            :rows="2"
          />
          <span v-else>{{ '' }}</span>
        </template>
      </el-table-column>

      <el-table-column
        label="Indikator Keberhasilan Pengelolaan Lingkungan Hidup"
      >
        <template slot-scope="scope">
          <el-input
            v-if="scope.row.type == 'subtitle'"
            v-model="scope.row.success_indicator"
            type="textarea"
            :rows="2"
          />
          <span v-else>{{ '' }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Bentuk Pengelolaan Lingkungan Hidup">
        <template slot-scope="scope">
          <el-input
            v-if="scope.row.type == 'subtitle'"
            v-model="scope.row.form"
            type="textarea"
            :rows="2"
          />
          <span v-else>{{ '' }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Lokasi Pengelolaan Lingkungan Hidup">
        <template slot-scope="scope">
          <el-input
            v-if="scope.row.type == 'subtitle'"
            v-model="scope.row.location"
            type="textarea"
            :rows="2"
          />
          <span v-else>{{ '' }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Periode Pengelolaan Lingkungan Hidup">
        <template slot-scope="scope">
          <el-input
            v-if="scope.row.type == 'subtitle'"
            v-model="scope.row.period"
            type="textarea"
            :rows="2"
          />
          <span v-else>{{ '' }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Institusi Pengelolaan Lingkungan Hidup">
        <template slot-scope="scope">
          <el-input
            v-if="scope.row.type == 'subtitle'"
            v-model="scope.row.institution"
            type="textarea"
            :rows="2"
          />
          <span v-else>{{ '' }}</span>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const rklResource = new Resource('matriks-rkl');

export default {
  name: 'TableRKL',
  data() {
    return {
      list: [],
      lastTime: null,
      loadingMap: false,
      loading: false,
      loadingSubmit: false,
      loadingSubmitComment: false,
      idProject: this.$route.params.id,
      selectedImpactCommentId: null,
      impactComment: null,
      impactColumnType: null,
      userInfo: {},
      kolom: [
        {
          label: 'Jenis Dampak yang Timbul',
          value: 'Jenis Dampak yang Timbul',
        },
        {
          label: 'Sumber Dampak',
          value: 'Sumber Dampak',
        },
        {
          label: 'Indikator Keberhasilan Pengelolaan',
          value: 'Indikator Keberhasilan Pengelolaan',
        },
        {
          label: 'Bentuk Pengelolaan Lingkungan Hidup',
          value: 'Bentuk Pengelolaan Lingkungan Hidup',
        },
        {
          label: 'Lokasi Pengelolaan Lingkungan Hidup',
          value: 'Lokasi Pengelolaan Lingkungan Hidup',
        },
        {
          label: 'Periode Pengelolaan Lingkungan Hidup',
          value: 'Periode Pengelolaan Lingkungan Hidup',
        },
        {
          label: 'Institusi Pengelolaan Lingkungan Hidup',
          value: 'Institusi Pengelolaan Lingkungan Hidup',
        },
      ],
    };
  },
  created() {
    this.getRKL();
    this.getLastTimeRKL();
    this.getUserInfo();
  },
  methods: {
    async getRKL() {
      this.loading = true;
      this.list = await rklResource.list({
        idProject: this.idProject,
      });
      this.loading = false;
    },
    async getLastTimeRKL() {
      this.lastTime = await rklResource.list({
        lastTime: 'true',
        idProject: this.idProject,
      });
    },
    async handleUploadChange(file, fileList) {
      const formData = new FormData();
      formData.append('idProject', this.idProject);
      formData.append('map_file', file.raw);
      formData.append('map', 'true');
      this.loadingMap = true;
      await rklResource.store(formData);
      this.loadingMap = false;
      this.$message({
        message: 'Data is saved Successfully',
        type: 'success',
        duration: 5 * 1000,
      });
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      const sendForm = this.list.filter((com) => com.type === 'subtitle');
      const time = await rklResource.store({
        manage: sendForm,
        type: this.lastTime ? 'update' : 'new',
      });
      this.loadingSubmit = false;
      this.lastTime = time;
      this.getRKL();
      this.$message({
        message: 'Data is saved Successfully',
        type: 'success',
        duration: 5 * 1000,
      });
    },
    async handleSubmitComment() {
      this.loadingSubmitComment = true;

      const newComment = await rklResource.store({
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
      const newCommentReply = await rklResource.store({
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

      this.list[indexImpact].comments[indexComment].replies = newCommentReply;
    },
    async handleCheckedComment(id) {
      await rklResource.store({
        type: 'checked-comment',
        id,
      });
    },
    async getUserInfo() {
      this.userInfo = await this.$store.dispatch('user/getInfo');
    },
    arraySpanMethod({ row, column, rowIndex, columnIndex }) {
      if (row.type === 'title' && columnIndex === 1) {
        return [1, 7];
      }

      if (row.type === 'master-title' && columnIndex === 0) {
        return [1, 8];
      }

      if (row.type === 'comments' && columnIndex === 0) {
        return [1, 8];
      }
    },
    tableRowClassName({ row, rowIndex }) {
      if (row.type === 'master-title') {
        return 'master-title';
      }
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
.master-title {
  background: #81de69 !important;
  color: white;
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
.upload-demo {
  font-size: 0.8rem;
  background: #e2cd39;
  padding: 8px;
  color: white;
  border-radius: 3px;
}
</style>
