import Layout from '@/layout';

const announcementRoutes = {
  path: '/lpjp-team',
  component: Layout,
  redirect: '/lpjp-team',
  alwaysShow: false,
  meta: { title: 'lpjp-team', icon: 'zip', roles: ['lpjp'] },
  children: [
    // {
    //   path: '',
    //   component: () => import('@/views/lpjp-team'),
    //   name: 'listLpjpTeam',
    //   meta: { title: 'listLpjpTeam', icon: 'documentation' },
    // },
    {
      path: ':id(\\d+)/daftar-anggota',
      component: () =>
        import('@/views/independent-formulator-team/member/Create'),
      name: 'listLpjpTeam',
      hidden: true,
      meta: {
        title: 'Daftar Anggota LPJP',
        icon: 'documentation',
        noCache: true,
      },
    },
    {
      path: ':id(\\d+)/anggota-penyusun',
      component: () => import('@/views/lpjp/Member'),
      name: 'lpjpFormulatorMember',
      hidden: true,
      meta: {
        title: 'Daftar Penyusun LPJP',
        icon: 'documentation',
        noCache: true,
      },
    },
  ],
};

export default announcementRoutes;
