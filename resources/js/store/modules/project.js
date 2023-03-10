import Resource from '@/api/resource';
const projectFieldResource = new Resource('project-fields');
const provinceResource = new Resource('provinces');
const districtResource = new Resource('districts');
const kbliResource = new Resource('business');
const kbliEnvParamResource = new Resource('kbli-env-params');
const ossProjectResource = new Resource('oss-projects');
const lpjpResource = new Resource('lpjp');
const formulatorResource = new Resource('formulators');
const projectResource = new Resource('projects');
// const SupportDocResource = new Resource('support-docs');

// const API_URL = '/api/projects';

const projects = {
  namespace: true,
  state: {
    projectOptions: [],
    projectFieldOptions: [],
    sectorOptions: [],
    provinceOptions: [],
    cityOptions: [],
    businessTypeOptions: [],
    listKbli: [],
    listLpjp: [],
    projectTypeOptions: [
      {
        value: 'Baru',
        label: 'Baru',
      },
      {
        value: 'Pengembangan',
        label: 'Pengembangan',
      },
    ],
    membershipOptions: [
      {
        value: 'ketua',
        label: 'Ketua',
      },
      {
        value: 'anggota',
        label: 'Anggota',
      },
      {
        value: 'ahli',
        label: 'Tenaga Ahli',
      },
    ],
    unitOptions: [],
    teamType: '',
    formulators: [],
    marking: null,
    loadingStatus: false,
    requiredDoc: null,
  },
  mutations: {
    SET_PROJECT_OPTIONS(state, payload) {
      state.projectOptions = payload;
    },
    SET_PROJECT_FIELD_OPTIONS(state, payload) {
      state.projectFieldOptions = payload;
    },
    SET_SECTOR_OPTIONS(state, payload) {
      state.sectorOptions = payload;
    },
    SET_PROVINCE_OPTIONS(state, payload) {
      state.provinceOptions = payload;
    },
    SET_CITY_OPTIONS(state, payload) {
      state.cityOptions = payload;
    },
    SET_BUSINESS_TYPE_OPTIONS(state, payload) {
      state.businessTypeOptions = payload;
    },
    SET_UNIT_OPTIONS(state, payload) {
      state.unitOptions = payload;
    },
    SET_LIST_KBLI(state, payload) {
      state.listKbli = payload;
    },
    SET_LIST_LPJP(state, payload) {
      state.listLpjp = payload;
    },
    SET_TEAM_TYPE(state, payload) {
      state.teamType = payload;
    },
    SET_FORMULATORS(state, payload) {
      state.formulators = payload;
    },
    LOADING_STATUS(state, payload) {
      state.loadingStatus = payload;
    },
    SET_MARKING(state, payload) {
      state.marking = payload;
    },
    SET_REQUIRED_DOC(state, payload) {
      state.requiredDoc = payload;
    },
  },
  actions: {
    async getMarking({ commit }, payload) {
      const project = await projectResource.get(payload);
      commit('SET_MARKING', project.marking);
    },
    async getRequiredDoc({ commit }, payload) {
      const project = await projectResource.get(payload);
      commit('SET_REQUIRED_DOC', project.required_doc);
    },
    async getProjectOss({ commit }) {
      const { data } = await ossProjectResource.list({});
      const option = data.map((i) => {
        return { value: i.json_content, label: i.json_content.nama_kegiatan };
      });
      commit('SET_PROJECT_OPTIONS', option);
    },
    async getProjectFields({ commit }) {
      const { data } = await projectFieldResource.list({});
      const option = data.map((i) => {
        return { value: i.id, label: i.name };
      });
      commit('SET_PROJECT_FIELD_OPTIONS', option);
    },
    async getProvinces({ commit }) {
      const { data } = await provinceResource.list({});
      const option = data.map((i) => {
        return { value: i.name, label: i.name };
      });
      commit('SET_PROVINCE_OPTIONS', option);
    },
    async getSectors({ commit }, payload) {
      const { data } = await kbliResource.list(payload);
      const option = data.map((i) => {
        return { value: i.value, label: i.value };
      });
      commit('SET_SECTOR_OPTIONS', option);
    },
    async getKblis({ commit }, payload) {
      const { data } = await kbliResource.list(payload);
      const option = data.map((i) => {
        return { value: i.value, label: i.value };
      });
      commit('SET_LIST_KBLI', option);
    },
    async getLpjp({ commit }) {
      const { data } = await lpjpResource.list({});
      const option = data.map((i) => {
        return { value: i.id, label: i.name };
      });
      commit('SET_LIST_LPJP', option);
    },
    async getDistricts({ commit }, payload) {
      const { data } = await districtResource.list(payload);
      const option = data.map((i) => {
        return { value: i.name, label: i.name };
      });
      commit('SET_CITY_OPTIONS', option);
    },
    async getFormulators({ commit }, payload) {
      const { data } = await formulatorResource.list(payload);
      const option = data.map((i) => {
        return { value: i.id, label: i.name };
      });
      commit('SET_FORMULATORS', option);
    },
    async getUnitByKbli({ commit }, payload) {
      const { data } = await kbliEnvParamResource.list(payload);
      const option = data.map((i) => {
        return { value: i.unit, label: i.unit };
      });
      commit('SET_UNIT_OPTIONS', option);
    },
    async getBusinessByKbli({ commit }, payload) {
      const { data } = await kbliEnvParamResource.list(payload);
      const option = data.map((i) => {
        return { value: i.param, label: i.param };
      });
      commit('SET_BUSINESS_TYPE_OPTIONS', option);
    },
    async getTeamType({ commit }, payload) {
      commit('SET_TEAM_TYPE', payload);
    },
  },
};

export default projects;
