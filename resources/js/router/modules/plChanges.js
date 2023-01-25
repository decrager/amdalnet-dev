import Layout from '@/layout';
const plChangesRoutes = {
  path: '/perubahan_pl',
  component: Layout,
  redirect: 'https://amdalnet-dev.menlhk.go.id/perubahan_pl',
  meta: {
    title: 'Perubahan PL',
    icon: 'excel',
    permissions: [
      'view menu digital workspace',
      'view menu ukl upl adm',
      'view menu andal rkl rpl meeting invitation',
    ],
  },
  children: [
    {
      path: '/perubahan_pl',
      beforeEnter() {
        if (window.location.href.includes('amdalnet-dev')) {
          location.href = 'https://amdalnet-dev.menlhk.go.id/perubahan_pl';
        } else {
          location.href = 'https://amdalnet.menlhk.go.id/perubahan_pl';
        }
      },
      name: 'Perubahan PL',
      meta: { title: 'Perubahan PL', icon: 'excel' },
    },
  ],
};

export default plChangesRoutes;
