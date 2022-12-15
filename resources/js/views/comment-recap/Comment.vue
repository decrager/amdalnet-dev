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
    <!-- Jika TUK dan ada response -->
    <td v-if="checktuk && anyResponse" @dblclick="handleDoubleClickResponse()">
      <div v-if="!enableResponseInput">
        <h3>Halaman di perbaiki: {{ pageFix }}</h3>
        <div v-html="response" />
      </div>
      <div v-else>
        <el-input v-model="pageFix" placeholder="Halaman Perbaikan" />
        <TextEditor v-model="response" />
      </div>
    </td>
    <!-- Jika Bukan TUK maka tampilkan semua -->
    <td v-if="!checktuk" @dblclick="handleDoubleClickResponse()">
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
      <el-button v-if="checktuk && !(response != null)" rounded type="danger" @click="handleDelete()">Delete</el-button>
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
    id: {
      type: Number,
      default: null,
    },
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
      enableSuggestInput: false,
      enableResponseInput: false,
      enableSaveButton: false,
    };
  },
  computed: {
    anyResponse() {
      console.log({ HALO: 'ASDASD' });
      console.log({ ANY_RESPONSE: this.comments.some((val) => val.response != null) });
      return this.comments.some((val) => val.response != null);
    },
  },
  watch: {

  },
  mounted() {
    console.log({ google: this.checktuk && (this.response != null) });
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
        id: this.id,
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
      this.$emit('handleDeleteComment', this.id);
    },
    handleDoubleClickSuggest() {
      if (!this.anyResponse) {
        if (this.checktuk) {
          this.toggleSuggestInput();
          this.enableSaveButton = true;
        }
      }
    },
    handleDoubleClickResponse() {
      if (!this.checktuk) {
        this.toggelResponseInput();
        this.enableSaveButton = true;
      }
    },
  },
};
</script>
