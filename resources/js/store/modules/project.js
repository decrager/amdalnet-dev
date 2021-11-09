import Resource from '@/api/resource';
const projectFieldResource = new Resource('project-fields');
const provinceResource = new Resource('provinces');
const districtResource = new Resource('districts');
const kbliResource = new Resource('kblis');
const kbliEnvParamResource = new Resource('kbli-env-params');
const ossProjectResource = new Resource('oss-projects');
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
    unitOptions: [],

    loadingStatus: false,
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
    LOADING_STATUS(state, payload) {
      state.loadingStatus = payload;
    },
  },
  actions: {
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
        return { value: i.id, label: i.name };
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
    async getDistricts({ commit }, payload) {
      const { data } = await districtResource.list(payload);
      const option = data.map((i) => {
        return { value: i.id, label: i.name };
      });
      commit('SET_CITY_OPTIONS', option);
    },
    async getUnitByKbli({ commit }, payload) {
      const { data } = await kbliEnvParamResource.list(payload);
      const option = data.map((i) => {
        return { value: i.unit, label: i.unit };
      });
      commit('SET_UNIT_OPTIONS', option);
    },
  },
};

export default projects;
