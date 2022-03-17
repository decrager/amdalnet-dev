import Layout from '@/layout';

const tukRoutes = {
  path: '/tuk-profile',
  component: Layout,
  meta: { title: 'Profil TUK', icon: 'zip', roles: ['examiner-secretary'] },
  children: [
    {
      path: '',
      component: () => import('@/views/tuk-management/Profile.vue'),
      name: 'tukProfile',
      meta: {
        title: 'Profil TUK',
        icon: 'user',
        noCache: true,
        roles: ['examiner-secretary'],
      },
    },
    {
      path: 'create/:id(\\d+)/secretary-member/',
      component: () => import('@/views/tuk-management/components/SecretaryMemberForm.vue'),
      name: 'createTukSecretaryMember',
      hidden: true,
      meta: {
        title: 'Tambah Anggota Sekretariat TUK',
        icon: 'user',
        noCache: true,
        roles: ['examiner-secretary'],
      },
    },
    {
      path: 'edit/:id(\\d+)/secretary-member/',
      component: () => import('@/views/tuk-management/components/SecretaryMemberForm.vue'),
      name: 'editTukSecretaryMember',
      hidden: true,
      meta: {
        title: 'Edit Anggota Sekretariat TUK',
        icon: 'user',
        noCache: true,
        roles: ['examiner-secretary'],
      },
    },
  ],
};

export default tukRoutes;
