import Layout from '@/layout';

const businessRoutes = {
  path: '/business',
  component: Layout,
  redirect: '/business',
  alwaysShow: true,
  hidden: true,
  meta: { title: 'KBLI', icon: 'zip' },
  children: [
    {
      path: '',
      component: () => import('@/views/business/index'),
      name: 'ListBusiness',
      hidden: false,
      meta: { title: 'Master KBLI', icon: 'documentation' },
    },
    {
      path: ':id(\\d+)',
      component: () => import('@/views/business/param/index'),
      name: 'ListBusinessEnvParam',
      hidden: false,
      meta: { title: 'Master Parameter KBLI', icon: 'documentation' },
    },
  ],
};

export default businessRoutes;
