import Layout from '@/layout';

const businessRoutes = {
  path: '/business',
  component: Layout,
  redirect: '/business',
  meta: { title: 'masterKbli', icon: 'zip', roles: ['admin-standard'] },
  children: [
    {
      path: '',
      component: () => import('@/views/business/index'),
      name: 'ListBusiness',
      meta: { title: 'masterKbli', icon: 'documentation', roles: ['admin-standard'] },
    },
    {
      path: ':id(\\d+)',
      component: () => import('@/views/business/param/index'),
      name: 'ListBusinessEnvParam',
      meta: { title: 'masterParameterKbli', icon: 'documentation', roles: ['admin-standard'] },
    },
  ],
};

export default businessRoutes;
