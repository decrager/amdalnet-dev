<template>
  <div
    class="comment-container"
    @click.stop
  >
    <h4>MASUKAN/SARAN PERBAIKAN</h4>
    <div class="comment-list">
      <div class="comment-card">
        <el-card style="margin-bottom: 10px">
          <div class="comment-body" style="padding-top: 20px">
            <el-select
              v-model="columnType"
              placeholder="Pilih Kolom"
              style="width: 100%; margin-bottom: 10px"
            >
              <el-option
                v-for="item in column"
                :key="item.label"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
            <el-input
              v-model="comment"
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
        v-for="(com, index) in comments"
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
                comments[index].is_checked
              "
              @change="
                handleCheckedComment(
                  comments[index].id
                )
              "
            />
          </div>
          <div class="comment-body">{{ com.description }}</div>
          <div
            v-if="
              comments[index].replies.id !== null
            "
            class="comment-header reply"
          >
            <div>
              <p>Catatan Balasan Penyusun</p>
              <p>
                {{
                  comments[index].replies
                    .created_at
                }}
              </p>
            </div>
          </div>
          <div
            v-if="
              comments[index].replies.id !== null
            "
            class="comment-body reply"
          >
            {{
              comments[index].replies.description
            }}
          </div>
          <div
            v-if="
              comments[index].replies.id === null
            "
            class="comment-reply"
          >
            <el-input
              v-model="
                comments[index].replies
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
                    comments[index].id,
                    comments[index].replies
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

<script>
export default {
  name: 'CommentForm',
  props: {
    column: {
      type: Array,
      default: () => [],
    },
    comments: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      loadingSubmitComment: false,
      columnType: '',
    };
  },
  created() {

  },
  methods: {
    handleSubmitComment() {
      this.$emit('handleSubmitComment');
    },
    handleSubmitReply() {
      this.$emit('handleSubmitReply');
    },
    handleCheckedComment() {
      this.$emit('handleCheckedComment');
    },
  },
};
</script>

<style scoped>
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
