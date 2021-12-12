const penyusun = {
  namespace: true,
  state: {
    jumlahAnggota: [],
  },
  mutations: {
    ADD_TO_TABLE(state, { name, expertise, reg_no, membership_status, cv_file }) {
      state.jumlahAnggota.push({ name, expertise, reg_no, membership_status, cv_file });
    },
  },
  actions: {
    addPenyusunToTable({ commit }, { name, expertise, reg_no, membership_status, cv_file }) {
      commit('ADD_TO_TABLE', { name, expertise, reg_no, membership_status, cv_file });
    },
  },
};

export default penyusun;
