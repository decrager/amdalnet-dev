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
      path: 'create',
      component: () => import('@/views/business/Create'),
      name: 'CreateBusiness',
      hidden: false,
      meta: { title: 'Tambah KBLI', icon: 'documentation' },
    },
    {
      path: 'edit/:id(\\d+)',
      component: () => import('@/views/business/Edit'),
      name: 'EditBusiness',
      hidden: false,
      meta: { title: 'Edit KBLI', icon: 'documentation' },
    },
    {
      path: ':id(\\d+)',
      component: () => import('@/views/business/param/index'),
      name: 'ListBusinessEnvParam',
      hidden: false,
      meta: { title: 'Master Parameter KBLI', icon: 'documentation' },
    },
    {
      path: ':id(\\d+)/create',
      component: () => import('@/views/business/param/Create'),
      name: 'CreateBusinessEnvParam',
      hidden: false,
      meta: { title: 'Tambah Parameter KBLI', icon: 'documentation' },
    },
    {
      path: ':id(\\d+)/edit/:idParam(\\d+)',
      component: () => import('@/views/business/param/Edit'),
      name: 'EditBusinessEnvParam',
      hidden: false,
      meta: { title: 'Edit Parameter KBLI', icon: 'documentation' },
    },
  ],
};

export default businessRoutes;
