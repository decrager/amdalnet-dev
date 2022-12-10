<template>
  <tr>
    <td>{{ no }}</td>
    <td @dblclick="handleDoubleClickSuggest()">
      <div v-if="!enableSuggestInput">
        <h3>Halaman: {{ page }}</h3>
        <div v-html="suggest" />
      </div>
      <div v-else>
        <el-input v-model="page" name="halaman" placeholder="Halaman" />
        <TextEditor v-model="suggest" />
      </div>
    </td>
    <td @dblclick="handleDoubleClickResponse()">
      <div v-if="!enableResponseInput">
        <h3>Halaman di perbaiki: {{ pageFix }}</h3>
        <div v-html="response" />
      </div>
      <div v-else>
        <el-input v-model="pageFix" placeholder="Halaman Perbaikan" />
        <TextEditor v-model="response" />
      </div>
    </td>
    <td>
      <el-button rounded :disabled="!enableSaveButton" type="primary" @click="handleSave()">Save</el-button>
      <el-button rounded type="danger" @click="handleDelete()">Delete</el-button>
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
    no: {
      type: Number,
      default: null,
    },
    page: {
      type: String,
      default: null,
    },
    suggest: {
      type: String,
      default: null,
    },
    pageFix: {
      type: String,
      default: null,
    },
    response: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      enableSuggestInput: false,
      enableResponseInput: false,
      enableSaveButton: false,
    };
  },
  computed: {

  },
  watch: {

  },
  mounted: {

  },
  methods: {
    toggleSuggestInput() {
      this.enableSuggestInput = !this.enableSuggestInput;
    },
    toggelResponseInput() {
      this.enableResponseInput = !this.enableResponseInput;
    },
    handleSave() {
      const comment = {
        no: this.no,
        page: this.page,
        suggest: this.suggest,
        pageFix: this.pageFix,
        response: this.response,
      };
      this.enableSaveButton = false;
      this.enableSuggestInput = false;
      this.enableResponseInput = false;
      this.$emit('handleSaveComment', comment);
    },
    handleDelete() {
      this.$emit('handleDeleteComment', this.no);
    },
    handleDoubleClickSuggest() {
      this.toggleSuggestInput();
      this.enableSaveButton = true;
    },
    handleDoubleClickResponse() {
      this.toggelResponseInput();
      this.enableSaveButton = true;
    },
  },
};
</script>
