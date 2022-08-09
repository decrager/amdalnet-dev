import Layout from '@/layout';

const tukRoutes = {
  path: '/tuk-profile',
  component: Layout,
  meta: {
    title: 'Profil TUK',
    icon: 'zip',
    permissions: ['view menu tuk profile'],
  },
  children: [
    {
      path: '',
      component: () => import('@/views/tuk-management/Profile.vue'),
      name: 'tukProfile',
      meta: {
        title: 'Profil TUK',
        icon: 'user',
        noCache: true,
        permissions: ['view menu tuk profile'],
      },
    },
    {
      path: 'create/:id(\\d+)/secretary-member/',
      component: () =>
        import('@/views/tuk-management/components/SecretaryMemberForm.vue'),
      name: 'createTukSecretaryMember',
      hidden: true,
      meta: {
        title: 'Tambah Anggota Sekretariat TUK',
        icon: 'user',
        noCache: true,
        permissions: ['manage tuk profile'],
      },
    },
    {
      path: 'edit/:id(\\d+)/secretary-member/',
      component: () =>
        import('@/views/tuk-management/components/SecretaryMemberForm.vue'),
      name: 'editTukSecretaryMember',
      hidden: true,
      meta: {
        title: 'Edit Anggota Sekretariat TUK',
        icon: 'user',
        noCache: true,
        permissions: ['manage tuk profile'],
      },
    },
  ],
};

export default tukRoutes;
