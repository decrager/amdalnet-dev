import Layout from '@/layout';

const masterRoutes = {
  path: '/master-data',
  component: Layout,
  redirect: '/master-data',
  alwaysShow: true,
  meta: {
    title: 'masterData',
    icon: 'el-icon-reading',
    permissions: ['view menu administrator'],
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
      path: 'tim-penyusun',
      component: () => import('@/views/formulator-team/index'),
      name: 'formulatorTeam',
      meta: {
        title: 'Tim Penyusun',
        icon: 'documentation',
        noCache: true,
      },
    },
    {
      path: 'tim-penyusun/create',
      component: () => import('@/views/formulator-team/Create'),
      name: 'createFormulatorTeam',
      hidden: true,
      meta: { title: 'Tambah Tim Penyusun', icon: 'documentation', noCache: true },
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
  ],
};

export default masterRoutes;
