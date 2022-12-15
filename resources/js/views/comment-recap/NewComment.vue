<template>
  <tr>
    <td>{{ nextComment }}</td>
    <!-- Jika TUK dan sudah ada response lakukan colspan-->
    <td v-if="checktuk && anyResponses" colspan="2" @dblclick="handleDoubleClickSuggest()">
      <div v-if="checktuk">
        <el-input v-model="page" name="halaman" placeholder="Halaman" />
        <TextEditor v-model="suggest" />
      </div>
    </td>
    <!-- Jika TUK dan belum ada response -->
    <td v-if="checktuk && !anyResponses" colspan="2" @dblclick="handleDoubleClickSuggest()">
      <div v-if="checktuk">
        <el-input v-model="page" name="halaman" placeholder="Halaman" />
        <TextEditor v-model="suggest" />
      </div>
    </td>
    <!-- Jika bukan TUK-->
    <td v-if="!checktuk" @dblclick="handleDoubleClickResponse()">
      <div v-if="!checktuk">
        <el-input v-model="pageFix" placeholder="Halaman Perbaikan" />
        <TextEditor v-model="response" />
      </div>
    </td>
    <td>
      <el-button rounded :disabled="!enableAddButton" type="primary" @click="handleAdd()">Add</el-button>
    </td>
  </tr>
</template>
<script>
import TextEditor from '@/components/Tinymce';

export default {
  components: {
    TextEditor,
  },
  props: {
    nextComment: {
      type: Number,
      default: null,
    },
    checktuk: {
      type: Boolean,
      default: null,
    },
    comments: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      page: null,
      suggest: null,
      pageFix: null,
      response: null,
      enableSuggestInput: false,
      enableResponseInput: false,
    };
  },
  computed: {
    enableAddButton() {
      if ((this.suggest && this.page) || (this.pageFix && this.response)) {
        return true;
      } else {
        return false;
      }
    },
    anyResponses() {
      return this.comments.some((val) => val.response != null);
    },
  },
  watch: {},
  mounted() {},
  methods: {
    handleAdd() {
      const newComment = {
        no: this.nextComment,
        page: this.page,
        suggest: this.suggest,
        pageFix: this.pageFix,
        response: this.response,
      };
      this.enableSuggestInput = false;
      this.enableResponseInput = false;
      this.suggest = null;
      this.response = null;
      this.page = null;
      this.pageFix = null;
      this.$emit('handleAddComment', newComment);
    },
    toggleSuggestInput() {
      this.enableSuggestInput = !this.enableSuggestInput;
    },
    toggelResponseInput() {
      this.enableResponseInput = !this.enableResponseInput;
    },
    handleDoubleClickSuggest() {
      this.toggleSuggestInput();
    },
    handleDoubleClickResponse() {
      this.toggelResponseInput();
    },
  },
};
</script>
