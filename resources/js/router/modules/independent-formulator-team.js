import Layout from '@/layout';

const formulatorTeamRoutes = {
  path: '/tim-penyusun-mandiri',
  component: Layout,
  redirect: '/tim-penyusun-mandiri',
  alwaysShow: true,
  meta: { title: 'Tim Penyusun Mandiri', icon: 'nested', roles: ['iniator'] },
  children: [
    {
      path: 'daftar-anggota',
      component: () =>
        import('@/views/independent-formulator-team/member/Create'),
      name: 'memberList',
      hidden: false,
      meta: {
        title: 'Daftar Anggota',
        icon: 'documentation',
        noCache: true,
      },
    },
    {
      path: 'tim-ahli',
      component: () =>
        import('@/views/independent-formulator-team/tim-ahli/Create'),
      name: 'timAhli',
      hidden: false,
      meta: {
        title: 'Tambah Tenaga Ahli',
        icon: 'documentation',
        noCache: true,
      },
    },
  ],
};

export default formulatorTeamRoutes;
