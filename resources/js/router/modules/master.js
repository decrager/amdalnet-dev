import Layout from '@/layout';

const masterRoutes = {
  path: '/master-data',
  component: Layout,
  alwaysShow: true,
  meta: {
    title: 'masterData',
    icon: 'cube',
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
      path: 'instansi-pemerintah',
      component: () => import('@/views/government-institution/index'),
      name: 'governmentInstitution',
      meta: { title: 'Instansi Pemerintah', icon: 'documentation', noCache: true },
    },
    {
      path: 'anggota-tuk',
      component: () => import('@/views/employee/index'),
      name: 'employeeTuk',
      meta: { title: 'Daftar Anggota TUK', icon: 'documentation', noCache: true },
    },
    {
      path: 'anggota-tuk/create',
      component: () => import('@/views/employee/Create'),
      name: 'createEmployeeTuk',
      hidden: true,
      meta: { title: 'Tambah Anggota TUK', icon: 'documentation', noCache: true },
    },
    {
      path: 'anggota-tuk/edit/:id',
      component: () => import('@/views/employee/Create'),
      name: 'editEmployeeTuk',
      hidden: true,
      meta: { title: 'Edit Anggota TUK', icon: 'documentation', noCache: true },
    },
    {
      path: 'materi-kebijakan/materi/create',
      component: () => import('@/views/materi/Create'),
      name: 'addMateri',
      hidden: true,
      meta: { title: 'Tambah Materi', icon: 'documentation', noCache: true },
    },
    {
      path: 'materi-kebijakan/materi/edit',
      component: () => import('@/views/materi/Edit'),
      name: 'editMateri',
      hidden: true,
      meta: { title: 'Edit Materi', icon: 'documentation', noCache: true },
    },
    {
      path: 'materi-kebijakan/peraturan/create',
      component: () => import('@/views/peraturan/Create'),
      name: 'addPeraturan',
      hidden: true,
      meta: { title: 'Tambah Peraturan', icon: 'documentation', noCache: true },
    },
    {
      path: 'materi-kebijakan/peraturan/create',
      component: () => import('@/views/peraturan/Edit'),
      name: 'DaftarIzin',
      hidden: true,
      meta: { title: 'Ubah Peraturan', icon: 'documentation', noCache: true },
    },
    {
      path: 'materi-kebijakan/kebijakan/create',
      component: () => import('@/views/kebijakan/Create'),
      name: 'addKebijakan',
      hidden: true,
      meta: { title: 'Tambah Kebijakan', icon: 'documentation', noCache: true },
    },
    {
      path: 'materi-kebijakan/kebijakan/edit',
      component: () => import('@/views/kebijakan/Edit'),
      name: 'editKebijakan',
      hidden: true,
      meta: { title: 'Edit Kebijakan', icon: 'documentation', noCache: true },
    },
    {
      path: 'master-data/materi-kebijakan',
      component: () => import('@/views/materi-kebijakan/index'),
      name: 'MateriDanKebijakan',
      meta: { title: 'Materi dan Kebijakan', icon: 'documentation', noCache: true },
    },
    {
      path: 'daftar-izin',
      component: () => import('@/views/master-izin/index'),
      name: 'DaftarIzins',
      meta: { title: 'Daftar Izin', icon: 'documentation', noCache: true },
    },
    {
      path: 'daftar-izin/create',
      component: () => import('@/views/master-izin/Create'),
      name: 'AddIzin',
      hidden: true,
      meta: { title: 'Tambah Izin Baru', icon: 'documentation', noCache: true },
    },
    {
      path: 'daftar-izin/edit',
      component: () => import('@/views/master-izin/Edit'),
      name: 'EditIzin',
      hidden: true,
      meta: { title: 'Ubah Izin', icon: 'documentation', noCache: true },
    },
    {
      path: 'template-persetujuan',
      component: () => import('@/views/template-persetujuan/index'),
      name: 'DaftarPersetujuan',
      meta: { title: 'Persetujuan Lingkungan', icon: 'documentation', noCache: true },
    },
    {
      path: 'template-persetujuan/create',
      component: () => import('@/views/template-persetujuan/Create'),
      name: 'AddDaftarPersetujuan',
      hidden: true,
      meta: { title: 'Tambah Persetujuan Lingkungan', icon: 'documentation', noCache: true },
    },
    {
      path: 'template-persetujuan/edit',
      component: () => import('@/views/template-persetujuan/Edit'),
      name: 'EditDaftarPersetujuan',
      hidden: true,
      meta: { title: 'Edit Persetujuan Lingkungan', icon: 'documentation', noCache: true },
    },
    {
      path: 'template-ukl-upl',
      component: () => import('@/views/template-ukl-menengah/index'),
      name: 'UklRendah',
      meta: { title: 'UKL-UPL', icon: 'documentation', noCache: true },
    },
    {
      path: 'template-ukl-upl/create',
      component: () => import('@/views/template-ukl-menengah/Create'),
      name: 'AddUklMenengah',
      hidden: true,
      meta: { title: 'Tambah UKL-UPL', icon: 'documentation', noCache: true },
    },
    {
      path: 'template-ukl-upl/edit',
      component: () => import('@/views/template-ukl-menengah/Edit'),
      name: 'EditUklMenengah',
      hidden: true,
      meta: { title: 'Edit UKL-UPL', icon: 'documentation', noCache: true },
    },
  ],
};

export default masterRoutes;
