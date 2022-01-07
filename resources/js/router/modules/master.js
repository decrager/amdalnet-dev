import Layout from '@/layout';

const masterRoutes = {
  path: '/master-data',
  component: Layout,
  alwaysShow: true,
  meta: {
    title: 'masterData',
    icon: 'el-icon-reading',
    roles: ['admin-standard'],
  },
  children: [
    {
      path: '/provinces',
      component: () => import('@/views/master-data/province'),
      name: 'province',
      hidden: true,
      meta: { title: 'provinsi', icon: 'el-icon-school' },
    },
    {
      path: 'lpjp',
      component: () => import('@/views/lpjp/index'),
      name: 'lpjp',
      meta: { title: 'LPJP', icon: 'documentation', noCache: true },
    },
    {
      path: 'lpjp/create',
      component: () => import('@/views/lpjp/Create'),
      name: 'createLpjp',
      hidden: true,
      meta: { title: 'Tambah LPJP', icon: 'documentation', noCache: true },
    },
    {
      path: 'lpjp/edit/:id',
      component: () => import('@/views/lpjp/Create'),
      name: 'editLpjp',
      hidden: true,
      meta: { title: 'Edit LPJP', icon: 'documentation', noCache: true },
    },
    {
      path: 'rona-awal',
      component: () => import('@/views/rona-awal/index'),
      name: 'rona-awal',
      meta: { title: 'Rona-Awal', icon: 'documentation', noCache: true },
    },
    {
      path: 'rona-awal/create',
      component: () => import('@/views/lpjp/Create'),
      name: 'createRonaAwal',
      hidden: true,
      meta: {
        title: 'Tambah Rona Lingkungan',
        icon: 'documentation',
        noCache: true,
      },
    },
    {
      path: 'component',
      component: () => import('@/views/component/index'),
      name: 'component',
      meta: { title: 'Komponen', icon: 'documentation', noCache: true },
    },
    {
      path: 'formulator',
      component: () => import('@/views/formulator/index'),
      name: 'formulator',
      meta: {
        title: 'Penyusun',
        icon: 'documentation',
        noCache: true,
      },
    },
    {
      path: 'formulator/create',
      component: () => import('@/views/formulator/Create'),
      name: 'createFormulator',
      hidden: true,
      meta: { title: 'Tambah Penyusun', icon: 'documentation', noCache: true },
    },
    {
      path: 'formulator/edit/:id',
      component: () => import('@/views/formulator/Create'),
      name: 'editFormulator',
      hidden: true,
      meta: { title: 'Edit Penyusun', icon: 'documentation', noCache: true },
    },
    {
      path: 'bank-ahli',
      component: () => import('@/views/expert-bank/index'),
      name: 'expertBank',
      meta: { title: 'expertBank', icon: 'documentation', noCache: true },
    },
    {
      path: 'bank-ahli/create',
      component: () => import('@/views/expert-bank/Create'),
      name: 'createExpertBank',
      hidden: true,
      meta: { title: 'Tambah Bank Ahli', icon: 'documentation', noCache: true },
    },
    {
      path: 'bank-ahli/edit/:id',
      component: () => import('@/views/expert-bank/Create'),
      name: 'editExpertBank',
      hidden: true,
      meta: { title: 'Edit Bank Ahli', icon: 'documentation', noCache: true },
    },
    {
      path: 'sop',
      component: () => import('@/views/master-sop/index'),
      name: 'sop',
      meta: { title: 'SOP', icon: 'documentation', noCache: true },
    },
    {
      path: 'sop/create',
      component: () => import('@/views/master-sop/Create'),
      name: 'createSop',
      hidden: true,
      meta: { title: 'Tambah SOP', icon: 'documentation', noCache: true },
    },
    {
      path: 'pegawai-tuk',
      component: () => import('@/views/employee/index'),
      name: 'employeeTuk',
      meta: { title: 'Pegawai TUK', icon: 'documentation', noCache: true },
    },
    {
      path: 'pegawai-tuk/create',
      component: () => import('@/views/employee/Create'),
      name: 'createEmployeeTuk',
      hidden: true,
      meta: { title: 'Tambah Pegawai TUK', icon: 'documentation', noCache: true },
    },
    {
      path: 'pegawai-tuk/edit/:id',
      component: () => import('@/views/employee/Create'),
      name: 'editEmployeeTuk',
      hidden: true,
      meta: { title: 'Edit Pegawai TUK', icon: 'documentation', noCache: true },
    },
    {
      path: 'materi',
      component: () => import('@/views/materi/index'),
      name: 'materi',
      meta: { title: 'Materi', icon: 'documentation', noCache: true },
    },
    {
      path: 'materi/create',
      component: () => import('@/views/materi/Create'),
      name: 'addMateri',
      hidden: true,
      meta: { title: 'Tambah Materi', icon: 'documentation', noCache: true },
    },
    {
      path: 'materi/edit',
      component: () => import('@/views/materi/Edit'),
      name: 'editMateri',
      hidden: true,
      meta: { title: 'Edit Materi', icon: 'documentation', noCache: true },
    },
    {
      path: 'peraturan',
      component: () => import('@/views/peraturan/index'),
      name: 'peraturan',
      meta: { title: 'Peraturan', icon: 'documentation', noCache: true },
    },
    {
      path: 'peraturan/create',
      component: () => import('@/views/peraturan/Create'),
      name: 'addPeraturan',
      hidden: true,
      meta: { title: 'Tambah Peraturan', icon: 'documentation', noCache: true },
    },
    {
      path: 'peraturan/create',
      component: () => import('@/views/peraturan/Edit'),
      name: 'editPeraturan',
      hidden: true,
      meta: { title: 'Ubah Peraturan', icon: 'documentation', noCache: true },
    },
    {
      path: 'kebijakan',
      component: () => import('@/views/kebijakan/index'),
      name: 'kebijakan',
      meta: { title: 'Kebijakan', icon: 'documentation', noCache: true },
    },
    {
      path: 'kebijakan/create',
      component: () => import('@/views/kebijakan/Create'),
      name: 'addKebijakan',
      hidden: true,
      meta: { title: 'Tambah Kebijakan', icon: 'documentation', noCache: true },
    },
    {
      path: 'kebijakan/edit',
      component: () => import('@/views/kebijakan/Edit'),
      name: 'editKebijakan',
      hidden: true,
      meta: { title: 'Edit Kebijakan', icon: 'documentation', noCache: true },
    },
    // {
    //   path: 'master-data/params',
    //   component: () => import('@/views/params/index'),
    //   name: 'Params',
    //   meta: { title: 'Parameter Aplikasi', icon: 'user', noCache: true },
    // },
    // {
    //   path: 'create-params',
    //   component: () => import('@/views/params/Create'),
    //   name: 'addParams',
    //   hidden: true,
    //   meta: { title: 'Tambah Parameter Aplikasi', icon: 'documentation', noCache: true },
    // },
    // {
    //   path: 'update-params',
    //   component: () => import('@/views/params/Create'),
    //   name: 'updateParams',
    //   hidden: true,
    //   meta: { title: 'Tambah Parameter Aplikasi', icon: 'documentation', noCache: true },
    // },
  ],
};

export default masterRoutes;
